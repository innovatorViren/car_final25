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
         Schema::table('plans', function (Blueprint $table) {
            // First drop the ENUM column if it exists
            $table->dropColumn('frequency');
        });

        Schema::table('plans', function (Blueprint $table) {
            // Then re-add as nullable string
            $table->string('frequency')->nullable()->after('car_size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('frequency');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->enum('frequency', ['one_time', 'daily', 'weekly_2x', 'weekly_4x'])->default('one_time');
        });
    }
};
