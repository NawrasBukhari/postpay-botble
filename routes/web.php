<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'NawrasBukhari\Postpay\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('postpay/payment/callback', [
        'as' => 'postpay.payment.callback',
        'uses' => 'PostpayController@getPaymentStatus',
    ]);
});
