<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Person;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    private $diagnosisService;

    public function __construct(DiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diagnoses = Diagnosis::with(['person', 'disease'])
            ->latest()
            ->paginate(15);
        return view('diagnoses.index', compact('diagnoses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $people = Person::orderBy('name')->get();
        $symptoms = [
            'Febre',
            'Dor de cabeça',
            'Dor de barriga',
            'Tosse',
            'Vômito',
            'Diarreia',
            'Dor no corpo',
            'Dor de garganta',
            'Coriza',
            'Fadiga',
            'Calafrios',
            'Perda de olfato',
        ];
        return view('diagnoses.create', compact('people', 'symptoms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
        ]);

        $person = Person::findOrFail($validated['person_id']);
        $symptoms = $validated['symptoms'];

        // Calculate diagnoses
        $diagnoses = $this->diagnosisService->calculateDiagnosis($person, $symptoms);
        
        if (empty($diagnoses)) {
            return redirect()->back()->with('error', 'Nenhuma doença encontrada.');
        }

        // Get the most likely disease
        $mostLikely = array_shift($diagnoses);
        $disease = $mostLikely['disease'];
        $probability = $mostLikely['probability'];
        $alertLevel = $this->diagnosisService->getAlertLevel($person->neighborhood);

        // Store diagnosis
        $diagnosis = Diagnosis::create([
            'person_id' => $person->id,
            'disease_id' => $disease->id,
            'probability' => $probability,
            'symptoms' => $symptoms,
            'neighborhood' => $person->neighborhood,
            'alert_level' => $alertLevel,
        ]);

        return redirect()->route('diagnoses.show', $diagnosis)
            ->with('success', 'Diagnóstico realizado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  Diagnosis  $diagnosis
     * @return \Illuminate\Http\Response
     */
    public function show(Diagnosis $diagnosis)
    {
        $diagnosis->load(['person', 'disease']);
        $possibleDiagnoses = $this->diagnosisService
            ->calculateDiagnosis($diagnosis->person, $diagnosis->symptoms ?? []);

        $possibleDiagnoses = array_values(array_filter($possibleDiagnoses, function ($item) {
            return ($item['probability'] ?? 0) > 0;
        }));

        return view('diagnoses.show', compact('diagnosis', 'possibleDiagnoses'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Diagnosis  $diagnosis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();
        return redirect()->route('diagnoses.index')->with('success', 'Diagnóstico deletado com sucesso!');
    }
}
