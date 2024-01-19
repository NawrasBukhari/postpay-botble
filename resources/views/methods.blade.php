@if (get_payment_setting('status', POSTPAY_PAYMENT_METHOD_NAME) == 1)
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
            @include('plugins/postpay::partials.custom_widget')
            @if($selecting == POSTPAY_PAYMENT_METHOD_NAME)
                @include('plugins/postpay::partials.widget')
            @endif
        </div>
    </li>
@endif

