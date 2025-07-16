<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\StatesDataTable;
use App\Models\{Country, State};
use Illuminate\Http\Request;
use App\Http\Requests\StatesRequest;
use Session;
use Flash;
use Sentinel;
use Carbon\Carbon;
use DB;

class StateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:state.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:state.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:state.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:state.delete', ['only' => ['destroy']]);

        $this->common = new CommonController();
        $this->title = trans("state.state");
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StatesDataTable $dataTable)
    {
        return $dataTable->render('states.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['countries'] =  $this->common->getCountries();
        return response()->json(['html' =>  view('states.create', $this->data)->render()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatesRequest $request)
    {
        $input = $request->except(['_token']);
        $user = Sentinel::getUser();
        $input['created_by'] = $user->id ?? '';
        $input['ip'] = $request->ip();
        $model = State::create($input);
        return redirect()->route('state.index')->with('success', __('state.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('state.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = State::find($id);
        $this->data['countries'] =  $this->common->getCountries($state->country_id);
        $this->data['state'] = $state;

        return response()->json(['html' => view('states.edit', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatesRequest $request, $id)
    {
        $state = State::findOrFail($id);
        $input = $request->except(['_token', '_method']);
        $user = Sentinel::getUser();
        $input['updated_by'] = $user->id ?? '';
        $input['update_from_ip'] = $request->ip();
        $state->update($input);
        return redirect()->route('state.index')->with('success', __('state.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::findOrFail($id);
        if ($state) {
            $dependency = $state->deleteValidate($id);
            if (!$dependency) {
                $state->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('state.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('state.delete_success'),
        ], 200);
    }

    public function checkUniqueName(Request $request, $id = '')
    {
        $name = trim($request->name);
        if($name != ''){
            $checkName = State::where(['name' => $name])
                ->whereNull('deleted_at')
                ->when($id, function($q) use($id){
                    $q->where('id','!=',$id);
                })
                ->count();
            
            return ($checkName > 0) ? 'false' : 'true';
        }        
    }
}
