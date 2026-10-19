<?php

namespace App\Services;

use App\Models\ApiProvider;
use Illuminate\Support\Facades\Http;

class ApiOrderService
{
    /**
     * Send an order to the provider. Tries the service's backup provider if the main one refuses.
     * @return bool|null  true = accepted, false = refused by every provider, null = manual service (no provider)
     */
    public static function placeOrder($service, $order, $data)
    {
        if (empty($service->api_provider_id)) {
            return null;
        }

        if (self::sendTo((int) $service->api_provider_id, $service->api_service_id, $service, $order, $data)) {
            return true;
        }

        if (!empty($service->backup_api_provider_id) && !empty($service->backup_api_service_id)) {
            $firstError = $order->status_description;
            if (self::sendTo((int) $service->backup_api_provider_id, $service->backup_api_service_id, $service, $order, $data)) {
                return true;
            }
            $order->update(['status_description' => $firstError . ' | backup: ' . $order->status_description]);
        }
        return false;
    }

    private static function sendTo(int $providerId, $providerServiceId, $service, $order, $data): bool
    {
        $apiProvider = ApiProvider::where('status', 1)->find($providerId);

        if (!$apiProvider || empty($apiProvider->url)) {
            $order->update(['status_description' => 'error: provider not available']);
            return false;
        }

        $postData = [
            'key' => $apiProvider->api_key,
            'action' => 'add',
            'service' => $providerServiceId,
        ];

        switch ($service->service_type) {
            case 'Package':
                $postData['link'] = $data['link'] ?? null;
                break;

            case 'Custom Comments':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? null;
                $postData['comments'] = preg_replace("/\r\n|\r|\n/", "\n", $data['comments'] ?? '');
                $order->update([
                    'order_type_info' => [
                        'comments' => $data['comments'],
                    ],
                ]);
                break;

            case 'SEO':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['keywords'] = $data['keywords'];
                $order->update([
                    'order_type_info' => [
                        'keywords' => $data['keywords']
                    ],
                ]);
                break;

            case 'Mentions with Hashtags':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['usernames'] = $data['usernames'];
                $postData['hashtags'] = $data['hashtags'];
                $order->update([
                    'order_type_info' => [
                        'usernames' => $data['usernames'],
                        'hashtags' => $data['hashtags']
                    ],
                ]);
                break;

            case 'Mentions Custom List':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['usernames'] = $data['usernames'];
                $order->update([
                    'order_type_info' => [
                        'usernames' => $data['usernames'],
                    ],
                ]);
                break;
            case 'Mentions Hashtag':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['hashtag'] = $data['hashtag'];
                $order->update([
                    'order_type_info' => [
                        'hashtag' => $data['hashtag'],
                    ],
                ]);
                break;

            case 'Mentions User Followers':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['username'] = $data['username'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'],
                    ],
                ]);
                break;

