<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('leave_type'); // vacation, sick, emergency, maternity, paternity, unpaid
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_count')->default(1);
            $table->boolean('is_paid')->default(true);
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
