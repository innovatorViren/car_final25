<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\EmployeeDataTable;
use App\Http\Requests\EmployeeRequest;
use App\Models\{
    Employee,
    EmployeeAddress,
    EmployeeDocument,
    State,
    Country,
    Customer,
    Role,
    RoleUser,
    Setting
};
use App\Models\User;
use Carbon\Carbon;
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
        // $this->depart_id = DB::table('departments')->where('slug','sales')->whereNull('deleted_at')->first()->id ?? '';
    }

    public function index(Request $request, EmployeeDataTable $dataTable)
    {
        // $this->data['department'] =  $this->common->getDepartment();
        // $this->data['designation'] =  $this->common->getDesignation();
        $this->data['employeesData'] =  $this->common->getEmployeeWithEmpCode();
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

        $this->data['maritalstatus'] = Config('project.marital_status');
        $this->data['bloodgroup'] = Config('project.bloodgroup');

        return view('employee.create', $this->data);
    }


    public function store(EmployeeRequest $request)
    {
        // DB::beginTransaction();
        // try {
            list($employeeData, $employeeAddress, $employeeDocument) = $this->getInput($request->all());

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
            DB::commit();
        // } catch (Exception $e) {
        //     DB::rollback();
        //     info($e);
        //     // return false;
        //     $this->response_json['message'] = $e->getMessage();
        //     return $this->responseError();
        // }
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


    public function show($id)
    {
        $withArr = [
            'employeeAddress',
            'employeeDocument',
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

        $this->data['employee'] = $employee;
        $table_name =  $employee->getTable();
        $this->data['table_name'] = $table_name;
        // dd($this->data);
        return view('employee.show', $this->data);
    }

    public function edit($id)
    {
        $employee = Employee::with([
            'employeeAddress',
            'employeeDocument'
        ])->findOrFail($id);
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
        
        $this->data['bloodgroup'] = Config('project.bloodgroup');
        
        $this->data['employee'] = $employee;
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
        list($employeeData, $employeeAddress, $employeeDocument) = $this->getInput($request->all(), $id);
        $employee->update($employeeData);
        $employee_id = $id;
        $img_path = $employee_id;
        $this->uploadImage($request, $employee->photo, $img_path, $employee_id);

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

                if (File::exists($unlink_aadhar_card) && $unlink_aadhar_card != null) {
                    unlink(base_path('public' . $unlink_aadhar_card));
                }
                if (File::exists($unlink_drivinglicence) && $unlink_drivinglicence) {
                    unlink(base_path('public' . $unlink_drivinglicence));
                }
                if (File::exists($unlink_pancard) && $unlink_pancard) {
                    unlink(base_path('public' . $unlink_pancard));
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
            'strengths' => $request['strengths'],
            'weakness' => $request['weakness'],
            'blood_group' => $request['blood_group'],
            'beneficiary_name' => $request['beneficiary_name'],
            'bank_name' => $request['bank_name'],
            'ifsc_code' => $request['ifsc_code'],
            'account_no' => $request['account_no'],
            'branch_name' => $request['branch_name'],
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
        ];

        $employeeDocument = [
            'aadhar_card_no' => $request['aadhar_card_no'],
            'driving_licence_no' => $request['driving_licence_no'],
            'pan_card_no' => $request['pan_card_no'],
        ];


        if (empty($employee_id)) {
            $generateCode = $this->IDGenerator(new Employee, 'id', 4, 'E');
            $employeeData['employee_code'] = $generateCode;
        }

        return [$employeeData, $employeeAddress, $employeeDocument];
    }

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

    
}
