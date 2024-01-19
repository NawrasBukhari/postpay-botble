<?php

namespace NawrasBukhari\Postpay\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Http\Request;
use NawrasBukhari\Postpay\Services\Postpay;

class PostpayController extends BaseController
{
    /**
     * Get the payment status from the endpoint.
     *
     *
     * @return BaseHttpResponse|string
     *
     */
    public function getPaymentStatus(Request $request, BaseHttpResponse $response)
    {
        try {
            $requestStatus = strtolower(trim($request->get('status')));

            $capture = (new Postpay())->capture($request->get('order_id'));
            $status = strtolower(trim($capture['status']));
            $order_id = $capture['order_id'];

            if ($requestStatus !== 'approved' || $status !== 'captured') {
                return $response
                    ->setError()
                    ->setNextUrl(PaymentHelper::getCancelURL())
                    ->setMessage(__('Checkout failed! Please try again.'));
            }

            // Perform action on payment processed
            do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                'order_id' => (string)$order_id,
                'status' => (string)PaymentStatusEnum::COMPLETED,
                'amount' => $capture['total_amount'] / 100,
                'currency' => $capture['currency'],
                'charge_id' => $capture['order_id'],
                'payment_channel' => POSTPAY_PAYMENT_METHOD_NAME,
                'customer_id' => $capture['customer']['id'],
                'customer_type' => 'customer',
                'payment_type' => 'direct',
            ], $request);

            // Return success response
            return $response
                ->setNextUrl(PaymentHelper::getRedirectURL())
                ->setMessage(__('Checkout successfully!'));

        } catch (\Exception $exception) {
            
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Checkout failed! Please try again. (Postpay)'));
        }
    }
}
