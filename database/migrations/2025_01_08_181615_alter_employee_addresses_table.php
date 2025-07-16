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
        Schema::table('employee_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('permanent_city')->nullable()->change();
            $table->unsignedBigInteger('present_city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_addresses', function (Blueprint $table) {
            $table->string('permanent_city')->nullable();
            $table->string('present_city')->nullable();
        });
    }
};
