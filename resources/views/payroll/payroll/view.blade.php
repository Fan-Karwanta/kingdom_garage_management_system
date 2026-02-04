@extends('layouts.app')
@section('content')
<style>
    .payroll-header {
        background: linear-gradient(135deg, #2596BE 0%, #1a7a9a 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
    }
    .status-badge {
        font-size: 14px;
        padding: 8px 16px;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">View Payroll - {{ $period->name }}</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    @include('success_message.message')

    <!-- Payroll Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="payroll-header">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <h3 class="mb-1">{{ $period->name }}</h3>
                        <p class="mb-2 opacity-75">
                            <i class="fa fa-calendar me-2"></i>
                            {{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }}
                        </p>
                        @if($period->pay_date)
                        <p class="mb-0 opacity-75">
                            <i class="fa fa-money me-2"></i>Pay Date: {{ $period->pay_date->format('M d, Y') }}
                        </p>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'processing' => 'info',
                                'approved' => 'primary',
                                'paid' => 'success',
                                'cancelled' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$period->status] ?? 'secondary' }} status-badge">
                            {{ ucfirst($period->status) }}
                        </span>
                    </div>
                    <div class="col-md-3 text-end">
                        @if($period->status == 'processing')
                        <a href="{{ url('/payroll/payroll/approve/' . $period->id) }}" class="btn btn-success">
                            <i class="fa fa-check me-2"></i>Approve
                        </a>
                        @endif
                        @if($period->status == 'approved')
                        <a href="{{ url('/payroll/payroll/mark-paid/' . $period->id) }}" class="btn btn-success">
                            <i class="fa fa-money me-2"></i>Mark as Paid
                        </a>
                        @endif
                        @if($period->status == 'draft' || $period->status == 'processing')
                        <a href="{{ url('/payroll/payroll/process/' . $period->id) }}" class="btn btn-warning">
                            <i class="fa fa-edit me-2"></i>Edit
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="x_panel text-center">
                <h4 class="text-primary mb-0">{{ $period->employee_count }}</h4>
                <small class="text-muted">Employees</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel text-center">
                <h4 class="text-info mb-0">{{ number_format($period->total_gross, 2) }}</h4>
                <small class="text-muted">Total Gross</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel text-center">
                <h4 class="text-danger mb-0">{{ number_format($period->total_deductions, 2) }}</h4>
                <small class="text-muted">Total Deductions</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="x_panel text-center">
                <h4 class="text-success mb-0">{{ number_format($period->total_net, 2) }}</h4>
                <small class="text-muted">Total Net Pay</small>
            </div>
        </div>
    </div>

    <!-- Payroll Records Table -->
    <div class="row">
        <div class="col-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-list me-2"></i>Employee Payroll Records</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th class="text-center">Days</th>
                                    <th class="text-end">Basic Pay</th>
                                    <th class="text-end">OT Pay</th>
                                    <th class="text-end">Allowances</th>
                                    <th class="text-end">Gross</th>
                                    <th class="text-end">Deductions</th>
                                    <th class="text-end">Net Pay</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($period->payrollRecords as $record)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('public/employee/' . ($record->user->image ?? 'avtar.png')) }}" 
                                                 class="rounded-circle me-2" width="35" height="35">
                                            <div>
                                                <strong>{{ $record->user->name ?? 'N/A' }} {{ $record->user->lastname ?? '' }}</strong>
                                                <br><small class="text-muted">{{ $record->user->designation ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $record->days_worked }}</td>
                                    <td class="text-end">{{ number_format($record->basic_pay, 2) }}</td>
                                    <td class="text-end">{{ number_format($record->overtime_pay, 2) }}</td>
                                    <td class="text-end">{{ number_format($record->allowances, 2) }}</td>
                                    <td class="text-end"><strong>{{ number_format($record->gross_pay, 2) }}</strong></td>
                                    <td class="text-end text-danger">{{ number_format($record->total_deductions, 2) }}</td>
                                    <td class="text-end text-success"><strong>{{ number_format($record->net_pay, 2) }}</strong></td>
                                    <td class="text-center">
                                        <a href="{{ url('/payroll/payslip/' . $record->id) }}" class="btn btn-sm btn-outline-primary" title="View Payslip">
                                            <i class="fa fa-file-text"></i>
                                        </a>
                                        <a href="{{ url('/payroll/payslip/print/' . $record->id) }}" class="btn btn-sm btn-outline-secondary" title="Print Payslip" target="_blank">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <p class="text-muted mb-0">No payroll records found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="5" class="text-end">Totals:</th>
                                    <th class="text-end">{{ number_format($period->total_gross, 2) }}</th>
                                    <th class="text-end text-danger">{{ number_format($period->total_deductions, 2) }}</th>
                                    <th class="text-end text-success">{{ number_format($period->total_net, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ url('/payroll/payroll') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-2"></i>Back to Payroll List
            </a>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
@endsection
