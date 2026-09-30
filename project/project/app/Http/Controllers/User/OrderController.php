<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\OrderStoreRequest;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Service;
use App\Models\SocialMedia;
use App\Models\Transaction;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Facades\App\Services\ApiOrderService;


class OrderController extends Controller
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

    public function create(Request $request)
    {
        try {
            $serviceId = $request->serviceId;
            if (isset($serviceId)) {
                $data['selectService'] = Service::where('service_status', 1)->userRate()->with('category', 'category.socialMedia')->find($serviceId);
            } else {
                $data['selectService'] = null;
            }
            $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
            $data['socialMedia'] = SocialMedia::has('category')->with('category')->orderBy('sort_by', 'asc')->get();
            return view(template() . 'user.order.add', $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function getCategoryService(Request $request): \Illuminate\Http\JsonResponse
    {
        $categories = Category::with(['service' => function ($query) {
            return $query->where('service_status', 1)->userRate();
        }])
            ->whereHas('service', function ($query) {
                $query->where('service_status', 1)->userRate()
                    ->whereHas('provider', function ($query) {
                        $query->where('status', 1);
                    })->orWhereDoesntHave('provider');
            })
            ->where('status', 1)
            ->when($request->social_media_id != 0, function ($query) use ($request) {
                return $query->where('social_media_id', $request->social_media_id);
            })
            ->get()
            ->map(function ($category) {
                foreach ($category->service as $service) {
                    preg_match('/Speed:\s*([^\]]+)/i', $service->service_title, $matches);
                    $speedRaw = $matches[1] ?? null;
                    $timeInMinutes = null;

                    if ($speedRaw) {
                        preg_match('/(\d+)(?:-(\d+))?\s*(Day|Hour|Minute)s?/i', $speedRaw, $parts);

                        if ($parts) {
                            $min = (int)$parts[1];
                            $max = isset($parts[2]) ? (int)$parts[2] : $min;
                            $unit = strtolower($parts[3]);

                            $average = ($min + $max) / 2;

                            switch ($unit) {
                                case 'day':
                                    $timeInMinutes = $average * 24 * 60;
                                    break;
                                case 'hour':
                                    $timeInMinutes = $average * 60;
                                    break;
                                case 'minute':
                                    $timeInMinutes = $average;
                                    break;
                            }
                        }
                    }

                    $service->average_time_minutes = $timeInMinutes ?? null;
                }

                return $category;
            });

        return response()->json($categories);
    }


    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $service = Service::userRate()->find($request->service);

            if (!$service) {
                DB::rollBack();
                return response([
                    'status' => 'error',
                    'message' => 'Selected service not found.'
                ]);
            }

            $quantity = $request->quantity ?? 0;

            if ($service->drip_feed == 1 && !isset($request->drip_feed)) {
                $quantity = $request->quantity * ($request->runs ?? 1);
            }

            $isQuantityBased = in_array($service->service_type, [
                'Default', 'Mentions', 'Mentions with Hashtags', 'Custom Comments', 'Mentions Custom List', 'Mentions Hashtag',
                'Mentions User Followers', 'Mentions Media Likers', 'Comment Likes', 'Poll', 'Invites from Groups', 'Web Traffic'
            ]);

            if ($isQuantityBased) {
                if ($quantity < $service->min_amount || $quantity > $service->max_amount) {
                    DB::rollBack();
                    return response([
                        'status' => 'error',
                        'message' => "Order quantity should be minimum {$service->min_amount} and maximum {$service->max_amount}"
                    ]);
                }
            }

            $userRate = $service->user_rate ?? $service->price;
            // Packages/subscriptions are a flat price. Never take the price from the request:
            // a customer could send 0 or a negative number and get free orders or extra balance.
            $price = $isQuantityBased
                ? round(($quantity * $userRate) / 1000, basicControl()->fraction_number)
                : round((float)$userRate, basicControl()->fraction_number);

            if ($price < 0) {
                DB::rollBack();
                return response(['status' => 'error', 'message' => 'Invalid price for this service.']);
            }

            $user = Auth::user();
            if ($user->balance < $price) {
                DB::rollBack();
                $missing = round($price - $user->balance, 2);
                return response([
                    'status' => 'error',
                    'code' => 'insufficient_balance',
                    'message' => 'Not enough balance for this order. You need ' . currencyPosition($missing) . ' more.',
                    'missing' => $missing,
                    'add_funds_url' => route('user.add.fund', ['amount' => max(1, ceil($missing))]),
                ]);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'category_id' => $data['category'],
                'service_id' => $data['service'],
                'link' => $data['link'] ?? null,
                'quantity' => $data['quantity'] ?? null,
                'status' => 'processing',
                'price' => $price,
                'order_type' => $data['order_type'] ?? 'Default',
                'comments' => $data['order_notes'] ?? null,
                'custom_comments' => $data['comments'] ?? null,
                'drip_feed' => (!empty($data['runs']) && !empty($data['interval'])) ? 1 : null,
                'runs' => $data['runs'] ?? null,
                'interval' => $data['interval'] ?? null,
            ]);

            if (!$order) {
                DB::rollBack();
                return response([
                    'status' => 'error',
                    'message' => 'Something went wrong creating the order.'
                ]);
            }

            ApiOrderService::placeOrder($service, $order, $data);

            $user->decrement('balance', $price);

            $transaction = Transaction::create([
                'transactional_id' => $order->id,
                'transactional_type' => Order::class,
                'user_id' => $user->id,
                'trx_type' => '-',
                'amount' => $price,
                'remarks' => 'Place order',
                'charge' => 0,
            ]);

            if (!$transaction) {
                DB::rollBack();
                return response([
                    'status' => 'error',
                    'message' => 'Transaction failed. Please try again.'
                ]);
            }

            $msg = [
                'username' => $user->username,
                'price' => currencyPosition($price),
                'order_id' => $order->id,
            ];

            $action = [
                'link' => route('admin.order'),
                'image' => getFile($user->image_driver, $user->image),
                'icon' => 'fas fa-cart-plus text-white'
            ];

            $this->adminPushNotification('ORDER_CREATE', $msg, $action);
            $this->adminFirebasePushNotification('ORDER_CREATE', $msg, route('admin.order'));

            $this->sendMailSms($user, 'ORDER_CONFIRM', [
                'order_id' => $order->id,
                'order_at' => $order->created_at,
                'service' => optional($order->service)->service_title,
                'status' => $order->status,
                'paid_amount' => $price,
                'remaining_balance' => $user->balance,
                'currency' => basicControl()->currency,
                'transaction' => $transaction->trx_id,
            ]);

            DB::commit();

            return response([
                'status' => 'success',
                'message' => 'Your order has been submitted successfully.'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ]);
        }
    }

    public function index(Request $request, $status = null)
    {
        $search = $request->all();
        $currency = Currency::where('code', auth()->user()->currency)->first();

        $ordersQuery = Order::with([
            'service:id,service_title,service_status,description,refill'
        ])
            ->where('user_id', auth()->id())
            ->when(!empty($status), fn($q) => $q->where('status', $status))
            ->when(!empty($search['order_id']), fn($q) => $q->where('id', $search['order_id']))
            ->when(!empty($search['service']), function ($q) use ($search) {
                $q->whereHas('service', fn($s) => $s->where('service_title', 'like', '%' . $search['service'] . '%'));
            })
            ->when(!empty($search['status']), fn($q) => $q->where('status', $search['status']))
            ->when(!empty($search['from_date']) && !empty($search['to_date']), fn($q) => $q->whereBetween('created_at', [$search['from_date'], $search['to_date']])
            )
            ->when(!empty($search['from_date']) && empty($search['to_date']), fn($q) => $q->whereDate('created_at', $search['from_date'])
            )
            ->when(empty($search['from_date']) && !empty($search['to_date']), fn($q) => $q->whereDate('created_at', $search['to_date'])
            )
            ->orderByDesc('id');

        $orders = $ordersQuery->paginate(15);
        return view(template() . 'user.order.index', compact('orders', 'currency'));
    }

    public function showOrderRefill(Request $request, $status = null): view
    {
        $search = $request->all();
        $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
        $data['orders'] = Order::with(['service:id,service_title,service_status,description,refill'])
            ->where('user_id', Auth::id())
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($search['order_id']), function ($query) use ($search) {
                return $query->where('id', $search['order_id']);
            })
            ->when(!empty($search['service']), function ($query) use ($search) {
                $query->whereHas('service', function ($qry) use ($search) {
                    return $qry->where('service_title', 'like', '%' . $search['service'] . '%');
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->whereNotNull('refill_status')
            ->whereNotNull('refilled_at')
            ->orderBy('id', 'desc')->paginate(15);
        return view(template() . 'user.order.show_order_refill', $data);
    }

    public function orderRefill(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $order = Order::with('service', 'service.provider')->findOrFail($id);
        try {
            if ($order->canBeRefilled()) {

                if (optional($order->service)->is_refill_automatic == 1) {

                    if (optional(optional($order->service)->provider)->status != 1) {
                        return back()->with('error', 'You are not eligible to send refill request.');
                    }

                    $response = Http::post(optional(optional($order->service)->provider)->url, [
                        'key' => optional(optional($order->service)->provider)->api_key,
                        'action' => 'refill',
                        'order' => $order->api_order_id,
                    ]);

                    $res = $response->json();
                    if (isset($res['refill'])) {
                        $order->update([
                            'api_refill_id' => $res['refill'],
                            'refill_status' => 'pending',
                            'refilled_at' => now(),
                        ]);
                    } else {
                        return back()->with('error', 'You are not eligible to send refill request.');
                    }
                } else {
                    $order->update([
                        'refill_status' => 'pending',
                        'refilled_at' => now(),
                    ]);
                }
            } else {
                return back()->with('error', 'You are not eligible to send refill request.');
            }
            return back()->with('success', 'Refill request has been submitted');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function showDripFeed(Request $request, $status = null)
    {
        $search = $request->all();
        $data['currency'] = Currency::where('code', auth()->user()->currency)->first();
        $data['orders'] = Order::with(['service'])
            ->where('drip_feed', '!=', NULL)
            ->orderBy('id', 'desc')->where('user_id', auth()->user()->id)
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(!empty($search['order_id']), function ($query) use ($search) {
                return $query->where('id', $search['order_id']);
            })
            ->when(!empty($search['service']), function ($query) use ($search) {
                $query->whereHas('service', function ($qry) use ($search) {
                    return $qry->where('service_title', 'like', '%' . $search['service'] . '%');
                });
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereBetween('created_at', [$search['from_date'], $search['to_date']]);
            })
            ->when(isset($search['from_date']) && !isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', isset($search['from_date']));
            })
            ->when(!isset($search['from_date']) && isset($search['to_date']), function ($query) use ($search) {
                return $query->whereDate('created_at', $search['to_date']);
            })
            ->paginate(15);
        return view(template() . 'user.order.show_drip_feed_order', $data);
    }
}
