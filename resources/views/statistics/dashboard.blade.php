@extends('layouts.app')

@section('title', 'Estatísticas e Alertas')

@section('content')
    <h1 class="display-6 mb-4">
        <i class="fas fa-chart-bar"></i> Estatísticas e Alertas
    </h1>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <h3>{{ $totalPeople }}</h3>
                <p><i class="fas fa-users"></i> Pessoas Registradas</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <h3>{{ $totalDiagnoses }}</h3>
                <p><i class="fas fa-stethoscope"></i> Diagnósticos Realizados</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <h3>{{ $diseaseStats->count() }}</h3>
                <p><i class="fas fa-virus-covid"></i> Doenças Detectadas</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <h3>{{ $neighborhoodStats->count() }}</h3>
                <p><i class="fas fa-map-marker-alt"></i> Bairros Afetados</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Disease Rankings Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-award"></i> Ranking de Doenças
                    </h5>
                </div>
                <div class="card-body">
                    @if($diseaseStats->count() > 0)
                        <canvas id="diseaseChart" height="80"></canvas>
                    @else
                        <p class="text-muted text-center py-4">Nenhum dados disponível</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Neighborhood Stats Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map"></i> Incidência por Bairro
                    </h5>
                </div>
                <div class="card-body">
                    @if($neighborhoodStats->count() > 0)
                        <canvas id="neighborhoodChart" height="80"></canvas>
                    @else
                        <p class="text-muted text-center py-4">Nenhum dados disponível</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Disease Ranking Table -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-ol"></i> Doenças Mais Registradas</h5>
                </div>
                @if($diseaseStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Posição</th>
                                    <th>Doença</th>
                                    <th style="width: 80px;">Casos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($diseaseStats as $index => $stat)
                                    <tr>
                                        <td>
                                            <strong style="font-size: 1.2rem;">{{ $index + 1 }}º</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $stat->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $stat->count }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body text-muted text-center py-4">
                        Nenhuma doença registrada
                    </div>
                @endif
            </div>
        </div>

        <!-- Neighborhood Stats Table -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-location"></i> Incidência por Bairro</h5>
                </div>
                @if($neighborhoodStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Bairro</th>
                                    <th style="width: 100px;">Casos</th>
                                    <th style="width: 80px;">Alerta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($neighborhoodStats as $stat)
                                    @php
                                        if ($stat->count >= 30) {
                                            $alertLevel = 'critical';
                                            $alertText = 'Crítico';
                                            $badgeClass = 'badge-critical';
                                        } elseif ($stat->count >= 20) {
                                            $alertLevel = 'high';
                                            $alertText = 'Alto';
                                            $badgeClass = 'badge-high';
                                        } elseif ($stat->count >= 10) {
                                            $alertLevel = 'moderate';
                                            $alertText = 'Moderado';
                                            $badgeClass = 'badge-moderate';
                                        } else {
                                            $alertLevel = 'low';
                                            $alertText = 'Baixo';
                                            $badgeClass = 'badge-low';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $stat->neighborhood }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $stat->count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $alertText }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body text-muted text-center py-4">
                        Nenhum bairro registrado
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Disease Chart
        @if($diseaseStats->count() > 0)
            const ctx = document.getElementById('diseaseChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! $diseaseStats->pluck('name')->toJson() !!},
                    datasets: [{
                        label: 'Número de Casos',
                        data: {!! $diseaseStats->pluck('count')->toJson() !!},
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#FF6384',
                            '#C9CBCF'
                        ],
                        borderColor: '#333',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @endif

        // Neighborhood Chart
        @if($neighborhoodStats->count() > 0)
            const ctx2 = document.getElementById('neighborhoodChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! $neighborhoodStats->pluck('neighborhood')->toJson() !!},
                    datasets: [{
                        data: {!! $neighborhoodStats->pluck('count')->toJson() !!},
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40',
                            '#FF6384',
                            '#C9CBCF'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        @endif
    </script>
@endsection
