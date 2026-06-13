<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\ConversaMedicinal;
use App\Models\ConversaMedicinalMensagem;
use App\Models\Person;

class DoctorPanelController extends Controller
{
    public function index()
    {
        $activeDiagnoses = Diagnosis::with(['person', 'disease', 'conversation.latestMessage'])
            ->unresolved()
            ->latest()
            ->take(8)
            ->get();

        $openConversations = ConversaMedicinal::with(['diagnosis.person', 'diagnosis.disease', 'latestMessage'])
            ->where('status', 'open')
            ->latest()
            ->take(8)
            ->get();

        $latestMessageIds = ConversaMedicinalMensagem::selectRaw('MAX(id)')
            ->groupBy('medical_conversation_id');

        $waitingDoctorCount = ConversaMedicinal::where('status', 'open')
            ->whereHas('messages', function ($query) use ($latestMessageIds) {
                $query->whereIn('id', $latestMessageIds)
                    ->where('sender_type', 'patient');
            })
            ->count();

        return view('doctor-panel.index', [
            'activeDiagnosesCount' => Diagnosis::unresolved()->count(),
            'openConversationsCount' => ConversaMedicinal::where('status', 'open')->count(),
            'waitingDoctorCount' => $waitingDoctorCount,
            'peopleCount' => Person::count(),
            'activeDiagnoses' => $activeDiagnoses,
            'openConversations' => $openConversations,
        ]);
    }
}
