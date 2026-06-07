<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Padrões de Autenticação
    |--------------------------------------------------------------------------
    |
    | Esta opção controla o "guard" de autenticação padrão e as opções de
    | redefinição de senha para sua aplicação. Você pode alterar esses padrões
    | conforme necessário, mas eles são um ótimo começo para a maioria das aplicações.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Guards de Autenticação
    |--------------------------------------------------------------------------
    |
    | A seguir, você pode definir cada guard de autenticação para sua aplicação.
    | É claro que uma ótima configuração padrão foi definida para você
    | aqui, que usa armazenamento de sessão e o provedor de usuário Eloquent.
    |
    | Todos os drivers de autenticação tém um provedor de usuário. Isso define como os
    | usuários são realmente recuperados do seu banco de dados ou outro armazenamento
    | mecanismos usados por esta aplicação para persistir seus dados de usuário.
    |
    | Suportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Provedores de Usuário
    |--------------------------------------------------------------------------
    |
    | Todos os drivers de autenticação tém um provedor de usuário. Isso define como os
    | usuários são realmente recuperados do seu banco de dados ou outro armazenamento
    | mecanismos usados por esta aplicação para persistir seus dados de usuário.
    |
    | Se você tiver várias tabelas ou modelos de usuário, você pode configurar múltiplas
    | fontes que representam cada modelo / tabela. Essas fontes podem então
    | ser atribuídas a qualquer guard de autenticação extra que você tenha definido.
    |
    | Suportado: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redefinindo Senhas
    |--------------------------------------------------------------------------
    |
    | Você pode especificar múltiplas configurações de redefinição de senha se tiver mais
    | de uma tabela de usuário ou modelo na aplicação e deseje ter
    | configurações de redefinição de senha separadas com base nos tipos de usuário específicos.
    |
    | O tempo de expiração é o número de minutos que cada token de redefinição será
    | considerado válido. Este recurso de segurança mantém os tokens curtos para que
    | tenham menos tempo para serem adivinhados. Você pode alterar isto conforme necessário.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tempo Limite de Confirmação de Senha
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir o número de segundos antes de uma confirmação de senha
    | expirar e o usuário ser solicitado a re-inserir sua senha via
    | tela de confirmação. Por padrão, o tempo limite dura três horas.
    |
    */

    'password_timeout' => 10800,

];
