<?php

namespace NawrasBukhari\Postpay\Providers;

use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Theme\Facades\Theme;

class PostpayServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        if (!class_exists('Postpay\Postpay')) {
            if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
                require __DIR__ . '/../../vendor/autoload.php';
            }
        }
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/postpay')
            ->loadHelpers()
            ->publishAssets()
            ->loadAndPublishViews()
            ->loadRoutes();

        $this->app->register(HookServiceProvider::class);

        if ($this->isActivePlugin(POSTPAY_PAYMENT_METHOD_NAME)) {
            Theme::asset()
                ->usePath(false)
                ->add(POSTPAY_PAYMENT_METHOD_NAME, asset('vendor/core/plugins/postpay/js/postpay.js'));
        }
    }

    /**
     * @return mixed
     * This function is used to check if the plugin is active or not based on array value
     */
    private function getValue(array $haystack, $needle): mixed
    {
        return collect($haystack)
            ->first(function ($value) use ($needle) {
                if (is_scalar($value) && $value === $needle) {
                    return true;
                }

                if (is_array($value) && $this->getValue($value, $needle)) {
                    return true;
                }

                return is_object($value) && (string)$value === (string)$needle;
            });
    }

    /**
     * We have a function called get_active_plugins() which return array of active plugins.
     *
     * @param string $plugin in @Botble CMS
     */
    private function isActivePlugin(string $plugin): bool|string
    {
        return $this->getValue(get_active_plugins(), $plugin) === $plugin;
    }
}
