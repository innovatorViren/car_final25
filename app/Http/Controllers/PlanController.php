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
    public function index(PlanDataTable $dataTable)
    {
        return $dataTable->render('plan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['carSizes']  = Config('global.car_sizes');
        $this->data['frequency']  = Config('global.frequency');
        return view('plan.create',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'car_size_id' => 'nullable|integer',
            'frequency' => 'required|in:one_time,daily,weekly_2x,weekly_4x',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        Plan::create($validated);
        return redirect()->route('plan.index')->with('success', 'Plan created successfully.');
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
    public function edit($id)
    {
        $plan = Plan::find($id);
        // dd($plan);
        $this->data['plan'] = $plan;
        $this->data['carSizes']  = Config('global.car_sizes');
        $this->data['frequency']  = Config('global.frequency');
        return view('plan.edit',$this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'car_size_id' => 'nullable|integer',
            'frequency' => 'required|in:one_time,daily,weekly_2x,weekly_4x',
            'price' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $plan = Plan::findOrFail($id);
        $data = $request->except(['_token', '_method','saveBtn','saveExitBtn']);
        $plan->update($data);
        return redirect()->route('plan.index')->with('success', 'Plan updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        if ($plan) {
            $dependency = $plan->deleteValidate($id);
                $state->delete();
        }
        return response()->json([
            'success' => true,
            'message' => __('Plan deleted successfully.'),
        ], 200);
    }
}
