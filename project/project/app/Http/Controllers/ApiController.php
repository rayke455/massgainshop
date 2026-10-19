<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\GetChildPanel;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\ApiOrderService as OrderService;

class ApiController extends Controller
{
    use Notify, GetChildPanel;

    public function apiV2(Request $request)
    {
        $basic = basicControl();
        $req = $request->all();

        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
            'action' => 'required|in:balance,services,add,create_order,status,orders,refill,refill_status',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::select('id', 'api_token', 'status', 'balance')
            ->where('api_token', $request->key)
            ->first();

        if (!$user || !$user->status) {
            return response()->json(['error' => 'The selected key is invalid.'], 422);
        }

        if (strtolower($req['action']) == 'balance') {

            $result['status'] = 'success';
            $result['balance'] = $user->balance;
            $result['currency'] = $basic->base_currency;
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'services') {

            $result = $this->getServices();
            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'add') {

            DB::beginTransaction();
            try {
                $validator = Validator::make($req, [
                    'service' => 'required|exists:services,id,service_status,1'
                ]);

                if ($validator->fails()) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'error' => $validator->messages()->first()], 422);
                }

                $service = Service::where('id', $req['service'])->where('service_status', 1)->first();
                if (!$service) {
                    return response()->json(['status' => 'error', 'error' => 'The selected service is invalid.'], 400);
                }

                $quantity = $req['quantity'] ?? 0;
                if ($service->drip_feed == 1 && !isset($req['drip_feed'])) {
                    $quantity = ($req['quantity'] ?? 0) * ($req['runs'] ?? 1);
                }

                $isQuantityBased = in_array($service->service_type, [
                    'Default', 'Mentions', 'Mentions with Hashtags', 'Custom Comments', 'Mentions Custom List', 'Mentions Hashtag',
                    'Mentions User Followers', 'Mentions Media Likers', 'Comment Likes', 'Poll', 'Invites from Groups', 'Web Traffic'
                ]);

