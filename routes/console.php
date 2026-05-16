<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Rotas de Console
|--------------------------------------------------------------------------
|
| Neste arquivo voce pode definir comandos de console baseados em
| Closure. Cada Closure e associada a uma instancia de comando.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Exibe uma frase inspiradora');
