<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceImportsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->integer('total_records')->default(0);
            $table->integer('successful_records')->default(0);
            $table->integer('failed_records')->default(0);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_log')->nullable();
            $table->unsignedInteger('imported_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_imports');
    }
}
