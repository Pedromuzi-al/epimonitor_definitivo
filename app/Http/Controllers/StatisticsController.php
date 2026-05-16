<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    private $servicoDiagnostico;

    public function __construct(DiagnosisService $servicoDiagnostico)
    {
        $this->servicoDiagnostico = $servicoDiagnostico;
    }

    /**
     * Exibe o painel de estatisticas.
     */
    public function dashboard()
    {
        $estatisticasDoencas = $this->servicoDiagnostico->getDiseaseStatistics();
        $estatisticasBairros = $this->servicoDiagnostico->getNeighborhoodStatistics();
        $totalDiagnosticos = Diagnosis::count();
        $totalPessoas = Diagnosis::distinct('person_id')->count();

        return view('statistics.dashboard', [
            'diseaseStats' => $estatisticasDoencas,
            'neighborhoodStats' => $estatisticasBairros,
            'totalDiagnoses' => $totalDiagnosticos,
            'totalPeople' => $totalPessoas,
        ]);
    }
}
