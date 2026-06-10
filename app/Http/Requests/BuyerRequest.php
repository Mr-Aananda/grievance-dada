<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BuyerRequest extends FormRequest
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
        $buyerId = $this->route('buyer') ? $this->route('buyer')->id : null;

        return [
            'company_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:buyers,code,' . $buyerId,
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('buyers', 'email')->ignore($buyerId)
            ],
            'phone' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('buyers', 'phone')->ignore($buyerId)
            ],
            'country' => 'required|string|max:100',
            'address' => 'nullable|string',
            'note' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered with another buyer.',
            'phone.unique' => 'This phone number is already registered with another buyer.',
            'company_name.required' => 'Company name is required.',
            'code.required' => 'Code is required.',
            'country.required' => 'Country is required.',
        ];
    }
}
