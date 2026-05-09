<!-- Notification Bell Component -->
<button class="btn btn-link nav-link position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationBell">
    <i class="fas fa-bell"></i>
    @if($medicalAlerts && $medicalAlerts->count() > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $medicalAlerts->count() }}
            <span class="visually-hidden">notificações</span>
        </span>
    @endif
</button>

<!-- Notifications Dropdown -->
<ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
    <li><h6 class="dropdown-header"><i class="fas fa-exclamation-triangle text-warning"></i> Alertas Médicos</h6></li>
    <li><hr class="dropdown-divider"></li>
    
    @if($medicalAlerts && $medicalAlerts->count() > 0)
        @foreach($medicalAlerts as $alert)
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#notificationModal">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $alert['neighborhood'] }}</h6>
                            <small class="text-muted">{{ $alert['total'] }} ocorrências</small>
                        </div>
                        <span class="badge @if($alert['level'] === 'critical') bg-danger @else bg-warning text-dark @endif">
                            {{ $alert['level'] === 'critical' ? 'CRÍTICO' : 'ALTO' }}
                        </span>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
        @endforeach
        <li>
            <a class="dropdown-item text-center" href="#" data-bs-toggle="modal" data-bs-target="#notificationModal">
                <small class="text-primary">Ver todos os alertas →</small>
            </a>
        </li>
    @else
        <li>
            <a class="dropdown-item text-center text-success" href="#">
                <i class="fas fa-check-circle"></i> Nenhum alerta no momento
            </a>
        </li>
    @endif
</ul>
