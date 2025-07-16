<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $id  = $this->get('id');
        $validation = [
            'company_name' => [
                'required',
                Rule::unique('customers')->ignore($id)
            ],
            'person_name' => [
                'required'
            ],
            // 'primary_managed_by' => ['required'],
            'pan_no' => [
                'nullable',
                'regex:/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/',
                Rule::unique('customers')->ignore($id)
            ],
            'gst_type' => ['required'],
            'gst_no' => [
                'required_if:gst_type,Registered,Composition,Special Economic Zones',
                'nullable',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('customers')->ignore($id)
            ],
            'address_line1' => [
                'required'
            ],
            'is_create_user' => [
                'nullable'
            ],
            'country_id' => [
                'required',
                Rule::exists('countries', 'id')->where(function ($query) {
                    $query->where('is_active', "Yes");
                }),
            ],
            'state_id' => [
                'required',
                Rule::exists('states', 'id')->where(function ($query) {
                    $query->where('is_active', "Yes");
                })
            ],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')->where(function ($query) {
                    $query->where('is_active', "Yes");
                }),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('customers')->ignore($id),
                function ($attribute, $value, $fail) {
                    if ($this->get('id') == null) {
                        if ($this->is_create_user == "1") {
                            $user = User::where('email', $value)->first();
                            if ($user) {
                                $fail('You cannot create user with this email. This email is already exists in user table. Please use another email.');
                            }
                        }
                    }
                }
            ],
            'pincode' => [
                'required',
                'numeric',
                'digits:6'
            ],
            'mobile' => [
                'required',
                'numeric',
                'digits:10'
            ],
            'fssai_no' => [
                'nullable',
                'regex:/^([0-9]){14}$/',
                Rule::unique('customers')->ignore($id)
            ],
        ];

        if ($id) {
            $validation['password'] = 'nullable|confirmed|min:8|regex:/^(?=.*?[A-Za-z])(?=.*?[0-9])(?=.*[$!@#$%^_&*!?)(,]{1,}).{6,}$/';
            $validation['password_confirmation'] = 'nullable|min:8';
        } else {
            $validation['password'] = 'required|confirmed|min:8|regex:/^(?=.*?[A-Za-z])(?=.*?[0-9])(?=.*[$!@#$%^_&*!?)(,]{1,}).{6,}$/';
            $validation['password_confirmation'] = 'required|min:8';
        }

        return $validation;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_name.required' => 'Company Name is required',
            'company_name.unique' => 'Company Name is already exists',
            'person_name.required' => 'Person Name is required',
            // 'primary_managed_by.required' => 'Primary Managed By is required',
            'pan_no.regex' => 'PAN No is invalid',
            'pan_no.unique' => 'PAN No is already exists',
            'gst_type.required' => 'GST Type is required',
            'gst_no.required_if' => 'GST No is required',
            'gst_no.regex' => 'GST No is invalid',
            'gst_no.unique' => 'GST No is already exists',
            'address_line1.required' => 'Address Line1 is required',
            'country_id.required' => 'Country is required',
            'country_id.exists' => 'Country is invalid',
            'state_id.required' => 'State is required',
            'state_id.exists' => 'State is invalid',
            'city_id.required' => 'City is required',
            'city_id.exists' => 'City is invalid',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email is already exists',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one letter, one number and one special character',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters',
            'pincode.required' => 'Pincode is required',
            'pincode.numeric' => 'Pincode is invalid',
            'pincode.digits' => 'Pincode must be 6 digits',
            'mobile.required' => 'Mobile is required',
            'mobile.numeric' => 'Mobile is invalid',
            'mobile.digits' => 'Mobile must be 10 digits',
            'fssai_no.regex' => 'FSSAI No is invalid',
            'fssai_no.unique' => 'FSSAI No is already exists',
        ];
    }
}
