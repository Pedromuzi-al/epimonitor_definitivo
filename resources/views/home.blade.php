@extends('layouts.app')

@section('title', 'Monitoramento de Sintomas')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-body p-4 p-lg-5">
                    <div>
                        <div>
                            <h1 class="display-6 mb-2">
                                <i class="fas fa-heartbeat text-danger"></i> Monitoramento de Sintomas
                            </h1>
                            <p class="text-muted mb-0">
                                @if(auth()->user()->user_type === 'person')
                                    Cadastre seus dados, realize seu diagnóstico e acompanhe o contato com o médico.
                                @else
                                    Acompanhe os sintomas registrados, visualize tendências e acesse rapidamente os principais fluxos.
                                @endif
                            </p>
                        </div>
                        @if(auth()->user()->user_type === 'person')
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <a href="{{ route('patient.profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-user-edit"></i> {{ $patientPerson ? 'Editar Meus Dados' : 'Cadastrar Meus Dados' }}
                                </a>
                                <a href="{{ route('patient.diagnoses.create') }}" class="btn btn-success {{ !$patientPerson ? 'disabled' : '' }}">
                                    <i class="fas fa-stethoscope"></i> Novo Diagnóstico
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card h-100">
                <h3>{{ $totalPeople }}</h3>
                <p>{{ auth()->user()->user_type === 'person' ? 'Cadastro Pessoal' : 'Pessoas Cadastradas' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card h-100">
                <h3>{{ $totalDiagnoses }}</h3>
                <p>{{ auth()->user()->user_type === 'person' ? 'Meus Diagnósticos' : 'Diagnósticos Registrados' }}</p>
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
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i>
                        {{ auth()->user()->user_type === 'person' ? 'Meus Últimos Diagnósticos' : 'Últimos Diagnósticos' }}
                    </h5>
                    @if(auth()->user()->user_type === 'doctor')
                        <a href="{{ route('diagnoses.index') }}" class="btn btn-sm btn-light">Ver todos</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if(auth()->user()->user_type === 'person' && !$patientPerson)
                        <div class="p-4 text-center text-muted">
                            Cadastre seus dados para realizar seu primeiro diagnóstico.
                            <div class="mt-3">
                                <a href="{{ route('patient.profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Cadastrar Meus Dados
                                </a>
                            </div>
                        </div>
                    @else
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
                                                @if(auth()->user()->user_type === 'person')
                                                    <a href="{{ route('patient.diagnoses.show', $diagnosis) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                @else
                                                    <button
                                                        class="btn btn-sm btn-outline-success"
                                                        data-resolve-diagnosis="{{ $diagnosis->id }}"
                                                        data-resolve-url="{{ route('diagnoses.resolve', $diagnosis) }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Marcar como resolvido">
                                                        <i class="fas fa-check"></i> Resolver
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            Nenhum diagnóstico registrado até o momento.
                            @if(auth()->user()->user_type === 'person')
                                <div class="mt-3">
                                    <a href="{{ route('patient.diagnoses.create') }}" class="btn btn-success">
                                        <i class="fas fa-stethoscope"></i> Realizar Diagnóstico
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!empty($patientConversation))
        <div class="card mb-4 chat-widget" id="patientChat">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-comments"></i> Chat Médico-Paciente
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $patientConversation->status === 'open' ? 'bg-info' : 'bg-secondary' }}">
                        {{ $patientConversation->status === 'open' ? 'Aberto' : 'Encerrado' }}
                    </span>
                    <button type="button" class="btn btn-sm btn-light chat-widget-close" id="closePatientChatWidget" aria-label="Minimizar chat">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-warning small">
                    <i class="fas fa-triangle-exclamation"></i>
                    Este canal registra orientações e dúvidas sobre o diagnóstico. Em sinais graves ou piora importante, procure atendimento presencial imediato.
                </div>

                <div class="small text-muted mb-3">
                    Diagnóstico: {{ optional($patientConversation->diagnosis->disease)->name ?? 'Não informado' }}
                </div>

                <div
                    class="chat-thread mb-3"
                    id="patientChatMessages"
                    data-refresh-url="{{ route('conversa.refresh', $patientConversation) }}">
                    @include('conversas.partials.messages', ['conversation' => $patientConversation])
                </div>

                @if($patientConversation->status === 'open')
                    <form action="{{ route('conversa.messages.store', $patientConversation) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_message" class="form-label">Mensagem</label>
                            <textarea
                                name="message"
                                id="patient_message"
                                rows="3"
                                class="form-control"
                                maxlength="3000"
                                required
                                placeholder="Digite sua dúvida para o médico...">{{ old('message') }}</textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-muted mb-0">Este chat foi encerrado pelo médico.</p>
                @endif
            </div>
        </div>

        <button type="button" class="chat-widget-toggle" id="openPatientChatWidget" aria-label="Abrir chat">
            <i class="fas fa-comments"></i>
        </button>
    @endif

