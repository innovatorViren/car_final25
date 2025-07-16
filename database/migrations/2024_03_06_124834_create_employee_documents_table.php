<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
     *
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            // Employee Document Details (Tab 3)
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->string('aadhar_card_no')->nullable();
            $table->string('aadharcard_img')->nullable();
            $table->string('aadharcard_img_path')->nullable();
            $table->string('driving_licence_no')->nullable();
            $table->string('drivinglicence_img')->nullable();
            $table->string('drivinglicence_img_path')->nullable();
            $table->string('pan_card_no')->nullable();
            $table->string('pancard_img')->nullable();
            $table->string('pancard_img_path')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('passport_img')->nullable();
            $table->string('passport_img_path')->nullable();
            $table->string('uan_no')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};
