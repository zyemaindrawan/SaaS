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
            'content_data.*' => 'nullable',
            'subdomain' => 'nullable|string|max:255|unique:website_contents,subdomain,' . $this->route('websiteContent')->id,
            'custom_domain' => 'nullable|string|max:255|unique:website_contents,custom_domain,' . $this->route('websiteContent')->id
        ];
    }

    public function messages(): array
    {
        return [
            'website_name.required' => 'Website name is required',
            'content_data.required' => 'Content data is required',
            'subdomain.unique' => 'This subdomain is already taken',
            'custom_domain.unique' => 'This domain is already registered'
        ];
    }
}
