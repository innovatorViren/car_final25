<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_id' => 'required',
            'name' => "required|unique:states,name," . request()->route('state').',id,deleted_at,NULL',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'country_id.required' => trans("state.validation_country_required"),
            'state.required' => trans("state.validation_state_required"),
            'state.unique' => trans("state.validation_state_unique"),
            'gst_code.required' => trans("state.validation_gst_code_required"),
            'gst_code.numeric' => trans("state.validation_gst_code_number"),
        ];
    }
}
