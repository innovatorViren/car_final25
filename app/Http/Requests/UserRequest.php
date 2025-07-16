<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id  = $this->get('id');
        $empType = $this->get('emp_type');
        $validation =
            [
                'first_name' => 'required',
                'last_name' => 'required_if:emp_type,employee,non-employee|nullable',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'mobile' => 'required|numeric|unique:users,mobile,' . $id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        if ($id) {
            $validation['password'] = 'nullable|confirmed|min:8|regex:/^(?=.*?[A-Za-z])(?=.*?[0-9])(?=.*[$!@#$%^_&*!?)(,]{1,}).{6,}$/';
            $validation['password_confirmation'] = 'nullable|min:8';
        } else {
            $validation['password'] = 'required|confirmed|min:8|regex:/^(?=.*?[A-Za-z])(?=.*?[0-9])(?=.*[$!@#$%^_&*!?)(,]{1,}).{6,}$/';
            $validation['password_confirmation'] = 'required|min:8';
        }


        if ($empType == 'non-employee') {
            $validation['emp_id'] = 'nullable';
            $validation['customer_id'] = 'nullable';
            $validation['emp_type'] = 'required|in:non-employee';
        }
        if ($empType == 'employee') {
            $validation['emp_id'] = 'required|unique:users,emp_id,' . $id;
            $validation['customer_id'] = 'nullable';
            $validation['emp_type'] = 'required|in:employee';
        }
        if ($empType == 'customer') {
            $validation['emp_id'] = 'nullable';
            $validation['emp_type'] = 'required|in:customer';
        }

        if ($this->has('is_ip_base')) {
            $valid = true;
            foreach ($this->get('loginips') as $value) {
                if ($value['login_ip'] != '' || $value['login_ip'] != null) {
                    $valid = false;
                }
            }
            if ($valid) {
                $validation['loginips.0.login_ip'] = 'required';
            }
        }

        return $validation;
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required_if' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email is already taken',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one letter, one number and one special character',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters',
            'emp_id.required' => 'Employee ID is required',
            'emp_id.unique' => 'Employee ID is already taken',
            'customer_id.required' => 'Customer ID is required',
            'customer_id.unique' => 'Customer ID is already taken',
            'mobile.required' => 'Mobile number is required',
            'mobile.numeric' => 'Mobile number must be numeric',
            'mobile.unique' => 'Mobile number is already taken',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg',
            'image.max' => 'Image may not be greater than 2048 kilobytes',
        ];
    }
}
