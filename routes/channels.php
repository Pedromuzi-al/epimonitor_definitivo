<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Canais de Broadcast
|--------------------------------------------------------------------------
|
| Aqui você pode registrar os canais de broadcast suportados pela
| aplicacao. Os callbacks de autorizacao sao usados para verificar
| se um usuario autenticado pode ouvir o canal.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
