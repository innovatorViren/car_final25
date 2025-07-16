<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public $successStatus = 200;
    public $response_json = [];
    protected $data = [];
    protected $request;

    public function __construct(Request $request)
    {

        //Log::channel('api')->info($request->all());
        $this->request = $request;
        $this->response_json['message'] = 'Success';
    }

    public function responseUnauthenticated()
    {
        $this->response_json['status'] = 0;
        return response()->json($this->response_json, 401);
    }
}
