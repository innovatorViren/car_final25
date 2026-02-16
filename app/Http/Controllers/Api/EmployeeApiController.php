<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;
use App\Models\{Employee,Role,RoleUser,User,Wash};
use Illuminate\Pagination\LengthAwarePaginator;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeApiController extends ApiController
{
    public function addEmployee(Request $request)
    {
        DB::beginTransaction();
        try {

            $requestData = Validator::make($this->request->all(), [
                'first_name'=>'required',
                'last_name'=>'required',
                'mobile'=>'required',
                'email'=>'required',
                'birth_date'=>'required',
                'address'=>'required',
                'state_id'=>'required',
                'city'=>'required',
                'aadhar_card_no'=>'nullable',
                'account_no'=>'nullable',
                'ifsc_code'=>'nullable',
                'password'=>'nullable',
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $mobRecode = DB::table('employees')->whereNull('deleted_at')->where('mobile',$request->mobile)->first();
            $aadharRecode = DB::table('employees')->whereNull('deleted_at')->where('aadhar_card_no',$request->aadhar_card_no)->first();

            
            $userPassword = $request->get('password', false);

            if($mobRecode)
            {
                $this->response_json['status'] = 0;
                $this->response_json['message'] = 'Mobile Number already add.!';
                return $this->responseError();
            }
            if($aadharRecode)
            {
                $this->response_json['status'] = 0;
                $this->response_json['message'] = 'Please Enter unique Aadhar no.!';
                return $this->responseError();
            }

            $generateCode = $this->IDGenerator(new Employee, 'id', 4, 'E');
            $data = [
                'first_name' => $request->first_name ??  null,
                'middle_name' => $request->middle_name?? null,
                'last_name' => $request->last_name?? null,
                'employee_code' => $generateCode,
                'mobile' => $request->mobile?? null,
                'email' => $request->email?? null,
                'birth_date' => $request->birth_date?? null,
                'reference' => $request->reference?? null,
                'reference_tel_no' => $request->reference_tel_no?? null,
                'beneficiary_name' => $request->beneficiary_name?? null,
                'bank_name' => $request->bank_name?? null,
                'ifsc_code' => $request->ifsc_code?? null,
                'account_no' => $request->account_no?? null,
                'address' => $request->address?? null,
                'state_id' => $request->state_id?? null,
                'city' => $request->city?? null,
                'pincode' => $request->pincode?? null,
                'aadhar_card_no' => $request->aadhar_card_no?? null,
            ];
            
            $employee = Employee::create($data);


            if($request->hasfile('aadharcard_img'))
            {
                $file = $request->file('aadharcard_img');
                $extenstion = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extenstion;
                $file->move('uploads/Employee/', $filename);
                $employee->aadharcard_img = $filename;
                $employee->aadharcard_img_path = '/uploads/Employee/' .$filename;
            }

            $employee->save();


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
                    'password' => Hash::make($userPassword),
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
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        $this->response_json['message'] = 'Employee created Successfully!!';
        return $this->responseSuccess();
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


    public function getEmployeeList(Request $request)
    {
        $search = $request->get('search', '');
        $employees = DB::table('employees as E')
                    ->select(
                        'E.id as employee_id',
                        DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as first_name"),
                        DB::raw("(CASE WHEN E.middle_name IS NOT NULL THEN  E.middle_name ELSE '' END) as middle_name"),
                        DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as last_name"),
                        DB::raw("(CASE WHEN E.email IS NOT NULL THEN  E.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN E.mobile IS NOT NULL THEN  E.mobile ELSE '' END) as mobile")
                        )
                    ->where('E.is_active','Yes')
                    ->when($search, function ($query, $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->whereRaw("CONCAT(E.first_name, ' ', E.last_name) LIKE ?", ["%{$search}%"]);

                        });
                    })
                    ->whereNull('E.deleted_at')
                    ->groupBy('E.id')
                    ->orderBy('first_name','ASC')
                    ->get();
        $this->data = $employees;

        return $this->responseSuccessWithoutObject();
    }

    public function getEmployeeDetail($employee_id)
    {
        $employees = DB::table('employees as E')
                    ->select(
                        DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as first_name"),
                        DB::raw("(CASE WHEN E.middle_name IS NOT NULL THEN  E.middle_name ELSE '' END) as middle_name"),
                        DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as last_name"),
                        DB::raw("(CASE WHEN E.email IS NOT NULL THEN  E.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN E.mobile IS NOT NULL THEN  E.mobile ELSE '' END) as mobile"),
                        DB::raw("(CASE WHEN E.address IS NOT NULL THEN  E.address ELSE '' END) as address"),
                        DB::raw("(CASE WHEN E.pincode IS NOT NULL THEN  E.pincode ELSE '' END) as pincode"),
                        DB::raw("(CASE WHEN S.name IS NOT NULL THEN  S.name ELSE '' END) as state_name"),
                        DB::raw("(CASE WHEN C.name IS NOT NULL THEN  C.name ELSE '' END) as city_name")
                        )
                    ->join('states as S','S.id','E.state_id')
                    ->join('cities as C','C.id','E.city')
                    ->where('E.id',$employee_id)
                    ->where('E.is_active','Yes')
                    ->whereNull('E.deleted_at')
                    ->first();

        $fields = [
            'O.id as order_id',
            'O.code as code',
            'O.total_washes as total_washes',
            'O.status as status',
            'O.pay_amount as pay_amount',
            DB::raw("(CASE WHEN O.start_date IS NOT NULL THEN DATE_FORMAT(O.start_date, '%d-%m-%Y') ELSE '' END) as start_date"),
            DB::raw("(CASE WHEN O.start_time IS NOT NULL THEN DATE_FORMAT(O.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
            DB::raw("(CASE WHEN O.end_time IS NOT NULL THEN DATE_FORMAT(O.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
            DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
            DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
        ];

        $orderData = DB::table('orders as O')
                ->select($fields)
                ->join('customers as C','C.id','O.customer_id')
                ->leftjoin('employees as E', function ($join) {
                    $join->on('E.id', '=', 'O.employee_id');
                })
                ->whereNull('O.deleted_at')
                ->where('O.employee_id',$employee_id)
                ->orderBy('O.id', 'DESC')
                ->get();
        $this->data = $employees;
        $this->response_json['orders'] = $orderData; 

        return $this->responseSuccessWithoutObject();
    }

    public function getEmployeeDashboard($id)
    {

        $fields = [
            'O.id as order_id',
            'W.id as wash_id',
            'O.code as code',
            'O.status as status',
            'W.scheduled_date as scheduled_date',
            DB::raw("(CASE WHEN W.start_time IS NOT NULL THEN DATE_FORMAT(W.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
            DB::raw("(CASE WHEN W.end_time IS NOT NULL THEN DATE_FORMAT(W.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
            DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
            DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
        ];

        $orderData = DB::table('orders as O')
                ->select($fields)
                ->join('customers as C','C.id','O.customer_id')
                ->join('washes as W','W.order_id','O.id')
                ->whereNull('O.deleted_at')
                ->where('W.employee_id',$id)
                ->whereDate('W.scheduled_date', Carbon::today())
                ->orderBy('O.id', 'DESC')
                ->get();
        // $this->data = '';
        $this->response_json['today_orders'] = $orderData; 

        return $this->responseSuccessWithoutObject();
    }

    public function getEmployeeDashboardDetail($wasId)
    {

        $fields = [
            'W.id as wash_id',
            'W.status as status',
            'C.mobile as mobile',
            'CM.name as car_model_name',
            DB::raw("(CASE WHEN O.vehicle_no IS NOT NULL THEN  O.vehicle_no ELSE '' END) as vehicle_no"),
            'CA.address_line1 as address_line1',
            'CA.address_line2 as address_line2',
            'CA.pincode as pincode',
            'CI.name as city',
            'S.name as state',
            DB::raw("(CASE WHEN W.start_time IS NOT NULL THEN DATE_FORMAT(W.start_time, ' %I:%i %p') ELSE '' END) as start_time"),
            DB::raw("(CASE WHEN W.end_time IS NOT NULL THEN DATE_FORMAT(W.end_time, ' %I:%i %p') ELSE '' END) as end_time"),
            DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as customer_first_name"),
            DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as customer_last_name"),
        ];

        $orderData = DB::table('orders as O')
                ->select($fields)
                ->join('customers as C','C.id','O.customer_id')
                ->join('washes as W','W.order_id','O.id')
                ->join('car_models as CM','CM.id','O.car_model_id')
                ->join('customer_adresses as CA','CA.id','O.customer_adress_id')
                ->join('cities as CI','CI.id','CA.city_id')
                ->join('states as S','S.id','CA.state_id')
                ->whereNull('O.deleted_at')
                ->where('W.id',$wasId)
                ->first();
        $this->data = $orderData;
        // $this->response_json['today_orders'] = $orderData; 

        return $this->responseSuccessWithoutObject();
    }

    public function carWashEmployee(Request $request)
    {
        DB::beginTransaction();
        try {

            $requestData = Validator::make($this->request->all(), [
                'type'=>'required',
                'wash_id'=>'required',
                'emp_id'=>'required',
                'photo' => 'required|file|mimes:jpg,jpeg,png'
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }


            $washId = $request->get('wash_id');
            $type = $request->get('type');

            $wash = DB::table('washes')->where('id', $washId)->first();
            if (!$wash) {
                throw new Exception('Wash not found.');
            }


            $orderId = $wash->order_id ?? 'general';


             // Build directory path
            $destinationPath = public_path("uploads/wash/{$orderId}");
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $photoPath = "uploads/wash/{$orderId}/{$fileName}"; // Save this in DB

            $updateData = [];
            if ($type === 'start') {
                $updateData = [
                    'before_wash_photo' => $photoPath,
                    'wash_start_time' => Carbon::now(),
                    'status' => 'in_progress',
                ];
            } else {
                $updateData = [
                    'after_wash_photo' => $photoPath,
                    'wash_end_time' => Carbon::now(),
                    'status' => 'completed',
                ];
            }
            DB::table('washes')->where('id', $washId)->update($updateData);
            DB::table('orders')->where('id', $orderId)->update(['status' => $this->getOrderStatus($orderId)]);
            DB::commit();
        } catch (Exception $e) {

            DB::rollback();
            info($e);
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        $this->response_json['message'] = 'Car Wash Successfully';
        return $this->responseSuccess();
    }


    public function getOrderStatus($orderId)
    {
        $status_array = Wash::where('order_id', $orderId)->pluck('status')->toArray();

        // Normalize case
        $status_array = array_map('strtolower', $status_array);

        // Define main order status
        if (empty($status_array)) {
            return 'Pending';
        }

        if (in_array('in_progress', $status_array) ||
            (in_array('pending', $status_array) && in_array('completed', $status_array)) ||
            (in_array('pending', $status_array) && in_array('in_progress', $status_array)) ||
            (in_array('completed', $status_array) && in_array('in_progress', $status_array))) {
            return 'Partial';
        }

        if (count(array_unique($status_array)) === 1) {
            // All items same status
            switch ($status_array[0]) {
                case 'completed':
                    return 'Completed';
                case 'cancelled':
                    return 'Cancelled';
                case 'pending':
                    return 'Pending';
                case 'in_progress':
                    return 'Partial'; // can be treated as partial progress
            }
        }

        // Mixed statuses
        if (in_array('completed', $status_array) && !in_array('pending', $status_array)) {
            return 'Completed';
        }

        if (in_array('cancelled', $status_array) && count(array_unique($status_array)) === 1) {
            return 'Cancelled';
        }

        // Default
        return 'Pending';
    }

}
