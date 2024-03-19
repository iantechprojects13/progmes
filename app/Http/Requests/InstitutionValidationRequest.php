<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionValidationRequest extends FormRequest
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
            'id' => 'nullable',
            'code' => 'required',
            'name' => 'required',
            'programs' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The institutional code field is required.',
            'name.required' => 'The institution name field is required.',
        ];
    }

}
