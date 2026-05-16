<?php

namespace App\Services;

use App\Models\Disease;
use App\Models\Diagnosis;
use App\Models\Person;

class DiagnosisService
{
    /**
     * Calcula diagnosticos com base nos sintomas.
     */
    public function calculateDiagnosis(Person $person, array $symptoms)
    {
        $doencas = Disease::all();
        $diagnosticos = [];

        foreach ($doencas as $doenca) {
            $pontuacao = $this->calculateScore($doenca, $symptoms);
            $diagnosticos[$doenca->id] = [
                'disease' => $doenca,
                'score' => $pontuacao,
                'probability' => $this->calculateProbability($doenca, $pontuacao),
            ];
        }

        // Ordena por probabilidade decrescente.
        usort($diagnosticos, function ($a, $b) {
            return $b['probability'] <=> $a['probability'];
        });

        return $diagnosticos;
    }

    /**
     * Calcula pontuacao para uma doenca com base nos sintomas.
     */
    private function calculateScore(Disease $disease, array $symptoms): float
    {
        $pesos = $this->normalizeWeights($disease->symptom_weights ?? []);
        $pontuacaoTotal = 0;

        foreach ($symptoms as $symptom) {
            if (isset($pesos[$symptom])) {
                $pontuacaoTotal += $pesos[$symptom];
            }
        }

        return $pontuacaoTotal;
    }

    /**
     * Calcula probabilidade em percentual.
     */
    private function calculateProbability(Disease $disease, float $score): float
    {
        $pesos = $this->normalizeWeights($disease->symptom_weights ?? []);
        $pontuacaoMaxima = array_sum($pesos);

        if ($pontuacaoMaxima == 0) {
            return 0;
        }

        return ($score / $pontuacaoMaxima) * 100;
    }

    /**
     * Garante que os pesos dos sintomas sejam sempre um array.
     *
     * @param mixed $weights
     * @return array<string, float|int>
     */
    private function normalizeWeights($weights): array
    {
        if (is_array($weights)) {
            return $weights;
        }

        if (is_string($weights) && $weights !== '') {
            $decodificado = json_decode($weights, true);
            if (is_array($decodificado)) {
                return $decodificado;
            }
        }

        return [];
    }

    /**
     * Retorna o nivel de alerta com base no total de sintomas por bairro.
     */
    public function getAlertLevel(string $neighborhood): string
    {
        $quantidadeSintomas = Diagnosis::where('neighborhood', $neighborhood)
            ->count();

        if ($quantidadeSintomas >= 30) {
            return 'critical';
        } elseif ($quantidadeSintomas >= 20) {
            return 'high';
        } elseif ($quantidadeSintomas >= 10) {
            return 'moderate';
        }

        return 'low';
    }

    /**
     * Retorna estatisticas por doenca.
     */
    public function getDiseaseStatistics()
    {
        return Diagnosis::with('disease')
            ->selectRaw('diagnoses.disease_id, diseases.name, count(*) as count')
            ->join('diseases', 'diagnoses.disease_id', '=', 'diseases.id')
            ->groupBy('diagnoses.disease_id', 'diseases.name')
            ->orderByRaw('count DESC')
            ->get();
    }

    /**
     * Retorna estatisticas por bairro.
     */
    public function getNeighborhoodStatistics()
    {
        return Diagnosis::selectRaw('neighborhood, count(*) as count')
            ->groupBy('neighborhood')
            ->orderByRaw('count DESC')
            ->get();
    }
}
