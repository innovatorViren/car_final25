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
    public function getEmployeeList()
    {
        $employees = DB::table('employees as E')
                    ->select(
                        DB::raw("(CASE WHEN E.first_name IS NOT NULL THEN  E.first_name ELSE '' END) as first_name"),
                        DB::raw("(CASE WHEN E.middle_name IS NOT NULL THEN  E.middle_name ELSE '' END) as middle_name"),
                        DB::raw("(CASE WHEN E.last_name IS NOT NULL THEN  E.last_name ELSE '' END) as last_name"),
                        DB::raw("(CASE WHEN E.email IS NOT NULL THEN  E.email ELSE '' END) as email")
                        )
                    ->where('E.is_active','Yes')
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
                        )
                    ->where('E.id',$employee_id)
                    ->where('E.is_active','Yes')
                    ->whereNull('E.deleted_at')
                    ->first();
        $this->data = $employees;
        $this->response_json['orders'] = ''; 

        return $this->responseSuccessWithoutObject();
    }
}
