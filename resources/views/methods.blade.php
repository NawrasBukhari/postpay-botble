@if (get_payment_setting('status', POSTPAY_PAYMENT_METHOD_NAME) == 1)
    @if ($selecting == POSTPAY_PAYMENT_METHOD_NAME) <script src="{{ postpayLoadScript() }}"></script> @endif
    <span hidden="hidden" type="hidden" id="{{ POSTPAY_PAYMENT_METHOD_NAME }}_payment_method_merchant_id"
          data-postpay-merchant-id="{{ get_payment_setting('merchant_id', POSTPAY_PAYMENT_METHOD_NAME) }}"></span>
    <li class="list-group-item">
        <input
            class="magic-radio js_payment_method"
            id="payment_{{ POSTPAY_PAYMENT_METHOD_NAME }}"
            name="payment_method"
            type="radio"
            value="{{ POSTPAY_PAYMENT_METHOD_NAME }}"
            @if ($selecting == POSTPAY_PAYMENT_METHOD_NAME) checked @endif
        >
        <label
            for="payment_{{ POSTPAY_PAYMENT_METHOD_NAME }}">{{ get_payment_setting('name', POSTPAY_PAYMENT_METHOD_NAME) }}</label>
        <div
            class="payment_{{ POSTPAY_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if ($selecting == POSTPAY_PAYMENT_METHOD_NAME) show @endif">
            @if($selecting == POSTPAY_PAYMENT_METHOD_NAME)
                <div class="postpay-widget"
                     data-type="payment-summary"
                     data-environment="{{ postpayEnv() }}"
                     data-amount="{{ toDecimal($amount) }}"
                     data-currency="{{ $currency }}"
                     data-num-instalments="{{ checkIfInstallmentsAllowed() }}"
                     data-country="AE"
                     data-locale="en">
                </div>
            @endif
        </div>
    </li>

    <script>
        $(document).ready(function() {
            document.addEventListener('payment-form-reloaded', function () {
                postpay.ui.refresh();
            })
        });
    </script>
@endif

