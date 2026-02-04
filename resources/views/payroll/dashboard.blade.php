@extends('layouts.app')
@section('content')
<style>
    .payroll-stat-card {
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }
    .payroll-stat-card:hover {
        transform: translateY(-3px);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-icon.blue { background: rgba(37, 150, 190, 0.15); color: #2596BE; }
    .stat-icon.green { background: rgba(40, 167, 69, 0.15); color: #28a745; }
    .stat-icon.orange { background: rgba(234, 107, 0, 0.15); color: #EA6B00; }
    .stat-icon.purple { background: rgba(111, 66, 193, 0.15); color: #6f42c1; }
    .stat-value { font-size: 28px; font-weight: 700; color: #333; }
    .stat-label { font-size: 14px; color: #6c757d; }
    .quick-action-btn {
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .payroll-table th { background: #f8f9fa; font-weight: 600; }
    .attendance-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }
    .attendance-item:last-child { border-bottom: none; }
    .chart-container { height: 300px; }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Payroll Management Dashboard</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="payroll-stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon blue me-3">
                        <i class="fa fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $employees }}</div>
                        <div class="stat-label">Total Employees</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="payroll-stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon green me-3">
                        <i class="fa fa-money"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ number_format($totalPayrollThisMonth, 2) }}</div>
                        <div class="stat-label">Payroll This Month</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="payroll-stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon orange me-3">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $attendanceStats->total_hours ? number_format($attendanceStats->total_hours, 0) : 0 }}</div>
                        <div class="stat-label">Hours This Month</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="payroll-stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon purple me-3">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $pendingLeaves }}</div>
                        <div class="stat-label">Pending Leave Requests</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bolt me-2"></i>Quick Actions</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ url('/payroll/payroll/add') }}" class="btn btn-primary quick-action-btn">
                            <i class="fa fa-plus me-2"></i>Create Payroll
                        </a>
                        <a href="{{ url('/payroll/attendance/add') }}" class="btn btn-success quick-action-btn">
                            <i class="fa fa-clock-o me-2"></i>Add Attendance
                        </a>
                        <a href="{{ url('/payroll/attendance/import') }}" class="btn btn-info quick-action-btn text-white">
                            <i class="fa fa-upload me-2"></i>Import Biometrics
                        </a>
                        <a href="{{ url('/payroll/leave/add') }}" class="btn btn-warning quick-action-btn">
                            <i class="fa fa-calendar me-2"></i>File Leave
                        </a>
                        <a href="{{ url('/payroll/salary') }}" class="btn btn-secondary quick-action-btn">
                            <i class="fa fa-money me-2"></i>Manage Salaries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Payroll Periods -->
        <div class="col-md-8 mb-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-list me-2"></i>Recent Payroll Periods</h2>
                    <a href="{{ url('/payroll/payroll') }}" class="btn btn-sm btn-outline-primary float-end">View All</a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Pay Date</th>
                                    <th>Employees</th>
                                    <th>Total Net</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPayrolls as $payroll)
                                <tr>
                                    <td>
                                        <a href="{{ url('/payroll/payroll/view/' . $payroll->id) }}">
                                            {{ $payroll->name }}
                                        </a>
                                    </td>
                                    <td>{{ $payroll->pay_date ? $payroll->pay_date->format('M d, Y') : '-' }}</td>
                                    <td>{{ $payroll->employee_count }}</td>
                                    <td>{{ number_format($payroll->total_net, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'processing' => 'info',
                                                'approved' => 'primary',
                                                'paid' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$payroll->status] ?? 'secondary' }}">
                                            {{ ucfirst($payroll->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted mb-0">No payroll periods yet</p>
                                        <a href="{{ url('/payroll/payroll/add') }}" class="btn btn-sm btn-primary mt-2">Create First Payroll</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="col-md-4 mb-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-calendar-check-o me-2"></i>Today's Attendance</h2>
                    <a href="{{ url('/payroll/attendance') }}" class="btn btn-sm btn-outline-primary float-end">View All</a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content" style="max-height: 400px; overflow-y: auto;">
                    @forelse($todayAttendance as $attendance)
                    <div class="attendance-item d-flex align-items-center">
                        <img src="{{ asset('public/employee/' . ($attendance->user->image ?? 'avtar.png')) }}" 
                             class="rounded-circle me-3" width="40" height="40">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $attendance->user->name ?? 'Unknown' }} {{ $attendance->user->lastname ?? '' }}</div>
                            <small class="text-muted">
                                In: {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : '-' }}
                                @if($attendance->clock_out)
                                | Out: {{ \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') }}
                                @endif
                            </small>
                        </div>
                        @php
                            $statusColors = [
                                'present' => 'success',
                                'absent' => 'danger',
                                'late' => 'warning',
                                'half-day' => 'info',
                                'on-leave' => 'primary'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$attendance->status] ?? 'secondary' }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No attendance records for today</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-pie-chart me-2"></i>Monthly Attendance Summary</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h3 class="text-success mb-0">{{ $attendanceStats->present_count ?? 0 }}</h3>
                                <small class="text-muted">Present</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h3 class="text-danger mb-0">{{ $attendanceStats->absent_count ?? 0 }}</h3>
                                <small class="text-muted">Absent</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h3 class="text-warning mb-0">{{ $attendanceStats->late_count ?? 0 }}</h3>
                                <small class="text-muted">Late</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary mb-0">{{ number_format($attendanceStats->total_hours ?? 0, 1) }}</h4>
                            <small class="text-muted">Total Hours</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0">{{ number_format($attendanceStats->overtime_hours ?? 0, 1) }}</h4>
                            <small class="text-muted">Overtime Hours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-link me-2"></i>Quick Links</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="list-group list-group-flush">
                        <a href="{{ url('/payroll/attendance') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-clock-o me-2 text-primary"></i>Attendance Records</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('/payroll/salary') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-money me-2 text-success"></i>Employee Salaries</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('/payroll/payroll') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-file-text me-2 text-info"></i>Payroll Periods</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('/payroll/leave') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-calendar me-2 text-warning"></i>Leave Requests</span>
                            <span class="badge bg-warning">{{ $pendingLeaves }}</span>
                        </a>
                        <a href="{{ url('/payroll/deductions') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-minus-circle me-2 text-danger"></i>Deductions</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('/payroll/holidays') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-gift me-2 text-purple"></i>Holidays</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('/payroll/settings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-cog me-2 text-secondary"></i>Payroll Settings</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
@endsection
