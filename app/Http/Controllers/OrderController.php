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
    public function show(Order $order)
    {
        //
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
