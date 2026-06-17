<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Person;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PatientPortalController extends Controller
{
    private const CARATINGA_NEIGHBORHOODS = [
        'Anapolis',
        'Area Rural de Caratinga',
        'Bom Pastor',
        'Centro',
        'Dario Grossi',
        'Dos Rodoviarios',
        'Doutor Eduardo Daladier Pereira',
        'Esperanca',
        'Esplanada',
        'Floresta',
        'Graca',
        'Jardim Francisco Pena',
        'Jose Moyses Nacif',
        'Limoeiro',
        'Manoel Ribeiro Sobrino',
        'Maria da Gloria',
        'Monte Libano',
        'Nossa Senhora Aparecida',
        'Nossa Senhora das Gracas',
        'Rafael Jose de Lima',
        'Salatiel',
        'Santa Cruz',
        'Santa Zita',
        'Santo Antonio',
        'Vale do Sol',
        'Zacarias',
    ];

    private const SYMPTOMS = [
        'Febre',
        'Dor de cabeca',
        'Dor de barriga',
        'Tosse',
        'Vomito',
        'Diarreia',
        'Dor no corpo',
        'Dor de garganta',
        'Coriza',
        'Fadiga',
        'Calafrios',
        'Perda de olfato',
    ];

    private DiagnosisService $diagnosisService;

    public function __construct(DiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    public function editProfile()
    {
        $person = $this->currentPerson();
        $selectedNeighborhoods = $person
            ? array_map('trim', explode(',', (string) $person->neighborhood))
            : [];

        return view('patient.profile-form', [
            'person' => $person,
            'neighborhoods' => self::CARATINGA_NEIGHBORHOODS,
            'selectedNeighborhoods' => $selectedNeighborhoods,
        ]);
    }

    public function saveProfile(Request $request)
    {
        $person = $this->currentPerson();

        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->cpf),
            'phone' => preg_replace('/\D/', '', (string) $request->phone),
            'zip_code' => preg_replace('/\D/', '', (string) $request->zip_code),
            'state' => strtoupper((string) $request->state),
        ]);

        $existingCpfPerson = Person::where('cpf', $request->cpf)->first();

        if (!$person && $existingCpfPerson) {
            if ($existingCpfPerson->user_id && (int) $existingCpfPerson->user_id !== (int) auth()->id()) {
                throw ValidationException::withMessages([
                    'cpf' => 'Este CPF já está vinculado a outra conta.',
                ]);
            }

            $person = $existingCpfPerson;
        }

        if ($person && $existingCpfPerson && (int) $existingCpfPerson->id !== (int) $person->id) {
            throw ValidationException::withMessages([
                'cpf' => 'Este CPF já está cadastrado em outro registro.',
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => [
                'required',
                'cpf',
                Rule::unique('people', 'cpf')->ignore(optional($person)->id),
            ],
            'age' => 'required|integer|min:0|max:150',
            'phone' => 'required|string|min:10|max:11',
            'neighborhood' => 'required|array|min:1',
            'neighborhood.*' => 'required|string|in:' . implode(',', self::CARATINGA_NEIGHBORHOODS),
            'zip_code' => 'required|digits:8',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
            'housing_type' => 'required|string|in:casa,apartamento',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'house_number.required' => 'O número é obrigatório.',
        ]);

        $validated['neighborhood'] = implode(', ', $validated['neighborhood']);
        $validated['user_id'] = auth()->id();

        if ($person) {
            $person->update($validated);
            $message = 'Seus dados foram atualizados com sucesso.';
        } else {
            Person::create($validated);
            $message = 'Seus dados foram cadastrados com sucesso.';
        }

        return redirect()->route('home')->with('success', $message);
    }

    public function createDiagnosis()
    {
        $person = $this->currentPerson();

        if (!$person) {
            return redirect()
                ->route('patient.profile.edit')
                ->with('error', 'Cadastre seus dados antes de realizar um diagnóstico.');
        }

        return view('patient.diagnosis-create', [
            'person' => $person,
            'symptoms' => self::SYMPTOMS,
        ]);
    }

    public function storeDiagnosis(Request $request)
    {
        $person = $this->currentPerson();

        if (!$person) {
            return redirect()
                ->route('patient.profile.edit')
                ->with('error', 'Cadastre seus dados antes de realizar um diagnóstico.');
        }

        $validated = $request->validate([
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
            'patient_notes' => 'nullable|string|max:2000',
        ]);

        $diagnoses = $this->diagnosisService->calculateDiagnosis($person, $validated['symptoms']);

        if (empty($diagnoses)) {
            return redirect()->back()->with('error', 'Nenhuma doença encontrada.');
        }

        $mostLikely = array_shift($diagnoses);
        $diagnosis = Diagnosis::create([
            'person_id' => $person->id,
            'disease_id' => $mostLikely['disease']->id,
            'probability' => $mostLikely['probability'],
            'symptoms' => $validated['symptoms'],
            'patient_notes' => $validated['patient_notes'] ?? null,
            'neighborhood' => $person->neighborhood,
            'alert_level' => $this->diagnosisService->getAlertLevel($person->neighborhood),
        ]);

        return redirect()
            ->route('patient.diagnoses.show', $diagnosis)
            ->with('success', 'Diagnóstico realizado com sucesso. O médico já poderá visualizar este registro.');
    }

    public function showDiagnosis(Diagnosis $diagnosis)
    {
        $person = $this->currentPerson();

        abort_unless($person && $diagnosis->person_id === $person->id, 403);

        $diagnosis->load(['person', 'disease', 'conversation.messages.user', 'conversation.doctor']);

        $possibleDiagnoses = $this->diagnosisService
            ->calculateDiagnosis($diagnosis->person, $diagnosis->symptoms ?? []);

        $possibleDiagnoses = array_values(array_filter($possibleDiagnoses, function ($item) {
            return ($item['probability'] ?? 0) > 0;
        }));

        return view('patient.diagnosis-show', [
            'diagnosis' => $diagnosis,
            'possibleDiagnoses' => array_slice($possibleDiagnoses, 0, 3),
        ]);
    }

    private function currentPerson(): ?Person
    {
        return Person::where('user_id', auth()->id())->first();
    }
}
