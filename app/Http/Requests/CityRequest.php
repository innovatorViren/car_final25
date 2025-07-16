<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'state_id' => 'required',
            'name' => "required|unique:cities,name," . request()->route('city') . ',id,deleted_at,NULL',
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
            'country_id.required' => trans("city.validation_country_required"),
            'state_id.required' => trans("city.validation_state_required"),
            'city.required' => trans("city.validation_city_required"),
            'city.unique' => trans("city.validation_city_unique"),
        ];
    }
}
