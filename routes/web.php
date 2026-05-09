<?php

use Illuminate\Support\Facades\Route;
use App\Models\Person;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\StatisticsController;

// Home
Route::get('/', function () {
    $totalPeople = Person::count();
    $totalDiagnoses = Diagnosis::count();
    $totalDiseases = Disease::count();
    $latestDiagnoses = Diagnosis::with(['person', 'disease'])->latest()->take(5)->get();

    return view('home', compact(
        'totalPeople',
        'totalDiagnoses',
        'totalDiseases',
        'latestDiagnoses'
    ));
})->name('home');

// People routes
Route::resource('people', PersonController::class);

// Diagnosis routes
Route::resource('diagnoses', DiagnosisController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

// Statistics
Route::get('/statistics', [StatisticsController::class, 'dashboard'])->name('statistics.dashboard');
