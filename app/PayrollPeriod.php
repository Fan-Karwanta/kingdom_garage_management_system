<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollPeriod extends Model
{
    protected $table = 'payroll_periods';
    
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'pay_date',
        'status',
        'branch_id',
        'total_gross',
        'total_deductions',
        'total_net',
        'employee_count',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'pay_date' => 'date',
        'approved_at' => 'datetime',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net' => 'decimal:2',
    ];

    public function payrollRecords()
    {
        return $this->hasMany(PayrollRecord::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
