<?php

use Botble\Theme\Facades\Theme;

/**
 * According to the documentation, if you want to disable installments, then the value should be 1
 * Maximum number of installments allowed for this payment. If not specified, defaults to 1.
 *
 * @return int
 */
if (! function_exists('checkIfInstallmentsAllowed')) {
    function checkIfInstallmentsAllowed(): int
    {
        if ((string) get_payment_setting('installments_allowed', POSTPAY_PAYMENT_METHOD_NAME) === 'true') {
            return 3;
        } else {
            return 1;
        }
    }
}

if (! function_exists('postpayEnv')) {

    function postpayEnv(): string
    {
        return app()->environment() === 'production' ? 'prod' : 'sandbox';
    }
}

/**
 * @param $number
 * @return float
 * We use this function to convert the price from integer to decimal
 * according to the documentation the price should be in decimal
 *
 * @check https://docs.postpay.io/v1/#data-types
 */
if (! function_exists('toDecimal')) {

    function toDecimal($number): float
    {
        return $number * 100;
    }
}

/**
 * @return string
 * We use this function to load the script in the checkout page
 * since I have not found a proper way to load dynamically from PostpayServiceProvider.php
 *
 * @todo find a better way to load the script
 */
if (! function_exists('postpayLoadScript')) {
    function postpayLoadScript(): string
    {
        return Theme::asset()->get(POSTPAY_PAYMENT_METHOD_NAME)['source'] ?? '';
    }
}

/**
 * @return string
 * Making the sandbox status dynamic so the developer can change it from the admin panel
 * without changing the code in the plugin.
 */
if (! function_exists('postpaySandboxStatus')) {
    function postpaySandboxStatus(): bool
    {
        return (string) get_payment_setting('sandbox', POSTPAY_PAYMENT_METHOD_NAME) === 'enabled';
    }
}
