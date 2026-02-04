<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $table = 'employee_salaries';
    
    protected $fillable = [
        'user_id',
        'salary_type',
        'basic_salary',
        'hourly_rate',
        'daily_rate',
        'allowance',
        'transportation_allowance',
        'meal_allowance',
        'housing_allowance',
        'sss_enabled',
        'philhealth_enabled',
        'pagibig_enabled',
        'tax_enabled',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'effective_date',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'sss_enabled' => 'boolean',
        'philhealth_enabled' => 'boolean',
        'pagibig_enabled' => 'boolean',
        'tax_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
