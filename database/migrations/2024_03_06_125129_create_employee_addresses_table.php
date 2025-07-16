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
        Schema::create('employee_addresses', function (Blueprint $table) {
            // Employee Contact Details (Tab 2)
            $table->bigIncrements('id');

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->string('permanent_address')->nullable();
            $table->string('present_address')->nullable();

            $table->unsignedBigInteger('permanent_state_id')->nullable();
            $table->foreign('permanent_state_id')->references('id')->on('states');

            $table->unsignedBigInteger('present_state_id')->nullable();
            $table->foreign('present_state_id')->references('id')->on('states');

            $table->string('permanent_city')->nullable();
            $table->string('present_city')->nullable();

            $table->string('permanent_pincode')->nullable();
            $table->string('present_pincode')->nullable();

            $table->string('email')->nullable();
            $table->string('mobile1')->nullable();
            $table->string('mobile2')->nullable();
            $table->enum('same_as_present', [0, 1])->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_addresses');
    }
};
