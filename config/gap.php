<?php
return [
    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),
        'username' => env('MELIPAYAMAK_USERNAME'),
        'password' => env('MELIPAYAMAK_PASSWORD'),
        'from' => env('MELIPAYAMAK_FROM_NUMBER'),
        'otp_body_id' => env('MELIPAYAMAK_OTP_BODY_ID'),
    ],
    'zarinpal' => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        'sandbox' => env('ZARINPAL_SANDBOX', true),
    ],
    'auto_approve' => env('COMPANY_AUTO_APPROVE', true),
];
