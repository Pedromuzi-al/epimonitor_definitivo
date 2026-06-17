<?php

namespace App\Providers;

use App\Rules\ValidCPF;
use App\Services\MedicalAlertService;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();

        Validator::extend('cpf', function ($attribute, $value) {
            $rule = new ValidCPF();
            return $rule->passes($attribute, $value);
        }, 'CPF inválido.');

        // Registrar Alerta medicinal Service no View Composer
        View::composer('*', function ($view) {
            if (!auth()->check() || auth()->user()->user_type !== 'doctor') {
                $view->with('medicalAlerts', collect());
                return;
            }

            $medicalAlertService = app(MedicalAlertService::class);
            $medicalAlerts = $medicalAlertService->getActiveMedicalAlerts();

            $view->with('medicalAlerts', $medicalAlerts);
        });
    }
}
