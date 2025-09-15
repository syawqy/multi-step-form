<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'company' => 'required|string|max:255|min:2',
            'role' => 'required|string|max:255|min:2',
            'years' => 'required|in:0-1,1-3,3-5,5-10,10+',
            'description' => 'required|string|min:50|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'company.required' => 'Company name is required.',
            'company.min' => 'Company name must be at least 2 characters long.',
            'role.required' => 'Job title/role is required.',
            'role.min' => 'Job title must be at least 2 characters long.',
            'years.required' => 'Please select your years of experience.',
            'years.in' => 'Please select a valid experience level.',
            'description.required' => 'Job description is required.',
            'description.min' => 'Job description must be at least 50 characters long.',
            'description.max' => 'Job description cannot exceed 1000 characters.',
        ];
    }
}
