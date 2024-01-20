<?php

namespace NawrasBukhari\Postpay\Services;

use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Postpay\Exceptions\PostpayException;
use Postpay\Exceptions\RESTfulException;
use Postpay\Postpay as PostpayBase;

class Postpay
{
    use PaymentErrorTrait;

    /**
     * @throws PostpayException
     */
    public function postpay(): PostpayBase
    {
        return new PostpayBase([
            'merchant_id' => get_payment_setting('merchant_id', POSTPAY_PAYMENT_METHOD_NAME),
            'secret_key' => get_payment_setting('secret_key', POSTPAY_PAYMENT_METHOD_NAME),
            'sandbox' => '',
        ]);
    }

    /**
     * @throws PostpayException
     */
    public function checkout($parameters): array
    {
        $relativeUrl = '/checkouts';

        try {

            $checkout = $this->postpay()->post($relativeUrl, $parameters);
            if (!$checkout->isError()) {
                return $checkout->json();
            }

            throw new RESTfulException($checkout->json()['fields']);
        } catch (RESTfulException|PostpayException $exception) {
            $this->setErrorMessage($exception->getMessage());
            throw new PostpayException('[ Error Code: ' . $exception->getErrorCode() . ' - And Message is: ' . $exception->getMessage() . ']');
        }
    }

    /**
     * @throws PostpayException
     */
    public function capture($order_id): array
    {
        $relativeUrl = "/orders/$order_id/capture";

        $request = $this->postpay()->post($relativeUrl);

        if (!$request->isError()) {
            return $request->json();
        }

        throw new RESTfulException($request);
    }

    /**
     * @throws RESTfulException
     * @throws PostpayException
     */
    public function getListTransactions(array $params): array
    {
        $relativeUrl = '/transactions' . ($params ? ('?' . http_build_query($params)) : '');

        $request = $this->postpay()->query($relativeUrl);

        if (!$request->isError()) {
            return $request->json();
        }

        throw new RESTfulException($request);
    }
}
