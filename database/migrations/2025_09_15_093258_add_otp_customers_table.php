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
        Schema::table('customers', function (Blueprint $table) {
            $table->timestamp('otp_verified_at')->nullable()->after('otp');
            $table->tinyInteger('is_otp_verified')->nullable()->default(0)->after('otp_verified_at');
            $table->timestamp('expired_at')->nullable()->after('otp_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['otp_verified_at', 'is_otp_verified','expired_at']);
        });
    }
};
