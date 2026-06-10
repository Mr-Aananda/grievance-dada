<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can adjust authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sectionId = $this->route('section') ? $this->route('section')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sections', 'name')
                    ->where(function ($query) {
                        if ($this->has('department_id')) {
                            $query->where('department_id', $this->department_id);
                        }
                    })
                    ->ignore($sectionId)
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sections', 'code')->ignore($sectionId)
            ],
            'department_id' => [
                'required',
                'exists:departments,id',
                function ($attribute, $value, $fail) {
                    // Check if department is active
                    $department = \App\Models\Department::find($value);
                    if ($department && !$department->status) {
                        $fail('The selected department is inactive.');
                    }
                }
            ],
            'note' => 'nullable|string|max:500',
            'status' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Section name is required.',
            'name.unique' => 'A section with this name already exists in the selected department.',
            'name.max' => 'Section name may not be greater than 255 characters.',
            'code.required' => 'Section code is required.',
            'code.unique' => 'This section code is already in use.',
            'code.max' => 'Section code may not be greater than 50 characters.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'The selected department does not exist.',
            'note.max' => 'Note may not be greater than 500 characters.',
            'status.boolean' => 'Status must be either active or inactive.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'section name',
            'code' => 'section code',
            'department_id' => 'department',
            'note' => 'note',
            'status' => 'status',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim inputs
        $this->merge([
            'name' => trim($this->name),
            'code' => trim($this->code),
            'note' => $this->note ? trim($this->note) : null,
        ]);
    }
}
