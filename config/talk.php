<?php

return [
    'user' => [
        'model' => 'App\User',
        'foreignKey' => null,
        'ownerKey' => null,
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => env('PUSHER_APP_NAME'),
        'pusher' => [
            'app_id' => env('PUSHER_APP_ID'),
            'app_key' => env('PUSHER_APP_KEY'),
            'app_secret' => env('PUSHER_APP_SECRET'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true
            ]
        ],
    ],
    'oembed' => [
        'enabled' => false,
        'url' => '',
        'key' => ''
    ]
];
