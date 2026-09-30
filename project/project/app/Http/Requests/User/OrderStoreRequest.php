<?php

namespace App\Http\Requests\User;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
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
        $service = Service::find($this->service);

        $rules = [
            'category' => 'required|integer|exists:categories,id',
            'service' => 'required|integer|exists:services,id',
            'link' => [
                'required',
                'max:2000',
                function ($attribute, $value, $fail) {
                    // Browser-side checks can be bypassed; at least make sure this is a real web link.
                    $ok = !preg_match('/\s/', (string)$value)
                        && filter_var($value, FILTER_VALIDATE_URL)
                        && preg_match('#^https?://[^/]+\.[a-z]{2,}#i', (string)$value);
                    if (!$ok) {
                        $fail(__('Please paste a full link, e.g. https://instagram.com/yourname. Open the app, tap Share → Copy link.'));
                    }
                },
            ],
            'order_type' => 'required|string',
            'order_notes' => 'nullable|string|max:2000',
            'check' => 'required',
            'quantity' => [
                Rule::requiredIf(function () use ($service) {
                    return $service && !in_array($service->service_type, ['Package', 'Mentions Custom List', 'Custom Comments Package', 'Comment Replies', 'Subscriptions', 'Comment Replies']);
                }),
                Rule::when($service && !in_array($service->service_type, ['Package', 'Mentions Custom List', 'Custom Comments Package', 'Comment Replies', 'Subscriptions', 'Comment Replies']), [
                    'integer',
                    'min:1',
                ]),
            ],
            'comments' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service && in_array($service->service_type, ['Custom Comments', 'Custom Comments Package', 'Comment Replies']);
                }),
            ],
            'keywords' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'SEO';
                }),
            ],
            'usernames' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service && (
                            $service->service_type === 'Mentions with Hashtags' ||
                            $service->service_type === 'Mentions Custom List'
                        );
                }),
            ],
            'hashtags' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Mentions with Hashtags';
                }),
            ],
            'hashtag' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Mentions Hashtag';
                }),
            ],
            'username' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service && (
                            $service->service_type === 'Mentions User Followers' ||
                            $service->service_type === 'Comment Likes' ||
                            $service->service_type === 'Comment Replies' ||
                            $service->service_type === 'Subscriptions'
                        );
                }),
            ],
            'media' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Mentions Media Likers';
                }),
            ],
            'answer_number' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Poll';
                }),
            ],
            'groups' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Groups';
                }),
            ],

            'new_posts' => ['nullable', 'max:1000'],
            'old_posts' => ['nullable', 'max:1000'],
            'expiry_date' => ['nullable', 'date', 'max:1000'],
            'min' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Subscriptions';
                }),
            ],
            'max' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Subscriptions';
                }),
            ],
            'delay' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Subscriptions';
                }),
            ],
            'device' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Web Traffic';
                }),
            ],
            'country' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Web Traffic';
                }),
            ],
            'type_of_traffic' => [
                'max:1000',
                Rule::requiredIf(function () use ($service) {
                    return $service && $service->service_type === 'Web Traffic';
                }),
            ],
            'google_keyword' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service &&
                        (
                            $service->service_type === 'Web Traffic' &&
                            request()->input('type_of_traffic') == 1
                        );
                }),
            ],
            'referring_url' => [
                'max:10000',
                Rule::requiredIf(function () use ($service) {
                    return $service &&
                        (
                            $service->service_type === 'Web Traffic' &&
                            request()->input('type_of_traffic') == 2
                        );
                }),
            ],

        ];

        if ($this->filled('drip_feed')) {
            $rules['runs'] = 'required|integer|not_in:0';
            $rules['interval'] = 'required|integer|not_in:0';
        }

        return $rules;
    }

    /** Trim the link and add https:// when people paste "instagram.com/name". */
    protected function prepareForValidation(): void
    {
        if ($this->filled('link')) {
            $link = trim((string)$this->input('link'));
            if (!preg_match('#^https?://#i', $link) && preg_match('#^[a-z0-9-]+(\.[a-z0-9-]+)+(/|$)#i', $link)) {
                $link = 'https://' . $link;
            }
            $this->merge(['link' => $link]);
        }
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Please choose a category.',
            'service.required' => 'Please choose a service.',
            'link.required' => 'Paste the link to your profile or post.',
            'check.required' => 'Please confirm the link is correct and the account is public.',
            'quantity.required' => 'Enter how many you want.',
        ];
    }

}
