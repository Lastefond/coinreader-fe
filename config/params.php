<?php

return [
    'adminEmail' => 'admin@example.com',
    'coinReader' => [
        'url' => 'ws://127.0.0.1:8080/coins',
    ],
    'coinSender' => [
        'apiUrl' => getenv('COIN_API_URL'),
        'boxId' => getenv('COIN_BOX_ID'),
    ],
];
