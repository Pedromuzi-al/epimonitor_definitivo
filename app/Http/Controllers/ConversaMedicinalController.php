<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\ConversaMedicinal;
use Illuminate\Http\Request;

class ConversaMedicinalController extends Controller
{
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
            ->with('success', 'Chat iniciado para este diagnostico.');
    }

    public function storeMessage(Request $request, Diagnosis $diagnosis)
    {
        $data = $request->validate([
            'sender_type' => 'required|in:doctor,patient',
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
            'user_id' => $data['sender_type'] === 'doctor' ? auth()->id() : null,
            'sender_type' => $data['sender_type'],
            'message' => $data['message'],
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
                ->with('error', 'Este diagnostico ainda nao possui chat.');
        }

        $conversation->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Chat encerrado.');
    }
}
