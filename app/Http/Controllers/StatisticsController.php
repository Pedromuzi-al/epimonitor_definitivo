<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Exibe o mapa de casos por bairro.
     */
    public function map()
    {
        return view('statistics.map', [
            'googleMapsApiKey' => (string) config('services.google_maps.api_key', ''),
        ]);
    }

    /**
     * Retorna dados agregados para exibicao no mapa.
     */
    public function mapData()
    {
        $registros = Diagnosis::query()
            ->join('people', 'people.id', '=', 'diagnoses.person_id')
            ->join('diseases', 'diseases.id', '=', 'diagnoses.disease_id')
            ->unresolved()
            ->whereNotNull('diagnoses.neighborhood')
            ->where('diagnoses.neighborhood', '!=', '')
            ->select([
                DB::raw('TRIM(diagnoses.neighborhood) as neighborhood'),
                DB::raw('COALESCE(NULLIF(TRIM(people.city), \'\'), \'Cidade nao informada\') as city'),
                DB::raw('COALESCE(NULLIF(TRIM(people.state), \'\'), \'Estado nao informado\') as state'),
                'diseases.name as disease_name',
            ])
            ->get();

        $agrupados = $registros
            ->groupBy(function ($item) {
                return mb_strtolower($item->neighborhood, 'UTF-8') . '|' .
                    mb_strtolower($item->city, 'UTF-8') . '|' .
                    mb_strtolower($item->state, 'UTF-8');
            })
            ->map(function ($grupo) {
                $primeiro = $grupo->first();
                $contagemDoencas = $grupo->groupBy('disease_name')->map->count()->sortDesc();

                return [
                    'neighborhood' => $primeiro->neighborhood,
                    'city' => $primeiro->city,
                    'state' => $primeiro->state,
                    'total_cases' => $grupo->count(),
                    'top_disease' => (string) $contagemDoencas->keys()->first(),
                    'disease_breakdown' => $contagemDoencas->map(function ($total, $nome) {
                        return [
                            'disease' => (string) $nome,
                            'cases' => (int) $total,
                        ];
                    })->values(),
                ];
            })
            ->sortByDesc('total_cases')
            ->values();

        return response()->json([
            'updated_at' => now()->toDateTimeString(),
            'locations' => $agrupados,
        ]);
    }

    /**
     * Retorna dados agregados apenas de Caratinga para o mapa da home.
     */
    public function caratingaMapData()
    {
        $registros = Diagnosis::query()
            ->join('people', 'people.id', '=', 'diagnoses.person_id')
            ->join('diseases', 'diseases.id', '=', 'diagnoses.disease_id')
            ->unresolved()
            ->whereNotNull('diagnoses.neighborhood')
            ->where('diagnoses.neighborhood', '!=', '')
            ->whereRaw("LOWER(TRIM(people.city)) = ?", [mb_strtolower('Caratinga', 'UTF-8')])
            ->select([
                DB::raw('TRIM(diagnoses.neighborhood) as neighborhood'),
                DB::raw('COALESCE(NULLIF(TRIM(people.city), \'\'), \'Cidade nao informada\') as city'),
                DB::raw('COALESCE(NULLIF(TRIM(people.state), \'\'), \'Estado nao informado\') as state'),
                'diseases.name as disease_name',
            ])
            ->get();

        $agrupados = $registros
            ->groupBy(function ($item) {
                return mb_strtolower($item->neighborhood, 'UTF-8') . '|' .
                    mb_strtolower($item->city, 'UTF-8') . '|' .
                    mb_strtolower($item->state, 'UTF-8');
            })
            ->map(function ($grupo) {
                $primeiro = $grupo->first();
                $contagemDoencas = $grupo->groupBy('disease_name')->map->count()->sortDesc();

                return [
                    'neighborhood' => $primeiro->neighborhood,
                    'city' => $primeiro->city,
                    'state' => $primeiro->state,
                    'total_cases' => $grupo->count(),
                    'top_disease' => (string) $contagemDoencas->keys()->first(),
                    'disease_breakdown' => $contagemDoencas->map(function ($total, $nome) {
                        return [
                            'disease' => (string) $nome,
                            'cases' => (int) $total,
                        ];
                    })->values(),
                ];
            })
            ->sortByDesc('total_cases')
            ->values();

        return response()->json([
            'updated_at' => now()->toDateTimeString(),
            'locations' => $agrupados,
        ]);
    }
}
