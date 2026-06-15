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
            $probabilidade = $this->calculateProbability($doenca, $pontuacao);

            if ($probabilidade > 0) {
                $diagnosticos[$doenca->id] = [
                    'disease' => $doenca,
                    'score' => $pontuacao,
                    'probability' => $probabilidade,
                ];
            }
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
            $chaveSintoma = $this->normalizeSymptomName($symptom);

            if (isset($pesos[$chaveSintoma])) {
                $pontuacaoTotal += $pesos[$chaveSintoma];
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
        $pesosNormalizados = [];

        if (is_array($weights)) {
            foreach ($weights as $symptom => $weight) {
                $pesosNormalizados[$this->normalizeSymptomName($symptom)] = is_numeric($weight) ? (float) $weight : 0;
            }

            return $pesosNormalizados;
        }

        if (is_string($weights) && $weights !== '') {
            $decodificado = json_decode($weights, true);
            if (is_array($decodificado)) {
                foreach ($decodificado as $symptom => $weight) {
                    $pesosNormalizados[$this->normalizeSymptomName($symptom)] = is_numeric($weight) ? (float) $weight : 0;
                }

                return $pesosNormalizados;
            }
        }

        return [];
    }

    /**
     * Normaliza nomes para comparar sintomas com e sem acentos.
     */
    private function normalizeSymptomName(string $symptom): string
    {
        $symptom = trim($symptom);
        $symptom = strtr($symptom, [
            'Ã¡' => 'a',
            'Ã ' => 'a',
            'Ã¢' => 'a',
            'Ã£' => 'a',
            'Ã©' => 'e',
            'Ãª' => 'e',
            'Ã­' => 'i',
            'Ã³' => 'o',
            'Ã´' => 'o',
            'Ãµ' => 'o',
            'Ãº' => 'u',
            'Ã§' => 'c',
            'á' => 'a',
            'à' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ç' => 'c',
        ]);

        $symptom = strtolower($symptom);

        return preg_replace('/[^a-z0-9]+/', ' ', $symptom) ?: '';
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
