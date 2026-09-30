<?php

namespace App\Http\Requests\Admin\ApiProvider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ApiProviderStoreRequest extends FormRequest
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
            'api_name' => 'required|string|min:1|max:191',
            'url' => 'required|url|min:1|max:191',
            'api_key' => 'required|string|min:1|max:191',
            'conversion_rate' => 'required|numeric|not_in:0',
            'status' => 'nullable|numeric|in:0,1',
            'low_balance_alert' => 'nullable|numeric|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $url = $this->input('url');

            if (!str_contains($url, '/api/v2')) {
                $validator->errors()->add('url', 'This version is not compatible for the script.');
            }
        });
    }
}
