<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver de Hash Padrão
    |--------------------------------------------------------------------------
    |
    | Esta opção controla o driver de hash padrão que será usado para hash
    | senhas para sua aplicação. Por padrão, o algoritmo bcrypt é
    | usado; no entanto, você permanece livre para modificar esta opção se desejar.
    |
    | Suportado: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Opções do Bcrypt
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar as opções de configuração que devem ser usadas quando
    | as senhas são hash usando o algoritmo Bcrypt. Isto lhe permitirá
    | controlar o tempo que leva para fazer o hash da senha fornecida.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Opções do Argon
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar as opções de configuração que devem ser usadas quando
    | as senhas são hash usando o algoritmo Argon. Estas lhe permitirão
    | controlar o tempo que leva para fazer o hash da senha fornecida.
    |
    */

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
    ],

];
