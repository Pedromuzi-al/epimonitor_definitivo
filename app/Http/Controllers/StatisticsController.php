<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    private $diagnosisService;

    public function __construct(DiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    /**
     * Show statistics dashboard
     */
    public function dashboard()
    {
        $diseaseStats = $this->diagnosisService->getDiseaseStatistics();
        $neighborhoodStats = $this->diagnosisService->getNeighborhoodStatistics();
        $totalDiagnoses = Diagnosis::count();
        $totalPeople = Diagnosis::distinct('person_id')->count();

        return view('statistics.dashboard', compact(
            'diseaseStats',
            'neighborhoodStats',
            'totalDiagnoses',
            'totalPeople'
        ));
    }
}
