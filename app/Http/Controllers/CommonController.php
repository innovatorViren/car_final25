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

    public function getPrimaryManagedBy($primary_managed_by_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $usersData = User::select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
                ->where('is_active', 'Yes')
                ->orderBy('first_name', 'ASC')
                ->pluck('user_full_name', 'id')
                ->toArray();

            /** List of users which are not superadmin */
            $userList = [];
            if (!empty($usersData) && count($usersData)) {
                foreach ($usersData as $key => $value) {
                    $login_user = Sentinel::findById($key);
                    $superadmin = $login_user->hasAccess(['users.superadmin']);

                    if (!$superadmin) {
                        $objData = [
                            'value' => $key,
                            'text' => $value
                        ];
                        array_push($userList, $objData);
                    }
                }
            }
            /** List of users which are not superadmin */

            $toReturn = $userList;
            $this->data = $toReturn;

            return $this->responseSuccessWithoutObject();
        } else {
            $primary_managed_by_id = $request->get('primary_managed_by_id', $primary_managed_by_id);
            $usersData = User::select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
                ->when($primary_managed_by_id, function ($sql) use ($primary_managed_by_id) {
                    $sql->where('id', $primary_managed_by_id);
                })
                ->where('is_active', 'Yes')
                ->orderBy('first_name', 'ASC')
                ->pluck('user_full_name', 'id')
                ->toArray();

            /** List of users which are not superadmin */
            $userList = [];
            if (!empty($usersData) && count($usersData)) {
                foreach ($usersData as $key => $value) {
                    $login_user = Sentinel::findById($key);
                    $superadmin = $login_user->hasAccess(['users.superadmin']);

                    if (!$superadmin) {
                        $userList[$key] = $value;
                    }
                }
            }
            /** List of users which are not superadmin */

            return $userList;
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
            /*
            // $addUserAgentData  = $this->getUserAgentAddedBy($addedData);
            // $browser = $addUserAgentData->browser();
            // $version =  $addUserAgentData->version($browser);
            // $platform = $addUserAgentData->platform();
            if(isset($addedData->platform) && $addedData->platform == 1){
                $device = $addedData->user_agent ?? '';
            } else{
                $device =  $browser . ' ' . $version . ' / ' . $platform;
            }
            */
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
            // $updateUserAgentData  = $this->getUserAgentUpdatedBy($updatedData);
            // $browser = $updateUserAgentData->browser();
            // $version =  $updateUserAgentData->version($browser);
            // $platform = $updateUserAgentData->platform();
            /*
            if(isset($updatedData->update_platform) && $updatedData->update_platform == 1){
                $upddevice = $updatedData->update_from_user_agent ?? '';
            } else{
                $upddevice =  $browser . ' ' . $version . ' / ' . $platform;
            }
            */
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

    public function getVariant($variant_id = null, $product_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $variant = variant::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $variant;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $variant_id = $request->get('variant_id', $variant_id);
            $product_id = $request->get('product_id', $product_id);
            $variant = Variant::leftJoin('product_variants', 'product_variants.variant_id', '=', 'variants.id')
                ->select('variants.name as name', 'variants.id as id')
                ->where('variants.is_active', 'Yes')
                ->when($product_id, function ($sql) use ($product_id) {
                    $sql->where('product_variants.product_id', $product_id); // Specify product_variants.product_id for clarity
                })
                ->when($variant_id, function ($sql) use ($variant_id) {
                    $sql->orWhere('variants.id', $variant_id); // Specify variants.id to avoid ambiguity
                })
                ->orderBy('variants.name', 'ASC')
                ->pluck('variants.name', 'variants.id')->toArray();
            return $variant;
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

    public function getProduct($category = null,$page = 1)
    {
        $request = request();
        $platform = $request->header('platform');
        $page = $request->page ?? 1;
        $limit = config('global.pagination_records');
        if ($platform == 1) {
            $category_id = $request->get('category_id', false);
            $path = URL::asset('');
            $product = Product::select('products.id', 'products.product_name', DB::raw("(CASE WHEN products.image !='' THEN  CONCAT('" . $path . "', products.image) ELSE '' END) as image"), 'C.name as category_name', DB::raw('0 as is_wishlist'), DB::raw('0 as is_cart'))
                ->leftjoin('categories as C', 'C.id', 'products.category_id')
                ->when($category_id, function ($sql) use ($category_id) {
                    $sql->where('products.category_id', $category_id);
                })
                ->where('products.is_active', 'Yes')
                ->orderBy('products.product_name', 'asc')
                ->paginate($limit);
                // ->get();

            // $toReturn = $product;
            // $this->data = $toReturn;
            // return $this->responseSuccess();
            $this->response_json['product'] = $product;
            $this->response_json['status'] = 1;
            return response()->json($this->response_json, 200);
        } else {
            $category_id = $request->get('category', $category);
            $id = $request->get('id', false);
            $product = Product::where('is_active', 'Yes')
                ->when($category_id, function ($sql) use ($category_id) {
                    $sql->where('category_id', $category_id);
                })
                ->when($id, function ($sql) use ($id) {
                    $sql->orWhere('id', $id);
                })
                ->orderBy('product_name', 'ASC')
                ->pluck('product_name', 'id')->toArray();
            return $product;
        }
    }


    public function getRmCategory($category_id = null)
    {
        $request = request();

        $platform = $request->header('platform');
        if ($platform == 1) {
            $category = Category::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $category;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $category_id = $request->get('category_id', $category_id);
            $category = Category::where('is_active', 'Yes')->where('c_type', 'rm_category')
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

    public function getAgent($agent_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $agent = Agent::select('id AS value', 'name AS text')->where('is_active', 'Yes')
                ->orderBy('name', 'asc')->get();

            $toReturn = $agent;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $agent_id = $request->get('agent_id', $agent_id);
            $field = [DB::raw("CONCAT(agents.first_name, ' ', agents.last_name) as agent_name"), 'id'];
            $agent = Agent::select($field)
                ->where('is_active', 'Yes')
                ->when($agent_id, function ($sql) use ($agent_id) {
                    $sql->orwhere('id', $agent_id);
                })
                ->orderBy('agent_name', 'ASC')
                ->pluck('agent_name', 'id')->toArray();
            return $agent;
        }
    }

    public function getLeads($lead_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $leads = Lead::select('id AS value', 'company_name AS text')->orderBy('company_name', 'asc')->get();

            $toReturn = $leads;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $lead_id = $request->get('lead_id', $lead_id);
            $leads = Lead::when($lead_id, function ($sql) use ($lead_id) {
                $sql->orWhere('id', $lead_id);
            })
                ->orderBy('company_name', 'ASC')
                ->pluck('company_name', 'id')->toArray();
            return $leads;
        }
    }


    public function getLeadDetail()
    {
        $id = request()->get('id');
        $data = Lead::with(['officeAddresses', 'leadSingleContactPersons'])->where('id', $id)->first();
        if (!empty($data)) {
            return ['status' => 'success', 'data' => $data];
        }
        return ['status' => 'fail'];
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

    public function getIndustries($industry_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $industries = Industry::select('id AS value', 'name AS text')->where('is_active', 'Yes')->orderBy('name', 'asc')->get();

            $toReturn = $industries;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $industry_id = $request->get('industry_id', $industry_id);
            $industries = Industry::where('is_active', 'Yes')
                ->when($industry_id, function ($sql) use ($industry_id) {
                    $sql->orWhere('id', $industry_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $industries;
        }
    }

    public function getSalesCordinator()
    {
        $request = request();
        $department = Department::where('name', 'like', 'Sales')->first();
        $department_id = (!empty($department)) ? $department->id : 0;
        $platform = $request->header('platform');

        if ($platform == 1) {
            $users = User::with(['employee'])
                ->select('id AS value', DB::raw("CONCAT(first_name, ' ', last_name) as text"))
                // ->whereHas('employee', function ($q) use ($department_id) {
                //     $q->where('department_id', $department_id);
                // })
                ->where('is_active', 'Yes')
                ->orderBy('first_name', 'ASC')
                ->get();

            $toReturn = $users;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $users = User::with(['employee'])
                ->select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
                // ->whereHas('employee', function ($q) use ($department_id) {
                //     $q->where('department_id', $department_id);
                // })
                ->where('is_active', 'Yes')
                ->orderBy('first_name', 'ASC')
                ->pluck('user_full_name', 'id')
                ->toArray();

            return $users;
        }
        // $users = User::with(['employee'])
        //     ->select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
        //     ->whereHas('employee', function ($q) use ($department_id) {
        //         $q->where('department_id', $department_id);
        //     })
        //     ->where('is_active', 'Yes')
        //     ->orderBy('first_name', 'ASC')
        //     ->pluck('user_full_name', 'id')
        //     ->toArray();

        // return $users;
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
            })->when($branch_id, function ($sql) use ($branch_id) {
                $sql->where('branch_id', $branch_id);
            })
                ->where(['is_active' => 'Yes'])
                ->orderBy('company_name', 'ASC')
                ->pluck('company_name', 'id')->toArray();
            return $customer;
        }
    }


    public function getGroupOfCompanies($group_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $group = GroupOfCompany::select('id AS value', 'name AS text')->orderBy('name', 'asc')->get();

            $toReturn = $group;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $group_id = $request->get('group_id', $group_id);
            $group = GroupOfCompany::when($group_id, function ($sql) use ($group_id) {
                $sql->orWhere('id', $group_id);
            })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')
                ->toArray();

            return $group;
        }
    }

    public function getGroupOfCompaniesData($group_of_company_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        // if ($platform == 1) {

        $group = GroupOfCompany::select('id AS value', 'name AS text')->orderBy('id', 'asc')->get();

        $toReturn = $group;
        $this->data = $toReturn;

        return $this->responseSuccess();
        // } else {
        //     $group_of_company_id = $request->get('group_of_company_id', $group_of_company_id);
        //     $group = GroupOfCompany::when($group_of_company_id, function ($sql) use ($group_of_company_id) {
        //             $sql->orWhere('id', $group_of_company_id);
        //         })
        //         ->orderBy('name', 'ASC')
        //         ->pluck('name', 'id')->toArray();
        //     return $group;
        // }
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

    public function getCurrency($group_of_company_id = null)
    {
        $request = request();
        $platform = $request->header('platform');

        $currencies = Config('srtpl.currencies');
        $toReturn = [];
        $i = 0;
        foreach ($currencies as $key => $item) {
            $toReturn[$i]['string_value'] = $key;
            $toReturn[$i]['text'] = $item;
            $i++;
        }
        $this->data = $toReturn;

        return $this->responseSuccessWithoutObject();
    }
    public function getUnit($unit_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $unit = Unit::select('id AS value', 'unit_name AS text')->where('is_active', 'Yes')->orderBy('name', 'asc')->get();

            $toReturn = $unit;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $unit_id = $request->get('unit_id', $unit_id);
            $unit = Unit::where('is_active', 'Yes')
                ->when($unit_id, function ($sql) use ($unit_id) {
                    $sql->orWhere('id', $unit_id);
                })
                ->orderBy('unit_name', 'ASC')
                ->pluck('unit_name', 'id')->toArray();
            return $unit;
        }
    }
    public function getLocation($location_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $location = Location::select('id AS value', 'name AS text')->where(['is_active' => 'Yes'])->orderBy('name', 'asc')->get();
            $toReturn = $location;
            $this->data = $toReturn;
            return $this->responseSuccess();
        } else {
            $location_id = $request->get('location_id', $location_id);
            $location = Location::where(['is_active' => 'Yes'])
                ->when($location_id, function ($sql) use ($location_id) {
                    $sql->orWhere('id', $location_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $location;
        }
    }

    public function getSupplierList($supplier_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $supplier = Supplier::select('id AS value', 'company_name AS text')->where(['is_active' => 'Yes', 'object_type' => 'Supplier', 'category' => 'Supplier'])->orderBy('company_name', 'asc')->get();

            $toReturn = $supplier;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $supplier_id = $request->get('supplier_id', $supplier_id);
            $supplier = Supplier::where(['is_active' => 'Yes'])
                ->when($supplier_id, function ($sql) use ($supplier_id) {
                    $sql->orWhere('id', $supplier_id);
                })
                ->select(DB::raw("TRIM(company_name) AS company_name"), 'id')
                ->orderBy('company_name', 'ASC')
                ->pluck('company_name', 'id')->toArray();

            return $supplier;
        }
    }

    public function getPurchaseOrder($po_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $po_id = PurchaseOrder::select('id AS value', 'purchase_order_code AS text')->where(['is_active' => 'Yes'])->orderBy('purchase_order_code', 'asc')->get();

            $toReturn = $po_id;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $po_id = $request->get('po_id', $po_id);
            $po_id = PurchaseOrder::when($po_id, function ($sql) use ($po_id) {
                $sql->orWhere('id', $po_id);
            })
                ->orderBy('purchase_order_code', 'ASC')
                ->pluck('purchase_order_code', 'id')->toArray();
            return $po_id;
        }
    }
    public function getInwardChallan($po_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $po_id = InwardChallan::select('id AS value', 'inward_challan_code AS text')->where(['is_active' => 'Yes'])->orderBy('inward_challan_code', 'asc')->get();

            $toReturn = $po_id;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $po_id = $request->get('ic_id', $po_id);
            $po_id = InwardChallan::when($po_id, function ($sql) use ($po_id) {
                $sql->orWhere('id', $po_id);
            })
                ->orderBy('inward_challan_code', 'ASC')
                ->pluck('inward_challan_code', 'id')->toArray();
            return $po_id;
        }
    }

    public function getICBatchNo($ic_id = null)
    {
        $request = request();
        $ic_id = $request->get('ic_id', $ic_id);
        $inward_challan = InwardChallan::when($ic_id, function ($sql) use ($ic_id) {
            $sql->orWhere('id', $ic_id);
        })
            ->whereNotNull('batch_no')
            ->where(['is_active' => 'Yes'])
            ->orderBy('batch_no', 'ASC')
            ->pluck('batch_no', 'id')
            ->toArray();

        return $inward_challan;
    }

    public function getEmployeeWithEmpCode($employee_id = null)
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
            $field = [DB::raw("CONCAT(employees.employee_code, ' - ', employees.first_name, ' ', COALESCE(employees.middle_name,''), ' ', employees.last_name) as employee_name"), 'employees.id'];
            // dd($field);
            $employee = Employee::select($field)
                ->when($employee_id, function ($sql) use ($employee_id) {
                    $sql->orWhere('id', $employee_id);
                })
                ->orderBy('employee_name', 'ASC')
                ->pluck('employee_name', 'id')->toArray();
            return $employee;
        }
    }

    public function getRawMaterialByCategory(Request $request)
    {
        $category_id = $request->category_id ?? null;
        $items = RawMaterial::when($category_id, function ($sql) use ($category_id) {
            $sql->where('category_id', $category_id);
        })->orderBy('name', 'ASC')->get();
        return response()->json($items);
    }

    public function getRmProduct()
    {
        // $category_id = $request->category_id;
        $category_id = '';

        $variant = RawMaterial::select('name', 'id')
            ->where('is_active', 'Yes')
            ->when($category_id, function ($sql) use ($category_id) {
                $sql->orWhere('id', $category_id);
            })
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')->toArray();
        return $variant;
    }
    public function getCustomerWithCity($customer_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $customer = Customer::with(['customerAddress'])->select('id AS value', 'company_name AS text')->where(['is_active' => 'Yes'])->orderBy('person_name', 'asc')->get();

            $toReturn = $customer;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $customer_id = $request->get('customer_id', $customer_id);
            $customer = Customer::with(['customerAddress'])->when($customer_id, function ($sql) use ($customer_id) {
                $sql->orWhere('id', $customer_id);
            })
                ->where(['is_active' => 'Yes'])
                ->orderBy('company_name', 'ASC')->get();
            $data = [];
            foreach ($customer as $row) {
                $data[$row->id] = $row->company_name . " - " . $row->customerAddress->city->name ?? "";
            }
            return $data;
        }
    }
    public function getCustomerWiseRoutes($customer_id = null, $route_id = null,$customer_idfilter = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $cities = Routes::select('id AS value', 'name AS text')
                ->when($request->customer_id, function ($query) use ($request) {
                    $query->where('customer_id', $request->customer_id);
                })
                ->orderBy('name', 'asc')
                ->get();

            $toReturn = $cities;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $customer_id = $request->get('customer_id', $customer_id);
            $customer_idfilter = $request->get('customer_idfilter',$customer_idfilter);

            $route_id = $request->get('route_id', $route_id);
            $cities = Routes::where('is_active', 'Yes')
                ->when($customer_id, function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id);
                })
                ->when($route_id, function ($query) use ($route_id) {
                    $query->orWhere('id', $route_id);
                })
                ->when($customer_idfilter, function ($query) use ($customer_idfilter) {
                    $query->where('customer_id', $customer_idfilter);
                })
                ->orderBy('name')->get();

            $cities = $cities->pluck('name', 'id')->toArray();

            return $cities;
        }
    }
    public function rawMaterialByCategory($id = null, $category = null)
    {
        $request = request();
        $id = $request->get('id', $id);
        $category = $request->get('category', $category);

        $raw_materials = RawMaterial::when($id, function ($sql) use ($id) {
            $sql->orWhere('id', $id);
        })
            ->when($category, function ($sql) use ($category) {
                $sql->where('category_id', $category);
            })
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')->toArray();
        // dd($raw_materials);

        return $raw_materials;
    }
    public function getPoNos($po_no = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $purchase_order = PurchaseOrder::select('id AS value', 'purchase_order_code AS text')->orderBy('id', 'asc')->get();
            $this->data = $purchase_order;

            return $this->responseSuccess();
        } else {
            $po_no = $request->get('po_no', $po_no);

            $purchase_order = PurchaseOrder::when($po_no, function ($sql) use ($po_no) {
                //$sql->orWhere('id', $po_no);
            })->orderBy('id', 'ASC')->pluck('purchase_order_code', 'id')->toArray();
            return $purchase_order;
        }
    }
    public function getCustomerWithName($customer_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $customer = Customer::select('id', 'company_name', 'persone_name')->where(['is_active' => 'Yes'])->orderBy('person_name', 'asc')->get();
            // dd($customer);
            $toReturn = $customer;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $customer_id = $request->get('customer_id', $customer_id);
            $customer = Customer::when($customer_id, function ($sql) use ($customer_id) {
                $sql->orWhere('id', $customer_id);
            })
                ->where(['is_active' => 'Yes'])
                ->orderBy('person_name', 'ASC')->get();
            $data = [];
            foreach ($customer as $row) {
                // dd($row);

                $data[$row->id] = $row->company_name . "(" . $row->person_name . ")" ?? "";
            }
            return $data;
        }
    }
    public function getDeleteReason($id, $url)
    {
        $this->data['id'] = $id;
        $this->data['url'] = $url;
        return response()->json([
            'html' =>  view('delete-logs.reason', $this->data)->render()
        ]);
    }
    public function getCreateDeleteLog($deleteArray)
    {
        $request = request();
        $loginUser = Sentinel::getUser();
        $user_id = $loginUser ? $loginUser->id : 0;
        $dateTime = Carbon::now();
        $dateTime = $dateTime->format('Y-m-d H:i');
        $delete_reason = $request->get('reason') ?? '';
        $dataArray = [
            'user_id' => $user_id ?? '',
            'delete_reason' => $delete_reason ?? '',
            'delete_datetime' => $dateTime,
        ];
        $deleteArray = array_merge($dataArray, $deleteArray);
        DeleteLog::create($deleteArray);
        return true;
    }

    public function getPriceList($price_list_id = null)
    {
        $request = request();
        $price_list_id = $request->get('price_list_id', $price_list_id);
        $price_list = PriceList::where('is_active', 'Yes')
            ->when($price_list_id, function ($sql) use ($price_list_id) {
                $sql->where('id', $price_list_id);
            })
            ->orderBy('price', 'ASC')
            ->pluck('price', 'id')->toArray();
        return $price_list;
    }

    public function getBranchList($branch_list_id = null)
    {
        $request = request();
        $branch_list_id = $request->get('branch_list_id', $branch_list_id);
        $branch_list = Branch::where('is_active', 'Yes')
            ->when($branch_list_id, function ($sql) use ($branch_list_id) {
                $sql->where('id', $branch_list_id);
            })
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')->toArray();
        return $branch_list;
    }

    public function getClosingRmStock(Request $request)
    {
        $rm_id = $request->get('rm_id', false);
        $location = Session::get('location_id', 0);
        $location_id = $request->get('location_id', $location);
        $rmreport = new RawMaterialReportController;
        $request->request->add([
            'date' => custom_date_format(now(), 'Y-m-d H:i') . " | " . custom_date_format(now(), 'Y-m-d H:i'),
            'raw_material_id' => $rm_id,
            'location_id' => $location_id,
            'is_closing' => true
        ]);

        $closingQty = $rmreport->index($request);
        return ['closing_qty' => $closingQty ?? 0];
    }

    public function getSalesOrderCode($so_id = null)
    {
        $request = request();
        $so_id = $request->get('so_id', $so_id);
        $sale_order = SalesOrder::when($so_id, function ($sql) use ($so_id) {
            $sql->where('id', $so_id);
        })->orderBy('id', 'ASC')->pluck('code', 'id')->toArray();
        return $sale_order;
    }
    public function getEmployeeCustomers($customer_id = null)
    {
        $request = request();
        $customer_id = $request->get('customer_id', $customer_id);
        $employees = EmployeeCustomers::select('employee_id')
            ->join('employees', 'employees.id', 'employee_customers.employee_id')
            ->select('employees.id', DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"))
            ->where('customer_id', $customer_id)
            ->orderBy('employee_name', 'ASC')
            ->pluck('employee_name', 'id')
            ->toArray();

        return $employees;
    }
    public function getCategoryByRM($category_id = null)
    {
        $request = request();
        $category_id = $request->get('category_id', false) ?? null;
        $items = RawMaterial::when($category_id, function ($sql) use ($category_id) {
            $sql->where('category_id', $category_id);
        })->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();

        return $items;
    }

    public function getCategorybyProduct($category_id = null)
    {
        $request = request();
        $category_id = $request->get('category_id') ?? null;
        $items = Product::select('product_name as name', 'id')
            ->when($category_id, function ($sql) use ($category_id) {
                $sql->where('category_id', $category_id);
            })->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->toArray();
        return $items;
    }

    public function getProductByVariant($product_id = null)
    {
        $request = request();

        $product_id = $request->get('product_id', false);

        $variant = ProductVariant::leftJoin('variants',  'variants.id', '=', 'product_variants.variant_id')
            ->select('variants.name', 'variants.id', 'product_variants.product_id', 'product_variants.variant_id')
            ->where('variants.is_active', 'Yes')
            ->when($product_id, function ($sql) use ($product_id) {
                $sql->Where('product_variants.product_id', $product_id);
            })
            ->orderBy('variants.name', 'ASC')->get();
        return response()->json($variant);
    }
    public function getGrindingRmCategory($category_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        $category_id = $request->get('category_id', $category_id);
        if ($platform == 1) {
            $category = Category::select('id AS value', 'name AS text')
                ->where('process', 'Grinding')
                ->when($category_id, function ($sql) use ($category_id) {
                    $sql->orWhere('id', $category_id);
                })->orderBy('name', 'asc')->get();
            $this->data = $category;
            return $this->responseSuccess();
        } else {
            $category = Category::where('is_active', 'Yes')->where('c_type', 'rm_category')
                ->where('process', 'Grinding')
                ->when($category_id, function ($sql) use ($category_id) {
                    $sql->orWhere('id', $category_id);
                })
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $category;
        }
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

    public function getShop($shop_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $shop = Shop::select('id AS value', 'name AS text')->where(['is_active' => 'Yes'])->orderBy('name', 'asc')->get();

            $toReturn = $shop;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $shop_id = $request->get('customer_id', $shop_id);
            $shop = DB::table('shops')->when($shop_id, function ($sql) use ($shop_id) {
                    $sql->orWhere('id', $shop_id);
                })
                ->where(['is_active' => 'Yes'])
                ->orderBy('name', 'ASC')
                ->pluck('name', 'id')->toArray();
            return $shop;
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

    public function getOutwardChallanCode($oc_id = null)
    {
        $request = request();
        $platform = $request->header('platform');
        if ($platform == 1) {
            $outwardChallan = OutwardChallan::select('id AS value', 'code AS text')->orderBy('id', 'asc')->get();

            $toReturn = $outwardChallan;
            $this->data = $toReturn;

            return $this->responseSuccess();
        } else {
            $oc_id = $request->get('id', $oc_id);
            $outwardChallan = OutwardChallan::when($oc_id, function ($sql) use ($oc_id) {
                    $sql->orWhere('id', $oc_id);
                })
                ->orderBy('id', 'ASC')
                ->pluck('code', 'id')->toArray();
            return $outwardChallan;
        }
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

    public function getCustomerWithBranch($customer_id = null)
    {
        $request = request();
        $customer_id = $request->get('customer_id', $customer_id);
        $branch_id = Session::get('branch_id', false);
        
        $customer = DB::table('customers as C')
            ->join('branches as B', 'B.id', '=', 'C.branch_id')
            ->when($customer_id, function ($sql) use ($customer_id) {
                $sql->orWhere('C.id', $customer_id);
            })
            ->select('C.id', 'C.company_name', 'B.name')
            ->where('C.is_active' , 'Yes')
            ->where('C.branch_id' , $branch_id)
            ->orderBy('company_name', 'ASC')
            ->whereNull('B.deleted_at')
            ->whereNull('C.deleted_at')
            ->get()->toArray();

        return $customer;
    }

    public function getEmployeeWithBranch($employee_id = null ,$customer_id = null)
    {
        $request = request();
        $employee_id = $request->get('employee_id', $employee_id);
        $customer_id = $request->get('customer_id', $customer_id);
        
        $customer = DB::table('employees as E')
            ->join('branches as B', 'B.id', '=', 'E.branch_id')
            ->when($employee_id, function ($sql) use ($employee_id) {
                $sql->orWhere('E.id', $employee_id);
            })
            ->when($customer_id, function ($sql) use ($customer_id) {
                $sql->join('employee_customers as EC', 'E.id', 'EC.employee_id');
                $sql->where('EC.customer_id', $customer_id);
                $sql->whereNull('EC.deleted_at');
            })
            ->select('E.id', 'E.person_name', 'B.name')
            ->where('E.is_active', 'Yes')
            ->orderBy('person_name', 'ASC')
            ->whereNull('B.deleted_at')
            ->whereNull('E.deleted_at')
            ->get()->toArray();

        return $customer;
    }

    public function getUsedRmBarcodeQty($type, $id = null)
    {
        if($type == "barcode" && $id > 0){
            $usedQty = DB::table('barcodes')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->sum('grinding_use_qty');
        }elseif($type == "opening" && $id > 0){
            $usedQty = DB::table('opening_stocks')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->sum('grinding_used_qty');
        }else{
            $usedQty = 0;
        }
        return ['used_qty' => $usedQty];
    }

    public function OutwardChallanQtyData($data = []){

        $so_ids = $data['so_id'] ?? []; 
        $product_id = $data['product_id'] ?? null;
        $variant_id = $data['variant_id'] ?? null;
        $from_date = $data['from_date'] ?? null ;
        $to_date = $data['to_date'] ?? null;

        $barcodeFG = DB::table('outward_challans as OC')
            ->select([
                "P.id as product_id",
                "PV.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN B.id IS NOT NULL THEN B.qty
                    ELSE 0
                END) as outward_qty"),
                DB::raw("CONCAT(P.product_name, ' - ', V.name) as product_name"),
                "SOI.rate as rate",
                "OC.date as date",
                'P.category_id as category_id',
                "OC.location_id as location_id",
                'OC.branch_id as branch_id',
                'SOI.sales_order_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'FG');
            })
            ->leftJoin('barcodes as B', function ($join) {
                $join->on('B.id', '=', 'OCI.barcode_id')
                    ->whereNull('B.deleted_at');
            })
            ->join('products as P', function ($join) {
                $join->on('P.id', '=','B.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->join('product_variants as PV', function ($join) {
                $join->on('PV.id', '=', 'B.product_variant_id')
                    ->whereNull('PV.deleted_at');
            })
            ->join('variants as V', function ($join) {
                $join->on('PV.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'P.id')
                    ->on('SOI.variant_id', '=', 'V.id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when(!empty($so_ids), function ($query) use ($so_ids) {
                return $query->whereIntegerInRaw('OC.sale_order_id', $so_ids);
            })
            ->when($from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
                return $query->where('OC.date', '>=', $from_date)->where('OC.date', '<=', $to_date);
            })
            ->whereNotNull('OCI.barcode_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['SOI.id'])
            ->get();
        
        $openingFG = DB::table('outward_challans as OC')
            ->select([
                "OS.product_id as product_id",
                "OS.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OCI.opening_stock_id IS NOT NULL THEN OS.qty
                    ELSE 0
                END) as outward_qty"),
                DB::raw("CONCAT(P.product_name, ' - ', V.name) as product_name"),
                "SOI.rate as rate",
                "OC.date as date",
                'P.category_id as category_id',
                "OC.location_id as location_id",
                'OC.branch_id as branch_id',
                'SOI.sales_order_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'FG');
            })
            ->leftJoin('opening_stocks as OS', function ($join) {
                $join->on('OS.id', '=', 'OCI.opening_stock_id')
                    ->whereNull('OS.deleted_at');
            })
            ->join('products as P', function ($join) {
                $join->on('P.id', '=','OS.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->join('variants as V', function ($join) {
                $join->on('OS.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'OS.product_id')
                    ->on('SOI.variant_id', '=', 'OS.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when(!empty($so_ids), function ($query) use ($so_ids) {
                return $query->whereIntegerInRaw('OC.sale_order_id', $so_ids);
            })
            ->when($from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
                return $query->where('OC.date', '>=', $from_date)->where('OC.date', '<=', $to_date);
            })
            ->whereNotNull('OCI.opening_stock_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['SOI.id'])
            ->get();

        $barcodeOFG = DB::table('outward_challans as OC')
            ->select([
                "B.product_id as product_id",
                "PV.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OB.outer_barcode_id IS NOT NULL THEN B.qty
                    ELSE 0 END) as outward_qty"),
                DB::raw("CONCAT(P.product_name, ' - ', V.name) as product_name"),
                DB::raw("SOI.rate as rate"),
                "OC.date as date",
                'P.category_id as category_id',
                "OC.location_id as location_id",
                'OC.branch_id as branch_id',
                'SOI.sales_order_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'OFG');
            })
            ->leftJoin('outer_barcodes as OB', function ($join) {
                $join->on('OCI.barcode_id', '=', 'OB.barcode_id')
                ->whereNull('OB.deleted_at');
            })
            ->leftJoin('barcodes as B', function ($join) {
                $join->on('B.id', '=', 'OB.outer_barcode_id')
                    ->whereNull('B.deleted_at');
            })
            ->leftJoin('products as P', function ($join) {
                $join->on('P.id', '=','B.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->leftJoin('product_variants as PV', function ($join) {
                $join->on('PV.id', '=', 'B.product_variant_id')
                    ->whereNull('PV.deleted_at');
            })
            ->leftJoin('variants as V', function ($join) {
                $join->on('PV.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'B.product_id')
                    ->on('SOI.variant_id', '=', 'PV.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when(!empty($so_ids), function ($query) use ($so_ids) {
                return $query->whereIntegerInRaw('OC.sale_order_id', $so_ids);
            })
            ->when($from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
                return $query->where('OC.date', '>=', $from_date)->where('OC.date', '<=', $to_date);
            })
            ->whereNotNull('OB.outer_barcode_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['SOI.id'])
            ->get();

        $openingOFG = DB::table('outward_challans as OC')
            ->select([
                "OS.product_id as product_id",
                "OS.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OB.outer_opening_stock_id IS NOT NULL THEN OS.qty 
                    ELSE 0 END) as outward_qty"),
                DB::raw("CONCAT(P.product_name, ' - ', V.name) as product_name"),
                "SOI.rate as rate",
                "OC.date as date",
                'P.category_id as category_id',
                "OC.location_id as location_id",
                'OC.branch_id as branch_id',
                'SOI.sales_order_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'OFG');
            })
            ->leftJoin('outer_barcodes as OB', function ($join) {
                $join->on('OCI.barcode_id', '=', 'OB.barcode_id')
                    ->whereNull('OB.deleted_at');
            })
            ->leftJoin('opening_stocks as OS', function ($join) {
                $join->on('OS.id', '=', 'OB.outer_opening_stock_id')
                    ->whereNull('OS.deleted_at');
            })
            ->leftJoin('products as P', function ($join) {
                $join->on('P.id', '=','OS.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->leftJoin('variants as V', function ($join) {
                $join->on('OS.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'OS.product_id')
                    ->on('SOI.variant_id', '=', 'OS.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when(!empty($so_ids), function ($query) use ($so_ids) {
                return $query->whereIntegerInRaw('OC.sale_order_id', $so_ids);
            })
            ->when($from_date != '' && $to_date != '', function ($query) use ($from_date, $to_date) {
                return $query->where('OC.date', '>=', $from_date)->where('OC.date', '<=', $to_date);
            })
            ->whereNull('OC.deleted_at')
            ->whereNotNull('OB.outer_opening_stock_id')
            ->groupBy(['SOI.id'])
            ->get();

            $outwardChallans = $barcodeFG->merge($barcodeOFG)->merge($openingOFG)->merge($openingFG);
            if($product_id){
                $outwardChallans = $outwardChallans->where('product_id', $product_id);
            }
            if($variant_id){
                $outwardChallans = $outwardChallans->where('variant_id', $variant_id);
            }
            return  $outwardChallans ?? [];
    }
    public function getProductVariant($productVariantId = null){
        $productVariant = DB::table('product_variants as PV')
            ->join('products as P', 'PV.product_id', '=', 'P.id')
            ->join('variants as V', 'PV.variant_id', '=', 'V.id')
            ->select([
                DB::raw('CONCAT(P.product_name, " - ", V.name) as product_name'),
                'PV.id',
            ])
            ->when($productVariantId, function ($query) use ($productVariantId) {
                return $query->where('PV.id', $productVariantId);
            })
            ->where('V.is_active', 'Yes')
            ->where('P.is_active', 'Yes')
            // ->where('PV.is_active', 'Yes')
            ->whereNull('P.deleted_at')
            ->whereNull('P.deleted_at')
            ->whereNull('PV.deleted_at')
            ->orderBy('P.product_name', 'ASC')
            ->pluck('product_name', 'PV.id')
            ->toArray();

        return $productVariant;
    }
    public function getvoucherIn($type)
    {
        $getvoucherIn = Transfer::where('type',$type)
        ->orderBy('voucher_no', 'ASC')
        ->pluck('voucher_no', 'id')->toArray();
        return $getvoucherIn;
    }
    public function getbranch()
    {
        $branch = Branch::where('is_active',"Yes")
        ->orderBy('name', 'ASC')
        ->pluck('name', 'id')->toArray();
        return $branch;
    }
    public function getbranchvoucher($type)
    {
        $getBranchVoucher = BranchTransfer::where('type',$type)
        ->orderBy('voucher_no', 'ASC')
        ->pluck('voucher_no', 'id')->toArray();
        return $getBranchVoucher;
    }
    public function getCategorybyProductbyVariant($category_id = null)
    {
        $request = request();
        $category_id = $request->get('category_id') ?? null;
        $product_id = $request->get('product_id') ?? null;
        $sel_clm = [
        DB::raw("CONCAT(products.product_name, ' - ', V.name) as name"),
            'PV.id as id',
        ];
        
        $items = Product::leftJoin('product_variants as PV', function ($join) {
            $join->on('PV.product_id', "=", 'products.id');
            $join->whereNull('PV.deleted_at');
        })
        ->leftJoin('variants as V', function ($join) {
            $join->on('V.id', "=", 'PV.variant_id');
            $join->whereNull('V.deleted_at');
        })
        ->when($category_id, function ($sql) use ($category_id) {
            $sql->where('category_id', $category_id);
        })
        ->whereNull('products.deleted_at')
        ->orderBy('name', 'ASC')
        ->select($sel_clm)
        ->pluck('name', 'id')
        ->toArray();
        return $items;
    }

    public function getOcBaseSo($so_id = null){

        $barcodeFG = DB::table('outward_challans as OC')
            ->select([
                "P.id as product_id",
                "PV.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN B.id IS NOT NULL THEN B.qty
                    ELSE 0
                END) as qty"),
                'OCI.so_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'FG');
            })
            ->leftJoin('barcodes as B', function ($join) {
                $join->on('B.id', '=', 'OCI.barcode_id')
                    ->whereNull('B.deleted_at');
            })
            ->join('products as P', function ($join) {
                $join->on('P.id', '=','B.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->join('product_variants as PV', function ($join) {
                $join->on('PV.id', '=', 'B.product_variant_id')
                    ->whereNull('PV.deleted_at');
            })
            ->join('variants as V', function ($join) {
                $join->on('PV.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'P.id')
                    ->on('SOI.variant_id', '=', 'V.id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when($so_id, function($query) use($so_id){
                return $query->where('OCI.so_id', $so_id);
            })
            ->whereNotNull('OCI.barcode_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['P.id', 'PV.variant_id', 'so_id'])
            ->get();
        
        $openingFG = DB::table('outward_challans as OC')
            ->select([
                "OS.product_id as product_id",
                "OS.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OCI.opening_stock_id IS NOT NULL THEN OS.qty
                    ELSE 0
                END) as qty"),
                'OCI.so_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'FG');
            })
            ->leftJoin('opening_stocks as OS', function ($join) {
                $join->on('OS.id', '=', 'OCI.opening_stock_id')
                    ->whereNull('OS.deleted_at');
            })
            ->join('products as P', function ($join) {
                $join->on('P.id', '=','OS.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->join('variants as V', function ($join) {
                $join->on('OS.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'OS.product_id')
                    ->on('SOI.variant_id', '=', 'OS.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when($so_id, function($query) use($so_id){
                return $query->where('OCI.so_id', $so_id);
            })
            ->whereNotNull('OCI.opening_stock_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['OS.product_id', 'OS.variant_id', 'so_id'])
            ->get();

        $barcodeOFG = DB::table('outward_challans as OC')
            ->select([
                "B.product_id as product_id",
                "PV.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OB.outer_barcode_id IS NOT NULL THEN B.qty
                    ELSE 0 END) as qty"),
                'OCI.so_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'OFG');
            })
            ->leftJoin('outer_barcodes as OB', function ($join) {
                $join->on('OCI.barcode_id', '=', 'OB.barcode_id')
                ->whereNull('OB.deleted_at');
            })
            ->leftJoin('barcodes as B', function ($join) {
                $join->on('B.id', '=', 'OB.outer_barcode_id')
                    ->whereNull('B.deleted_at');
            })
            ->leftJoin('products as P', function ($join) {
                $join->on('P.id', '=','B.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->leftJoin('product_variants as PV', function ($join) {
                $join->on('PV.id', '=', 'B.product_variant_id')
                    ->whereNull('PV.deleted_at');
            })
            ->leftJoin('variants as V', function ($join) {
                $join->on('PV.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'B.product_id')
                    ->on('SOI.variant_id', '=', 'PV.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when($so_id, function($query) use($so_id){
                return $query->where('OCI.so_id', $so_id);
            })
            ->whereNotNull('OB.outer_barcode_id')
            ->whereNull('OC.deleted_at')
            ->groupBy(['product_id', 'variant_id', 'so_id'])
            ->get();


        $openingOFG = DB::table('outward_challans as OC')
            ->select([
                "OS.product_id as product_id",
                "OS.variant_id as variant_id",
                DB::raw("SUM(CASE 
                    WHEN OB.outer_opening_stock_id IS NOT NULL THEN OS.qty 
                    ELSE 0 END) as qty"),
                'OCI.so_id as so_id',
            ])
            ->join('outward_challan_items as OCI', function ($join) {
                $join->on('OCI.outward_challan_id', '=', 'OC.id')
                    ->whereNull('OCI.deleted_at')
                    ->where('OCI.type', 'OFG');
            })
            ->leftJoin('outer_barcodes as OB', function ($join) {
                $join->on('OCI.barcode_id', '=', 'OB.barcode_id')
                    ->whereNull('OB.deleted_at');
            })
            ->leftJoin('opening_stocks as OS', function ($join) {
                $join->on('OS.id', '=', 'OB.outer_opening_stock_id')
                    ->whereNull('OS.deleted_at');
            })
            ->leftJoin('products as P', function ($join) {
                $join->on('P.id', '=','OS.product_id')
                    ->whereNull('P.deleted_at');
            })
            ->leftJoin('variants as V', function ($join) {
                $join->on('OS.variant_id', '=','V.id')
                    ->whereNull('V.deleted_at');
            })
            ->leftJoin('sales_order_items as SOI', function ($join) {
                $join->on('SOI.sales_order_id', '=', 'OC.sale_order_id')
                    ->on('SOI.product_id', '=', 'OS.product_id')
                    ->on('SOI.variant_id', '=', 'OS.variant_id')
                    ->whereNull('SOI.deleted_at');
            })
            ->when($so_id, function($query) use($so_id){
                return $query->where('OCI.so_id', $so_id);
            })
            ->whereNull('OC.deleted_at')
            ->whereNotNull('OB.outer_opening_stock_id')
            ->groupBy(['OS.product_id', 'OS.variant_id', 'so_id'])
            ->get();

            $outwardChallans = $barcodeFG->merge($barcodeOFG)->merge($openingOFG)->merge($openingFG);
            return  $outwardChallans ?? [];
    }
    public function getLeadCreatedBy()
    {
        $department = Department::where('slug','sales')->first();
        $department_id = $department->id;
        $field = [DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"), 'id'];
        $employee = User::select($field)
        ->whereHas('employee',function($query)use ($department_id){
           return $query->where('department_id', $department_id);
        })->orderBy('employee_name', 'ASC')
        ->pluck('employee_name', 'id')->toArray();
        return $employee;
    }

    public function getSalesDepartmentEmployee($employee_id = null){

        $department = Department::where('slug','sales')->first();
        $department_id = $department->id;
        $employee_id = request()->get('id', $employee_id);
        $salesname = Employee::when($employee_id, function ($sql) use ($employee_id) {
                $sql->orWhere('id', $employee_id);
            })
            ->select(DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as user_full_name"), 'employees.id')
            // ->where('employees.is_active', 'Yes')
            ->where('employees.department_id', $department_id)
            ->orderBy('employees.first_name', 'ASC')
            ->pluck('user_full_name', 'employees.id')
            ->toArray();
        // dd($salesname);

        return $salesname;
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

    public function getProductReport($category = null,$page = 1)
    {
        $request = request();
        $category_id = $request->get('category', $category);
        $id = $request->get('id', false);
        $product = Product::when($category_id, function ($sql) use ($category_id) {
                $sql->where('category_id', $category_id);
            })
            ->when($id, function ($sql) use ($id) {
                $sql->orWhere('id', $id);
            })
            ->orderBy('product_name', 'ASC')
            ->pluck('product_name', 'id')->toArray();
        return $product;
    }

    public function getVariantReport($variant_id = null, $product_id = null)
    {
        $request = request();
        $variant_id = $request->get('variant_id', $variant_id);
        $product_id = $request->get('product_id', $product_id);
        $variant = Variant::leftJoin('product_variants', 'product_variants.variant_id', '=', 'variants.id')
            ->select('variants.name as name', 'variants.id as id')
            ->when($product_id, function ($sql) use ($product_id) {
                $sql->where('product_variants.product_id', $product_id); // Specify product_variants.product_id for clarity
            })
            ->when($variant_id, function ($sql) use ($variant_id) {
                $sql->orWhere('variants.id', $variant_id); // Specify variants.id to avoid ambiguity
            })
            ->orderBy('variants.name', 'ASC')
            ->pluck('variants.name', 'variants.id')->toArray();
        return $variant;
        
    }
    public function getOutwardBaseSalesOrder($so_id = null)
    {
        $barcodeFG = DB::table('outward_challan_items as OCI')
            ->select([
                "OCI.product_id as product_id",
                "OCI.variant_id as variant_id",
                DB::raw("SUM(OCI.qty) as qty"),
                'OCI.so_id as so_id',
            ])
            ->where('OCI.so_id', $so_id)
            ->where('OCI.type', 'FG')
            ->whereNotNull('OCI.barcode_id')
            ->whereNull('OCI.deleted_at')
            ->groupBy(['OCI.product_id', 'OCI.variant_id'])
            ->get();
        
        $openingFG = DB::table('outward_challan_items as OCI')
            ->select([
                "OCI.product_id as product_id",
                "OCI.variant_id as variant_id",
                DB::raw("SUM(OCI.qty) as qty"),
                'OCI.so_id as so_id',
            ])
            ->where('OCI.so_id', $so_id)
            ->where('OCI.type', 'FG')
            ->whereNotNull('OCI.opening_stock_id')
            ->whereNull('OCI.deleted_at')
            ->groupBy(['OCI.product_id', 'OCI.variant_id'])
            ->get();

        $outerBarcodeData = DB::table('outer_barcodes')
            ->select([
                'id as id',
                'product_id',
                'variant_id',
                DB::raw('SUM(qty) as qty'),
                'so_id',
            ])
            ->where('so_id', $so_id)
            ->whereNotNull('oc_id')
            ->whereNull('deleted_at')
            ->groupBy('product_id', 'variant_id')
            ->get();

        $outwardChallans = $outerBarcodeData->merge($barcodeFG)->merge($openingFG);
        return $outwardChallans;
    }    
}