<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCustomers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\EmployeeDataTable;
use App\Http\Requests\EmployeeRequest;
use App\Models\{
    Employee,
    EmployeeAddress,
    EmployeeDocument,
    State,
    Department,
    Designation,
    Country,
    Customer,
    Role,
    RoleUser,
    Setting,
    AsmEmployee,
};
use App\Models\User;
use Carbon\Carbon;
use App\Exports\EmployeeExport;
use App\Exports\EmployeeAllDetailExport;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    protected $data, $common, $title, $response_json;
    private $path;
    private $is_public = true;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->common = new CommonController();
        $this->title = trans("employee.employee");
        view()->share('title', $this->title);
        $this->middleware('permission:employee.add', ['only' => ['create', 'store']]);
        $this->middleware('permission:employee.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:employee.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:employee.delete', ['only' => ['destroy']]);
        // $deviceType = env('ATT_DEVICE_TYPE', 'BioMax');
        // $srtpl = config('srtpl');
        $this->depart_id = DB::table('departments')->where('slug','sales')->whereNull('deleted_at')->first()->id ?? '';
    }

    public function index(Request $request, EmployeeDataTable $dataTable)
    {
        $this->data['department'] =  $this->common->getDepartment();
        $this->data['designation'] =  $this->common->getDesignation();
        $this->data['employeesData'] =  $this->common->getEmployeeWithEmpCode();
        $this->data['branchList'] = $this->common->getBranchList();
        $this->data['type'] = ($request->has('type') && in_array($request->type, ['Yes', 'No'])) ? $request->type : '';
        return $dataTable->render('employee.index', $this->data);
    }

    public function create()
    {

        $this->data['generateCode'] = $this->idGenerator(new Employee, 'id', 4, 'E');

        $countryData = Country::where('name', 'India')->where('is_active', 'Yes')->first();
        $this->data['present_state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['permanent_state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['present_city'] =  [];
        $this->data['permanent_city'] =  [];
        $this->data['department'] =  $this->common->getDepartment();
        $this->data['designation'] =  $this->common->getDesignation();

        $this->data['appointedBy'] =  $this->common->getEmployee();

        $this->data['maritalstatus'] = Config('project.marital_status');
        $this->data['bloodgroup'] = Config('project.bloodgroup');
        $this->data['customers'] = $this->common->getCustomer();
        $this->data['branchList'] = $this->common->getBranchList();

        return view('employee.create', $this->data);
    }


    public function getAppointee(Request $request)
    {
        $departmentId = Employee::where('id', $request->appointedID)->first()->department_id;
        $departmentName = Department::where('id', $departmentId)->first()->name;
        return  $departmentName;
    }

    public function getDesignation(Request $request)
    {
        $department_id = $request->department_id;
        $department = Designation::where('department_id', $department_id)->get();
        return  $department;
    }


    public function store(EmployeeRequest $request)
    {
        $check_entry = Employee::latest()->first();
        $finishTime = Carbon::now();
        $totalDuration = 10;
        if (!empty($check_entry)) {
            $totalDuration = $finishTime->diffInSeconds($check_entry->created_at);
        }
        if (!empty($check_entry) && (Sentinel::getUser()->id == $check_entry->created_by && $totalDuration <= 5 && $check_entry->first_name == $request['first_name'])) {
            return redirect()->route('employee.create')->with('success', 'Please Check into list entry added succesfully you submit form multiple time!!');
        }
        //--------------------------------------------------------

        DB::beginTransaction();
        try {
            list($employeeData, $employeeAddress, $employeeDocument, $emp_customers) = $this->getInput($request->all());
            $employeeData['old_employee_id'] =  $request->parentId ?? null;
            $employeeData['parent_employee_id'] =  $request->parentId ?? null;
            $employee = Employee::create($employeeData);
            $userPassword = $request->get('password', false);
            $employee_id = $employee->id;
            $img_path = $employee_id;
            $this->uploadImage($request, null, $img_path, $employee_id);
            $employeeAddress['employee_id'] = $employee_id;
            EmployeeAddress::create($employeeAddress);

            if (!empty($emp_customers)) {
                foreach ($emp_customers as $customer) {
                    $customerData = [
                        'employee_id' => $employee_id,
                        'customer_id' => $customer,
                    ];
                    EmployeeCustomers::create($customerData);
                }
            }

            $employeeDocument['employee_id'] = $employee_id;
            $employeeDocument = EmployeeDocument::create($employeeDocument);
            $employeeDocument_id = $employeeDocument->id;

            $this->uploadAadharCard($request, null, $img_path, $employee_id);

            $this->uploadDrivingLicence($request, null, $img_path, $employee_id);
            $this->uploadPanCard($request, null, $img_path, $employee_id);
            $this->uploadPassport($request, null, $img_path, $employee_id);
            // dd(66); 
            // dd($request->parentId);
            if ($request->parentId) {
                // $employeeData['parent_employee_id'] = $request->parentId;
                $pastEmployee = Employee::find($request->parentId);
                $pastInput['rejoin_date'] = $request->join_date;
                // $pastInput['is_active'] = "Yes";
                $pastEmployee->update($pastInput);

                $regUserData = User::where('emp_id', $pastEmployee->id)->where('is_active', 'No')->first();

                if ($regUserData) {
                    $userId = $regUserData->id;

                    $userDataUpdate = [
                        'is_active' => 'Yes',
                        'emp_id' => $employee_id,
                        'mobile' => $employee['mobile'] ?? '',
                        'first_name' => $employee['first_name'] ?? '',
                        'last_name' => $employee['last_name'] ?? '',
                        'middle_name' => $employee['middle_name'] ?? '',
                        'email' => $employee['email'] ?? '',
                    ];
                    User::where('id', $userId)->update($userDataUpdate);
                }
            } else {

                $roleModal = Role::where('slug', 'employee')->first();
                $role_id = (!empty($roleModal)) ? $roleModal->id : NULL;
                $userData = [];
                $userData['first_name'] = $employee['first_name'];
                $userData['middle_name'] = $employee['middle_name'];
                $userData['last_name'] = $employee['last_name'];
                $userData['email'] = $employee['email'] ?? null;
                $userData['mobile'] = $employee['mobile'] ?? null;
                $userData['password'] = $userPassword;
                $regUserData = Sentinel::registerAndActivate($userData);
                if ($regUserData) {
                    $userId = $regUserData->id;
                    $userDataUpdate = [
                        'is_active' => 'Yes',
                        'emp_type' => 'employee',
                        'emp_id' => $employee['id'],
                        'roles_id' => $role_id,
                        'mobile' => $employee['mobile'],
                        'first_name' => $employee['first_name'],
                        'last_name' => $employee['last_name'],
                        'middle_name' => $employee['middle_name'],
                        'email' => $employee['email'],
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
            // return false;
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return redirect()->route('employee.index')->with('success', __('common.create_success'));
    }

    public function uploadImage($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('photo')) {

            $storepath = '/uploads/Employee/' . $img_path . '/Person_Photo/';

            $file['photo'] = $this->getUniqueFilename($request->file('photo'), $this->getImagePath($storepath));

            $request->file('photo')->move($this->getImagePath($storepath), $file['photo']);

            $employees['photo'] = $file['photo'];
            $employees['photo_path'] = $storepath . $file['photo'];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }

            $employeedata = Employee::findOrFail($employee_id);
            $employeedata->update($employees);
        }
    }

    public function uploadAadharCard($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('aadharcard_img')) {

            $storepath = '/uploads/Employee/' . $img_path . '/Document/';

            $file['aadharcard_img'] = $this->getUniqueFilename($request->file('aadharcard_img'), $this->getImagePath($storepath));

            $request->file('aadharcard_img')->move($this->getImagePath($storepath), $file['aadharcard_img']);

            $employeesDocuments['aadharcard_img'] = $file['aadharcard_img'];
            $employeesDocuments['aadharcard_img_path'] = $storepath . $file['aadharcard_img'];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }
            $employeedocument = EmployeeDocument::findOrFail($employee_id);
            $employeedocument->update($employeesDocuments);
        }
    }

    public function uploadDrivingLicence($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('drivinglicence_img')) {

            $storepath = '/uploads/Employee/' . $img_path . '/Document/';

            $file['drivinglicence_img'] = $this->getUniqueFilename($request->file('drivinglicence_img'), $this->getImagePath($storepath));

            $request->file('drivinglicence_img')->move($this->getImagePath($storepath), $file['drivinglicence_img']);

            $employeesDocuments['drivinglicence_img'] = $file['drivinglicence_img'];
            $employeesDocuments['drivinglicence_img_path'] = $storepath . $file['drivinglicence_img'];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }

            $employeedocument = EmployeeDocument::findOrFail($employee_id);
            $employeedocument->update($employeesDocuments);
        }
    }

    public function uploadPanCard($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('pancard_img')) {

            $storepath = '/uploads/Employee/' . $img_path . '/Document/';

            $file['pancard_img'] = $this->getUniqueFilename($request->file('pancard_img'), $this->getImagePath($storepath));

            $request->file('pancard_img')->move($this->getImagePath($storepath), $file['pancard_img']);

            $employeesDocuments['pancard_img'] = $file['pancard_img'];
            $employeesDocuments['pancard_img_path'] = $storepath . $file['pancard_img'];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }

            $employeedocument = EmployeeDocument::findOrFail($employee_id);
            $employeedocument->update($employeesDocuments);
        }
    }

    public function uploadPassport($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('passport_img')) {

            $storepath = '/uploads/Employee/' . $img_path . '/Document/';

            $file['passport_img'] = $this->getUniqueFilename($request->file('passport_img'), $this->getImagePath($storepath));

            $request->file('passport_img')->move($this->getImagePath($storepath), $file['passport_img']);

            $employeesDocuments['passport_img'] = $file['passport_img'];
            $employeesDocuments['passport_img_path'] = $storepath . $file['passport_img'];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }

            $employeedocument = EmployeeDocument::findOrFail($employee_id);
            $employeedocument->update($employeesDocuments);
        }
    }

    public function show($id)
    {
        $withArr = [
            'employeeAddress',
            'employeeDocument',
            'appointed' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'designation_id');
                $query->with([
                    'designationName' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            }
        ];
        $employee = Employee::with($withArr)->findOrFail($id);
        // dd($employee->appointed);
        $employeeAddress = $employee->employeeAddress;

        $employeeDocument = $employee->employeeDocument;
        if ($employeeAddress) {
            $employee['present_address'] = $employeeAddress->present_address;
            $employee['permanent_address'] = $employeeAddress->permanent_address;
            $employee['present_state_id'] = $employeeAddress->present_state_id;
            $employee['permanent_state_id'] = $employeeAddress->permanent_state_id;
            $employee['present_city'] = $employeeAddress->present_city;
            $employee['permanent_city'] = $employeeAddress->permanent_city;
            $employee['present_pincode'] = $employeeAddress->present_pincode;
            $employee['permanent_pincode'] = $employeeAddress->permanent_pincode;
            $employee['same_as_present'] = $employeeAddress->get('same_as_present', null);
            $employee['mobile1'] = $employeeAddress->mobile1;
        }

        $employee['aadhar_card_no'] = $employeeDocument->aadhar_card_no;
        $employee['driving_licence_no'] = $employeeDocument->driving_licence_no;
        $employee['pan_card_no'] = $employeeDocument->pan_card_no;
        $employee['passport_no'] = $employeeDocument->passport_no;
        // $this->data['customers'] = $employee->customers;

        // $this->data['present_state_id'] = State::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        // $this->data['permanent_state_id'] = State::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        // $this->data['maritalstatus'] = Config('project.marital_status');
        // $this->data['department'] = Department::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        // $this->data['designation'] = Designation::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        // $this->data['bloodgroup'] = Config('project.bloodgroup');

        $this->data['child_employee_id'] = Employee::where('parent_employee_id', $employee->id)->first();

        // $this->data['appointedBy'] = Employee::where('is_active', 'Yes')->pluck('person_name', 'id')->toArray();

        $this->data['employee'] = $employee;
        $this->data['department'] = Department::where('id', $employee->department_id)->first();
        $this->data['designation'] = Designation::where('id', $employee->designation_id)->first();
        $table_name =  $employee->getTable();
        $this->data['table_name'] = $table_name;
        $this->data['branchList'] = $this->common->getBranchList();
        // dd($this->data);
        return view('employee.show', $this->data);
    }

    public function graphView(Request $request)
    {

        $emp = Employee::leftJoin('departments AS dept', 'employees.department_id', '=', 'dept.id')
            ->leftJoin('designations AS desig', 'employees.designation_id', '=', 'desig.id')
            ->select(
                'employees.department_id',
                'employees.designation_id',
                'dept.name as department_name',
                'desig.name as designation_name',
                'desig.grade as grade',
                DB::raw('(CONCAT(employees.first_name," ",employees.last_name," - ",DATE_FORMAT(employees.join_date, "%d-%m-%Y"))) as employee_name')
            )
            ->where('employees.is_active', 'Yes')
            ->where('employees.department_id', '!=', 0)
            ->orderBy('grade', 'asc')
            ->get();

        $this->data['employeeData'] = $emp->groupBy(['department_name', 'process_name', 'designation_name'])->toArray();
        //dd($this->data['employeeData']);

        // $empData = Employee::leftJoin('departments AS dept', 'employees.department_id', '=', 'dept.id')
        //     ->leftJoin('designations AS desig', 'employees.designation_id', '=', 'desig.id')
        //     ->select(
        //         'employees.id as empId',
        //         'dept.name as department_name',
        //         'desig.name as designation_name',
        //         DB::raw('GROUP_CONCAT(CONCAT(employees.first_name," ",employees.last_name," (",DATE_FORMAT(employees.join_date, "%d-%m-%Y"),")")) as employee_name'),
        //         DB::raw('COUNT(employees.id) as totalemps')
        //     )
        //     ->where('employees.department_id', '!=', 0)
        //     ->where('employees.is_active', 'Yes')
        //     ->groupBy('designation_id')
        //     ->get();
        // $this->data['emp'] = $empData->groupBy('department_name')->toArray();
        return view('employee.employee_graph', $this->data);
    }

    public function edit($id)
    {
        $employee = Employee::with([
            'employeeAddress',
            'employeeDocument',
            'employeeCustomers'
            // "appointeds"
        ])->findOrFail($id);
        // $appointeds_ids = $employee->appointeds->pluck('id')->toArray();
        $employeeAddress = $employee->employeeAddress;
        $employeeDocument = $employee->employeeDocument;
        if ($employeeAddress) {
            $employee['present_address'] = $employeeAddress->present_address;
            $employee['permanent_address'] = $employeeAddress->permanent_address;
            $employee['present_state_id'] = $employeeAddress->present_state_id;
            $employee['permanent_state_id'] = $employeeAddress->permanent_state_id;
            $employee['present_city'] = $employeeAddress->present_city;
            $employee['permanent_city'] = $employeeAddress->permanent_city;
            $employee['present_pincode'] = $employeeAddress->present_pincode;
            $employee['permanent_pincode'] = $employeeAddress->permanent_pincode;
            $employee['same_as_present'] = $employeeAddress->get('same_as_present', null);
            $employee['mobile1'] = $employeeAddress->mobile1;
            $employee['appointed_by'] = $employee->appointed_by;
            $employee['designation_of_appointee'] = $employee->designation_of_appointee;
        }
        if ($employeeDocument) {
            $employee['uan_no'] = $employeeDocument->uan_no;
            $employee['aadhar_card_no'] = $employeeDocument->aadhar_card_no;
            $employee['driving_licence_no'] = $employeeDocument->driving_licence_no;
            $employee['pan_card_no'] = $employeeDocument->pan_card_no;
            $employee['passport_no'] = $employeeDocument->passport_no;
        }

        $countryData = Country::where('name', 'India')->where('is_active', 'Yes')->first();
        $this->data['present_state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['permanent_state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['present_city'] = !empty($employeeAddress->present_state_id) ? $this->common->getCities($employeeAddress->present_state_id) : [];
        $this->data['permanent_city'] = !empty($employeeAddress->permanent_state_id) ? $this->common->getCities($employeeAddress->permanent_state_id) : [];
        $this->data['maritalstatus'] = Config('project.marital_status');
        $this->data['department'] = Department::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        $this->data['designation'] = Designation::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
        $this->data['bloodgroup'] = Config('project.bloodgroup');
        // $this->data['appointedBy'] = Employee::where('is_active', 'Yes')->pluck('person_name', 'id')->toArray();
        $field = [DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) as employee_name"), 'id'];
        // $doNotSelect = [$id, ...$appointeds_ids];
        $this->data['appointedBy'] =
            Employee::select($field)
            ->where('is_active', 'Yes')
            // ->whereNotIn('id', $doNotSelect)
            ->whereNotIn('id', [$id])
            ->orderBy('first_name', 'ASC')
            ->pluck('employee_name', 'id')
            ->toArray();
        $this->data['employee'] = $employee;
        $this->data['customers'] = $this->common->getCustomer();
        $this->data['branchList'] = $this->common->getBranchList();
        // employee_customers
        $this->data['employee_customers'] = $employee->employeeCustomers->pluck('customer_id')->toArray();
        return view('employee.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
        $employee = Employee::with(['employeeAddress', 'employeeDocument'])->findOrFail($id);
        // dd($employee);
        $userPassword = $request->get('password', false);
        list($employeeData, $employeeAddress, $employeeDocument, $emp_customers) = $this->getInput($request->all(), $id);
        $employee->update($employeeData);
        $employee_id = $id;
        $img_path = $employee_id;
        $this->uploadImage($request, $employee->photo, $img_path, $employee_id);

        // $old_emp_cust_ids = EmployeeCustomers::where('employee_id', $employee_id)->get();

        // if (!empty($old_emp_cust_ids)) {
        //     foreach ($old_emp_cust_ids as $old_emp_cust_id) {
        //         if (!in_array($old_emp_cust_id->customer_id, $emp_customers)) {
        //             EmployeeCustomers::where('employee_id', $employee_id)->where('customer_id', $old_emp_cust_id->customer_id)->delete();
        //         }
        //     }
        // }

        // if (!empty($emp_customers)) {
        //     foreach ($emp_customers as $customer) {
        //         $customerData = [
        //             'employee_id' => $employee_id,
        //             'customer_id' => $customer,
        //         ];
        //         $old_emp_cust_id = EmployeeCustomers::where('employee_id', $employee_id)->where('customer_id', $customer)->first();
        //         if (empty($old_emp_cust_id)) {
        //             EmployeeCustomers::create($customerData);
        //         }
        //     }
        // }

        $loginUser = Sentinel::getUser();
        $user_id = $loginUser ? $loginUser->id : 0;
        if (empty($loginUser) && Auth::check()) {
            $user_id = Auth::user()->id;
        }
        $updateArr = [
            'updated_by' => $user_id,
            'updated_at' => now()
        ];

        if ($userPassword) {
            $userData = [
                'password' => Hash::make($userPassword),
                'updated_by' => $user_id,
                'updated_at' => now()
            ];
            $regUserData = User::where('emp_id', $employee_id)->first();
            if ($regUserData) {
                $userId = $regUserData->id ;
                User::where('id', $userId)->update($userData);
            }
        }

        $employeeAddressObj = $employee->employeeAddress;
        if ($employeeAddressObj) {
            $employeeAddressObj->update($employeeAddress);
            DB::table('employees')->where('id', $id)->update($updateArr);
        } else {
            $employeeAddress['employee_id'] = $employee->id;
            $employee->employeeAddress = EmployeeAddress::create($employeeAddress);
        }


        $employeeDocumentObj = $employee->employeeDocument;
        if ($employeeDocumentObj) {
            $employeeDocumentObj->update($employeeDocument);
            DB::table('employees')->where('id', $id)->update($updateArr);
        }

        $this->uploadAadharCard($request, $employee->aadharcard_img, $img_path, $employee_id);
        $this->uploadDrivingLicence($request, $employee->drivinglicence_img, $img_path, $employee_id);
        $this->uploadPanCard($request, $employee->pancard_img, $img_path, $employee_id);
        $this->uploadPassport($request, $employee->passport_img, $img_path, $employee_id);

        $regUserData = User::where('emp_id', $employee->id)->where('is_active', 'Yes')->first();
        if ($regUserData) {
            $userId = $regUserData->id;
            $userDataUpdate = [
                'is_active' => 'Yes',
                'emp_id' => $employee['id'],
                'mobile' => $employee['mobile'],
                'first_name' => $employee['first_name'],
                'last_name' => $employee['last_name'],
                'middle_name' => $employee['middle_name'],
                // 'email' => $employee['email'],
            ];
            User::where('id', $userId)->update($userDataUpdate);
        }
        return redirect()->route('employee.index')->with('success', __('common.update_success'));
    }

    public function destroy($id)
    {
        $employee = Employee::with(
            [
                'employeeAddress',
                'employeeDocument'
            ]
        )->findOrFail($id);
        if ($employee) {
            DB::beginTransaction();
            $dependency = $employee->deleteValidate($id);
            if (!$dependency) {
                $unlink_img = $employee->photo_path;
                $unlink_aadhar_card = $employee->employeeDocument->aadharcard_img_path;
                $unlink_drivinglicence = $employee->employeeDocument->drivinglicence_img_path;
                $unlink_pancard = $employee->employeeDocument->pancard_img_path;
                $unlink_passport = $employee->employeeDocument->passport_img_path;

                if (File::exists($unlink_aadhar_card) && $unlink_aadhar_card != null) {
                    unlink(base_path('public' . $unlink_aadhar_card));
                }
                if (File::exists($unlink_drivinglicence) && $unlink_drivinglicence) {
                    unlink(base_path('public' . $unlink_drivinglicence));
                }
                if (File::exists($unlink_pancard) && $unlink_pancard) {
                    unlink(base_path('public' . $unlink_pancard));
                }
                if (File::exists($unlink_passport) && $unlink_passport) {
                    unlink(base_path('public' . $unlink_passport));
                }

                EmployeeAddress::where('employee_id', $id)->delete();
                EmployeeDocument::where('employee_id', $id)->delete();

                if (File::exists($unlink_img)) {
                    unlink(base_path($unlink_img));
                }

                $employee->delete();
                DB::commit();
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => __('employee.dependency_error', ['dependency' => $dependency]),
                ], 200);
            }
        }
        return response()->json([
            'success' => true,
            'message' => __('common.delete_success'),
        ], 200);
    }

    public static function IDGenerator($model, $trow, $length = 4, $prefix)
    {
        $lastEmp = $model::orderBy('id', 'desc')->first()  ?? null;
        $lastEmpCode = 1;
        if ($lastEmp) {
            $lastEmpCode = $lastEmp->employee_code;
            $lastEmpCode = intval(str_replace($prefix . "-", "", $lastEmpCode)) + 1;
        }
        $empCode = $prefix . "-" . str_pad($lastEmpCode, $length, '0', STR_PAD_LEFT);
        return $empCode;
    }

    public function getInput($request, $employee_id = null)
    {
        $is_salesman = "No";
        $emp_customers = [];
        $department = DB::table('departments')
            ->join('designations', 'departments.id', '=', 'designations.department_id')
            ->where('departments.id', $request['department_id'])
            ->where('designations.id', $request['designation_id'])
            ->where('departments.name', 'Sales')
            ->where('designations.name', 'Salesman')
            ->whereNull('departments.deleted_at')
            ->whereNull('designations.deleted_at')
            ->count();

        if ($department > 0) {
            $is_salesman = "Yes";
            $emp_customers = $request['emp_customers'] ?? [];
        }

        $employeeData = [
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'person_name' => $request['person_name'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'gender' => $request['gender'],
            'birth_date' => $request['birth_date'],
            'age' => $request['age'],
            'marital_status' => $request['marital_status'],
            'hobbies' => $request['hobbies'],
            'reference' => $request['reference'],
            'reference_tel_no' => $request['reference_tel_no'],
            'is_salesman' => $is_salesman,

            'strengths' => $request['strengths'],
            'weakness' => $request['weakness'],
            'blood_group' => $request['blood_group'],

            'beneficiary_name' => $request['beneficiary_name'],
            'bank_name' => $request['bank_name'],
            'ifsc_code' => $request['ifsc_code'],
            'account_no' => $request['account_no'],
            'branch_name' => $request['branch_name'],

            'experience' => $request['experience'],
            'total_experience' => $request['total_experience'],
            'join_date' => $request['join_date'],
            'department_id' => $request['department_id'],
            'designation_id' => $request['designation_id'],
            'designation_of_appointee' => $request['designation_of_appointee'],
            'appointed_by' => $request['appointed_by'],
            'branch_id' => $request['branch_id'] ?? 0,

        ];

        $employeeAddress = [
            'present_address' => $request['present_address'],
            'permanent_address' => $request['permanent_address'],
            'permanent_state_id' => $request['permanent_state'],
            'present_state_id' => $request['present_state'],
            'permanent_city' => $request['permanent_city'],
            'present_city' => $request['present_city'],
            'permanent_pincode' => $request['permanent_pincode'],
            'present_pincode' => $request['present_pincode'],
            'same_as_present' => !empty($request['same_as_present']) ? $request['same_as_present'] : '0',
            'mobile1' => $request['mobile1'],
            'branch_id' => $request['branch_id'] ?? 0,
        ];

        $employeeDocument = [
            'uan_no' => $request['uan_no'],
            'aadhar_card_no' => $request['aadhar_card_no'],
            'driving_licence_no' => $request['driving_licence_no'],
            'pan_card_no' => $request['pan_card_no'],
            'passport_no' => $request['passport_no'],
            'branch_id' => $request['branch_id'] ?? 0,
        ];


        if (empty($employee_id)) {
            $generateCode = $this->IDGenerator(new Employee, 'id', 4, 'E');
            $employeeData['employee_code'] = $generateCode;
        }

        return [$employeeData, $employeeAddress, $employeeDocument, $emp_customers];
    }

    public function employeeLeft(Request $request)
    {
        // dd($request->all());
        $empId = $request->empId;
        $left_date = $request->left_date;
        $left_reason = $request->left_reason;
        $recruit_again = $request->recruit_again;
        $sales_emp_id = $request->sales_emp_id;
        if ($empId > 0 && isset($left_date)) {
            $employeeData = Employee::findOrFail($empId);
            $empInput['left_date'] = $left_date;
            $empInput['left_reason'] = $left_reason;
            $empInput['recruit_again'] = $recruit_again;
            $empInput['is_active'] = 'No';
            $employeeData->update($empInput);

            // Ctc::where('employee_id', $empId)->update(['is_active' => 'No']);

            $userInput['is_active'] = 'No';
            $userData = User::where('emp_id', $empId)->first();

            if (isset($userData) && $userData->count() > 0) {
                $userData->update($userInput);
            }
            // Account::where('employee_id', $empId)->update(['is_active' => 'No']);

            // if (isset($sales_emp_id) && $sales_emp_id > 0) {
            //     Lead::where('sales_cordinator_id', $userData->id)->update(['sales_cordinator_id' => $sales_emp_id]);
            //     Lead::where('lead_owner_id', $userData->id)->update(['lead_owner_id' => $sales_emp_id]);
            //     Account::where('managed_by', $userData->id)->update(['managed_by' => $sales_emp_id]);
            //     Account::where('secondary_managed_by', $userData->id)->update(['secondary_managed_by' => $sales_emp_id]);
            // }

            /*try {
                $db_conn3 = DB::connection('mysql3');
                $db_conn3->table(env('DB_PREFIX').'_accounts')->where('employee_id', $empId)->update(['is_active' => 'No']);
            } catch (\Exception $e) {}*/


            return response()->json([
                'success' => true,
                'message' => __('common.update_success'),
            ], 200);
        }
    }

    public function leftCreate(Request $request)
    {
        $emp_id = $request->emp_id;
        $this->data['is_sales'] = false;
        $this->data['salesCordinator'] = [];
        if ($emp_id) {
            $employeeData = Employee::with('DepartmentName')->findOrFail($emp_id);
            $department = $employeeData->DepartmentName->name ?? '';
            if ($department == 'Sales') {
                $this->data['is_sales'] = true;
                $this->data['salesCordinator'] =  $this->common->getSalesCordinator();
            }
        }
        return response()->json([
            'html' =>  view('employee.employee_left_modal', $this->data)->render()
        ]);
    }

    public function employeeRejoin($parentId)
    {
        // dd($parentId);
        if ($parentId) {
            $this->data['generateCode'] = $this->idGenerator(new Employee, 'id', 4, 'E');
            $withArr = ['employeeAddress',  'employeeDocument'];
            $employee = Employee::with($withArr)->findOrFail($parentId);
            // /dd($employee);
            $employeeAddress = $employee->employeeAddress;
            $employeeDocument = $employee->employeeDocument;

            $employee['join_date'] = '';
            $employee['total_experience'] = '';
            $employee['department_id'] = '';
            $employee['designation_id'] = '';
            $employee['experience'] = '';

            if ($employeeAddress) {
                $employee['present_address'] = $employeeAddress->present_address;
                $employee['permanent_address'] = $employeeAddress->permanent_address;
                $employee['present_state_id'] = $employeeAddress->present_state_id;
                $employee['permanent_state_id'] = $employeeAddress->permanent_state_id;
                $employee['present_city'] = $employeeAddress->present_city;
                $employee['permanent_city'] = $employeeAddress->permanent_city;
                $employee['present_pincode'] = $employeeAddress->present_pincode;
                $employee['permanent_pincode'] = $employeeAddress->permanent_pincode;
                $employee['same_as_present'] = $employeeAddress->get('same_as_present', null);
                $employee['mobile1'] = $employeeAddress->mobile1;
            }

            if ($employeeDocument) {
                $employee['aadhar_card_no'] = $employeeDocument->aadhar_card_no;
                $employee['driving_licence_no'] = $employeeDocument->driving_licence_no;
                $employee['pan_card_no'] = $employeeDocument->pan_card_no;
                $employee['passport_no'] = $employeeDocument->passport_no;
            }

            $this->data['present_state_id'] = State::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
            $this->data['permanent_state_id'] = State::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
            $this->data['present_city'] = !empty($employeeAddress->present_state_id) ? $this->common->getCities($employeeAddress->present_state_id) : [];
            $this->data['permanent_city'] = !empty($employeeAddress->permanent_state_id) ? $this->common->getCities($employeeAddress->permanent_state_id) : [];
            $this->data['maritalstatus'] = Config('project.marital_status');
            $this->data['department'] = Department::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
            $this->data['designation'] = Designation::where('is_active', 'Yes')->pluck('name', 'id')->toArray();
            $this->data['bloodgroup'] = Config('project.bloodgroup');
            $this->data['appointedBy'] = Employee::where('is_active', 'Yes')->pluck('person_name', 'id')->toArray();

            $this->data['parentId'] = $parentId;
            $this->data['employee'] = $employee;
            $this->data['customers'] = $this->common->getCustomer();
            $this->data['branchList'] = $this->common->getBranchList();

            return view('employee.edit', $this->data);
        }
    }

    public function employeeExport(Request $request)
    {
        $settingsCmpNmData = Setting::where([ 'name' => 'company_name'])->first();
        $settingsCmpAddrData = Setting::where([ 'name' => 'company_address'])->first();
        $settingsCmpEmailData = Setting::where([ 'name' => 'company_email'])->first();

        $this->data['company_title'] = $settingsCmpNmData->value;
        $this->data['company_address'] = $settingsCmpAddrData->value;
        $this->data['company_email'] = $settingsCmpEmailData->value;
        $this->data['module_title'] = $this->title;

        return Excel::download(new EmployeeExport($this->data), 'employee.xlsx');
    }

    // public function employeeAllDetailExport(Request $request)
    // {
    //     $settingsCmpNmData = Setting::where(['group' => 'company', 'name' => 'company_name'])->first();
    //     $settingsCmpAddrData = Setting::where(['group' => 'company', 'name' => 'company_address'])->first();
    //     $settingsCmpEmailData = Setting::where(['group' => 'company', 'name' => 'company_email'])->first();

    //     $this->data['company_title'] = $settingsCmpNmData->value;
    //     $this->data['company_address'] = $settingsCmpAddrData->value;
    //     $this->data['company_email'] = $settingsCmpEmailData->value;
    //     $this->data['module_title'] = $this->title;

    //     return Excel::download(new EmployeeAllDetailExport($this->data), 'employee_all_details.xlsx');
    // }

    public function checkDuplicateAdhar(Request $request, $id = '')
    {
        $aadhar_card_no = $request->aadhar_card_no;
        $parent_id = [];
        if ($id > 0) {
            $get_parent =  DB::select("WITH RECURSIVE tree (parent_employee_id) AS ( SELECT parent_employee_id FROM employees WHERE id = $id UNION ALL SELECT lpc.parent_employee_id FROM employees lpc JOIN tree t ON t.parent_employee_id = lpc.id ) SELECT parent_employee_id FROM tree");
            $parent_id = array_filter(array_column($get_parent, 'parent_employee_id'));
        }
        $employee = Employee::where('id', $id)->first();
        $old_employee_id = $employee->old_employee_id ?? null;

        $checkAdhar = EmployeeDocument::where(['aadhar_card_no' => $aadhar_card_no])
            ->when($id, function ($q) use ($id) {
                $q->where('employee_id', '!=', $id);
            })
            ->when($old_employee_id, function ($q) use ($old_employee_id) {
                $q->where('employee_id', '!=', $old_employee_id);
            })
            ->when(!empty($parent_id), function ($query) use ($parent_id) {
                $query->whereNOTIN('employee_id', $parent_id);
            })
            ->count();

        if ($checkAdhar > 0) {
            return 'false';
        } else {
            return 'true';
        }
    }

    // getUniqueFilename() Imported from Mahalaxmi/Helper/AppHelper.php -> error occured AppHelper class not found
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
    // getImagePath() Imported from Mahalaxmi/Helper/AppHelper.php -> error occured AppHelper class not found
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

    public function employeeCustomers($id)
    {
        $viewPermission = $this->user->hasAnyAccess(['customers.view', 'users.superadmin']);

        $customerIds = EmployeeCustomers::where('employee_id', $id)->pluck('customer_id')->toArray();
        $results = Customer::whereIn('id', $customerIds)->get();
        $data_arr = [];
        foreach ($results as $index => $record) {
            // $checked = '';
            // $url = route('common.change-status', [$record->id]);
            // if (strtoupper($record->is_active) == 'YES' && $record->is_active !== NULL) {
            //     $checked = "checked";
            // }
            $data_arr[] = array(
                "id" => $index + 1,
                "company_name" =>
                $viewPermission ?
                    '<a href="' . route('customers.show', $record->id) . '">' . $record->company_name . '</a>' : $record->company_name,
                "person_name" => $record->person_name ?? null,
                "email" => $record->email ?? null,
                "gst_no" => $record->gst_no ?? null,
                //"is_active" => '<div class="text-center"><span class="switch switch-icon switch-md"><label><input type="checkbox" class="change-status" id="status_' . $record->id . '" name="status_' . $record->id . '" data-url="' . $url . '" data-table="customers" value="' . $record->id . '" ' . $checked . '><span></span></label></span></div>',
            );
        }
        return response()->json(['aaData' => $data_arr]);
    }
    public function checkDuplicateEmail(Request $request)
    {
        $email = $request->email;
        $id = $request->id;
        // $employee = Employee::where('email', $email);
        $user = User::where('email', $email);
        $parent_id = [];
        if ($id > 0) {
            $get_parent =  DB::select("WITH RECURSIVE tree (parent_employee_id) AS ( SELECT parent_employee_id FROM employees WHERE id = $id UNION ALL SELECT lpc.parent_employee_id FROM employees lpc JOIN tree t ON t.parent_employee_id = lpc.id ) SELECT parent_employee_id FROM tree");
            $parent_id = array_filter(array_column($get_parent, 'parent_employee_id'));
            $user = $user->where('emp_id', '!=', $id)->count();
        }else{
            $user = User::where('email', $email)->count();
        }
        // if ($id) {
        //     $employee = $employee->where('id','!=',$id)->where('old_employee_id', '!=', $id);
        //     $user = $user->where('emp_id', '!=', $id);
        // }
        

        $employee = Employee::where(['email' => $email])
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->when(!empty($parent_id), function ($query) use ($parent_id) {
                $query->whereNOTIN('id', $parent_id);
            })
            ->count();

        if (($employee > 0) || ($user > 0)) {
            return 'false';
        } else {
            return 'true';
        }
    }
    public function checkDuplicateMobile(Request $request)
    {
        $mobile = $request->mobile;
        $id = $request->id;
        // $employee = Employee::where('mobile', $mobile);
        $parent_id = [];
        if ($id > 0) {
            $get_parent =  DB::select("WITH RECURSIVE tree (parent_employee_id) AS ( SELECT parent_employee_id FROM employees WHERE id = $id UNION ALL SELECT lpc.parent_employee_id FROM employees lpc JOIN tree t ON t.parent_employee_id = lpc.id ) SELECT parent_employee_id FROM tree");
            $parent_id = array_filter(array_column($get_parent, 'parent_employee_id'));
        }
         $employee = Employee::where(['mobile' => $mobile])
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->when(!empty($parent_id), function ($query) use ($parent_id) {
                $query->whereNOTIN('id', $parent_id);
            })
            ->count();
        
        if ($employee > 0) {

            return 'false';
        } else {
            return 'true';
        }
    }

    public function getCustomerlist($id)
    {
        $this->data['id'] = $id;
        
        $customerEmployeeId = EmployeeCustomers::where('employee_id', $id)->pluck('customer_id')->toArray();
        $customerList = Customer::with(['customerAddress'])->where(['is_active' => 'Yes'])
            ->orWhereIn('id', $customerEmployeeId)
            ->get();

        $this->data['customerList'] = $customerList;
        $this->data['customerIds'] = $customerList->pluck('id')->toArray();
        $this->data['customerEmployeeId'] = $customerEmployeeId;

        return response()->json([
            'html' =>  view('employee.assign_customer', $this->data)->render()
        ]);
    }

    public function getEmployeeUpdate(Request $request)
    {
        $assign_customer = $request->assign_customer ?? [];
        $empId = $request->id ?? null;

        DB::beginTransaction();
        try {
            $employeeCustomerId = EmployeeCustomers::where('employee_id', $empId)->pluck('customer_id')->toArray();
            if(!empty($assign_customer) && count($assign_customer) > 0){
                $deletedIds = array_diff($employeeCustomerId, $assign_customer);
                if(count($deletedIds) > 0){
                    EmployeeCustomers::where('employee_id', $empId)->whereIn('customer_id', $deletedIds)->delete();
                }
                $createIds = array_diff($assign_customer, $employeeCustomerId);
                $input['employee_id'] = $empId;
                if(count($createIds) > 0){
                    foreach($createIds as $row){
                        $input['customer_id'] = $row;
                        EmployeeCustomers::create($input);
                    }
                }
            }else{
                if(count($employeeCustomerId) > 0 && empty($assign_customer)){
                    EmployeeCustomers::where('employee_id', $empId)->delete();
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message'=> $e->getMessage()
            ], 403);
        }
        return redirect()->route('employee.show', $empId)->with('success', __('employee.success'));
    }

    public function getSalesmanlist($id)
    {
        $this->data['id'] = $id;
        $subEmployeeId = AsmEmployee::where('employee_id', $id)->pluck('sub_employee_id')->toArray();
        $subOtherEmployeeId = AsmEmployee::where('employee_id','!=', $id)->pluck('sub_employee_id')->toArray();
        $asmEmployeeList = Employee::where(['is_active' => 'Yes','is_asm'=>'0'])->where('id','!=', $id)->whereNotIn('id',$subOtherEmployeeId)->where('department_id',$this->depart_id)->orderBy('first_name')->get();

        $this->data['salesmanList'] = $asmEmployeeList;
        $this->data['salesmanIds'] = $asmEmployeeList->pluck('id')->toArray();
        $this->data['subEmployeeId'] = $subEmployeeId;

        return response()->json([
            'html' =>  view('employee.assign_salesman', $this->data)->render()
        ]);
    }

    public function getAsmEmployeeUpdate(Request $request)
    {
        $assign_salesman = $request->assign_salesman ?? [];
        $empId = $request->id ?? null;

        DB::beginTransaction();
        try {
            $employeeAsmId = AsmEmployee::where('employee_id', $empId)->pluck('sub_employee_id')->toArray();
            if(!empty($assign_salesman) && count($assign_salesman) > 0){
                $deletedIds = array_diff($employeeAsmId, $assign_salesman);


                if(count($deletedIds) > 0){
                    AsmEmployee::where('employee_id', $empId)->whereIn('sub_employee_id', $deletedIds)->delete();
                    Employee::whereIn('id', $deletedIds)->update(['is_use_asm'=> '0']);
                }
                $createIds = array_diff($assign_salesman, $employeeAsmId);
                $input['employee_id'] = $empId;
                if(count($createIds) > 0){
                    foreach($createIds as $row){
                        $input['sub_employee_id'] = $row;
                        AsmEmployee::create($input);
                        Employee::where('id', $row)->update(['is_use_asm'=> '1']);
                        Employee::where('id', $empId)->update(['is_asm'=> '1']);
                    }
                }
            }else{
                if(count($employeeAsmId) > 0 && empty($assign_customer)){
                    AsmEmployee::where('employee_id', $empId)->delete();
                    Employee::whereIn('id', $employeeAsmId)->update(['is_use_asm'=> '0']);
                    Employee::where('id', $empId)->update(['is_asm'=> '0']);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message'=> $e->getMessage()
            ], 403);
        }
        return redirect()->route('employee.show', $empId)->with('success', __('employee.salesman_success'));
    }
}
