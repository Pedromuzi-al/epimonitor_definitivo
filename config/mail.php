<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mailer Padrão
    |--------------------------------------------------------------------------
    |
    | Esta opção controla o mailer padrão que é usado para enviar qualquer email
    | mensagens enviadas pela sua aplicação. Mailers alternativos podem ser configurados
    | e usados conforme necessário; no entanto, este mailer será usado por padrão.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Configurações do Mailer
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar todos os mailers usados por sua aplicação, além de
    | suas respectivas configurações. Vários exemplos foram configurados para
    | você e você é livre para adicionar o seu conforme sua aplicação exigir.
    |
    | Laravel suporta uma variedade de drivers de "transporte" de email para serem usados
    | ao enviar um e-mail. Você especificará qual você está usando para seu
    | mailers abaixo. Você é livre para adicionar mailers adicionais conforme necessário.
    |
    | Suportado: "smtp", "sendmail", "mailgun", "ses",
    |            "postmark", "log", "array", "failover"
    |
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'auth_mode' => null,
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'mailgun' => [
            'transport' => 'mailgun',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -t -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Endereço "From" Global
    |--------------------------------------------------------------------------
    |
    | Você pode desejar que todos os e-mails enviados por sua aplicação sejam enviados de
    | o mesmo endereço. Aqui, você pode especificar um nome e endereço que é
    | usado globalmente para todos os e-mails enviados por sua aplicação.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
