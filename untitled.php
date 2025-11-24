<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhonePeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('PhonePe Webhook Received:', $request->all());

        $eventType = $request->input('eventType');
        $data = $request->input('data');

        // Transaction ID from PhonePe
        $transactionId = $data['merchantTransactionId'] ?? null;
        $status = $data['transactionStatus'] ?? null;

        // SUCCESS
        if ($status === 'SUCCESS') {
            // Mark order as paid
            Order::where('transaction_id', $transactionId)
                ->update([
                    'payment_status' => 'PAID'
                ]);
        }

        // FAILURE
        if ($status === 'FAILED') {
            Order::where('transaction_id', $transactionId)
                ->update([
                    'payment_status' => 'FAILED'
                ]);
        }

        // Send 200 response or PhonePe will retry
        return response()->json(['success' => true], 200);
    }
}
