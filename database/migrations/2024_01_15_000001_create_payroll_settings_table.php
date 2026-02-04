<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('default_hourly_rate', 10, 2)->default(0);
            $table->decimal('overtime_multiplier', 4, 2)->default(1.5);
            $table->decimal('night_diff_multiplier', 4, 2)->default(1.1);
            $table->decimal('holiday_multiplier', 4, 2)->default(2.0);
            $table->integer('regular_hours_per_day')->default(8);
            $table->integer('work_days_per_week')->default(5);
            $table->time('night_diff_start')->default('22:00:00');
            $table->time('night_diff_end')->default('06:00:00');
            $table->string('pay_period')->default('semi-monthly'); // weekly, bi-weekly, semi-monthly, monthly
            $table->integer('cutoff_day_1')->default(15);
            $table->integer('cutoff_day_2')->default(30);
            $table->decimal('sss_contribution_rate', 5, 4)->default(0.045);
            $table->decimal('philhealth_contribution_rate', 5, 4)->default(0.025);
            $table->decimal('pagibig_contribution_rate', 5, 4)->default(0.02);
            $table->decimal('tax_rate', 5, 4)->default(0);
            $table->integer('branch_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_settings');
    }
}
