<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSalariesTable extends Migration
{
    public function up()
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('salary_type')->default('monthly'); // hourly, daily, monthly
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->decimal('transportation_allowance', 10, 2)->default(0);
            $table->decimal('meal_allowance', 10, 2)->default(0);
            $table->decimal('housing_allowance', 10, 2)->default(0);
            $table->boolean('sss_enabled')->default(true);
            $table->boolean('philhealth_enabled')->default(true);
            $table->boolean('pagibig_enabled')->default(true);
            $table->boolean('tax_enabled')->default(true);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->date('effective_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_salaries');
    }
}
