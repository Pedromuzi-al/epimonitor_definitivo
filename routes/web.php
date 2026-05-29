<?php

use Illuminate\Support\Facades\Route;
use App\Models\Person;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\AuthController;

// Rotas de autenticação (públicas)
Route::get('/login', [AuthController::class, 'showLoginPage'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::get('/register', [AuthController::class, 'showRegisterPage'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rotas de perfil (autenticadas)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('user.profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('user.edit-profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('user.update-profile');
});

// Página de boas-vindas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard (área autenticada)
Route::get('/dashboard', function () {
    $totalPessoas = Person::count();
    $totalDiagnosticos = Diagnosis::count();
    $totalDoencas = Disease::count();
    $ultimosDiagnosticos = Diagnosis::with(['person', 'disease'])
        ->unresolved()
        ->latest()
        ->take(5)
        ->get();

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
Route::get('/statistics/map', [StatisticsController::class, 'map'])->name('statistics.map');
Route::get('/statistics/map-data', [StatisticsController::class, 'mapData'])->name('statistics.map-data');
