<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PlanDataTable;
use Centaur\AuthManager;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\{Plan};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class PlanController extends Controller
{
    public function __construct(AuthManager $authManager)
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->common = new CommonController();
        $this->authManager = $authManager;
        $this->middleware('permission:plan.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:plan.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:plan.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:plan.delete', ['only' => ['destroy']]);
        ini_set('memory_limit', '-1');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function index(PlanDataTable $dataTable)
    {
        return $dataTable->render('plan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('plan.create');
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
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        //
    }
}
