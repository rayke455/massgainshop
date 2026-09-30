<?php

namespace App\Http\Controllers;

use App\Events\UpdateAdminNotification;
use App\Events\UpdateChildPanelAdminNotification;
use App\Events\UpdateUserNotification;
use App\Models\Admin;
use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\ChildPanel\App\Models\ChildPanel;

class InAppNotificationController extends Controller
{
    public function showByAdmin()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->latest()->get();

        return $siteNotifications;
    }

    public function show()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [User::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->latest()->get();
        return $siteNotifications;
    }

    public function readAt($id)
    {
        $siteNotification = InAppNotification::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateAdminNotification(Auth::id()));
            }
            else {
                event(new UpdateUserNotification(Auth::id()));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return $data;
    }

    public function readAllByAdmin()
    {
        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [Admin::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateAdminNotification(Auth::id()));
        }
        $data['status'] = true;
        return $data;
    }

    public function readAllByChildPanelAdmin()
    {
        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [ChildPanel::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->delete();

        if ($siteNotification) {
            event(new UpdateChildPanelAdminNotification(Auth::id()));
        }
        $data['status'] = true;
        return $data;
    }

    public function childPanelReadAt($id)
    {
        $siteNotification = InAppNotification::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateChildPanelAdminNotification(Auth::id()));
            }
            else {
                event(new UpdateUserNotification(Auth::id()));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return $data;
    }

    public function readAll()
    {

        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [User::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->delete();
        if ($siteNotification) {
            event(new UpdateUserNotification(Auth::id()));
        }

        $data['status'] = true;
        return $data;
    }

    public function showByChildPanelAdmin()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [ChildPanel::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->latest()->get();

        return $siteNotifications;
    }
}
