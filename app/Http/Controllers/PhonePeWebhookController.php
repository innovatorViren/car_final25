<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhonePeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
        Log::info('PhonePe Webhook Received:', $request->all());
            // // Raw body
            // $rawBody = $request->getContent();

            // // Headers
            // $xVerify = $request->header('X-VERIFY');

            // Log::info('PhonePe Webhook Received', [
            //     'headers' => $request->headers->all(),
            //     'body' => $rawBody
            // ]);

            // // Decode JSON
            // $data = json_decode($rawBody, true);

            // if (!$data) {
            //     Log::error('Invalid JSON');
            //     return response()->json(['status' => 'failed'], 400);
            // }

            // // 🔐 VERIFY SIGNATURE
            // $saltKey = env('PHONEPE_SALT_KEY');
            // $saltIndex = env('PHONEPE_SALT_INDEX');

            // $calculatedChecksum = hash('sha256', $rawBody . $saltKey) . "###" . $saltIndex;

            // if ($xVerify !== $calculatedChecksum) {
            //     Log::error('Checksum mismatch', [
            //         'expected' => $calculatedChecksum,
            //         'received' => $xVerify
            //     ]);

            //     return response()->json(['status' => 'unauthorized'], 401);
            // }

            // // ✅ Process Payment Data
            // $transactionId = $data['data']['merchantTransactionId'] ?? null;
            // $status = $data['data']['state'] ?? null;

            // if ($status === 'COMPLETED') {
            //     // Payment success logic
            //     Log::info("Payment SUCCESS: " . $transactionId);

            //     // TODO: update DB
            // } elseif ($status === 'FAILED') {
            //     Log::info("Payment FAILED: " . $transactionId);
            // } else {
            //     Log::info("Payment PENDING: " . $transactionId);
            // }

            // return response()->json(['status' => 'success'], 200);
            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Webhook Exception', [
                'error' => $e->getMessage()
            ]);

            return response()->json(['status' => 'error'], 500);
        }
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
