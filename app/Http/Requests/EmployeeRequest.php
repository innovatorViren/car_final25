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
        $parent_employee_id = '';
        if ($this->get('parentId')) {
            $id  = $this->get('parentId');
            $parent_employee_id = '';
        } else {
            $id  = $this->get('id');
            $parent_employee_id  = $this->get('parent_employee_id');
        }
        $parent_id = [];
        if ($id > 0) {
            $get_parent =  DB::select("WITH RECURSIVE tree (parent_employee_id) AS ( SELECT parent_employee_id FROM employees WHERE id = $id UNION ALL SELECT lpc.parent_employee_id FROM employees lpc JOIN tree t ON t.parent_employee_id = lpc.id ) SELECT parent_employee_id FROM tree");
            $parent_id = array_filter(array_column($get_parent, 'parent_employee_id'));
        }
        // dd($this->get('id') == null || $this->get('parentId') == null);
        $validation = [
            'first_name' => 'required',
            'last_name' => 'required',
            'person_name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->whereNull('deleted_at')
                    ->when($id, function ($qry) use ($id) {
                        $qry->where(function ($query) use ($id) {
                            return $query->where('id', '!=', $id);
                        });
                    })
                    ->when(!empty($parent_id), function ($query) use ($parent_id) {
                        return $query->whereNOTIN('id', $parent_id);
                    }),

                function ($attribute, $value, $fail) {
                    if($this->get('id') == null && $this->get('parentId') == null){
                        $user = User::where('email', $value)->first();
                        // dd($user);
                        if ($user) {
                            $fail('You cannot create user with this email. This email is already exists in user table. Please use another email.');
                        }
                    }
                }
            ],
            'gender' => 'required',
            'birth_date' => 'required',
            'age' => 'required',
            'marital_status' => 'required',
            'total_experience' => 'required',
            'join_date' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',

            'permanent_address' => 'required',
            'present_address' => 'required',
            'permanent_state' => 'required',
            'present_state' => 'required',
            'permanent_city' => 'required',
            'present_city' => 'required',
            'mobile1' => 'required',
            'aadhar_card_no' => [
                'required',
                Rule::unique('employee_documents')->whereNull('deleted_at')

                    ->when($id, function ($qry) use ($id) {
                        $qry->where(function ($query) use ($id) {
                            return $query->where('employee_id', '!=', $id);
                        });
                    })
                    ->when(!empty($parent_id), function ($query) use ($parent_id) {
                        return $query->whereNOTIN('employee_id', $parent_id);
                    })
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
