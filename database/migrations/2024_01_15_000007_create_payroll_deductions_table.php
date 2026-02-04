<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollDeductionsTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('deduction_type'); // loan, cash_advance, other
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->decimal('monthly_deduction', 12, 2)->default(0);
            $table->decimal('remaining_balance', 12, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_deductions');
    }
}
