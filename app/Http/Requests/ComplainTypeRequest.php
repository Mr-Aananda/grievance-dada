<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplainTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:complain_types,code',
            'type' => 'required|in:complain,manual',
            'status' => 'sometimes|boolean',
            'note' => 'nullable|string',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'] = 'nullable|string|max:50|unique:complain_types,code,' . $this->route('complain_type');
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Complain type name is required',
            'code.unique' => 'This code is already taken',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be either complain or manual',
        ];
    }
}
