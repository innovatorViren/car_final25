<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CountryDatatable;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\CountriesRequest;
use Session;
use Flash;
use Sentinel;
use Carbon\Carbon;
use DB;

class CountryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:country.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:country.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:country.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:country.delete', ['only' => ['destroy']]);
        $this->title = trans("country.country");
        view()->share('title', $this->title);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(CountryDatatable $dataTable)
    {
        return $dataTable->render('countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response()->json([
            'html' =>  view('countries.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CountriesRequest $request)
    {
        // $input = $request->all();
        $input = $request->except(['_token']);
        $user = Sentinel::getUser();
        $input['created_by'] = $user->id ?? '';
        $input['ip'] = $request->ip();
        $input['name'] = trim($request->name);
        $model = Country::create($input);
        return redirect()->route('country.index')->with('success', __('country.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        return redirect()->route('country.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $country = Country::find($id);
        $this->data['country'] = $country;
        return response()->json(['html' => view('countries.edit', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, CountriesRequest $request)
    {
        $country = Country::findOrFail($id);
        
        $input = $request->except(['_token','_method']);
        $user = Sentinel::getUser();
        $input['updated_by'] = $user->id ?? '';
        $input['update_from_ip'] = $request->ip();
        $input['name'] = trim($request->name);
        $country->update($input);
        return redirect()->route('country.index')->with('success', __('country.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        if ($country) {
            $dependency = $country->deleteValidate($id);
            if (!$dependency) {
                $country->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('country.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('common.delete_success'),
        ], 200);
    }

    public function checkUniqueName(Request $request, $id = '')
    {
        $name = trim($request->name);
        if($name != ''){
            $checkName = Country::where(['name' => $name])
                ->whereNull('deleted_at')
                ->when($id, function($q) use($id){
                    $q->where('id','!=',$id);
                })
                ->count();
            
            return ($checkName > 0) ? 'false' : 'true';
        }        
    }
}
