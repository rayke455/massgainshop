<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogUpdateRequest extends FormRequest
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
            'language_id' => 'required|integer|exists:languages,id',
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'min:1', 'max:150',
                Rule::unique('blog_details', 'blog_id')->ignore($this->route()->parameters['id']),
            ],
            'category_id' => 'required|integer|exists:blog_categories,id',
            'author_id' => 'required|integer|exists:authors,id',
            'tags' => 'required|array',
            'quote_author' => 'required|string|max:100',
            'quote' => 'required|string|min:10|max:500',
            'description' => 'required|string|min:5',
            'status' => 'nullable|numeric|in:0,1',
            'breadcrumb_status' => 'nullable|numeric|in:0,1',
            'thumbnail_image' => 'nullable|mimes:png,jpg,jpeg|max:4096',
            'thumbnail_image_two' => 'nullable|mimes:png,jpg,jpeg|max:4096',
            'description_image' => 'nullable|mimes:png,jpg,jpeg|max:4096',
            'breadcrumb_image' => 'nullable|mimes:png,jpg,jpeg|max:4096',
        ];
    }
}
