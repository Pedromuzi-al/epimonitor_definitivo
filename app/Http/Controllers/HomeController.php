<?php

namespace App\Http\Controllers;

use App\Models\ConversaMedicinal;
use App\Models\Diagnosis;
use App\Models\Disease;
use App\Models\Person;

class HomeController extends Controller
{
    public function index()
    {
        $patientPerson = null;
        $patientDiagnoses = collect();
        $totalDiseases = Disease::count();
        $patientConversation = null;
        $totalPeople = Person::count();
        $totalDiagnoses = Diagnosis::count();
        $latestDiagnoses = Diagnosis::with(['person', 'disease'])
            ->unresolved()
            ->latest()
            ->take(5)
            ->get();

        if (auth()->user()->user_type === 'person') {
            $patientPerson = Person::with(['diagnoses.disease', 'diagnoses.conversation'])
                ->where('user_id', auth()->id())
                ->first();
            $patientDiagnoses = $patientPerson
                ? $patientPerson->diagnoses()->with(['disease', 'conversation'])->latest()->take(5)->get()
                : collect();
            $totalPeople = $patientPerson ? 1 : 0;
            $totalDiagnoses = $patientPerson ? $patientPerson->diagnoses()->count() : 0;
            $latestDiagnoses = $patientDiagnoses;

            $patientConversation = ConversaMedicinal::with(['diagnosis.person', 'diagnosis.disease', 'messages.user'])
                ->whereHas('diagnosis.person', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->latest()
                ->first();
        }

        return view('home', [
            'totalPeople' => $totalPeople,
            'totalDiagnoses' => $totalDiagnoses,
            'totalDiseases' => $totalDiseases,
            'latestDiagnoses' => $latestDiagnoses,
            'patientConversation' => $patientConversation,
            'patientPerson' => $patientPerson,
            'patientDiagnoses' => $patientDiagnoses,
        ]);
    }
}
