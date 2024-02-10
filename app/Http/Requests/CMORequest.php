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
            'number' => 'required',
            'series' => 'required',
            'version' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'The number field is required.',
            'number.numeric' => 'Must be a number',
        ];
    }
}
