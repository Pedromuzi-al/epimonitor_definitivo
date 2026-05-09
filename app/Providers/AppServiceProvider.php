<?php

namespace App\Providers;

use App\Models\Diagnosis;
use App\Rules\ValidCPF;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('cpf', function ($attribute, $value) {
            $rule = new ValidCPF();
            return $rule->passes($attribute, $value);
        }, 'CPF invalido.');

        View::composer('*', function ($view) {
            $medicalAlerts = Diagnosis::selectRaw('neighborhood, count(*) as total')
                ->groupBy('neighborhood')
                ->havingRaw('count(*) >= 20')
                ->orderByRaw('count(*) desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'neighborhood' => $item->neighborhood,
                        'total' => (int) $item->total,
                        'level' => ((int) $item->total >= 30) ? 'critical' : 'high',
                    ];
                });

            $view->with('medicalAlerts', $medicalAlerts);
        });
    }
}
