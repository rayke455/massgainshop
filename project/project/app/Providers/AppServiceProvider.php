<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            DB::connection()->getPdo();

            $data['basicControl'] = basicControl();
            View::share($data);

            view()->composer([
                'themes.light_green.partials.header',
                'themes.dark_voilet.partials.header',
                'themes.minimal.partials.header',
                'themes.light_orange.partials.header',
                'themes.dark_mode.partials.header',
                'themes.deep_blue.partials.header',
                'themes.light_green.sections.footer',
                'themes.dark_voilet.sections.footer',
                'themes.minimal.sections.footer',
                'themes.deep_blue.sections.footer',
                'themes.light_orange.sections.footer',
                'themes.dark_mode.sections.footer',
                'themes.light_green.user.partials.navbar',
                'themes.dark_voilet.user.partials.header',
                'themes.dark_voilet.user.partials.sidebar',
                'themes.dark_voilet.partials.banner',
            ], function ($view) {

                $languages = Language::orderBy('name')->where('status', 1)->get();
                $view->with('languages', $languages);

                $currencies = Currency::where('status', 1)->orderBy('sort_by', 'asc')->get();
                $view->with('currencies', $currencies);

                $curr = Currency::where('code', auth()->user()->currency ?? 'USD')->first();
                $view->with('curr', $curr);
            });

            if (basicControl()->force_ssl == 1) {
                if ($this->app->environment('production') || $this->app->environment('local')) {
                    \URL::forceScheme('https');
                }
            }

            Paginator::useBootstrap();

            Mail::extend('sendinblue', function () {
                return (new SendinblueTransportFactory)->create(
                    new Dsn(
                        'sendinblue+api',
                        'default',
                        config('services.sendinblue.key')
                    )
                );
            });

            Mail::extend('sendgrid', function () {
                return (new SendgridTransportFactory)->create(
                    new Dsn(
                        'sendgrid+api',
                        'default',
                        config('services.sendgrid.key')
                    )
                );
            });

            Mail::extend('mandrill', function () {
                return (new MandrillTransportFactory)->create(
                    new Dsn(
                        'mandrill+api',
                        'default',
                        config('services.mandrill.key')
                    )
                );
            });

        } catch (\Exception $e) {
        }

    }
}
