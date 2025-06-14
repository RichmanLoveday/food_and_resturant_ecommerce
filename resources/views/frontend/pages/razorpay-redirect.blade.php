<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        #razorpay-payment-form {
            display: none;
        }

        .razorpay-payment-button {
            display: none;
        }
    </style>
</head>

<body>
    @php
        $grand_total = session()->get('grand_total');
        $payableAmount = $grand_total * config('gatewaySettings.razorpay_rate');
        $payableAmount = $payableAmount * 100;
    @endphp

    <form action="{{ route('razorpay.payment') }}" method="POST" id="razorpay-payment-form">
        @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ config('gatewaySettings.razorpay_api_key') }}"
            data-currency="{{ config('gatewaySettings.razorpay_currency') }}" data-amount="{{ $payableAmount }}"
            data-buttontext="pay" data-name="Payment" data-description="Payment for product" data-prefill.name="Jhon"
            data-prefill.email="test@gmail.com" data-theme.color="#ff7529"></script>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var button = document.querySelector('.razorpay-payment-button');
            button.click();
        });
    </script>
</body>

</html>
