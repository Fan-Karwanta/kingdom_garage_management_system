<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    protected $table = 'employee_schedules';
    
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_rest_day',
        'is_active',
    ];

    protected $casts = [
        'is_rest_day' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getDayName($dayOfWeek)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$dayOfWeek] ?? '';
    }
}
