<?php

namespace App\Http\Controllers;

use App\Models\CarBrand;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CarBrandDatatable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use DB;

class CarBrandController extends Controller
{

    private $path, $common, $title, $data;
    private $is_public = true;


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
        $input = $request->except(['_token','brand_logo']);
        $logo = '';
        if ($request->hasfile('brand_logo')) {
            $logo = uploadFile($request, 'Logo/','brand_logo');
        }
        $input['brand_logo'] = $logo;

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
    public function update(Request $request,$id)
    {
        $carBrand = CarBrand::findOrFail($id);
        $input = $request->except(['_token','_method','brand_logo']);
        if ($request->hasfile('brand_logo')) {
            $logo = uploadFile($request, 'Logo/','brand_logo',$carBrand->brand_logo);
            $input['brand_logo'] = $logo;
        }
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
                $image_path = public_path($carBrand->brand_logo);
                if (File::exists($image_path)) {
                    unlink(public_path($carBrand->brand_logo));
                }
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
