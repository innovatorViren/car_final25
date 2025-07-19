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
        Schema::create('car_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('car_brand_id')->unsigned();
            $table->foreign('car_brand_id')->references('id')->on('car_brands');
            $table->string('name');
            $table->string('model_photo')->nullable();
            $table->string('model_photo_path')->nullable();
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
        Schema::dropIfExists('car_models');
    }
};
