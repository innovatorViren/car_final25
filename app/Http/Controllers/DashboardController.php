<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Employee};
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');


        $this->common = new CommonController();
        $this->title = "Dashboard";
        view()->share('title', $this->title);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = DB::table('customers')->where('is_active','Yes')->whereNull('deleted_at')->count();
        $employee = DB::table('employees')->where('is_active','Yes')->whereNull('deleted_at')->count();
        $carModel = DB::table('car_models')->where('is_active','Yes')->whereNull('deleted_at')->count();
        $washes = DB::table('washes')->whereDate('scheduled_date', Carbon::today())->whereNull('deleted_at')->count();
        
        $this->data['totalCustomers'] = $customer;
        $this->data['totalEmployee'] = $employee;
        $this->data['todayOrder'] = $washes;
        $this->data['totalCarModel'] = $carModel;

        return view('dashboard.dashboard', $this->data);
    }
    // master main page
    public function masterPages()
    {
        $this->data['master_title'] = __('master.masters');
        return view('masters.index', $this->data);
    }
}
