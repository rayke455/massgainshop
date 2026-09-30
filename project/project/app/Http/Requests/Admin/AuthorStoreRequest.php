<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuthorStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'social_media_name' => 'required|array',
            'social_media_name.*' => 'required|string|max:300',
            'icon' => 'required|array',
            'icon.*' => 'required|string|max:300',
            'link' => 'required|array',
            'link.*' => 'required|string|max:2000',
            'status' => 'required|string|max:100',
            'image' => 'required|mimes:jpg,png,jpeg|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'social_media_name.*.required' => 'The social media name field is required',
            'icon.*.required' => 'The icon field is required',
            'link.*.required' => 'The link field is required',
        ];
    }
}
