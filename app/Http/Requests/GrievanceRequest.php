<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrievanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => [
                'file',
                'max:102400',                                // 100 MB
                'mimes:jpg,jpeg,png,gif,webp,bmp,'
                . 'mp4,avi,mov,wmv,mkv,webm,'
                . 'pdf,doc,docx,xls,xlsx,txt,csv',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a grievance category.',
            'category_id.exists' => 'The selected category is invalid.',
            'department_id.exists' => 'The selected department is invalid.',
            'description.required' => 'Please describe your issue.',
            'description.min' => 'Description must be at least 10 characters.',
            'description.max' => 'Description cannot exceed 5,000 characters.',
            'files.max' => 'You can upload a maximum of 10 files.',
            'files.*.file' => 'One or more uploads are not valid files.',
            'files.*.max' => 'Each file must not exceed 100 MB.',
            'files.*.mimes' => 'Unsupported file type. Allowed: images, videos, PDF, Word, Excel, text.',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => 'grievance category',
            'department_id' => 'department',
            'employee_id' => 'employee ID',
            'description' => 'description',
            'files.*' => 'file',
        ];
    }

    /**
     * Ensure validation errors are always returned as JSON
     * when the request comes from Vue (XMLHttpRequest / expects JSON).
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): never
    {
        if ($this->expectsJson()) {
            throw new \Illuminate\Validation\ValidationException($validator, response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
