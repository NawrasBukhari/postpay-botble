<?php

namespace NawrasBukhari\Postpay\Services\Abstracts;

use Botble\Payment\Services\Traits\PaymentErrorTrait;
use Botble\Support\Services\ProduceServiceInterface;
use Exception;
use Illuminate\Http\Request;
use NawrasBukhari\Postpay\Services\Postpay;

abstract class PostpayPaymentAbstract implements ProduceServiceInterface
{
    use PaymentErrorTrait;

    protected ?string $paymentCurrency = null;

    protected bool $supportRefundOnline;

    protected float $totalAmount;

    public function __construct()
    {
        $this->paymentCurrency = config('plugins.payment.payment.currency');

        $this->totalAmount = 0;

        $this->supportRefundOnline = true;
    }

    public function getPaymentDetails($payment)
    {
        try {
            $params = [
                'created__gte' => $payment->created_at->subDays(1)->toISOString(),
                'created__lt' => $payment->created_at->addDays(1)->toISOString(),
            ];

            $postpay = new Postpay();
            $response = $postpay->getListTransactions($params);

            if ($response['status']) {
                return collect($response['data'])->firstWhere('reference', $payment->charge_id);
            }
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }

        return false;
    }

    public function execute(Request $request)
    {
        try {
            return $this->makePayment($request);
        } catch (Exception $exception) {
            $this->setErrorMessageAndLogging($exception, 1);

            return false;
        }
    }

    abstract public function makePayment(Request $request);

    abstract public function afterMakePayment(Request $request);
}
