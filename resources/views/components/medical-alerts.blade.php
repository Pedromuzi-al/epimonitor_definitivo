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

                @php
                    $alertNeighborhoods = $medicalAlerts->pluck('neighborhood')->unique()->sort()->values();
                @endphp

                <div class="d-flex flex-wrap align-items-end gap-2 mt-3">
                    <form method="POST" action="{{ route('diagnoses.resolve-all') }}" onsubmit="return confirm('Confirmar resolução de todos os diagnósticos ativos de todos os locais?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-check-double"></i> Confirmar tudo resolvido
                        </button>
                    </form>

                    <form method="POST" action="{{ route('diagnoses.resolve-by-neighborhood') }}" class="d-flex flex-wrap align-items-end gap-2" onsubmit="return confirm('Confirmar resolução para os bairros selecionados?');">
                        @csrf
                        <div>
                            <label for="resolveNeighborhoods" class="form-label mb-1">Resolver por bairro</label>
                            <select name="neighborhoods[]" id="resolveNeighborhoods" class="form-select form-select-sm" multiple size="4" required>
                                @foreach($alertNeighborhoods as $neighborhood)
                                    <option value="{{ $neighborhood }}">{{ $neighborhood }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-check"></i> Confirmar bairros
                        </button>
                    </form>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
@endif

