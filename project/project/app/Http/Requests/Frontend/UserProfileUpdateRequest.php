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
    public function rules(): array
    {
        $phoneLength = 15;
        foreach (config('country') as $country) {
            if ($country['phone_code'] == $this->input('phone_code')) {
                $phoneLength = $country['phoneLength'];
                break;
            }
        }

        return [
            'firstname' => 'required|string|min:1|max:100',
            'lastname' => 'required|string|min:1|max:100',
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
            'address' => 'required|string|min:3|max:500',
            'state' => 'required|string|max:100',
            'zipcode' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'country_code' => 'nullable|string|max:50',
            'language' => 'required|string|exists:languages,id',
            'time_zone' => 'required|string|max:100',
        ];
    }
}
