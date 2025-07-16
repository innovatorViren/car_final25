<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CityDataTable;
use App\Models\{Country, State, City};

use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;
use Session;
use Flash;
use Sentinel;
use Carbon\Carbon;
use DB;

class CityController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:city.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:city.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:city.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:city.delete', ['only' => ['destroy']]);

        $this->common = new CommonController();
        $this->title = trans("city.city");
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CityDataTable $dataTable)
    {
        return $dataTable->render('cities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->data['countries'] =  $this->common->getCountries();
        $this->data['states'] =  $this->common->getStates();
        return response()->json(['html' =>  view('cities.create', $this->data)->render()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        $input = $request->except(['_token']);
        $user = Sentinel::getUser();
        $input['created_by'] = $user->id ?? '';
        $input['ip'] = $request->ip();
        $model = City::create($input);
       
        return redirect()->route('city.index')->with('success', __('city.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('city.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $city = City::find($id);
        $this->data['countries'] =  $this->common->getCountries($city->country_id);
        $this->data['states'] =  $this->common->getStates($city->country_id,$city->state_id);
        $this->data['city'] = $city;

        return response()->json(['html' => view('cities.edit', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, $id)
    {
        $city = City::findOrFail($id);
        $input = $request->except(['_token', '_method']);
        $user = Sentinel::getUser();
        $input['updated_by'] = $user->id ?? '';
        $input['update_from_ip'] = $request->ip();
        $city->update($input);
        return redirect()->route('city.index')->with('success', __('city.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        if ($city) {
            $dependency = $city->deleteValidate($id);
            if (!$dependency) {
                $city->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('city.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('city.delete_success'),
        ], 200);
    }

    public function checkUniqueName(Request $request, $id = '')
    {
        $name = trim($request->name);
        if ($name != '') {
            $checkName = City::where(['name' => $name])
                ->whereNull('deleted_at')
                ->when($id, function ($q) use ($id) {
                    $q->where('id', '!=', $id);
                })
                ->count();

            return ($checkName > 0) ? 'false' : 'true';
        }
    }
}
