<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\EmployeeDataTable;
use App\Http\Requests\EmployeeRequest;
use App\Models\{
    Employee,
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
    }

    public function index(Request $request, EmployeeDataTable $dataTable)
    {
        $this->data['employeesData'] =  $this->common->getEmployeeWithEmpCode();
        $this->data['type'] = ($request->has('type') && in_array($request->type, ['Yes', 'No'])) ? $request->type : '';
        return $dataTable->render('employee.index', $this->data);
    }

    public function create()
    {

        $this->data['generateCode'] = $this->idGenerator(new Employee, 'id', 4, 'E');

        $countryData = Country::where('name', 'India')->where('is_active', 'Yes')->first();
        $this->data['state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['city'] =  [];

        return view('employee.create', $this->data);
    }


    public function store(EmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            list($employeeData) = $this->getInput($request->all());
            $employee = Employee::create($employeeData);
            $userPassword = $request->get('password', false);
            $employee_id = $employee->id;
            $img_path = $employee_id;
            $this->uploadAadharCard($request, null, $img_path, $employee_id);

            $roleModal = Role::where('slug', 'employee')->first();
            $role_id = (!empty($roleModal)) ? $roleModal->id : NULL;
            $userData = [];
            $userData['first_name'] = $employee['first_name'];
            $userData['middle_name'] = $employee['middle_name'];
            $userData['last_name'] = $employee['last_name'];
            $userData['email'] = $employee['email'] ?? null;
            $userData['mobile'] = $employee['mobile'] ?? null;
            $userData['password'] = Hash::make($userPassword);
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
        } catch (Exception $e) {
            DB::rollback();
            info($e);
            // return false;
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        return redirect()->route('employee.index')->with('success', __('common.create_success'));
    }
    public function uploadAadharCard($request, $unlink = null, $img_path = null, $employee_id = null)
    {
        if ($request->hasFile('aadharcard_img')) {

            $storepath = '/uploads/Employee/' . $img_path  ;

            $file['aadharcard_img'] = $this->getUniqueFilename($request->file('aadharcard_img'), $this->getImagePath($storepath));

            $request->file('aadharcard_img')->move($this->getImagePath($storepath), $file['aadharcard_img']);

            $updateData = [
                'aadharcard_img' => $file['aadharcard_img'],
                'aadharcard_img_path' => $storepath . $file['aadharcard_img'],
            ];

            if (File::exists($unlink)) {
                unlink(base_path('public' . $storepath . $unlink));
            }
            $employeeData = Employee::findOrFail($employee_id);
            $employeeData->update($updateData);
        }
    }


    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        $this->data['employee'] = $employee;
        $table_name =  $employee->getTable();
        $this->data['table_name'] = $table_name;
        return view('employee.show', $this->data);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $countryData = Country::where('name', 'India')->where('is_active', 'Yes')->first();

        $this->data['state_id'] =  (!empty($countryData)) ? $this->common->getStates($countryData->id) : $this->common->getStates();
        $this->data['city'] = !empty($employee->state_id) ? $this->common->getCities($employee->state_id) : [];
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
        $employee = Employee::findOrFail($id);
        $userPassword = $request->get('password', false);
        list($employeeData) = $this->getInput($request->all(), $id);
        $employee->update($employeeData);
        $employee_id = $id;
        $img_path = $employee_id;
        $this->uploadAadharCard($request, $employee->aadharcard_img, $img_path, $employee_id);

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
        $employee = Employee::findOrFail($id);
        if ($employee) {
            DB::beginTransaction();
            $dependency = $employee->deleteValidate($id);
            if (!$dependency) {
                $unlink_aadhar_card = $employee->aadharcard_img_path;

                if (File::exists($unlink_aadhar_card) && $unlink_aadhar_card != null) {
                    unlink(base_path('public' . $unlink_aadhar_card));
                }
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
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'birth_date' => $request['birth_date'],
            'age' => $request['age'],
            'reference' => $request['reference'],
            'reference_tel_no' => $request['reference_tel_no'],
            'beneficiary_name' => $request['beneficiary_name'],
            'bank_name' => $request['bank_name'],
            'ifsc_code' => $request['ifsc_code'],
            'account_no' => $request['account_no'],
            'branch_name' => $request['branch_name'],
            'address' => $request['address'],
            'state_id' => $request['state'],
            'city' => $request['city'],
            'pincode' => $request['pincode'],
            'phone' => $request['phone'],
            'aadhar_card_no' => $request['aadhar_card_no'],
        ];

        if (empty($employee_id)) {
            $generateCode = $this->IDGenerator(new Employee, 'id', 4, 'E');
            $employeeData['employee_code'] = $generateCode;
        }

        return [$employeeData];
    }

    public function checkDuplicateAdhar(Request $request, $id = '')
    {
        $aadhar_card_no = $request->aadhar_card_no;
        $employee = Employee::where('id', $id)->first();

        $checkAdhar = Employee::where(['aadhar_card_no' => $aadhar_card_no])
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
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
        $user = User::where('email', $email);
        if ($id > 0) {
            $user = $user->where('emp_id', '!=', $id)->count();
        }else{
            $user = User::where('email', $email)->count();
        }
        

        $employee = Employee::where(['email' => $email])
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
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
         $employee = Employee::where(['mobile' => $mobile])
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->count();
        
        if ($employee > 0) {

            return 'false';
        } else {
            return 'true';
        }
    }


//     SET foreign_key_checks = 0;
// DROP TABLE `employee_addresses`, `employee_documents`, `employees`;

    
}
