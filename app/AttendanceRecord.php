<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $table = 'attendance_records';
    
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'total_hours',
        'regular_hours',
        'overtime_hours',
        'night_diff_hours',
        'late_minutes',
        'undertime_minutes',
        'status',
        'source',
        'biometric_id',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
