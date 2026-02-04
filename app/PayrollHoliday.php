<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollHoliday extends Model
{
    protected $table = 'payroll_holidays';
    
    protected $fillable = [
        'name',
        'date',
        'type',
        'pay_multiplier',
        'is_recurring',
        'branch_id',
    ];

    protected $casts = [
        'date' => 'date',
        'pay_multiplier' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];
}
