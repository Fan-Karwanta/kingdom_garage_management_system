<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
{
    protected $table = 'payroll_deductions';
    
    protected $fillable = [
        'user_id',
        'deduction_type',
        'description',
        'amount',
        'monthly_deduction',
        'remaining_balance',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'monthly_deduction' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