@endsection
@section('css')
<style>
    .stat-card {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 2rem;
        border-left: 4px solid var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 0.35rem 1rem rgba(52, 152, 219, 0.22);
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

    .chat-widget {
        position: fixed;
        right: 1.5rem;
        bottom: 6.25rem;
        z-index: 1050;
        width: min(420px, calc(100vw - 2rem));
        max-height: calc(100vh - 8rem);
        margin: 0 !important;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 18px 45px rgba(24, 39, 75, 0.24);
        transform: translateY(12px);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .chat-widget.is-open {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .chat-widget .card-header {
        min-height: 56px;
    }

    .chat-widget .card-body {
        max-height: calc(100vh - 13rem);
        overflow-y: auto;
    }

    .chat-widget-toggle {
        position: fixed;
        right: 1.75rem;
        bottom: 1.5rem;
        z-index: 1051;
        width: 64px;
        height: 64px;
        border: 0;
        border-radius: 50%;
        color: #ffffff;
        background: #0d83f3;
        box-shadow: 0 14px 32px rgba(13, 131, 243, 0.36);
        font-size: 1.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .chat-widget-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 36px rgba(13, 131, 243, 0.42);
    }

    .chat-widget-close {
        width: 34px;
        height: 34px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .chat-thread {
        max-height: 250px;
        overflow-y: auto;
        padding-right: 0.25rem;
    }

    .chat-bubble {
        max-width: 78%;
        border-radius: 8px;
        padding: 0.85rem 1rem;
        border: 1px solid rgba(0,0,0,0.08);
        background: #f8f9fa;
    }

    .chat-doctor {
        background: #e8f4ff;
        border-color: rgba(52, 152, 219, 0.25);
    }

    .chat-patient {
        background: #f7f7f7;
    }

    @media (max-width: 768px) {
        .chat-widget {
            right: 1rem;
            bottom: 5.75rem;
            width: calc(100vw - 2rem);
            max-height: calc(100vh - 7rem);
        }

        .chat-widget .card-body {
            max-height: calc(100vh - 12rem);
        }

        .chat-widget-toggle {
            right: 1.25rem;
            bottom: 1.25rem;
        }

        .chat-bubble {
            max-width: 100%;
        }
    }

</style>
@endsection

@section('js')
<script>
    var patientChatWidget = document.getElementById('patientChat');
    var openPatientChatWidget = document.getElementById('openPatientChatWidget');
    var closePatientChatWidget = document.getElementById('closePatientChatWidget');

    function showPatientChatWidget() {
        if (patientChatWidget) {
            patientChatWidget.classList.add('is-open');
        }
    }

    function hidePatientChatWidget() {
        if (patientChatWidget) {
            patientChatWidget.classList.remove('is-open');
        }
    }

    if (openPatientChatWidget) {
        openPatientChatWidget.addEventListener('click', showPatientChatWidget);
    }

    if (closePatientChatWidget) {
        closePatientChatWidget.addEventListener('click', hidePatientChatWidget);
    }

    var patientChatMessages = document.getElementById('patientChatMessages');
    if (patientChatMessages && patientChatMessages.dataset.refreshUrl) {
        setInterval(function () {
            fetch(patientChatMessages.dataset.refreshUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    return response.ok ? response.text() : null;
                })
                .then(function (html) {
                    if (html !== null) {
                        patientChatMessages.innerHTML = html;
                    }
                })
                .catch(function () {});
        }, 10000);
    }
</script>
@endsection
