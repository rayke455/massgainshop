<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class ServiceStoreRequest extends FormRequest
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
            'name' => 'required|string|min:1|max:255',
            'category' => 'required|integer|exists:categories,id',
            'min_amount' => 'required|numeric|not_in:0',
            'max_amount' => 'required|numeric|not_in:0',
            'price' => 'required|numeric|not_in:0',
            'status' => 'nullable|numeric|in:0,1',
            'drip_feed' => 'nullable|numeric|in:0,1',
            'manual_api' => 'required|numeric|in:0,1',
            'api_provider_id' => 'exclude_if:manual_api,0|exists:api_providers,id',
            'api_service_id' => 'exclude_if:manual_api,0|numeric|not_in:0',
            'refill' => 'required|numeric|in:1,2,3',
            'description' => 'required|string|max:2000',
        ];
    }
}
