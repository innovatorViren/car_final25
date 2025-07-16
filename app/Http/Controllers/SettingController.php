<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Setting,IpWhitelist,YearWiseScope};
use App\Http\Requests\SettingRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use DB;

class SettingController extends Controller
{
    private $path, $common, $is_public = true, $response_json = [], $data = [];
    public function __construct()
    {
        parent::__construct();
        $this->middleware('sentinel.auth');
        $this->common = new CommonController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::get();
        foreach ($settings as $key => $setting) {
            if ($setting->name == "allow_ip_for_tv_display") {
                $setting->value = json_decode($setting->value);
            }
            $settingsArray[$setting->name] = $setting->value;
        }
        $country = $settings->where('name', 'country')->first();
        $state = $settings->where('name', 'state')->first();
        $country_id = $country ? $country->value : 0;
        $state_id = $state ? $state->value : 0;
        $this->data['countries'] =  $this->common->getCountries();
        $this->data['states']    =  $this->common->getStates($country_id);
        $this->data['cities']    =  $this->common->getCities($state_id);
        $this->data['settings']  = $settingsArray ?? '';
        return view('settings.setting', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        $input = $request->except('_token');
        $company_logo = '';
        $company_fevicon = '';
        $group_name = $input['group'] ?? '';
        
        if (count($input) > 0) {
            foreach ($input as $field => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                if ($field == 'country_id') {
                    $field = 'country';
                }
                if ($field == 'state_id') {
                    $field = 'state';
                }
                if ($field == 'city_id') {
                    $field = 'city';
                }
                $data = [
                    'name' => $field,
                    'title' => ucwords(str_replace("_", " ", $field)),
                    'value' => $value,
                    'group' => $input['group'] ?? '',
                ];

                Setting::updateOrCreate(['name' => $field], $data);

                if ($field == 'company_logo') {
                    $this->uploadLogoImage($request);
                }

                if ($field == 'company_favicon') {
                    $this->uploadFaviconImage($request);
                }

                if ($field == 'company_brochure') {
                    $this->uploadPDF($request);
                }

            }
        }
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $fileName = time() . '_' . rand(0, 500) . '_' . $file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);
            $file->storeAs('public', $fileName);
            $company_logo = $fileName;
        }
        if ($request->hasFile('company_favicon')) {
            $file = $request->file('company_favicon');
            $fileName1 = time() . '_' . rand(0, 500) . '_' . $file->getClientOriginalName();
            $fileName1 = str_replace(' ', '_', $fileName1);
            $file->storeAs('public', $fileName1);
            $company_fevicon = $fileName1;
        }

        return back()->with('success', __('settings.update_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Setting::find($id);
        $this->data['setting'] = $setting;

        return response()->json(['html' => view('settings.form', $this->data)->render()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadLogoImage($request)
    {
        if ($request->hasFile('company_logo')) {
            $store_path = '/uploads/Setting/Logo/';
            $stored_path = 'uploads/Setting/Logo/';
            $logo_name = $this->getUniqueFilename($request->file('company_logo'),  $this->getImagePath($store_path));
            $request->file('company_logo')->move($this->getImagePath($store_path), $logo_name);
            $settings['value'] = $stored_path . $logo_name;

            Setting::where('name', 'company_logo')->update($settings);
        }
    }

    public function uploadFaviconImage($request)
    {
        if ($request->hasFile('company_favicon')) {
            $store_path = '/uploads/Setting/Favicon/';
            $store_paths = 'uploads/Setting/Favicon/';
            $favicon_name =  $this->getUniqueFilename($request->file('company_favicon'),  $this->getImagePath($store_path));
            $request->file('company_favicon')->move($this->getImagePath($store_path), $favicon_name);
            $settings['value'] = $store_paths . $favicon_name;

            Setting::where('name', 'company_favicon')->update($settings);
        }
    }
    public function getUniqueFilename($fileInput, $destination)
    {
        $filename = $fileInput->getClientOriginalName();
        $i = 0;
        $path_parts = pathinfo($filename);
        $path_parts['filename'] = Str::slug($path_parts['filename'], '-');
        $filename = $path_parts['filename'];
        while (File::exists($destination . '/' . $filename . '.' . $path_parts['extension'])) {
            $filename = $path_parts['filename'] . '-' . $i;
            $i++;
        }
        return time() . '_' . $filename . '.' . $path_parts['extension'];
    }
    public function getImagePath($file_name = '')
    {
        if ($this->is_public) {
            $path = public_path($this->path);
        } else {
            $path = storage_path($this->path);
        }

        if (File::isDirectory($path) === false) {
            File::makeDirectory($path, 0777, true);
            $this->createIndexHtmlFile($path);
        }
        return $path . $file_name;
    }
    public function path($path, $is_public = true)
    {
        $this->path = $path;
        $this->is_public = $is_public;
        return $this;
    }
    private function createIndexHtmlFile($path)
    {
        $path = Str::finish($path, '/');
        if (File::isFile("{$path}index.html") === false) {
            File::put("{$path}index.html", '<html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }
    }
    public function uploadPDF($request)
    {
        if ($request->hasFile('company_brochure')) {
            $stored_path = 'uploads/Setting/Brochure/';
            $logo_name = $this->getUniqueFilename($request->file('company_brochure'),  $this->getImagePath('/'.$stored_path));
            $request->file('company_brochure')->move($this->getImagePath('/'.$stored_path), $logo_name);
            $settings['value'] = $stored_path . $logo_name;
            Setting::where('name', 'company_brochure')->update($settings);
        }
    }
}
