<!-- Resolve Diagnosis Modal -->
<div class="modal fade" id="resolveDiagnosisModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="resolveDiagnosisForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Marcar Diagnóstico Como Resolvido
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Confirmar Resolução:</strong> 
                        Este diagnóstico será marcado como resolvido e removido do banco de dados ativo (soft delete).
                    </div>

                    <div class="mb-3">
                        <label for="resolutionReason" class="form-label">
                            Motivo da Resolução <small class="text-muted">(Opcional)</small>
                        </label>
                        <textarea 
                            class="form-control" 
                            id="resolutionReason" 
                            name="resolution_reason"
                            rows="3"
                            placeholder="Ex: Paciente curado, falso positivo, em acompanhamento...">
                        </textarea>
                        <small class="text-muted">Descreva brevemente o motivo da resolução</small>
                    </div>

                    <div class="card bg-light border-warning">
                        <div class="card-body">
                            <h6 class="card-title mb-2"><i class="fas fa-shield-alt"></i> O que acontece:</h6>
                            <ul class="mb-0 small">
                                <li>✓ Diagnóstico marcado como resolvido</li>
                                <li>✓ Removido dos alertas ativos</li>
                                <li>✓ Mantido no histórico (recuperável)</li>
                                <li>✓ Não afeta estatísticas gerais</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Confirmar Resolução
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('resolveDiagnosisModal');
    const form = document.getElementById('resolveDiagnosisForm');

    // Quando um botão de resolver é clicado
    document.querySelectorAll('[data-resolve-diagnosis]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const diagnosisId = this.getAttribute('data-resolve-diagnosis');
            const diagnosisUrl = this.getAttribute('data-resolve-url');
            
            // Atualizar action do form
            form.setAttribute('action', diagnosisUrl);
            
            // Limpar textarea
            document.getElementById('resolutionReason').value = '';
            
            // Mostrar modal
            new bootstrap.Modal(modal).show();
        });
    });
});
</script>
