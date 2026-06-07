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
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordPage'])->name('auth.password.request');
Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetLink'])->name('auth.password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordPage'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.password.update');
Route::get('/register', [AuthController::class, 'showRegisterPage'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Rotas de perfil (autenticadas para todos)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('user.profile');
    Route::get('/profile/photo/{user}', [AuthController::class, 'profilePhoto'])->name('user.profile-photo');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('user.edit-profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('user.update-profile');
});

// Página de boas-vindas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard (área autenticada - para todos)
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
})->name('home')->middleware('auth');

// ===== ROTAS EXCLUSIVAS PARA MEDICOS =====
Route::middleware(['auth', 'is.doctor'])->group(function () {
    // Rotas de pessoas (gerenciar cadastro)
    Route::resource('people', PersonController::class);

    // Rotas de diagnosticos (criar, listar, visualizar, deletar)
    Route::resource('diagnoses', DiagnosisController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Rotas para resolver diagnosticos
    Route::post('/diagnoses/{diagnosis}/resolve', [DiagnosisController::class, 'resolve'])->name('diagnoses.resolve');
    Route::post('/diagnoses/resolve-all', [DiagnosisController::class, 'resolveAll'])->name('diagnoses.resolve-all');
    Route::post('/diagnoses/resolve-by-neighborhood', [DiagnosisController::class, 'resolveByNeighborhood'])->name('diagnoses.resolve-by-neighborhood');

    // Rotas de estatisticas
    Route::get('/statistics', [StatisticsController::class, 'dashboard'])->name('statistics.dashboard');
});
