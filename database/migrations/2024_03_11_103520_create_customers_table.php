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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('person_name');
            $table->string('email', 255)->nullable();
            $table->integer('managed_by')->nullable();
            $table->string('credit_days')->nullable();
            $table->string('credit_limit')->nullable();
            $table->string('mobile', 15)->nullable();

            $table->string('pan_no', 20)->nullable();
            $table->string('gst_type')->nullable();
            $table->string('gst_no', 20)->nullable();
            $table->string('fssai_no', 20)->nullable();
            $table->enum('is_create_user', [0, 1])->default(0);
            $table->string('pan_card_photo')->nullable();
            $table->string('gst_certificate_photo')->nullable();

            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
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
        Schema::dropIfExists('customers');
    }
};
