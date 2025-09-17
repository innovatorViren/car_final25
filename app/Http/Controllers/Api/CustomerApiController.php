<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{Customer,Cart,Product};
use Exception;
use DB;
use URL;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerApiController extends ApiController
{

    public function getCustomerDashboard()
    {
        $user = $this->currentuser();
        $perPage = $this->perPageCommon();

        // if($user->emp_type == 'customer')
        // {
            
            $cusAddressData = DB::table('customer_adresses as CA')
                            ->select('CA.id as customer_address_id',
                                DB::raw("(CASE WHEN CA.name IS NOT NULL THEN  CA.name ELSE '' END) as name"),
                                DB::raw("(CASE WHEN CA.mobile IS NOT NULL THEN  CA.mobile ELSE '' END) as mobile"),
                                DB::raw("(CASE WHEN CA.address_type IS NOT NULL THEN  CA.address_type ELSE '' END) as address_type"),
                                DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                                DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                                DB::raw("(CASE WHEN CA.landmark IS NOT NULL THEN  CA.landmark ELSE '' END) as landmark"),
                                DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                                'CA.country_id',
                                'CA.state_id',
                                'CA.city_id',
                                'CA.is_default',
                                'S.name as state',
                                'C.name as city'
                            )
                            ->leftjoin('states as S','S.id','CA.state_id')
                            ->leftjoin('cities as C','C.id','CA.city_id')
                            ->where('CA.customer_id',$user->customer_id)
                            ->where('CA.is_default',1)
                            ->whereNull('CA.deleted_at')
                            ->first();
            $cusCarsData = DB::table('customer_cars as CC')
                            ->select(
                                'CC.id as customer_car_id',
                                'CC.customer_id as customer_id',
                                'CC.car_model_id as car_model_id',
                                'CC.car_brand_id as car_brand_id',
                                DB::raw("(CASE WHEN CB.name IS NOT NULL THEN  CB.name ELSE '' END) as car_brand_name"),
                                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                                'CC.is_default as selected',

                            )
                            ->leftjoin('car_brands as CB','CB.id','CC.car_brand_id')
                            ->leftjoin('car_models as CM','CM.id','CC.car_model_id')
                            ->where('CC.customer_id',$user->customer_id)
                            ->where('CC.is_default',1)
                            ->first();

            $customerWiseCar = DB::table('customer_cars as CC')
                            ->select(
                                'CC.id as customer_car_id',
                                'CC.car_model_id as car_model_id',
                                DB::raw("(CASE WHEN CC.car_brand_id IS NOT NULL THEN  CC.car_brand_id ELSE '' END) as car_brand_id"),
                                'CC.vehicle_name as vehicle_name',
                                DB::raw("(CASE WHEN CB.name IS NOT NULL THEN  CB.name ELSE '' END) as car_brand_name"),
                                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                                'CC.is_default as selected',
                            )
                            ->leftjoin('car_brands as CB','CB.id','CC.car_brand_id')
                            ->leftjoin('car_models as CM','CM.id','CC.car_model_id')
                            ->where('CC.customer_id',$user->customer_id)
                            ->get();
            $frequencyData = [
                                ['name' => 'Daily 1','value' => 'daily', 'title' => '30 days daily wash', 'description' => 'Every day for 30 days', 'washes' => 30, 'days' => 30],
                                ['name' => 'Week 2', 'value' => 'weekly_2', 'title' => '8 washes in 2 Week', 'description' => 'Any 8 washes within 14 days', 'washes' => 8, 'days' => 14],
                                ['name' => 'Week 1', 'value' => 'weekly_1', 'title' => '4 washes in 1 Week', 'description' => 'Any 4 washes within 7 days', 'washes' => 4, 'days' => 7],
                                ['name' => '1 Time Wash', 'value' => 'one_time', 'title' => 'Book a single Wash', 'description' => 'One time appoinment', 'washes' => 1, 'days' => 1],
                            ];
                            $this->data = collect($frequencyData);

        $this->response_json['cusAddressData'] = $cusAddressData; 
        $this->response_json['cusCarsData'] = $cusCarsData; 
        $this->response_json['customerWiseCar'] = $customerWiseCar; 
        $this->response_json['frequencyData'] = collect($frequencyData); 

        return $this->responseSuccessWithoutDataObject();

        // }else{
        //     dd('not customer dashboard');
        // }

    }
    public function getCustomerList()
    {

        $customers = DB::table('customers as C')
                    ->select(
                        DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as first_name"),
                        DB::raw("(CASE WHEN C.middle_name IS NOT NULL THEN  C.middle_name ELSE '' END) as middle_name"),
                        DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as last_name"),
                        DB::raw("(CASE WHEN C.email IS NOT NULL THEN  C.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN C.pincode IS NOT NULL THEN  C.pincode ELSE '' END) as pincode"),
                            DB::raw("(CASE WHEN C.address_line IS NOT NULL THEN  C.address_line ELSE '' END) as address_line"),
                        )
                    ->where('C.is_active','Yes')
                    ->whereNull('C.deleted_at')
                    ->groupBy('C.id')
                    ->orderBy('first_name','ASC')
                    ->get();
        $this->data = $customers;

        return $this->responseSuccessWithoutObject();
    }

    public function getCustomerDetail($customer_id)
    {
        $customers = DB::table('customers as C')
                    ->select(
                        DB::raw("(CASE WHEN C.first_name IS NOT NULL THEN  C.first_name ELSE '' END) as first_name"),
                        DB::raw("(CASE WHEN C.middle_name IS NOT NULL THEN  C.middle_name ELSE '' END) as middle_name"),
                        DB::raw("(CASE WHEN C.last_name IS NOT NULL THEN  C.last_name ELSE '' END) as last_name"),
                        DB::raw("(CASE WHEN C.email IS NOT NULL THEN  C.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN C.pincode IS NOT NULL THEN  C.pincode ELSE '' END) as pincode"),
                        DB::raw("(CASE WHEN C.address_line IS NOT NULL THEN  C.address_line ELSE '' END) as address_line"),
                        DB::raw("(CASE WHEN C.city_id IS NOT NULL THEN  C.city_id ELSE '' END) as city_id"),
                        DB::raw("(CASE WHEN C.state_id IS NOT NULL THEN  C.state_id ELSE '' END) as state_id"),
                        DB::raw("(CASE WHEN CI.name IS NOT NULL THEN  CI.name ELSE '' END) as city_name"),
                        DB::raw("(CASE WHEN S.name IS NOT NULL THEN  S.name ELSE '' END) as state_name"),
                        )
                    ->leftjoin('cities as CI','CI.id','C.city_id')
                    ->leftjoin('states as S','S.id','C.state_id')
                    ->where('C.id',$customer_id)
                    ->where('C.is_active','Yes')
                    ->whereNull('C.deleted_at')
                    ->first();
        $this->data = $customers;
        $this->response_json['orders'] = ''; 

        return $this->responseSuccessWithoutObject();
    }

    public function addCustomerAddress(Request $request)
    {
        try{
            $requestData = Validator::make($this->request->all(), [
                'customer_id' => 'required',
                'name' => 'required|string',
                'mobile' => 'required|string',
                'address_line1' => 'required|string',
                'address_line2' => 'required|string',
                'address_type' => 'required|in:home,work,other',
                'pincode' => 'required|string',
                'state_id' => 'required|string',
                'city_id' => 'required|string',
            ]);

            if ($requestData->fails()) {
                $this->response_json['message'] = $requestData->messages()->first();
                return $this->responseError();
            }

            $customer_id = $request->customer_id ?? 0;

            $stateName = DB::table('states')->where('id',$request->state_id)->first()->name ?? '';
            $cityName = DB::table('cities')->where('id',$request->city_id)->first()->name ?? '';

        $cuAdd = DB::table('customer_adresses')->insertGetId([
                'customer_id'    => $customer_id,
                'name'           => $request->name,
                'mobile'         => $request->mobile ?? null,
                'address_line1'  => $request->address_line1 ?? null,
                'address_line2'  => $request->address_line2 ?? null,
                'address_type'   => $request->address_type ?? 'home',
                'landmark'       => $request->landmark ?? null,
                'country_id'     => $request->country_id ?? null,
                'state_id'       => $request->state_id ?? null,
                'city_id'        => $request->city_id ?? null,
                'pincode'        => $request->pincode ?? null,
                'is_default'     => $request->has('is_default') ? true : false,
                'created_by'     => loginUserDetail()->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);


            $cusAddressData = DB::table('customer_adresses as CA')
                            ->select('CA.id as customer_address_id',
                                DB::raw("(CASE WHEN CA.name IS NOT NULL THEN  CA.name ELSE '' END) as name"),
                                DB::raw("(CASE WHEN CA.mobile IS NOT NULL THEN  CA.mobile ELSE '' END) as mobile"),
                                DB::raw("(CASE WHEN CA.address_type IS NOT NULL THEN  CA.address_type ELSE '' END) as address_type"),
                                DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                                DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                                DB::raw("(CASE WHEN CA.landmark IS NOT NULL THEN  CA.landmark ELSE '' END) as landmark"),
                                DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                                'CA.country_id',
                                'CA.state_id',
                                'CA.city_id',
                                'CA.is_default',
                                'S.name as state',
                                'C.name as city'
                            )
                            ->leftjoin('states as S','S.id','CA.state_id')
                            ->leftjoin('cities as C','C.id','CA.city_id')
                            ->where('CA.id',$cuAdd)
                            ->first();
            DB::table('customer_adresses')->where('customer_id',$customer_id)->update(['is_default'=>0]);
            DB::table('customer_adresses')->where('id',$cusAddressData->customer_address_id)->update(['is_default'=>1]);

            $this->data = $cusAddressData;
            return $this->responseSuccessWithoutObject();

        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

    }

    public function defaultCustomerAddress(Request $request)
    {
        $customerId = $request->get('customer_id','');
        $customerAddressId = $request->get('customer_address_id','');

        DB::table('customer_adresses')->where('customer_id',$customerId)->update(['is_default'=>0]);
        DB::table('customer_adresses')->where('id',$customerAddressId)->update(['is_default'=>1]);

        $this->response_json['message'] = 'Default address set successfully.';
        return $this->responseSuccessWithoutObject();

    }

    public function getCustomerWiseAddress($customer_id)
    {
        $cusAddressData = DB::table('customer_adresses as CA')
                            ->select('CA.id as customer_address_id',
                                DB::raw("(CASE WHEN CA.name IS NOT NULL THEN  CA.name ELSE '' END) as name"),
                                DB::raw("(CASE WHEN CA.mobile IS NOT NULL THEN  CA.mobile ELSE '' END) as mobile"),
                                DB::raw("(CASE WHEN CA.address_type IS NOT NULL THEN  CA.address_type ELSE '' END) as address_type"),
                                DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                                DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                                DB::raw("(CASE WHEN CA.landmark IS NOT NULL THEN  CA.landmark ELSE '' END) as landmark"),
                                DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                                'CA.country_id',
                                'CA.state_id',
                                'CA.city_id',
                                'CA.is_default',
                                'S.name as state',
                                'C.name as city'
                            )
                            ->leftjoin('states as S','S.id','CA.state_id')
                            ->leftjoin('cities as C','C.id','CA.city_id')
                            ->where('customer_id',$customer_id)
                            ->whereNull('CA.deleted_at')
                            ->get();
        $this->data = $cusAddressData;
        return $this->responseSuccessWithoutObject();
    }

    public function editCustomerAddress(Request $request)
    {
        try{
            $requestData = Validator::make($this->request->all(), [
                'customer_adress_id' => 'required',
                'customer_id' => 'required',
                'name' => 'required|string',
                'mobile' => 'required|string',
                'address_line1' => 'required|string',
                'address_line2' => 'required|string',
                'address_type' => 'required|in:home,work,other',
                'pincode' => 'required|string',
                'state_id' => 'required|string',
                'city_id' => 'required|string',
            ]);

            if ($requestData->fails()) {
                $this->response_json['message'] = $requestData->messages()->first();
                return $this->responseError();
            }
            $customerAdressId = $request->customer_adress_id ?? 0;
            $customer_id = $request->customer_id ?? 0;

            $cuAdd = DB::table('customer_adresses')->insertGetId([
                
                'customer_id'    => $customer_id,
                'name'           => $request->name,
                'mobile'         => $request->mobile ?? null,
                'address_line1'  => $request->address_line1 ?? null,
                'address_line2'  => $request->address_line2 ?? null,
                'address_type'   => $request->address_type ?? 'home',
                'landmark'       => $request->landmark ?? null,
                'country_id'     => $request->country_id ?? null,
                'state_id'       => $request->state_id ?? null,
                'city_id'        => $request->city_id ?? null,
                'pincode'        => $request->pincode ?? null,
                'is_default'     => $request->has('is_default') ? true : false,
                'created_by'     => loginUserDetail()->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('customer_adresses')->where('id',$customerAdressId)->update(['deleted_at'=>now()]);

            $this->data = $request->all();
            return $this->responseSuccessWithoutObject();
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

    }

    public function editCustomer(Request $request)
    {
        try {

            $requestData = Validator::make($this->request->all(), [
                'customer_id' => 'required',
            ]);
            if ($requestData->fails()) {
                $this->response_json['message'] = $requestData->messages()->first();
                return $this->responseError();
            }
            $customerId = $request->customer_id;

            // $path = URL::asset('');

            $result = DB::table("customers as C")
                    ->select([
                        'C.id',
                        DB::raw("(CASE WHEN C.company_name IS NOT NULL THEN  C.company_name ELSE '' END) as company_name"),
                        DB::raw("(CASE WHEN C.person_name IS NOT NULL THEN  C.person_name ELSE '' END) as person_name"),
                        DB::raw("(CASE WHEN C.mobile IS NOT NULL THEN  C.mobile ELSE '' END) as mobile"),
                        DB::raw("(CASE WHEN C.email IS NOT NULL THEN  C.email ELSE '' END) as email"),
                        DB::raw("(CASE WHEN C.gst_no IS NOT NULL THEN  C.gst_no ELSE '' END) as gst_no"),
                        DB::raw("(CASE WHEN CA.address_line1 IS NOT NULL THEN  CA.address_line1 ELSE '' END) as address_line1"),
                        DB::raw("(CASE WHEN CA.address_line2 IS NOT NULL THEN  CA.address_line2 ELSE '' END) as address_line2"),
                        DB::raw("(CASE WHEN CA.pincode IS NOT NULL THEN  CA.pincode ELSE '' END) as pincode"),
                        DB::raw("(CASE WHEN CA.city_id IS NOT NULL THEN  CA.city_id ELSE '' END) as city_id"),
                        DB::raw("(CASE WHEN CA.state_id IS NOT NULL THEN  CA.state_id ELSE '' END) as state_id"),
                        DB::raw("(CASE WHEN CA.country_id IS NOT NULL THEN  CA.country_id ELSE '' END) as country_id"),
                        DB::raw("(CASE WHEN CI.name IS NOT NULL THEN  CI.name ELSE '' END) as city_name"),
                        DB::raw("(CASE WHEN ST.name IS NOT NULL THEN  ST.name ELSE '' END) as state_name"),
                        DB::raw("(CASE WHEN CC.name IS NOT NULL THEN  CC.name ELSE '' END) as country_name"),
                        DB::raw("'-' as photo"),
                    ])
                    ->leftjoin("customer_addresses as CA", 'CA.customer_id', '=', 'C.id')
                    ->leftjoin('cities as CI','CI.id', '=', 'CA.city_id')
                    ->leftjoin('states as ST','ST.id', '=', 'CA.state_id')
                    ->leftjoin('countries as CC','CC.id', '=', 'CA.country_id')
                    ->where('C.id',$customerId)
                    ->whereNull('C.deleted_at')
                    ->first();

            

            $this->data = $result;

        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccessWithoutObject();
    }

    public function addCustomerCar(Request $request)
    {
        try{
            $requestData = Validator::make($this->request->all(), [
                'customer_id' => 'required',
                'car_model_id' => 'required',
                // 'car_brand_id' => 'required',
            ]);

            if ($requestData->fails()) {
                $this->response_json['message'] = $requestData->messages()->first();
                return $this->responseError();
            }
            $customer_id = $request->customer_id ?? 0;

            $carCusId = DB::table('customer_cars')->insertGetId([
                'customer_id'    => $customer_id,
                'car_model_id'   => $request->car_model_id,
                'car_brand_id'   => $request->car_brand_id ?? null,
                'vehicle_name'   => $request->vehicle_name ?? null,
                'created_by'     => loginUserDetail()->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);


            $cusCarsData = DB::table('customer_cars as CC')
                            ->select(
                                'CC.id as customer_car_id',
                                'CC.customer_id as customer_id',
                                'CC.car_model_id as car_model_id',
                                DB::raw("(CASE WHEN CC.car_brand_id IS NOT NULL THEN  CC.car_brand_id ELSE '' END) as car_brand_id"),
                                'CC.vehicle_name as vehicle_name',
                                DB::raw("(CASE WHEN CB.name IS NOT NULL THEN  CB.name ELSE '' END) as car_brand_name"),
                                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                                'CC.is_default as selected',

                            )
                            ->leftjoin('car_brands as CB','CB.id','CC.car_brand_id')
                            ->leftjoin('car_models as CM','CM.id','CC.car_model_id')
                            ->where('CC.id',$carCusId)
                            ->first();
            DB::table('customer_cars')->where('customer_id',$cusCarsData->customer_id)->update(['is_default'=>0]);
            DB::table('customer_cars')->where('id',$cusCarsData->customer_car_id)->update(['is_default'=>1]);

            $this->data = $cusCarsData;
            return $this->responseSuccessWithoutObject();
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

    }

    public function getCustomerWiseCar($customer_id)
    {
        $cusCarsData = DB::table('customer_cars as CC')
                            ->select(
                                'CC.id as customer_car_id',
                                'CC.car_model_id as car_model_id',
                                DB::raw("(CASE WHEN CC.car_brand_id IS NOT NULL THEN  CC.car_brand_id ELSE '' END) as car_brand_id"),
                                'CC.vehicle_name as vehicle_name',
                                DB::raw("(CASE WHEN CB.name IS NOT NULL THEN  CB.name ELSE '' END) as car_brand_name"),
                                DB::raw("(CASE WHEN CM.name IS NOT NULL THEN  CM.name ELSE '' END) as car_model_name"),
                                'CC.is_default as selected',
                            )
                            ->leftjoin('car_brands as CB','CB.id','CC.car_brand_id')
                            ->leftjoin('car_models as CM','CM.id','CC.car_model_id')
                            ->where('CC.customer_id',$customer_id)
                            ->get();
        $this->data = $cusCarsData;
        return $this->responseSuccessWithoutObject();
    }

    public function defaultCustomerCar(Request $request)
    {
        $customerId = $request->get('customer_id','');
        $customerCarId = $request->get('customer_car_id','');

        DB::table('customer_cars')->where('customer_id',$customerId)->update(['is_default'=>0]);
        DB::table('customer_cars')->where('id',$customerCarId)->update(['is_default'=>1]);

        $this->response_json['message'] = 'Default Car set successfully.';
        return $this->responseSuccessWithoutDataObject();

    }

    public function customerCardelete(Request $request)
    {
        $customer_car_id = $request->customer_car_id;

        $cart = DB::table('customer_cars')->where('id', $customer_car_id)->first();
        if($cart->is_default == 1){
            DB::table('customer_cars')->where('customer_id', $cart->customer_id)->orderBy('id','desc')->limit(1)->update(['is_default' => 1]);
            $cart = DB::table('customer_cars')->where('id', $customer_car_id)->delete();
        }else{
            $cart = DB::table('customer_cars')->where('id', $customer_car_id)->delete();
        }

        $this->response_json['message'] = 'Car Deleted';
        return $this->responseSuccessWithoutDataObject();
    }
}
