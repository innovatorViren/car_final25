<?php

namespace App\Http\Controllers\Auth;

use Sentinel;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Centaur\Dispatches\BaseDispatch;
use App\Models\{sessions,Location,Year,Branch};
use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;
use App\Rules\ReCaptcha;
use Carbon\CarbonInterface;

class SessionController extends Controller
{
    /** @var Centaur\AuthManager */
    protected $authManager;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $authManager)
    {
        $this->middleware('sentinel.guest', ['except' => 'getLogout']);
        $this->authManager = $authManager;
    }

    /**
     * Show the Login Form
     * @return View
     */
    public function getLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle a Login Request
     * @return Response|Redirect
     */
    public function postLogin(Request $request)
    {
        // Validate the Form Data
        $result = $this->validate(
            $request,
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => trans("module_validation.email_required"),
                'password.required' => trans("module_validation.password_required"),
            ]
        );

        // Assemble Login Credentials
        $credentials = array(
            'login' => str_replace(' ', '', $request->get('email')),
            'password' => $request->get('password'),
        );

        $remember = (bool) $request->get('remember', false);

        // $user = Sentinel::findById(1);
        // Sentinel::login($user);
        // Attempt the Login
        $result = $this->authManager->authenticate($credentials, $remember);


        $user = Sentinel::getUser();
        $validLogin = true;
        if ($user) {
            $user_id = $user->id;
            if ($user->is_active == "No") {
                $error_message = trans('Access denied due to user not activated.');
                Session::put('error', $error_message);
                $this->authManager->logout(null, null);
                $this->loginLog('Reject',$error_message);
                $route = 'auth.login.form';
                $validLogin = false;
            } 
            else 
            {
                Session::forget('error');
            }
        }
        $route = 'dashboard';
        if ($result->statusCode === 200 && $validLogin) {

            $allow_multi_login=$user->allow_multi_login;

            // if($allow_multi_login==null){
            //     $sessionCount = sessions::where('user_id',$user->id)->where('platform','Web')->count();
            //     if($sessionCount > 0){
            //         Session::flash('error',trans('This user is already logged in another device.')); 
            //         $this->authManager->logout(null, null);
            //         return redirect()->route('auth.login.form');
            //     }
            // }

            $route = 'dashboard';
            $this->authenticated($request, $user);
            DB::table('sessions')->where('id', Session::getId())->update(['user_id' => $user_id]);
            $this->loginLog('Active','Login has been successfully.');
        
            $default_year = Year::where('is_default','Yes')->first();
            if($default_year){
                Session::put('default_year',$default_year);
                $fromDate = date('y', strtotime($default_year->from_date));
                $toDate = date('y', strtotime($default_year->to_date));
                $default_year_name = $fromDate.'-'.$toDate;
            }else{
                $start_year = carbon::now()->format('Y');
                $end_year = carbon::now()->format('Y') + 1;
                $default_year_name = $start_year.'-'.$end_year;
            }
            Session::put('default_year_name',$default_year_name);
        } else {
            $this->authManager->logout(null, null);
            $route = '/';
            if($validLogin){
                $this->loginLog('Reject',$result->message ?? '');
            }
        }
        // Return the appropriate response
        $path = session()->pull('url.intended', $route);
        return $result->dispatch($path);
    }

    protected function authenticated($request, $user)
    {

        $allowMultiLogin = $user->allow_multi_login ?? '';
        Session::put('user_id', $user->id);

        if($allowMultiLogin !=1)
        {
            $sessions = sessions::get();
            if(!empty($sessions)){
                foreach ($sessions as $key => $session) {
                    $user_id = $session->user_id ?? 0;
                    if ($allowMultiLogin != '1' && $user_id == $user->id && $session->token != Session::get('_token')) {
                        $session->delete();
                    }
                }
            }
        }
    }

    /**
     * Handle a Logout Request
     * @return Response|Redirect
     */
    public function getLogout(Request $request)
    {
        // Terminate the user's current session.  Passing true as the
        // second parameter kills all of the user's active sessions.
        $this->loginLog('Logout','');
        $result = $this->authManager->logout(null, null);
        // Return the appropriate response
        sessions::where('id', session::getId())->delete();
        return $result->dispatch(route('auth.login.form'));
    }
    protected function translate($key, $message)
    {
        $key = 'centaur.' . $key;

        if (Lang::has($key)) {
            $message = trans($key);
        }

        return $message;
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
                $login_session = gmdate('H:i:s', Carbon::now()->diffInSeconds($last_login_time));
                // $login_session = custom_date_format(Carbon::now()->diffForHumans($last_login_time, $options),'H:m:s');
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
}
