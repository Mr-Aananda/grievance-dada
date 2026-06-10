<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:categories,code',
            // 'status' => 'required|boolean',
            'note' => 'nullable|string',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['code'] = 'nullable|string|max:50|unique:categories,code,' . $this->route('category');
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'code.unique' => 'This code is already taken',
        ];
    }
}
