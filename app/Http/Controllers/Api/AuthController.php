<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use App\Models\Setting;
use App\Models\{Employee,Role,sessions,Customer};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use DB;
use URL;
use Sentinel;
use Carbon\Carbon;
use Centaur\AuthManager;
use Reminder;

class AuthController extends ApiController
{

    protected $authManager;
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
        $this->common = new CommonController();
        $this->userRepository = app()->make('sentinel.users');

    }


    public function login(Request $request)
    {   
        try {
            $loginData = Validator::make($request->all(), [
                'mobile' => 'required',
                'password' => 'required|min:6',
                // 'device_token' => 'required'
            ]);
            
            if ($loginData->fails()) {
                throw new Exception($loginData->messages()->first(), 1);
            }

            $validMobile = DB::table('users')->where('mobile',$request->mobile)->count();
            if($validMobile > 0){

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

            $sessions = sessions::get();
            if(!empty($sessions)){
                foreach ($sessions as $key => $session) {
                    $user_id = $session->user_id ?? 0;
                    if ($user_id == $user->id && $session->token != Session::get('_token')) {
                        // DB::table('oauth_access_tokens')->where('user_id',$user->id)->delete();
                        // $session->delete();
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

            $is_app_login = 1;
            if ($user) 
            {
                $user->is_app_login = '1'; 
                $user->fcm_token = $request->fcm_token;
                $user->platform = $request->Platform;
                $user->save();
            }
                

            $this->data = $this->userCollection($user);

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

            $this->response_json['message'] = "Success";
        } catch (Exception $e) {
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }

        return $this->responseSuccess();
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            
                $messages = [
                  'first_name.required' => 'First name is required!',
                  'last_name.required' => 'Last name is required!',
                  'mobile.required' => 'Mobile is required!',
                  'password.required' => 'Password is required!',
                  
                ];
                $validatedData = Validator::make($request->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'mobile' => 'required',
                    'password' => 'required',
                ],$messages);
            

            if(!$this->checkDuplicateMobile($request->mobile))
            {
                // return re
                $this->response_json['status'] = 0;
                $this->response_json['message'] = 'Mobile Already Exit';
                return $this->responseError();
            }

            if ($validatedData->fails()) {
                throw new Exception($validatedData->messages()->first(), 1);
            }

            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            $dataArray = [
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => str_replace(' ','',$request->email),
                'mobile' => $request->mobile,
                'otp' => $otp,
                'platform' => 'App',
            ];
            $customer = Customer::create($dataArray);
            $customer_id = $customer->id;

            //otp veryfyy 

            
            // $updateCusData = [
            //         'expired_at' => Carbon::now()->addMinutes(10)
            //     ];
            // DB::table('customers')->where('id', $customer_id)->update($updateCusData);

            // $to_mobile = $request->mobile ?? '';
            // $this->common->send_whatsapp_otp($otp, $to_mobile);




            $role_id = Role::where('slug', 'customer')->first()->id ?? '';

            $userArray = [
                'emp_type' => 'customer',
                'customer_id' => $customer_id ?? null,        
                'first_name' => $request->get('first_name', null),
                'middle_name' => $request->middle_name,
                'last_name' => $request->get('last_name', null),
                'permissions' => json_encode(['customer.view' => true]),
                'email' => str_replace(' ','',$request->email),
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile, 
                'is_active' => 'Yes',
                'roles_id' => $role_id,
            ];
            $credentials = [
                'first_name' => $request->get('first_name', null),
                'last_name' => $request->get('last_name', null),
                'email' => str_replace(' ','',$request->email),
                'password' => $request->get('password'),
            ];
            $activate = true;
            $result = $this->authManager->register($credentials,$activate);
            $user_id = $result->user->id;

            $user = User::findOrFail($user_id);
            $user->update($userArray);
            
            $result->user->roles()->sync(array($role_id));

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            info($e);

            $this->response_json['status'] = 0;
            $this->response_json['message'] = $e->getMessage();
            return $this->responseError();
        }
        $this->response_json['status'] = 1;
        $this->response_json['customer_id'] = $customer_id;
        $this->response_json['otp'] = $otp;
        $this->response_json['message'] = 'Customer and User Created SuccessFully';
        return response()->json($this->response_json, 200);
    }

    public function checkDuplicateMobile($mobile)
    {
        $mobile = str_replace(' ','',$mobile);
        $customer = Customer::where('mobile', $mobile)->first();
        $user = User::where('mobile', $mobile)->first();


        if ($customer || $user) {
            return false;
        } else {
            return true;
        }
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

    // logout user
    public function logout(Request $request)
    {

        $id = $this->currentuser()->id;
        $user = User::findorfail($id);
        $bearerToken = $request->bearerToken();
        if ($user) 
        {
            $user->is_app_login = '0';
            $user->platform = Null; 
            $user->fcm_token = Null; 
            $user->save();
        }
        $this->currentuser()->token()->revoke();
        

        DB::table('sessions')->where('platform','App')->where('token',$bearerToken)->delete();
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

    public function forgotPassword(Request $request)
    {
        $result = $this->validate($request, [
            'email' => 'required|email|max:255'
        ]);
        $user = Sentinel::findUserByCredentials(['email' => $result]);

        if ($user) {

            $reminder = Reminder::create($user);
            $code = $this->generateRandumCodeEmail();
            $email = $user->email;
            DB::table('forgot_password_otps')->where('email',$email)->delete();
            DB::table('forgot_password_otps')->insert([
                    'email' => $email,
                    'otp' => $code,  
                    'platform' => 'app',  
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            $this->sendPasswordMail($email, $code);

            $message = 'Instructions for changing your password will be sent to your email address if it is associated with a valid account.';
            return response()->json(['message' => $message,'email' => $email,'id' => $user->id,'status' => 1], 200);
            // Mail::to($email)->queue(new CentaurPasswordReset($code));
        } else {
            $this->response_json['message'] = 'The value is not a valid email address';
            return $this->responseError();
        }





        // $this->response_json['code'] = $code;
        // $this->response_json['message'] =  $message;
        // return response()->json($this->response_json, 200);

    }

    public function sendPasswordMail($email, $code)
    {

            $dateTime = Carbon::now()->addMinutes(15)->format('M d, Y H:i A');
            $html = '<p>This OTP is valid for 15 minutes until '.$dateTime.'.</p><br>'. '<p>Your Login OTP is: <strong>'.$code.'</strong></p><br>'. '<p>Thank you,<br><strong>Clear My Car</strong></p>';

            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', '465'))
                ->setUsername('virendrabutani@gmail.com')
                ->setPassword('qwcb fnlf cpmw adkx')
                ->setEncryption('SSl');

            $mailer    = new \Swift_Mailer($transport);
            $message   = (new \Swift_Message('Clear My Car'))
                ->setFrom('viren04041995@gmail.com', '')
                ->setTo($email)
                ->setBody($html, 'text/html');
            $mailer->send($message);
    }


    public function generateRandumCodeEmail()
    {
        $rand = mt_rand(1000, 9999);
        return $rand;
    }

    public function verifyOtpApp(Request $request)
    {

        $verifyOtp = DB::table('forgot_password_otps')->where('email',$request->email)->where('platform','app')->orderByDesc('id')->first();

        if($request->otp == $verifyOtp->otp)
        {
            $message = 'Your OTP has been successfully verified';
            return response()->json(['message' => $message,'email' => $request->email,'id' => (int)$request->id,'status' => 1], 200);
        }

            $this->response_json['message'] = 'Incorrect OTP, please try again';
            return $this->responseError();        
    }


    public function forgotChangePassword(Request $request)
    {

        DB::beginTransaction();
        try {

            $id = $request->id;

            $login_user = User::where('id', $id)->first();

            $validator = Validator::make($request->all(), [
                'new_password' => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                $this->response_json['message'] = $validator->messages()->first();
                return $this->responseError();
            }

            $new_password = $request->new_password;
            $current_password = $login_user->password;

            $user = User::findOrFail($login_user->id);

            $user->update(['password' => Hash::make($new_password)]);
            $this->response_json['message'] = 'Password Updated Successfully.';

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
