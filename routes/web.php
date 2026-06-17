<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorPanelController;
use App\Http\Controllers\ConversaMedicinalController;
use App\Http\Controllers\PatientPortalController;
use App\Http\Controllers\HomeController;

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
    Route::get('/conversa/{conversa}/refresh', [ConversaMedicinalController::class, 'refresh'])->name('conversa.refresh');
    Route::post('/conversa/{conversa}/messages', [ConversaMedicinalController::class, 'storePatientMessage'])->name('conversa.messages.store');
});

// Página de boas-vindas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dashboard (área autenticada - para todos)
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// ===== ROTAS EXCLUSIVAS PARA PACIENTES =====
Route::middleware(['auth', 'is.person'])->group(function () {
    Route::get('/meus-dados', [PatientPortalController::class, 'editProfile'])->name('patient.profile.edit');
    Route::post('/meus-dados', [PatientPortalController::class, 'saveProfile'])->name('patient.profile.save');
    Route::get('/meu-diagnostico/novo', [PatientPortalController::class, 'createDiagnosis'])->name('patient.diagnoses.create');
    Route::post('/meu-diagnostico', [PatientPortalController::class, 'storeDiagnosis'])->name('patient.diagnoses.store');
    Route::get('/meus-diagnosticos/{diagnosis}', [PatientPortalController::class, 'showDiagnosis'])->name('patient.diagnoses.show');
});

// ===== ROTAS EXCLUSIVAS PARA MEDICOS =====
Route::middleware(['auth', 'is.doctor'])->group(function () {
    // Painel do médico
    Route::get('/doctor-panel', [DoctorPanelController::class, 'index'])->name('doctor-panel.index');

    // Rotas de pessoas (gerenciar cadastro)
    Route::resource('people', PersonController::class);

    // Rotas de diagnósticos (criar, listar, visualizar, deletar)
    Route::resource('diagnoses', DiagnosisController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Rotas para resolver diagnósticos
    Route::post('/diagnoses/{diagnosis}/resolve', [DiagnosisController::class, 'resolve'])->name('diagnoses.resolve');
    Route::post('/diagnoses/resolve-all', [DiagnosisController::class, 'resolveAll'])->name('diagnoses.resolve-all');
    Route::post('/diagnoses/resolve-by-neighborhood', [DiagnosisController::class, 'resolveByNeighborhood'])->name('diagnoses.resolve-by-neighborhood');
    Route::post('/diagnoses/{diagnosis}/conversation/start', [ConversaMedicinalController::class, 'start'])->name('diagnoses.conversation.start');
    Route::post('/diagnoses/{diagnosis}/conversation/messages', [ConversaMedicinalController::class, 'storeMessage'])->name('diagnoses.conversation.messages.store');
    Route::post('/diagnoses/{diagnosis}/conversation/close', [ConversaMedicinalController::class, 'close'])->name('diagnoses.conversation.close');

    // Rotas de estatisticas
    Route::get('/statistics', [StatisticsController::class, 'dashboard'])->name('statistics.dashboard');
});
