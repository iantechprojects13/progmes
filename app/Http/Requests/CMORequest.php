<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CMORequest extends FormRequest
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
            'discipline' => 'required',
            'program' => 'required',
            'number' => 'required|integer|between:1,100',
            'series' => 'required|integer|between:2000,2100',
            'version' => 'nullable|integer|between:1,100',
        ];
    }

    public function messages(): array
    {
        return [
            'discipline.required' => 'The discipline field is required.',
            'program.required' => 'The program field is required.',
            'number.required' => 'The CMO no. field is required.',
            'number.integer' => 'The number field must be an integer.',
            'series.required' => 'The series field is required.',
            'series.integer' => 'The series field must be an integer.',
            'series.between' => 'The series field must be in the range of 2000 to 2100.',
            'version.integer' => 'The version field must be an integer.',
            'version.between' => 'The version field must be in the range of 1 to 100.',
        ];
    }
}
