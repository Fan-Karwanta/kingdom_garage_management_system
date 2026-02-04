<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceImport extends Model
{
    protected $table = 'attendance_imports';
    
    protected $fillable = [
        'filename',
        'original_filename',
        'total_records',
        'successful_records',
        'failed_records',
        'status',
        'error_log',
        'imported_by',
    ];

    public function importer()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }
}
