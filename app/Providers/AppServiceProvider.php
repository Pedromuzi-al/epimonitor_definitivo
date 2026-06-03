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
        }, 'CPF invalido.');

        // Registrar Alerta medicinal Service no View Composer
        View::composer('*', function ($view) {
            $medicalAlertService = app(MedicalAlertService::class);
            $medicalAlerts = $medicalAlertService->getActiveMedicalAlerts();

            $view->with('medicalAlerts', $medicalAlerts);
        });
    }
}
