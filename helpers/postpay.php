<?php

if (! function_exists('showInstallmentTotalPrice')) {
    function showInstallmentTotalPrice(int $amount): int
    {
        return $amount * 100;
    }
}

if (! function_exists('checkIfInstallmentsAllowed')) {
    /**
     * According to the documentation if you want to disable installments then the value should be 1
     *
     * @check https://ui.postpay.io/widgets
     */
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

if (! function_exists('toDecimal')) {

    function toDecimal($number): float
    {
        return $number * 100;
    }
}

if (!function_exists('postpaySandbox')){
    function postpaySandbox(): array|string
    {
        return app()->environment() === 'production' ? '' : ['sandbox' =>  true];
    }
}