                if ($isQuantityBased) {
                    if ($quantity < $service->min_amount || $quantity > $service->max_amount) {
                        return response()->json(['status' => 'error', 'error' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}."], 422);
                    }
                }

                $userRate = \App\Services\Growth::applyVip((float) ($service->user_rate ?? $service->price), $user);
                $price = round(($quantity * $userRate) / 1000, basicControl()->fraction_number);

                if (!$isQuantityBased) {
                    $price = round((float) $userRate, basicControl()->fraction_number);
                }

                $user = User::lockForUpdate()->find($user->id); // two API orders at once can't both spend the same balance
                if ($user->balance < $price) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'error' => 'Insufficient balance. Please add funds.'], 402);
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'category_id' => $req['category'] ?? $service->category_id,
                    'service_id' => $service->id,
                    'link' => $req['link'] ?? null,
                    'quantity' => $req['quantity'] ?? 0,
                    'status' => 'pending',
                    'price' => $price,
                    'comments' => $req['order_notes'] ?? null,
                    'custom_comments' => $req['custom_comments'] ?? null,
                    'drip_feed' => (!empty($req['runs']) && !empty($req['interval'])) ? 1 : null,
                    'runs' => $req['runs'] ?? null,
                    'interval' => $req['interval'] ?? null,
                    'reason' => 'This order from user api.',
                ]);

                if (!$order) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'error' => 'Failed to create order.'], 500);
                }

                $data = $req;
                $apiData = OrderService::placeOrderThroughApi($service, $order, $data);

                if ($apiData['status'] !== 'success') {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'error' => $apiData['error'] ?? 'Provider error'], 400);
                }

                $user->decrement('balance', $price);

                Transaction::create([
                    'user_id' => $user->id,
                    'trx_type' => '-',
                    'amount' => $price,
                    'remarks' => 'This transaction creates an order user.',
                    'charge' => 0,
                ]);

                DB::commit();

                return response()->json(['status' => 'success', 'order' => $order->id]);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'error' => 'Server error: ' . $e->getMessage()], 500);
            }

        } elseif (strtolower($req['action']) == 'create_order') {

            return $this->createOrder($req, $user);

        } elseif (strtolower($req['action']) == 'status') {

            $validator = Validator::make($req, [
                'order' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $order = Order::where('id', $req['order'])->where('user_id', $user->id)->first();
            if (!$order) {
                return response()->json(['error' => "The selected order id is invalid."], 422);
            }

            $result['status'] = strtoupper($order->status);
            $result['charge'] = $order->price;
            $result['start_count'] = (int)$order->start_counter;
            $result['remains'] = (int)$order->remains;
            $result['currency'] = $basic->base_currency;

            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'orders') {

            $validator = Validator::make($req, [
                'orders' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $orders = explode(",", $req['orders']);

            $result = Order::whereIn('id', $orders)->where('user_id', $user->id)->get()->map(function ($order) {
                return [
                    'order' => $order->id,
                    'status' => strtoupper($order->status),
                    'charge' => $order->price,
                    'start_count' => (int)$order->start_counter,
                    'remains' => (int)$order->remains
                ];
            });

            return response()->json($result, 200);

        } elseif (strtolower($req['action']) == 'refill') {

            $validator = Validator::make($req, ['order' => 'required']);
            if ($validator->fails()) return response()->json(['error' => $validator->messages()->first()], 422);

            $order = Order::with('service.provider')
                ->where(['id' => $req['order'], 'user_id' => $user->id])
                ->first();

            if (!$order) return response()->json(['error' => "Invalid order ID."], 422);

            $service = optional($order->service);
            $provider = optional($order->effectiveProvider());

            if (!$order->canBeRefilled()) return response()->json(['error' => "Not eligible for refill."], 400);

            if ($service->is_refill_automatic && $provider->status == 1) {
                $res = Http::asForm()->timeout(30)->post($provider->url, [
                    'key' => $provider->api_key,
                    'action' => 'refill',
                    'order' => $order->api_order_id,
                ])->json();

                if (!isset($res['refill'])) return response()->json(['error' => "Provider rejected refill."], 400);

                $order->update(['api_refill_id' => $res['refill']]);
            } elseif ($service->is_refill_automatic && $provider->status != 1) {
                return response()->json(['error' => "Provider inactive."], 400);
            }

            $order->update(['refill_status' => 'awaiting', 'refilled_at' => now()]);

            return response()->json(['refill' => $order->id], 200);

        } elseif (strtolower($req['action']) == 'refill_status') {

            $validator = Validator::make($req, [
                'refill' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $order = Order::where('id', $req['refill'])->where('user_id', $user->id)->whereNotNull('refill_status')->first();

            if (!$order) {
                return response()->json(['error' => "The selected refill id is invalid."], 422);
            }

            return response()->json(['status' => strtoupper($order->refill_status)], 200);
        }

        return response()->json(['error' => 'Invalid action.'], 422);
    }

    public function getServices()
    {
        $services = Service::where('service_status', 1)
            ->get()
            ->map(function ($service) {
                return [
                    'service' => $service->id,
                    'name' => $service->service_title,
                    'category' => optional($service->category)->category_title,
                    'rate' => $service->price,
                    'min' => $service->min_amount,
                    'max' => $service->max_amount,
                    'drip_feed' => $service->drip_feed,
                    'refill' => $service->refill,
                    'type' => $service->service_type,
                    'currency' => basicControl()->base_currency,
                ];
            });

        return $services;
    }

    public function createOrder($req, $user)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($req, [
                'service' => 'required|exists:services,id,service_status,1',
                'child_panel_id' => 'nullable|exists:child_panels,id'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'error' => $validator->messages()->first()], 422);
            }

            $service = Service::find($req['service']);
            if (!$service) {
                return response()->json(['status' => 'error', 'error' => 'Incorrect service id.'], 400);
            }

            $quantity = $req['quantity'] ?? 0;
            if ($service->drip_feed == 1 && !isset($req['drip_feed'])) {
                $quantity = ($req['quantity'] ?? 0) * ($req['runs'] ?? 1);
            }

            $isQuantityBased = in_array($service->service_type, [
                'Default', 'Mentions', 'Mentions with Hashtags', 'Custom Comments', 'Mentions Custom List', 'Mentions Hashtag',
                'Mentions User Followers', 'Mentions Media Likers', 'Comment Likes', 'Poll', 'Invites from Groups', 'Web Traffic'
            ]);

            if ($isQuantityBased) {
                if ($quantity < $service->min_amount || $quantity > $service->max_amount) {
                    return response()->json(['status' => 'error', 'error' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}."], 422);
                }
            }

            $userRate = $service->user_rate ?? $service->price;
            $price = round(($quantity * $userRate) / 1000, basicControl()->fraction_number);

            if (!$isQuantityBased) {
                $price = $service->price ?? 0;
            }

            $user = User::lockForUpdate()->find($user->id); // no double spending with simultaneous orders
            if ($user->balance < $price) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'error' => 'Insufficient balance on child panel account.'], 402);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'category_id' => $req['category'] ?? null,
                'service_id' => $service->id,
                'link' => $req['link'] ?? null,
                'quantity' => $req['quantity'] ?? 0,
                'status' => 'pending',
                'price' => $price,
                'comments' => $req['order_notes'] ?? null,
                'custom_comments' => $req['custom_comments'] ?? null,
                'drip_feed' => (!empty($req['runs']) && !empty($req['interval'])) ? 1 : null,
                'runs' => $req['runs'] ?? null,
                'interval' => $req['interval'] ?? null,
                'reason' => 'This order was created by a user from your child panel account.',
            ]);

            if (!$order) {
                DB::rollBack();
                return response()->json(['status' => 'error', 'error' => 'Failed to create order.'], 500);
            }

            $data = $req;
            $apiData = OrderService::placeOrderThroughApi($service, $order, $data);

            if ($apiData['status'] !== 'success') {
                DB::rollBack();
                return response()->json(['status' => 'error', 'error' => $apiData['error'] ?? 'Provider error'], 400);
            }

            $user->decrement('balance', $price);

            Transaction::create([
                'user_id' => $user->id,
                'trx_type' => '-',
                'amount' => $price,
                'remarks' => 'This transaction creates an order for a child panel user.',
                'charge' => 0,
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'order' => $order->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}
