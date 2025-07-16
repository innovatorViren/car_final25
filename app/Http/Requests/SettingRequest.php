<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->get('group') == "company") {
            $validation = [
                'project_title' => 'required',
                'company_name' => 'required',
                'company_address' => 'required',
                'company_email' => 'required',
                'company_mobile' => 'required',
                'gst_no' => 'required',
                'pan_no' => 'required',
                'country_id' => 'required',
                'state_id' => 'required',
                'city_id' => 'required',
                'pincode' => 'required'
            ];
        } elseif ($this->get('group') == "barcode") {
            $validation = [
                'inward_width' => 'required|numeric',
                'inward_height' => 'required|numeric',
                'production_width' => 'required|numeric',
                'production_height' => 'required|numeric',

            ];
        } elseif ($this->get('group') == "smtp_config") {
            $validation = [
                'driver' => 'required',
                'display_name' => 'required',
                'host' => 'required',
                'user_name' => 'required',
                'encryption' => 'required',
                'port' => 'required',
                'password' => 'required',
            ];
        } elseif ($this->get('group') == "tax") {
            $validation = [
                'packaging_freight' => 'required',
                'insurance' => 'required',
            ];
        }else{
            $validation = [];
        }
        return $validation;
    }
}
