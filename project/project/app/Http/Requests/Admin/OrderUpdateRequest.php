<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
            'link' => 'required|url|max:2000',
            'remains' => 'nullable|numeric',
            'start_counter' => 'nullable|numeric',
            'status' => 'required|in:awaiting,pending,processing,progress,completed,partial,canceled,refunded',
            'refill_status' => 'sometimes|in:awaiting,pending,processing,progress,completed,partial,canceled,refunded',
            'reason' => 'nullable|max:10000'
        ];
    }

    public function messages()
    {
        return [
            'reason.max' => 'The note field should be maximum 10000 characters.'
        ];
    }
}
