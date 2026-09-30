<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserKyc;
use App\Traits\GetChildPanel;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    use Upload, Notify, GetChildPanel;

    public function index()
    {
        $type = 'main_dashboard';
        $data['firebaseNotify'] = config('firebase');
        $data['latestUser'] = User::query()->whereNull('child_panel_id')->latest()->limit(5)->get();
        $statistics['schedule'] = $this->dayList();
        $data['monthlySchedule'] = $this->monthly();

        $data['bestSales'] = Order::with('service')
            ->whereHas('service')
            ->selectRaw('service_id ,COUNT(service_id) as count, sum(quantity) as quantity')
            ->groupBy('service_id')->orderBy('count', 'DESC')->take(10)->get();

        $data['categories'] = Category::where('status', 1)->get();
        return view('admin.dashboard-alternative', $data, compact("statistics", 'type'));
    }

    public function monthlyDepositWithdraw(Request $request)
    {
        $keyDataset = $request->keyDataset;
        $type = $request->type;
        $dailyDeposit = $this->dayList();

        Deposit::when($type == 'child_panel_dashboard', function ($query) {
            $query->whereNotNull('child_panel_id');
        })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when($keyDataset == '0', function ($query) {
                $query->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($keyDataset == '1', function ($query) {
                $lastMonth = Carbon::now()->subMonth();
                $query->whereMonth('created_at', $lastMonth->month);
            })
            ->where('status', '1')
            ->select(
                DB::raw('SUM(payable_amount_in_base_currency) as totalDeposit'),
                DB::raw('DATE_FORMAT(created_at,"Day %d") as date')
            )
            ->groupBy(DB::raw("DATE(created_at)"))
            ->get()->map(function ($item) use ($dailyDeposit) {
                $dailyDeposit->put($item['date'], $item['totalDeposit']);
            });

        return response()->json([
            "totalDeposit" => currencyPosition($dailyDeposit->sum()),
            "dailyDeposit" => $dailyDeposit,
        ]);
    }


    public function monthlyOrderShow(Request $request)
    {

        $category = $request->category ?? null;
        $type = $request->type;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $diffInDays = $startDate->diffInDays($endDate);

        $groupBy = $diffInDays <= 1 ? "HOUR(created_at)" : "DATE(created_at)";

        $orders = Order::query()
            ->when($type === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->when($type === 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->select([
                DB::raw("$groupBy as group_key"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(price) as total_amount'),
                DB::raw('MIN(created_at) as created_at'),
            ])
            ->when($category, function ($query) use ($category) {
                if ($category !== 'all') {
                    $query->where('category_id', $category);
                }
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('group_key')
            ->orderBy('group_key', 'asc')
            ->get();


        $data = [
            'labels' => [],
            'totalOrder' => [],
            'totalAmount' => []
        ];

        $dateFormat = $diffInDays <= 1 ? 'Y-m-d H' : 'Y-m-d';
        $displayFormat = $diffInDays <= 1 ? 'd M H:i' : 'd M';

        $totalOrder = $orders->sum('total_orders');
        $totalAmount = $orders->sum('total_amount');
        $period = Carbon::parse($startDate)->daysUntil($endDate->copy()->addDay());

        foreach ($period as $date) {
            $formattedKey = $date->format($dateFormat);
            $found = $orders->firstWhere('group_key', $formattedKey);

            $data['labels'][] = $date->format($displayFormat);
            $data['totalOrder'][] = $found ? $found->total_orders : 0;
            $data['totalAmount'][] = $found ? $found->total_amount : 0;
        }

        return response()->json([
            'labels' => $data['labels'],
            'order' => $data['totalOrder'],
            'amount' => $data['totalAmount'],
            'totalOrderInRange' => $totalOrder,
            'totalAmountInRange' => currencyPosition($totalAmount),
        ]);
    }


    public function saveToken(Request $request)
    {
        Auth::guard('admin')->user()
            ->fireBaseToken()
            ->create([
                'token' => $request->token,
            ]);
        return response()->json([
            'msg' => 'token saved successfully.',
        ]);
    }


    public function dayList()
    {
        $totalDays = Carbon::now()->endOfMonth()->format('d');
        $daysByMonth = [];
        for ($i = 1; $i <= $totalDays; $i++) {
            array_push($daysByMonth, ['Day ' . sprintf("%02d", $i) => 0]);
        }

        return collect($daysByMonth)->collapse();
    }

    public function monthly()
    {
        $month = Carbon::now()->format('M');
        $totalDays = Carbon::now()->endOfMonth()->format('d');
        $daysByMonth = [];

        for ($i = 1; $i <= $totalDays; $i++) {
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);
            $daysByMonth["$month $day"] = 0;
        }
        return collect($daysByMonth);
    }

    protected function followupGrap($todaysRecords, $lastDayRecords = 0)
    {
        if (0 < $lastDayRecords) {
            $percentageIncrease = (($todaysRecords - $lastDayRecords) / $lastDayRecords) * 100;
        } else {
            $percentageIncrease = 0;
        }
        if ($percentageIncrease > 0) {
            $class = "bg-soft-success text-success";
        } elseif ($percentageIncrease < 0) {
            $class = "bg-soft-danger text-danger";
        } else {
            $class = "bg-soft-secondary text-body";
        }

        return [
            'class' => $class,
            'percentage' => round($percentageIncrease, 2)
        ];
    }


    public function chartUserRecords(Request $request)
    {
        $type = $request->type;
        $currentMonth = Carbon::now()->format('Y-m');
        $userRecord = collect(User::selectRaw('COUNT(id) AS totalUsers')
            ->when($type == 'child_panel', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateUserCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateUserCount')
            ->get()->makeHidden(['last-seen-activity', 'fullname'])
            ->toArray())->collapse();
        $followupGrap = $this->followupGrap($userRecord['currentDateUserCount'], $userRecord['previousDateUserCount']);

        $userRecord->put('followupGrapClass', $followupGrap['class']);
        $userRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('users')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $userRecord['chartPercentageIncDec'] = fractionNumber($userRecord['totalUsers'] - $userRecord['currentDateUserCount'], false);
        return response()->json(['userRecord' => $userRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartTicketRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $ticketRecord = collect(SupportTicket::whereNull('child_panel_id')->selectRaw('COUNT(id) AS totalTickets')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateTicketsCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateTicketsCount')
            ->selectRaw('count(CASE WHEN status = 2  THEN status END) AS replied')
            ->selectRaw('count(CASE WHEN status = 1  THEN status END) AS answered')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pending')
            ->get()
            ->toArray())->collapse();

        $followupGrap = $this->followupGrap($ticketRecord['currentDateTicketsCount'], $ticketRecord['previousDateTicketsCount']);
        $ticketRecord->put('followupGrapClass', $followupGrap['class']);
        $ticketRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('support_tickets')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $ticketRecord['chartPercentageIncDec'] = fractionNumber($ticketRecord['totalTickets'] - $ticketRecord['currentDateTicketsCount'], false);
        return response()->json(['ticketRecord' => $ticketRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartKycRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $kycRecords = collect(UserKyc::selectRaw('COUNT(id) AS totalKYC')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateKYCCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateKYCCount')
            ->selectRaw('count(CASE WHEN status = 0  THEN status END) AS pendingKYC')
            ->get()
            ->toArray())->collapse();
        $followupGrap = $this->followupGrap($kycRecords['currentDateKYCCount'], $kycRecords['previousDateKYCCount']);
        $kycRecords->put('followupGrapClass', $followupGrap['class']);
        $kycRecords->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('user_kycs')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $kycRecords['chartPercentageIncDec'] = fractionNumber($kycRecords['totalKYC'] - $kycRecords['currentDateKYCCount'], false);
        return response()->json(['kycRecord' => $kycRecords, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartTransactionRecords(Request $request)
    {
        $type = $request->type;
        $currentMonth = Carbon::now()->format('Y-m');

        $transaction = collect(Transaction::selectRaw('COUNT(id) AS totalTransaction')
            ->when($type == 'child_panel', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateTransactionCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateTransactionCount')
            ->whereRaw('YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())')
            ->get()
            ->toArray())
            ->collapse();

        $followupGrap = $this->followupGrap($transaction['currentDateTransactionCount'], $transaction['previousDateTransactionCount']);
        $transaction->put('followupGrapClass', $followupGrap['class']);
        $transaction->put('followupGrap', $followupGrap['percentage']);


        $current_month_data = DB::table('transactions')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $transaction['chartPercentageIncDec'] = fractionNumber($transaction['totalTransaction'] - $transaction['currentDateTransactionCount'], false);
        return response()->json(['transactionRecord' => $transaction, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function chartBrowserHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsBrowserData = $userLoginsData->groupBy('browser')->map->count();
        $data['browserKeys'] = $userLoginsBrowserData->keys();
        $data['browserValue'] = $userLoginsBrowserData->values();

        return response()->json(['browserPerformance' => $data]);
    }

    public function chartOsHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsOSData = $userLoginsData->groupBy('os')->map->count();
        $data['osKeys'] = $userLoginsOSData->keys();
        $data['osValue'] = $userLoginsOSData->values();

        return response()->json(['osPerformance' => $data]);
    }

    public function chartDeviceHistory(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $panelType = $request->panelType;

        $userLoginsData = DB::table('user_logins')
            ->when(isset($panelType) && $panelType === 'main_dashboard', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->when(isset($panelType) && $panelType === 'child_panel_dashboard', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('browser', 'os', 'get_device')
            ->get();

        $userLoginsDeviceData = $userLoginsData->groupBy('get_device')->map->count();
        $data['deviceKeys'] = $userLoginsDeviceData->keys();
        $data['deviceValue'] = $userLoginsDeviceData->values();

        return response()->json(['deviceHistory' => $data]);
    }

    public function orderRecords(Request $request)
    {
        $type = $request->type;
        $currentMonth = Carbon::now()->format('Y-m');
        $orderRecord = collect(Order::when($type == 'child_panel', function ($query) {
            $query->whereNotNull('child_panel_id');
        })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })->selectRaw('COUNT(id) AS totalOrders')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateOrderCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateOrderCount')
            ->get()->toArray())->collapse();


        $followupGrap = $this->followupGrap($orderRecord['currentDateOrderCount'], $orderRecord['previousDateOrderCount']);

        $orderRecord->put('followupGrapClass', $followupGrap['class']);
        $orderRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('orders')
            ->when($type == 'child_panel', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $orderRecord['chartPercentageIncDec'] = fractionNumber($orderRecord['totalOrders'] - $orderRecord['currentDateOrderCount'], false);
        return response()->json(['orderRecord' => $orderRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }


    public function pendingOrderRecords(Request $request)
    {
        $type = $request->type;
        $currentMonth = Carbon::now()->format('Y-m');
        $pendingOrderRecord = collect(Order::when($type == 'child_panel', function ($query) {
            $query->whereNotNull('child_panel_id');
        })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->selectRaw('COUNT(id) AS totalOrders')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDatePendingOrderCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDatePendingOrderCount')
            ->selectRaw('count(CASE WHEN status = "pending"  THEN status END) AS pendingOrderCount')
            ->get()->toArray())->collapse();

        $followupGrap = $this->followupGrap($pendingOrderRecord['currentDatePendingOrderCount'], $pendingOrderRecord['previousDatePendingOrderCount']);

        $pendingOrderRecord->put('followupGrapClass', $followupGrap['class']);
        $pendingOrderRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('orders')
            ->when($type == 'child_panel', function ($query) {
                $query->whereNotNull('child_panel_id');
            })
            ->when($type == 'main_panel', function ($query) {
                $query->whereNull('child_panel_id');
            })
            ->where('status', 'pending')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();


        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $pendingOrderRecord['chartPercentageIncDec'] = fractionNumber($pendingOrderRecord['totalOrders'] - $pendingOrderRecord['currentDatePendingOrderCount'], false);
        return response()->json(['pendingOrderRecord' => $pendingOrderRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function completedOrderRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $completedOrderRecord = collect(Order::selectRaw('COUNT(id) AS totalOrders')->whereNull('child_panel_id')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateOrderCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateOrderCount')
            ->selectRaw('count(CASE WHEN status = "completed"  THEN status END) AS completedOrderCount')
            ->get()->toArray())->collapse();

        $followupGrap = $this->followupGrap($completedOrderRecord['currentDateOrderCount'], $completedOrderRecord['previousDateOrderCount']);

        $completedOrderRecord->put('followupGrapClass', $followupGrap['class']);
        $completedOrderRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('orders')->whereNull('child_panel_id')
            ->where('status', 'completed')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();


        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $completedOrderRecord['chartPercentageIncDec'] = fractionNumber($completedOrderRecord['totalOrders'] - $completedOrderRecord['currentDateOrderCount'], false);
        return response()->json(['completedOrderRecord' => $completedOrderRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }


    public function canceledOrderRecords()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $canceledOrderRecord = collect(Order::selectRaw('COUNT(id) AS totalOrders')->whereNull('child_panel_id')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS currentDateCanceledOrderCount')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) THEN id END) AS previousDateCanceledOrderCount')
            ->selectRaw('count(CASE WHEN status = "canceled"  THEN status END) AS canceledOrderCount')
            ->get()->toArray())->collapse();

        $followupGrap = $this->followupGrap($canceledOrderRecord['currentDateCanceledOrderCount'], $canceledOrderRecord['previousDateCanceledOrderCount']);

        $canceledOrderRecord->put('followupGrapClass', $followupGrap['class']);
        $canceledOrderRecord->put('followupGrap', $followupGrap['percentage']);

        $current_month_data = DB::table('orders')->whereNull('child_panel_id')
            ->where('status', 'canceled')
            ->select(DB::raw('DATE_FORMAT(created_at,"%e %b") as date'), DB::raw('count(*) as count'))
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $currentMonth)
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();


        $current_month_data_dates = $current_month_data->pluck('date');
        $current_month_datas = $current_month_data->pluck('count');
        $canceledOrderRecord['chartPercentageIncDec'] = fractionNumber($canceledOrderRecord['totalOrders'] - $canceledOrderRecord['currentDateCanceledOrderCount'], false);
        return response()->json(['canceledOrderRecord' => $canceledOrderRecord, 'current_month_data_dates' => $current_month_data_dates, 'current_month_datas' => $current_month_datas]);
    }

    public function socialMediaBestSellerService(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $socialMediaBestSale = DB::table('orders')
            ->whereNull('child_panel_id')
            ->join('categories', 'orders.category_id', '=', 'categories.id')
            ->join('social_media', 'categories.social_media_id', '=', 'social_media.id')
            ->select('social_media.name as social_media_name', DB::raw('SUM(orders.price) as total_order_value'))
            ->groupBy('social_media.id', 'social_media.name')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->orderByDesc('total_order_value')
            ->get();

        $data['socialMediaNames'] = $socialMediaBestSale->pluck('social_media_name');
        $data['socialMediaOrderValue'] = $socialMediaBestSale->pluck('total_order_value');
        $data['sumTotalAmount'] = currencyPosition($socialMediaBestSale->pluck('total_order_value')->sum() ?? 0);

        return response()->json(['socialMediaBestSeller' => $data]);
    }
}
