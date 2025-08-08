<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use App\Models\Setting;
use App\Models\{Employee,Role,sessions};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use DB;
use URL;
use Sentinel;
use Carbon\Carbon;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        try {

            $loginData = Validator::make($this->request->all(), [
                'mobile' => 'required',
                'password' => 'required|min:5',
                // 'device_token' => 'required'
            ]);
            
            if ($loginData->fails()) {
                throw new Exception($loginData->messages()->first(), 1);
            }

            $validEmail = DB::table('users')->where('mobile',$request->mobile)->count();
            if($validEmail > 0){

            }else{
    
                throw new Exception('Please enter a correct mobile !', 1);
            }
            
            if (!Auth::attempt([
                'mobile' => $request->mobile,
                'password' => $request->password,
            ])) {
                throw new Exception('Please enter a correct password !', 1);
                // throw new Exception('Invalid Credentials!', 1);
            }

            $user = $this->currentuser();
            if ($user->is_active == 'No') {
                throw new Exception('Your account has not been activated yet!', 1);
            }

            
            $curr_user = $this->currentuser();

            // Multiple login are another device autometic logout strat
            $sessions = sessions::get();
            if(!empty($sessions)){
                foreach ($sessions as $key => $session) {
                    $user_id = $session->user_id ?? 0;
                    if ($user_id == $user->id && $session->token != Session::get('_token')) {
                        DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
                        $session->delete();
                    }
                }
            }
            
            $login_user = Sentinel::findById($curr_user->id);
            $superadmin = $login_user->hasAccess(['users.superadmin']);

            if($superadmin == false){
                if($login_user->roles_id == 1){
                    $superadmin = true;
                }
            }

            $user_role_id = $this->currentuser()->roles_id;

            $rolesData = Role::where('id',$user_role_id)->first();
            $rolesData_array = Role::where('id',$user_role_id)->first()->permissions;
                

            $this->data = $this->userCollection($user);
            $this->response_json['setting_info'] = $this->getSettingData();

            $payload = '';
            $platform = $request->get('Platform', '');
            $deviceModel = $request->get('DeviceModel', '');
            $deviceId = $request->get('DeviceId', '');
            $deviceVersion = $request->get('DeviceVersion', '');
            $deviceBattery = $request->get('DeviceBattery', '');
            $payload = json_encode([
                "platform" => $platform,
                "device_model" => $deviceModel,
                "device_id" => $deviceId,
                "device_version" => $deviceVersion,
                "device_battery" => $deviceBattery,
            ]);

            
            $session_data = array(
                'id' => Session::getId(),
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'payload' => $payload,
                'last_activity' => Carbon::now()->addMonth()->timestamp,
                'platform' => 'App',
                'token' => $this->data['access_token'] ?? '',
            );
            DB::table('sessions')->insert($session_data);
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    public function sendotp(Request $request)
    {
        try {

            $validator = Validator::make($this->request->all(), [
                'mobile' => 'required|numeric|digits:10'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->messages()->first(), 1);
            }

            $mobile = $request->mobile;
            
            $user = User::where('mobile', $mobile)->first();

            if($user)
            {
                $user_mobile = $user->mobile;
                $otp_result = $this->send_otp_sms($user_mobile);

                if($otp_result == 1)
                {
                    $this->response_json['message'] = 'OTP sent to registered mobile number.';
                }
                else
                {
                    $this->response_json['message'] = 'Failed to send otp! Please try again.';
                    return $this->responseError();
                }
            }
            else
            {
                $this->response_json['message'] = 'User not found with this mobile.';
                return $this->responseError();
            }
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    public function send_otp_sms($mobile)
    {
        $random_otp = $this->generateRandomCode();

        $update_otp = User::where('mobile', $mobile)->update(['two_factor_secret' => $random_otp]);

        $message = "Your verification (OTP) code is " . $random_otp . " to confirm your phone no at BACIMO.";
        $receiver_phone = $mobile;

        //sned sms in mobile number
        $this->send_sms($message, $receiver_phone);

        return $update_otp;
    }

    public function otpVerify(Request $request)
    {

        try {

            $validator = Validator::make($this->request->all(), [
                'otp' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->messages()->first(), 1);
            }

            $otp = $request->otp;
            
            $is_valid_otp = User::where('two_factor_secret', $otp)->first();

            if($is_valid_otp)
            {
                $update = User::where('two_factor_secret', $otp)->update(['two_factor_secret' => '']);

                $this->response_json['message'] = 'Verification successfull.';

                $user_data['user_id'] = $is_valid_otp->id;
                $this->data = $user_data;
            }
            else
            {
                $this->response_json['message'] = 'Please enter correct otp send to your mobile.';
                return $this->responseError();
            }
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    public function forgot_password(Request $request)
    {
        try {

            $validator = Validator::make($this->request->all(), [
                'user_id' => 'required',
                'new_password' => 'required|same:confirm_password',
                'confirm_password' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->messages()->first(), 1);
            }

            $user_id = $request->user_id;
            $new_password = $request->new_password;
            $confirm_password = $request->confirm_password;

            $user_data = User::find($user_id);

            if($user_data->two_factor_secret == '' && $user_data->two_factor_secret == null)
            {
                $update = $user_data->update(['password' => Hash::make($new_password)]);

                $this->response_json['message'] = 'New password updated successfully.';

                if (!Auth::attempt([
                    'mobile' => $user_data->mobile,
                    'password' => $new_password
                ])) {
                    throw new Exception('Invalid Credentials', 1);
                }
                $user = $this->currentuser();
                $this->data = $this->userCollection($user);

                $this->response_json['setting_info'] = $this->getSettingData();
                if($user->user_type == 2){
                    $user_data = Contact::where('user_id', $user->id)->first();
                }else if($user->user_type == 3){
                    $user_data = Salesmans::where('user_id', $user->id)->first();
                }

                $customer_status = $user_data->is_active ?? 0;
                $user_status = $user->is_active ?? 0;
                $this->response_json['is_active'] = (strtolower($user_status) == 'yes' && strtolower($customer_status) == 'yes') ? 1 : 0;
            }
            else
            {
                $this->response_json['message'] = 'Please verify your mobile before proceeding.';
                return $this->responseError();
            }

            
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    public function generateRandomCode()
    {
        // $rand = substr(md5(microtime()),rand(0,26),6);
        $rand = mt_rand(100000, 999999);
        return $rand;
    }

    function send_sms($message, $mobileno)
    {
        $name = "BACIMO";
        $mobileNumber = $mobileno;
        $email = "l.d.dobaria@gmail.com";
        $senderId = "BACIMO";
        $routeId = "8";
        $authKey = "8127df56ed2915e1be154e51c14547cf";
        $serverUrl = "msg.msgclub.net";
        $this->sendsmsPOST($mobileNumber, $senderId, $routeId, $message, $serverUrl, $authKey);
    }

    function sendsmsPOST($mobileNumber, $senderId, $routeId, $message, $serverUrl, $authKey)
    {
        //Prepare you post parameters
        $postData = array(
            'mobileNumbers' => $mobileNumber,
            'smsContent' => $message,
            'senderId' => $senderId,
            'routeId' => $routeId,
            "smsContentType" => 'english'
        );

        $data_json = json_encode($postData);

        $url = "http://" . $serverUrl . "/rest/services/sendSMS/sendGroupSms?AUTH_KEY=" . $authKey;
        //$url="http://".$serverUrl."/rest/services/sendSMS/sendCustomGroupSms?AUTH_KEY=".$authKey;

        // init the resource
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json', 'Content-Length: ' . strlen($data_json)),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        //get response
        $output = curl_exec($ch);


        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        // dd($output);
        return $output;
    }

    public function checkToken(Request $request)
    {
        try{
            $token = $request->bearerToken();
            $this->data = $token;
            return $this->responseSuccessWithoutObject();
        }catch(Exception $e){
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
    }

    public function getSettingData()
    {

        $settings = Setting::whereIn('name', ['android_version','ios_version'])->get()->toArray();

        $android_version = array_reduce(array_filter($settings, function($val, $key){
            return ($val['name'] == 'android_version');
        },ARRAY_FILTER_USE_BOTH), 'array_merge', array());

        $ios_version = array_reduce(array_filter($settings, function($val, $key){
            return ($val['name'] == 'ios_version');
        },ARRAY_FILTER_USE_BOTH), 'array_merge', array());

        return [
            'android_version' => (!empty($android_version)) ? (int)$android_version['value'] : 0,
            'ios_version' => (!empty($ios_version)) ? (int)$ios_version['value'] : 0,
        ];
    }

    // logout user
    public function logout(Request $request)
    {
        $id = $this->currentuser()->id;
        $user = User::findorfail($id);
        if ($user) {
            // $user->firebase_token = NULL;
            $user->save();

            $employee_id = $user->emp_id;
            if($employee_id > 0){
                Employee::where('id', $employee_id)->update(['device_token'=> NULL]);
            }
        }
        $this->currentuser()->token()->revoke();
        $this->response_json['message'] = 'logged out successfully';
        return $this->responseSuccess();
    }

    public function getAppInfoData(Request $request)
    {
        $version = $request->version ?? '';
        $userId = $request->user_id ?? '';

        $settings = new Setting;
        $tempVer = DB::table('sessions')->where('user_id', $userId)->where('platform','App')->first();
        
        if($tempVer){
            if(strtolower(json_decode($tempVer->payload)->platform) == 'android'){
                $versionData = $settings->where('name', 'android_version')->first();            
            }else{
                $versionData = $settings->where('name', 'ios_version')->first();       
            }
        }else{
            $versionData = $settings->where('name', 'android_version')->first();            
        }
        
        $versionValue = $versionData->value ?? 0;

        $userData = User::where('id',$userId)->first();
        $sesionData = DB::table('sessions')->where('user_id', $userId)->where('platform','App')->count();
        if($sesionData == 0){
            $sesionCount = true;
            $sesionMsg = "Your account Logout from Admin Side";
        }else{
            $sesionCount = false;
            $sesionMsg = "";
        }
        $this->data['is_session_expire'] = $sesionCount;
        $this->data['is_session_msg'] = $sesionMsg;

        if($versionValue <= $version)
        {
            $this->data['is_update_available'] = false;
        }else{
            $this->data['is_update_available'] = true;
            $this->response_json['message'] = 'Time for an update! Get the latest version now!';
            // $this->data['is_update_available'] = false;
        }
        $settings = new Setting;
        $android_version = $settings->where('name', 'android_version')->first();
        $ios_version = $settings->where('name', 'ios_version')->first();

        $this->data['android_version'] = (int) $android_version->value;
        $this->data['ios_version'] = (int) $ios_version->value;
                
        return $this->responseSuccessWithoutObject();

    }


    // change password of login user profile
    public function changePassword(Request $request)
    {
        // dd(12);
        DB::beginTransaction();
        try {
            $login_user = $this->currentuser();

            $validator = Validator::make($request->all(), [
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6|same:confirm_password',
                'confirm_password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                $this->response_json['message'] = $validator->messages()->first();
                return $this->responseError();
            }

            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $current_password = $login_user->password;

            $user = User::findOrFail($login_user->id);

            if (Hash::check($old_password, $current_password)) {
                $user->update(['password' => Hash::make($new_password)]);

                $this->response_json['message'] = 'Password Updated Successfully.';
            } else {
                $this->response_json['message'] = 'Please enter correct old password.';
                return $this->responseError();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            info($e);

            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }
}
