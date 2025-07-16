<?php

use App\Models\{
    Employee,
    Series,
    Setting,
    Year,
    User,
};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

if (!function_exists('date_format_list')) {

    function date_format_list()
    {
        return [
            'DD-MM-YYYY' => 'd-m-Y',
            'YYYY-MM-DD' => 'YY-m-d',
            'MM-DD-YYYY' => 'm-d-Y',
        ];
    }
}

if (!function_exists('dateformat')) {

    function dateformat($date, $format = null)
    {

        // $setting = Cache::remember('default_setting', 600, function () {
        //     return \DB::table('settings')->where('fieldName','date_format')->first();
        // });
        $setting = DB::table('settings')->where('fieldName', 'date_format')->first();
        //dd($setting);
        $date =  Carbon::parse($date)->format($format ?? $setting->fieldValue);

        return $date;
    }
}