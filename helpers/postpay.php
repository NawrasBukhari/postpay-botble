<?php

if (! function_exists('showInstallmentTotalPrice')) {
    function showInstallmentTotalPrice(int $amount): int
    {
        return $amount * 100;
    }
}

if (! function_exists('checkIfInstallmentsAllowed')) {
    function checkIfInstallmentsAllowed(): int
    {
        if (get_payment_setting('number_installments', 'postpay') > 1) {
            return get_payment_setting('number_installments', 'postpay');
        } else {
            return 0;
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
