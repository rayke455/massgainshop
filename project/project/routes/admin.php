<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BasicControlController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailConfigController;
use App\Http\Controllers\Admin\FirebaseConfigController;
use App\Http\Controllers\Admin\KycController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\ManageMenuController;
use App\Http\Controllers\Admin\ManualGatewayController;
use App\Http\Controllers\Admin\PaymentLogController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\PusherConfigController;
use App\Http\Controllers\Admin\SmsConfigController;
use App\Http\Controllers\Admin\StorageController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\TransactionLogController;
use App\Http\Controllers\Admin\TranslateAPISettingController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\Admin\AdminProfileSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MaintenanceModeController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\ApiProviderController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReferralCommissionController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\SocialiteController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\PwaController;
use App\Http\Controllers\Admin\TwoFaSecurityController;
use App\Http\Controllers\Admin\VerificationController;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('auth:admin')->get('/set-app-url', function () {
    $host = request()->getScheme() . '://' . request()->getHost();
    app(\App\Services\BasicService::class)->setEnv(['APP_URL' => $host]);
    \Artisan::call('config:clear');
    $keywords = ['push-notification', 'ajax'];
    $previousUrl = url()->previous();
    if (array_filter($keywords, fn($keyword) => str_contains($previousUrl, $keyword))) {
        return redirect('/')->with('success', 'Cache Cleared Successfully');
    }
    return redirect()->back(fallback: '/');
});



Route::middleware('auth:admin')->get('clear', function () {
    Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $previousUrl = url()->previous();
    if (str_contains($previousUrl, 'push-notification')) {
        return redirect('/')->with('success', 'Cache Cleared Successfully');
    }
    return redirect()->back(fallback: '/')->with('success', 'Cache Cleared Successfully');
})->name('clear');

Route::middleware('auth:admin')->get('queue-work', function () {
    return Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
})->name('queue.work');

Route::middleware('auth:admin')->get('schedule-run', function () {
    return Illuminate\Support\Facades\Artisan::call('schedule:run');
})->name('schedule:run');


Route::middleware('auth:admin')->get('migrate', function () {
    Illuminate\Support\Facades\Artisan::call('migrate');
    $output = Illuminate\Support\Facades\Artisan::output();

    return "<pre>$output</pre>";
})->name('migrate');

