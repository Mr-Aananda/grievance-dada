<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplainRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:complain,manual',
            'buyer_id' => 'required|exists:buyers,id',
            'complain_type_id' => 'required|exists:complain_types,id',
            'category_id' => 'nullable|exists:categories,id',
            'subject' => 'nullable|string|max:255',
            'manual_category' => 'nullable|string',
            'date' => 'required|date',
            'name' => 'nullable|string|max:255',
            'ps' => 'nullable|string|max:255',
            'po' => 'nullable|string|max:255',
            'cap' => 'nullable|string|max:255',
            'style_order' => 'nullable|string|max:255',
            'quantity' => 'nullable|numeric|min:0',
            'amount' => 'nullable|numeric|min:0',
            'line_floor' => 'nullable|string|max:255',
            'complain' => 'required|string',
            'note' => 'nullable|string',
            'status' => 'nullable|string|in:pending,in_progress,resolved,closed',

            // For edit mode
            'deleted_files' => 'nullable|array',
            'deleted_files.*' => 'integer|exists:media,id',
            'deleted_videos' => 'nullable|array',
            'deleted_videos.*' => 'integer|exists:media,id',
        ];

        // Add new_files validation rules for images + documents
        if ($this->hasFile('new_files')) {
            $rules['new_files'] = ['array', 'max:20'];

            // Validate each file
            $rules['new_files.*'] = function ($attribute, $value, $fail) {
                // Check if file size exceeds limit
                if ($value->getSize() > 15360 * 1024) { // 15MB in bytes
                    $fail('File size must be less than 15MB');
                }

                // Check if total files exceed 20 (already handled by max:20)
            };
        } else {
            $rules['new_files'] = ['nullable', 'array', 'max:20'];
        }

        // Add videos validation rules
        if ($this->hasFile('videos')) {
            $rules['videos'] = ['array', 'max:5'];
            $rules['videos.*'] = [
                'file',
                'mimes:mp4,avi,mov,wmv,mkv,webm,flv,3gp,mpeg',
                'max:524288000' // 500MB
            ];
        } else {
            $rules['videos'] = ['nullable', 'array', 'max:5'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'type.required' => 'Type is required',
            'type.in' => 'Type must be either "complain" or "manual"',
            'complain_type_id.required' => 'Complain type is required',
            'complain_type_id.exists' => 'Selected complain type does not exist',
            'buyer_id.required' => 'Buyer is required',
            'buyer_id.exists' => 'Selected buyer does not exist',
            'category_id.exists' => 'Selected category does not exist',
            'date.required' => 'Date is required',
            'name.required' => 'Name is required',
            'complain.required' => 'Complaint details are required',

            // File messages
            'new_files.max' => 'Maximum 20 files allowed',
            'new_files.*.max' => 'Each file must be less than 15MB',

            // Video messages
            'videos.max' => 'Maximum 5 videos allowed',
            'videos.*.max' => 'Each video must be less than 500MB',
            'videos.*.mimes' => 'Video must be in MP4, AVI, MOV, WMV, MKV, WEBM, FLV, 3GP, or MPEG format',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Ensure deleted_files is an array
        if ($this->has('deleted_files')) {
            $this->merge([
                'deleted_files' => is_array($this->deleted_files)
                    ? $this->deleted_files
                    : explode(',', $this->deleted_files),
            ]);
        }

        // Ensure deleted_videos is an array
        if ($this->has('deleted_videos')) {
            $this->merge([
                'deleted_videos' => is_array($this->deleted_videos)
                    ? $this->deleted_videos
                    : explode(',', $this->deleted_videos),
            ]);
        }
    }
}
