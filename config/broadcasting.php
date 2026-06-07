<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcaster Padrão
    |--------------------------------------------------------------------------
    |
    | Esta opção controla o broadcaster padrão que será usado pelo
    | framework quando um evento precisar ser transmitido. Você pode defini-lo como
    | qualquer uma das conexões definidas no array "connections" abaixo.
    |
    | Suportado: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'null'),

    /*
    |--------------------------------------------------------------------------
    | Conexões de Transmissão
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir todas as conexões de transmissão que serão usadas
    | para transmitir eventos para outros sistemas ou sobre websockets. Exemplos de
    | cada tipo disponível de conexão são fornecidos dentro deste array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
