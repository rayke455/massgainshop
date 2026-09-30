<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\khaltiPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\User\KycVerificationController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\MassOrderController;
use App\Http\Controllers\User\TwoFaSecurityController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\ApiController;
use App\Http\Controllers\SocialiteController;
use Nwidart\Modules\Facades\Module;
use App\Http\Controllers\KoraPayPaymentController;
use App\Http\Controllers\MpesaController;

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

$basicControl = basicControl();

Route::get('maintenance-mode', function () {
    if (!basicControl()->is_maintenance_mode) {
        return redirect(route('page'));
    }
    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');


Route::group(['middleware' => ['maintenanceMode', 'checkActiveUser', 'CheckModuleIsEnable']], function () use ($basicControl) {

    Route::get('language/{locale}', function ($locale) {
        app()->setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('language');

    Route::get('switch', function () {
        $language = \App\Models\Language::where('default_status', 1)->first();
        app()->setLocale($language->short_name);
        session()->put('lang', $language->short_name);
        return redirect()->route('page');
    })->name('switch.language');

    Route::get('instruction/page', function () {
        return view('instruction-page');
    })->name('instructionPage');

    Route::get('register/{sponsor?}', [RegisterController::class, 'sponsor'])->name('register.sponsor')->middleware('guest');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPassword'])->name('user.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login-submit', [UserLoginController::class, 'login'])->name('login.submit');
    });

    Route::group(['middleware' => ['auth', 'previewUserDashboard'], 'prefix' => 'user', 'as' => 'user.'], function () {

        Route::get('check', [VerificationController::class, 'check'])->name('check');
        Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('resend.code');
        Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('mail.verify');
        Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('sms.verify');
        Route::post('two-fa-verify', [VerificationController::class, 'twoFaVerify'])->name('twoFA-Verify');

        Route::middleware('userCheck')->group(function () {

            Route::middleware('kyc')->group(function () {

                Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
                Route::post('save-token', [HomeController::class, 'saveToken'])->name('save.token');
                Route::get("selected-currency", [HomeController::class, 'selectedCurrency'])->name('selected.currency');
                Route::get('notice', [HomeController::class, 'notice'])->name('notice');

                Route::get('profile', [HomeController::class, 'profile'])->name('profile');
                Route::post('profile-update', [HomeController::class, 'profileUpdate'])->name('profile.update');
                Route::post('profile-update/image', [HomeController::class, 'profileImageUpdate'])->name('profile.image.update');
                Route::get('password-setting', [HomeController::class, 'passwordSetting'])->name('password.setting');
                Route::post('update/password', [HomeController::class, 'updatePassword'])->name('update.password');
                Route::get('notification-settings', [HomeController::class, 'notificationSettings'])->name('notification.settings');
                Route::post('notification-permission', [HomeController::class, 'notificationPermission'])->name('notification.permission');
                Route::get('referral', [HomeController::class, 'referral'])->name('referral');
                Route::get('referral/bonus', [HomeController::class, 'referralBonus'])->name('referral.bonus');
                Route::post('referral/user-bonus', [HomeController::class, 'getReferralUser'])->name('myGetDirectReferralUser');

                /* ===== 2Fa Security ===== */
                Route::get('two-step/security', [TwoFaSecurityController::class, 'twoStepSecurity'])->name('two.step.security');
                Route::post('twoStep-enable', [TwoFaSecurityController::class, 'twoStepEnable'])->name('two.step.enable');
                Route::post('twoStep-disable', [TwoFaSecurityController::class, 'twoStepDisable'])->name('two.step.disable');
                Route::post('twoStep/re-generate', [TwoFaSecurityController::class, 'twoStepRegenerate'])->name('two.step.regenerate');

                Route::get('ticket', [SupportTicketController::class, 'index'])->name('ticket.list');
                Route::get('ticket/create', [SupportTicketController::class, 'create'])->name('ticket.create');
                Route::post('ticket/create', [SupportTicketController::class, 'store'])->name('ticket.store');
                Route::get('ticket/view/{ticket}', [SupportTicketController::class, 'ticketView'])->name('ticket.view');
                Route::put('ticket/reply/{ticket}', [SupportTicketController::class, 'reply'])->name('ticket.reply');
                Route::get('ticket/download/{ticket}', [SupportTicketController::class, 'download'])->name('ticket.download');

                /* ===== Order Manage ===== */
                Route::get('orders/{status?}', [OrderController::class, 'index'])->name('order.index');
                Route::get('order/create', [OrderController::class, 'create'])->name('order.create');
                Route::post('order/store', [OrderController::class, 'store'])->name('order.store');
                Route::get('get-category-service', [OrderController::class, 'getCategoryService'])->name('get.category.service');
                Route::get('order/refill/{status?}', [OrderController::class, 'showOrderRefill'])->name('show.order.refill');
                Route::put('order/refill/{id}', [OrderController::class, 'orderRefill'])->name('order.refill');
                Route::get('order/drip-feed/{status?}', [OrderController::class, 'showDripFeed'])->name('show.drip.feed');

                /* ===== Mass Order Manage ===== */
                Route::get('mass/order', [MassOrderController::class, 'massOrder'])->name('mass.order');
                Route::post('draft-order/store', [MassOrderController::class, 'draftOrderStore'])->name('draft.order.store');
                Route::get('mass/draft/order', [MassOrderController::class, 'showDraftOrder'])->name('show.draft.order');
                Route::post('mass-order/store', [MassOrderController::class, 'massOrderStore'])->name('mass.order.store');
                Route::get('mass/order/draft/{order_number}', [MassOrderController::class, 'draftMassOrder'])->name('draft.mass.order');
                Route::post('edit/draft-order', [MassOrderController::class, 'editDraftOrder'])->name('edit.draft.order');

                /* ===== Add Deposits ===== */
                Route::get('add-fund', [HomeController::class, 'addFund'])->name('add.fund');
                Route::get('deposit/history', [HomeController::class, 'fund'])->name('fund.index');
                Route::get('transaction/history', [HomeController::class, 'transactionHistory'])->name('transaction.history');

                Route::get('api/docs', [ApiController::class, 'index'])->name('api.docs');
                Route::post('keyGenerate', [ApiController::class, 'apiGenerate'])->name('keyGenerate');
                Route::post('api-key-view', [ApiController::class, 'apiKeyView'])->name('api.key.view');
            });

            /* ===== Push Notification ===== */
            Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
            Route::get('push.notification.readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
            Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

            Route::get('kyc/verification', [KycVerificationController::class, 'index'])->name('kyc.verification');
            Route::get('kyc/verification/form/{id}', [KycVerificationController::class, 'kycForm'])->name('kyc.verification.form');
            Route::post('kyc/verification/submit', [KycVerificationController::class, 'verificationSubmit'])->name('kyc.verification.submit');
            Route::get('kyc/verification/history', [KycVerificationController::class, 'history'])->name('kyc.verification.history');
        });

        Route::get('payment-process/{trx_id}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
        Route::post('addFundConfirm/{trx_id}', [PaymentController::class, 'fromSubmit'])->name('add.fund.from.submit');

        /* Manage User Deposit */
        Route::get('supported-currency', [DepositController::class, 'supportedCurrency'])->name('supported.currency');
        Route::post('payment-request', [DepositController::class, 'paymentRequest'])->name('payment.request');
        Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');
    });


    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');
    Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');

    Route::get('services', [ServiceController::class, 'services'])->name('services');
    Route::get('captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');

    Route::get('blog/{slug}', [BlogController::class, 'blogDetails'])->name('blog.details');
    Route::get('blogs/category-wise', [BlogController::class, 'categoryWiseBlog'])->name('category.wise.blog');
    Route::get('blog/author/{slug}', [BlogController::class, 'author'])->name('blog.author');

    Route::post('subscribe', [FrontendController::class, 'subscribe'])->name('subscribe');
    Route::post('contact/send', [FrontendController::class, 'contactSend'])->name('contact.send');

    Route::get('services', [ServiceController::class, 'services'])->name('services');
    Route::get('get-services', [ServiceController::class, 'getServices'])->name('get.services');
    Route::get('get-category', [ServiceController::class, 'getCategory'])->name('get.category');

    Route::post("set-currency-cookie", [FrontendController::class, 'setCurrency'])->name('set.currency');

    Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
    Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');

    //***PaymentMethodForController***//
    Route::post('khalti/payment/verify/{trx}', [khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    Route::get('kora-payment/verify', [KoraPayPaymentController::class, 'verifyPayment'])->name('kora.payment.verify');

    Route::post('mpesa/stkPush/{trx_id}', [MpesaController::class, 'stkPush'])->name('mpesa.stkPush');
    Route::post('mpesa/webhook', [MpesaController::class, 'webhook'])->name('mpesa.webhook');


    Auth::routes();

    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');
});


if (Module::has('ChildPanel') && Module::isEnabled('ChildPanel')) {
    Route::middleware(['web'])
        ->group(module_path('ChildPanel', '/routes/site.php'));
}



