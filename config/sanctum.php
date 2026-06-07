<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Domínios com Estado
    |--------------------------------------------------------------------------
    |
    | As requisições dos seguintes domínios / hosts receberão cookies de
    | autenticação de API com estado. Normalmente, estes devem incluir seu local
    | e domínios de produção que acessam sua API via um SPA frontend.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    /*
    |--------------------------------------------------------------------------
    | Guards do Sanctum
    |--------------------------------------------------------------------------
    |
    | Este array contém os guards de autenticação que serão verificados quando
    | Sanctum está tentando autenticar uma requisição. Se nenhum desses guards
    | conseguir autenticar a requisição, Sanctum usará o bearer token
    | que está presente em uma requisição recebida para autenticação.
    |
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Minutos de Expiração
    |--------------------------------------------------------------------------
    |
    | Este valor controla o número de minutos até que um token emitido será
    | considerado expirado. Se este valor for nulo, os tokens de acesso pessoal não
    | expiram. Isto não ajustará o tempo de vida das sessões de primeira parte.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],

];
