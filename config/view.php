<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Caminhos de Armazenamento de Visualização
    |--------------------------------------------------------------------------
    |
    | A maioria dos sistemas de template carregam templates do disco. Aqui você pode especificar
    | um array de caminhos que devem ser verificados para suas visualizações. É claro
    | que o caminho de visualização usual do Laravel já foi registrado para você.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Caminho da Visualização Compilada
    |--------------------------------------------------------------------------
    |
    | Esta opção determina onde todos os templates Blade compilados serão
    | armazenados para sua aplicação. Normalmente, isto fica dentro do di retório de armazenamento.
    | No entanto, como de costume, você é livre para alterar este valor.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),

];
