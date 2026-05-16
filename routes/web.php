<?php

use Illuminate\Support\Facades\Route;
use App\Models\Person;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\StatisticsController;

// Inicio
Route::get('/', function () {
    $totalPessoas = Person::count();
    $totalDiagnosticos = Diagnosis::count();
    $totalDoencas = Disease::count();
    $ultimosDiagnosticos = Diagnosis::with(['person', 'disease'])->latest()->take(5)->get();

    return view('home', [
        'totalPeople' => $totalPessoas,
        'totalDiagnoses' => $totalDiagnosticos,
        'totalDiseases' => $totalDoencas,
        'latestDiagnoses' => $ultimosDiagnosticos,
    ]);
})->name('home');

// Rotas de pessoas
Route::resource('people', PersonController::class);

// Rotas de diagnosticos
Route::resource('diagnoses', DiagnosisController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

// Rotas para resolver diagnosticos
Route::post('/diagnoses/{diagnosis}/resolve', [DiagnosisController::class, 'resolve'])->name('diagnoses.resolve');
Route::post('/diagnoses/resolve-all', [DiagnosisController::class, 'resolveAll'])->name('diagnoses.resolve-all');
Route::post('/diagnoses/resolve-by-neighborhood', [DiagnosisController::class, 'resolveByNeighborhood'])->name('diagnoses.resolve-by-neighborhood');

// Estatisticas
Route::get('/statistics', [StatisticsController::class, 'dashboard'])->name('statistics.dashboard');
