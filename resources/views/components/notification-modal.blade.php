<!-- Notifications Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Alertas Médicos Ativos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($medicalAlerts && $medicalAlerts->count() > 0)
                    <div class="alert alert-warning" role="alert">
                        <strong>{{ $medicalAlerts->count() }} bairro(s) com alerta ativo</strong>
                    </div>
                    
                    <div class="row g-3">
                        @foreach($medicalAlerts as $alert)
                            <div class="col-12">
                                <div class="card border-@if($alert['level'] === 'critical')danger @else warning @endif">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-map-marker-alt"></i> {{ $alert['neighborhood'] }}
                                            </h5>
                                            <span class="badge @if($alert['level'] === 'critical') bg-danger @else bg-warning text-dark @endif fs-6">
                                                {{ $alert['level'] === 'critical' ? 'CRÍTICO' : 'ALTO' }}
                                            </span>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Total de Casos</small>
                                                <div class="fs-5 fw-bold text-danger">{{ $alert['total'] }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Nível de Alerta</small>
                                                <div class="fs-5 fw-bold">
                                                    @if($alert['level'] === 'critical')
                                                        <span class="text-danger">⚠️ Crítico</span>
                                                    @else
                                                        <span class="text-warning">⚡ Alto</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <p class="mb-0 small">
                                            @if($alert['level'] === 'critical')
                                                <strong class="text-danger">Ação Imediata Necessária:</strong> 
                                                Este bairro apresenta níveis críticos de casos. Recomenda-se acionamento imediato de equipes médicas e epidemiológicas.
                                            @else
                                                <strong class="text-warning">Monitoramento Intensivo:</strong>
                                                Este bairro apresenta números elevados. Aumento na vigilância epidemiológica é recomendado.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="fas fa-info-circle"></i> Informações Importantes</h6>
                        <ul class="small mb-0">
                            <li><strong>Nível CRÍTICO:</strong> 30 ou mais casos no bairro</li>
                            <li><strong>Nível ALTO:</strong> 20 a 29 casos no bairro</li>
                            <li>Alertas são atualizados automaticamente</li>
                        </ul>
                    </div>
                @else
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <strong>Nenhum alerta ativo no momento!</strong>
                        <p class="mb-0 mt-2">Parabéns! A situação epidemiológica encontra-se sob controle em todos os bairros monitorados.</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

