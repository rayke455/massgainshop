<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\MassOrderStoreRequest;
use App\Models\ApiProvider;
use App\Models\ContentDetails;
use App\Models\Currency;
use App\Models\DraftMassOrder;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class MassOrderController extends Controller
{
    use Notify;

    protected object $user;

    protected string $theme;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function massOrder(): view
    {
        $data['howToMassOrder'] = ContentDetails::with('content')
            ->whereHas('content', function ($query) {
                $query->where('name', 'how_to_mass_orders')
                    ->where('theme', basicControl()->theme)
                    ->where('type', 'single');
            })
            ->first();
        return view(template() . 'user.order.add_mass_order', $data);
    }


    public function showDraftOrder(): view
    {
        $currency = Currency::where('code', $this->user->currency)->first();
        $draftMassOrders = DraftMassOrder::with('service')
            ->where('user_id', auth()->user()->id)->get();
        return view(template() . 'user.order.show_draft_order', compact('draftMassOrders', 'currency'));
    }

    public function draftOrderStore(MassOrderStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $orders = preg_split('/\r\n|\r|\n/', $request->mass_order);
            $orderNumber = $this->strRandomOrder(8);

            foreach ($orders as $order) {

                $singleOrderArray = explode("|", trim($order));
                $singleOrder = array_map('trim', $singleOrderArray);
                $remarks = '';

                if (count($singleOrder) != 3) {
                    return back()->with('error', "Each order must contain exactly 3 items separated by pipe(|).");
                }

                $serviceId = Service::userRate()->find($singleOrder[0]);
                if (!is_numeric($singleOrder[0]) || !$serviceId) {
                    $remarks .= "Service id must be a valid number. ";
                }

                if (!is_numeric($singleOrder[1])) {
                    $remarks .= "Quantity must be an integer. ";
                }

                if (!filter_var($singleOrder[2], FILTER_VALIDATE_URL)) {
                    $remarks .= "Link must be a valid URL. ";
                }

                if ($serviceId) {
                    $specificRate = \App\Services\Growth::applyVip(floatval(($serviceId->user_rate) ?? $serviceId->price), auth()->user());
                    $price = round(((floatval($singleOrder[1]) * $specificRate) / 1000), basicControl()->fraction_number);
                }

                $draftMassOrder = DraftMassOrder::create([
                    'user_id' => auth()->user()->id,
                    'order_id' => $orderNumber,
                    'service_id' => $singleOrder[0],
                    'quantity' => $singleOrder[1],
                    'link' => $singleOrder[2],
                    'price' => $price ?? 0,
                    'remarks' => !empty($remarks) ? $remarks : null,
                    'status' => !empty($remarks) ? 0 : 1,
                ]);

                if (!$draftMassOrder) {
                    throw new \Exception('Something went wrong, while store mass order data.');
                }
            }

            $currency = Currency::where('code', optional($this->user)->currency)->first();
            session()->put('currency', $currency);
            return redirect()->route('user.draft.mass.order', $orderNumber)->with('success', 'Draft mass order saved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }


    public function strRandomOrder($length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function draftMassOrder(Request $request, $order_id): view
    {
        $draftMassOrders = DraftMassOrder::with('service')
            ->where('order_id', $order_id)
            ->where('user_id', auth()->user()->id)->get();
        return view(template() . 'user.order.draft_mass_order', compact('draftMassOrders'));
    }

    public function editDraftOrder(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $draftOrder = DraftMassOrder::where('user_id', auth()->id())->find($request->draftOrderId);
        if (!$draftOrder) {
            return response([
                'status' => 'error',
                'message' => 'Draft order not found.'
            ]);
        }

        $service = Service::userRate()->where('id', $request->serviceId)->first();

        if (!$service) {
            return response([
                'status' => 'error',
                'message' => 'Provide a valid service ID.'
            ]);
        }

        if (!is_numeric($request->quantity)) {
            return response([
                'status' => 'error',
                'message' => 'Quantity must be an integer.'
            ]);
        }

        if (!filter_var($request->link, FILTER_VALIDATE_URL)) {
            return response([
                'status' => 'error',
                'message' => 'Link must be a valid URL.'
            ]);
        }

        $specificRate = \App\Services\Growth::applyVip(floatval(($service->user_rate) ?? $service->price), auth()->user());
        $price = round(((floatval($request->quantity) * $specificRate) / 1000), basicControl()->fraction_number);

        $draftOrder->update([
            'service_id' => $service->id,
            'quantity' => $request->quantity,
            'link' => $request->link,
            'price' => $price,
            'remarks' => null,
            'status' => 1,
        ]);

        return response([
            'status' => 'success',
            'message' => 'Draft order saved successfully.'
        ]);
    }


    public function massOrderStore(Request $request): \Illuminate\Http\RedirectResponse
    {
        $orders = json_decode($request->orders);
        $basic = basicControl();

        if (!is_array($orders) && !is_object($orders)) {
            return back()->with('error', 'No mass orders were submitted.');
        }

        foreach ($orders as $order) {

            $serviceId = Service::userRate()->find($order->service_id);

            DB::beginTransaction();
            try {
                if ($serviceId) {
                    $specificRate = \App\Services\Growth::applyVip(floatval(($serviceId->user_rate) ?? $serviceId->price), auth()->user());

                    $orderM = new Order();
                    $orderM->service_id = $order->service_id;
                    $orderM->category_id = $serviceId->category_id;
                    $orderM->quantity = $order->quantity;
                    $orderM->link = $order->link;

                    $price = round(((floatval($order->quantity) * $specificRate) / 1000), $basic->fraction_number);
                    $orderM->price = $price;
                    $user = $this->user;
                    $orderM->user_id = $user->id;

                    if ($serviceId->service_status == 1) {
                        if (isset($order->quantity) && !empty($order->quantity) && $order->quantity % 1 == 0) {
                            if ($serviceId->min_amount <= $order->quantity && $serviceId->max_amount >= $order->quantity) {
                                if (isset($order->link) && !empty($order->link)) {

                                    if ($user->balance >= $orderM->price) {
                                        $user->balance -= $orderM->price;
                                        $user->save();

                                        $orderM->status = 'pending';

                                        if (isset($serviceId->api_provider_id)) {
                                            $apiProviderData = ApiProvider::find($serviceId->api_provider_id);
                                            if ($apiProviderData) {
                                                $response = Http::acceptJson()->post($apiProviderData->url, [
                                                    'key' => $apiProviderData['api_key'],
                                                    'action' => 'add',
                                                    'service' => $serviceId->api_service_id,
                                                    'link' => $order->link,
                                                    'quantity' => $order->quantity,
                                                ]);

                                                $apiData = $response->json();
                                                if (isset($apiData['order'])) {
                                                    $orderM->status_description = "order: {$apiData['order']}";
                                                    $orderM->api_order_id = $apiData['order'];
                                                    $orderM->status = 'progress';
                                                } else {
                                                    if ($apiData != null)
                                                        $orderM->status_description = "error: {$apiData['error']}";
                                                }
                                            }
                                        }
                                        $orderM->save();

                                        $transaction = Transaction::create([
                                            'transactional_id' => $orderM->id,
                                            'transactional_type' => Order::class,
                                            'user_id' => $user->id,
                                            'trx_type' => '-',
                                            'amount' => $orderM->price,
                                            'charge' => 0,
                                            'remarks' => 'Place order',
                                        ]);

                                        $this->sendNotification($user, $orderM, $transaction);

                                    } else {
                                        $orderM->reason = "Insufficient balance in your wallet";
                                        $orderM->status = 'canceled';
                                    }
                                } else {
                                    $orderM->reason = "Link is Invalid";
                                    $orderM->status = 'canceled';
                                }
                            } else {
                                $orderM->reason = "Order quantity should be minimum {$serviceId->min_amount} and maximum {$serviceId->max_amount}";
                                $orderM->status = 'canceled';
                            }
                        } else {
                            $orderM->reason = "Invalid Quantity";
                            $orderM->status = 'canceled';
                        }
                    } else {
                        $orderM->reason = "Service not available";
                        $orderM->status = 'canceled';
                    }
                    $orderM->save();
                }
                $draftMassOrder = DraftMassOrder::where('user_id', auth()->id())->find($order->id ?? null);
                if ($draftMassOrder) {
                    $draftMassOrder->delete();
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }
        return redirect()->route('user.mass.order')->with('success', 'Mass order added successfully.');
    }

    public function sendNotification($user, $orderM, $transaction)
    {
        $this->sendMailSms($user, 'ORDER_CONFIRM', [
            'order_id' => $orderM->id,
            'order_at' => $orderM->created_at,
            'service' => optional($orderM->service)->service_title,
            'status' => $orderM->status,
            'paid_amount' => $orderM->price,
            'remaining_balance' => $user->balance,
            'currency' => basicControl()->currency,
            'transaction' => $transaction->trx_id,
        ]);
        $msg = ['username' => $user->username, 'order_id' => $orderM->id, 'price' => currencyPosition($orderM->price), 'currency' => basicControl()->currency];

        $action = [
            "link" => route('admin.order'),
            "image" => getFile($user->image_driver, $user->image),
            "icon" => "fas fa-cart-plus text-white"
        ];

        $this->adminPushNotification('ORDER_CREATE', $msg, $action);
        $this->adminFirebasePushNotification('ORDER_CREATE', $msg, route('admin.order'));
    }
}
