<?php

namespace App\Http\Requests\Admin\Notice;

use Illuminate\Foundation\Http\FormRequest;

class NoticeStoreRequest extends FormRequest
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
            'description' => 'required|string|min:5',
            'status' => 'nullable|numeric|in:0,1',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:4096',
        ];
    }
}
