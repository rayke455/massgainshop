<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:255|unique:categories,category_title',
            'social_media' => 'nullable|integer|exists:social_media,id', // empty = detect from the name
            'status' => 'nullable|numeric|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096'
        ];
    }

    public function messages(): array
    {
        return ['name.unique' => 'A category with this name already exists.'];
    }
}
