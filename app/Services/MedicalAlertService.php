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
        return Diagnosis::query()
            ->unresolved()
            ->selectRaw('neighborhood, count(*) as total')
            ->groupBy('neighborhood')
            ->havingRaw('count(*) >= ' . self::ALERT_THRESHOLD)
            ->orderByRaw('count(*) desc')
            ->get()
            ->map(function ($registro) {
                return $this->formatAlert($registro);
            });
    }

    /**
     * Formatar alerta para o padrão de exibição
     * 
     * @param object $registro
     * @return array
     */
    private function formatAlert($registro): array
    {
        $total = (int) $registro->total;

        return [
            'neighborhood' => $registro->neighborhood,
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
        return Diagnosis::query()
            ->unresolved()
            ->selectRaw('neighborhood, count(*) as total')
            ->groupBy('neighborhood')
            ->havingRaw('count(*) >= ' . self::ALERT_THRESHOLD)
            ->exists();
    }

    /**
     * Obter estatísticas de alertas por nível
     * 
     * @return array
     */
    public function getAlertStatistics(): array
    {
        $alertas = $this->getActiveMedicalAlerts();

        return [
            'total' => $alertas->count(),
            'critical_count' => $alertas->where('level', 'critical')->count(),
            'high_count' => $alertas->where('level', 'high')->count(),
        ];
    }
}
