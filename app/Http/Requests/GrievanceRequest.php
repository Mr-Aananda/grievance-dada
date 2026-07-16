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
        $rules = [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'files' => ['nullable', 'array', 'max:10'],
        ];

        if ($this->hasFile('files')) {
            $files = $this->file('files');
            if (is_array($files)) {
                foreach ($files as $key => $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $mime = $file->getMimeType();
                        if (str_starts_with($mime, 'video/')) {
                            // Max 50 MB = 51200 KB
                            $rules["files.{$key}"] = [
                                'file',
                                'max:51200',
                                'mimes:mp4,avi,mov,wmv,mkv,webm',
                            ];
                        } elseif (str_starts_with($mime, 'image/')) {
                            // Max 10 MB = 10240 KB
                            $rules["files.{$key}"] = [
                                'file',
                                'max:10240',
                                'mimes:jpg,jpeg,png,gif,webp,bmp',
                            ];
                        } else {
                            // Max 10 MB = 10240 KB
                            $rules["files.{$key}"] = [
                                'file',
                                'max:10240',
                                'mimes:pdf,doc,docx,xls,xlsx,txt,csv',
                            ];
                        }
                    }
                }
            }
        }

        return $rules;
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
            'files.*.max' => 'File size exceeds the allowed limit (10 MB for images/files, 50 MB for videos).',
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
