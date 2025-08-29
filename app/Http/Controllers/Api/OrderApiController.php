<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use URL;

class OrderApiController extends ApiController
{
    public function addOrder(Request $request)
    {
        dd($request->all());
    }
}
