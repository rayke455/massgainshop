<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\ThemeColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Facades\App\Services\BasicService;
use Facades\App\Services\CurrencyLayerService;
use Exception;

class BasicControlController extends Controller
{
    public function index($settings = null)
    {
        $settings = $settings ?? 'settings';
        abort_if(!in_array($settings, array_keys(config('generalsettings'))), 404);
        $settingsDetails = config("generalsettings.{$settings}");
        return view('admin.control_panel.settings', compact('settings', 'settingsDetails'));
    }

    public function basicControl()
    {
        $data['basicControl'] = basicControl();
        $data['timeZones'] = timezone_identifiers_list();
        $data['dateFormat'] = config('dateformat');
        return view('admin.control_panel.basic_control', $data);
    }

    public function basicControlUpdate(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|min:1|max:100',
            'time_zone' => 'required|string',
            'base_currency' => 'required|string|min:1|max:100',
            'currency_symbol' => 'required|string|min:1|max:100',
            'fraction_number' => 'required|integer|not_in:0',
            'paginate' => 'required|integer|not_in:0',
            'date_format' => 'required|string',
            'admin_prefix' => 'required|string|min:3|max:100',
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'site_title' => $request->site_title,
                'time_zone' => $request->time_zone,
                'base_currency' => $request->base_currency,
                'currency_symbol' => $request->currency_symbol,
                'fraction_number' => $request->fraction_number,
                'date_time_format' => $request->date_format,
                'paginate' => $request->paginate,
                'admin_prefix' => $request->admin_prefix,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating data');

            $env = [
                'APP_TIMEZONE' => $response->time_zone,
                'APP_DEBUG' => $response->error_log == 0 ? 'true' : 'false'
            ];

            BasicService::setEnv($env);
            session()->flash('success', 'Basic control has been successfully configured');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function themeColorSetting()
    {
        $themeColor = ThemeColor::first();
        return view('admin.control_panel.theme_color', compact('themeColor'));
    }

    public function themeColorUpdate(Request $request)
    {

        $request->validate([
            "light_green_primary_color" => 'sometimes|required|string',
            "light_green_secondary_color" => 'sometimes|required|string',
            "light_green_hero_color" => 'sometimes|required|string',
            "dark_violet_primary_color" => 'sometimes|required|string',
            "dark_violet_secondary_color" => 'sometimes|required|string',
            "minimal_primary_color" => 'sometimes|required|string',
            "minimal_secondary_color" => 'sometimes|required|string',
            "minimal_subheading_color" => 'sometimes|required|string',
            "minimal_bg_left_color" => 'sometimes|required|string',
            "minimal_bg_right_color" => 'sometimes|required|string',
            "minimal_button_left_color" => 'sometimes|required|string',
            "minimal_bg_left_two_color" => 'sometimes|required|string',
            "minimal_copy_right_bg_color" => 'sometimes|required|string',
            "deep_blue_primary_color" => 'sometimes|required|string',
            "deep_blue_secondary_color" => 'sometimes|required|string',
            "dark_mode_primary_color" => 'sometimes|required|string',
            "dark_mode_secondary_color" => 'sometimes|required|string',
            "light_orange_primary_color" => 'sometimes|required|string',
            "light_orange_theme_light_color" => 'sometimes|required|string',
            "light_orange_secondary_color" => 'sometimes|required|string',
        ]);


        try {
            $themeColor = ThemeColor::first();

            $updateData = [];

            if ($request->has('light_green_primary_color')) {
                $updateData['light_green_primary_color'] = $request->light_green_primary_color;
            }

            if ($request->has('light_green_secondary_color')) {
                $updateData['light_green_secondary_color'] = $request->light_green_secondary_color;
            }

            if ($request->has('light_green_hero_color')) {
                $updateData['light_green_hero_color'] = $request->light_green_hero_color;
            }

            if ($request->has('dark_violet_primary_color')) {
                $updateData['dark_violet_primary_color'] = $request->dark_violet_primary_color;
            }

            if ($request->has('dark_violet_secondary_color')) {
                $updateData['dark_violet_secondary_color'] = $request->dark_violet_secondary_color;
            }

            if ($request->has('minimal_primary_color')) {
                $updateData['minimal_primary_color'] = $request->minimal_primary_color;
            }

            if ($request->has('minimal_secondary_color')) {
                $updateData['minimal_secondary_color'] = $request->minimal_secondary_color;
            }

            if ($request->has('minimal_subheading_color')) {
                $updateData['minimal_sub_heading_color'] = $request->minimal_subheading_color;
            }

            if ($request->has('minimal_bg_left_color')) {
                $updateData['minimal_bg_left_color'] = $request->minimal_bg_left_color;
            }

            if ($request->has('minimal_bg_right_color')) {
                $updateData['minimal_bg_right_color'] = $request->minimal_bg_right_color;
            }

            if ($request->has('minimal_button_left_color')) {
                $updateData['minimal_button_left_color'] = $request->minimal_button_left_color;
            }

            if ($request->has('minimal_bg_left_two_color')) {
                $updateData['minimal_bg_left_two_color'] = $request->minimal_bg_left_two_color;
            }

            if ($request->has('minimal_copy_right_bg_color')) {
                $updateData['minimal_copy_right_bg_color'] = $request->minimal_copy_right_bg_color;
            }

            if ($request->has('deep_blue_primary_color')) {
                $updateData['deep_blue_primary_color'] = $request->deep_blue_primary_color;
            }

            if ($request->has('deep_blue_secondary_color')) {
                $updateData['deep_blue_secondary_color'] = $request->deep_blue_secondary_color;
            }

            if ($request->has('dark_mode_primary_color')) {
                $updateData['dark_mode_primary_color'] = $request->dark_mode_primary_color;
            }

            if ($request->has('dark_mode_secondary_color')) {
                $updateData['dark_mode_secondary_color'] = $request->dark_mode_secondary_color;
            }

            if ($request->has('light_orange_primary_color')) {
                $updateData['light_orange_primary_color'] = $request->light_orange_primary_color;
            }

            if ($request->has('light_orange_secondary_color')) {
                $updateData['light_orange_secondary_color'] = $request->light_orange_secondary_color;
            }

            if ($request->has('light_orange_theme_light_color')) {
                $updateData['light_orange_theme_light_color'] = $request->light_orange_theme_light_color;
            }

            $response = $themeColor->update($updateData);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Theme color has been successfully updated.');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function basicControlActivityUpdate(Request $request)
    {
        $request->validate([
            'strong_password' => 'nullable|numeric|in:0,1',
            'registration' => 'nullable|numeric|in:0,1',
            'error_log' => 'nullable|numeric|in:0,1',
            'is_active_cron_notification' => 'nullable|numeric|in:0,1',
            'has_space_between_currency_and_amount' => 'nullable|numeric|in:0,1',
            'is_force_ssl' => 'nullable|numeric|in:0,1',
            'is_currency_position' => 'nullable|string|in:left,right',
            'automatic_currency_update_permission' => 'nullable|numeric|in:0,1'
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'error_log' => $request->error_log,
                'strong_password' => $request->strong_password,
                'registration' => $request->registration,
                'is_active_cron_notification' => $request->is_active_cron_notification,
                'has_space_between_currency_and_amount' => $request->has_space_between_currency_and_amount,
                'is_currency_position' => $request->is_currency_position,
                'is_force_ssl' => $request->is_force_ssl,
                'automatic_currency_update_permission' => $request->automatic_currency_update_permission
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating the data.');

            session()->flash('success', 'Basic control has been successfully configured.');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function currencyExchangeApiConfig()
    {
        $data['scheduleList'] = config('schedulelist.schedule_list');
        $data['basicControl'] = basicControl();
        return view('admin.control_panel.exchange_api_setting', $data);
    }

    public function currencyExchangeApiConfigUpdate(Request $request)
    {
        $request->validate([
            'currency_layer_access_key' => 'required|string',
            'coin_market_cap_app_key' => 'required|string',
        ]);

        try {
            $basicControl = basicControl();
            $basicControl->update([
                'currency_layer_access_key' => $request->currency_layer_access_key,
                'currency_layer_auto_update' => $request->currency_layer_auto_update,
                'currency_layer_auto_update_at' => $request->currency_layer_auto_update_at,
                'coin_market_cap_app_key' => $request->coin_market_cap_app_key,
                'coin_market_cap_auto_update' => $request->coin_market_cap_auto_update,
                'coin_market_cap_auto_update_at' => $request->coin_market_cap_auto_update_at
            ]);
            return back()->with('success', 'Configuration changes successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function cookieControl(Request $request)
    {
        if ($request->isMethod('get')) {
            $data['basic'] = basicControl();
            return view('admin.control_panel.cookie', $data);
        } elseif ($request->isMethod('post')) {
            $data = $request->validate([
                'cookie_title' => 'required|string|max:100',
                'cookie_sub_title' => 'required|string|max:100',
                'cookie_url' => 'required|string|max:100',
                'cookie_status' => 'nullable|numeric|in:0,1',
            ]);
            $basic = BasicControl();
            $basic->update($data);

            Artisan::call('optimize:clear');
            return back()->with('success', 'Successfully Updated');
        }
    }

    public function addonManager()
    {
        return view('admin.control_panel.addon_manager');
    }

    public function addonManagerUpdate(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'status' => 'required|boolean',
        ]);

        try {
            $moduleName = $request->input('module');
            $moduleStatus = $request->input('status');

            if ($moduleStatus) {
                Artisan::call("module:enable $moduleName");
                Artisan::call('migrate', ['--force' => true]);
                $message = "$moduleName successfully enabled.";
            } else {
                Artisan::call("module:disable $moduleName");
                $message = "$moduleName successfully disabled.";
            }

            Artisan::call('optimize:clear');

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the module.'], 500);
        }
    }
}


