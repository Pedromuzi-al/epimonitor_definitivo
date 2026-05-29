@extends('layouts.app')

@section('title', 'Estatisticas e Alertas')

@section('css')
    <style>
        #statisticsMap {
            width: 100%;
            min-height: 360px;
            border-radius: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endsection

@section('content')
    <h1 class="display-6 mb-4">
        <i class="fas fa-chart-bar"></i> Estatisticas e Alertas
    </h1>

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
                <p><i class="fas fa-stethoscope"></i> Diagnosticos Realizados</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <h3>{{ $diseaseStats->count() }}</h3>
                <p><i class="fas fa-virus-covid"></i> Doencas Detectadas</p>
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
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-award"></i> Ranking de Doencas
                    </h5>
                </div>
                <div class="card-body">
                    @if($diseaseStats->count() > 0)
                        <canvas id="diseaseChart" height="80"></canvas>
                    @else
                        <p class="text-muted text-center py-4">Nenhum dado disponivel</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map"></i> Incidencia por Bairro
                    </h5>
                </div>
                <div class="card-body">
                    @if($neighborhoodStats->count() > 0)
                        <canvas id="neighborhoodChart" height="80"></canvas>
                    @else
                        <p class="text-muted text-center py-4">Nenhum dado disponivel</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i> Mapa de Casos por Bairro</h5>
                    <button id="reloadMapData" class="btn btn-sm btn-light" type="button">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                </div>
                <div class="card-body">
                    <div id="statisticsMap"></div>
                    <small class="text-muted d-block mt-2">
                        Casos ativos por bairro. Ultima atualizacao: <span id="mapUpdatedAt">-</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-ol"></i> Doencas Mais Registradas</h5>
                </div>
                @if($diseaseStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Posicao</th>
                                    <th>Doenca</th>
                                    <th style="width: 80px;">Casos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($diseaseStats as $index => $stat)
                                    <tr>
                                        <td><strong style="font-size: 1.2rem;">{{ $index + 1 }}o</strong></td>
                                        <td><strong>{{ $stat->name }}</strong></td>
                                        <td><span class="badge bg-primary">{{ $stat->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body text-muted text-center py-4">Nenhuma doenca registrada</div>
                @endif
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-location"></i> Incidencia por Bairro</h5>
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
                                            $alertText = 'Critico';
                                            $badgeClass = 'badge-critical';
                                        } elseif ($stat->count >= 20) {
                                            $alertText = 'Alto';
                                            $badgeClass = 'badge-high';
                                        } elseif ($stat->count >= 10) {
                                            $alertText = 'Moderado';
                                            $badgeClass = 'badge-moderate';
                                        } else {
                                            $alertText = 'Baixo';
                                            $badgeClass = 'badge-low';
                                        }
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $stat->neighborhood }}</strong></td>
                                        <td><span class="badge bg-primary">{{ $stat->count }}</span></td>
                                        <td><span class="badge {{ $badgeClass }}">{{ $alertText }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body text-muted text-center py-4">Nenhum bairro registrado</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let statisticsMap;
        let statisticsMarkers = [];
        const geocodeCache = new Map();

        function getMapColorByCases(totalCases) {
            if (totalCases >= 20) return '#e74c3c';
            if (totalCases >= 10) return '#e67e22';
            if (totalCases >= 5) return '#f1c40f';
            return '#2ecc71';
        }

        function clearStatisticsMarkers() {
            statisticsMarkers.forEach((marker) => marker.remove());
            statisticsMarkers = [];
        }

        async function geocodeStatisticsAddress(address) {
            if (geocodeCache.has(address)) {
                return geocodeCache.get(address);
            }

            const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=br&q=${encodeURIComponent(address)}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Falha ao consultar localizacao no OpenStreetMap.');
            }

            const results = await response.json();
            if (!Array.isArray(results) || results.length === 0) {
                throw new Error('Endereco nao encontrado');
            }

            const point = {
                lat: Number(results[0].lat),
                lng: Number(results[0].lon),
            };

            geocodeCache.set(address, point);
            return point;
        }

        async function loadStatisticsMapData() {
            const response = await fetch("{{ route('statistics.map-data') }}");
            if (!response.ok) {
                throw new Error('Falha ao carregar os dados do mapa.');
            }

            const payload = await response.json();
            const locations = payload.locations || [];
            const updatedAtLabel = document.getElementById('mapUpdatedAt');
            if (updatedAtLabel) {
                updatedAtLabel.textContent = payload.updated_at || '-';
            }

            clearStatisticsMarkers();

            for (const item of locations) {
                const address = `${item.neighborhood}, ${item.city}, ${item.state}, Brasil`;

                try {
                    const point = await geocodeStatisticsAddress(address);
                    const marker = L.circleMarker([point.lat, point.lng], {
                        radius: Math.min(8 + item.total_cases, 20),
                        color: '#1f2d3d',
                        weight: 1,
                        fillColor: getMapColorByCases(item.total_cases),
                        fillOpacity: 0.92,
                    });
                    marker.addTo(statisticsMap);

                    const topDiseases = (item.disease_breakdown || [])
                        .slice(0, 5)
                        .map((d) => `<li>${d.disease}: ${d.cases}</li>`)
                        .join('');

                    marker.bindPopup(`
                        <div style="min-width: 230px">
                            <h6 style="margin:0 0 8px 0">${item.neighborhood}</h6>
                            <p style="margin:0 0 8px 0"><strong>Casos ativos:</strong> ${item.total_cases}</p>
                            <p style="margin:0 0 8px 0"><strong>Doenca mais frequente:</strong> ${item.top_disease}</p>
                            <ul style="padding-left:18px; margin:0">${topDiseases || '<li>Sem detalhamento</li>'}</ul>
                        </div>
                    `);
                    statisticsMarkers.push(marker);
                } catch (error) {
                    console.warn(`Nao foi possivel geocodificar ${address}`, error);
                }
            }

            if (statisticsMarkers.length > 0) {
                const group = L.featureGroup(statisticsMarkers);
                statisticsMap.fitBounds(group.getBounds().pad(0.2));
            }
        }

        async function initStatisticsMap() {
            const mapElement = document.getElementById('statisticsMap');
            if (!mapElement) return;

            statisticsMap = L.map(mapElement, {
                center: [-14.2350, -51.9253],
                zoom: 4,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(statisticsMap);

            await loadStatisticsMapData();

            const reloadButton = document.getElementById('reloadMapData');
            if (reloadButton) {
                reloadButton.addEventListener('click', async () => {
                    await loadStatisticsMapData();
                });
            }
        }

        @if($diseaseStats->count() > 0)
            const ctx = document.getElementById('diseaseChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! $diseaseStats->pluck('name')->toJson() !!},
                    datasets: [{
                        label: 'Numero de Casos',
                        data: {!! $diseaseStats->pluck('count')->toJson() !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ],
                        borderColor: '#333',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        @endif

        @if($neighborhoodStats->count() > 0)
            const ctx2 = document.getElementById('neighborhoodChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: {!! $neighborhoodStats->pluck('neighborhood')->toJson() !!},
                    datasets: [{
                        data: {!! $neighborhoodStats->pluck('count')->toJson() !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        @endif

        initStatisticsMap();
    </script>
@endsection
