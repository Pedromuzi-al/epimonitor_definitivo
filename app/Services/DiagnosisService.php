<?php

namespace App\Services;

use App\Models\Disease;
use App\Models\Diagnosis;
use App\Models\Person;

class DiagnosisService
{
    /**
     * Calculate diagnosis based on symptoms
     */
    public function calculateDiagnosis(Person $person, array $symptoms)
    {
        $diseases = Disease::all();
        $diagnoses = [];

        foreach ($diseases as $disease) {
            $score = $this->calculateScore($disease, $symptoms);
            $diagnoses[$disease->id] = [
                'disease' => $disease,
                'score' => $score,
                'probability' => $this->calculateProbability($disease, $score),
            ];
        }

        // Sort by probability descending
        usort($diagnoses, function ($a, $b) {
            return $b['probability'] <=> $a['probability'];
        });

        return $diagnoses;
    }

    /**
     * Calculate score for a disease based on symptoms
     */
    private function calculateScore(Disease $disease, array $symptoms): float
    {
        $weights = $this->normalizeWeights($disease->symptom_weights ?? []);
        $totalScore = 0;

        foreach ($symptoms as $symptom) {
            if (isset($weights[$symptom])) {
                $totalScore += $weights[$symptom];
            }
        }

        return $totalScore;
    }

    /**
     * Calculate probability as percentage
     */
    private function calculateProbability(Disease $disease, float $score): float
    {
        $weights = $this->normalizeWeights($disease->symptom_weights ?? []);
        $maxScore = array_sum($weights);

        if ($maxScore == 0) {
            return 0;
        }

        return ($score / $maxScore) * 100;
    }

    /**
     * Ensure symptom weights are always an array
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
            $decoded = json_decode($weights, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }

    /**
     * Get alert level based on neighborhood symptom count
     */
    public function getAlertLevel(string $neighborhood): string
    {
        $symptomCount = Diagnosis::where('neighborhood', $neighborhood)
            ->count();

        if ($symptomCount >= 30) {
            return 'critical';
        } elseif ($symptomCount >= 20) {
            return 'high';
        } elseif ($symptomCount >= 10) {
            return 'moderate';
        }

        return 'low';
    }

    /**
     * Get disease statistics
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
     * Get neighborhood statistics
     */
    public function getNeighborhoodStatistics()
    {
        return Diagnosis::selectRaw('neighborhood, count(*) as count')
            ->groupBy('neighborhood')
            ->orderByRaw('count DESC')
            ->get();
    }
}
