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
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
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
                                {{ number_format($diagnosis->probability, 2, ',', '.') }}%
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
                        <i class="fas fa-list"></i> Sintomas Informados
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
                                    @foreach($possibleDiagnoses as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['disease']->name }}</td>
                                            <td>
                                                <span class="badge {{ $index === 0 ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ number_format($item['probability'], 2, ',', '.') }}%
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
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Seus Dados</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Nome:</strong><br>{{ $diagnosis->person->name }}</p>
                    <p><strong>Data:</strong><br>{{ $diagnosis->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-0"><strong>Bairro:</strong><br><span class="badge bg-secondary">{{ $diagnosis->neighborhood }}</span></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Conversa com o Médico</h5>
                </div>
                <div class="card-body">
                    @if($diagnosis->conversation)
                        <button type="button" class="btn btn-outline-primary w-100" data-open-patient-chat-widget>
                            <i class="fas fa-comments"></i> Abrir Chat
                        </button>
                    @else
                        <p class="text-muted mb-0">O médico ainda não iniciou um chat para este diagnóstico.</p>
                    @endif
                </div>
            </div>

            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-warning"></i> Aviso Importante</h5>
                </div>
                <div class="card-body small">
                    <p>Este diagnóstico é apenas uma simulação baseada em análise de sintomas.</p>
                    <p class="mb-0"><strong>Procure um profissional de saúde para diagnóstico e tratamento adequados.</strong></p>
                </div>
            </div>
        </div>
    </div>

    @if($diagnosis->conversation)
        <div class="card mb-4 chat-widget" id="patientChat">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-comments"></i> Chat Médico-Paciente
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge {{ $diagnosis->conversation->status === 'open' ? 'bg-info' : 'bg-secondary' }}">
                        {{ $diagnosis->conversation->status === 'open' ? 'Aberto' : 'Encerrado' }}
                    </span>
                    <button type="button" class="btn btn-sm btn-light chat-widget-close" id="closePatientChatWidget" aria-label="Minimizar chat">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div
                    class="chat-thread mb-3"
                    id="patientChatMessages"
                    data-refresh-url="{{ route('conversa.refresh', $diagnosis->conversation) }}">
                    @include('conversas.partials.messages', ['conversation' => $diagnosis->conversation])
                </div>

                @if($diagnosis->conversation->status === 'open')
                    <form action="{{ route('conversa.messages.store', $diagnosis->conversation) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_message" class="form-label">Mensagem</label>
                            <textarea name="message" id="patient_message" rows="3" class="form-control" maxlength="3000" required placeholder="Digite sua dúvida para o médico...">{{ old('message') }}</textarea>
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
        openPatientChatWidget.addEventListener('click', function () {
            if (patientChatWidget && patientChatWidget.classList.contains('is-open')) {
                hidePatientChatWidget();
                return;
            }

            showPatientChatWidget();
        });
    }

    document.querySelectorAll('[data-open-patient-chat-widget]').forEach(function (button) {
        button.addEventListener('click', showPatientChatWidget);
    });

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
