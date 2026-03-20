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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('merchant_order_id')->nullable()->after('status');
            $table->string('order_id')->nullable()->after('merchant_order_id');
            $table->string('payment_status')->nullable()->after('order_id');
            $table->string('payable_amount')->nullable()->after('payment_status');
            $table->string('currency')->nullable()->after('payable_amount');
            $table->string('transaction_id')->nullable()->after('currency');
            $table->string('payment_mode')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('payment_mode');
            $table->string('payment_time')->nullable()->after('payment_type');
            $table->string('transaction_state')->nullable()->after('payment_time');
            $table->string('utr')->nullable()->after('transaction_state');
            $table->string('upi_transaction_id')->nullable()->after('utr');
            $table->string('vpa')->nullable()->after('upi_transaction_id');
            $table->string('instrument')->nullable()->after('vpa');
            $table->string('meta_info')->nullable()->after('instrument');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('merchant_order_id');
            $table->dropColumn('order_id');
            $table->dropColumn('payment_status');
            $table->dropColumn('payable_amount');
            $table->dropColumn('currency');
            $table->dropColumn('transaction_id');
            $table->dropColumn('payment_mode');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_time');
            $table->dropColumn('transaction_state');
            $table->dropColumn('utr');
            $table->dropColumn('upi_transaction_id');
            $table->dropColumn('vpa');
            $table->dropColumn('instrument');
            $table->dropColumn('meta_info');
        });
    }
};
