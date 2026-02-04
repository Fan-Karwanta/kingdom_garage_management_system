<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $record->user->name }} {{ $record->user->lastname }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .payslip {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
        }
        .header {
            background: #2596BE;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .company-info {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }
        .employee-info {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #2596BE;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            padding: 5px 0;
        }
        table td:last-child {
            text-align: right;
        }
        .total-row {
            border-top: 1px solid #ddd;
            font-weight: bold;
        }
        .net-pay {
            background: #f5f5f5;
            padding: 15px;
            text-align: center;
            margin-top: 20px;
        }
        .net-pay h2 {
            color: #28a745;
            font-size: 28px;
        }
        .two-columns {
            display: flex;
            gap: 30px;
        }
        .two-columns > div {
            flex: 1;
        }
        .footer {
            padding: 15px 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        @media print {
            body { padding: 0; }
            .payslip { border: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="payslip">
        <div class="header">
            <h1>PAYSLIP</h1>
            <p>{{ $record->payrollPeriod->name }}</p>
            <p>{{ $record->payrollPeriod->start_date->format('M d, Y') }} - {{ $record->payrollPeriod->end_date->format('M d, Y') }}</p>
        </div>

        <div class="company-info">
            <div>
                @if($logo)
                <strong>{{ $logo->system_name ?? 'Company Name' }}</strong>
                @endif
            </div>
            <div style="text-align: right;">
                <strong>Pay Date:</strong> {{ $record->payrollPeriod->pay_date ? $record->payrollPeriod->pay_date->format('M d, Y') : 'N/A' }}
            </div>
        </div>

        <div class="employee-info">
            <div>
                <strong>{{ $record->user->name }} {{ $record->user->lastname }}</strong><br>
                {{ $record->user->designation ?? 'Employee' }}<br>
                {{ $record->user->email }}
            </div>
            <div style="text-align: right;">
                <strong>Employee ID:</strong> {{ $record->user->id }}<br>
                @if($salary && $salary->bank_name)
                <strong>Bank:</strong> {{ $salary->bank_name }}<br>
                <strong>Account:</strong> {{ $salary->bank_account_number }}
                @endif
            </div>
        </div>

        <div class="content">
            <div class="two-columns">
                <div class="section">
                    <div class="section-title">EARNINGS</div>
                    <table>
                        <tr>
                            <td>Basic Pay</td>
                            <td>{{ number_format($record->basic_pay, 2) }}</td>
                        </tr>
                        @if($record->overtime_pay > 0)
                        <tr>
                            <td>Overtime Pay ({{ $record->overtime_hours }} hrs)</td>
                            <td>{{ number_format($record->overtime_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->night_diff_pay > 0)
                        <tr>
                            <td>Night Differential</td>
                            <td>{{ number_format($record->night_diff_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->holiday_pay > 0)
                        <tr>
                            <td>Holiday Pay</td>
                            <td>{{ number_format($record->holiday_pay, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->allowances > 0)
                        <tr>
                            <td>Allowances</td>
                            <td>{{ number_format($record->allowances, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->bonus > 0)
                        <tr>
                            <td>Bonus</td>
                            <td>{{ number_format($record->bonus, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td>GROSS PAY</td>
                            <td>{{ number_format($record->gross_pay, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <div class="section-title">DEDUCTIONS</div>
                    <table>
                        @if($record->sss_contribution > 0)
                        <tr>
                            <td>SSS</td>
                            <td>{{ number_format($record->sss_contribution, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->philhealth_contribution > 0)
                        <tr>
                            <td>PhilHealth</td>
                            <td>{{ number_format($record->philhealth_contribution, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->pagibig_contribution > 0)
                        <tr>
                            <td>Pag-IBIG</td>
                            <td>{{ number_format($record->pagibig_contribution, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->tax_withholding > 0)
                        <tr>
                            <td>Tax Withholding</td>
                            <td>{{ number_format($record->tax_withholding, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->late_deduction > 0)
                        <tr>
                            <td>Late Deduction</td>
                            <td>{{ number_format($record->late_deduction, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->loan_deduction > 0)
                        <tr>
                            <td>Loan</td>
                            <td>{{ number_format($record->loan_deduction, 2) }}</td>
                        </tr>
                        @endif
                        @if($record->cash_advance_deduction > 0)
                        <tr>
                            <td>Cash Advance</td>
                            <td>{{ number_format($record->cash_advance_deduction, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td>TOTAL DEDUCTIONS</td>
                            <td>{{ number_format($record->total_deductions, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="net-pay">
                <p style="margin-bottom: 5px;">NET PAY</p>
                <h2>{{ number_format($record->net_pay, 2) }}</h2>
            </div>

            <div class="section" style="margin-top: 20px;">
                <div class="section-title">WORK SUMMARY</div>
                <table>
                    <tr>
                        <td>Days Worked</td>
                        <td>{{ $record->days_worked }}</td>
                        <td>Regular Hours</td>
                        <td>{{ $record->regular_hours }}</td>
                    </tr>
                    <tr>
                        <td>Overtime Hours</td>
                        <td>{{ $record->overtime_hours }}</td>
                        <td>Absences</td>
                        <td>{{ $record->absences }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            This is a computer-generated payslip. No signature required.<br>
            Generated on {{ now()->format('M d, Y h:i A') }}
        </div>
    </div>
</body>
</html>
