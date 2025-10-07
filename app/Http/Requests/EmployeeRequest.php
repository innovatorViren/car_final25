<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email',
            ],
            'birth_date' => 'required',
            'age' => 'required',
            'aadhar_card_no' => 'required',
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

    public function messages()
    {
        return [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password does not match',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least one letter, one number and one special character',
            'password_confirmation.required' => 'Password confirmation is required',
            'password_confirmation.min' => 'Password confirmation must be at least 8 characters',
        ];
    }
}
