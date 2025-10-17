<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    public function rules(): array
    {
        return [
            'website_name' => 'required|string|max:255',
            'content_data' => 'required|array',
            'content_data.*' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'website_name.required' => 'Website name is required',
            'content_data.required' => 'Content data is required'
        ];
    }
}
