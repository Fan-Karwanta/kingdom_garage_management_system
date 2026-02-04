<?php

namespace App\Http\Controllers;

use App\AttendanceImport;
use App\AttendanceRecord;
use App\EmployeeSalary;
use App\EmployeeSchedule;
use App\LeaveRequest;
use App\PayrollDeduction;
use App\PayrollHoliday;
use App\PayrollPeriod;
use App\PayrollRecord;
use App\PayrollSetting;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Dashboard
    public function dashboard()
    {
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->count();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get current payroll period
        $currentPeriod = PayrollPeriod::where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->first();

        // Get attendance stats for current month
        $attendanceStats = AttendanceRecord::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->selectRaw('
                COUNT(*) as total_records,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_count,
                SUM(total_hours) as total_hours,
                SUM(overtime_hours) as overtime_hours
            ')
            ->first();

        // Get pending leave requests
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        // Get recent payroll periods
        $recentPayrolls = PayrollPeriod::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get monthly payroll totals for chart (last 6 months)
        $monthlyPayroll = PayrollPeriod::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTH(pay_date) as month, YEAR(pay_date) as year, SUM(total_net) as total')
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Get today's attendance
        $todayAttendance = AttendanceRecord::whereDate('date', Carbon::today())
            ->with('user')
            ->orderBy('clock_in', 'desc')
            ->take(10)
            ->get();

        // Calculate total payroll this month
        $totalPayrollThisMonth = PayrollPeriod::whereMonth('pay_date', $currentMonth)
            ->whereYear('pay_date', $currentYear)
            ->sum('total_net');

        return view('payroll.dashboard', compact(
            'employees',
            'currentPeriod',
            'attendanceStats',
            'pendingLeaves',
            'recentPayrolls',
            'monthlyPayroll',
            'todayAttendance',
            'totalPayrollThisMonth'
        ));
    }

    // Attendance Management
    public function attendanceList(Request $request)
    {
        if ($request->ajax()) {
            $query = AttendanceRecord::with('user')
                ->select('attendance_records.*');

            // Filter by date range
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Filter by employee
            if ($request->employee_id) {
                $query->where('user_id', $request->employee_id);
            }

            // Filter by status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->whereHas('user', function ($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('lastname', 'LIKE', "%{$searchValue}%");
                });
            }

            $filteredRecords = $query->count();

            $data = $query->orderBy('date', 'desc')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => AttendanceRecord::count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'employee' => $record->user ? $record->user->name . ' ' . $record->user->lastname : 'N/A',
                        'date' => $record->date->format('M d, Y'),
                        'clock_in' => $record->clock_in ? Carbon::parse($record->clock_in)->format('h:i A') : '-',
                        'clock_out' => $record->clock_out ? Carbon::parse($record->clock_out)->format('h:i A') : '-',
                        'total_hours' => number_format($record->total_hours, 2),
                        'overtime' => number_format($record->overtime_hours, 2),
                        'status' => '<span class="badge bg-' . $this->getStatusColor($record->status) . '">' . ucfirst($record->status) . '</span>',
                        'action' => $this->getAttendanceActions($record),
                    ];
                }),
            ];

            return response()->json($response);
        }

        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.attendance.list', compact('employees'));
    }

    public function attendanceCreate()
    {
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.attendance.add', compact('employees'));
    }

    public function attendanceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'nullable',
            'clock_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half-day,on-leave,holiday',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['source'] = 'manual';

        // Calculate hours if clock in/out provided
        if ($request->clock_in && $request->clock_out) {
            $clockIn = Carbon::parse($request->date . ' ' . $request->clock_in);
            $clockOut = Carbon::parse($request->date . ' ' . $request->clock_out);
            
            $data['clock_in'] = $clockIn;
            $data['clock_out'] = $clockOut;
            $data['total_hours'] = $clockOut->diffInMinutes($clockIn) / 60;
            
            // Calculate regular and overtime
            $settings = PayrollSetting::first();
            $regularHours = $settings ? $settings->regular_hours_per_day : 8;
            
            if ($data['total_hours'] > $regularHours) {
                $data['regular_hours'] = $regularHours;
                $data['overtime_hours'] = $data['total_hours'] - $regularHours;
            } else {
                $data['regular_hours'] = $data['total_hours'];
                $data['overtime_hours'] = 0;
            }
        }

        AttendanceRecord::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            $data
        );

        return redirect('/payroll/attendance')->with('message', 'Attendance record saved successfully');
    }

    public function attendanceEdit($id)
    {
        $attendance = AttendanceRecord::findOrFail($id);
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.attendance.edit', compact('attendance', 'employees'));
    }

    public function attendanceUpdate(Request $request, $id)
    {
        $attendance = AttendanceRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,late,half-day,on-leave,holiday',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        if ($request->clock_in && $request->clock_out) {
            $clockIn = Carbon::parse($request->date . ' ' . $request->clock_in);
            $clockOut = Carbon::parse($request->date . ' ' . $request->clock_out);
            
            $data['clock_in'] = $clockIn;
            $data['clock_out'] = $clockOut;
            $data['total_hours'] = $clockOut->diffInMinutes($clockIn) / 60;
            
            $settings = PayrollSetting::first();
            $regularHours = $settings ? $settings->regular_hours_per_day : 8;
            
            if ($data['total_hours'] > $regularHours) {
                $data['regular_hours'] = $regularHours;
                $data['overtime_hours'] = $data['total_hours'] - $regularHours;
            } else {
                $data['regular_hours'] = $data['total_hours'];
                $data['overtime_hours'] = 0;
            }
        }

        $attendance->update($data);

        return redirect('/payroll/attendance')->with('message', 'Attendance record updated successfully');
    }

    public function attendanceDelete($id)
    {
        AttendanceRecord::destroy($id);
        return redirect('/payroll/attendance')->with('message', 'Attendance record deleted successfully');
    }

    // CSV Import for Biometrics
    public function importAttendance()
    {
        $imports = AttendanceImport::orderBy('created_at', 'desc')->take(10)->get();
        return view('payroll.attendance.import', compact('imports'));
    }

    public function processImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $file = $request->file('csv_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/attendance'), $filename);

        $import = AttendanceImport::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'status' => 'processing',
            'imported_by' => Auth::id(),
        ]);

        // Process CSV
        $path = public_path('uploads/attendance/' . $filename);
        $handle = fopen($path, 'r');
        
        $header = fgetcsv($handle);
        $totalRecords = 0;
        $successfulRecords = 0;
        $failedRecords = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            $totalRecords++;
            
            try {
                // Expected CSV format: employee_id/biometric_id, date, clock_in, clock_out
                $employeeIdentifier = $row[0] ?? null;
                $date = $row[1] ?? null;
                $clockIn = $row[2] ?? null;
                $clockOut = $row[3] ?? null;

                // Find employee by ID or biometric_id
                $user = User::where('id', $employeeIdentifier)
                    ->orWhere('email', $employeeIdentifier)
                    ->where('role', 'employee')
                    ->first();

                if (!$user) {
                    $failedRecords++;
                    $errors[] = "Row {$totalRecords}: Employee not found - {$employeeIdentifier}";
                    continue;
                }

                $parsedDate = Carbon::parse($date)->format('Y-m-d');
                $clockInTime = $clockIn ? Carbon::parse($parsedDate . ' ' . $clockIn) : null;
                $clockOutTime = $clockOut ? Carbon::parse($parsedDate . ' ' . $clockOut) : null;

                $totalHours = 0;
                $regularHours = 0;
                $overtimeHours = 0;

                if ($clockInTime && $clockOutTime) {
                    $totalHours = $clockOutTime->diffInMinutes($clockInTime) / 60;
                    
                    $settings = PayrollSetting::first();
                    $regularHoursLimit = $settings ? $settings->regular_hours_per_day : 8;
                    
                    if ($totalHours > $regularHoursLimit) {
                        $regularHours = $regularHoursLimit;
                        $overtimeHours = $totalHours - $regularHoursLimit;
                    } else {
                        $regularHours = $totalHours;
                    }
                }

                AttendanceRecord::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $parsedDate],
                    [
                        'clock_in' => $clockInTime,
                        'clock_out' => $clockOutTime,
                        'total_hours' => $totalHours,
                        'regular_hours' => $regularHours,
                        'overtime_hours' => $overtimeHours,
                        'status' => $totalHours > 0 ? 'present' : 'absent',
                        'source' => 'csv_import',
                        'biometric_id' => $employeeIdentifier,
                        'created_by' => Auth::id(),
                    ]
                );

                $successfulRecords++;
            } catch (\Exception $e) {
                $failedRecords++;
                $errors[] = "Row {$totalRecords}: " . $e->getMessage();
            }
        }

        fclose($handle);

        $import->update([
            'total_records' => $totalRecords,
            'successful_records' => $successfulRecords,
            'failed_records' => $failedRecords,
            'status' => 'completed',
            'error_log' => !empty($errors) ? json_encode($errors) : null,
        ]);

        return redirect('/payroll/attendance/import')->with('message', 
            "Import completed. {$successfulRecords} records imported successfully, {$failedRecords} failed.");
    }

    // Employee Salary Management
    public function salaryList(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role', 'employee')
                ->where('soft_delete', 0)
                ->with('salary');

            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'LIKE', "%{$searchValue}%")
                        ->orWhere('lastname', 'LIKE', "%{$searchValue}%")
                        ->orWhere('email', 'LIKE', "%{$searchValue}%");
                });
            }

            $filteredRecords = $query->count();

            $data = $query->orderBy('name')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => User::where('role', 'employee')->where('soft_delete', 0)->count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($employee) {
                    $salary = EmployeeSalary::where('user_id', $employee->id)->first();
                    return [
                        'id' => $employee->id,
                        'image' => '<img src="' . asset('public/employee/' . $employee->image) . '" width="40px" height="40px" class="rounded-circle">',
                        'name' => $employee->name . ' ' . $employee->lastname,
                        'designation' => $employee->designation ?? '-',
                        'salary_type' => $salary ? ucfirst($salary->salary_type) : 'Not Set',
                        'basic_salary' => $salary ? number_format($salary->basic_salary, 2) : '0.00',
                        'allowances' => $salary ? number_format($salary->allowance + $salary->transportation_allowance + $salary->meal_allowance, 2) : '0.00',
                        'action' => '<a href="' . url('/payroll/salary/edit/' . $employee->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>',
                    ];
                }),
            ];

            return response()->json($response);
        }

        return view('payroll.salary.list');
    }

    public function salaryEdit($userId)
    {
        $employee = User::findOrFail($userId);
        $salary = EmployeeSalary::where('user_id', $userId)->first();
        
        if (!$salary) {
            $salary = new EmployeeSalary();
            $salary->user_id = $userId;
        }

        return view('payroll.salary.edit', compact('employee', 'salary'));
    }

    public function salaryUpdate(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'salary_type' => 'required|in:hourly,daily,monthly',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        EmployeeSalary::updateOrCreate(
            ['user_id' => $userId],
            [
                'salary_type' => $request->salary_type,
                'basic_salary' => $request->basic_salary,
                'hourly_rate' => $request->hourly_rate ?? 0,
                'daily_rate' => $request->daily_rate ?? 0,
                'allowance' => $request->allowance ?? 0,
                'transportation_allowance' => $request->transportation_allowance ?? 0,
                'meal_allowance' => $request->meal_allowance ?? 0,
                'housing_allowance' => $request->housing_allowance ?? 0,
                'sss_enabled' => $request->has('sss_enabled'),
                'philhealth_enabled' => $request->has('philhealth_enabled'),
                'pagibig_enabled' => $request->has('pagibig_enabled'),
                'tax_enabled' => $request->has('tax_enabled'),
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name' => $request->bank_account_name,
                'effective_date' => $request->effective_date,
                'notes' => $request->notes,
            ]
        );

        return redirect('/payroll/salary')->with('message', 'Salary information updated successfully');
    }

    // Payroll Period Management
    public function payrollList(Request $request)
    {
        if ($request->ajax()) {
            $query = PayrollPeriod::query();

            $searchValue = $request->input('search.value');
            if ($searchValue) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $filteredRecords = $query->count();

            $data = $query->orderBy('created_at', 'desc')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => PayrollPeriod::count(),
                'recordsFiltered' => $filteredRecords,
                'data' => $data->map(function ($period) {
                    return [
                        'id' => $period->id,
                        'name' => $period->name,
                        'period' => $period->start_date->format('M d') . ' - ' . $period->end_date->format('M d, Y'),
                        'pay_date' => $period->pay_date ? $period->pay_date->format('M d, Y') : '-',
                        'employees' => $period->employee_count,
                        'total_net' => number_format($period->total_net, 2),
                        'status' => '<span class="badge bg-' . $this->getPayrollStatusColor($period->status) . '">' . ucfirst($period->status) . '</span>',
                        'action' => $this->getPayrollActions($period),
                    ];
                }),
            ];

            return response()->json($response);
        }

        return view('payroll.payroll.list');
    }

    public function payrollCreate()
    {
        $settings = PayrollSetting::first();
        return view('payroll.payroll.add', compact('settings'));
    }

    public function payrollStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pay_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $period = PayrollPeriod::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pay_date' => $request->pay_date,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect('/payroll/payroll/process/' . $period->id)->with('message', 'Payroll period created. Now processing...');
    }

    public function payrollProcess($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        $settings = PayrollSetting::first();

        $payrollData = [];

        foreach ($employees as $employee) {
            $salary = EmployeeSalary::where('user_id', $employee->id)->first();
            
            if (!$salary) {
                continue;
            }

            // Get attendance records for the period
            $attendance = AttendanceRecord::where('user_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->get();

            $daysWorked = $attendance->where('status', '!=', 'absent')->count();
            $regularHours = $attendance->sum('regular_hours');
            $overtimeHours = $attendance->sum('overtime_hours');
            $nightDiffHours = $attendance->sum('night_diff_hours');
            $lateMinutes = $attendance->sum('late_minutes');
            $undertimeMinutes = $attendance->sum('undertime_minutes');
            $absences = $attendance->where('status', 'absent')->count();

            // Calculate earnings
            $basicPay = $this->calculateBasicPay($salary, $daysWorked, $regularHours, $period);
            $overtimePay = $this->calculateOvertimePay($salary, $overtimeHours, $settings);
            $nightDiffPay = $this->calculateNightDiffPay($salary, $nightDiffHours, $settings);
            $allowances = $salary->allowance + $salary->transportation_allowance + $salary->meal_allowance + $salary->housing_allowance;

            $grossPay = $basicPay + $overtimePay + $nightDiffPay + $allowances;

            // Calculate deductions
            $sssContribution = $salary->sss_enabled && $settings ? $grossPay * $settings->sss_contribution_rate : 0;
            $philhealthContribution = $salary->philhealth_enabled && $settings ? $grossPay * $settings->philhealth_contribution_rate : 0;
            $pagibigContribution = $salary->pagibig_enabled && $settings ? min($grossPay * $settings->pagibig_contribution_rate, 100) : 0;
            
            $lateDeduction = $this->calculateLateDeduction($salary, $lateMinutes);
            $undertimeDeduction = $this->calculateUndertimeDeduction($salary, $undertimeMinutes);
            $absenceDeduction = $this->calculateAbsenceDeduction($salary, $absences);

            // Get loan deductions
            $loanDeduction = PayrollDeduction::where('user_id', $employee->id)
                ->where('status', 'active')
                ->where('deduction_type', 'loan')
                ->sum('monthly_deduction');

            $cashAdvanceDeduction = PayrollDeduction::where('user_id', $employee->id)
                ->where('status', 'active')
                ->where('deduction_type', 'cash_advance')
                ->sum('monthly_deduction');

            $totalDeductions = $sssContribution + $philhealthContribution + $pagibigContribution + 
                              $lateDeduction + $undertimeDeduction + $absenceDeduction + 
                              $loanDeduction + $cashAdvanceDeduction;

            $netPay = $grossPay - $totalDeductions;

            $payrollData[] = [
                'employee' => $employee,
                'salary' => $salary,
                'days_worked' => $daysWorked,
                'regular_hours' => $regularHours,
                'overtime_hours' => $overtimeHours,
                'basic_pay' => $basicPay,
                'overtime_pay' => $overtimePay,
                'night_diff_pay' => $nightDiffPay,
                'allowances' => $allowances,
                'gross_pay' => $grossPay,
                'sss_contribution' => $sssContribution,
                'philhealth_contribution' => $philhealthContribution,
                'pagibig_contribution' => $pagibigContribution,
                'late_deduction' => $lateDeduction,
                'undertime_deduction' => $undertimeDeduction,
                'absence_deduction' => $absenceDeduction,
                'loan_deduction' => $loanDeduction,
                'cash_advance_deduction' => $cashAdvanceDeduction,
                'total_deductions' => $totalDeductions,
                'net_pay' => $netPay,
            ];
        }

        return view('payroll.payroll.process', compact('period', 'payrollData', 'settings'));
    }

    public function payrollSave(Request $request, $id)
    {
        $period = PayrollPeriod::findOrFail($id);
        
        $totalGross = 0;
        $totalDeductions = 0;
        $totalNet = 0;
        $employeeCount = 0;

        foreach ($request->employees as $userId => $data) {
            PayrollRecord::updateOrCreate(
                ['payroll_period_id' => $period->id, 'user_id' => $userId],
                [
                    'days_worked' => $data['days_worked'] ?? 0,
                    'regular_hours' => $data['regular_hours'] ?? 0,
                    'overtime_hours' => $data['overtime_hours'] ?? 0,
                    'basic_pay' => $data['basic_pay'] ?? 0,
                    'overtime_pay' => $data['overtime_pay'] ?? 0,
                    'night_diff_pay' => $data['night_diff_pay'] ?? 0,
                    'allowances' => $data['allowances'] ?? 0,
                    'gross_pay' => $data['gross_pay'] ?? 0,
                    'sss_contribution' => $data['sss_contribution'] ?? 0,
                    'philhealth_contribution' => $data['philhealth_contribution'] ?? 0,
                    'pagibig_contribution' => $data['pagibig_contribution'] ?? 0,
                    'late_deduction' => $data['late_deduction'] ?? 0,
                    'undertime_deduction' => $data['undertime_deduction'] ?? 0,
                    'absence_deduction' => $data['absence_deduction'] ?? 0,
                    'loan_deduction' => $data['loan_deduction'] ?? 0,
                    'cash_advance_deduction' => $data['cash_advance_deduction'] ?? 0,
                    'total_deductions' => $data['total_deductions'] ?? 0,
                    'net_pay' => $data['net_pay'] ?? 0,
                    'status' => 'pending',
                ]
            );

            $totalGross += $data['gross_pay'] ?? 0;
            $totalDeductions += $data['total_deductions'] ?? 0;
            $totalNet += $data['net_pay'] ?? 0;
            $employeeCount++;
        }

        $period->update([
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
            'employee_count' => $employeeCount,
            'status' => 'processing',
        ]);

        return redirect('/payroll/payroll/view/' . $period->id)->with('message', 'Payroll saved successfully');
    }

    public function payrollView($id)
    {
        $period = PayrollPeriod::with(['payrollRecords.user'])->findOrFail($id);
        return view('payroll.payroll.view', compact('period'));
    }

    public function payrollApprove($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $period->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        PayrollRecord::where('payroll_period_id', $id)->update(['status' => 'approved']);

        return redirect('/payroll/payroll/view/' . $id)->with('message', 'Payroll approved successfully');
    }

    public function payrollMarkPaid($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $period->update(['status' => 'paid']);

        PayrollRecord::where('payroll_period_id', $id)->update(['status' => 'paid']);

        // Update loan deductions
        $records = PayrollRecord::where('payroll_period_id', $id)->get();
        foreach ($records as $record) {
            $deductions = PayrollDeduction::where('user_id', $record->user_id)
                ->where('status', 'active')
                ->get();

            foreach ($deductions as $deduction) {
                $newBalance = $deduction->remaining_balance - $deduction->monthly_deduction;
                if ($newBalance <= 0) {
                    $deduction->update([
                        'remaining_balance' => 0,
                        'status' => 'completed',
                    ]);
                } else {
                    $deduction->update(['remaining_balance' => $newBalance]);
                }
            }
        }

        return redirect('/payroll/payroll/view/' . $id)->with('message', 'Payroll marked as paid');
    }

    public function payrollDelete($id)
    {
        PayrollPeriod::destroy($id);
        return redirect('/payroll/payroll')->with('message', 'Payroll period deleted');
    }

    // Payroll Settings
    public function settings()
    {
        $settings = PayrollSetting::first();
        if (!$settings) {
            $settings = PayrollSetting::create([]);
        }
        return view('payroll.settings', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        $settings = PayrollSetting::first();
        if (!$settings) {
            $settings = new PayrollSetting();
        }

        $settings->fill($request->all());
        $settings->save();

        return redirect('/payroll/settings')->with('message', 'Settings updated successfully');
    }

    // Holidays
    public function holidayList(Request $request)
    {
        if ($request->ajax()) {
            $query = PayrollHoliday::query();

            $data = $query->orderBy('date', 'desc')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => PayrollHoliday::count(),
                'recordsFiltered' => $query->count(),
                'data' => $data->map(function ($holiday) {
                    return [
                        'id' => $holiday->id,
                        'name' => $holiday->name,
                        'date' => $holiday->date->format('M d, Y'),
                        'type' => '<span class="badge bg-' . ($holiday->type == 'regular' ? 'primary' : 'warning') . '">' . ucfirst($holiday->type) . '</span>',
                        'multiplier' => $holiday->pay_multiplier . 'x',
                        'action' => '<a href="' . url('/payroll/holidays/edit/' . $holiday->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="' . url('/payroll/holidays/delete/' . $holiday->id) . '" class="btn btn-sm btn-danger deletedatas"><i class="fa fa-trash"></i></a>',
                    ];
                }),
            ];

            return response()->json($response);
        }

        return view('payroll.holidays.list');
    }

    public function holidayCreate()
    {
        return view('payroll.holidays.add');
    }

    public function holidayStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PayrollHoliday::create($request->all());

        return redirect('/payroll/holidays')->with('message', 'Holiday added successfully');
    }

    public function holidayEdit($id)
    {
        $holiday = PayrollHoliday::findOrFail($id);
        return view('payroll.holidays.edit', compact('holiday'));
    }

    public function holidayUpdate(Request $request, $id)
    {
        $holiday = PayrollHoliday::findOrFail($id);
        $holiday->update($request->all());
        return redirect('/payroll/holidays')->with('message', 'Holiday updated successfully');
    }

    public function holidayDelete($id)
    {
        PayrollHoliday::destroy($id);
        return redirect('/payroll/holidays')->with('message', 'Holiday deleted successfully');
    }

    // Leave Requests
    public function leaveList(Request $request)
    {
        if ($request->ajax()) {
            $query = LeaveRequest::with('user');

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $data = $query->orderBy('created_at', 'desc')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => LeaveRequest::count(),
                'recordsFiltered' => $query->count(),
                'data' => $data->map(function ($leave) {
                    return [
                        'id' => $leave->id,
                        'employee' => $leave->user ? $leave->user->name . ' ' . $leave->user->lastname : 'N/A',
                        'type' => ucfirst(str_replace('_', ' ', $leave->leave_type)),
                        'dates' => $leave->start_date->format('M d') . ' - ' . $leave->end_date->format('M d, Y'),
                        'days' => $leave->days_count,
                        'paid' => $leave->is_paid ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-secondary">Unpaid</span>',
                        'status' => '<span class="badge bg-' . $this->getLeaveStatusColor($leave->status) . '">' . ucfirst($leave->status) . '</span>',
                        'action' => $this->getLeaveActions($leave),
                    ];
                }),
            ];

            return response()->json($response);
        }

        return view('payroll.leave.list');
    }

    public function leaveCreate()
    {
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.leave.add', compact('employees'));
    }

    public function leaveStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $daysCount = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'user_id' => $request->user_id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_count' => $daysCount,
            'is_paid' => $request->has('is_paid'),
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect('/payroll/leave')->with('message', 'Leave request submitted');
    }

    public function leaveApprove($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Create attendance records for leave days
        $startDate = Carbon::parse($leave->start_date);
        $endDate = Carbon::parse($leave->end_date);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            AttendanceRecord::updateOrCreate(
                ['user_id' => $leave->user_id, 'date' => $date->format('Y-m-d')],
                [
                    'status' => 'on-leave',
                    'source' => 'manual',
                    'remarks' => ucfirst($leave->leave_type) . ' leave',
                    'created_by' => Auth::id(),
                ]
            );
        }

        return redirect('/payroll/leave')->with('message', 'Leave request approved');
    }

    public function leaveReject($id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect('/payroll/leave')->with('message', 'Leave request rejected');
    }

    public function leaveDelete($id)
    {
        LeaveRequest::destroy($id);
        return redirect('/payroll/leave')->with('message', 'Leave request deleted');
    }

    // Deductions Management
    public function deductionList(Request $request)
    {
        if ($request->ajax()) {
            $query = PayrollDeduction::with('user');

            $data = $query->orderBy('created_at', 'desc')
                ->offset($request->input('start', 0))
                ->limit($request->input('length', 10))
                ->get();

            $response = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => PayrollDeduction::count(),
                'recordsFiltered' => $query->count(),
                'data' => $data->map(function ($deduction) {
                    return [
                        'id' => $deduction->id,
                        'employee' => $deduction->user ? $deduction->user->name . ' ' . $deduction->user->lastname : 'N/A',
                        'type' => ucfirst(str_replace('_', ' ', $deduction->deduction_type)),
                        'description' => $deduction->description,
                        'amount' => number_format($deduction->amount, 2),
                        'monthly' => number_format($deduction->monthly_deduction, 2),
                        'balance' => number_format($deduction->remaining_balance, 2),
                        'status' => '<span class="badge bg-' . ($deduction->status == 'active' ? 'success' : 'secondary') . '">' . ucfirst($deduction->status) . '</span>',
                        'action' => '<a href="' . url('/payroll/deductions/edit/' . $deduction->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="' . url('/payroll/deductions/delete/' . $deduction->id) . '" class="btn btn-sm btn-danger deletedatas"><i class="fa fa-trash"></i></a>',
                    ];
                }),
            ];

            return response()->json($response);
        }

        return view('payroll.deductions.list');
    }

    public function deductionCreate()
    {
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.deductions.add', compact('employees'));
    }

    public function deductionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'deduction_type' => 'required',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'monthly_deduction' => 'required|numeric|min:0',
            'start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        PayrollDeduction::create([
            'user_id' => $request->user_id,
            'deduction_type' => $request->deduction_type,
            'description' => $request->description,
            'amount' => $request->amount,
            'monthly_deduction' => $request->monthly_deduction,
            'remaining_balance' => $request->amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
            'status' => 'active',
        ]);

        return redirect('/payroll/deductions')->with('message', 'Deduction added successfully');
    }

    public function deductionEdit($id)
    {
        $deduction = PayrollDeduction::findOrFail($id);
        $employees = User::where('role', 'employee')->where('soft_delete', 0)->get();
        return view('payroll.deductions.edit', compact('deduction', 'employees'));
    }

    public function deductionUpdate(Request $request, $id)
    {
        $deduction = PayrollDeduction::findOrFail($id);
        $deduction->update($request->all());
        return redirect('/payroll/deductions')->with('message', 'Deduction updated successfully');
    }

    public function deductionDelete($id)
    {
        PayrollDeduction::destroy($id);
        return redirect('/payroll/deductions')->with('message', 'Deduction deleted successfully');
    }

    // Payslip
    public function payslip($recordId)
    {
        $record = PayrollRecord::with(['user', 'payrollPeriod'])->findOrFail($recordId);
        $salary = EmployeeSalary::where('user_id', $record->user_id)->first();
        $settings = PayrollSetting::first();
        
        return view('payroll.payslip', compact('record', 'salary', 'settings'));
    }

    public function payslipPrint($recordId)
    {
        $record = PayrollRecord::with(['user', 'payrollPeriod'])->findOrFail($recordId);
        $salary = EmployeeSalary::where('user_id', $record->user_id)->first();
        $settings = PayrollSetting::first();
        $logo = DB::table('tbl_settings')->first();
        
        return view('payroll.payslip_print', compact('record', 'salary', 'settings', 'logo'));
    }

    // Helper Methods
    private function calculateBasicPay($salary, $daysWorked, $regularHours, $period)
    {
        switch ($salary->salary_type) {
            case 'hourly':
                return $regularHours * $salary->hourly_rate;
            case 'daily':
                return $daysWorked * $salary->daily_rate;
            case 'monthly':
            default:
                $totalDays = $period->start_date->diffInDays($period->end_date) + 1;
                $workDays = 0;
                for ($d = clone $period->start_date; $d->lte($period->end_date); $d->addDay()) {
                    if ($d->dayOfWeek != 0 && $d->dayOfWeek != 6) {
                        $workDays++;
                    }
                }
                $dailyRate = $workDays > 0 ? $salary->basic_salary / $workDays : 0;
                return $daysWorked * $dailyRate;
        }
    }

    private function calculateOvertimePay($salary, $overtimeHours, $settings)
    {
        $hourlyRate = $salary->hourly_rate > 0 ? $salary->hourly_rate : ($salary->basic_salary / 22 / 8);
        $multiplier = $settings ? $settings->overtime_multiplier : 1.5;
        return $overtimeHours * $hourlyRate * $multiplier;
    }

    private function calculateNightDiffPay($salary, $nightDiffHours, $settings)
    {
        $hourlyRate = $salary->hourly_rate > 0 ? $salary->hourly_rate : ($salary->basic_salary / 22 / 8);
        $multiplier = $settings ? $settings->night_diff_multiplier : 1.1;
        return $nightDiffHours * $hourlyRate * ($multiplier - 1);
    }

    private function calculateLateDeduction($salary, $lateMinutes)
    {
        $hourlyRate = $salary->hourly_rate > 0 ? $salary->hourly_rate : ($salary->basic_salary / 22 / 8);
        return ($lateMinutes / 60) * $hourlyRate;
    }

    private function calculateUndertimeDeduction($salary, $undertimeMinutes)
    {
        $hourlyRate = $salary->hourly_rate > 0 ? $salary->hourly_rate : ($salary->basic_salary / 22 / 8);
        return ($undertimeMinutes / 60) * $hourlyRate;
    }

    private function calculateAbsenceDeduction($salary, $absences)
    {
        $dailyRate = $salary->daily_rate > 0 ? $salary->daily_rate : ($salary->basic_salary / 22);
        return $absences * $dailyRate;
    }

    private function getStatusColor($status)
    {
        $colors = [
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'half-day' => 'info',
            'on-leave' => 'primary',
            'holiday' => 'secondary',
        ];
        return $colors[$status] ?? 'secondary';
    }

    private function getPayrollStatusColor($status)
    {
        $colors = [
            'draft' => 'secondary',
            'processing' => 'info',
            'approved' => 'primary',
            'paid' => 'success',
            'cancelled' => 'danger',
        ];
        return $colors[$status] ?? 'secondary';
    }

    private function getLeaveStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
        ];
        return $colors[$status] ?? 'secondary';
    }

    private function getAttendanceActions($record)
    {
        return '<div class="dropdown_toggle">
            <img src="' . asset('public/img/list/dots.png') . '" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2">
                <li><a class="dropdown-item" href="' . url('/payroll/attendance/edit/' . $record->id) . '">
                    <img src="' . asset('public/img/list/Edit.png') . '" class="me-3"> Edit</a></li>
                <div class="dropdown-divider m-0"></div>
                <li><a class="dropdown-item deletedatas" url="' . url('/payroll/attendance/delete/' . $record->id) . '" style="color:#FD726A">
                    <img src="' . asset('public/img/list/Delete.png') . '" class="me-3"> Delete</a></li>
            </ul>
        </div>';
    }

    private function getPayrollActions($period)
    {
        $actions = '<div class="dropdown_toggle">
            <img src="' . asset('public/img/list/dots.png') . '" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2">
                <li><a class="dropdown-item" href="' . url('/payroll/payroll/view/' . $period->id) . '">
                    <img src="' . asset('public/img/list/Vector.png') . '" class="me-3"> View</a></li>';

        if ($period->status == 'draft' || $period->status == 'processing') {
            $actions .= '<li><a class="dropdown-item" href="' . url('/payroll/payroll/process/' . $period->id) . '">
                    <img src="' . asset('public/img/list/Edit.png') . '" class="me-3"> Process</a></li>';
        }

        if ($period->status == 'processing') {
            $actions .= '<li><a class="dropdown-item" href="' . url('/payroll/payroll/approve/' . $period->id) . '">
                    <i class="fa fa-check me-3 text-success"></i> Approve</a></li>';
        }

        if ($period->status == 'approved') {
            $actions .= '<li><a class="dropdown-item" href="' . url('/payroll/payroll/mark-paid/' . $period->id) . '">
                    <i class="fa fa-money me-3 text-success"></i> Mark as Paid</a></li>';
        }

        if ($period->status == 'draft') {
            $actions .= '<div class="dropdown-divider m-0"></div>
                <li><a class="dropdown-item deletedatas" url="' . url('/payroll/payroll/delete/' . $period->id) . '" style="color:#FD726A">
                    <img src="' . asset('public/img/list/Delete.png') . '" class="me-3"> Delete</a></li>';
        }

        $actions .= '</ul></div>';
        return $actions;
    }

    private function getLeaveActions($leave)
    {
        $actions = '<div class="dropdown_toggle">
            <img src="' . asset('public/img/list/dots.png') . '" class="btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2">';

        if ($leave->status == 'pending') {
            $actions .= '<li><a class="dropdown-item" href="' . url('/payroll/leave/approve/' . $leave->id) . '">
                    <i class="fa fa-check me-3 text-success"></i> Approve</a></li>
                <li><a class="dropdown-item" href="' . url('/payroll/leave/reject/' . $leave->id) . '">
                    <i class="fa fa-times me-3 text-danger"></i> Reject</a></li>';
        }

        $actions .= '<div class="dropdown-divider m-0"></div>
            <li><a class="dropdown-item deletedatas" url="' . url('/payroll/leave/delete/' . $leave->id) . '" style="color:#FD726A">
                <img src="' . asset('public/img/list/Delete.png') . '" class="me-3"> Delete</a></li>
            </ul></div>';

        return $actions;
    }
}
