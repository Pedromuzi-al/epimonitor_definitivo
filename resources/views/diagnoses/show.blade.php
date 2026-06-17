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
        <div class="col-lg-8 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Pessoa:</p>
                            <h5>{{ $diagnosis->person->name }}</h5>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Data do Diagnóstico:</p>
                            <h5>{{ $diagnosis->created_at->format('d/m/Y H:i') }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-diagnoses"></i> Diagnóstico Mais Provavel
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

            @if($diagnosis->patient_notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-notes-medical"></i> Relato do Paciente
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $diagnosis->patient_notes }}</p>
                    </div>
                </div>
            @endif

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
                                    @foreach(array_slice($possibleDiagnoses, 0, 3) as $index => $item)
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

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Nivel de Alerta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @switch($diagnosis->alert_level)
                            @case('critical')
                                <div style="font-size: 1.5rem;" class="mb-2">
                                    <span class="badge badge-critical p-3">
                                        <i class="fas fa-exclamation"></i> CRITICO
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

            <div class="card mb-4 chat-widget" id="chat">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-comments"></i> Chat Médico-Paciente
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        @if($diagnosis->conversation)
                            <span class="badge {{ $diagnosis->conversation->status === 'open' ? 'bg-info' : 'bg-secondary' }}">
                                {{ $diagnosis->conversation->status === 'open' ? 'Aberto' : 'Encerrado' }}
                            </span>
                        @endif
                        <button type="button" class="btn btn-sm btn-light chat-widget-close" id="closeChatWidget" data-close-chat-widget aria-label="Minimizar chat">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small">
                        <i class="fas fa-triangle-exclamation"></i>
                        Este canal registra orientações e dúvidas do caso. Em sinais graves ou piora importante, oriente atendimento presencial imediato.
                    </div>

                    @if(!$diagnosis->conversation)
                        <form action="{{ route('diagnoses.conversation.start', $diagnosis) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment-medical"></i> Iniciar chat com paciente
                            </button>
                        </form>
                    @else
                        <div
                            class="chat-thread mb-3"
                            id="chatMessages"
                            data-refresh-url="{{ route('conversa.refresh', $diagnosis->conversation) }}">
                            @include('conversas.partials.messages', ['conversation' => $diagnosis->conversation])
                        </div>

                        @if($diagnosis->conversation->status === 'open')
                            <form action="{{ route('diagnoses.conversation.messages.store', $diagnosis) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label for="message" class="form-label">Mensagem</label>
                                        <textarea name="message" id="message" rows="3" class="form-control" maxlength="3000" required placeholder="Registre a orientacao ou a duvida do paciente...">{{ old('message') }}</textarea>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-message" data-message="Continue observando os sintomas e informe se houver piora.">Observacao</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-message" data-message="Procure atendimento imediato se houver falta de ar, dor no peito, desmaio ou piora intensa.">Alerta</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-message" data-message="Agende um retorno para reavaliarmos a evolucao do quadro.">Retorno</button>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Enviar
                                    </button>
                                </div>
                            </form>

                            <form action="{{ route('diagnoses.conversation.close', $diagnosis) }}" method="POST" onsubmit="return confirm('Encerrar este chat?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-lock"></i> Encerrar chat
                                </button>
                            </form>
                        @else
                            <form action="{{ route('diagnoses.conversation.start', $diagnosis) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-lock-open"></i> Reabrir chat
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informações da Pessoa</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Nome:</strong><br>{{ $diagnosis->person->name }}</p>
                    <p><strong>CPF:</strong><br>{{ $diagnosis->person->cpf }}</p>
                    <p><strong>Idade:</strong><br>{{ $diagnosis->person->age }} anos</p>
                    <p><strong>Bairro:</strong><br><span class="badge bg-secondary">{{ $diagnosis->neighborhood }}</span></p>
                    <p class="mb-0"><strong>Telefone:</strong><br>{{ $diagnosis->person->phone }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Ações</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('people.show', $diagnosis->person) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-circle"></i> Ver Perfil
                    </a>
                    <a href="{{ route('diagnoses.create') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-plus"></i> Novo Diagnóstico
                    </a>
                    @if($diagnosis->conversation)
                        <button type="button" class="btn btn-outline-primary w-100" data-open-chat-widget>
                            <i class="fas fa-comments"></i> Ir para o Chat
                        </button>
                    @else
                        <form action="{{ route('diagnoses.conversation.start', $diagnosis) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-comment-medical"></i> Iniciar Chat
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-warning"></i> Aviso Importante</h5>
                </div>
                <div class="card-body small">
                    <p>Este diagnóstico é apenas uma simulação baseada em análise de sintomas.</p>
                    <p class="mb-0">
                        <strong>Sempre consulte um profissional de saúde qualificado para diagnóstico e tratamento adequados.</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="chat-widget-toggle" id="openChatWidget" aria-label="Abrir chat">
        <i class="fas fa-comments"></i>
    </button>
@endsection
@section('css')
<style>
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
    document.querySelectorAll('.quick-message').forEach(function (button) {
        button.addEventListener('click', function () {
            var messageField = document.getElementById('message');
            if (messageField) {
                messageField.value = this.dataset.message;
                messageField.focus();
            }
        });
    });

    var chatWidget = document.getElementById('chat');
    var openChatWidget = document.getElementById('openChatWidget');
    var closeChatWidget = document.getElementById('closeChatWidget');

    function showChatWidget() {
        if (chatWidget) {
            chatWidget.classList.add('is-open');
        }
    }

    function hideChatWidget() {
        if (chatWidget) {
            chatWidget.classList.remove('is-open');
        }
    }

    if (openChatWidget) {
        openChatWidget.addEventListener('click', function () {
            if (chatWidget && chatWidget.classList.contains('is-open')) {
                hideChatWidget();
                return;
            }

            showChatWidget();
        });
    }

    document.addEventListener('click', function (event) {
        var closeButton = event.target.closest('[data-close-chat-widget], #closeChatWidget');
        var openButton = event.target.closest('[data-open-chat-widget]');

        if (closeButton) {
            event.preventDefault();
            hideChatWidget();
            return;
        }

        if (openButton) {
            event.preventDefault();
            showChatWidget();
        }
    });

    if (new URLSearchParams(window.location.search).get('openChat') === '1') {
        showChatWidget();
    }

    var chatMessages = document.getElementById('chatMessages');
    if (chatMessages && chatMessages.dataset.refreshUrl) {
        setInterval(function () {
            fetch(chatMessages.dataset.refreshUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    return response.ok ? response.text() : null;
                })
                .then(function (html) {
                    if (html !== null) {
                        chatMessages.innerHTML = html;
                    }
                })
                .catch(function () {});
        }, 10000);
    }
</script>
@endsection
