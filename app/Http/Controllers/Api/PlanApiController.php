<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\Validator;
use DB;
use Exception;
use School\Helper\Facades\AppHelper;

class PlanApiController extends ApiController
{
    public function getCarWisePlan(Request $request)
    {
        try {   

            if($request->type == 'bike'){
                $requestData = Validator::make($this->request->all(), [
                    'car_model_id' => 'required',
                    'frequency_type' => 'required',
                ]);
            }elseif($request->type == 'car'){
                $requestData = Validator::make($this->request->all(), [
                    'frequency_type' => 'required',
                ]);
            }else{
                $requestData = Validator::make($this->request->all(), [
                    'car_model_id' => 'required',
                    'frequency_type' => 'required',
                ]);
            }

            if ($requestData->fails()) {

                $this->response_json['status'] = 0;
                $this->response_json['message'] = $requestData->messages()->first();
                return $this->responseError();
            }

            if($request->type == 'bike'){
                $carModelId = DB::table('car_models')->where('name', 'LIKE', '%bike%')->first()->id;
            }elseif($request->type == 'car'){
                $carModelId = $request->get('car_model_id',false);
            }else{
                $carModelId = $request->get('car_model_id',false);
            }

            $frequencyType = $request->get('frequency_type',false);

            $carModel = DB::table('car_models')->where('id',$carModelId)->first();
            $planData = DB::table('plans')->where('car_size_id',$carModel->car_size_id)->where('frequency', $frequencyType)->whereNull('deleted_at')->first();
            
            $this->data = $planData;
            $this->response_json['message'] = 'Successfully';
            $this->response_json['status'] = 1;
            return $this->responseSuccessWithoutObject();
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            $this->response_json['status'] = 0;
            return $this->responseError();
        }

    }
}
