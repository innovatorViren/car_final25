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
        Schema::table('settings', function (Blueprint $table) {
            $settingData = [
                [
                    'name' => 'password_reset_date',
                    'title' => 'Password Reset Date',
                    'value' => now(),
                    'group' => 'login_configuration',
                ]
             ];
             DB::table('settings')->insert($settingData);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
