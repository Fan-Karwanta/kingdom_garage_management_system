<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysPayrollTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('type')->default('regular'); // regular, special
            $table->decimal('pay_multiplier', 4, 2)->default(2.0);
            $table->boolean('is_recurring')->default(false);
            $table->integer('branch_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_holidays');
    }
}
