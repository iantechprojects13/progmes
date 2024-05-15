<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
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
            'major' => 'nullable',
        ];
    }
    

    public function messages(): array
    {
        return [
            'discipline.required' => 'The discipline field is required.',
            'program.required' => 'The program field is required.',
        ];
    }
}
