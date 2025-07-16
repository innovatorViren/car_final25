<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerDataTable;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\updateManagedRequest;
use App\Models\{Customer, CustomerPriceListLog, Lead, PriceListItem, Routes, Shop, CustomerAddress,CustomerBankDetails, Employee, Role, RoleUser, User};
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
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

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
        $this->data['customers'] = Customer::where(['is_active' => 'Yes'])->pluck('company_name', 'id')->toArray();
        $this->data['states'] = $this->common->getStates(old('country_id'));
        $usersData = Employee::select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
            ->where('is_active', 'Yes')
            ->orderBy('first_name', 'ASC')
            ->pluck('user_full_name', 'id')
            ->toArray();
        $this->data['employees'] = $usersData;
        $this->data['productType'] = Config('srtpl.sales_invoice_product_type');
        $this->data['gst_type'] = Config('project.gst_type');
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
        $this->data['employees'] =  $this->common->getEmployee();
        $this->data['priceList'] = $this->common->getPriceList();
        $this->data['branchList'] = $this->common->getBranchList();
        $this->data['gst_type'] = Config('project.gst_type');
        $this->data['currencies'] =  Config('srtpl.currencies');
        return view('customers.create', $this->data);
    }

    public function show($id)
    {
        $customers = Customer::with(['customerAddress', 'customerBankDetails', 'createdBy', 'managedBy', 'salesOrders'])->findOrFail($id);
        $this->data['countries'] =  $this->common->getCountries();
        $this->data['states'] =  $this->common->getStates();
        $this->data['employees'] =  $this->common->getEmployee();
        $this->data['week_days'] = Config('project.week_days');
        $this->data['gst_type'] = Config('project.gst_type');
        $this->data['customers'] = $customers;
        $table_name =  $customers->getTable();
        $this->data['priceList'] = $this->common->getPriceList();
        $this->data['branchList'] = $this->common->getBranchList();
        // $this->data['customers']['managed_by'] = $customers->managedBy->first_name . ' ' . $customers->managedBy->last_name;
        // $this->data['customers']['managed_by'] = $customers->managedBy->first_name . ' ' . $customers->managedBy->last_name;
        $this->data['table_name'] = $table_name;
        $isCustomerCount = $customers->salesOrders->count();
        $user_status = DB::table('users')
            ->where('emp_type', 'customer')
            ->where('customer_id', $id)
            ->where('is_active', 'No')
            ->count();
        $this->data['user_status'] = $user_status ?? '';

        $this->data['isCustomerCount'] = $isCustomerCount;

        $priceData = PriceListItem::with(['product', 'variant'])->where('price_list_id', $customers->price_list_id)->get();
        $shopData = DB::table('shops as S')
            ->where('S.customer_id', $customers->id)
            ->leftJoin('shop_visits as SV', 'SV.shop_id', '=', 'S.id')
            ->leftJoin('routes as R', 'R.id', '=', 'S.route_id')
            ->leftJoin('employee_customers as EC', 'EC.customer_id', '=', 'S.customer_id')
            ->leftJoin('employees as E', 'E.id', '=', 'EC.employee_id')
            ->select(
                'S.id as id',
                'S.name as name',
                'S.owner_name as owner_name',
                'S.phone_number as phone_number',
                'R.name as route_name',
                DB::raw('MAX(SV.created_at) as route_date'),
                'E.first_name as first_name',
                'E.last_name as last_name'
            )
            ->whereNull('S.deleted_at')
            ->groupBy('S.id')
            ->get();
        $routeData = Routes::with(['shop_routes', 'employee', 'shop_visits_latest'])->where('customer_id', $customers->id)->orderBy('name')->get();
        // dd($routeData);

        $this->data['priceListData'] = $priceData ?? [];
        $this->data['shopData'] = $shopData ?? [];
        $this->data['routeData'] = $routeData ?? [];
    
        return view('customers.show', $this->data);
    }

    public function edit($id)
    {
        $customers = Customer::with(['customerAddress', 'customerBankDetails', 'createdBy', 'managedBy'])->findOrFail($id);
        $address = $customers->customerAddress;
        $bankDetail = $customers->customerBankDetails;


        $customers['customer_addresses_id'] = $address->id;
        $customers['address_line1'] = $address->address_line1;
        $customers['address_line2'] = $address->address_line2;
        $customers['city'] = $address->city;
        $customers['pincode'] = $address->pincode;
        $customers['country_id'] = $address->country_id;
        $customers['state_id'] = $address->state_id;
        $customers['city_id'] = $address->city_id;
        $customers['mobile'] = $customers->mobile;
        $customers['phone'] = $address->phone;
        $customers['phone2'] = $address->phone2;
        $customers['mobile2'] = $address->mobile2;
        // $customers['primary_managed_by'] = $customers->managedBy->first_name . ' ' . $customers->managedBy->last_name;
        // $customers['primary_managed_by'] = $customers->managed_by;

        $customers['customer_bank_details_id'] = $bankDetail->id ?? '';
        $customers['beneficiary_name'] = $bankDetail->beneficiary_name ?? '';
        $customers['bank_name'] = $bankDetail->bank_name ?? '';
        $customers['account_no'] = $bankDetail->account_no ?? '';
        $customers['ifsc_code'] = $bankDetail->ifsc_code ?? '';
        $customers['branch_name'] = $bankDetail->branch_name ?? '';
        $this->data['customers'] = $customers;
        $this->data['gst_type'] = Config('project.gst_type');
        $this->data['countries'] =  $this->common->getCountries($customers['country_id']);
        $this->data['states'] =  $this->common->getStates($customers['country_id'], $customers['state_id']);
        $this->data['cities'] =  $this->common->getCities($customers['state_id'], $customers['city_id']);
        $this->data['employees'] =  $this->common->getEmployee();
        $this->data['priceList'] = $this->common->getPriceList();
        $this->data['branchList'] = $this->common->getBranchList();
        return view('customers.edit', $this->data);
    }

    public function update(CustomerRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            list(
                $customerData,
                $customerAddress,
                $customerBankDetails
            ) = $this->getInput($request->all(), $id);
            $id = $request->id;
            if($request->leadId){
                $customerData['lead_id'] = $request->leadId;
                $customer = Customer::create($customerData);
                $customerId = $customer->id ?? 0;
                $user_id = Sentinel::getUser()->id ?? 0;
                $date = Carbon::now()->format('Y-m-d');

                $createArray = [
                    'customer_id' => $customerId ?? null,
                    'from_price_list_id' => null,
                    'to_price_list_id' => $request['price_list_id'],
                    'user_id'=> $user_id ?? null,
                    'date'=> $date ?? null
                ];
                CustomerPriceListLog::create($createArray);

                $img_path = $customer->id;
                if ($request->hasFile('pan_card_photo')) {
                    $this->uploadPanCard($request, null, $img_path, $customerId);
                }
                if ($request->hasFile('gst_certificate_photo')) {
                    $this->uploadGstCertificate($request, null, $img_path, $customerId);
                }
    
                $customerAddress['customer_id'] = $customerId;
                $customerAddress = CustomerAddress::create($customerAddress);
                $customerBankDetails['customer_id'] = $customerId;
                $customerBankDetails = CustomerBankDetails::create($customerBankDetails);
    
                $userPassword = $request->password ?? 'Admin@123';

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
                            'empty_email' => $empty_email ?? 0,
                        ];
                        User::where('id', $userId)->update($userDataUpdate);
    
                        $roleUser = [];
                        $roleUser['user_id'] = $userId;
                        $roleUser['role_id'] = $role_id;
                        RoleUser::create($roleUser);
                    }
                }
                Lead::where('id', $request->leadId)->update(['is_customer' => 'Yes']);
            }else{
                $customer = Customer::findOrFail($id);
    
                if($customer->price_list_id != $request['price_list_id']){
                    $user_id = Sentinel::getUser()->id ?? 0;
                    $date = Carbon::now()->format('Y-m-d');
                    $createArray = [
                        'customer_id' => $id ?? null,
                        'from_price_list_id' => $customer->price_list_id,
                        'to_price_list_id' => $request['price_list_id'],
                        'user_id'=> $user_id ?? null,
                        'date'=> $date ?? null
                    ];
                    CustomerPriceListLog::create($createArray);
                }
    
                if($customer->email != $customerData['email']) {  
                    $customerData['empty_email'] = 0;
                }else{
                    $customerData['empty_email'] = 1;
                }
                $customer->update($customerData);
                $customerId = $customer->id;
                $img_path = $customerId;
                $userPassword = $request->get('password', false);
                $this->uploadPanCard($request, null, $img_path, $customerId);
                $this->uploadGstCertificate($request, null, $img_path, $customerId);
    
                $customer_addresses_id = $request->customer_addresses_id;
                $customer_bank_details_id = $request->customer_bank_details_id;
    
                $customerAddressData = CustomerAddress::findOrFail($customer_addresses_id);
                $customerAddressData->update($customerAddress);
                if($customer_bank_details_id != null){
                    $customerBankDetailsData = CustomerBankDetails::findOrFail($customer_bank_details_id);
                    $customerBankDetailsData->update($customerBankDetails);
                }else{
                    $customerBankDetails['customer_id'] = $customerId;
                    $customerBankDetails = CustomerBankDetails::create($customerBankDetails);
                }
    
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
                        'empty_email' => $customer['empty_email'] ?? 0,
                    ];
                    User::where('id', $userId)->update($userDataUpdate);
                }
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
                $customerBankDetails = CustomerBankDetails::where('customer_id', $id)->first();
                if ($customerBankDetails) {
                    $customerBankDetails->delete();
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
            'company_name' => trim($request['company_name']),
            'person_name' => $request['person_name'],
            'email' => $email ?? '',
            'managed_by' => $request['primary_managed_by'] ?? 0,
            'credit_days' => $request['credit_days'] ?? 0,
            'credit_limit' => $request['credit_limit'] ?? 0,
            'fssai_no' => $request['fssai_no'] ?? '',
            'price_list_id' => $request['price_list_id'] ?? 0,
            'branch_id' => $request['branch_id'] ?? 0,
            'gst_type' => $request['gst_type'] ?? '',
            'gst_no' => $request['gst_no'] ?? '',
            'pan_no' => $request['pan_no'] ?? '',
            'mobile' => $request['mobile'] ?? '',
            'is_create_user' => $request['is_create_user'] ?? 0,
        ];

        $customerAddress = [
            'address_line1' => $request['address_line1'] ?? '',
            'address_line2' => $request['address_line2'] ?? '',
            'pincode' => $request['pincode'] ?? '',
            'country_id' => $request['country_id'] ?? 0,
            'state_id' => $request['state_id'] ?? 0,
            'city_id' => $request['city_id'] ?? 0,
            'mobile2' => $request['mobile2'] ?? '',
            'phone' => $request['phone'] ?? '',
            'phone2' => $request['phone2'] ?? '',
            'branch_id' => $request['branch_id'] ?? 0,
        ];

        $customerBankDetails = [
            'beneficiary_name' => $request['beneficiary_name'] ?? '',
            'bank_name' => $request['bank_name'] ?? '',
            'account_no' => $request['account_no'] ?? '',
            'ifsc_code' => $request['ifsc_code'] ?? '',
            'branch_name' => $request['branch_name'] ?? '',
            'branch_id' => $request['branch_id'] ?? 0,
        ];

        return [$customerData, $customerAddress, $customerBankDetails];
    }

    public function uploadPanCard($request, $unlink = null, $img_path = null, $customerId = null)
    {
        if ($request->hasFile('pan_card_photo')) {
            $storepath = '/uploads/Customer/' . $img_path . '/Document/';
            $file['pan_card_photo'] = $this->getUniqueFilename($request->file('pan_card_photo'), $this->getImagePath($storepath));
            $request->file('pan_card_photo')->move($this->getImagePath($storepath), $file['pan_card_photo']);
            $customers['pan_card_photo'] = $storepath . $file['pan_card_photo'];
            if ($unlink != null) {
                if (File::exists(base_path('public' . $unlink))) {
                    unlink(base_path('public' . $unlink));
                }
            }
            $customerdata = Customer::findOrFail($customerId);
            $customerdata->update($customers);
        }
    }

    public function uploadGstCertificate($request, $unlink = null, $img_path = null, $customerId = null)
    {
        if ($request->hasFile('gst_certificate_photo')) {
            $storepath = '/uploads/Customer/' . $img_path . '/Document/';
            $file['gst_certificate_photo'] = $this->getUniqueFilename($request->file('gst_certificate_photo'), $this->getImagePath($storepath));
            $request->file('gst_certificate_photo')->move($this->getImagePath($storepath), $file['gst_certificate_photo']);
            $customers['gst_certificate_photo'] = $storepath . $file['gst_certificate_photo'];
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

    // checkDuplicateGstNo
    public function checkDuplicateGstNo(Request $request)
    {
        $gst_no = $request->gst_no;
        $id = $request->id;
        $customer = Customer::where('gst_no', $gst_no);
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

    // checkDuplicateFssaiNo
    public function checkDuplicateFssaiNo(Request $request)
    {
        $fssai_no = $request->fssai_no;
        $id = $request->id;
        $customer = Customer::where('fssai_no', $fssai_no);
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

    // checkDuplicateCompanyName
    public function checkDuplicateCompanyName(Request $request)
    {
        $company_name = $request->company_name;
        $id = $request->id;
        $customer = Customer::where('company_name', $company_name);
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

    public function change_primary_managed($id)
    {
        $customer = Customer::find($id);
        $this->data['customer'] = $customer;
        $usersData = Employee::select(DB::raw("CONCAT(first_name, ' ', last_name) as user_full_name"), 'id')
            ->where('is_active', 'Yes')
            ->orderBy('first_name', 'ASC')
            ->pluck('user_full_name', 'id')
            ->toArray();
        $this->data['employees'] = $usersData;
        return response()->json([
            'html' =>  view('customers.change_primary_managed', $this->data)->render()
        ]);
    }

    public function update_primary_managed(updateManagedRequest $request)
    {

        // $customerdata = Account::findOrFail($request->id);
        // $update_data = [
        //     'managed_by' => $request->primary_managed_by,
        //     'secondary_managed_by' => $request->secondary_managed_by,
        // ];
        // $customerdata->update($update_data);
        // return redirect()->route('customers.index')->with('success', __('customers.managed_by_has_been_updated_successfully'));
    }

    public function store(CustomerRequest $request)
    {
        DB::beginTransaction();
        try {
            list(
                $customerData,
                $customerAddress,
                $customerBankDetails
            ) = $this->getInput($request->all());

            $customerData['ip'] = $request->ip();
            $customerData['created_by'] = $this->user->id;
            $customerData['updated_by'] = $this->user->id;
            $userPassword = $request->get('password', false);
            $empty_email = $request['email'] ? 0 : 1;
            $customerData['empty_email'] = $empty_email;
            $customer = Customer::create($customerData);
            $customerId = $customer->id;

            $user_id = Sentinel::getUser()->id ?? 0;
            $date = Carbon::now()->format('Y-m-d');

            $createArray = [
                'customer_id' => $customerId ?? null,
                'from_price_list_id' => null,
                'to_price_list_id' => $request['price_list_id'],
                'user_id'=> $user_id ?? null,
                'date'=> $date ?? null
            ];
            CustomerPriceListLog::create($createArray);

            $img_path = $customer->id;
            if ($request->hasFile('pan_card_photo')) {
                $this->uploadPanCard($request, null, $img_path, $customerId);
            }
            if ($request->hasFile('gst_certificate_photo')) {
                $this->uploadGstCertificate($request, null, $img_path, $customerId);
            }

            $customerAddress['customer_id'] = $customerId;
            $customerAddress = CustomerAddress::create($customerAddress);
            $customerBankDetails['customer_id'] = $customerId;
            $customerBankDetails = CustomerBankDetails::create($customerBankDetails);


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
                        'empty_email' => $empty_email ?? 0,
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
            DB::rollback();
            info($e);
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return redirect()->route('customers.index')->with('success', __('common.create_success'));
    }
}
