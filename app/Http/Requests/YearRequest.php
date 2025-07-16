<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class YearRequest extends FormRequest
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
        return [
            'yearname' => [
                'required',
                Rule::unique('years')->whereNull('deleted_at')->ignore($id),
            ],
            'is_default' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',

        ];

    }
}
