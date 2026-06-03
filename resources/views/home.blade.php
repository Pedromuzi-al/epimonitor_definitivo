@extends('layouts.app')

@section('title', 'Monitoramento de Sintomas')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div>
                            <h1 class="display-6 mb-2">
                                <i class="fas fa-heartbeat text-danger"></i> Monitoramento de Sintomas
                            </h1>
                            <p class="text-muted mb-0">Acompanhe os sintomas registrados, visualize tendencias e acesse rapidamente os principais fluxos.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('people.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Nova Pessoa
                            </a>
                            <a href="{{ route('diagnoses.create') }}" class="btn btn-success">
                                <i class="fas fa-stethoscope"></i> Novo Diagnóstico
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card h-100">
                <h3>{{ $totalPeople }}</h3>
                <p>Pessoas Cadastradas</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <h3>{{ $totalDiagnoses }}</h3>
                <p>Diagnósticos Registrados</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <h3>{{ $totalDiseases }}</h3>
                <p>Doenças Mapeadas</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Últimos Diagnósticos</h5>
                    <a href="{{ route('diagnoses.index') }}" class="btn btn-sm btn-light">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    @if($latestDiagnoses->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Pessoa</th>
                                        <th>Doença</th>
                                        <th>Probabilidade</th>
                                        <th>Bairro</th>
                                        <th style="width: 120px;">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestDiagnoses as $diagnosis)
                                        <tr>
                                            <td>{{ optional($diagnosis->person)->name ?? 'N/A' }}</td>
                                            <td>{{ optional($diagnosis->disease)->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($diagnosis->probability, 2, ',', '.') }}%</td>
                                            <td>{{ $diagnosis->neighborhood }}</td>
                                            <td>
                                                <button 
                                                    class="btn btn-sm btn-outline-success"
                                                    data-resolve-diagnosis="{{ $diagnosis->id }}"
                                                    data-resolve-url="{{ route('diagnoses.resolve', $diagnosis) }}"
                                                    data-bs-toggle="tooltip"
                                                    title="Marcar como resolvido">
                                                    <i class="fas fa-check"></i> Resolver
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            Nenhum diagnóstico registrado até o momento.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-compass"></i> Acesso Rápido</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('people.index') }}" class="btn btn-outline-primary text-start">
                        <i class="fas fa-users"></i> Gerenciar Pessoas
                    </a>
                    <a href="{{ route('diagnoses.create') }}" class="btn btn-outline-success text-start">
                        <i class="fas fa-notes-medical"></i> Realizar Diagnóstico
                    </a>
                    <a href="{{ route('statistics.dashboard') }}" class="btn btn-outline-info text-start">
                        <i class="fas fa-chart-line"></i> Ver Estatísticas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-map-marked-alt"></i> Mapa de Casos - Caratinga</h5>
                    <button id="reloadCaratingaMap" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt"></i> Atualizar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="caratingaMap" style="width: 100%; height: 400px; border-radius: 0 0 12px 12px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1f7fb8 0%, #20a77b 100%);
        color: white;
        padding: 2rem;
        border-left: 4px solid #16c79a;
        border-radius: 8px;
        box-shadow: 0 0.35rem 1rem rgba(31, 127, 184, 0.2);
        text-align: center;
    }

    .stat-card h3 {
        color: #ffffff;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
    }

    .stat-card p {
        color: rgba(255, 255, 255, 0.92);
        margin: 0.5rem 0 0 0;
    }

    /* Leaflet styles */
    #caratingaMap {
        border-radius: 0 0 12px 12px;
        z-index: 1;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .leaflet-popup-content h6 {
        margin: 0 0 6px 0;
    }

    .leaflet-popup-content p {
        margin: 0 0 6px 0;
    }

    .leaflet-popup-content ul {
        padding-left: 16px;
        margin: 0;
        font-size: 0.85rem;
    }
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
@endsection

@section('js')
<!-- Leaflet JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<!-- Leaflet Heat -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-heat/0.2.0/leaflet-heat.min.js"></script></script>

<script>
    let caratingaMap;
    let caratingaMarkers = [];

    function colorByCases(totalCases) {
        if (totalCases >= 20) return '#e74c3c';
        if (totalCases >= 10) return '#e67e22';
        if (totalCases >= 5) return '#f1c40f';
        return '#2ecc71';
    }

    function getSizeByCases(totalCases) {
        if (totalCases >= 20) return 25;
        if (totalCases >= 10) return 20;
        if (totalCases >= 5) return 15;
        return 10;
    }

    function clearCaratingaMarkers() {
        caratingaMarkers.forEach((marker) => caratingaMap.removeLayer(marker));
        caratingaMarkers = [];
    }

    async function loadCaratingaMapData() {
        try {
            const response = await fetch("{{ route('map-data.caratinga') }}");
            if (!response.ok) {
                throw new Error('Falha ao carregar dados do mapa.');
            }

            const payload = await response.json();
            const locations = payload.locations || [];

            clearCaratingaMarkers();

            let bounds = L.latLngBounds();

            for (const item of locations) {
                try {
                    // Geocodificação usando Nominatim (gratuito)
                    const address = `${item.neighborhood}, ${item.city}, ${item.state}, Brasil`;
                    const geoResponse = await fetch(
                        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`
                    );

                    if (!geoResponse.ok) continue;

                    const geoData = await geoResponse.json();
                    if (!geoData || geoData.length === 0) {
                        console.warn(`Não foi possível geocodificar: ${address}`);
                        continue;
                    }

                    const { lat, lon } = geoData[0];
                    const diseaseList = (item.disease_breakdown || [])
                        .slice(0, 5)
                        .map((disease) => `<li>${disease.disease}: ${disease.cases}</li>`)
                        .join('');

                    const popupContent = `
                        <div>
                            <h6 style="margin:0 0 6px 0"><strong>${item.neighborhood}</strong></h6>
                            <p style="margin:0 0 6px 0"><strong>Casos:</strong> ${item.total_cases}</p>
                            <p style="margin:0 0 6px 0"><strong>Principal:</strong> ${item.top_disease}</p>
                            <ul style="padding-left:16px; margin:0; font-size: 0.85rem;">${diseaseList || '<li>Sem detalhamento</li>'}</ul>
                        </div>
                    `;

                    const marker = L.circleMarker([lat, lon], {
                        radius: getSizeByCases(item.total_cases),
                        fillColor: colorByCases(item.total_cases),
                        color: '#1f2d3d',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.85
                    })
                    .bindPopup(popupContent)
                    .addTo(caratingaMap);

                    bounds.extend([lat, lon]);
                    caratingaMarkers.push(marker);
                } catch (error) {
                    console.warn(`Erro ao processar ${item.neighborhood}:`, error);
                }
            }

            // Ajusta o mapa para mostrar todos os marcadores
            if (caratingaMarkers.length > 0) {
                caratingaMap.fitBounds(bounds, { padding: [50, 50] });
            }
        } catch (error) {
            console.error('Erro ao carregar mapa:', error);
        }
    }

    function initCaratingaMap() {
        // Coordenadas de Caratinga, Minas Gerais
        caratingaMap = L.map('caratingaMap').setView([-19.78, -42.164], 14);

        // OpenStreetMap tiles (gratuito)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(caratingaMap);

        loadCaratingaMapData();

        document.getElementById('reloadCaratingaMap').addEventListener('click', async () => {
            await loadCaratingaMapData();
        });
    }

    // Inicializa o mapa quando a página carrega
    document.addEventListener('DOMContentLoaded', initCaratingaMap);
</script>
@endsection
