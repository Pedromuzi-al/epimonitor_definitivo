<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\ConversaMedicinal;
use Illuminate\Http\Request;

class ConversaMedicinalController extends Controller
{
    private function canAccessConversation(ConversaMedicinal $conversa): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ($user->user_type === 'doctor') {
            return true;
        }

        return $conversa->diagnosis()
            ->whereHas('person', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }

    public function start(Diagnosis $diagnosis)
    {
        $conversation = ConversaMedicinal::firstOrCreate(
            ['diagnosis_id' => $diagnosis->id],
            [
                'doctor_id' => auth()->id(),
                'status' => 'open',
            ]
        );

        if (!$conversation->isOpen()) {
            $conversation->update([
                'status' => 'open',
                'closed_at' => null,
            ]);
        }

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Chat iniciado para este diagnóstico.');
    }

    public function storeMessage(Request $request, Diagnosis $diagnosis)
    {
        $data = $request->validate([
            'message' => 'required|string|max:3000',
        ]);

        $conversation = ConversaMedicinal::firstOrCreate(
            ['diagnosis_id' => $diagnosis->id],
            [
                'doctor_id' => auth()->id(),
                'status' => 'open',
            ]
        );

        if (!$conversation->isOpen()) {
            return redirect()
                ->route('diagnoses.show', $diagnosis)
                ->with('error', 'Reabra o chat antes de enviar novas mensagens.');
        }

        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'sender_type' => 'doctor',
            'message' => $data['message'],
            'read' => true,
            'read_at' => now(),
        ]);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Mensagem registrada no chat.');
    }

    public function close(Diagnosis $diagnosis)
    {
        $conversation = $diagnosis->conversation;

        if (!$conversation) {
            return redirect()
                ->route('diagnoses.show', $diagnosis)
                ->with('error', 'Este diagnóstico ainda não possui chat.');
        }

        $conversation->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Chat encerrado.');
    }

    public function storePatientMessage(Request $request, ConversaMedicinal $conversa)
    {
        abort_unless($this->canAccessConversation($conversa), 403);
        abort_unless(auth()->user()->user_type === 'person', 403);

        if (!$conversa->isOpen()) {
            return redirect()
                ->back()
                ->with('error', 'Este chat está encerrado.');
        }

        $data = $request->validate([
            'message' => 'required|string|max:3000',
        ]);

        $conversa->messages()->create([
            'user_id' => auth()->id(),
            'sender_type' => 'patient',
            'message' => $data['message'],
            'read' => false,
            'read_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Mensagem enviada ao médico.');
    }

    public function refresh(ConversaMedicinal $conversa)
    {
        abort_unless($this->canAccessConversation($conversa), 403);

        $conversa->load(['messages.user']);

        return view('conversas.partials.messages', [
            'conversation' => $conversa,
        ]);
    }
}
