<?php

namespace App\Http\Middleware;

use Closure;
use Centaur\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as FacadeRequest;
use Carbon\Carbon;

class IPAddresses
{
    protected $authManager;
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ( $user ){
            $user_id = $user->id;
            $is_ip_base = $user->is_ip_base;
            $allow_access_from_other_network = $user->allow_access_from_other_network ?? 'No';
            $password_resets = DB::table('password_reset_new')->where('user_id', $user_id)->orderBy('id','desc')->first();
            $password_reset_days = getSetting('password_reset_days');
            if($password_resets){
                $last_date = Carbon::parse($password_resets->last_date)->addDays($password_reset_days + 1);
            }else{
                $password_reset_date = getSetting('password_reset_date') ?? Carbon::now()->format('Y-m-d');
                if($password_reset_date){
                    $last_date = Carbon::parse($password_reset_date)->addDays($password_reset_days + 1);
                }else{
                    $last_date = Carbon::now()->addDays($password_reset_days);
                }
            }
            $now = Carbon::now();
            if ($allow_access_from_other_network == 'No') {
                $currentIP = \Request::ip();
                // $allowIPArr = DB::table('ip_whitelists')->whereNull('deleted_at')->get()->pluck('ip_address')->toArray();
                // if (!in_array($currentIP, $allowIPArr) && $currentIP != '127.0.0.1' && Session::get('back_login_id') <= 0) {
                //     Session::put('error',trans('Access denied due to user not allowed from this network.'));
                //     $this->authManager->logout(null, null);
                // }else if($now->isAfter($last_date) && !in_array(\Request::route()->getName(), ['profile.update-password','forcefully-change-password']) && Session::get('back_login_id') <= 0){
                //     return Redirect()->route('forcefully-change-password')->with('warning', __('profile.force_reset_password'));
                // }
            }else if($now->isAfter($last_date) && !in_array(\Request::route()->getName(), ['profile.update-password','forcefully-change-password']) && Session::get('back_login_id') <= 0){
                return Redirect()->route('forcefully-change-password')->with('warning', __('profile.force_reset_password'));
            }
            /*if ($is_ip_base == 'Yes') {
                $currentIP = \Request::ip();
                $allowIPArr = DB::table('user_ips')->where('user_id', $user_id)->whereNull('deleted_at')->get()->pluck('login_ip')->toArray();
                if (!in_array($currentIP, $allowIPArr)) {
                    Session::put('error',trans('Access denied due to user not allowed from this network.'));
                    $this->authManager->logout(null, null);
                }
            }*/
        }
        return $next($request);
    }
}
