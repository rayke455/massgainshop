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
// Custom rental hosts are dispatched by ResolveRentalHost after CSRF checks.
// On the main host these paths deliberately have no public action.
Route::post('/panel-order', fn () => abort(404));
Route::get('/track/{token}', fn () => abort(404));

Route::get('maintenance-mode', function () {
    if (!basicControl()->is_maintenance_mode) {
        return redirect(route('page'));
    }
    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');


// Safaricom payment callbacks: must work on whatever public address Safaricom was given (tunnel, www/non-www),
// so this sits outside the single-domain CheckModuleIsEnable group. Payments are re-verified before crediting.
Route::post('mpesa/webhook', [MpesaController::class, 'webhook'])->name('mpesa.webhook');
Route::post('rentals/payout-result', [\App\Http\Controllers\PanelRentalController::class, 'payoutResult'])->middleware('throttle:60,1')->name('rentals.payout.result');
Route::post('snippe/webhook', [\App\Http\Controllers\SnippeController::class, 'webhook'])->middleware('throttle:120,1')->name('snippe.webhook');

Route::group(['middleware' => ['maintenanceMode', 'checkActiveUser', 'CheckModuleIsEnable']], function () use ($basicControl) {
    Route::get('/order-without-account', [\App\Http\Controllers\GuestOrderController::class, 'index'])->name('guest.order');
    Route::post('/order-without-account', [\App\Http\Controllers\GuestOrderController::class, 'store'])->middleware('throttle:3,1')->block(40,40)->name('guest.store');
    Route::get('/guest-orders/{token}', [\App\Http\Controllers\GuestOrderController::class, 'track'])->whereUuid('token')->name('guest.track');
    Route::get('/guest-orders/{token}/status', [\App\Http\Controllers\GuestOrderController::class, 'status'])->whereUuid('token')->middleware('throttle:10,1')->name('guest.status');

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
                Route::post('favorite/{service}', [\App\Http\Controllers\User\GrowthController::class, 'toggleFavorite'])->middleware('throttle:60,1')->name('favorite.toggle');
                Route::post('order/{order}/cancel', [\App\Http\Controllers\User\GrowthController::class, 'cancelOrder'])->middleware('throttle:10,1')->name('order.cancel');
                Route::post('order/{order}/review', [\App\Http\Controllers\User\GrowthController::class, 'storeReview'])->middleware('throttle:10,1')->name('order.review');
                Route::post('free-trial', [\App\Http\Controllers\User\GrowthController::class, 'claimTrial'])->middleware('throttle:5,1')->name('free.trial');
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
        Route::get('deposit-bonus-preview', [DepositController::class, 'bonusPreview'])->middleware('throttle:60,1')->name('deposit.bonusPreview');
    });


    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');
    Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');

    Route::get('services', [ServiceController::class, 'services'])->name('services');
    Route::get('buy', [\App\Http\Controllers\Frontend\LandingController::class, 'index'])->name('landing.index');
    Route::get('buy/{slug}/{country?}', [\App\Http\Controllers\Frontend\LandingController::class, 'show'])->where(['slug' => '[a-z0-9-]+', 'country' => '[a-z-]+'])->name('landing.show');
    Route::get('sitemap.xml', [\App\Http\Controllers\Frontend\LandingController::class, 'sitemap'])->name('sitemap');
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

    Route::get('track-order', [\App\Http\Controllers\Frontend\OrderTrackController::class, 'track'])
        ->middleware('throttle:15,1')->name('order.track');

    Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
    Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');

    //***PaymentMethodForController***//
    Route::post('khalti/payment/verify/{trx}', [khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    Route::get('kora-payment/verify', [KoraPayPaymentController::class, 'verifyPayment'])->name('kora.payment.verify');

    Route::post('mpesa/stkPush/{trx_id}', [MpesaController::class, 'stkPush'])->name('mpesa.stkPush');
    Route::get('mpesa/status/{trx_id}', [MpesaController::class, 'status'])->middleware('auth')->name('mpesa.status');
    Route::post('snippe/pay/{trx_id}', [\App\Http\Controllers\SnippeController::class, 'pay'])->middleware(['auth', 'throttle:10,1'])->name('snippe.pay');
    Route::get('snippe/status/{trx_id}', [\App\Http\Controllers\SnippeController::class, 'status'])->middleware(['auth', 'throttle:40,1'])->name('snippe.status');


    Route::middleware(['auth', 'userCheck'])->prefix('user/panels')->name('user.panels.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PanelRentalController::class, 'index'])->name('index');
        Route::get('/preview', [\App\Http\Controllers\PanelRentalController::class, 'preview'])->middleware('throttle:60,1')->name('preview');
        Route::post('/payout-destination', [\App\Http\Controllers\PanelRentalController::class, 'savePayoutDestination'])->middleware('throttle:5,1')->name('destination');
        Route::post('/withdraw', [\App\Http\Controllers\PanelRentalController::class, 'withdraw'])->middleware('throttle:5,1')->block(10, 10)->name('withdraw');
        Route::post('/earnings-to-wallet', [\App\Http\Controllers\PanelRentalController::class, 'earningsToWallet'])->middleware('throttle:10,1')->name('towallet');
        Route::post('/{id}/prices', [\App\Http\Controllers\PanelRentalController::class, 'prices'])->whereNumber('id')->middleware('throttle:20,1')->name('prices');
        Route::post('/', [\App\Http\Controllers\PanelRentalController::class, 'store'])->middleware('throttle:5,1')->name('store');
        Route::get('/{id}', [\App\Http\Controllers\PanelRentalController::class, 'show'])->whereNumber('id')->name('show');
        Route::post('/{id}/settings', [\App\Http\Controllers\PanelRentalController::class, 'update'])->whereNumber('id')->name('update');
        Route::post('/{id}/verify', [\App\Http\Controllers\PanelRentalController::class, 'verify'])->middleware('throttle:3,1')->name('verify');
        Route::post('/{id}/renew', [\App\Http\Controllers\PanelRentalController::class, 'renew'])->middleware('throttle:2,1')->block(10, 10)->name('renew');
        Route::post('/{id}/requests/{requestId}', [\App\Http\Controllers\PanelRentalController::class, 'linkOrder'])->name('link');
    });
    Route::get('/panels/{slug}', [\App\Http\Controllers\RentalStorefrontController::class, 'show'])->name('panels.show');
    Route::post('/panels/{slug}/panel-order', [\App\Http\Controllers\RentalStorefrontController::class, 'order'])->middleware('throttle:5,1')->name('panels.order');
    Route::get('/panels/{slug}/track/{token}', [\App\Http\Controllers\RentalStorefrontController::class, 'track'])->middleware('throttle:30,1')->name('panels.track');
    Route::get('/panels/{slug}/pay-status/{token}', [\App\Http\Controllers\RentalStorefrontController::class, 'payStatus'])->middleware('throttle:40,1')->name('panels.paystatus');

    Auth::routes();

    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');
});


if (Module::has('ChildPanel') && Module::isEnabled('ChildPanel')) {
    Route::middleware(['web'])
        ->group(module_path('ChildPanel', '/routes/site.php'));
}



