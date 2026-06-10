<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user') : null;
        $rules = [
            'name' => 'required|string|max:255',
            'emp_id' => 'required|string|max:50|unique:users,emp_id,' . $userId,
            'designation' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'section_id' => 'required|exists:sections,id',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'phone' => 'nullable|regex:/^\+?\d{1,3}?\s?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/|min:11|max:14|unique:users,phone,' . $userId,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => 'nullable',
            'role' => 'required',
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'confirmed',
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'emp_id.required' => 'Employee ID is required',
            'emp_id.unique' => 'This Employee ID is already registered',
            'department_id.required' => 'Please select a department',
            'section_id.required' => 'Please select a section',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please enter a valid phone number',
        ];
    }
}
