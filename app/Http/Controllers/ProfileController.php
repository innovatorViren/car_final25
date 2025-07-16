<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\{User};
use Auth;
use Sentinel;
use Clean\Helper\Facades\AppHelper;
use Illuminate\Support\Facades\File;
use DB;
use Carbon\CarbonInterface;
use Carbon\Carbon;

class ProfileController extends Controller
{

	public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->common = new CommonController();
    
        $this->common = new CommonController();
        $this->title = trans("profile.profile");
        view()->share('title', $this->title);
    }

    public function editProfile(Request $request) {
      
        $user = Sentinel::getUser();
        $role = $user->roles->first();

        $segment = $request->segment(2);
        
        return view('profile.update-profile', compact('user', 'segment','role'));
    }

    public function updateProfile(Request $request) {
        $user = Sentinel::getUser();

        $user->first_name = $request->get('first_name');
        $user->email = $request->get('email');
        
        if ($request->hasFile('image')) {
            $storepath = '/uploads/users/'. $user->id.'/';
            
            $file['image'] = AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath($storepath));

            $request->file('image')->move(AppHelper::getImagePath($storepath), $file['image']);

            $userData['image'] = $file['image'];
            $userData['image_path'] = $storepath.$file['image'];

        	$unlink = $user->image_path;
            if ($unlink != null || $unlink != '') {
            	$unlink = base_path('public'.$user->image_path);
            	if (File::exists($unlink)) {
                	unlink($unlink);
                }
            }

            $user_data = User::findOrFail($user->id);
            $user_data->update($userData);        
        }
        $user->save();

        return redirect()->route('profile.edit')->with('success', __('common.update_success'));
    }

    public function changePassword(Request $request) {
        $user = Sentinel::getUser();
        $role = $user->roles->first();
        $segment = $request->segment(2);

        return view('profile.update-password', compact('user', 'segment','role'));
    }

    public function updatePassword(Request $request) {

        $user = Sentinel::getUser();
        $user_id = $user->id;
        $is_forcefully = $request->is_forcefully ?? 'No';
        $password_resets = DB::table('password_reset_new')->select(['last_password'])->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->pluck('last_password')->toArray();
        $current_password = [];
        if($is_forcefully != 'Yes'){
            $current_password = [
                'required',                
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Your password was not updated, since the provided current password does not match.');
                    }
                }
            ];
        }
        $validated = $request->validate([
            'current_password' => $current_password,
            'password' => [
                'required',                
                function ($attribute, $value, $fail) use ($password_resets) {
                    if(in_array($value, $password_resets)){
                        $fail('Entered password should be different then your last three (3) passwords. Please try with different password.');
                    }
                }
            ],
            
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        $currentIP = \Request::ip();
        DB::table('password_reset_new')->insert(['user_id'=>$user_id,'last_password'=>$request->password,'last_date'=>now(),'ip'=>$currentIP,'created_at'=>now()]);
        if($is_forcefully == 'Yes'){
            $this->loginLog('Logout','');
            Sentinel::logout($user, true);
            return redirect()->route('auth.login.form')->with('success', __('profile.password_change_successfully'));
        }else{
            return back()->with('success', __('common.update_success'));
        }
        
    }
    public function loginLog($login_status='Active',$login_message=''){
        $user = Sentinel::getUser();
        $ip_address = \Request::ip();
        $request = Request();
        /*Get user ip address details with geoplugin.net*/
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip_address;
        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
        $user_id = '';
        $full_name = '';        
        if($user){
            $full_name = $user->first_name . " " . $user->last_name;
            $user_id = $user->id;
            $email_id = $user->email;
        }else{
            $email_id = $request->email ?? '';    
        }
        $browser_name = getBrowser();
        $login_session = '';
        if($login_status == 'Logout' && $user_id > 0){
            $last_login = DB::table('login_log')
               ->whereJsonContains('log_data->login_status', 'Success')
               ->whereJsonContains('log_data->ip_address', $ip_address)
               ->whereJsonContains('log_data->browser_name', $browser_name)
            ->where('user_id',$user_id)->orderBy('id','desc')
            ->first();
            if($last_login){
                $last_login_time = $last_login->created_at;              
                $options = [
                  'join' => ', ',
                  'parts' => 4,
                  'syntax' => CarbonInterface::DIFF_ABSOLUTE,
                ];
                $login_session = custom_date_format(Carbon::now()->diffForHumans($last_login_time, $options),'H:m:s');
            }
        }
        $session_status = '';
        if($login_status != 'Reject'){
            $session_status = $login_status;
        }
        $loginData = [
            'user_id'=>$user_id,
            'log_data'=> json_encode([
                'user_id' => $user_id,
                'user_name' => $full_name,
                'email_id' => $email_id,
                'ip_address' => $ip_address,
                'device_name' => php_uname('n'),
                'country' => $addrDetailsArr['geoplugin_countryName'] ?? '',
                'state' => $addrDetailsArr['geoplugin_regionName'] ?? '',
                'city' => $addrDetailsArr['geoplugin_city'] ?? '',
                'operating_system' => getOS(),
                'browser_name' => $browser_name,
                'date_time' => Date('Y-m-d H:i:s'),
                'login_session' => $login_session,
                'login_status' => ($login_status == 'Reject') ? 'Reject' : 'Success',
                'login_message' => $login_message,
                'session_status' => $session_status,
            ]),
            'created_at' => Date('Y-m-d H:i:s'),
        ];
        DB::table('login_log')->insert($loginData);
    }
    public function forcefullyChangePassword(Request $request) {
        $user = Sentinel::getUser();
        $this->data['title']= __('profile.change_password');
        $this->data['user']= $user;
        return view('profile.forcefully-change-password', $this->data);
    }
}