            case 'Mentions Media Likers':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['media'] = $data['media'];
                $order->update([
                    'order_type_info' => [
                        'media_url' => $data['media'],
                    ],
                ]);
                break;
            case 'Custom Comments Package':
                $postData['link'] = $data['link'] ?? null;
                $postData['comments'] = $data['comments'];
                $order->update([
                    'order_type_info' => [
                        'comments' => $data['comments'],
                    ],
                ]);
                break;
            case 'Comment Likes':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['username'] = $data['username'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username']
                    ],
                ]);
                break;

            case 'Poll':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['answer_number'] = $data['answer_number'];
                $order->update([
                    'order_type_info' => [
                        'answer_number' => $data['answer_number'],
                    ],
                ]);
                break;

            case 'Comment Replies':
                $postData['link'] = $data['link'] ?? null;
                $postData['username'] = $data['username'];
                $postData['comments'] = $data['comments'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'],
                        'comments' => $data['comments'],
                    ],
                ]);
                break;

            case 'Invites from Groups':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['groups'] = $data['groups'];
                $order->update([
                    'order_type_info' => [
                        'groups' => $data['groups'],
                    ],
                ]);
                break;

            case 'Subscriptions':
                $postData['link'] = $data['link'];
                $postData['username'] = $data['username'];
                $postData['new_posts'] = $data['new_posts'] ?? null;
                $postData['old_posts'] = $data['old_posts'] ?? null;
                $postData['min'] = $data['min'] ?? 0;
                $postData['max'] = $data['max'];
                $postData['delay'] = $data['delay'];
                $postData['expiry'] = $data['expiry_date'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'] ?? null,
                        'new_posts' => $data['new_posts'] ?? null,
                        'old_posts' => $data['old_posts'] ?? null,
                        'min' => $data['min'],
                        'max' => $data['max'],
                        'delay' => $data['delay'] ?? '0',
                        'expiry' => $data['expiry_date'] ?? null
                    ],
                ]);
                break;
            case 'Web Traffic':
                $postData['link'] = $data['link'];
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['country'] = $data['country'];
                $postData['device'] = $data['device'];
                $postData['type_of_traffic'] = $data['type_of_traffic'] ?? null;
                if ($postData['type_of_traffic'] == 1) {
                    $postData['google_keyword'] = $data['google_keyword'] ?? null;
                } elseif ($postData['type_of_traffic'] == 2) {
                    $postData['referring_url'] = $data['referring_url'] ?? null;
                } else {
                    $postData['blank_referrer'] = 3;
                }
                $order->update([
                    'order_type_info' => [
                        'country' => $data['country'] ?? null,
                        'device' => $data['device'] ?? null,
                        'type_of_traffic' => $data['type_of_traffic'],
                        'google_keyword' => $data['google_keyword'] ?? null,
                        'referring_url' => $data['referring_url'] ?? null,
                        'blank_referrer' => 3,
                    ],
                ]);
                break;

            default:
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                break;
        }

        if (isset($data['runs'])) {
            $postData['runs'] = $data['runs'];
        }
        if (isset($data['interval'])) {
            $postData['interval'] = $data['interval'];
        }

        try {
            $response = Http::asForm()->timeout(60)->post($apiProvider->url, $postData);
            $apiData = $response->json();

            if (isset($apiData['order'])) {
                $order->update([
                    'api_order_id' => $apiData['order'],
                    'api_provider_id' => $apiProvider->id,
                    'status_description' => "order: {$apiData['order']}",
                ]);
                return true;
            }

            $error = $apiData['error'] ?? 'Unknown error';
            $order->update([
                'status_description' => "error: {$error}",
            ]);

            return false;
        } catch (\Throwable $e) {
            $order->update([
                'status_description' => 'API request failed: ' . $e->getMessage(),
            ]);
            return false;
        }
    }

    public static function placeOrderThroughApi($service, $order, array $data = [])
    {
        if (!isset($service->api_provider_id) || empty($service->api_provider_id)) {
            return ['status' => 'success', 'order' => $order->id ?? null, 'error' => null, 'raw' => null];
        }

        $apiProvider = ApiProvider::find($service->api_provider_id);

        if (!$apiProvider || empty($apiProvider->url)) {
            return ['status' => 'error', 'order' => null, 'error' => 'API provider not found or url missing', 'raw' => null];
        }

        $postData = [
            'key' => $apiProvider->api_key,
            'action' => 'add',
            'service' => $service->api_service_id,
        ];

        switch ($service->service_type) {
            case 'Package':
                $postData['link'] = $data['link'] ?? null;
                break;

            case 'Custom Comments':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? null;
                $postData['comments'] = preg_replace("/\r\n|\r|\n/", "\n", $data['comments'] ?? '');
                $order->update([
                    'order_type_info' => [
                        'comments' => $data['comments'],
                    ],
                ]);
                break;

            case 'SEO':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['keywords'] = $data['keywords'];
                $order->update([
                    'order_type_info' => [
                        'keywords' => $data['keywords']
                    ],
                ]);
                break;

            case 'Mentions with Hashtags':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['usernames'] = $data['usernames'];
                $postData['hashtags'] = $data['hashtags'];
                $order->update([
                    'order_type_info' => [
                        'usernames' => $data['usernames'],
                        'hashtags' => $data['hashtags']
                    ],
                ]);
                break;

            case 'Mentions Custom List':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['usernames'] = $data['usernames'];
                $order->update([
                    'order_type_info' => [
                        'usernames' => $data['usernames'],
                    ],
                ]);
                break;
            case 'Mentions Hashtag':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['hashtag'] = $data['hashtag'];
                $order->update([
                    'order_type_info' => [
                        'hashtag' => $data['hashtag'],
                    ],
                ]);
                break;

            case 'Mentions User Followers':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['username'] = $data['username'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'],
                    ],
                ]);
                break;

            case 'Mentions Media Likers':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['media'] = $data['media'];
                $order->update([
                    'order_type_info' => [
                        'media_url' => $data['media'],
                    ],
                ]);
                break;
            case 'Custom Comments Package':
                $postData['link'] = $data['link'] ?? null;
                $postData['comments'] = $data['comments'];
                $order->update([
                    'order_type_info' => [
                        'comments' => $data['comments'],
                    ],
                ]);
                break;
            case 'Comment Likes':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['username'] = $data['username'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username']
                    ],
                ]);
                break;

            case 'Poll':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['answer_number'] = $data['answer_number'];
                $order->update([
                    'order_type_info' => [
                        'answer_number' => $data['answer_number'],
                    ],
                ]);
                break;

            case 'Comment Replies':
                $postData['link'] = $data['link'] ?? null;
                $postData['username'] = $data['username'];
                $postData['comments'] = $data['comments'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'],
                        'comments' => $data['comments'],
                    ],
                ]);
                break;

            case 'Invites from Groups':
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['groups'] = $data['groups'];
                $order->update([
                    'order_type_info' => [
                        'groups' => $data['groups'],
                    ],
                ]);
                break;

            case 'Subscriptions':
                $postData['link'] = $data['link'];
                $postData['username'] = $data['username'];
                $postData['new_posts'] = $data['new_posts'] ?? null;
                $postData['old_posts'] = $data['old_posts'] ?? null;
                $postData['min'] = $data['min'] ?? 0;
                $postData['max'] = $data['max'];
                $postData['delay'] = $data['delay'];
                $postData['expiry'] = $data['expiry_date'];
                $order->update([
                    'order_type_info' => [
                        'username' => $data['username'] ?? null,
                        'new_posts' => $data['new_posts'] ?? null,
                        'old_posts' => $data['old_posts'] ?? null,
                        'min' => $data['min'],
                        'max' => $data['max'],
                        'delay' => $data['delay'] ?? '0',
                        'expiry' => $data['expiry_date'] ?? null
                    ],
                ]);
                break;
            case 'Web Traffic':
                $postData['link'] = $data['link'];
                $postData['quantity'] = $data['quantity'] ?? 0;
                $postData['country'] = $data['country'];
                $postData['device'] = $data['device'];
                $postData['type_of_traffic'] = $data['type_of_traffic'] ?? null;
                if ($postData['type_of_traffic'] == 1) {
                    $postData['google_keyword'] = $data['google_keyword'] ?? null;
                } elseif ($postData['type_of_traffic'] == 2) {
                    $postData['referring_url'] = $data['referring_url'] ?? null;
                } else {
                    $postData['blank_referrer'] = 3;
                }
                $order->update([
                    'order_type_info' => [
                        'country' => $data['country'] ?? null,
                        'device' => $data['device'] ?? null,
                        'type_of_traffic' => $data['type_of_traffic'],
                        'google_keyword' => $data['google_keyword'] ?? null,
                        'referring_url' => $data['referring_url'] ?? null,
                        'blank_referrer' => 3,
                    ],
                ]);
                break;

            default:
                $postData['link'] = $data['link'] ?? null;
                $postData['quantity'] = $data['quantity'] ?? 0;
                break;
        }

        if (isset($data['runs'])) {
            $postData['runs'] = $data['runs'];
        }
        if (isset($data['interval'])) {
            $postData['interval'] = $data['interval'];
        }

        try {
            $response = Http::asForm()->post($apiProvider->url, $postData);
            $apiData = $response->json();

            if (isset($apiData['order'])) {
                $order->update(['api_order_id' => $apiData['order'], 'api_provider_id' => $apiProvider->id, 'status_description' => "order: {$apiData['order']}"]);
                return ['status' => 'success', 'order' => $apiData['order'], 'error' => null, 'raw' => $apiData];
            }

            $error = $apiData['error'] ?? 'Unknown error from provider';
            $order->update(['status_description' => "error: {$error}"]);
            return ['status' => 'error', 'order' => null, 'error' => $error, 'raw' => $apiData];
        } catch (\Throwable $e) {
            $order->update(['status_description' => 'Provider API request failed: ' . $e->getMessage(), 'api_response' => $e->getMessage()]);
            return ['status' => 'error', 'order' => null, 'error' => 'Provider API request failed: ' . $e->getMessage(), 'raw' => null];
        }
    }

    public static function resendPlaceOrder($service, $order, $newOrder)
    {
        if (!isset($service->api_provider_id)) {
            return false;
        }

        $apiProvider = ApiProvider::find($service->api_provider_id);

        if (!$apiProvider || empty($apiProvider->url)) {
            return false;
        }

        $postData = [
            'key' => $apiProvider->api_key,
            'action' => 'add',
            'service' => $service->api_service_id,
        ];


        switch ($service->service_type) {
            case 'Package':
                $postData['link'] = $order->link ?? null;
                break;

            case 'Custom Comments':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? null;
                $postData['comments'] = preg_replace("/\r\n|\r|\n/", "\n", $order->custom_comments ?? '');
                $newOrder->update([
                    'order_type_info' => [
                        'comments' => $order->custom_comments,
                    ],
                ]);
                break;

            case 'SEO':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['keywords'] = optional($order->order_type_info)->keywords;
                $newOrder->update([
                    'order_type_info' => [
                        'keywords' => optional($order->order_type_info)->keywords
                    ],
                ]);
                break;

            case 'Mentions with Hashtags':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['usernames'] = optional($order->order_type_info)->usernames;
                $postData['hashtags'] = optional($order->order_type_info)->hashtags;
                $newOrder->update([
                    'order_type_info' => [
                        'usernames' => optional($order->order_type_info)->usernames,
                        'hashtags' => optional($order->order_type_info)->hashtags
                    ],
                ]);
                break;

            case 'Mentions Custom List':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['usernames'] = optional($order->order_type_info)->usernames;
                $newOrder->update([
                    'order_type_info' => [
                        'usernames' => optional($order->order_type_info)->usernames,
                    ],
                ]);
                break;
            case 'Mentions Hashtag':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['hashtag'] = optional($order->order_type_info)->hashtag;
                $newOrder->update([
                    'order_type_info' => [
                        'hashtag' => optional($order->order_type_info)->hashtag,
                    ],
                ]);
                break;

            case 'Mentions User Followers':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['username'] = optional($order->order_type_info)->username;
                $newOrder->update([
                    'order_type_info' => [
                        'username' => optional($order->order_type_info)->username,
                    ],
                ]);
                break;

            case 'Mentions Media Likers':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['media'] = optional($order->order_type_info)->media_url;
                $newOrder->update([
                    'order_type_info' => [
                        'media_url' => optional($order->order_type_info)->media_url,
                    ],
                ]);
                break;
            case 'Custom Comments Package':
                $postData['link'] = $order->link ?? null;
                $postData['comments'] = optional($order->order_type_info)->comments;
                $newOrder->update([
                    'order_type_info' => [
                        'comments' => optional($order->order_type_info)->comments,
                    ],
                ]);
                break;
            case 'Comment Likes':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['username'] = optional($order->order_type_info)->username;
                $newOrder->update([
                    'order_type_info' => [
                        'username' => optional($order->order_type_info)->username
                    ],
                ]);
                break;

            case 'Poll':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['answer_number'] = optional($order->order_type_info)->answer_number;
                $newOrder->update([
                    'order_type_info' => [
                        'answer_number' => optional($order->order_type_info)->answer_number,
                    ],
                ]);
                break;

            case 'Comment Replies':
                $postData['link'] = $order->link ?? null;
                $postData['username'] = optional($order->order_type_info)->username;
                $postData['comments'] = optional($order->order_type_info)->comments;
                $newOrder->update([
                    'order_type_info' => [
                        'username' => optional($order->order_type_info)->username,
                        'comments' => optional($order->order_type_info)->comments,
                    ],
                ]);
                break;

            case 'Invites from Groups':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['groups'] = optional($order->order_type_info)->groups;
                $newOrder->update([
                    'order_type_info' => [
                        'groups' => optional($order->order_type_info)->groups,
                    ],
                ]);
                break;

            case 'Subscriptions':
                $postData['link'] = $order->link ?? null;
                $postData['username'] = optional($order->order_type_info)->username;
                $postData['new_posts'] = optional($order->order_type_info)->new_posts ?? null;
                $postData['old_posts'] = optional($order->order_type_info)->old_posts ?? null;
                $postData['min'] = optional($order->order_type_info)->min ?? 0;
                $postData['max'] = optional($order->order_type_info)->max;
                $postData['delay'] = optional($order->order_type_info)->delay;
                $postData['expiry'] = optional($order->order_type_info)->expiry;
                $newOrder->update([
                    'order_type_info' => [
                        'username' => optional($order->order_type_info)->username ?? null,
                        'new_posts' => optional($order->order_type_info)->new_posts ?? null,
                        'old_posts' => optional($order->order_type_info)->old_posts ?? null,
                        'min' => optional($order->order_type_info)->min,
                        'max' => optional($order->order_type_info)->max,
                        'delay' => optional($order->order_type_info)->delay ?? '0',
                        'expiry' => optional($order->order_type_info)->expiry ?? null
                    ],
                ]);
                break;
            case 'Web Traffic':
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                $postData['country'] = optional($order->order_type_info)->country;
                $postData['device'] = optional($order->order_type_info)->device;
                $postData['type_of_traffic'] = optional($order->order_type_info)->type_of_traffic ?? null;
                if ($postData['type_of_traffic'] == 1) {
                    $postData['google_keyword'] = optional($order->order_type_info)->google_keyword ?? null;
                } elseif ($postData['type_of_traffic'] == 2) {
                    $postData['referring_url'] = optional($order->order_type_info)->referring_url ?? null;
                } else {
                    $postData['blank_referrer'] = 3;
                }
                $newOrder->update([
                    'order_type_info' => [
                        'country' => optional($order->order_type_info)->country ?? null,
                        'device' => optional($order->order_type_info)->device ?? null,
                        'type_of_traffic' => optional($order->order_type_info)->type_of_traffic,
                        'google_keyword' => optional($order->order_type_info)->google_keyword ?? null,
                        'referring_url' => optional($order->order_type_info)->referring_url ?? null,
                        'blank_referrer' => 3,
                    ],
                ]);
                break;

            default:
                $postData['link'] = $order->link ?? null;
                $postData['quantity'] = $order->quantity ?? 0;
                break;
        }

        if (!empty($order->runs)) {
            $postData['runs'] = $order->runs;
        }
        if (!empty($order->interval)) {
            $postData['interval'] = $order->interval;
        }

        try {
            $response = Http::asForm()->post($apiProvider->url, $postData);
            $apiData = $response->json();

            if (isset($apiData['order'])) {
                $newOrder->update([
                    'api_order_id' => $apiData['order'],
                    'api_provider_id' => $apiProvider->id,
                    'status_description' => "order: {$apiData['order']}",
                ]);
                return true;
            }

            $error = $apiData['error'] ?? 'Unknown error';
            $newOrder->update([
                'status_description' => "error: {$error}",
            ]);

            return false;
        } catch (\Throwable $e) {
            $newOrder->update([
                'status_description' => 'API request failed: ' . $e->getMessage(),
            ]);
            return false;
        }
    }


}
