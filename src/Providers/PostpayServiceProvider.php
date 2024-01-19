<?php

namespace NawrasBukhari\Postpay\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class PostpayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/postpay')
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app->register(HookServiceProvider::class);

    }
}
