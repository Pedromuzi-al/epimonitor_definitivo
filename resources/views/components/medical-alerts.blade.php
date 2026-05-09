<!-- Medical Alerts Notification Component -->
@if($medicalAlerts && $medicalAlerts->count() > 0)
    <div class="alert alert-warning border-0 shadow-lg mb-4" role="alert" id="medical-alerts">
        <div class="d-flex align-items-start justify-content-between">
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">
                    <i class="fas fa-exclamation-triangle"></i> Notificação Médica Urgente
                </h5>
                <p class="mb-3">
                    Foram detectados bairros com alerta alto/crítico. Recomendado acionamento rápido das equipes médicas:
                </p>
                <ul class="mb-0">
                    @forelse($medicalAlerts as $alert)
                        <li class="mb-2">
                            <strong class="text-danger">{{ $alert['neighborhood'] }}</strong>
                            <span class="badge bg-warning text-dark ms-2">{{ $alert['total'] }} ocorrências</span>
                            <span class="badge @if($alert['level'] === 'critical') bg-danger @else bg-warning text-dark @endif ms-1">
                                {{ $alert['level'] === 'critical' ? 'CRÍTICO' : 'ALTO' }}
                            </span>
                        </li>
                    @empty
                        <li>Nenhum alerta ativo no momento.</li>
                    @endforelse
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
@endif
