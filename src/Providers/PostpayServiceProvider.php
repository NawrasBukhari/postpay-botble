<?php

namespace NawrasBukhari\Postpay\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;


class PostpayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/postpay')
            ->loadHelpers()
            ->publishAssets()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app->booted(function () {
            $this->app['events']->listen(RouteMatched::class, function () {
                if (get_payment_setting('status', POSTPAY_PAYMENT_METHOD_NAME) == 1) {
                    Theme::asset()
                        ->container('footer')
                        ->usePath(false)
                        ->add('postpay-js', asset('vendor/core/plugins/postpay/js/postpay.js'));
                }
            });
        });

        $this->app->register(HookServiceProvider::class);
    }
}
