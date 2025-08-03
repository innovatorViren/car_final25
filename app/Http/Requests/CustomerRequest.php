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
            
            'address_line' => [
                'required'
            ],
            'is_create_user' => [
                'nullable'
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
            'address_line1.required' => 'Address Line1 is required',
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
        ];
    }
}
