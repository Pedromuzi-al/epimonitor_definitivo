@extends('layouts.app')

@section('title', 'Mapa de Doencas')

@section('css')
<style>
    #diseaseMap {
        width: 100%;
        height: 70vh;
        min-height: 480px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .map-legend {
        background: #fff;
        border-radius: 10px;
        padding: 1rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .dot-low { background: #2ecc71; }
    .dot-mid { background: #f1c40f; }
    .dot-high { background: #e67e22; }
    .dot-critical { background: #e74c3c; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h2 class="mb-0"><i class="fas fa-map-marked-alt"></i> Mapa de Doencas por Bairro</h2>
        <small class="text-muted">Casos ativos (nao resolvidos), agrupados por bairro.</small>
    </div>
    <button id="reloadMapData" class="btn btn-outline-primary">
        <i class="fas fa-sync-alt"></i> Atualizar dados
    </button>
</div>

@if(empty($googleMapsApiKey))
    <div class="alert alert-warning">
        Defina <code>GOOGLE_MAPS_API_KEY</code> no arquivo <code>.env</code> para carregar o Google Maps.
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-9">
        <div id="diseaseMap"></div>
    </div>
    <div class="col-lg-3">
        <div class="map-legend mb-3">
            <h6 class="mb-3">Legenda por volume</h6>
            <p class="mb-2"><span class="legend-dot dot-low"></span> 1 a 4 casos</p>
            <p class="mb-2"><span class="legend-dot dot-mid"></span> 5 a 9 casos</p>
            <p class="mb-2"><span class="legend-dot dot-high"></span> 10 a 19 casos</p>
            <p class="mb-0"><span class="legend-dot dot-critical"></span> 20+ casos</p>
        </div>
        <div class="card">
            <div class="card-header">
                <strong>Resumo</strong>
            </div>
            <div class="card-body">
                <p class="mb-1">Bairros no mapa: <strong id="totalNeighborhoods">0</strong></p>
                <p class="mb-1">Casos ativos: <strong id="totalCases">0</strong></p>
                <p class="mb-0">Atualizado em: <strong id="updatedAt">-</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let map;
    let geocoder;
    let markers = [];

    function colorByCases(totalCases) {
        if (totalCases >= 20) return '#e74c3c';
        if (totalCases >= 10) return '#e67e22';
        if (totalCases >= 5) return '#f1c40f';
        return '#2ecc71';
    }

    function clearMarkers() {
        markers.forEach((marker) => marker.setMap(null));
        markers = [];
    }

    function geocodeAddress(address) {
        return new Promise((resolve, reject) => {
            geocoder.geocode({ address }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    resolve(results[0].geometry.location);
                    return;
                }
                reject(status);
            });
        });
    }

    async function loadMapData() {
        const response = await fetch("{{ route('statistics.map-data') }}");
        if (!response.ok) {
            throw new Error('Falha ao carregar dados do mapa.');
        }

        const payload = await response.json();
        const locations = payload.locations || [];
        document.getElementById('totalNeighborhoods').textContent = locations.length;
        document.getElementById('totalCases').textContent = locations.reduce((acc, item) => acc + item.total_cases, 0);
        document.getElementById('updatedAt').textContent = payload.updated_at || '-';

        clearMarkers();

        for (const item of locations) {
            const address = `${item.neighborhood}, ${item.city}, ${item.state}, Brasil`;

            try {
                const point = await geocodeAddress(address);
                const marker = new google.maps.Marker({
                    map,
                    position: point,
                    title: `${item.neighborhood} (${item.total_cases} casos)`,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: colorByCases(item.total_cases),
                        fillOpacity: 0.92,
                        strokeColor: '#1f2d3d',
                        strokeWeight: 1,
                        scale: Math.min(8 + item.total_cases, 24),
                    },
                });

                const diseaseList = (item.disease_breakdown || [])
                    .slice(0, 5)
                    .map((disease) => `<li>${disease.disease}: ${disease.cases}</li>`)
                    .join('');

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="min-width: 230px">
                            <h6 style="margin:0 0 8px 0">${item.neighborhood}</h6>
                            <p style="margin:0 0 8px 0"><strong>Casos ativos:</strong> ${item.total_cases}</p>
                            <p style="margin:0 0 8px 0"><strong>Doenca mais frequente:</strong> ${item.top_disease}</p>
                            <ul style="padding-left:18px; margin:0">${diseaseList || '<li>Sem detalhamento</li>'}</ul>
                        </div>
                    `,
                });

                marker.addListener('click', () => infoWindow.open({ anchor: marker, map }));
                markers.push(marker);
            } catch (error) {
                console.warn(`Nao foi possivel geocodificar ${address}`, error);
            }
        }
    }

    async function initMap() {
        map = new google.maps.Map(document.getElementById('diseaseMap'), {
            center: { lat: -14.2350, lng: -51.9253 },
            zoom: 4,
            mapTypeControl: true,
            streetViewControl: false,
        });

        geocoder = new google.maps.Geocoder();

        try {
            await loadMapData();
        } catch (error) {
            alert(error.message);
        }

        document.getElementById('reloadMapData').addEventListener('click', async () => {
            try {
                await loadMapData();
            } catch (error) {
                alert(error.message);
            }
        });
    }
</script>

@if(!empty($googleMapsApiKey))
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap"></script>
@endif
@endsection
