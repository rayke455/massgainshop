<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Models\Order;
use App\Models\Service;
use App\Models\Transaction;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Facades\App\Services\ApiOrderService;


class OrderController extends Controller
{
    use Notify;

    public function index($status = 'list')
    {
        $orderRecord = \Cache::get('orderRecord');
        if (!$orderRecord) {
            $orderRecord = Order::whereNull('child_panel_id')->selectRaw('COUNT(id) AS totalOrder')
                ->selectRaw('COUNT(CASE WHEN status = "awaiting" THEN 1 END) AS awaitingOrder')
                ->selectRaw('COUNT(CASE WHEN status = "pending" THEN 1 END) AS pendingOrder')
                ->selectRaw('COUNT(CASE WHEN status = "processing" THEN 1 END) AS processingOrder')
                ->selectRaw('COUNT(CASE WHEN status = "progress" THEN 1 END) AS progressOrder')
                ->selectRaw('COUNT(CASE WHEN status = "completed" THEN 1 END) AS completedOrder')
                ->selectRaw('COUNT(CASE WHEN status = "partial" THEN 1 END) AS partialOrder')
                ->selectRaw('COUNT(CASE WHEN status = "canceled" THEN 1 END) AS canceledOrder')
                ->selectRaw('COUNT(CASE WHEN status = "refunded" THEN 1 END) AS refundedOrder')
                ->first();
            \Cache::put('orderRecord', $orderRecord);
        }
        return view('admin.orders.list', compact('status', 'orderRecord'));
    }

