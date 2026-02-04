<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPeriodsTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('pay_date')->nullable();
            $table->string('status')->default('draft'); // draft, processing, approved, paid, cancelled
            $table->integer('branch_id')->nullable();
            $table->decimal('total_gross', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('total_net', 15, 2)->default(0);
            $table->integer('employee_count')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_periods');
    }
}
