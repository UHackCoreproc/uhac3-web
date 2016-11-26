<?php

return [
    'accounts' => [
        'source' => [
            'account_no' => env('ACCOUNT_OMEGA')
        ]
    ],

    'apis' => [
        'chikka'  => [
            'base_url'   => 'https://post.chikka.com/smsapi/request',
            'client_id'  => env('CHIKKA_CLIENT_ID'),
            'secret_key' => env('CHIKKA_SECRET_KEY'),
            'short_code' => env('CHIKKA_SHORT_CODE')
        ],
        'paymaya' => [
            'base_url' => 'https://pg-sandbox.paymaya.com/payments/v1',
            'public'   => env('PAYMAYA_PUBLIC'),
            'secret'   => env('PAYMAYA_SECRET')
        ]
    ]
];
