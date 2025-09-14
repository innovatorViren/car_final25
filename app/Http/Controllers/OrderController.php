<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
    public function create()
    {
        //
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
                        DB::raw("(CASE WHEN W.scheduled_date IS NOT NULL THEN DATE_FORMAT(W.scheduled_date, '%d-%m-%Y') ELSE '' END) as scheduled_date"),
                        DB::raw("(CASE WHEN W.start_time IS NOT NULL THEN DATE_FORMAT(W.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
                        DB::raw("(CASE WHEN W.end_time IS NOT NULL THEN DATE_FORMAT(W.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
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
}
