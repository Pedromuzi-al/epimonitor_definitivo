@extends('layouts.app')

@section('title', 'Resultado do Diagnóstico')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-stethoscope"></i> Resultado do Diagnóstico
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('diagnoses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Result -->
        <div class="col-lg-8 mb-4">
            <!-- Person and Date Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Pessoa:</p>
                            <h5>{{ $diagnosis->person->name }}</h5>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Data do Diagnóstico:</p>
                            <h5>{{ $diagnosis->created_at->format('d/m/Y \à\s H:i') }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Diagnosis Result -->
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-diagnoses"></i> Diagnóstico Mais Provável
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-0">{{ $diagnosis->disease->name }}</h3>
                            <p class="text-muted mb-0">{{ $diagnosis->disease->description }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div style="font-size: 3rem; font-weight: bold; color: #27ae60;">
                                {{ number_format($diagnosis->probability, 2) }}%
                            </div>
                            <p class="text-muted">Probabilidade</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $diagnosis->probability }}%;" 
                                 aria-valuenow="{{ $diagnosis->probability }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Symptoms Analyzed -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Sintomas Analisados
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($diagnosis->symptoms as $symptom)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-primary" style="font-size: 1rem;">
                                    <i class="fas fa-check-circle"></i> {{ $symptom }}
                                </span>
                            </div>
                        @empty
                            <p class="text-muted">Nenhum sintoma registrado</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Possible Diagnoses -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ol"></i> Doenças Possíveis para Esta Combinação
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($possibleDiagnoses))
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Doença</th>
                                        <th>Probabilidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($possibleDiagnoses as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['disease']->name }}</td>
                                            <td>
                                                <span class="badge {{ $index === 0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ number_format($item['probability'], 2) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Não foi possível estimar doenças para os sintomas informados.</p>
                    @endif
                </div>
            </div>

            <!-- Alert Level -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Nível de Alerta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @switch($diagnosis->alert_level)
                            @case('critical')
                                <div style="font-size: 1.5rem;" class="mb-2">
                                    <span class="badge badge-critical p-3">
                                        <i class="fas fa-exclamation"></i> CRÍTICO
                                    </span>
                                </div>
                                <p class="text-muted">30 ou mais sintomas registrados no bairro</p>
                                @break
                            @case('high')
                                <div style="font-size: 1.5rem;" class="mb-2">
                                    <span class="badge badge-high p-3">
                                        <i class="fas fa-exclamation-triangle"></i> ALTO
                                    </span>
                                </div>
                                <p class="text-muted">20 a 29 sintomas registrados no bairro</p>
                                @break
                            @case('moderate')
                                <div style="font-size: 1.5rem;" class="mb-2">
                                    <span class="badge badge-moderate p-3">
                                        <i class="fas fa-info-circle"></i> MODERADO
                                    </span>
                                </div>
                                <p class="text-muted">10 a 19 sintomas registrados no bairro</p>
                                @break
                            @default
                                <div style="font-size: 1.5rem;" class="mb-2">
                                    <span class="badge badge-low p-3">
                                        <i class="fas fa-check"></i> BAIXO
                                    </span>
                                </div>
                                <p class="text-muted">Menos de 10 sintomas registrados no bairro</p>
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Person Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informações da Pessoa</h5>
                </div>
                <div class="card-body small">
                    <p>
                        <strong>Nome:</strong><br>
                        {{ $diagnosis->person->name }}
                    </p>
                    <p>
                        <strong>CPF:</strong><br>
                        {{ $diagnosis->person->cpf }}
                    </p>
                    <p>
                        <strong>Idade:</strong><br>
                        {{ $diagnosis->person->age }} anos
                    </p>
                    <p>
                        <strong>Bairro:</strong><br>
                        <span class="badge bg-secondary">{{ $diagnosis->neighborhood }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Telefone:</strong><br>
                        {{ $diagnosis->person->phone }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Ações</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('people.show', $diagnosis->person) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-circle"></i> Ver Perfil
                    </a>
                    <a href="{{ route('diagnoses.create') }}" class="btn btn-success w-100">
                        <i class="fas fa-plus"></i> Novo Diagnóstico
                    </a>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-warning"></i> Aviso Importante</h5>
                </div>
                <div class="card-body small">
                    <p>
                        Este diagnóstico é apenas uma simulação baseada em análise de sintomas. 
                    </p>
                    <p class="mb-0">
                        <strong>Sempre consulte um profissional de saúde qualificado para diagnóstico e tratamento adequados.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
