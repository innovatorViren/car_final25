<?php

namespace App\Http\Controllers;

use App\Models\{CarModel,CarBrand};
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\CarModelDataTable;
use Session;
use Flash;
use Sentinel;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\File;

class CarModelController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->middleware('permission:car_model.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:car_model.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:car_model.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:car_model.delete', ['only' => ['destroy']]);
        $this->common = new CommonController();
        $this->title = trans("car_model.car_model");
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.
     */
     public function index(CarModelDataTable $dataTable)
    {
        return $dataTable->render('car-model.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['carBrand'] =  $this->common->getCarBrand();
        return response()->json(['html' =>  view('car-model.create', $this->data)->render()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token','model_photo']);

        $photo = '';
        if ($request->hasfile('model_photo')) {
            $photo = uploadFile($request, 'Photo/','model_photo');
        }
        $input['model_photo'] = $photo;

        $model = CarModel::create($input);
        $carModelId = $model->id;

        return redirect()->route('car-model.index')->with('success', __('car_model.create_success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CarModel $carModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $carModel = CarModel::find($id);
        $this->data['carBrand'] =  $this->common->getCarBrand($carModel->car_brand_id);
        $this->data['carModel'] = $carModel;

        return response()->json(['html' => view('car-model.edit', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $carModel = CarModel::findOrFail($id);
        $input = $request->except(['_token', '_method']);

        if ($request->hasfile('model_photo')) {
            $photo = uploadFile($request, 'Photo/','model_photo',$carModel->model_photo);
            $input['model_photo'] = $photo;
        }
        $carModel->update($input);
        return redirect()->route('car-model.index')->with('success', __('car_model.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carModel = CarModel::findOrFail($id);

        if ($carModel) {
            $dependency = $carModel->deleteValidate($id);
            if (!$dependency) {
                $image_path = public_path($carModel->model_photo);
                if (File::exists($image_path)) {
                    unlink(public_path($carModel->model_photo));
                    // File::delete($image_path);
                }
                $carModel->delete();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('state.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('car_model.delete_success'),
        ], 200);
    }
}
