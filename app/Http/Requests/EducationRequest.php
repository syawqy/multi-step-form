<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationRequest extends FormRequest
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
        $currentYear = date('Y');
        $minYear = $currentYear - 50;
        $maxYear = $currentYear + 5;
        
        return [
            'institution' => 'required|string|max:255|min:2',
            'degree' => 'required|string|max:255|min:2',
            'graduation_year' => "required|integer|min:{$minYear}|max:{$maxYear}",
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'institution.required' => 'Institution name is required.',
            'institution.min' => 'Institution name must be at least 2 characters long.',
            'degree.required' => 'Degree/qualification is required.',
            'degree.min' => 'Degree must be at least 2 characters long.',
            'graduation_year.required' => 'Graduation year is required.',
            'graduation_year.integer' => 'Please select a valid graduation year.',
            'graduation_year.min' => 'Graduation year seems too old.',
            'graduation_year.max' => 'Graduation year cannot be more than 5 years in the future.',
        ];
    }
}
