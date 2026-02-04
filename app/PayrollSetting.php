<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    protected $table = 'payroll_settings';
    
    protected $fillable = [
        'default_hourly_rate',
        'overtime_multiplier',
        'night_diff_multiplier',
        'holiday_multiplier',
        'regular_hours_per_day',
        'work_days_per_week',
        'night_diff_start',
        'night_diff_end',
        'pay_period',
        'cutoff_day_1',
        'cutoff_day_2',
        'sss_contribution_rate',
        'philhealth_contribution_rate',
        'pagibig_contribution_rate',
        'tax_rate',
        'branch_id',
    ];

    protected $casts = [
        'default_hourly_rate' => 'decimal:2',
        'overtime_multiplier' => 'decimal:2',
        'night_diff_multiplier' => 'decimal:2',
        'holiday_multiplier' => 'decimal:2',
    ];
}
