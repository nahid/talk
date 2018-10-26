<?php

return [
    'user' => [
        'model' => 'App\User',
        'foreignKey' => null,
        'ownerKey' => null,
    ],
    'broadcast' => [
        'enable' => false,
        'app_name' => 'your-app-name',
        'pusher' => [
            'app_id' => '',
            'app_key' => '',
            'app_secret' => '',
            'options' => [
                'cluster' => 'ap1',
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
