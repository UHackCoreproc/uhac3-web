<?php

return [
    'accounts' => [
        'source' => [
            'account_no' => env('ACCOUNT_OMEGA')
        ]
    ],

    'apis' => [
        'chikka'    => [
            'base_url'   => 'https://post.chikka.com/smsapi/request',
            'client_id'  => env('CHIKKA_CLIENT_ID'),
            'secret_key' => env('CHIKKA_SECRET_KEY'),
            'short_code' => env('CHIKKA_SHORT_CODE')
        ],
        'paymaya'   => [
            'base_url' => 'https://pg-sandbox.paymaya.com/payments/v1',
            'public'   => env('PAYMAYA_PUBLIC'),
            'secret'   => env('PAYMAYA_SECRET')
        ],
        'firebase'  => [
            'base_url'      => 'https://fcm.googleapis.com/fcm/send',
            'message_token' => env('FIREBASE_MESSAGE_TOKEN'),
            'sender_id'     => env('FIREBASE_SENDER_ID'),
            'access_key'    => env('FIREBASE_ACCESS_KEY')
        ],
        'unionbank' => [
            'base_url'   => 'https://api.us.apiconnect.ibmcloud.com/ubpapi-dev/sb/api/RESTs',
            'client_id'  => env('UNIONBANK_CLIENT_ID'),
            'secret_key' => env('UNIONBANK_SECRET_KEY')
        ]
    ]
];
