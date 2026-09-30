<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeoUpdateRequest extends FormRequest
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
        return [
            'page_title' => 'required|string|min:1|max:200',
            'meta_title' => 'nullable|string|min:1|max:200',
            'meta_keywords' => 'nullable|array',
            'meta_description' => 'nullable|string|min:1|max:1000',
            'og_description' => 'nullable|string|min:1|max:1000',
            'meta_robots' => 'nullable',
            'meta_image' => 'nullable|mimes:png,jpg,jpeg|max:4096',
            'is_static_footer' => 'nullable|numeric|in:0,1,2'
        ];
    }
}
