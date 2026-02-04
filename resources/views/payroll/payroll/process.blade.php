@extends('layouts.app')
@section('content')
<style>
    .payroll-summary {
        background: linear-gradient(135deg, #2596BE 0%, #1a7a9a 100%);
        color: white;
        border-radius: 12px;
        padding: 20px;
    }
    .employee-payroll-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: box-shadow 0.2s;
    }
    .employee-payroll-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .payroll-section-title {
        background: #f8f9fa;
        padding: 10px 15px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Process Payroll - {{ $period->name }}</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <!-- Period Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="payroll-summary">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h4 class="mb-1">{{ $period->name }}</h4>
                        <p class="mb-0 opacity-75">
                            {{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="col-md-2 text-center">
                        <h3 class="mb-0">{{ count($payrollData) }}</h3>
                        <small class="opacity-75">Employees</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <h3 class="mb-0">{{ number_format(collect($payrollData)->sum('gross_pay'), 2) }}</h3>
                        <small class="opacity-75">Total Gross</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <h3 class="mb-0">{{ number_format(collect($payrollData)->sum('total_deductions'), 2) }}</h3>
                        <small class="opacity-75">Total Deductions</small>
                    </div>
                    <div class="col-md-2 text-center">
                        <h3 class="mb-0">{{ number_format(collect($payrollData)->sum('net_pay'), 2) }}</h3>
                        <small class="opacity-75">Total Net Pay</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ url('/payroll/payroll/save/' . $period->id) }}" method="POST">
        @csrf

        @if(count($payrollData) > 0)
        <!-- Employee Payroll Cards -->
        @foreach($payrollData as $index => $data)
        <div class="employee-payroll-card">
            <div class="payroll-section-title d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('public/employee/' . ($data['employee']->image ?? 'avtar.png')) }}" 
                         class="rounded-circle me-3" width="40" height="40">
                    <div>
                        <strong>{{ $data['employee']->name }} {{ $data['employee']->lastname }}</strong>
                        <br><small class="text-muted">{{ $data['employee']->designation ?? 'Employee' }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-success fs-6">Net Pay: {{ number_format($data['net_pay'], 2) }}</span>
                </div>
            </div>
            <div class="p-3">
                <div class="row">
                    <!-- Work Hours -->
                    <div class="col-md-3">
                        <h6 class="text-muted mb-2"><i class="fa fa-clock-o me-1"></i>Work Hours</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Days Worked</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][days_worked]" 
                                           class="form-control form-control-sm text-end" style="width:80px;display:inline"
                                           value="{{ $data['days_worked'] }}" step="0.5">
                                </td>
                            </tr>
                            <tr>
                                <td>Regular Hours</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][regular_hours]" 
                                           class="form-control form-control-sm text-end" style="width:80px;display:inline"
                                           value="{{ $data['regular_hours'] }}" step="0.5">
                                </td>
                            </tr>
                            <tr>
                                <td>Overtime Hours</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][overtime_hours]" 
                                           class="form-control form-control-sm text-end" style="width:80px;display:inline"
                                           value="{{ $data['overtime_hours'] }}" step="0.5">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Earnings -->
                    <div class="col-md-3">
                        <h6 class="text-muted mb-2"><i class="fa fa-plus-circle me-1 text-success"></i>Earnings</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Basic Pay</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][basic_pay]" 
                                           class="form-control form-control-sm text-end earning-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['basic_pay'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Overtime Pay</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][overtime_pay]" 
                                           class="form-control form-control-sm text-end earning-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['overtime_pay'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Night Diff Pay</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][night_diff_pay]" 
                                           class="form-control form-control-sm text-end earning-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['night_diff_pay'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Allowances</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][allowances]" 
                                           class="form-control form-control-sm text-end earning-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['allowances'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Gross Pay</strong></td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][gross_pay]" 
                                           class="form-control form-control-sm text-end fw-bold gross-pay" style="width:100px;display:inline"
                                           value="{{ number_format($data['gross_pay'], 2, '.', '') }}" step="0.01" readonly
                                           id="gross_{{ $data['employee']->id }}">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Deductions -->
                    <div class="col-md-3">
                        <h6 class="text-muted mb-2"><i class="fa fa-minus-circle me-1 text-danger"></i>Deductions</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>SSS</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][sss_contribution]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['sss_contribution'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>PhilHealth</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][philhealth_contribution]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['philhealth_contribution'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Pag-IBIG</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][pagibig_contribution]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['pagibig_contribution'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Late/Undertime</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][late_deduction]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['late_deduction'] + $data['undertime_deduction'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                    <input type="hidden" name="employees[{{ $data['employee']->id }}][undertime_deduction]" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td>Loans</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][loan_deduction]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['loan_deduction'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Cash Advance</td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][cash_advance_deduction]" 
                                           class="form-control form-control-sm text-end deduction-input" style="width:100px;display:inline"
                                           value="{{ number_format($data['cash_advance_deduction'], 2, '.', '') }}" step="0.01"
                                           data-employee="{{ $data['employee']->id }}">
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total Deductions</strong></td>
                                <td class="text-end">
                                    <input type="number" name="employees[{{ $data['employee']->id }}][total_deductions]" 
                                           class="form-control form-control-sm text-end fw-bold total-deductions" style="width:100px;display:inline"
                                           value="{{ number_format($data['total_deductions'], 2, '.', '') }}" step="0.01" readonly
                                           id="deductions_{{ $data['employee']->id }}">
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Net Pay -->
                    <div class="col-md-3">
                        <h6 class="text-muted mb-2"><i class="fa fa-money me-1 text-primary"></i>Net Pay</h6>
                        <div class="bg-light p-3 rounded text-center">
                            <h2 class="text-success mb-0" id="net_display_{{ $data['employee']->id }}">
                                {{ number_format($data['net_pay'], 2) }}
                            </h2>
                            <input type="hidden" name="employees[{{ $data['employee']->id }}][net_pay]" 
                                   value="{{ number_format($data['net_pay'], 2, '.', '') }}"
                                   id="net_{{ $data['employee']->id }}">
                            <small class="text-muted">Net Pay</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-save me-2"></i>Save Payroll
            </button>
            <a href="{{ url('/payroll/payroll') }}" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left me-2"></i>Back
            </a>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle me-2"></i>
            No employees with salary information found. Please configure employee salaries first.
            <a href="{{ url('/payroll/salary') }}" class="btn btn-sm btn-primary ms-3">Configure Salaries</a>
        </div>
        @endif
    </form>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script nonce="{{ $cspNonce }}">
