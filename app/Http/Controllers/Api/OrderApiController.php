<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;
use App\Models\{Order,Wash,Employee,Customer};
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Sentinel;

class OrderApiController extends ApiController
{
    public function getAdminDashboard()
    {

        $fields = [
                'O.id as order_id',
                'O.code as code',
                'O.total_washes as total_washes',
                'O.status as status',
                'O.pay_amount as pay_amount',
                'O.car_model_id as car_model_id',
                DB::raw("(CASE WHEN O.start_date IS NOT NULL THEN DATE_FORMAT(O.start_date, '%d-%m-%Y') ELSE '' END) as start_date"),
                DB::raw("(CASE WHEN O.start_time IS NOT NULL THEN DATE_FORMAT(O.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                DB::raw("(CASE WHEN O.end_time IS NOT NULL THEN DATE_FORMAT(O.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
                DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
                DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
                DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
                DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                DB::raw("(CASE WHEN LOWER(TRIM(O.status)) = 'pending' THEN 1 ELSE 0 END) as assign"),
            ];

            $orderData = DB::table('orders as O')
                    ->select($fields)
                    ->join('customers as C','C.id','O.customer_id')
                    ->join('car_models as CM','CM.id','O.car_model_id')
                    ->join('washes as W','W.order_id','O.id')
                    ->leftjoin('employees as E', function ($join) {
                        $join->on('E.id', '=', 'O.employee_id');
                    })
                    ->whereNull('O.deleted_at')
                    ->whereDate('W.scheduled_date', Carbon::today())
                    ->orderBy('O.id', 'DESC')
                    ->get();

            $employee = Employee::count();
            $customer = Customer::count();
            $order = Order::count();


            $this->data =  $orderData;
            $this->response_json['dashbordCard'] = [
                'employee'=>$employee,
                'customer'=>$customer,
                'order'=>$order,
                'today_order'=>$orderData->count(),
            ];
            $this->response_json['message'] = 'Success';
            return $this->responseSuccessWithoutObject();


    }
    public function addOrder(Request $request)
    {
        try {

            $requestData = Validator::make($this->request->all(), [
                'customer_id'=>'required',
                'plan_id'=>'required',
                'car_model_id'=>'required',
                'customer_adress_id'=>'required',
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $startDate = $request->start_date;
            $startTime = $request->start_time;
            $endTime   = $request->end_time;
            $frequencyType  = $request->frequency_type;


            $lastOrder = Order::latest()->first();
            $lastOrderNumber = $lastOrder ? (int) str_replace('O-', '', $lastOrder->id) : 0;
            $newOrderNumber = $lastOrderNumber + 1;
            $orderSeries = 'O-' . sprintf('%03d', $newOrderNumber);


            $customerId = $request->customer_id;
            $inputData['code'] = $orderSeries;
            $inputData['date'] = Carbon::now()->format('Y-m-d');
            $inputData['customer_id'] = $customerId;
            $inputData['plan_id'] = $request->plan_id;
            $inputData['car_model_id'] = $request->car_model_id;
            $inputData['car_size_id'] = $request->car_size_id;
            $inputData['vehicle_name'] = $request->vehicle_name ?? null;
            $inputData['frequency_type'] = $frequencyType;
            $inputData['total_washes'] = $request->total_washes;
            $inputData['price'] = $request->price;
            $inputData['pay_amount'] = $request->pay_amount;
            $inputData['start_date'] = $startDate;
            $inputData['end_date'] = $request->end_date;
            $inputData['start_time'] = $startTime;
            $inputData['end_time'] = $endTime;
            $inputData['customer_adress_id'] = $request->customer_adress_id;

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
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
    }

    private function generateAlternateDaySlots($startDate, $startTime, $endTime, $totalWashes,$order_id)
    {
        $slots = [];
        $start = Carbon::parse($startDate);

        try {
            $startTimeObj = Carbon::createFromFormat('g:i A', $startTime);
        } catch (\Exception $e) {
            $startTimeObj = Carbon::createFromFormat('H:i', $startTime);
        }

        try {
            $endTimeObj = Carbon::createFromFormat('g:i A', $endTime);
        } catch (\Exception $e) {
            $endTimeObj = Carbon::createFromFormat('H:i', $endTime);
        }

        for ($i = 0; $i < $totalWashes; $i++) {

            $scheduledDate = $start->copy()->addDays($i * 2)->format('Y-m-d');
            $slots[] = [
                'order_id' => $order_id,
                'scheduled_date' => $scheduledDate,
                'start_time' => $startTimeObj->format('H:i:s'),
                'end_time' => $endTimeObj->format('H:i:s'),
            ];
        }

        return $slots;
    }

    public function assignEmployee(Request $request)
    {
        DB::beginTransaction();
        try {

            $requestData = Validator::make($this->request->all(), [
                'type'=>'required',
                'id'=>'required',
                'emp_id'=>'required',
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $type = $request->type ?? false;
            $id = $request->id ?? null;
            $empId = $request->emp_id ?? null;
            if($type == 'main'){
                DB::table('orders')->where('id',$id)->update(['employee_id'=>$empId]);
                DB::table('washes')->where('order_id',$id)->update(['employee_id'=>$empId]);
            }else{
                DB::table('washes')->where('id',$id)->update(['employee_id'=>$empId]);

            }
            
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            info($e);
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        $this->response_json['message'] = 'Assign Employee Successfully!!';
        return $this->responseSuccess();
    }

    public function getOrderList(Request $request)
    {
        $page = $request->get('page', '');
        $user = $this->currentuser();
        $perPage = $this->perPageCommon();

        if($user->emp_type == 'non-employee')
        {
            $fields = [
                'O.id as order_id',
                'O.code as code',
                'O.total_washes as total_washes',
                'O.status as status',
                'O.pay_amount as pay_amount',
                'O.car_model_id as car_model_id',
                DB::raw("(CASE WHEN O.start_date IS NOT NULL THEN DATE_FORMAT(O.start_date, '%d-%m-%Y') ELSE '' END) as start_date"),
                DB::raw("(CASE WHEN O.start_time IS NOT NULL THEN DATE_FORMAT(O.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                DB::raw("(CASE WHEN O.end_time IS NOT NULL THEN DATE_FORMAT(O.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
                DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
                DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
                DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
                DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                DB::raw("(CASE WHEN LOWER(TRIM(O.status)) = 'pending' THEN 1 ELSE 0 END) as assign"),
            ];

            $orderData = DB::table('orders as O')
                    ->select($fields)
                    ->join('customers as C','C.id','O.customer_id')
                    ->join('car_models as CM','CM.id','O.car_model_id')
                    ->leftjoin('employees as E', function ($join) {
                        $join->on('E.id', '=', 'O.employee_id');
                    })
                    ->whereNull('O.deleted_at')
                    ->orderBy('O.id', 'DESC')
                    ->get();

        }elseif($user->emp_type == 'customer'){
            $fields = [
                'O.id as order_id',
                'O.code as code',
                'O.total_washes as total_washes',
                'O.status as status',
                'O.pay_amount as pay_amount',
                'O.car_model_id as car_model_id',
                DB::raw("(CASE WHEN O.start_date IS NOT NULL THEN DATE_FORMAT(O.start_date, '%d-%m-%Y') ELSE '' END) as start_date"),
                DB::raw("(CASE WHEN O.start_time IS NOT NULL THEN DATE_FORMAT(O.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                DB::raw("(CASE WHEN O.end_time IS NOT NULL THEN DATE_FORMAT(O.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
                DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
                DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
                DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
                DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                DB::raw(" 0  as assign"),
            ];

            $orderData = DB::table('orders as O')
                    ->select($fields)
                    ->join('customers as C','C.id','O.customer_id')
                    ->join('car_models as CM','CM.id','O.car_model_id')
                    ->leftjoin('employees as E', function ($join) {
                        $join->on('E.id', '=', 'O.employee_id');
                    })
                    ->whereNull('O.deleted_at')
                    ->where('O.customer_id',$user->customer_id)
                    ->orderBy('O.id', 'DESC')
                    ->get();
            
        }else{

            $fields = [
                'O.id as order_id',
                'O.code as code',
                'O.total_washes as total_washes',
                'O.status as status',
                'O.pay_amount as pay_amount',
                'O.car_model_id as car_model_id',
                DB::raw("(CASE WHEN O.start_date IS NOT NULL THEN DATE_FORMAT(O.start_date, '%d-%m-%Y') ELSE '' END) as start_date"),
                DB::raw("(CASE WHEN O.start_time IS NOT NULL THEN DATE_FORMAT(O.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                DB::raw("(CASE WHEN O.end_time IS NOT NULL THEN DATE_FORMAT(O.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
                DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
                DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
                DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
                DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                DB::raw(" 0  as assign"),
            ];

            $orderData = DB::table('orders as O')
                    ->select($fields)
                    ->join('customers as C','C.id','O.customer_id')
                    ->join('car_models as CM','CM.id','O.car_model_id')
                    ->leftjoin('employees as E', function ($join) {
                        $join->on('E.id', '=', 'O.employee_id');
                    })
                    ->whereNull('O.deleted_at')
                    ->where('O.employee_id',$user->emp_id)
                    ->orderBy('O.id', 'DESC')
                    ->get();
        }
        
        if (isset($orderData)) {
            $data = collect($orderData);
            $dataPerPage = $data->forPage($page, $perPage);
            $dataPerPage = array_values($dataPerPage->toArray());

            $result = new LengthAwarePaginator(
                $dataPerPage,
                $data->count(),
                $perPage,
                $page
            );
            $this->data =  $result;
            $this->response_json['message'] = 'Success';
            return $this->responseSuccessPagination();
        } else {
            $this->response_json['message'] = 'No data available';
            return $this->responseError();
        }

    }


    public function getOrderDetail($orderId)
    {
        $path = URL::asset('');
        $order = DB::table('orders as O')
                ->select(
                    'O.id as order_id',
                    'O.employee_id as employee_id',
                    'O.customer_adress_id as customer_adress_id',
                     DB::raw("(CASE WHEN O.vehicle_name IS NOT NULL THEN  O.vehicle_name ELSE '' END) as vehicle_name"),
                    'CM.name as model_name',
                    'P.name as plan_name',
                    'O.total_washes as total_washes',
                    'O.price as total_amount',
                    'O.pay_amount as pay_amount',
                )
                ->join('car_models as CM','CM.id','O.car_model_id')
                ->join('plans as P','P.id','O.plan_id')
                ->where('O.id',$orderId)->first();

        $fields = [
            'W.id as wash_id',
            'W.status as status',
            DB::raw("(CASE WHEN W.scheduled_date IS NOT NULL THEN DATE_FORMAT(W.scheduled_date, '%d-%m-%Y') ELSE '' END) as scheduled_date"),
            DB::raw("(CASE WHEN W.start_time IS NOT NULL THEN DATE_FORMAT(W.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
            DB::raw("(CASE WHEN W.end_time IS NOT NULL THEN DATE_FORMAT(W.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
            DB::raw("(CASE WHEN W.wash_start_time IS NOT NULL THEN DATE_FORMAT(W.wash_start_time, ' %I:%i %p') ELSE '' END) as wash_start_time"),
            DB::raw("(CASE WHEN W.wash_end_time IS NOT NULL THEN DATE_FORMAT(W.wash_end_time, ' %I:%i %p') ELSE '' END) as wash_end_time"),
            DB::raw("(CASE WHEN W.before_wash_photo !='' THEN  CONCAT('".$path."', W.before_wash_photo) ELSE '' END) as before_wash_photo"),
            DB::raw("(CASE WHEN W.after_wash_photo !='' THEN  CONCAT('".$path."', W.after_wash_photo) ELSE '' END) as after_wash_photo"),
            DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
            DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
        ];

        $washItem = DB::table('washes as W')
                ->select($fields)
                ->leftjoin('employees as E', function ($join) {
                    $join->on('E.id', '=', 'W.employee_id');
                })
                ->where('W.order_id',$orderId)
                ->whereNull('W.deleted_at')
                ->orderBy('W.id', 'ASC')
                ->get();

            $orderAddress = DB::table('customer_adresses as CA')
                            ->select('CA.id as customer_address_id',
                                DB::raw("(CASE WHEN CA.name IS NOT NULL THEN  CA.name ELSE '' END) as name"),
                                DB::raw("(CASE WHEN CA.mobile IS NOT NULL THEN  CA.mobile ELSE '' END) as mobile"),
                                DB::raw("(CASE WHEN CA.address_type IS NOT NULL THEN  CA.address_type ELSE '' END) as address_type"),
                                DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                                DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                                DB::raw("(CASE WHEN CA.landmark IS NOT NULL THEN  CA.landmark ELSE '' END) as landmark"),
                                DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                                'CA.country_id',
                                'CA.state_id',
                                'CA.city_id',
                                'CA.is_default',
                                'S.name as state',
                                'C.name as city'
                            )
                            ->leftjoin('states as S','S.id','CA.state_id')
                            ->leftjoin('cities as C','C.id','CA.city_id')
                            ->where('CA.id',$order->customer_adress_id)
                            ->first();
        
            $this->data =  $washItem;
            $this->response_json['order_car_model'] = $order->model_name;
            $this->response_json['vehicle_name'] = $order->vehicle_name;
            $this->response_json['total_wash'] = $order->total_washes;
            $this->response_json['plan_name'] = $order->plan_name;
            $this->response_json['total_amount'] = $order->total_amount ?? 0;
            $this->response_json['pay_amount'] = $order->pay_amount ?? 0;
            $this->response_json['order_address'] = $orderAddress;
            $this->response_json['message'] = 'Success';
            return $this->responseSuccessWithoutObject();

    }
}
