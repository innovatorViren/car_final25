<?php

namespace App\Http\Controllers;

use App\Models\{Order,Wash};
use Illuminate\Http\Request;
use App\DataTables\OrderDataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:orders.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:orders.delete', ['only' => ['destroy']]);
        $this->common = new CommonController();
        $this->title = trans("orders.title");
        view()->share('title', $this->title);
    }

    public function index(OrderDataTable $dataTable)
    {
        $this->data['customer'] =  $this->common->getCustomer();
        // $this->data['shop'] =  $this->common->getShop();
        // $salesname = $this->common->getSalename();

        // $this->data['salesname'] = $salesname;
        return $dataTable->render('orders.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->data['customers'] = $this->common->getCustomer();
        $this->data['carBrands'] = $this->common->getCarBrand();
        $this->data['frequency']  = Config('global.frequency');
        // $this->data['carModel'] = $this->common->getCarModel();

        $lastOrder = Order::latest()->first();
        $lastOrderNumber = $lastOrder ? (int) str_replace('O-', '', $lastOrder->id) : 0;
        $newOrderNumber = $lastOrderNumber + 1;
        $orderSeries = 'O-' . sprintf('%03d', $newOrderNumber);
        $this->data['generateCode'] =  $orderSeries ?? '';

        return view('orders.create', $this->data);
    }


    public function generateSlots(Request $request)
    {
        $frequencyType = $request->frequency;
        $startDate = $request->start_date;
        $startTime = $request->start_time;
        $endTime = $request->end_time; // already +1 hour
        $order_id = null;

        $slots = [];

        switch ($frequencyType) {
            case 'daily':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 30, $order_id);
                break;

            case 'weekly_2':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 8, $order_id);
                break;

            case 'weekly_1':
                $slots = $this->generateAlternateDaySlots($startDate, $startTime, $endTime, 4, $order_id);
                break;

            case 'one_time':
                $slots[] = [
                    'order_id' => $order_id,
                    'scheduled_date' => $startDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
                break;
        }

        return response()->json($slots);
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

        if($totalWashes == 30){
            $dayCount = 1;
        }else{
            $dayCount = 2;
        }

        for ($i = 0; $i < $totalWashes; $i++) {

            $scheduledDate = $start->copy()->addDays($i * $dayCount)->format('Y-m-d');
            $slots[] = [
                'order_id' => $order_id,
                'scheduled_date' => $scheduledDate,
                'start_time' => $startTimeObj->format('H:i:s'),
                'end_time' => $endTimeObj->format('H:i:s'),
            ];
        }

        return $slots;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $frequencyType = $request->frequency ?? null;
        $carModel = DB::table('car_models')->where('id',$request->car_model_id)->first();
        $planData = DB::table('plans')->where('car_size_id',$carModel->car_size_id)->where('frequency', $frequencyType)->whereNull('deleted_at')->first();
        $startDate = $request->start_date;
        $startTime = $request->start_time;
        $endTime   = $request->end_time;
        $totalWash = count($request->slots);
        $lastOrder = Order::latest()->first();
        $lastOrderNumber = $lastOrder ? (int) str_replace('O-', '', $lastOrder->id) : 0;
        $newOrderNumber = $lastOrderNumber + 1;
        $orderSeries = 'O-' . sprintf('%03d', $newOrderNumber);

        $vehicle = DB::table('customer_cars')->where('customer_id',$request->customer_id)->where('car_model_id',$request->car_model_id)->first();
        $endDate = Carbon::createFromFormat('d-m-Y',collect($request->slots)->last()['date'])->format('Y-m-d');

        $customerId = $request->customer_id ?? null;
        $inputData['code'] = $orderSeries;
        $inputData['date'] = Carbon::now()->format('Y-m-d');
        $inputData['customer_id'] = $customerId;
        $inputData['plan_id'] = $planData->id;
        $inputData['car_model_id'] = $request->car_model_id;
        $inputData['car_size_id'] = $carModel->car_size_id;
        $inputData['vehicle_name'] = $carModel->name ?? null;
        $inputData['vehicle_no'] = $vehicle->vehicle_no ?? null;
        $inputData['frequency_type'] = $frequencyType;
        $inputData['total_washes'] = $totalWash ?? null;
        $inputData['price'] = $planData->price ?? null;
        $inputData['pay_amount'] = null;
        $inputData['start_date'] = $startDate;
        $inputData['end_date'] = $endDate;
        $inputData['start_time'] = $startTime;
        $inputData['end_time'] = $endTime;
        $inputData['customer_adress_id'] = $request->customer_adress_id ?? null;
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

        return redirect()->route('orders.index')->with('success', __('common.create_success'));
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $fields = [
                'O.id as id',
                'O.code as code',
                DB::raw("(CASE WHEN O.date IS NOT NULL THEN DATE_FORMAT(O.date, '%d-%m-%Y') ELSE '' END) as date"),
                'O.total_washes as total_washes',
                'O.status as status',
                'O.price as price',
                'O.pay_amount as pay_amount',
                DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
                DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
            ];
        $order = DB::table('orders as O')
                 ->select($fields)
                ->join('customers as C','C.id','O.customer_id')
                ->leftjoin('employees as E', function ($join) {
                    $join->on('E.id', '=', 'O.employee_id');
                })
                ->where('O.id',$id)
                ->first();
        $orderItem = DB::table('washes as W')
                    ->select([
                        'W.id as wash_id',
                        'W.status as status',
                        'W.before_wash_photo as before_wash_photo',
                        'W.after_wash_photo as after_wash_photo',
                        DB::raw("(CASE WHEN W.scheduled_date IS NOT NULL THEN DATE_FORMAT(W.scheduled_date, '%d-%m-%Y') ELSE '' END) as scheduled_date"),
                        DB::raw("(CASE WHEN W.start_time IS NOT NULL THEN DATE_FORMAT(W.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                        DB::raw("(CASE WHEN W.end_time IS NOT NULL THEN DATE_FORMAT(W.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
                         DB::raw("(CASE WHEN W.wash_start_time IS NOT NULL THEN DATE_FORMAT(W.wash_start_time, ' %I:%i %p') ELSE '' END) as start_wash_time"),
                        DB::raw("(CASE WHEN W.wash_end_time IS NOT NULL THEN DATE_FORMAT(W.wash_end_time, ' %I:%i %p') ELSE '' END) as end_wash_time"),
                        DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as emp_first_name"),
                        DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as emp_last_name"),
                    ])
                    ->leftJoin('employees as E','E.id','W.employee_id')
                    ->where('W.order_id',$id)
                    ->get();
        $this->data['order'] = $order;
        $this->data['orderItem'] = $orderItem;
        return view('orders.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function orderStatus(Request $request, $id)
    {
        $status = $request->status;

        $orderItem = Wash::where('order_id', $id)->get();

        foreach ($orderItem as $item) {
            if ($item->status == 'pending') {
                $notification = true;
                Wash::where('id', $item->id)->update(['status' => 'cancelled']);
            } else if ($item->status == 'in_progress') {
                Wash::where('id', $item->id)->update(['status' => 'completed']);
            }
        }
        DB::table('orders')->where('id', $id)->update(['status' => 'Cancelled']);

        return redirect()->back()->with('success', __('common.update_success'));
    }

    public function getCustomerAddress(Request $request)
    {
        $account = DB::table('customers')->where('id', $request->customer_id)->first();

        $customerID = $request->customer_id;


        $customerAddressData = DB::table('customer_adresses as CA')
                // ->leftJoin('customers', 'CA.customer_id', '=', 'customers.id')
                ->leftJoin('countries', 'CA.country_id', '=', 'countries.id')
                ->leftJoin('states', 'CA.state_id', '=', 'states.id')
                ->select(
                        'CA.id as cus_add_id',
                        DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                        DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                        DB::raw("(CASE WHEN CA.landmark IS NOT NULL THEN  CA.landmark ELSE '' END) as landmark"),
                        'CA.address_type as address_type', 
                        'CA.is_default as is_default', 
                        'countries.name as country', 
                        'states.name as state', 
                        'CA.pincode')
                ->where('CA.customer_id', $customerID)
                ->whereNull('CA.deleted_at')
                ->get();
        $this->data['customerAddressData'] = $customerAddressData;

        return $this->data;
    }
}
