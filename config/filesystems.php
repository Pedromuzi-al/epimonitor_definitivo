<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disco do Sistema de Arquivos Padrão
    |--------------------------------------------------------------------------
    |
    | Aqui você pode especificar o disco de sistema de arquivos padrão que deve ser usado
    | pelo framework. O disco "local", bem como uma variedade de nuvem
    | discos baseados estão disponíveis para sua aplicação. Apenas guarde!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Discos do Sistema de Arquivos
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar quantos "discos" do sistema de arquivos desejar, e você
    | pode até mesmo configurar múltiplos discos do mesmo driver. Os padrões têm
    | sido configurados para cada driver como um exemplo das opções necessárias.
    |
    | Drivers Suportados: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Links Simbólicos
    |--------------------------------------------------------------------------
    |
    | Aqui você pode configurar os links simbólicos que serão criados quando o
    | comando Artisan `storage:link` é executado. As chaves do array devem ser
    | os locais dos links e os valores devem ser seus destinos.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
