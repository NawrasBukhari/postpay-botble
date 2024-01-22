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
     * This function is used to authenticate with Postpay API
     *
     * @check https://docs.postpay.io/v1/#authentication
     */
    public function postpay(): PostpayBase
    {
        $config = [];

        $config['merchant_id'] = get_payment_setting(key: POSTPAY_PAYMENT_MERCHANT_ID_FIELD_NAME, type: POSTPAY_PAYMENT_METHOD_NAME);
        $config['secret_key'] = get_payment_setting(key: POSTPAY_PAYMENT_SECRET_KEY_FIELD_NAME, type: POSTPAY_PAYMENT_METHOD_NAME);

        if (postpaySandboxStatus()) {
            $config['sandbox'] = true;
        } else {
            $config['sandbox'] = false;
        }

        return new PostpayBase($config);
    }

    /**
     * @param $parameters array
     *
     * @return array
     * @throws PostpayException This function is used to create a checkout
     * @check https://docs.postpay.io/v1/#checkout
     */
    public function checkout(array $parameters): array
    {
        $relativeUrl = '/checkouts';

        try {
            $checkout = $this->postpay()->post(path: $relativeUrl, params: $parameters);
            if (!$checkout->isError()) {
                return $checkout->json();
            }

            throw new RESTfulException($checkout->json()['fields']);
        } catch (RESTfulException $exception) {
            $this->setErrorMessage($exception->getMessage());

            throw new PostpayException('[ Error Code: ' . $exception->getErrorCode() . ' - And Message is: ' . $exception->getMessage() . ']');
        }
    }

    /**
     * @throws PostpayException
     * @throws RESTfulException
     * After checkout, we double-check that the checkout is valid by capturing the order
     *
     * @check https://docs.postpay.io/v1/#capture
     */
    public function capture($order_id): array
    {
        try {
            $relativeUrl = "/orders/$order_id/capture";

            $request = $this->postpay()->post($relativeUrl);

            if (!$request->isError()) {
                return $request->json();
            }

            throw new RESTfulException($request);
        } catch (RESTfulException $exception) {
            if (postpaySandboxStatus()) {
                $this->setErrorMessage($exception->getMessage());
            }

            throw new PostpayException($exception->getErrorCode() . ' ' . $exception->getMessage());
        }

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
