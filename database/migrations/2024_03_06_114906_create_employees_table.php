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
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('employee_code')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('reference')->nullable();
            $table->string('reference_tel_no')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('phone')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->string('aadharcard_img')->nullable();
            $table->string('aadharcard_img_path')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->string('device_token')->nullable();
            $table->string('ip')->nullable();
            $table->string('update_from_ip')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
