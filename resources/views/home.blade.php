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
@endsection