    public function show(Request $request, $status)
    {
        $search = data_get($request->search, 'value'); // null-safe when the table sends no search box
        $filterUser = $request->filterUser;
        $filterOrderId = $request->filterOrderId;
        $filterService = $request->filterService;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->date);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $orders = Order::with('users:id,firstname,lastname,username,email,image,image_driver', 'service')
            ->orderBy('id', 'desc')
            ->whereNull('child_panel_id')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->whereHas('user', function ($q) use ($search) {
                        $q->where('firstname', 'LIKE', "%$search%");
                        $q->orWhere('lastname', 'LIKE', "%$search%");
                        $q->orWhere('username', 'LIKE', "%$search%");
                        $q->orWhere('email', 'LIKE', "%$search%");
                    });
                });
            })
            ->when($status == 'awaiting', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'pending', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'processing', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'progress', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'completed', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'partial', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'canceled', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'refunded', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when($status == 'fail', function ($query) use ($status) {
                return $query->where('status', 'like', "%$status%");
            })
            ->when(!empty($filterUser), function ($query) use ($filterUser) {
                return $query->whereHas('users', function ($q) use ($filterUser) {
                    $q->where('firstname', 'LIKE', "%$filterUser%");
                    $q->orWhere('lastname', 'LIKE', "%$filterUser%");
                    $q->orWhere('username', 'LIKE', "%$filterUser%");
                    $q->orWhere('email', 'LIKE', "%$filterUser%");
                });
            })
            ->when(isset($filterOrderId), function ($query) use ($filterOrderId) {
                return $query->where('id', (int)$filterOrderId);
            })
            ->when(!empty($filterService), function ($query) use ($filterService) {
                $query->whereHas('service', function ($q) use ($filterService) {
                    $q->where('service_title', 'LIKE', "%$filterService%");
                });
            })
            ->when(isset($filterStatus) && $filterStatus != 'list', function ($query) use ($filterStatus) {
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($orders)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('order_id', function ($item) {
                $statusClass = ($item->status == "refunded" || $item->status == "canceled") ? "text-danger" : "text-dark";
                $toolTipText = $item->reason ?? ucfirst($item->status) . ' order';
                $iconTooltip = null;
                if ($item->status == "refunded" || $item->status == "canceled") {
                    $iconTooltip = '<i class="bi-exclamation-diamond-fill text-warning" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="' . $toolTipText . '"></i>';
                }
                return '<span class="' . $statusClass . '">' . '#' . $item->id . '<span> ' . $iconTooltip . '</span></span>';
            })
            ->addColumn('user', function ($item) {
                $url = route('admin.user.view.profile', optional($item->users)->id);
                return '<a class="d-flex align-items-center" href="' . $url . '">
                                <div class="flex-shrink-0">
                                    <img class="avatar avatar-sm" src="' . getFile(optional($item->users)->image_driver, optional($item->users)->image) . '"
                                         alt="Image Description">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="text-inherit mb-0">' . $item->users->firstname . ' ' . $item->users->lastname . '</h5>
                                    <span class="fs-6 text-body">' . "@" . $item->users->username . '</span>
                                </div>
                            </a>';
            })
            ->addColumn('order_details', function ($item) {
                $startCounter = $item->start_counter ?? 'N/A';
                $remains = $item->remains ?? 'N/A';
                $quantity = $item->quantity ?? 'N/A';
                return '<span class="mb-0">' . Str::limit(optional($item->service)->service_title, 40) . '</span><br>
                        <span>' . trans("Link: ") . Str::limit($item->link, 30) . '</span><br>
                        <span>' . trans("Price: ") . currencyPosition($item->price) . '</span><br>
                        <span>' . trans("Quantity: ") . $quantity . '</span><br>
                        <span>' . trans("Start Counter: ") . $startCounter . '</span><br>
                        <span>' . trans("Remains: ") . $remains . '</span><br>';
            })
            ->addColumn('order_type', function ($item) {
                if (optional($item->service)->api_provider_id) {
                    return '<span class="badge bg-soft-secondary text-primary">API</span>';
                } else {
                    return '<span class="badge bg-soft-dark text-dark">
                        Manual
                      </span>';
                }
            })
            ->addColumn('service_type', function ($item) {
                $serviceType = optional($item->service)->service_type ?? "Default";
                return '<span class="badge bg-soft-secondary text-secondary">' . $serviceType . '</span>';
            })
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('status', function ($item) {

                $refill = null;
                if (isset($item->refill_status) &&
                    !in_array($item->refill_status, ['completed', 'partial', 'canceled', 'refunded'])) {
                    $refill = '<span class="badge bg-warning text-dark ms-2">' . trans('Refilling') . '</span>';
                }

                if ($item->status == 'awaiting') {
                    return '<span class="badge bg-soft-dark text-dark">
                    <span class="legend-indicator bg-dark"></span>' . trans('Awaiting') . '
                  </span>';
                } elseif ($item->status == 'pending') {
                    return '<span class="badge bg-soft-warning text-warning">
                    <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                  </span>';
                } elseif ($item->status == 'processing') {
                    return '<span class="badge bg-soft-info text-info">
                    <span class="legend-indicator bg-info"></span>' . trans('Processing') . '
                  </span>';
                } elseif ($item->status == 'progress') {
                    return '<span class="badge bg-soft-primary text-primary">
                    <span class="legend-indicator bg-primary"></span>' . trans('In progress') . '
                  </span>';
                } elseif ($item->status == 'completed') {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Completed') . '
                  </span>' . $refill;
                } elseif ($item->status == 'partial') {
                    return '<span class="badge bg-soft-secondary text-secondary">
                    <span class="legend-indicator bg-secondary"></span>' . trans('Partial') . '
                  </span>';
                } elseif ($item->status == 'canceled') {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Canceled') . '
                  </span>' > $refill;
                } elseif ($item->status == 'refunded') {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Refunded') . '
                  </span>';
                } elseif ($item->status == 'fail') {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Fail') . '
                  </span>' . $refill;
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.order.edit', $item->id);
                $condition = isset($order->refill_status) && ($order->refill_status != 'completed' || $order->refill_status != 'partial' || $order->refill_status != 'canceled' || $order->refill_status != 'refunded');
                $route = route('admin.order.delete', $item->id);
                $statusChangeRoute = route('admin.order.status.change', $item->id);
                $refillHtml = '';
                if ($condition) {
                    $refillHtml = '<a class="dropdown-item" href="#">
                          <i class="bi-archive dropdown-item-icon"></i> ' . trans("Change Refill Status") . '
                        </a>';
                }
                return '<div class="btn-group" role="group">
                    <a class="btn btn-white btn-sm" href="' . $editUrl . '">
                        <i class="bi-pencil-fill me-1"></i>' . trans('Edit') . '
                    </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="ordersExportDropdown1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="ordersExportDropdown1">

                       <a class="dropdown-item openOrderSummary" href="javascript:void(0)" data-bs-toggle="modal"
                       data-order="' . htmlentities(json_encode($item), ENT_QUOTES, "UTF-8") . '"
                       data-bs-target="#orderSummeryModal">
                          <i class="fal fa-list dropdown-item-icon"></i>
                            ' . trans("Order Summary") . '
                        </a>

                        <a class="dropdown-item status-change" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#statusChangeModal"
                            data-route="' . $statusChangeRoute . '">
                          <i class="bi bi-arrow-down-right-square dropdown-item-icon"></i>
                            ' . trans("Status Change") . '
                        </a>


                         <a class="dropdown-item orderResendBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#orderResendModal"
                            data-route="' . route('admin.order.resend', $item->id) . '">
                          <i class="fa-light fa-paper-plane-top dropdown-item-icon"></i>
                            ' . trans("Resend Order") . '
                        </a>


                       <a class="dropdown-item deleteBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#singleDeleteModal"
                            data-route="' . $route . '">
                          <i class="bi-trash dropdown-item-icon"></i> ' . trans("Delete") . '
                        </a>
                        ' . $refillHtml . '
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['checkbox', 'order_id', 'service_type', 'user', 'order_type', 'order_details', 'status', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        try {
            $order = Order::where('id', $id)->firstOr(function () {
                throw new \Exception('Order not available.');
            });
            return view('admin.orders.edit', compact('order'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(OrderUpdateRequest $request, $id)
    {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            $order = Order::with('users')->where('id', $id)->firstOr(function () {
                throw new \Exception('Order is not available.');
            });

            if (isset($order->refilled_at) && $data['refill_status']) {
                $refill_status = $data['refill_status'];
            }

            $order->update([
                'start_counter' => $data['start_counter'] == '' ? null : $data['start_counter'],
                'link' => $data['link'],
                'remains' => $data['remains'] == '' ? null : $data['remains'],
                'status' => $data['status'],
                'refill_status' => $refill_status ?? null,
                'reason' => $data['reason'],
                'completed_at' => now() ?? null
            ]);

            if (($order->status == 'partial' || $order->status == 'refunded') && ($order->remains != 0 || $order->remains == null)) {

                $perOrder = $order->price / $order->quantity;
                $remains = $order->remains ?? 1;
                $getBackAmount = $remains * $perOrder;

                if ($order->users) {
                    $order->users->balance += $getBackAmount;
                    $order->users->save();
                }

                Transaction::create([
                    'transactional_id' => $order->id,
                    'transactional_type' => Order::class,
                    'user_id' => optional($order->users)->id,
                    'trx_type' => '+',
                    'amount' => $getBackAmount,
                    'remarks' => 'Refunded order on #' . $order->id,
                    'charge' => 0
                ]);
            }

            DB::commit();
            return back()->with('success', 'Order successfully updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function multiStatusChange(Request $request)
    {
        if ($request->strIds === null) {
            session()->flash('error', 'You did not select any service.');
            return response()->json(['error' => 1]);
        } else {
            Order::whereIn('id', $request->strIds)->each(function ($order) use ($request) {

                if (($order->status == 'partial' || $order->status == 'refunded') && ($order->remains != 0 || $order->remains == null)) {
                    $perOrder = $order->price / $order->quantity;
                    $getBackAmount = $order->remains > 0 ? $order->remains * $perOrder : $order->price;

                    if ($order->users) {
                        $order->users->balance += $getBackAmount;
                        $order->users->save();
                    }
                }

                $order->update([
                    'status' => $request->status,
                    'completed_at' => $request->status == 'completed' ? now() : null
                ]);
            });
            session()->flash('success', 'Order have been updated successfully.');
            return response()->json(['success' => 1]);
        }
    }

    public function statusChange(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::with('users')->findOrFail($id);
            $order->update([
                'status' => $request->status,
                'completed_at' => $request->status == 'completed' ? now() : null
            ]);

            if (($order->status == 'partial' || $order->status == 'refunded') && ($order->remains != 0 || $order->remains == null)) {

                $perOrder = $order->price / $order->quantity;
                $getBackAmount = $order->remains > 0 ? $order->remains * $perOrder : $order->price;

                if ($order->users) {
                    $order->users->balance += $getBackAmount;
                    $order->users->save();
                }

                Transaction::create([
                    'transactional_id' => $order->id,
                    'transactional_type' => Order::class,
                    'user_id' => optional($order->users)->id,
                    'trx_type' => '+',
                    'amount' => $getBackAmount,
                    'remarks' => 'Refunded order on #' . $order->id,
                    'charge' => 0
                ]);
            }

            DB::commit();
            return back()->with('success', 'Order status change successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return back()->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function resend($id)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('users')->findOrFail($id);
            $service = Service::userRate()->findOrFail($order->service_id);
            $user = $order->users;

            $quantity = $order->quantity ?? $order->quantity;
            if ($service->drip_feed && empty($order->drip_feed)) {
                $quantity *= ($order->runs ?? $order->runs ?? 1);
            }

            $isQuantityBased = in_array($service->service_type, [
                'Default', 'Mentions', 'Mentions with Hashtags', 'Custom Comments',
                'Mentions Custom List', 'Mentions Hashtag', 'Mentions User Followers',
                'Mentions Media Likers', 'Comment Likes', 'Poll', 'Invites from Groups', 'Web Traffic'
            ]);

            if ($isQuantityBased && ($quantity < $service->min_amount || $quantity > $service->max_amount)) {
                return $this->error("Order quantity must be between {$service->min_amount} and {$service->max_amount}");
            }

            $price = $isQuantityBased
                ? round(($quantity * ($service->user_rate ?? $service->price)) / 1000, basicControl()->fraction_number)
                : ($order->price ?? 0);

            if ($user->balance < $price) {
                return $this->error('Insufficient balance in your wallet.');
            }

            $newOrder = Order::create([
                'user_id' => $user->id,
                'category_id' => $order->category_id,
                'service_id' => $order->service_id,
                'link' => $order->link,
                'quantity' => $order->quantity,
                'status' => 'processing',
                'price' => $price,
                'comments' => $order->comments,
                'custom_comments' => $order->custom_comments,
                'runs' => $order->runs,
                'interval' => $order->interval,
            ]);

            if (!$newOrder) {
                return $this->error('Something went wrong while creating the order.');
            }

            ApiOrderService::resendPlaceOrder($service, $order, $newOrder);

            $user->decrement('balance', $price);

            $transaction = Transaction::create([
                'transactional_id' => $order->id,
                'transactional_type' => Order::class,
                'user_id' => $user->id,
                'trx_type' => '-',
                'amount' => $price,
                'remarks' => 'Place order',
                'charge' => 0
            ]);

            if (!$transaction) {
                DB::rollBack();
                return $this->error('Transaction failed. Please try again.');
            }

            $msg = [
                'username' => $user->username,
                'price' => currencyPosition($price),
                'order_id' => $order->id,
            ];

            $this->adminPushNotification('ORDER_CREATE', $msg, [
                "link" => route('admin.order'),
                "image" => getFile($user->image_driver, $user->image),
                "icon" => "fas fa-cart-plus text-white"
            ]);

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
            return back()->with('success', 'Order resent successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function error($message)
    {
        DB::rollBack();
        return response(['status' => 'error', 'message' => $message]);
    }

}
