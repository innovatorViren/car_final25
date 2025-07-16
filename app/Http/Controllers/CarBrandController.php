<?php

namespace App\Http\Controllers;

use App\Models\carBrand;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CarBrandDatatable;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use DB;

class CarBrandController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:car_brand.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:car_brand.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:car_brand.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:car_brand.delete', ['only' => ['destroy']]);
        $this->title = trans("car_brand.car_brand");
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     */
   public function index(CarBrandDatatable $dataTable)
    {
        return $dataTable->render('car-brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response()->json([
            'html' =>  view('car-brand.create')->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $input = $request->all();
        $input = $request->except(['_token']);
        $user = Sentinel::getUser();
        $input['created_by'] = $user->id ?? '';
        $input['ip'] = $request->ip();
        $input['brand_name'] = trim($request->brand_name);
        $model = CarBrand::create($input);
        return redirect()->route('car-brand.index')->with('success', __('car_brand.create_success'));
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
        return redirect()->route('car-brand.edit', $id);
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
        $carBrand = CarBrand::find($id);
        $this->data['carBrand'] = $carBrand;
        return response()->json(['html' => view('car-brand.edit', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $carBrand = CarBrand::findOrFail($id);
        
        $input = $request->except(['_token','_method']);
        $user = Sentinel::getUser();
        $input['updated_by'] = $user->id ?? '';
        $input['update_from_ip'] = $request->ip();
        $input['brand_name'] = trim($request->brand_name);
        $carBrand->update($input);
        return redirect()->route('car-brand.index')->with('success', __('car_brand.update_success'));
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
        $carBrand = CarBrand::findOrFail($id);
        if ($carBrand) {
            $dependency = $carBrand->deleteValidate($id);
            if (!$dependency) {
                $carBrand->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('car_brand.dependency_error', ['dependency' => $dependency]),
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
            $checkName = CarBrand::where(['name' => $name])
                ->whereNull('deleted_at')
                ->when($id, function($q) use($id){
                    $q->where('id','!=',$id);
                })
                ->count();
            
            return ($checkName > 0) ? 'false' : 'true';
        }        
    }
}
