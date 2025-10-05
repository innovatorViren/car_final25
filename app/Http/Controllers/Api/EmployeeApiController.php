<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeApiController extends ApiController
{
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
