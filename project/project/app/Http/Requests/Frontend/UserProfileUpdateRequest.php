<?php

namespace App\Http\Requests\Frontend;

use App\Rules\PhoneLength;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
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
    /**
     * Accept numbers the way people type them: "0712 345 678" → "712345678"
     * (digits only; drop a leading trunk 0 when that's the only reason it's too long).
     */
    protected function prepareForValidation(): void
    {
        if (!$this->filled('phone')) {
            return;
        }
        $phone = preg_replace('/\D+/', '', (string)$this->input('phone'));
        $iso = strtoupper((string)($this->input('phone_country') ?: $this->input('country_code')));
        $lengths = null;
        foreach (config('country') as $country) {
            if (($iso && strtoupper($country['code']) === $iso) || (!$iso && $country['phone_code'] == $this->input('phone_code'))) {
                $lengths = (array)$country['phoneLength'];
                break;
            }
        }
        if ($lengths && str_starts_with($phone, '0') && !in_array(strlen($phone), $lengths) && in_array(strlen($phone) - 1, $lengths)) {
            $phone = substr($phone, 1);
        }
        $this->merge(['phone' => $phone]);
    }

    public function rules(): array
    {
        // Match on ISO country first: dial codes are shared (+1 = US, Canada, American Samoa, …).
        // The phone's own flag decides its length rules (the profile "country" can be different).
        $iso = strtoupper((string)($this->input('phone_country') ?: $this->input('country_code')));
        $phoneLength = null;
        foreach (config('country') as $country) {
            if ($iso && strtoupper($country['code']) === $iso) {
                $phoneLength = $country['phoneLength'];
                break;
            }
            if ($phoneLength === null && $country['phone_code'] == $this->input('phone_code')) {
                $phoneLength = $country['phoneLength'];
            }
        }
        $phoneLength = $phoneLength ?? 15;

        return [
            'firstname' => 'required|string|min:1|max:100',
            'lastname' => 'nullable|string|max:100',
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users', 'email')->ignore(auth()->id())->whereNull('child_panel_id'),
            ],
            'phone' => [
                'required',
                'numeric',
                'digits_between:1,20',
                new PhoneLength($phoneLength),
            ],
            'phone_code' => 'nullable|string|max:20',
            'phone_country' => 'nullable|string|max:5',
            'address' => 'nullable|string|max:500', // an SMM account doesn't need a postal address
            'state' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:50',
            'language' => 'required|string|exists:languages,id',
            'time_zone' => 'required|string|max:100',
        ];
    }
}
