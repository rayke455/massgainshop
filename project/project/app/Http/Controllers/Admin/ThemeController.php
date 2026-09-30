<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ThemeController extends Controller
{
    public function index()
    {
        return view('admin.frontend_management.manage-theme');
    }

    public function selectTheme(Request $request)
    {
        $theme = $request->input('theme');
        if(!in_array($theme,array_keys(config('themes')))){
            return response()->json(['error'=>"Invalid Request"],422);
        }

        $basic = BasicControl::firstOrCreate();
        $basic->theme = $theme;
        $basic->save();

        $message = request()->theme_name .' theme selected.';
        Artisan::call('cache:clear');
        return response()->json(['message' => $message], 200);
    }

    public function userPanels()
    {
        return view('admin.frontend_management.manage-user-dashboard');
    }

    public function selectUserPanel(Request $request)
    {
        $dashboard = $request->input('dashboard');
        if(!in_array($dashboard,array_keys(config('userdashboard')))){
            return response()->json(['error'=>"Invalid Request"],422);
        }

        $basic = BasicControl::firstOrCreate();
        $basic->user_dashboard = $dashboard;
        $basic->save();

        $message = request()->dashboard_name .' dashboard selected.';
        return response()->json(['message' => $message], 200);
    }
}
