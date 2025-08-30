<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;
use App\Models\{Order,Wash};
use Carbon\Carbon;

class OrderApiController extends ApiController
{
    public function addOrder(Request $request)
    {
        try {

            $startDate = $request->start_date;
            $startTime = $request->start_time;
            $endTime   = $request->end_time;
            $frequencyType  = $request->frequency_type;


            $lastOrder = Order::latest()->first();
            $lastOrderNumber = $lastOrder ? (int) str_replace('O-', '', $lastOrder->order_id) : 0;
            $newOrderNumber = $lastOrderNumber + 1;
            $orderSeries = 'O-' . sprintf('%03d', $newOrderNumber);



            $customerId = $request->customer_id;
            $inputData['code'] = $orderSeries;
            $inputData['date'] = Carbon::now()->format('Y-m-d');
            $inputData['customer_id'] = $customerId;
            $inputData['plan_id'] = $request->plan_id;
            $inputData['car_model_id'] = $request->car_model_id;
            $inputData['car_size_id'] = $request->car_size_id;
            $inputData['frequency_type'] = $frequencyType;
            $inputData['total_washes'] = $request->total_washes;
            $inputData['price'] = $request->price;
            $inputData['pay_amount'] = $request->pay_amount;
            $inputData['start_date'] = $startDate;
            $inputData['end_date'] = $request->end_date;
            // $inputData['start_time'] = $startTime;
            // $inputData['end_time'] = $endTime;

            $model = Order::create($inputData);
            $order_id  = $model->id;



            $slots = [];
            switch ($frequencyType) {
                case 'daily':
                    $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 30,$order_id);
                    break;

                case 'weekly_2':
                    $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 8,$order_id);
                    break;

                case 'weekly_1':
                    $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 4,$order_id);
                    break;

                case 'one_time':
                    $slots[] = [
                        'order_id' => $order_id,
                        'scheduled_date' => Carbon::parse($startDate)->format('Y-m-d'),
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ];
                    break;
            }

            Wash::insert($slots);
            $this->response_json['status'] = 1;
            $this->response_json['message'] = 'Your order has been placed successfully!';

            return $this->responseSuccessWithoutDataObject();
            
             

        DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            info($e);
            return "Exception " . $e->getMessage();
        }
    }

    private function generateAlternateDaySlots($startDate, $startTime, $endTime, $totalWashes,$order_id)
    {
        $slots = [];
        $start = Carbon::parse($startDate)->setTimeFromTimeString($startTime);
        $end   = Carbon::parse($endTime)->format('g:i A');

        for ($i = 0; $i < $totalWashes; $i++) {
            $slots[] = [
                'order_id' => $order_id,
                'scheduled_date' => $start->copy()->addDays($i * 2)->format('Y-m-d'),
                'start_time' => $start->format('g:i A'),
                'end_time' => $end
            ];
        }

        return $slots;
    }
}
