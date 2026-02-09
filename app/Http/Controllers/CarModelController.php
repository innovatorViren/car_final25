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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

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
        $this->data['carSizes']  = Config('global.car_sizes');
        return response()->json(['html' =>  view('car-model.create', $this->data)->render()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
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
        $this->data['carSizes']  = Config('global.car_sizes');
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

    public function importCarModelsFromExcel()
    {
        $filePath = resource_path('views/excel/Car_Brand_Wise_Model.xlsx');

        if (!file_exists($filePath)) {
            return 'Excel file not found!';
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // remove header
        unset($rows[0]);

        foreach ($rows as $row) {

            $brandName = trim($row[0]); // Brand
            $modelName = trim($row[1]); // Model
            $segment   = trim($row[2]);
            $imageUrl  = trim($row[5] ?? ''); // Image URL column

            if (!$brandName || !$modelName) {
                continue;
            }

            // 🔹 get brand id
            $brand = DB::table('car_brands')
                ->where('name', $brandName)
                ->first();

            if (!$brand) {
                continue;
            }

            // 🔹 Map Segment to car_size_id
            $carSizeId = null;
            if (stripos($segment, 'Hatchback') !== false) {
                $carSizeId = 1;  // Hatchback
            } elseif (stripos($segment, 'Sedan') !== false) {
                $carSizeId = 2;  // Sedan
            } elseif (stripos($segment, 'SUV') !== false || stripos($segment, 'MUV') !== false || stripos($segment, 'XUV') !== false) {
                $carSizeId = 3;  // SUV / MUV / XUV
            }

            // 🔹 download model image
            $imagePath = null;

            if ($imageUrl) {
                try {
                    $imageContent = file_get_contents($imageUrl);

                    if ($imageContent) {

                        $folder = public_path('uploads/Photo');

                        if (!File::exists($folder)) {
                            File::makeDirectory($folder, 0755, true);
                        }

                        $fileName = time() . '_' . rand(0, 500) . '_' . Str::slug($modelName) . '.jpg';
                        $fullPath = $folder . '/' . $fileName;

                        file_put_contents($fullPath, $imageContent);

                        $imagePath = 'uploads/Photo/' . $fileName;
                    }

                } catch (\Exception $e) {
                    // ignore image failure
                }
            }
            // 🔹 insert / update model
            DB::table('car_models')->updateOrInsert(
                [
                    'car_brand_id' => $brand->id,
                    'name' => $modelName,
                ],
                [
                    'car_size_id'  => $carSizeId,
                    'model_photo' => $imagePath,
                    'is_active' => 'Yes',
                    'common_model' => 'No',
                    'ip' => '127.0.0.1',
                    'created_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return 'Excel import completed successfully';
    }
}