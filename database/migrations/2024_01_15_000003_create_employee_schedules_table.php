<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->boolean('is_rest_day')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_schedules');
    }
}
