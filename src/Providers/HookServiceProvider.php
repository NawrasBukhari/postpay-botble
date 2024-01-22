<?php

namespace NawrasBukhari\Postpay\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Payment\Enums\PaymentMethodEnum;
use Botble\Payment\Facades\PaymentMethods;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use NawrasBukhari\Postpay\Forms\PostpayPaymentMethodForm;
use NawrasBukhari\Postpay\Services\Gateways\PostpayPaymentService;
use NawrasBukhari\Postpay\Services\Postpay;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerPostpayMethod'], 17, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithPostpay'], 17, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 98, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['POSTPAY'] = POSTPAY_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 22, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == POSTPAY_PAYMENT_METHOD_NAME) {
                $value = 'Postpay';
            }

            return $value;
        }, 22, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == POSTPAY_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 22, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == POSTPAY_PAYMENT_METHOD_NAME) {
                $data = PostpayPaymentService::class;
            }

            return $data;
        }, 21, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == POSTPAY_PAYMENT_METHOD_NAME) {
                $paymentService = (new PostpayPaymentService());
                $paymentDetail = $paymentService->getPaymentDetails($payment);
                if ($paymentDetail) {
                    $data = view(
                        'plugins/postpay::detail',
                        ['payment' => $paymentDetail, 'paymentModel' => $payment]
                    )->render();
                }
            }

            return $data;
        }, 21, 2);

    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . PostpayPaymentMethodForm::create()->renderForm();
    }

    public function registerPostpayMethod(?string $html, array $data): string
    {
        PaymentMethods::method(POSTPAY_PAYMENT_METHOD_NAME, [
            'html' => view('plugins/postpay::methods', $data)->render(),
        ]);

        return $html;
    }

    public function checkoutWithPostpay(array $data, Request $request): array
    {
        if ($data['type'] !== POSTPAY_PAYMENT_METHOD_NAME) {
            return $data;
        }

        $paymentData = apply_filters(PAYMENT_FILTER_PAYMENT_DATA, [], $request);

        $orderIds = $paymentData['order_id'];
        $orderId = Arr::first($orderIds);
        $orderAddress = $paymentData['address'];
        $taxAmount = 0;
        $numInstallments = checkIfInstallmentsAllowed();
        $name = explode(' ', $orderAddress['name']);
        $firstName = $name[0];
        $lastName = $name[1];
        $uniqueID = $orderId . '-' . uniqid();
        if (count($name) > 2) {
            $lastName = $name[2];
        }

        try {
            $params = [
                'order_id' => (string)$uniqueID,
                'total_amount' => (float)toDecimal($paymentData['amount']),
                'currency' => (string)$paymentData['currency'],
                'tax_amount' => (float)toDecimal($taxAmount),
                'num_instalments' => (int)$numInstallments,
                'shipping' => [
                    'id' => (string)$uniqueID,
                    'name' => (string)$paymentData['shipping_method'],
                    'amount' => (float)toDecimal(40),
                    'address' => [
                        'first_name' => (string)$firstName,
                        'last_name' => (string)$lastName,
                        'line1' => (string)$orderAddress['address'],
                        'city' => (string)'Dubai',
                        'country' => (string)'AE',
                    ],
                ],

                'customer' => [
                    'id' => (string)date('Ymd') . mt_rand(100, 10000),
                    'email' => $orderAddress['email'],
                    'first_name' => (string)$firstName,
                    'last_name' => (string)$lastName,
                ],
                'items' => [
                    [
                        'reference' => (string)$uniqueID,
                        'name' => (string)$paymentData['products'][0]['name'],
                        'unit_price' => (float)toDecimal($paymentData['products'][0]['price']),
                        'qty' => (int)$paymentData['products'][0]['qty'],
                    ],
                ],

                'merchant' => [
                    'confirmation_url' => route('postpay.payment.callback'),
                    'cancel_url' => $paymentData['return_url'],
                ],
                'metadata' => [
                    'notes' => BaseHelper::clean($request->input('description')),
                    'customer_type' => $paymentData['customer_type'],
                ],
            ];

            $checkout = (new Postpay())->checkout($params);

            if ($checkout['redirect_url']) {
                $data['redirect'] = $checkout['redirect_url'];
                header('Location: ' . $checkout['redirect_url']);
            }

        } catch (Exception $e) {
            $data['error'] = true;
            $data['message'] = $e->getMessage() . ' At File ' . $e->getFile() . ' In Line ' . $e->getLine();
            Log::error($data['message']);

            return $data;
        }

        return $data;
    }
}
