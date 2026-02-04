@extends('layouts.app')
@section('content')
<style>
    .payslip-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }
    .payslip-header {
        background: linear-gradient(135deg, #2596BE 0%, #1a7a9a 100%);
        color: white;
        padding: 25px;
        border-radius: 12px 12px 0 0;
    }
    .payslip-body {
        padding: 25px;
    }
    .payslip-section {
        margin-bottom: 20px;
    }
    .payslip-section-title {
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #2596BE;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    .payslip-table td {
        padding: 8px 0;
    }
    .payslip-total {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars sidemenu_toggle"></i></a>
                        <span class="titleup">Payslip</span>
                    </div>
                    @include('dashboard.profile')
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="payslip-container">
                <div class="payslip-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">PAYSLIP</h3>
                            <p class="mb-0 opacity-75">{{ $record->payrollPeriod->name }}</p>
                            <p class="mb-0 opacity-75">
                                {{ $record->payrollPeriod->start_date->format('M d, Y') }} - {{ $record->payrollPeriod->end_date->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ url('/payroll/payslip/print/' . $record->id) }}" class="btn btn-light" target="_blank">
                                <i class="fa fa-print me-2"></i>Print
                            </a>
                        </div>
                    </div>
                </div>

                <div class="payslip-body">
                    <!-- Employee Info -->
                    <div class="payslip-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('public/employee/' . ($record->user->image ?? 'avtar.png')) }}" 
                                         class="rounded-circle me-3" width="60" height="60">
                                    <div>
                                        <h5 class="mb-1">{{ $record->user->name }} {{ $record->user->lastname }}</h5>
                                        <p class="text-muted mb-0">{{ $record->user->designation ?? 'Employee' }}</p>
                                        <small class="text-muted">{{ $record->user->email }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-1"><strong>Employee ID:</strong> {{ $record->user->id }}</p>
                                @if($salary && $salary->bank_name)
                                <p class="mb-1"><strong>Bank:</strong> {{ $salary->bank_name }}</p>
                                <p class="mb-0"><strong>Account:</strong> {{ $salary->bank_account_number }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- Earnings -->
                        <div class="col-md-6">
                            <div class="payslip-section">
                                <h6 class="payslip-section-title"><i class="fa fa-plus-circle text-success me-2"></i>Earnings</h6>
                                <table class="table table-borderless payslip-table">
                                    <tr>
                                        <td>Basic Pay</td>
                                        <td class="text-end">{{ number_format($record->basic_pay, 2) }}</td>
                                    </tr>
                                    @if($record->overtime_pay > 0)
                                    <tr>
                                        <td>Overtime Pay ({{ $record->overtime_hours }} hrs)</td>
                                        <td class="text-end">{{ number_format($record->overtime_pay, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->night_diff_pay > 0)
                                    <tr>
                                        <td>Night Differential Pay</td>
                                        <td class="text-end">{{ number_format($record->night_diff_pay, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->holiday_pay > 0)
                                    <tr>
                                        <td>Holiday Pay</td>
                                        <td class="text-end">{{ number_format($record->holiday_pay, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->allowances > 0)
                                    <tr>
                                        <td>Allowances</td>
                                        <td class="text-end">{{ number_format($record->allowances, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->bonus > 0)
                                    <tr>
                                        <td>Bonus</td>
                                        <td class="text-end">{{ number_format($record->bonus, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><strong>Gross Pay</strong></td>
                                        <td class="text-end"><strong>{{ number_format($record->gross_pay, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div class="col-md-6">
                            <div class="payslip-section">
                                <h6 class="payslip-section-title"><i class="fa fa-minus-circle text-danger me-2"></i>Deductions</h6>
                                <table class="table table-borderless payslip-table">
                                    @if($record->sss_contribution > 0)
                                    <tr>
                                        <td>SSS Contribution</td>
                                        <td class="text-end">{{ number_format($record->sss_contribution, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->philhealth_contribution > 0)
                                    <tr>
                                        <td>PhilHealth Contribution</td>
                                        <td class="text-end">{{ number_format($record->philhealth_contribution, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->pagibig_contribution > 0)
                                    <tr>
                                        <td>Pag-IBIG Contribution</td>
                                        <td class="text-end">{{ number_format($record->pagibig_contribution, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->tax_withholding > 0)
                                    <tr>
                                        <td>Tax Withholding</td>
                                        <td class="text-end">{{ number_format($record->tax_withholding, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->late_deduction > 0)
                                    <tr>
                                        <td>Late Deduction</td>
                                        <td class="text-end">{{ number_format($record->late_deduction, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->undertime_deduction > 0)
                                    <tr>
                                        <td>Undertime Deduction</td>
                                        <td class="text-end">{{ number_format($record->undertime_deduction, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->absence_deduction > 0)
                                    <tr>
                                        <td>Absence Deduction</td>
                                        <td class="text-end">{{ number_format($record->absence_deduction, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->loan_deduction > 0)
                                    <tr>
                                        <td>Loan Deduction</td>
                                        <td class="text-end">{{ number_format($record->loan_deduction, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($record->cash_advance_deduction > 0)
                                    <tr>
                                        <td>Cash Advance</td>
                                        <td class="text-end">{{ number_format($record->cash_advance_deduction, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><strong>Total Deductions</strong></td>
                                        <td class="text-end"><strong class="text-danger">{{ number_format($record->total_deductions, 2) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Net Pay -->
                    <div class="payslip-total text-center">
                        <h6 class="text-muted mb-2">NET PAY</h6>
                        <h2 class="text-success mb-0">{{ number_format($record->net_pay, 2) }}</h2>
                    </div>

                    <!-- Work Summary -->
                    <div class="payslip-section mt-4">
                        <h6 class="payslip-section-title"><i class="fa fa-clock-o me-2"></i>Work Summary</h6>
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="p-2 bg-light rounded">
                                    <h5 class="mb-0">{{ $record->days_worked }}</h5>
                                    <small class="text-muted">Days Worked</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-light rounded">
                                    <h5 class="mb-0">{{ $record->regular_hours }}</h5>
                                    <small class="text-muted">Regular Hours</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-light rounded">
                                    <h5 class="mb-0">{{ $record->overtime_hours }}</h5>
                                    <small class="text-muted">Overtime Hours</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="p-2 bg-light rounded">
                                    <h5 class="mb-0">{{ $record->absences }}</h5>
                                    <small class="text-muted">Absences</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/payroll/payroll/view/' . $record->payroll_period_id) }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Payroll
                </a>
            </div>
        </div>
    </div>
</div>

<script nonce="{{ $cspNonce }}" src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
@endsection
