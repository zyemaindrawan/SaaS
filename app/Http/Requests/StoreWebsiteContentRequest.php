<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'template_slug' => 'required|string|exists:templates,slug',
            'website_name' => 'required|string|max:255',
            'content_data' => 'required|array',
            'content_data.*' => 'nullable',
            'subdomain' => 'nullable|string|max:255|unique:website_contents,subdomain',
            'custom_domain' => 'nullable|string|max:255|unique:website_contents,custom_domain'
        ];
    }

    public function messages(): array
    {
        return [
            'template_slug.required' => 'Template is required',
            'template_slug.exists' => 'Selected template is invalid',
            'website_name.required' => 'Website name is required',
            'content_data.required' => 'Content data is required',
            'subdomain.unique' => 'This subdomain is already taken',
            'custom_domain.unique' => 'This domain is already registered'
        ];
    }
}
