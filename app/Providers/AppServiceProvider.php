<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Setting,Year};
use View;
use Session;
use Cache;
use Config;
use Illuminate\Support\Facades\Schema;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Validator::extend('validate_duplicate', function ($attribute, $value, $parameters, $validator){
        //     $fieldName = substr($attribute, strrpos($attribute, ".") + 1);
        //     $count = 0;
        //     $items = [];
        //     $data = $validator->getData();
        //     if (isset($data['items'])) {
        //         $items = $data['items'];
        //     }
        //     if (count($parameters) && $parameters[0] == "pitems") {
        //         $items = $validator->getData()['pitems'];
        //     }
        //     if (empty($items)) {
        //         $items = $validator->getData()['ositems'];
        //     }
        //     if (count($parameters) && $parameters[0] == "productsArr") {
        //         $items = $validator->getData()['productsArr'];
        //     }
        //     if (!empty($items)) {
        //         foreach ($items as $key => $item) {
        //             if (isset($item[$fieldName]) && strcasecmp($item[$fieldName], $value) == 0) {
        //                 ++$count;
        //             }
        //         }
        //     }
        //     if ($count >= 2) {
        //         return false;
        //     } else {
        //         return true;
        //     }
        // });
        if (Schema::hasTable('settings')) {
            $setting = Setting::first();
            $setting_project_title = Setting::where('name', 'project_title')->first();
            $project_title = $setting_project_title->value ?? '';
            $setting_logo = Setting::where('name', 'company_logo')->first();
            $logo = $setting_logo->value ?? '';
            $companylogo = Setting::pluck('value', 'name')->toArray();
            view()->share('companylogo',$companylogo);
            $setting_favicon = Setting::where('name', 'company_favicon')->first();
            $favicon = $setting_favicon->value ?? '';
            view()->share('project_title', $project_title);
            view()->share('setting', $setting);
            view()->share('logo', $logo);
            view()->share('favicon', $favicon);
            $cached_settings = Cache::remember('settings', 3600, function () {
                return Setting::pluck('value', 'name')->toArray();
            });
            Config::set('srtpl.settings', $cached_settings);

            $settingTag = Setting::where('name','tag_line')->first();
            view()->share('settingTag', $settingTag);
        }

        if (Schema::hasTable('years')) {
            $year = Year::get();
            view()->share('header_year', $year);
        }

    }
}
