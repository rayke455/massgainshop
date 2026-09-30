<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * One place for talking to SMM providers (standard "API v2": key + action, form-encoded).
 * Big panels return thousands of services (SMMGen ≈ 8k, 3 MB) and can take 15–30 s,
 * so we use a long timeout, retry once, and cache the service list per provider.
 */
class ProviderApi
{
    public const SERVICES_TTL = 3600; // 1 hour — catalogues rarely change; "Refresh" forces a new download

    public function call($provider, array $params, int $timeout = 120): array
    {
        try {
            $response = Http::asForm()
                ->acceptJson()
                ->connectTimeout(15)
                ->timeout($timeout)
                ->retry(2, 1500, throw: false)
                ->post($provider->url, ['key' => $provider->api_key] + $params);
        } catch (\Throwable $e) {
            throw new \Exception("Could not reach {$provider->api_name}: the provider did not answer in time. Check the API URL or try again.");
        }

        $data = $response->json();
        if (!is_array($data)) {
            throw new \Exception("{$provider->api_name} returned an unexpected answer (HTTP {$response->status()}). Check the API URL — it usually ends in /api/v2.");
        }
        if (isset($data['error'])) {
            $msg = is_string($data['error']) ? $data['error'] : json_encode($data['error']);
            if (preg_match('/key/i', $msg)) {
                $msg .= ' — check the API key in Admin → API Providers.';
            }
            throw new \Exception("{$provider->api_name}: {$msg}");
        }

        return $data;
    }

    /** The provider's full service list, cached per provider. */
    public function services($provider, bool $fresh = false): array
    {
        $key = 'provider_services_' . $provider->id . '_' . md5($provider->url . $provider->api_key);
        if ($fresh) {
            Cache::forget($key);
        }

        return Cache::remember($key, self::SERVICES_TTL, function () use ($provider) {
            @set_time_limit(300); // big catalogues can take over a minute; don't let PHP's default limit kill the page
            $list = $this->call($provider, ['action' => 'services'], 180);
            // Keep only well-formed rows so one bad entry can't break the whole import.
            return array_values(array_filter($list, fn($s) => is_array($s) && isset($s['service'], $s['name'], $s['rate'])));
        });
    }

    public function balance($provider): array
    {
        return $this->call($provider, ['action' => 'balance'], 30);
    }
}
