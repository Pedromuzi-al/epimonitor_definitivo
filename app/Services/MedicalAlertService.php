<?php

namespace App\Services;

use App\Models\Diagnosis;
use Illuminate\Support\Collection;

class MedicalAlertService
{
    /**
     * Limiar mínimo de casos para gerar alerta
     */
    private const ALERT_THRESHOLD = 20;

    /**
     * Limiar crítico de casos
     */
    private const CRITICAL_THRESHOLD = 30;

    /**
     * Obter alertas médicos ativos por bairro
     * 
     * @return Collection<int, array>
     */
    public function getActiveMedicalAlerts(): Collection
    {
        return Diagnosis::selectRaw('neighborhood, count(*) as total')
            ->groupBy('neighborhood')
            ->havingRaw('count(*) >= ' . self::ALERT_THRESHOLD)
            ->orderByRaw('count(*) desc')
            ->get()
            ->map(function ($item) {
                return $this->formatAlert($item);
            });
    }

    /**
     * Formatar alerta para o padrão de exibição
     * 
     * @param object $item
     * @return array
     */
    private function formatAlert($item): array
    {
        $total = (int) $item->total;

        return [
            'neighborhood' => $item->neighborhood,
            'total' => $total,
            'level' => $total >= self::CRITICAL_THRESHOLD ? 'critical' : 'high',
            'hasAlerts' => true,
        ];
    }

    /**
     * Verificar se há alertas ativos
     * 
     * @return bool
     */
    public function hasActiveAlerts(): bool
    {
        return Diagnosis::selectRaw('count(*) as total')
            ->where('alert_level', '!=', 'low')
            ->first()
            ->total > 0;
    }

    /**
     * Obter estatísticas de alertas por nível
     * 
     * @return array
     */
    public function getAlertStatistics(): array
    {
        $alerts = $this->getActiveMedicalAlerts();

        return [
            'total' => $alerts->count(),
            'critical_count' => $alerts->where('level', 'critical')->count(),
            'high_count' => $alerts->where('level', 'high')->count(),
        ];
    }
}
