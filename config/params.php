<?php

return [
    'adminEmail' => 'admin@example.com',
    'coinReader' => [
        'url' => 'ws://127.0.0.1:8080/coins',
    ],
    'coinSender' => [
        'boxId' => null, # please define in local config
        'worker' => 'SendCoinsWorker',
    ],
];
