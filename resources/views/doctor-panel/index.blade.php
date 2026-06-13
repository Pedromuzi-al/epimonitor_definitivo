@extends('layouts.app')

@section('title', 'Painel Medico')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-user-md"></i> Painel Medico
            </h1>
            <p class="text-muted mb-0">Acompanhe casos ativos, conversas abertas e pacientes que precisam de retorno.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('people.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Nova Pessoa
            </a>
            <a href="{{ route('diagnoses.create') }}" class="btn btn-success">
                <i class="fas fa-stethoscope"></i> Novo Diagnostico
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card h-100">
                <h3>{{ $activeDiagnosesCount }}</h3>
                <p>Diagnosticos Ativos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <h3>{{ $openConversationsCount }}</h3>
                <p>Chats Abertos</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <h3>{{ $waitingDoctorCount }}</h3>
                <p>Aguardando Medico</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <h3>{{ $peopleCount }}</h3>
                <p>Pacientes</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Casos Ativos Recentes</h5>
                    <a href="{{ route('diagnoses.index') }}" class="btn btn-sm btn-light">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    @if($activeDiagnoses->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Doenca</th>
                                        <th>Alerta</th>
                                        <th>Chat</th>
                                        <th class="text-end">Acao</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeDiagnoses as $diagnosis)
                                        <tr>
                                            <td>{{ optional($diagnosis->person)->name ?? 'N/A' }}</td>
                                            <td>{{ optional($diagnosis->disease)->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($diagnosis->alert_level) }}</span>
                                            </td>
                                            <td>
                                                @if($diagnosis->conversation)
                                                    <span class="badge {{ $diagnosis->conversation->status === 'open' ? 'bg-info' : 'bg-secondary' }}">
                                                        {{ $diagnosis->conversation->status === 'open' ? 'Aberto' : 'Encerrado' }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark border">Sem chat</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('diagnoses.show', $diagnosis) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Abrir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">Nenhum caso ativo no momento.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Conversas Abertas</h5>
                </div>
                <div class="card-body">
                    @forelse($openConversations as $conversation)
                        <div class="conversation-row border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <strong>{{ optional($conversation->diagnosis->person)->name ?? 'Paciente' }}</strong>
                                    <div class="small text-muted">{{ optional($conversation->diagnosis->disease)->name ?? 'Diagnostico' }}</div>
                                </div>
                                <a href="{{ route('diagnoses.show', $conversation->diagnosis) }}#chat" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-reply"></i> Responder
                                </a>
                            </div>
                            <div class="small mt-2">
                                @if($conversation->latestMessage)
                                    <strong>{{ $conversation->latestMessage->sender_type === 'doctor' ? 'Medico' : 'Paciente' }}:</strong>
                                    {{ \Illuminate\Support\Str::limit($conversation->latestMessage->message, 95) }}
                                @else
                                    <span class="text-muted">Sem mensagens registradas.</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Nenhuma conversa aberta.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
<style>
    .conversation-row:last-child {
        border-bottom: 0 !important;
        padding-bottom: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
@endsection
