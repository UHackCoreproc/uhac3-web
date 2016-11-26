<?php

return [
    'accounts' => [
        'source' => [
            'account_no' => env('ACCOUNT_OMEGA')
        ]
    ],

    'apis' => [
        'chikka'   => [
            'base_url'   => 'https://post.chikka.com/smsapi/request',
            'client_id'  => env('CHIKKA_CLIENT_ID'),
            'secret_key' => env('CHIKKA_SECRET_KEY'),
            'short_code' => env('CHIKKA_SHORT_CODE')
        ],
        'paymaya'  => [
            'base_url' => 'https://pg-sandbox.paymaya.com/payments/v1',
            'public'   => env('PAYMAYA_PUBLIC'),
            'secret'   => env('PAYMAYA_SECRET')
        ],
        'firebase' => [
            'base_url'      => 'https://fcm.googleapis.com/fcm/send',
            'message_token' => 'AAAAHNap_H0:APA91bFFvSEAVAlVRxQMj22TNbkm0BDJF1RvdTNoSzLhagktfb8w6lrf6z5Wx5P4u3pNj8pr1iTEYDBbVvXHzUjp7TMLYrjr18ihYBP7YIJKNyVDPZEsuITta8bzCdkfRQfUjc8Vd0VNVIFDbEkGAnBG0Bq4Ratj0g',
            'sender_id'     => '123860548733',
            'access_key'    => 'AIzaSyDR4oxEAcu-fXlYIjEhernRnful9cFg1j8'
        ]
    ]
];