Route::middleware(['CheckModuleIsEnable'])->group(function () {
    $basicControl = basicControl();
    Route::group(['prefix' => $basicControl->admin_prefix ?? 'admin', 'as' => 'admin.'], function () {
        Route::get('/themeMode/{themeType?}', function ($themeType = 'true') {
            session()->put('themeMode', $themeType);
            return $themeType;
        })->name('themeMode');

        /*== Authentication Routes ==*/
        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest:admin');
        Route::post('login', [LoginController::class, 'login'])->name('login.submit');
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request')
            ->middleware('guest:admin');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
            ->name('password.reset')->middleware('guest:admin');

        Route::post('password/reset/update', [ResetPasswordController::class, 'resetPassword'])
            ->name('password.reset.update');


        Route::middleware(['auth:admin', 'checkActiveUser', 'demo'])->group(function () {

            Route::get('verification/check', [VerificationController::class, 'check'])->name('check');
            Route::post('two-fa-verify', [VerificationController::class, 'twoStepVerify'])->name('2step.verify');

            Route::middleware('adminVerificationCheck')->group(function () {
                Route::get('guest-orders', [\App\Http\Controllers\GuestOrderController::class, 'admin'])->name('guest.orders');
                Route::get('panel-rentals', [\App\Http\Controllers\PanelRentalController::class, 'admin'])->name('rentals.index');
                Route::post('panel-rentals/settings', [\App\Http\Controllers\PanelRentalController::class, 'adminSettings'])->name('rentals.settings');
                Route::post('panel-rentals/{id}', [\App\Http\Controllers\PanelRentalController::class, 'adminPanel'])->whereNumber('id')->name('rentals.panel');
                Route::get('panel-rentals/payouts', [\App\Http\Controllers\PanelRentalController::class, 'adminPayouts'])->name('rentals.payouts');
                Route::post('panel-rentals/payouts/{id}', [\App\Http\Controllers\PanelRentalController::class, 'adminPayoutAction'])->whereNumber('id')->name('rentals.payout.action');

                Route::post('logout', [LoginController::class, 'logout'])->name('logout');

                Route::get('profile', [AdminProfileSettingController::class, 'profile'])->name('profile');
                Route::put('profile', [AdminProfileSettingController::class, 'profileUpdate'])->name('profile.update');
                Route::put('password', [AdminProfileSettingController::class, 'passwordUpdate'])->name('password.update');
                Route::post('notification-permission', [AdminProfileSettingController::class, 'notificationPermission'])->name('notification.permission');

                Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
                Route::post('save-token', [DashboardController::class, 'saveToken'])->name('save.token');


                Route::group(['prefix' => 'ajax/dashboard'], function () {
                    Route::get('monthly-deposit-withdraw', [DashboardController::class, 'monthlyDepositWithdraw'])->name('monthly.deposit.withdraw');
                    Route::get('chartUserRecords', [DashboardController::class, 'chartUserRecords'])->name('chartUserRecords');
                    Route::get('chartTicketRecords', [DashboardController::class, 'chartTicketRecords'])->name('chartTicketRecords');
                    Route::get('chartKycRecords', [DashboardController::class, 'chartKycRecords'])->name('chartKycRecords');
                    Route::get('chartTransactionRecords', [DashboardController::class, 'chartTransactionRecords'])->name('chartTransactionRecords');
                    Route::get('chartBrowserHistory', [DashboardController::class, 'chartBrowserHistory'])->name('chart.browser.history');
                    Route::get('chartOsHistory', [DashboardController::class, 'chartOsHistory'])->name('chart.os.history');
                    Route::get('chartDeviceHistory', [DashboardController::class, 'chartDeviceHistory'])->name('chart.device.history');
                    Route::get('monthly/orders/records', [DashboardController::class, 'monthlyOrderShow'])->name('monthly.order.show');
                    Route::get('order-records', [DashboardController::class, 'orderRecords'])->name('order.records');
                    Route::get('pending-order-records', [DashboardController::class, 'pendingOrderRecords'])->name('pending.order.records');
                    Route::get('completed-order-records', [DashboardController::class, 'completedOrderRecords'])->name('completed.order.records');
                    Route::get('canceled-order-records', [DashboardController::class, 'canceledOrderRecords'])->name('canceled.order.records');
                    Route::get('social-media-best-seller', [DashboardController::class, 'socialMediaBestSellerService'])
                        ->name('social.media.best.seller.service');
                });


                /*== Control Panel ==*/
                Route::get('settings/{settings?}', [BasicControlController::class, 'index'])->name('settings');
                Route::get('basic-control', [BasicControlController::class, 'basicControl'])->name('basic.control');
                Route::post('basic-control-update', [BasicControlController::class, 'basicControlUpdate'])->name('basic.control.update');
                Route::post('basic-control-activity-update', [BasicControlController::class, 'basicControlActivityUpdate'])->name('basic.control.activity.update');
                Route::get('currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])->name('currency.exchange.api.config');
                Route::post('currency-exchange-api-config/update', [BasicControlController::class, 'currencyExchangeApiConfigUpdate'])->name('currency.exchange.api.config.update');
                Route::match(['get', 'post'], 'cookie-control', [BasicControlController::class, 'cookieControl'])->name('cookie.control');

                if (Module::has('ChildPanel')) {
                    Route::get('addon-manager', [BasicControlController::class, 'addonManager'])->name('addon.manager');
                    Route::post('addon-manager/update', [BasicControlController::class, 'addonManagerUpdate'])->name('addon.manager.update');
                }

                /*== Theme Color Setting ==*/
                Route::get('theme-color', [BasicControlController::class, 'themeColorSetting'])->name('theme.color');
                Route::post('theme-color-update', [BasicControlController::class, 'themeColorUpdate'])->name('theme.color.update');

                /* ===== Storage ===== */
                Route::get('storage', [StorageController::class, 'index'])->name('storage.index');
                Route::any('storage/edit/{id}', [StorageController::class, 'edit'])->name('storage.edit');
                Route::any('storage/update/{id}', [StorageController::class, 'update'])->name('storage.update');
                Route::post('storage/set-default/{id}', [StorageController::class, 'setDefault'])->name('storage.setDefault');

                /* ===== Maintenance Mode ===== */
                Route::get('maintenance-mode', [MaintenanceModeController::class, 'index'])->name('maintenance.index');
                Route::post('maintenance-mode/update', [MaintenanceModeController::class, 'maintenanceModeUpdate'])->name('maintenance.mode.update');

                /* ===== LOGO, FAVICON UPDATE ===== */
                Route::get('logo-setting', [LogoController::class, 'logoSetting'])->name('logo.settings');
                Route::post('logo-update', [LogoController::class, 'logoUpdate'])->name('logo.update');


                /* ===== FIREBASE CONFIG ===== */
                Route::get('firebase-config', [FirebaseConfigController::class, 'firebaseConfig'])->name('firebase.config');
                Route::post('firebase-config-update', [FirebaseConfigController::class, 'firebaseConfigUpdate'])->name('firebase.config.update');
                Route::get('firebase/config/file/download', [FirebaseConfigController::class, 'firebaseConfigFileDownload'])->name('firebase.config.file.download');
                Route::post('firebase/config/file/upload', [FirebaseConfigController::class, 'firebaseConfigFileUpload'])->name('firebase.config.file.upload');


                /* ===== PUSHER CONFIG ===== */
                Route::get('pusher-config', [PusherConfigController::class, 'pusherConfig'])->name('pusher.config');
                Route::post('pusher-config-update', [PusherConfigController::class, 'pusherConfigUpdate'])->name('pusher.config.update');

                /* ===== EMAIL CONFIG ===== */
                Route::get('email-controls', [EmailConfigController::class, 'emailControls'])->name('email.control');
                Route::get('email-config/edit/{method}', [EmailConfigController::class, 'emailConfigEdit'])->name('email.config.edit');
                Route::post('email-config/update/{method}', [EmailConfigController::class, 'emailConfigUpdate'])->name('email.config.update');
                Route::post('email-config/set-as-default/{method}', [EmailConfigController::class, 'emailSetAsDefault'])->name('email.set.default');
                Route::post('test.email', [EmailConfigController::class, 'testEmail'])->name('test.email');


                /* Notification Templates Routes */
                Route::match(['get', 'post'], 'default-template', [NotificationTemplateController::class, 'defaultTemplate'])->name('email.template.default');
                Route::get('email-templates', [NotificationTemplateController::class, 'emailTemplates'])->name('email.templates');
                Route::get('email-template/edit/{id}', [NotificationTemplateController::class, 'editEmailTemplate'])->name('email.template.edit');
                Route::put('email-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateEmailTemplate'])->name('email.template.update');

                Route::get('sms-templates', [NotificationTemplateController::class, 'smsTemplates'])->name('sms.templates');
                Route::get('sms-template/edit/{id}', [NotificationTemplateController::class, 'editSmsTemplate'])->name('sms.template.edit');
                Route::put('sms-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateSmsTemplate'])->name('sms.template.update');

                Route::get('in-app-notification-templates', [NotificationTemplateController::class, 'inAppNotificationTemplates'])
                    ->name('in.app.notification.templates');
                Route::get('in-app-notification-template/edit/{id}', [NotificationTemplateController::class, 'editInAppNotificationTemplate'])
                    ->name('in.app.notification.template.edit');
                Route::put('in-app-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updateInAppNotificationTemplate'])
                    ->name('in.app.notification.template.update');
                Route::get('push-notification-templates', [NotificationTemplateController::class, 'pushNotificationTemplates'])->name('push.notification.templates');
                Route::get('push-notification-template/edit/{id}', [NotificationTemplateController::class, 'editPushNotificationTemplate'])->name('push.notification.template.edit');
                Route::put('push-notification-template/{id?}/{language_id}', [NotificationTemplateController::class, 'updatePushNotificationTemplate'])->name('push.notification.template.update');


                /* ===== EMAIL CONFIG ===== */
                Route::get('sms-configuration', [SmsConfigController::class, 'index'])->name('sms.controls');
                Route::get('sms-config-edit/{method}', [SmsConfigController::class, 'smsConfigEdit'])->name('sms.config.edit');
                Route::post('sms-config-update/{method}', [SmsConfigController::class, 'smsConfigUpdate'])->name('sms.config.update');
                Route::post('sms-method-update/{method}', [SmsConfigController::class, 'manualSmsMethodUpdate'])->name('manual.sms.method.update');
                Route::post('sms-config/set-as-default/{method}', [SmsConfigController::class, 'smsSetAsDefault'])->name('sms.set.default');

                /* ===== PLUGIN CONFIG ===== */
                Route::get('plugin', [PluginController::class, 'pluginConfig'])->name('plugin.config');
                Route::get('plugin/tawk', [PluginController::class, 'tawkConfiguration'])->name('tawk.configuration');
                Route::post('plugin/tawk/Configuration/update', [PluginController::class, 'tawkConfigurationUpdate'])->name('tawk.configuration.update');
                Route::get('plugin/fb-messenger-configuration', [PluginController::class, 'fbMessengerConfiguration'])->name('fb.messenger.configuration');
                Route::post('plugin/fb-messenger-configuration/update', [PluginController::class, 'fbMessengerConfigurationUpdate'])->name('fb.messenger.configuration.update');
                Route::get('plugin/google-recaptcha', [PluginController::class, 'googleRecaptchaConfiguration'])->name('google.recaptcha.configuration');
                Route::post('plugin/google-recaptcha/update', [PluginController::class, 'googleRecaptchaConfigurationUpdate'])->name('google.recaptcha.Configuration.update');
                Route::get('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfiguration'])->name('google.analytics.configuration');
                Route::post('plugin/google-analytics', [PluginController::class, 'googleAnalyticsConfigurationUpdate'])->name('google.analytics.configuration.update');
                Route::get('plugin/manual-recaptcha', [PluginController::class, 'manualRecaptcha'])->name('manual.recaptcha');
                Route::post('plugin/manual-recaptcha/update', [PluginController::class, 'manualRecaptchaUpdate'])->name('manual.recaptcha.update');
                Route::post('plugin/active-recaptcha', [PluginController::class, 'activeRecaptcha'])->name('active.recaptcha');

                /* ===== ADMIN GOOGLE API SETTING ===== */
                Route::get('translate-api-setting', [TranslateAPISettingController::class, 'translateAPISetting'])->name('translate.api.setting');
                Route::get('translate-api-config/edit/{method}', [TranslateAPISettingController::class, 'translateAPISettingEdit'])->name('translate.api.config.edit');
                Route::post('translate-api-setting/update/{method}', [TranslateAPISettingController::class, 'translateAPISettingUpdate'])->name('translate.api.setting.update');
                Route::post('translate-api-setting/set-as-default/{method}', [TranslateAPISettingController::class, 'translateSetAsDefault'])->name('translate.set.default');


                /* ===== ADMIN LANGUAGE SETTINGS ===== */
                Route::get('languages', [LanguageController::class, 'index'])->name('language.index');
                Route::post('languages/auto-translate', [LanguageController::class, 'toggleAutoTranslate'])->name('language.auto.translate');
                Route::get('language/create', [LanguageController::class, 'create'])->name('language.create');
                Route::post('language/store', [LanguageController::class, 'store'])->name('language.store');
                Route::get('language/edit/{id}', [LanguageController::class, 'edit'])->name('language.edit');
                Route::put('language/update/{id}', [LanguageController::class, 'update'])->name('language.update');
                Route::delete('language-delete/{id}', [LanguageController::class, 'destroy'])->name('language.delete');
                Route::put('change-language-status/{id}', [LanguageController::class, 'changeStatus'])->name('change.language.status');


                Route::get('{short_name}/keywords', [LanguageController::class, 'keywords'])->name('language.keywords');
                Route::post('language-keyword/{short_name}', [LanguageController::class, 'addKeyword'])->name('add.language.keyword');
                Route::put('language-keyword/{short_name}/{key}', [LanguageController::class, 'updateKeyword'])->name('update.language.keyword');
                Route::delete('language-keyword/{short_name}/{key}', [LanguageController::class, 'deleteKeyword'])->name('delete.language.keyword');
                Route::post('language-import-json', [LanguageController::class, 'importJson'])->name('language.import.json');
                Route::put('update-key/{language}', [LanguageController::class, 'updateKey'])->name('language.update.key');
                Route::post('language/keyword/translate', [LanguageController::class, 'singleKeywordTranslate'])->name('single.keyword.translate');
                Route::post('language/all-keyword/translate/{shortName}', [LanguageController::class, 'allKeywordTranslate'])->name('all.keyword.translate');


                /* ===== ADMIN SUPPORT TICKET ===== */
                Route::get('tickets/{status?}', [SupportTicketController::class, 'tickets'])->name('ticket');
                Route::get('tickets-search/{status}', [SupportTicketController::class, 'ticketSearch'])->name('ticket.search');
                Route::get('tickets-view/{id}', [SupportTicketController::class, 'ticketView'])->name('ticket.view');
                Route::put('ticket-reply/{id}', [SupportTicketController::class, 'ticketReplySend'])->name('ticket.reply');
                Route::get('ticket-download/{ticket}', [SupportTicketController::class, 'ticketDownload'])->name('ticket.download');
                Route::post('ticket-closed/{id}', [SupportTicketController::class, 'ticketClosed'])->name('ticket.closed');
                Route::post('ticket-delete', [SupportTicketController::class, 'ticketDelete'])->name('ticket.delete');


                /* ===== InAppNotificationController SETTINGS ===== */
                Route::get('push-notification-show', [InAppNotificationController::class, 'showByAdmin'])->name('push.notification.show');
                Route::get('push.notification.readAll', [InAppNotificationController::class, 'readAllByAdmin'])->name('push.notification.readAll');
                Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

                /* PAYMENT METHOD MANAGE BY ADMIN*/
                Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment.methods');
                Route::get('edit-payment-methods/{id}', [PaymentMethodController::class, 'edit'])->name('edit.payment.methods');
                Route::put('update-payment-methods/{id}', [PaymentMethodController::class, 'update'])->name('update.payment.methods');
                Route::post('sort-payment-methods', [PaymentMethodController::class, 'sortPaymentMethods'])->name('sort.payment.methods');
                Route::post('payment-methods/deactivate', [PaymentMethodController::class, 'deactivate'])->name('payment.methods.deactivate');
                Route::post('payment-methods/auto-update-currency/{id}', [PaymentMethodController::class, 'autoUpdateCurrency'])->name('payment.method.auto.update.currency');
                Route::post('payment-methods/auto-update-currency-all', [PaymentMethodController::class, 'autoUpdateAllCurrencies'])->name('payment.method.auto.update.all');
                Route::post('payment-methods/mpesa/test/{id}', [PaymentMethodController::class, 'testMpesa'])->name('payment.method.mpesa.test');
                Route::post('payment-methods/add-child-panel/{id}', [PaymentMethodController::class, 'addChildPanel'])->name('payment.methods.add.child.panel');


                /*=* MANUAL METHOD MANAGE BY ADMIN *=*/
                Route::get('payment-methods/manual', [ManualGatewayController::class, 'index'])->name('deposit.manual.index');
                Route::get('payment-methods/manual/create', [ManualGatewayController::class, 'create'])->name('deposit.manual.create');
                Route::post('payment-methods/manual/store', [ManualGatewayController::class, 'store'])->name('deposit.manual.store');
                Route::get('payment-methods/manual/edit/{id}', [ManualGatewayController::class, 'edit'])->name('deposit.manual.edit');
                Route::put('payment-methods/manual/update/{id}', [ManualGatewayController::class, 'update'])->name('deposit.manual.update');
                Route::match(['get', 'post'], 'currency-exchange-api-config', [BasicControlController::class, 'currencyExchangeApiConfig'])
                    ->name('currency.exchange.api.config');

                /*= MANAGE KYC =*/
                Route::get('kyc-setting/list', [KycController::class, 'index'])->name('kyc.form.list');
                Route::get('kyc-setting/create', [KycController::class, 'create'])->name('kyc.create');
                Route::post('manage-kyc/store', [KycController::class, 'store'])->name('kyc.store');
                Route::get('manage-kyc/edit/{id}', [KycController::class, 'edit'])->name('kyc.edit');
                Route::post('manage-kyc/update/{id}', [KycController::class, 'update'])->name('kyc.update');
                Route::get('kyc/{status?}', [KycController::class, 'userKycList'])->name('kyc.list');
                Route::get('kyc/search/{status?}', [KycController::class, 'userKycSearch'])->name('kyc.search');
                Route::get('kyc/view/{id}', [KycController::class, 'view'])->name('kyc.view');
                Route::post('user/kyc/action/{id}', [KycController::class, 'action'])->name('kyc.action');


                /* ====== Subscriber =====*/
                Route::get('subscriber/list', [SubscriberController::class, 'index'])->name('subscriber.index');
                Route::post('subscriber/store', [SubscriberController::class, 'store'])->name('subscriber.store');
                Route::delete('subscriber/destroy/{id}', [SubscriberController::class, 'destroy'])->name('subscriber.destroy');
                Route::get('send-mail', [SubscriberController::class, 'sendEmailForm'])->name('subscriber.mail');
                Route::post('send-email', [SubscriberController::class, 'sendEmail'])->name('subscriber.email');

                Route::get('frontend/themes', [ThemeController::class, 'index'])->name('theme.index');
                Route::post('frontend/select-theme', [ThemeController::class, 'selectTheme'])->name('select.theme');
                Route::get('frontend/user-panels', [ThemeController::class, 'userPanels'])->name('user.panels');
                Route::post('frontend/select/user-panel', [ThemeController::class, 'selectUserPanel'])->name('select.user.panel');

                /*= Frontend Manage =*/
                Route::get('frontend/pages/{theme}', [PageController::class, 'index'])->name('page.index');
                Route::get('frontend/create-page/{theme}', [PageController::class, 'create'])->name('create.page');
                Route::post('frontend/create-page/store/{theme}', [PageController::class, 'store'])->name('create.page.store');
                Route::get('frontend/edit-page/{id}/{theme}/{language?}', [PageController::class, 'edit'])->name('edit.page');
                Route::post('frontend/update-page/{id}/{theme}', [PageController::class, 'update'])->name('update.page');
                Route::post('frontend/page/update-slug', [PageController::class, 'updateSlug'])->name('update.slug');
                Route::delete('frontend/page/delete/{id}', [PageController::class, 'delete'])->name('page.delete');
                Route::get('frontend/page/seo/{id}', [PageController::class, 'seo'])->name('page.seo');
                Route::post('frontend/page/seo/update/{id}', [PageController::class, 'seoUpdate'])->name('page.seo.update');

                Route::get('frontend/edit-static-page/{id}/{theme}/{language?}', [PageController::class, 'editStaticPage'])->name('edit.static.page');
                Route::post('frontend/update-static-page/{id}/{theme}', [PageController::class, 'updateStaticPage'])->name('update.static.page');

                Route::get('frontend/manage-menu', [ManageMenuController::class, 'manageMenu'])->name('manage.menu');
                Route::post('frontend/header-menu-item/store', [ManageMenuController::class, 'headerMenuItemStore'])->name('header.menu.item.store');
                Route::post('frontend/footer-menu-item/store', [ManageMenuController::class, 'footerMenuItemStore'])->name('footer.menu.item.store');
                Route::post('frontend/manage-menu/add-custom-link', [ManageMenuController::class, 'addCustomLink'])->name('add.custom.link');
                Route::get('frontend/manage-menu/edit-custom-link/{pageId}', [ManageMenuController::class, 'editCustomLink'])->name('edit.custom.link');
                Route::post('frontend/manage-menu/update-custom-link/{pageId}', [ManageMenuController::class, 'updateCustomLink'])->name('update.custom.link');
                Route::delete('frontend/manage-menu/delete-custom-link/{pageId}', [ManageMenuController::class, 'deleteCustomLink'])->name('delete.custom.link');
                Route::get('frontend/manage-menu/get-custom-link-data', [ManageMenuController::class, 'getCustomLinkData'])->name('get.custom.link');

                Route::get('frontend/contents/{name}', [ContentController::class, 'index'])->name('manage.content');
                Route::post('frontend/contents/store/{name}/{language}', [ContentController::class, 'store'])->name('content.store');
                Route::get('frontend/contents/item/{name}', [ContentController::class, 'manageContentMultiple'])->name('manage.content.multiple');
                Route::post('frontend/contents/item/store/{name}/{language}', [ContentController::class, 'manageContentMultipleStore'])->name('content.multiple.store');
                Route::get('frontend/contents/item/edit/{name}/{id}', [ContentController::class, 'multipleContentItemEdit'])->name('content.item.edit');
                Route::post('frontend/contents/item/update/{name}/{id}/{language}', [ContentController::class, 'multipleContentItemUpdate'])->name('multiple.content.item.update');
                Route::delete('frontend/contents/delete/{id}', [ContentController::class, 'ContentDelete'])->name('content.item.delete');

                /*====Manage Users ====*/
                Route::post('login/as/user/{id}', [UsersController::class, 'loginAsUser'])->whereNumber('id')->middleware('throttle:10,1')->name('login.as.user');
                Route::post('block-profile/{id}', [UsersController::class, 'blockProfile'])->name('block.profile');
                Route::get('users', [UsersController::class, 'index'])->name('users');
                Route::get('users/search', [UsersController::class, 'search'])->name('users.search');

                Route::get('user/edit/{id}', [UsersController::class, 'userEdit'])->name('user.edit');
                Route::post('user/update/{id}', [UsersController::class, 'userUpdate'])->name('user.update');
                Route::post('user/email/{id}', [UsersController::class, 'EmailUpdate'])->name('user.email.update');
                Route::post('user/username/{id}', [UsersController::class, 'usernameUpdate'])->name('user.username.update');
                Route::post('users-delete-multiple', [UsersController::class, 'deleteMultiple'])->name('user.delete.multiple');
                Route::post('user/update-balance/{id}', [UsersController::class, 'updateBalanceUpdate'])->name('user.update.balance');
                Route::post('user/password/{id}', [UsersController::class, 'passwordUpdate'])->name('user.password.update');
                Route::post('user/preferences/{id}', [UsersController::class, 'preferencesUpdate'])->name('user.preferences.update');
                Route::post('user/two-fa-security/{id}', [UsersController::class, 'userTwoFaUpdate'])->name('user.twoFa.update');
                Route::post('user/balance-update/{id}', [UsersController::class, 'userBalanceUpdate'])->name('user-balance-update');

                Route::get('user/send-email/{id}', [UsersController::class, 'sendEmail'])->name('send.email');
                Route::post('user/send-email/{id?}', [UsersController::class, 'sendMailUser'])->name('user.email.send');
                Route::get('mail-all-user', [UsersController::class, 'mailAllUser'])->name('mail.all.user');

                Route::get('user/kyc/{id}', [UsersController::class, 'userKyc'])->name('user.kyc.list');
                Route::get('user/kyc/search/{id}', [UsersController::class, 'KycSearch'])->name('user.kyc.search');

                Route::get('user/transaction/{id}', [UsersController::class, 'transaction'])->name('user.transaction');
                Route::get('user/transaction/search/{id}', [UsersController::class, 'userTransactionSearch'])->name('user.transaction.search');

                Route::get('user/payment/{id}', [UsersController::class, 'payment'])->name('user.payment');
                Route::get('user/payment/search/{id}', [UsersController::class, 'userPaymentSearch'])->name('user.payment.search');

                Route::get('/email-send', [UsersController::class, 'emailToUsers'])->name('email-send');
                Route::post('/email-send', [UsersController::class, 'sendEmailToUsers'])->name('email-send.store');
                Route::delete('user/delete/{id}', [UsersController::class, 'userDelete'])->name('user.delete');

                Route::get('users/add/new', [UsersController::class, 'userAdd'])->name('users.add');
                Route::post('users/store', [UsersController::class, 'userStore'])->name('user.store');
                Route::get('users/added-successfully/{id}', [UsersController::class, 'userCreateSuccessMessage'])
                    ->name('user.create.success.message');
                Route::get('user/view-profile/{id}', [UsersController::class, 'userViewProfile'])->name('user.view.profile');

                Route::get('user/custom-rate/{id}', [UsersController::class, 'customRate'])->name('user.custom.rate');
                Route::get('user/getService', [UsersController::class, 'getService'])->name('user.getService');
                Route::post('user/setServiceRate', [UsersController::class, 'setServiceRate'])->name('user.setServiceRate');
                Route::post('user/updateServiceRate', [UsersController::class, 'updateServiceRate'])->name('user.updateServiceRate');
                Route::post('user/delete-service-rate/{id}', [UsersController::class, 'deleteServiceRate'])->name('user.deleteServiceRate');

                /* ====== Notice Manage =====*/
                Route::resource('notice', NoticeController::class);
                Route::get('notice/edit/{id}/{language?}', [NoticeController::class, 'edit'])->name('notice.edit');
                Route::post('notice/update/{id}', [NoticeController::class, 'update'])->name('notice.update');

                /* ====== Transaction Log =====*/
                Route::get('transaction', [TransactionLogController::class, 'transaction'])->name('transaction');
                Route::get('transaction/search', [TransactionLogController::class, 'transactionSearch'])->name('transaction.search');

                /* ====== Payment Log =====*/
                Route::get('payment/log', [PaymentLogController::class, 'index'])->name('payment.log');
                Route::get('payment/search', [PaymentLogController::class, 'search'])->name('payment.search');
                Route::get('payment/pending', [PaymentLogController::class, 'pending'])->name('payment.pending');
                Route::get('payment/pending/request', [PaymentLogController::class, 'paymentRequest'])->name('payment.request');
                Route::put('payment/action/{id}', [PaymentLogController::class, 'action'])->name('payment.action');


                // Only index/store exist in the controller; full resources exposed pages that crashed.
                // Growth: coupons, deposit bonuses, VIP levels, reviews, protection settings
                Route::get('growth', [\App\Http\Controllers\Admin\GrowthController::class, 'index'])->name('growth.index');
                Route::post('growth/coupons', [\App\Http\Controllers\Admin\GrowthController::class, 'couponStore'])->name('growth.coupon.store');
                Route::post('growth/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\GrowthController::class, 'couponToggle'])->name('growth.coupon.toggle');
                Route::delete('growth/coupons/{coupon}', [\App\Http\Controllers\Admin\GrowthController::class, 'couponDelete'])->name('growth.coupon.delete');
                Route::post('growth/bonuses', [\App\Http\Controllers\Admin\GrowthController::class, 'bonusSave'])->name('growth.bonus.save');
                Route::post('growth/vip', [\App\Http\Controllers\Admin\GrowthController::class, 'vipSave'])->name('growth.vip.save');
                Route::post('growth/reviews/{review}/status', [\App\Http\Controllers\Admin\GrowthController::class, 'reviewStatus'])->name('growth.review.status');
                Route::delete('growth/reviews/{review}', [\App\Http\Controllers\Admin\GrowthController::class, 'reviewDelete'])->name('growth.review.delete');
                Route::post('growth/settings', [\App\Http\Controllers\Admin\GrowthController::class, 'settingsSave'])->name('growth.settings.save');
                Route::resource('referral-commission', ReferralCommissionController::class)->only(['index', 'store']);
                Route::post('referral-commission/configure', [ReferralCommissionController::class, 'configure'])->name('referral.commission.configure');

                /* ====== Manage API Provider =====*/
                Route::post('system/check-provider-balances', [DashboardController::class, 'checkProviderBalances'])->name('system.check.balances');
                Route::post('system/alert-email', [DashboardController::class, 'saveAlertEmail'])->name('system.alert.email');
                Route::resource('api-provider', ApiProviderController::class);
                Route::group(['prefix' => 'api-provider', 'as' => 'provider.'], function () {
                    Route::post('status/{id}', [ApiProviderController::class, 'providerStatus'])->name('status');
                    Route::post('set-currency/{id}', [ApiProviderController::class, 'setCurrency'])->name('set.currency');
                    Route::post('price-update/{id}', [ApiProviderController::class, 'priceUpdate'])->name('price.update');
                    Route::post('balance-update/{id}', [ApiProviderController::class, 'balanceUpdate'])->name('balance.update');
                });

                /* ====== Manage Social Media  =====*/
                Route::resource('social-media', SocialMediaController::class);
                Route::post('social-media/status/{id}', [SocialMediaController::class, 'statusChange'])->name('social.media.status.change');
                Route::post('social-media/sort-by', [SocialMediaController::class, 'sortBy'])->name('social.media.sort.by');

                /* ====== Manage Category Controller  =====*/
                Route::resource('category', CategoryController::class)->except('show');
                Route::get('category/show', [CategoryController::class, 'categoryShowingWithDatatable'])->name('category.showing.with.datatable');
                Route::post('category/status-change/{id}', [CategoryController::class, 'changeStatus'])->name('category.change.status');
                Route::post('category/active-inactive-multiple', [CategoryController::class, 'activeInactive'])->name('category.active.inactive');
                Route::post('category/delete-multiple', [CategoryController::class, 'deleteMultiple'])->name('category.delete.multiple');
                Route::post('category/sorting', [CategoryController::class, 'sorting'])->name('category.sorting');

                /* ====== Manage API Services  =====*/
                Route::resource('service', ServiceController::class)->except('show');
                Route::post('service/status-change/{id}', [ServiceController::class, 'serviceStatusChange'])->name('service.status.change');
                Route::match(['get', 'post'], 'api-services', [ServiceController::class, 'getApiServices'])->name('get.api.services');
                Route::get('list-api-services/{id}', [ServiceController::class, 'listOfApiServices'])->name('list.api.services');
                Route::post('api-services/import', [ServiceController::class, 'import'])->name('api.service.import');
                Route::post('api-services/import/multiple', [ServiceController::class, 'importMultiple'])->name('api.service.import.multiple');
                Route::post('api-services/quick/preview', [ServiceController::class, 'smartPreview'])->name('api.service.smart.preview');
                Route::post('api-services/quick/import', [ServiceController::class, 'smartImport'])->name('api.service.smart.import');
                Route::post('service/active-inactive-multiple', [ServiceController::class, 'activeInactiveMultiple'])->name('service.active.inactive.multiple');
                Route::post('service/single-price-update/{id}', [ServiceController::class, 'singlePriceUpdate'])->name('service.single.price.update');
                Route::post('service/multiple-price-update', [ServiceController::class, 'multiplePriceUpdate'])->name('service.multiple.price.update');
                Route::post('service/delete-multiple', [ServiceController::class, 'deleteMultiple'])->name('service.delete.multiple');
                Route::delete('delete-all/services', [ServiceController::class, 'deleteAll'])->name('service.delete.all');

                /*====== Manage Currency =====*/
                Route::resource('currency', CurrencyController::class)->except('show');
                Route::group(['prefix' => 'currency', 'as' => 'currency.'], function () {
                    Route::post('status/{id}', [CurrencyController::class, 'statusChange'])->name('status.change');
                    Route::post('auto-update-currency', [CurrencyController::class, 'allAutoUpdateCurrency'])->name('all.auto.update');
                    Route::post('single/currency/auto-update', [CurrencyController::class, 'singleAutoUpdateCurrency'])->name('single.auto.update');
                    Route::post('currency/sort-by', [CurrencyController::class, 'sortBy'])->name('sort.by');
                    Route::get('market-rates', [CurrencyController::class, 'marketRates'])->name('market.rates');
                    Route::post('market-rates/apply', [CurrencyController::class, 'applyMarketRates'])->name('market.apply');
                    Route::get('market-quote', [CurrencyController::class, 'marketQuote'])->name('market.quote');
                    Route::post('auto-update/toggle', [CurrencyController::class, 'toggleAutoUpdate'])->name('auto.toggle');
                    Route::post('add-all', [CurrencyController::class, 'addAll'])->name('add.all');
                });

                /*====== Manage Order =====*/
                Route::get('orders/{status?}', [OrderController::class, 'index'])->name('order');
                Route::get('orders/show/{status?}', [OrderController::class, 'show'])->name('order.show');
                Route::post('orders/status-change/{id}', [OrderController::class, 'statusChange'])->name('order.status.change');
                Route::delete('orders/delete/{id}', [OrderController::class, 'delete'])->name('order.delete');
                Route::get('orders/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
                Route::put('orders/update/{id}', [OrderController::class, 'update'])->name('order.update');
                Route::post('orders/multi-status-change', [OrderController::class, 'multiStatusChange'])->name('multi.status.change');
                Route::post('order/resend/{id}', [OrderController::class, 'resend'])->name('order.resend');


                /* ====== Manage Blog  =====*/
                Route::resource('blog-category', BlogCategoryController::class);
                Route::get('blog/category/edit/{id}/{language?}', [BlogCategoryController::class, 'edit'])->name('blog.category.edit');
                Route::post('blog/category/update/{id}/{language?}', [BlogCategoryController::class, 'update'])->name('blog.category.update');

                Route::resource('blogs', BlogController::class);
                Route::get('blog/edit/{id}/{language?}', [BlogController::class, 'edit'])->name('blog.edit');
                Route::post('blog/update/{id}', [BlogController::class, 'update'])->name('blog.update');
                Route::post('blog/slug/update', [BlogController::class, 'slugUpdate'])->name('blog.slug.update');
                Route::get('blog/seo/page/{id}', [BlogController::class, 'seo'])->name('blog.seo');
                Route::post('blog/seo/update/{id}', [BlogController::class, 'seoUpdate'])->name('blog.seo.update');

                Route::resource('author', AuthorController::class);
                Route::get('author/edit/{id}/{language?}', [AuthorController::class, 'edit'])->name('author.edit');
                Route::put('author/update/{id}/{language?}', [AuthorController::class, 'update'])->name('author.update');
                Route::post('author/slug/update', [AuthorController::class, 'slugUpdate'])
                    ->name('author.slug.update');


                Route::get('socialite', [SocialiteController::class, 'index'])->name('socialite.index');
                Route::match(['get', 'post'], 'google-config', [SocialiteController::class, 'googleConfig'])->name('google.control');
                Route::match(['get', 'post'], 'facebook-config', [SocialiteController::class, 'facebookConfig'])->name('facebook.control');
                Route::match(['get', 'post'], 'github-config', [SocialiteController::class, 'githubConfig'])->name('github.control');

                Route::any('pwa', [PwaController::class, 'create'])->name('pwa.create');

                /* ===== 2Fa Security ===== */
                Route::get('2fa/security', [TwoFaSecurityController::class, 'twoStepSecurity'])->name('two.step.security');
                Route::post('2fa-enable', [TwoFaSecurityController::class, 'twoStepEnable'])->name('two.step.enable');
                Route::post('2fa-disable', [TwoFaSecurityController::class, 'twoStepDisable'])->name('two.step.disable');
                Route::post('2fa/re-generate', [TwoFaSecurityController::class, 'twoStepRegenerate'])->name('two.step.regenerate');
            });


        });
    });
});


if (Module::has('ChildPanel') && Module::isEnabled('ChildPanel')) {
    Route::middleware(['auth:admin', 'checkActiveUser', 'demo'])
        ->group(module_path('ChildPanel', '/routes/admin.php'));
}
