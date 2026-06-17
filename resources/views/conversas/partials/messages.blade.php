@forelse($conversation->messages as $message)
    <div class="d-flex mb-3 {{ $message->sender_type === 'doctor' ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="chat-bubble {{ $message->sender_type === 'doctor' ? 'chat-doctor' : 'chat-patient' }}">
            <div class="small fw-bold mb-1">
                @if($message->sender_type === 'doctor')
                    <i class="fas fa-user-md"></i> Médico
                @else
                    <i class="fas fa-user"></i> Paciente
                @endif
            </div>
            <div>{{ $message->message }}</div>
            <div class="small text-muted mt-2">{{ $message->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
@empty
    <p class="text-muted mb-0">Nenhuma mensagem registrada ainda.</p>
@endforelse

