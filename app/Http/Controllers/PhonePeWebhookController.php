<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PhonePeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log full webhook
        Log::info('PhonePe Webhook:', $request->all());

        $data = $request->all();

        // Check event type
        if (($data['event'] ?? '') === 'checkout.order.completed') {

            $payload = $data['payload'] ?? [];

            $merchantOrderId = $payload['merchantOrderId'] ?? null;
            $orderId = $payload['orderId'] ?? null;

            $paymentDetails = $payload['paymentDetails'][0] ?? [];

            $transactionId = $paymentDetails['transactionId'] ?? null;
            $paymentMode = $paymentDetails['paymentMode'] ?? null;
            $amount = $payload['amount'] ?? 0;
            $status = $payload['state'] ?? 'FAILED';

            // Find your order (based on merchantOrderId)
            $order = Order::where('transaction_id', $transactionId)->first();

            if ($order) {
                $order->update([
                    'transaction_id' => $transactionId,
                    'order_id' => $orderId,
                    'payment_mode' => $paymentMode,
                    'amount' => $amount,
                    'state' => $status,
                ]);
            } else {
                Log::error('Order not found for: ' . $transactionId);
            }
        }

        return response()->json(['success' => true]);
    }
    // public function handle(Request $request)
    // {
    //     Log::info('PhonePe Webhook Received:', $request->all());

    //     $eventType = $request->input('eventType');
    //     $data = $request->input('data');

    //     // Transaction ID from PhonePe
    //     $transactionId = $data['merchantTransactionId'] ?? null;
    //     $status = $data['transactionStatus'] ?? null;

    //     // SUCCESS
    //     if ($status === 'SUCCESS') {
    //         // Mark order as paid
    //         Order::where('transaction_id', $transactionId)
    //             ->update([
    //                 'payment_status' => 'PAID'
    //             ]);
    //     }

    //     // FAILURE
    //     if ($status === 'FAILED') {
    //         Order::where('transaction_id', $transactionId)
    //             ->update([
    //                 'payment_status' => 'FAILED'
    //             ]);
    //     }

    //     // Send 200 response or PhonePe will retry
    //     return response()->json(['success' => true], 200);
    // }
}
