<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Transfer;
use Illuminate\Http\Request;
use App\Models\{
    Agent,
    Category,
    City,
    Country,
    Customer,
    Department,
    Designation,
    Employee,
    GroupOfCompany,
    HsnCode,
    Industry,
    InwardChallan,
    LeadSource,
    LeadStatus,
    Location,
    Product,
    PurchaseOrder,
    Role,
    State,
    Unit,
    User,
    RawMaterial,
    Routes,
    Variant,
    Supplier,
    DeleteLog,
    EmployeeCustomers,
    PriceList,
    PriceListItem,
    SalesOrder,
    Shop,
    OutwardChallan,
    Branch,
    BranchTransfer,
    CarBrand,CarModel
};
use Carbon\Carbon;
use URL;
use Illuminate\Support\Facades\DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Session;

class CommonController extends Controller
{
    public $successStatus = 200;
    public $response_json = [];
    protected $data = [];
    protected $request;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->request = request();
        $this->response_json['message'] = 'Success';
    }

    /**
     * [changeStatus description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function changeStatus(Request $request, $id)
    {

        $table = $request->table;
        $is_active  = $request->status == 'true' ? 'Yes' : 'No';
        if ($table == 'users') {
            $userData = User::where('id', $id)->first();
            $employee = Employee::where('id', $userData->emp_id)->first();
            if ($employee && $employee->is_active == 'No') {
                // dd($employee);
                return response()->json([
                    'success' => false,
                    'message' => 'This User Employee No Active ! '
                ], 403);
            }
        }
        if ($table == 'locations') {
            $location_id = Session::get('location_id', '0');
            if ($location_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This Location is default set!'
                ], 403);
            }
        }

        if ($table == 'customers') {
            $customer = Customer::where('id', $id)->first();
            $userData = User::where('customer_id', $id)->first();
            $userTable = $userData->getTable();
            
            DB::table($userTable)->where('id', $userData->id)->update(['is_active' => $is_active]);
        }

        if ($table == 'branches') {
            $branch_id = Session::get('branch_id', '0');
            if ($branch_id == $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'This Branch is default set!'
                ], 403);
            }
        }
        $tableRes = DB::table($table)->where('id', $request->id)->update(['is_active' => $is_active]);

        $message = $request->status == 'true' ? __('common.active') : __('common.deactivate');

        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
    /**
     * [getCities description]
     * @param  [type] $state_id [description]
     * @return [type]             [description]
     */
    public function getCities($state_id = null, $city_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $cities = City::select('id AS value', 'name AS text')
                ->when($request->state_id, function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                })
                ->orderBy('name', 'asc')
                ->get();

            $toReturn = $cities;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $state_id = $request->get('state_id', $state_id);
            if ($request->get('factory_state_id')) {
                $state_id = $request->get('factory_state_id');
            }
            if ($request->get('present_state')) {
                $state_id = $request->get('present_state');
            }
            if ($request->get('permanent_state')) {
                $state_id = $request->get('permanent_state');
            }
            $city_id = $request->get('city_id', $city_id);
            $cities = City::where('is_active', 'Yes')
                ->when($state_id, function ($query) use ($state_id) {
                    $query->where('state_id', $state_id);
                })
                ->when($city_id, function ($query) use ($city_id) {
                    $query->orWhere('id', $city_id);
                })
                ->orderBy('name')->get();

            $cities = $cities->pluck('name', 'id')->toArray();

            return $cities;
        }
    }

    public function getStates($country_id = null, $state_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $states = State::select('id AS value', 'name AS text')
                ->when($request->country_id, function ($query) use ($request) {
                    $query->where('country_id', $request->country_id);
                })
                ->orderBy('name', 'asc')
                ->get();

            $toReturn = $states;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $country_id = $request->get('country_id', $country_id);
            if ($request->get('factory_country_id')) {
                $country_id = $request->get('factory_country_id');
            }
            $state_id = $request->get('state_id', $state_id);
            $states = State::where('is_active', 'Yes')
                ->when($country_id, function ($query) use ($country_id) {
                    $query->where('country_id', $country_id);
                })
                ->when($state_id, function ($query) use ($state_id) {
                    $query->orWhere('id', $state_id);
                })
                ->orderBy('name')->get();

            $states = $states->pluck('name', 'id')->toArray();

            return $states;
        }
    }

    public function getCity(Request $request)
    {

        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $cities = City::select('id AS value', 'name AS text')
                ->when($request->country_id, function ($query) use ($request) {
                    $query->where('country_id', $request->country_id);
                })
                ->when($request->state_id, function ($query) use ($request) {
                    $query->where('state_id', $request->state_id);
                })
                ->orderBy('name', 'asc')
                ->get();

            $toReturn = $cities;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $country_id = $request->get('country_id', false);
            $state_id = $request->get('state_id', false);
            $cities = City::where('is_active', 'Yes')
                ->when($country_id, function ($query) use ($country_id) {
                    $query->where('country_id', $country_id);
                })
                ->when($state_id, function ($query) use ($state_id) {
                    $query->orWhere('id', $state_id);
                })
                ->orderBy('name', 'asc')->get();


            $cities = $cities->pluck('name', 'id')->toArray();

            return $cities;
        }
    }
    /**
     * [getCountries description]
     * @param  [type] $country_id [description]
     * @return [type]             [description]
     */
    public function getCountries($country_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $countries = Country::select('id AS value', 'name AS text')->where('is_active', 'Yes')->orderBy('name', 'asc')->get();

            $toReturn = $countries;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $country_id = $request->get('country_id', $country_id);
            $countries = Country::where('is_active', 'Yes')
                ->when($country_id, function ($sql) use ($country_id) {
                    $sql->orWhere('id', $country_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $countries;
        }
    }

    public function getCarBrand($carBrandId = null)
    {
        $path = URL::asset('');
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $carBrand = CarBrand::select('id AS value', 'name AS text',
                        DB::raw("(CASE WHEN brand_logo !='' THEN  CONCAT('".$path."', brand_logo) ELSE '' END) as brand_logo"))
                        ->where('is_active', 'Yes')->orderBy('name', 'asc')->get();

            $toReturn = $carBrand;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $carBrandId = $request->get('carBrandId', $carBrandId);
            $carBrand = CarBrand::where('is_active', 'Yes')
                ->when($carBrandId, function ($sql) use ($carBrandId) {
                    $sql->orWhere('id', $carBrandId);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $carBrand;
        }
    }

    public function getCarModel($car_brand_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        $carSizes  = Config('global.car_sizes');
        
        if ($platform == 1) {
            $path = URL::asset('');
            $carModel = CarModel::select('id AS value', 'name AS text','car_size_id',
                            DB::raw("(CASE WHEN model_photo !='' THEN  CONCAT('".$path."', model_photo) ELSE '' END) as model_photo")
                        )
                        ->when($request->car_brand_id, function ($query) use ($request) {
                            $query->where('car_brand_id', $request->car_brand_id);
                        })
                        ->where('is_active', 'Yes')
                        ->orderBy('name', 'asc')
                        ->get()
                        ->map(function ($item) use ($carSizes){
                            $item->car_size = $carSizes[$item->car_size_id];
                            return $item;
                        });


            $toReturn = $carModel;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $carBrandId = $request->get('car_brand_id', $car_brand_id);
            $carModel = CarModel::where('is_active', 'Yes')
                ->when($carBrandId, function ($sql) use ($carBrandId) {
                    $sql->orWhere('id', $carBrandId);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $carModel;
        }
    }

    public function getBanner()
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {

            $path = URL::asset('');
            $banner = DB::table('banners as B')
                            ->select(
                                    'B.id',
                                    DB::raw("(CASE WHEN B.title IS NOT NULL THEN  B.title ELSE '' END) as title"),
                                    DB::raw("(CASE WHEN B.image !='' THEN  CONCAT('".$path."', B.image) ELSE '' END) as banner_image")
                                )
                            ->whereNull('B.deleted_at')
                            ->where('is_active','Yes')
                            ->orderBy('id','DESC')
                            ->get();

            $this->data = $banner;
            return $this->responseSuccessWithoutObject();
        } else {
            return '';
        }
    }

    /**
     * [getInfoData | This method is used to get info data]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getInfoData(Request $request)
    {
        $id = $request->id;
        $table_name = $request->table_name;
        $result = [];
        if ($table_name == 'users') {
            $addData = DB::table("users")
                // ->select(["u.*", "u.first_name as ufirst_name", "u.last_name as ulast_name"])
                ->where("id", '=', $id)
                ->first();
        } else {
            $addData = DB::table($table_name)
                ->select([$table_name . ".*", "users.first_name as ufirst_name", "users.last_name as ulast_name"])
                ->leftJoin('users', 'users.id', '=', $table_name . ".created_by")
                ->where($table_name . ".id", '=', $id)
                ->first();
        }

        //dd($addData);
        if ($addData) {
            $created_by = "N/A";
            if ($addData->created_by) {
                if ($table_name == 'users') {
                    $created_by = $addData->first_name . " " . $addData->last_name;
                } else {
                    $created_by = $addData->ufirst_name . " " . $addData->ulast_name;
                }
            }
            $created_at = Carbon::parse($addData->created_at)->format('d-m-Y | h:i:s A');
            
            $ip = $addData->ip ?? 'N/A';
            $result['addData'] = [
                'created_at' => $created_at,
                'created_by' => $created_by,
                'created_ip' => $ip
            ];
        }

        if ($table_name == 'users') {
            $updateData = DB::table("users")
                // ->select(["u.*", "u.first_name as ufirst_name", "u.last_name as ulast_name"])
                ->where("id", '=', $id)
                ->first();
        } else {
            $updateData = DB::table($table_name)
                ->select([$table_name . ".*", "users.first_name as ufirst_name", "users.last_name as ulast_name"])
                ->leftJoin('users', 'users.id', '=', $table_name . ".updated_by")
                ->where($table_name . ".id", '=', $id)
                ->first();
        }

        if ($updateData) {
            $updated_by = "N/A";
            if ($updateData->updated_by) {
                if ($table_name == 'users') {
                    $updated_by = $updateData->first_name . " " . $updateData->last_name;
                } else {
                    $updated_by = $updateData->ufirst_name . " " . $updateData->ulast_name;
                }
            }
            $updated_at = Carbon::parse($updateData->updated_at)->format('d-m-Y | h:i:s A');
            $update_from_ip = $updateData->update_from_ip ?? 'N/A';
            if ($updateData->updated_by) {
                $result['updateData'] = [
                    'updated_at' => $updated_at,
                    'updated_by' => $updated_by,
                    'updated_ip' => $update_from_ip
                ];
            } else {
                $result['updateData'] = [
                    'updated_by' => 'N/A',
                    'updated_at' => 'N/A',
                    'updated_ip' => 'N/A'
                ];
            }
        }
        return response()->json($result);
    }

    /**
     * [getItemQuantity description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */


    public function getEmployeeData($id)
    {
        $employee = Employee::with(['DepartmentName'])
            ->where('employees.id', $id)
            ->get();
        return response()->json($employee);
    }


    public function getDepartment($department_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $department = Department::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $department;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $department_id = $request->get('department_id', $department_id);
            $department = Department::where('is_active', 'Yes')
                ->when($department_id, function ($sql) use ($department_id) {
                    $sql->orWhere('id', $department_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $department;
        }
    }


    public function getDesignation($designation_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $designation = Designation::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $designation;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $designation_id = $request->get('designation_id', $designation_id);
            $designation = Designation::where('is_active', 'Yes')
                ->when($designation_id, function ($sql) use ($designation_id) {
                    $sql->orWhere('id', $designation_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $designation;
        }
    }

    public function getHsncode($hsncode_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $hsncode = HsnCode::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $hsncode;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $hsncode_id = $request->get('hsncode_id', $hsncode_id);
            $hsncode = HsnCode::select(DB::raw("CONCAT(hsn_code, ' - ', FLOOR(gst), '%') as hsn_code"), 'id')
                ->where('is_active', 'Yes')
                ->when($hsncode_id, function ($sql) use ($hsncode_id) {
                    $sql->orWhere('id', $hsncode_id);
                })
                ->orderBy('hsn_code', 'ASC')
                ->pluck('hsn_code', 'id')->toArray();
            return $hsncode;
        }
    }

    public function getCategory($category_id = null,$page = 1)
    {
        $request = request();
        $platform = $request->header('platform');
        $page = $request->page ?? 1;
        $limit = config('global.pagination_records');
        if ($platform == 1) {

            $search = $request->search ?? null;
            $path = URL::asset('');

            $category = Category::select('id AS value', 'name AS text', DB::raw("(CASE WHEN category_image !='' THEN  CONCAT('" . $path . "', category_image) ELSE '' END) as category_image"),)
                ->where('is_active', 'Yes')
                ->where('c_type', 'product_category')
                ->when($search, function ($query, $search) {
                      return $query->where('name','LIKE', "%{$search}%");
                })
                ->orderBy('name', 'asc')
                ->paginate($limit);
                // ->get();

            // $toReturn = $category;
            // $this->data = $toReturn;
            // return $this->responseSuccess();
            $this->response_json['category'] = $category;
            $this->response_json['status'] = 1;
            return response()->json($this->response_json, 200);
        } else {
            $category_id = $request->get('category_id', $category_id);
            $category = Category::where('is_active', 'Yes')->where('c_type', 'product_category')
                ->when($category_id, function ($sql) use ($category_id) {
                    $sql->orWhere('id', $category_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $category;
        }
    }

    public function getRoutes($routes_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $routes = Routes::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $routes;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $routes_id = $request->get('id', $routes_id);
            $routes = Routes::where('is_active', 'Yes')
                ->when($routes_id, function ($sql) use ($routes_id) {
                    $sql->orWhere('id', $routes_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $routes;
        }
    }

    public function getRole($role_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $role = Role::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $role;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $role_id = $request->get('role_id', $role_id);
            $role = Role::when($role_id, function ($sql) use ($role_id) {
                $sql->orWhere('id', $role_id);
            })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $role;
        }
    }


    public function getEmployee($employee_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $employee = Employee::select('id AS value', 'name AS text')->where('is_active', 'Yes')
                ->orderBy('name', 'asc')->get();

            $toReturn = $employee;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $employee_id = $request->get('employee_id', $employee_id);
            $field = [DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"), 'id'];
            $employee = Employee::select($field)
                ->where('is_active', 'Yes')
                ->when($employee_id, function ($sql) use ($employee_id) {
                    $sql->orWhere('id', $employee_id);
                })
                ->orderBy('employee_name', 'ASC')
                ->pluck('employee_name', 'id')->toArray();
            return $employee;
        }
    }

    // get list of all users
    public function getUsers()
    {
        $users = User::select(
            DB::raw("CONCAT(IFNULL(first_name, ''),' ',IFNULL(last_name, '')) as user_full_name"),
            'id'
        )
            ->where('is_active', 'Yes')
            ->orderBy('first_name', 'ASC')
            ->pluck('user_full_name', 'id')
            ->toArray();

        return $users;
    }

    public function getCustomer($customer_id = null,$branch_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $customer = Customer::select('id AS value', 'company_name AS text')->where(['is_active' => 'Yes'])->orderBy('person_name', 'asc')->get();

            $toReturn = $customer;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $customer_id = $request->get('customer_id', $customer_id);
            $customer = Customer::when($customer_id, function ($sql) use ($customer_id) {
                    $sql->orWhere('id', $customer_id);
                })
                ->select(DB::raw("CONCAT(first_name, ' ', last_name) as customer_name"), 'customers.id')
                ->where('is_active', 'Yes')
                ->pluck('customer_name', 'id')
                ->toArray();
            return $customer;
        }
    }


    public function getGstType($group_of_company_id = null)
    {
        $request = request();
        $platform = $request->header('platform');

        $gst = Config('project.gst_type');
        $toReturn = [];
        $i = 0;
        foreach ($gst as $key => $item) {
            $toReturn[$i]['string_value'] = $key;
            $toReturn[$i]['text'] = $item;
            $i++;
        }
        $this->data = $toReturn;

        return $this->responseSuccessWithoutObject();
    }

    public function changeDefault(Request $request, $id)
    {
        $table = $request->table;
        $is_default  = $request->is_default == 'true' ? 'Yes' : 'No';
        if($table == 'years'){
            $tableRes = DB::table($table)->where('id', $request->id)->where('is_displayed', 'Yes')->get();
        }else{
            $tableRes = DB::table($table)->where('id', $request->id)->where('is_active', 'Yes')->get();
        }
        if($tableRes->where('is_default','Yes')->count() > 0){
            return response()->json([
                'error' => false,
                'message' => 'Required at list one entry!'
            ], 422);
        }
        if($tableRes->count() > 0) {
            $tableResno = DB::table($table)->where('id', '!=', $request->id)->update(['is_default' => 'No']);
            $tableRes = DB::table($table)->where('id', $request->id)->update(['is_default' => $is_default]);
            if ($tableRes) {
                $statuscode = 200;
            }
            if ($table == 'locations') {
                $message = $request->is_default == 'true' ? __('location.active') : __('location.deactivate');
            } elseif($table == 'branches'){
                $message = $request->is_default == 'true' ? __('branch.active') : __('branch.deactivate');
            } else {
                $message = $request->is_default == 'true' ? __('year.active') : __('year.deactivate');
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ], $statuscode);
        }else{

            if ($table == 'locations') {
                $message = __('location.status_error');
            } elseif($table == 'branches'){
                $message = __('branch.status_error') ;
            } else {                 
                $message = __('year.status_error');
            }

            return response()->json([
                'error' => false,
                'message' => $message
            ], 422);
        }
    }

    public function getSalename($employee_id = null)
    {
        $request = request();
        $employee_id = $request->get('employee_id', $employee_id);
        $salesname = Employee::
            when($employee_id, function ($sql) use ($employee_id) {
                $sql->orWhere('id', $employee_id);
            })
            ->select(DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as user_full_name"), 'employees.id')
            ->where('employees.is_active', 'Yes')
            ->where('employees.is_salesman', 'Yes')
            ->orderBy('employees.first_name', 'ASC')
            ->pluck('user_full_name', 'employees.id')
            ->toArray();
        return $salesname;
        
    }

    public function getGstStatus()
    {

            $gststatus = Config('project.gst_type');
            $toReturn = [];
            $i=0;
            foreach($gststatus as $key=>$item){
                $toReturn[$i]['value']=$key;
                $toReturn[$i]['text']=$item;
                $i++;
            }
            $this->data = $toReturn;

            return $this->responseSuccessWithoutObject();
    }

    public function getsalesmans()
    {
        $department = Department::where('slug','sales')->first();
        $department_id = $department->id;
        $field = [DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"), 'id'];
        $employee = Employee::select($field)->where('department_id', $department_id)
        ->orderBy('employee_name', 'ASC')
        ->pluck('employee_name', 'id')->toArray();
        return $employee;
    }


    public function getFrequency()
    {

        $frequencyData = [
                ['name' => 'Daily 1','value' => 'daily', 'title' => '30 days daily wash', 'description' => 'Every day for 30 days', 'washes' => 30, 'days' => 30],
                ['name' => 'Week 2', 'value' => 'weekly_2', 'title' => '8 washes in 2 Week', 'description' => 'Any 8 washes within 14 days', 'washes' => 8, 'days' => 14],
                ['name' => 'Week 1', 'value' => 'weekly_1', 'title' => '4 washes in 1 Week', 'description' => 'Any 4 washes within 7 days', 'washes' => 4, 'days' => 7],
                ['name' => '1 Time Wash', 'value' => 'one_time', 'title' => 'Book a single Wash', 'description' => 'One time appoinment', 'washes' => 1, 'days' => 1],
            ];
            $this->data = collect($frequencyData);
            return $this->responseSuccess();
    } 
}