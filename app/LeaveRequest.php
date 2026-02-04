<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'days_count',
        'is_paid',
        'status',
        'reason',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_paid' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
