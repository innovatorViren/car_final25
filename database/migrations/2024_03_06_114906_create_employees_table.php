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

            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('person_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('hobbies')->nullable();
            $table->string('photo')->nullable();
            $table->string('reference')->nullable();
            $table->string('reference_tel_no')->nullable();

            $table->text('strengths')->nullable();
            $table->text('weakness')->nullable();
            $table->string('blood_group')->nullable();

            $table->string('beneficiary_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('branch_name')->nullable();

            $table->text('academic_background')->nullable();
            $table->string('courses')->nullable();

            $table->text('experience')->nullable();
            $table->string('total_experience')->nullable();
            $table->date('join_date')->nullable();
            $table->float('casual_leave_balance', 4, 2)->nullable();
            $table->float('sick_leave_balance', 4, 2)->nullable();
            $table->date('left_date')->nullable();
            $table->date('rejoin_date')->nullable();
            $table->unsignedBigInteger('parent_employee_id')->nullable();
            $table->text('left_reason')->nullable();
            $table->enum('recruit_again', ['Yes', 'No'])->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->string('grade')->nullable();

            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->tinyInteger('is_current')->default('1');
            $table->string('photo_path')->nullable();
            $table->string('device_token')->nullable();

            $table->string('employee_code')->nullable();
            $table->string('designation_of_appointee')->nullable();
            $table->string('appointed_by')->nullable();

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
