<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->nullable();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->unsignedBigInteger('car_model_id')->nullable();
            $table->unsignedBigInteger('car_size_id')->nullable();
            $table->string('vehicle_name')->nullable();
            $table->string('frequency_type')->nullable();
            $table->string('total_washes')->nullable();
            $table->double('price', 16, 2)->nullable();
            $table->double('pay_amount', 16, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable(); 
            $table->unsignedBigInteger('customer_adress_id')->nullable();
            $table->enum('status', ['Pending', 'Partial', 'Cancelled', 'Completed'])->default('Pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
