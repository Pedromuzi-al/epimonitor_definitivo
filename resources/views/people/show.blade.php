@extends('layouts.app')

@section('title', $person->name)

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="display-6"><i class="fas fa-user"></i> {{ $person->name }}</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('people.edit', $person) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
        <a href="{{ route('people.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-id-card"></i> Informações Pessoais</h5></div>
            <div class="card-body">
                <p><span class="text-muted">CPF:</span><br><strong>{{ $person->cpf }}</strong></p>
                <p><span class="text-muted">Idade:</span><br><strong>{{ $person->age }} anos</strong></p>
                <p><span class="text-muted">Telefone:</span><br><strong>{{ $person->phone }}</strong></p>
                <p class="mb-0"><span class="text-muted">Bairro:</span><br><span class="badge bg-secondary">{{ $person->neighborhood }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Endereço</h5></div>
            <div class="card-body">
                <p><span class="text-muted">CEP:</span><br><strong>{{ $person->zip_code ?? '-' }}</strong></p>
                <p><span class="text-muted">Logradouro:</span><br><strong>{{ $person->street ?? '-' }}</strong></p>
                <p><span class="text-muted">Número:</span><br><strong>{{ $person->house_number ?? '-' }}</strong></p>
                <p><span class="text-muted">Complemento:</span><br><strong>{{ $person->address_complement ?: '-' }}</strong></p>
                <p><span class="text-muted">Tipo de moradia:</span><br><strong>{{ ucfirst($person->housing_type ?? '-') }}</strong></p>
                <p class="mb-0"><span class="text-muted">Cidade/UF:</span><br><strong>{{ $person->city ?? '-' }} / {{ $person->state ?? '-' }}</strong></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-timeline"></i> Linha do Tempo do Paciente</h5>
        <a href="{{ route('diagnoses.create') }}" class="btn btn-sm btn-light">
            <i class="fas fa-plus"></i> Novo Diagnóstico
        </a>
    </div>
    <div class="card-body">
        @if($person->diagnoses->count())
            <div class="timeline">
                @foreach($person->diagnoses as $diagnosis)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $diagnosis->is_resolved ? 'bg-success' : 'bg-warning' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <div>
                                    <h6 class="mb-1">
                                        {{ optional($diagnosis->disease)->name ?? 'Diagnóstico sem doença vinculada' }}
                                        <span class="badge {{ $diagnosis->is_resolved ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $diagnosis->is_resolved ? 'Resolvido' : 'Ativo' }}
                                        </span>
                                    </h6>
                                    <div class="text-muted small">
                                        {{ $diagnosis->created_at->format('d/m/Y H:i') }} -
                                        {{ number_format($diagnosis->probability, 2, ',', '.') }}% de probabilidade
                                    </div>
                                </div>
                                <a href="{{ route('diagnoses.show', $diagnosis) }}" class="btn btn-sm btn-outline-primary align-self-md-start">
                                    <i class="fas fa-eye"></i> Ver caso
                                </a>
                            </div>

                            <div class="mt-2">
                                @foreach(($diagnosis->symptoms ?? []) as $symptom)
                                    <span class="badge bg-primary me-1 mb-1">{{ $symptom }}</span>
                                @endforeach
                            </div>

                            @if($diagnosis->conversation)
                                <div class="mt-3 p-3 bg-light border rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="fas fa-comments"></i> Chat do caso</strong>
                                        <span class="badge {{ $diagnosis->conversation->status === 'open' ? 'bg-info' : 'bg-secondary' }}">
                                            {{ $diagnosis->conversation->status === 'open' ? 'Aberto' : 'Encerrado' }}
                                        </span>
                                    </div>
                                    @forelse($diagnosis->conversation->messages->take(-3) as $message)
                                        <div class="small mb-1">
                                            <strong>{{ $message->sender_type === 'doctor' ? 'Médico' : 'Paciente' }}:</strong>
                                            {{ $message->message }}
                                            <span class="text-muted">({{ $message->created_at->format('d/m H:i') }})</span>
                                        </div>
                                    @empty
                                        <div class="small text-muted">Chat criado, sem mensagens registradas.</div>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-4">
                Nenhum diagnóstico registrado para esta pessoa.
            </div>
        @endif
    </div>
</div>
@endsection

@section('css')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }

    .timeline:before {
        content: "";
        position: absolute;
        left: 0.45rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #d6dde3;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -1.33rem;
        top: 0.25rem;
        width: 0.85rem;
        height: 0.85rem;
        border-radius: 50%;
        box-shadow: 0 0 0 4px #fff;
    }

    .timeline-content {
        background: #ffffff;
        border: 1px solid #e3e8ed;
        border-radius: 8px;
        padding: 1rem;
    }
</style>
@endsection