$(document).ready(function() {
    // Recalculate on input change
    $('.earning-input, .deduction-input').on('change', function() {
        var employeeId = $(this).data('employee');
        recalculate(employeeId);
    });

    function recalculate(employeeId) {
        // Calculate gross
        var basicPay = parseFloat($('input[name="employees[' + employeeId + '][basic_pay]"]').val()) || 0;
        var overtimePay = parseFloat($('input[name="employees[' + employeeId + '][overtime_pay]"]').val()) || 0;
        var nightDiffPay = parseFloat($('input[name="employees[' + employeeId + '][night_diff_pay]"]').val()) || 0;
        var allowances = parseFloat($('input[name="employees[' + employeeId + '][allowances]"]').val()) || 0;
        var grossPay = basicPay + overtimePay + nightDiffPay + allowances;
        $('#gross_' + employeeId).val(grossPay.toFixed(2));

        // Calculate deductions
        var sss = parseFloat($('input[name="employees[' + employeeId + '][sss_contribution]"]').val()) || 0;
        var philhealth = parseFloat($('input[name="employees[' + employeeId + '][philhealth_contribution]"]').val()) || 0;
        var pagibig = parseFloat($('input[name="employees[' + employeeId + '][pagibig_contribution]"]').val()) || 0;
        var late = parseFloat($('input[name="employees[' + employeeId + '][late_deduction]"]').val()) || 0;
        var loan = parseFloat($('input[name="employees[' + employeeId + '][loan_deduction]"]').val()) || 0;
        var cashAdvance = parseFloat($('input[name="employees[' + employeeId + '][cash_advance_deduction]"]').val()) || 0;
        var totalDeductions = sss + philhealth + pagibig + late + loan + cashAdvance;
        $('#deductions_' + employeeId).val(totalDeductions.toFixed(2));

        // Calculate net
        var netPay = grossPay - totalDeductions;
        $('#net_' + employeeId).val(netPay.toFixed(2));
        $('#net_display_' + employeeId).text(netPay.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }
});
</script>
@endsection
