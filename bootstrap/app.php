<?php

/*
|--------------------------------------------------------------------------
| Criar a Aplicação
|--------------------------------------------------------------------------
|
| A primeira coisa que faremos é criar uma nova instância de aplicação Laravel
| que serve como o "adesivo" para todos os componentes do Laravel e é
| o container IoC para a vinculação do sistema de todas as várias partes.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Vincular Interfaces Importantes
|--------------------------------------------------------------------------
|
| Em seguida, precisamos vincular algumas interfaces importantes ao container para
| que possamos resolvê-las quando necessário. Os kernels servem as
| requisições recebidas nesta aplicação tanto da web quanto da CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Retornar a Aplicação
|--------------------------------------------------------------------------
|
| Este script retorna a instância da aplicação. A instância é dada ao
| script de chamada para que possamos separar a construção das instâncias
| da execução real da aplicação e envio de respostas.
|
*/

return $app;
