<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;
// use App\Models\{Employee};
use Illuminate\Pagination\LengthAwarePaginator;

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
                'phone'=>'nullable',
                'aadhar_card_no'=>'nullable',
                'account_no'=>'nullable',
                'ifsc_code'=>'nullable',
            ]);

            if ($requestData->fails()) {
                throw new Exception($requestData->messages()->first(), 1);
            }

            $mobRecode = DB::table('employees')->whereNull('deleted_at')->where('mobile',$request->mobile)->first();
            $aadharRecode = DB::table('employees')->whereNull('deleted_at')->where('aadhar_card_no',$request->aadhar_card_no)->first();

            

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



            $data = [
                'first_name' => $request->first_name ?? null,
                'middle_name' => $request->middle_name ?? null,
                'last_name' => $request->last_name ?? null,
                'mobile' => $request->mobile ?? null,
                'email' => $request->email ?? null,
                'birth_date' => $request->birth_date ?? null,
                'reference' => $request->reference ?? null,
                'reference_tel_no' => $request->reference_tel_no ?? null,
                'beneficiary_name' => $request->beneficiary_name ?? null,
                'bank_name' => $request->bank_name ?? null,
                'ifsc_code' => $request->ifsc_code ?? null,
                'account_no' => $request->account_no ?? null,
                'address' => $request->address ?? null,
                'state_id' => $request->state_id ?? null,
                'city' => $request->city ?? null,
                'pincode' => $request->pincode ?? null,
                'phone' => $request->phone ?? null,
                'aadhar_card_no' => $request->aadhar_card_no ?? null,
            ];
            
            $employee = Employee::create($data);


            if($request->hasfile('aadharcard_img'))
            {
                $file = $request->file('aadharcard_img');
                // dd($file);
                $extenstion = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extenstion;
                $file->move('uploads/Employee/', $filename);
                $employee->aadharcard_img = $filename;
                $employee->aadharcard_img_path = '/uploads/Employee/' .$filename;
            }

            $employee->save();
            
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
                        DB::raw("(CASE WHEN E.mobile IS NOT NULL THEN  E.mobile ELSE '' END) as mobile")
                        )
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
}
