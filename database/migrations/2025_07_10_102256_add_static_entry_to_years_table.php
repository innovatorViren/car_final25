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
        Schema::table('years', function (Blueprint $table) {
            $yearData = [
                [
                    'yearname' => '2025-26',
                    'is_default' => 'Yes',
                    'is_displayed' => 'Yes',
                    'from_date' => '2025-04-01',
                    'to_date' => '2026-03-31',
                ]
             ];
             DB::table('years')->insert($yearData);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('years', function (Blueprint $table) {
            //
        });
    }
};
