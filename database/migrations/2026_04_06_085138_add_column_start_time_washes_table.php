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
        Schema::table('washes', function (Blueprint $table) {
            $table->time('wash_start_time')->nullable()->after('end_time');
            $table->time('wash_end_time')->nullable()->after('wash_start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('washes', function (Blueprint $table) {
            //
        });
    }
};
