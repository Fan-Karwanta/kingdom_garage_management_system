<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_period_id');
            $table->unsignedInteger('user_id');
            
            // Work hours
            $table->decimal('days_worked', 5, 2)->default(0);
            $table->decimal('regular_hours', 6, 2)->default(0);
            $table->decimal('overtime_hours', 6, 2)->default(0);
            $table->decimal('night_diff_hours', 6, 2)->default(0);
            $table->decimal('holiday_hours', 6, 2)->default(0);
            $table->decimal('late_hours', 6, 2)->default(0);
            $table->decimal('undertime_hours', 6, 2)->default(0);
            $table->integer('absences')->default(0);
            
            // Earnings
            $table->decimal('basic_pay', 12, 2)->default(0);
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('night_diff_pay', 12, 2)->default(0);
            $table->decimal('holiday_pay', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('other_earnings', 12, 2)->default(0);
            $table->decimal('gross_pay', 12, 2)->default(0);
            
            // Deductions
            $table->decimal('sss_contribution', 10, 2)->default(0);
            $table->decimal('philhealth_contribution', 10, 2)->default(0);
            $table->decimal('pagibig_contribution', 10, 2)->default(0);
            $table->decimal('tax_withholding', 10, 2)->default(0);
            $table->decimal('late_deduction', 10, 2)->default(0);
            $table->decimal('undertime_deduction', 10, 2)->default(0);
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('loan_deduction', 10, 2)->default(0);
            $table->decimal('cash_advance_deduction', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            
            // Net pay
            $table->decimal('net_pay', 12, 2)->default(0);
            
            $table->string('status')->default('pending'); // pending, approved, paid
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('payroll_period_id');
            $table->index('user_id');
            $table->unique(['payroll_period_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_records');
    }
}
