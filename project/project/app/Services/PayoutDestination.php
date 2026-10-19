<?php
namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
class PayoutDestination {
    public static function validate(Request $request): array {
        $data = $request->validate(['country' => 'required|in:KE,TZ', 'method' => 'required|in:b2c,paybill,till',
            'account' => 'required|string|max:30', 'account_ref' => 'nullable|string|max:13']);
        $data['account'] = preg_replace('/[\s+()-]/', '', $data['account']);
        if ($data['method'] === 'b2c') {
            $code = $data['country'] === 'KE' ? '254' : '255';
            $pattern = $data['country'] === 'KE' ? '[17]' : '[67]';
            if (!preg_match('/^(?:'.$code.'|0)?('.$pattern.'\d{8})$/D', $data['account'], $m)) {
                throw ValidationException::withMessages(['account' => 'Enter a valid mobile number for the selected country.']);
            }
            $data['account'] = $code.$m[1];
        } elseif (!preg_match('/^\d{5,10}$/D', $data['account'])) {
            throw ValidationException::withMessages(['account' => 'Enter the merchant Paybill or Till number (5–10 digits).']);
        }
        $data['account_ref'] = $data['method'] === 'paybill' ? trim($data['account_ref'] ?? '') : null;
        if ($data['method'] === 'paybill' && $data['account_ref'] === '') {
            throw ValidationException::withMessages(['account_ref' => 'Enter the Paybill account reference.']);
        }
        return $data;
    }
}
