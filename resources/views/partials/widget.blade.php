<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.postpayAsyncInit = function () {
            postpay.init({
                merchantId: '{{ get_payment_setting('merchant_id', 'postpay') }}',
                sandbox: {{ app()->environment() === 'production' }},
                theme: 'light',
                locale: 'en'
            });
        };
    });
</script>

<script async src="https://cdn.postpay.io/v1/js/postpay.js"></script>

<div class="postpay-widget"
     data-type="payment-summary"
     data-environment="{{ postpayEnv() }}"
     data-amount="{{ showInstallmentTotalPrice($amount) }}"
     data-currency="{{ $currency }}"
     data-num-instalments="{{ checkIfInstallmentsAllowed() }}"
     data-country="AE"
     data-locale="en">
</div>
