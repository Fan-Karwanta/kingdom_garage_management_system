<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollRecord extends Model
{
    protected $table = 'payroll_records';
    
    protected $fillable = [
        'payroll_period_id',
        'user_id',
        'days_worked',
        'regular_hours',
        'overtime_hours',
        'night_diff_hours',
        'holiday_hours',
        'late_hours',
        'undertime_hours',
        'absences',
        'basic_pay',
        'overtime_pay',
        'night_diff_pay',
        'holiday_pay',
        'allowances',
        'bonus',
        'other_earnings',
        'gross_pay',
        'sss_contribution',
        'philhealth_contribution',
        'pagibig_contribution',
        'tax_withholding',
        'late_deduction',
        'undertime_deduction',
        'absence_deduction',
        'loan_deduction',
        'cash_advance_deduction',
        'other_deductions',
        'total_deductions',
        'net_pay',
        'status',
        'remarks',
    ];

    protected $casts = [
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
    ];

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
