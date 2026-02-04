<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->date('date');
            $table->datetime('clock_in')->nullable();
            $table->datetime('clock_out')->nullable();
            $table->datetime('break_start')->nullable();
            $table->datetime('break_end')->nullable();
            $table->decimal('total_hours', 5, 2)->default(0);
            $table->decimal('regular_hours', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('night_diff_hours', 5, 2)->default(0);
            $table->decimal('late_minutes', 5, 2)->default(0);
            $table->decimal('undertime_minutes', 5, 2)->default(0);
            $table->string('status')->default('present'); // present, absent, late, half-day, on-leave, holiday
            $table->string('source')->default('manual'); // manual, biometric, csv_import
            $table->string('biometric_id')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->unique(['user_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
