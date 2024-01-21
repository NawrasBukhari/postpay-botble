<?php

namespace NawrasBukhari\Postpay\Forms;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\TextField;
use Botble\Payment\Forms\PaymentMethodForm;
use Illuminate\Support\Facades\Blade;

class PostpayPaymentMethodForm extends PaymentMethodForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setTitle('Postpay Payment Method')
            ->paymentId(POSTPAY_PAYMENT_METHOD_NAME)
            ->paymentName('Postpay')
            ->paymentDescription(
                'Customer can buy product and pay directly using Visa, Credit card via Postpay or even pay later.'
            )
            ->paymentLogo(url('vendor/core/plugins/postpay/images/postpay.svg'))
            ->paymentUrl('https://postpay.io')
            ->paymentInstructions(
                Blade::render(
                    <<<'BLADE'
                <ol>
                    <li>
                        <p>
                            <a
                                href='https://postpay.io'
                                target='_blank'
                            >
                                {{ __('Register an account on :name', ['name' => 'Postpay']) }}
                            </a>
                        </p>
                    </li>
                    <li>
                        <p>
                            {{ __('After registration at :name, you will have Merchant ID & Secret key', ['name' => 'Postpay']) }}
                        </p>
                    </li>
                    <li>
                        <p>
                            {{ __('Enter Public, Secret into the box in right hand') }}
                        </p>
                    </li>
                    <li>
                        <p>
                            {{ __('Click Save') }}
                        </p>
                    </li>
                </ol>
            BLADE
                )
            )
            ->add(
                'payment_postpay_merchant_id',
                TextField::class,
                TextFieldOption::make()
                    ->label('Merchant ID')
                    ->placeholder('pk_*************')
                    ->value(get_payment_setting('merchant_id', 'postpay') ?? '')
                    ->attributes(['data-counter' => 400])
                    ->toArray()
            )->add(
                'payment_postpay_secret_key',
                'password',
                TextFieldOption::make()
                    ->label('Secret Key')
                    ->placeholder('sk_*************')
                    ->value(get_payment_setting('secret_key', 'postpay') ?? '')
                    ->toArray()
            )->add(
                'payment_postpay_installments_allowed',
                'select',
                SelectFieldOption::make()
                    ->label('Allow Installments')
                    ->choices([
                        'true' => __('Yes'),
                        'false' => __('No'),
                    ])->toArray()
            )->add(
                'payment_postpay_sandbox_enabled',
                'select',
                SelectFieldOption::make()
                    ->label(__('Sandbox Mode'))
                    ->helperText(__('Enable sandbox mode to test the payment method.'))
                    ->choices([
                        'enabled' => __('Enable Sandbox'),
                        'disabled' => __('Disable Sandbox'),
                    ])->toArray()
            );
    }
}
