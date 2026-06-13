<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Person;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    private $servicoDiagnostico;

    public function __construct(DiagnosisService $servicoDiagnostico)
    {
        $this->servicoDiagnostico = $servicoDiagnostico;
    }

    /**
     * Exibe a listagem de diagnosticos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nome = trim((string) $request->input('name', ''));
        $bairro = trim((string) $request->input('neighborhood', ''));
        $cpf = preg_replace('/\D/', '', (string) $request->input('cpf', ''));

        $diagnosticos = Diagnosis::with(['person', 'disease', 'conversation.latestMessage'])
            ->unresolved()
            ->when($nome !== '', function ($query) use ($nome) {
                $query->whereHas('person', function ($consultaPessoa) use ($nome) {
                    $consultaPessoa->where('name', 'like', '%' . $nome . '%');
                });
            })
            ->when($bairro !== '', function ($query) use ($bairro) {
                $query->where('neighborhood', 'like', '%' . $bairro . '%');
            })
            ->when($cpf !== '', function ($query) use ($cpf) {
                $query->whereHas('person', function ($consultaPessoa) use ($cpf) {
                    $consultaPessoa->where('cpf', 'like', '%' . $cpf . '%');
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $bairros = Diagnosis::query()
            ->unresolved()
            ->whereNotNull('neighborhood')
            ->where('neighborhood', '!=', '')
            ->whereNotNull('symptoms')
            ->where('symptoms', '!=', '[]')
            ->select('neighborhood')
            ->distinct()
            ->orderBy('neighborhood')
            ->pluck('neighborhood');

        return view('diagnoses.index', [
            'diagnoses' => $diagnosticos,
            'neighborhoods' => $bairros,
        ]);
    }

    /**
     * Exibe o formulario para criar um novo recurso.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pessoas = Person::orderBy('name')->get();
        $sintomas = [
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

        return view('diagnoses.create', [
            'people' => $pessoas,
            'symptoms' => $sintomas,
        ]);
    }

    /**
     * Armazena um novo recurso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'person_id' => 'required|exists:people,id',
            'symptoms' => 'required|array|min:1',
            'symptoms.*' => 'string',
        ]);

        $pessoa = Person::findOrFail($dadosValidados['person_id']);
        $sintomas = $dadosValidados['symptoms'];

        // Calcula os diagnosticos com base nos sintomas informados.
        $diagnosticos = $this->servicoDiagnostico->calculateDiagnosis($pessoa, $sintomas);

        if (empty($diagnosticos)) {
            return redirect()->back()->with('error', 'Nenhuma doenca encontrada.');
        }

        // Seleciona a doenca mais provavel.
        $maisProvavel = array_shift($diagnosticos);
        $doenca = $maisProvavel['disease'];
        $probabilidade = $maisProvavel['probability'];
        $nivelAlerta = $this->servicoDiagnostico->getAlertLevel($pessoa->neighborhood);

        // Salva o diagnostico no banco.
        $diagnostico = Diagnosis::create([
            'person_id' => $pessoa->id,
            'disease_id' => $doenca->id,
            'probability' => $probabilidade,
            'symptoms' => $sintomas,
            'neighborhood' => $pessoa->neighborhood,
            'alert_level' => $nivelAlerta,
        ]);

        return redirect()->route('diagnoses.show', $diagnostico)
            ->with('success', 'Diagnostico realizado com sucesso!');
    }

    /**
     * Exibe o recurso especificado.
     *
     * @param  Diagnosis  $diagnosis
     * @return \Illuminate\Http\Response
     */
    public function show(Diagnosis $diagnosis)
    {
        $diagnosis->load(['person', 'disease', 'conversation.messages.user', 'conversation.doctor']);
        $possiveisDiagnosticos = $this->servicoDiagnostico
            ->calculateDiagnosis($diagnosis->person, $diagnosis->symptoms ?? []);

        $possiveisDiagnosticos = array_values(array_filter($possiveisDiagnosticos, function ($item) {
            return ($item['probability'] ?? 0) > 0;
        }));

        return view('diagnoses.show', [
            'diagnosis' => $diagnosis,
            'possibleDiagnoses' => $possiveisDiagnosticos,
        ]);
    }

    /**
     * Remove o recurso especificado.
     *
     * @param  Diagnosis  $diagnosis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();

        return redirect()->route('diagnoses.index')->with('success', 'Diagnostico deletado com sucesso!');
    }

    /**
     * Marca diagnostico como resolvido.
     *
     * @param  Diagnosis  $diagnosis
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resolve(Diagnosis $diagnosis, Request $request)
    {
        $dadosValidados = $request->validate([
            'resolution_reason' => 'nullable|string|max:255',
        ]);

        $diagnosis->markAsResolved($dadosValidados['resolution_reason'] ?? null);

        return redirect()->route('diagnoses.index')
            ->with('success', 'Diagnostico marcado como resolvido e removido do banco ativo!');
    }

    /**
     * Marca todos os diagnosticos nao resolvidos como resolvidos.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resolveAll(Request $request)
    {
        $dadosValidados = $request->validate([
            'resolution_reason' => 'nullable|string|max:255',
        ]);

        $totalAtualizado = Diagnosis::query()
            ->unresolved()
            ->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolution_reason' => $dadosValidados['resolution_reason'] ?? 'Resolucao global de alertas',
            ]);

        if ($totalAtualizado === 0) {
            return redirect()->back()->with('error', 'Nenhum diagnostico ativo para resolver.');
        }

        return redirect()->back()->with('success', "Todos os diagnosticos ativos foram resolvidos ({$totalAtualizado} registro(s)).");
    }

    /**
     * Marca diagnosticos nao resolvidos como resolvidos por bairros selecionados.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resolveByNeighborhood(Request $request)
    {
        $dadosValidados = $request->validate([
            'neighborhoods' => 'required|array|min:1',
            'neighborhoods.*' => 'required|string|max:255',
            'resolution_reason' => 'nullable|string|max:255',
        ]);

        $bairros = array_values(array_unique(array_filter($dadosValidados['neighborhoods'])));

        $totalAtualizado = Diagnosis::query()
            ->unresolved()
            ->whereIn('neighborhood', $bairros)
            ->update([
                'is_resolved' => true,
                'resolved_at' => now(),
                'resolution_reason' => $dadosValidados['resolution_reason'] ?? 'Resolucao por bairro selecionado',
            ]);

        if ($totalAtualizado === 0) {
            return redirect()->back()->with('error', 'Nenhum diagnostico ativo encontrado nos bairros selecionados.');
        }

        return redirect()->back()->with('success', "Diagnosticos ativos resolvidos para os bairros selecionados ({$totalAtualizado} registro(s)).");
    }
}
