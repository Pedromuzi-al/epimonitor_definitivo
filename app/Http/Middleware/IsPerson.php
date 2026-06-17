<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsPerson
{
    /**
     * Verifica se o usuário autenticado é uma pessoa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type === 'person') {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Você não tem permissão para acessar este recurso. Apenas pessoas podem acessar.');
    }
}

