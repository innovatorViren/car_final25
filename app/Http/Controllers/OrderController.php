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

        $lastOrder = Order::latest()->first();
        $lastOrderNumber = $lastOrder ? (int) str_replace('O-', '', $lastOrder->id) : 0;
        $newOrderNumber = $lastOrderNumber + 1;
        $orderSeries = 'O-' . sprintf('%03d', $newOrderNumber);
        $this->data['generateCode'] =  $orderSeries ?? '';

        return view('orders.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
