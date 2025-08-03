<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerDataTable;
use App\Http\Requests\CustomerRequest;
use App\Models\{Customer, CustomerAddress, Employee, Role, RoleUser, User};
use Carbon\Carbon;
use Centaur\AuthManager;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public $data, $common, $title, $response_json, $is_public = true, $path, $size, $defaultImage;
    protected $authManager;
    public function __construct(AuthManager $authManager)
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->common = new CommonController();
        $this->authManager = $authManager;
        $this->middleware('permission:customers.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:customers.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:customers.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customers.delete', ['only' => ['destroy']]);
        ini_set('memory_limit', '-1');
    }

    public function index(CustomerDataTable $dataTable)
    {
        $this->data['states'] = $this->common->getStates(old('country_id'));
        return $dataTable->render(
            'customers.index',
            $this->data
        );
    }

    public function create()
    {
        $this->data['countries'] =  $this->common->getCountries();
        $this->data['states'] =  [];
        $this->data['cities'] =  [];
        return view('customers.create', $this->data);
    }

    public function show($id)
    {
        $customers = Customer::with('customerAddress')->findOrFail($id);
        $this->data['countries'] =  $this->common->getCountries();
        $this->data['states'] =  $this->common->getStates();
        $this->data['customers'] = $customers;
        $table_name =  $customers->getTable();
        $this->data['table_name'] = $table_name;
    
        return view('customers.show', $this->data);
    }

    public function edit($id)
    {
        $customers = Customer::with('customerAddress')->findOrFail($id);
        $address = $customers->customerAddress;


        $customers['customer_addresses_id'] = $address->id;
        $customers['address_line'] = $address->address_line;
        $customers['city'] = $address->city;
        $customers['pincode'] = $address->pincode;
        $customers['country_id'] = $address->country_id;
        $customers['state_id'] = $address->state_id;
        $customers['city_id'] = $address->city_id;
        $customers['mobile'] = $customers->mobile;
        $customers['phone'] = $address->phone;

    
        $this->data['customers'] = $customers;
        $this->data['countries'] =  $this->common->getCountries($customers['country_id']);
        $this->data['states'] =  $this->common->getStates($customers['country_id'], $customers['state_id']);
        $this->data['cities'] =  $this->common->getCities($customers['state_id'], $customers['city_id']);
        return view('customers.edit', $this->data);
    }

    public function update(CustomerRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            list(
                $customerData,
                $customerAddress,
            ) = $this->getInput($request->all(), $id);
            $id = $request->id;
                $customer = Customer::findOrFail($id);

               
                $customer->update($customerData);

                $customerId = $customer->id;
                $img_path = $customerId;
                $userPassword = $request->get('password', false);
                $this->uploadPanCard($request, null, $img_path, $customerId);
                $this->uploadGstCertificate($request, null, $img_path, $customerId);
    
                $customer_addresses_id = $request->customer_addresses_id;
    
                $customerAddressData = CustomerAddress::findOrFail($customer_addresses_id);
                $customerAddressData->update($customerAddress);
                
                $regUserData = User::where('customer_id', $customer->id)->where('is_active', 'Yes')->first();
    
                if ($userPassword) {
                    $userData = [
                        'password' => Hash::make($userPassword),
                        'updated_by' => $this->user->id,
                        'updated_at' => now()
                    ];
                    $data = User::where('customer_id', $customer->id)->first();
                    if ($data) {
                        $userId = $data->id;
                        User::where('id', $userId)->update($userData);
                    }
                }
    
                if ($regUserData) {
                    $userId = $regUserData->id;
                    $userDataUpdate = [
                        'is_active' => 'Yes',
                        'emp_type' => 'customer',
                        'customer_id' => $customer['id'],
                        'mobile' => $customer['mobile'],
                        'first_name' => $customer['person_name'],
                        'email' => $customer['email'] ?? null,
                    ];
                    User::where('id', $userId)->update($userDataUpdate);
                }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            info($e);
            // return false;
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return redirect()->route('customers.index')->with('success', __('common.update_success'));
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $user = User::where('customer_id', $customer->id)->first();
        $is_active = $user->is_active;
        if ($is_active != "Yes") {
            $dependency = $customer->deleteValidate($customer->id);
            if (!$dependency) {
                $deleteArray = [
                    'module' => 'customer',
                    'table_name' => $customer->getTable() ?? '',
                    'table_id' => $id ?? 0,
                ];
                $this->common->getCreateDeleteLog($deleteArray);
                $customerAddress = CustomerAddress::where('customer_id', $id)->first();
                if ($customerAddress) {
                    $customerAddress->delete();
                }
                $customer->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => __('common.delete_success'),
                ], 200);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => __('customers.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => __('User Has been Active!'),
            ], 200);
        }
    }

    public function getInput($request, $customerId = null)
    {
        $email = $request['email'] ? $request['email'] : $request['mobile'].'@gmail.com';

        $customerData = [
            'first_name' => trim($request['first_name']),
            'middle_name' => trim($request['middle_name']),
            'last_name' => trim($request['last_name']),
            'email' => $email ?? '',
            'aadhar_card_no' => $request['aadhar_card_no'] ?? '',
            'mobile' => $request['mobile'] ?? '',
            'is_create_user' => $request['is_create_user'] ?? 0,
            'address_line' => $request['address_line'] ?? '',
            'pincode' => $request['pincode'] ?? '',
            'country_id' => $request['country_id'] ?? 0,
            'state_id' => $request['state_id'] ?? 0,
            'city_id' => $request['city_id'] ?? 0,
            'phone' => $request['phone'] ?? '',
        ];

        return [$customerData];
    }

    public function uploadAadharCard($request, $unlink = null, $img_path = null, $customerId = null)
    {
        if ($request->hasFile('aadharcard_img')) {
            $storepath = '/uploads/Customer/' . $img_path . '/Document/';
            $file['aadharcard_img'] = $this->getUniqueFilename($request->file('aadharcard_img'), $this->getImagePath($storepath));
            $request->file('aadharcard_img')->move($this->getImagePath($storepath), $file['aadharcard_img']);
            $customers['aadharcard_img'] = $storepath . $file['aadharcard_img'];
            if ($unlink != null) {
                if (File::exists(base_path('public' . $unlink))) {
                    unlink(base_path('public' . $unlink));
                }
            }
            $customerdata = Customer::findOrFail($customerId);
            $customerdata->update($customers);
        }
    }

    public function getUniqueFilename($fileInput, $destination)
    {
        $filename = $fileInput->getClientOriginalName();
        $i = 0;
        $path_parts = pathinfo($filename);
        $path_parts['filename'] = Str::slug($path_parts['filename'], '-');
        $filename = $path_parts['filename'];
        while (File::exists($destination . '/' . $filename . '.' . $path_parts['extension'])) {
            $filename = $path_parts['filename'] . '-' . $i;
            $i++;
        }
        return time() . '_' . $filename . '.' . $path_parts['extension'];
    }

    public function getImagePath($file_name = '')
    {
        if ($this->is_public) {
            $path = public_path($this->path);
        } else {
            $path = storage_path($this->path);
        }

        if (File::isDirectory($path) === false) {
            File::makeDirectory($path, 0777, true);
            $this->createIndexHtmlFile($path);
        }
        return $path . $file_name;
    }

    // checkDuplicatePanNo
    public function checkDuplicatePanNo(Request $request)
    {
        $pan_no = $request->pan_no;
        $id = $request->id;
        $customer = Customer::where('pan_no', $pan_no);
        if ($id) {
            $customer = $customer->where('id', '!=', $id);
        }
        $customer = $customer->first();
        if ($customer) {
            return 'false';
        } else {
            return 'true';
        }
    }

    // checkDuplicateEmail
    public function checkDuplicateEmail(Request $request)
    {
        $email = $request->email;
        $id = $request->id;
        $customer = Customer::where('email', $email);
        $user = User::where('email', $email);
        if ($id) {
            $customer = $customer->where('id', '!=', $id);
            $user = $user->where('customer_id', '!=', $id);
        }
        $customer = $customer->first();
        $user = $user->first();
        if ($customer || $user) {
            return 'false';
        } else {
            return 'true';
        }
    }

    // checkDuplicateMobile
    public function checkDuplicateMobile(Request $request)
    {
        $mobile = $request->mobile;
        $id = $request->id;
        $customer = Customer::where('mobile', $mobile);
        if ($id) {
            $customer = $customer->where('id', '!=', $id);
        }
        $customer = $customer->first();
        if ($customer) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            list(
                $customerData,
            ) = $this->getInput($request->all());

            $customerData['ip'] = $request->ip();
            $customerData['created_by'] = $this->user->id;
            $customerData['updated_by'] = $this->user->id;
            $userPassword = $request->get('password', false);
            
            $customer = Customer::create($customerData);
            $customerId = $customer->id;

            $user_id = Sentinel::getUser()->id ?? 0;
            $date = Carbon::now()->format('Y-m-d');

            $img_path = $customer->id;
            if ($request->hasFile('aadharcard_img')) {
                $this->uploadAadharCard($request, null, $img_path, $customerId);
            }


            $is_create_user = $request->is_create_user ?? 0;
            $email = $customer->email;
            $isUser = User::where('customer_id', $customer->id)->orWhere('email', $email)->count();
            if (!empty($email) && ($isUser == 0)) {

                $roleModal = Role::where('slug', 'customer')->first();
                $role_id = (!empty($roleModal)) ? $roleModal->id : NULL;

                $userData = [];
                $userData['first_name'] = $customer['person_name'];
                $userData['email'] = $customer['email'] ?? null;
                $userData['mobile'] = $customer['mobile'] ?? null;
                $userData['password'] = Hash::make($userPassword);
                $regUserData = Sentinel::registerAndActivate($userData);
                if ($regUserData) {
                    $userId = $regUserData->id;
                    $userDataUpdate = [
                        'is_active' => 'Yes',
                        'emp_type' => 'customer',
                        'customer_id' => $customer['id'],
                        'roles_id' => $role_id,
                        'mobile' => $customer['mobile'],
                    ];
                    User::where('id', $userId)->update($userDataUpdate);

                    $roleUser = [];
                    $roleUser['user_id'] = $userId;
                    $roleUser['role_id'] = $role_id;
                    RoleUser::create($roleUser);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            dd($e);
            DB::rollback();
            info($e);
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return redirect()->route('customers.index')->with('success', __('common.create_success'));
    }
}
