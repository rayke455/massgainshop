<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChildPanelDashboardController extends Controller
{
    public function childPanelDashboard()
    {
        $type = 'child_panel_dashboard';
        $data['latestUser'] = User::whereNotNull('child_panel_id')->latest()->limit(5)->get();
        $statistics['schedule'] = $this->dayList();
        $data['monthlySchedule'] = $this->monthly();
        return view('admin.child-panel-dashboard', $data, compact('statistics', 'type'));
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
}
