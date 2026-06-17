<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonController extends Controller
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

    public function index()
    {
        $pessoas = Person::withCount('diagnoses')->latest()->paginate(15);
        return view('people.index', ['people' => $pessoas]);
    }

    public function create()
    {
        $bairros = self::CARATINGA_NEIGHBORHOODS;
        $usuariosPacientes = User::where('user_type', 'person')
            ->whereDoesntHave('people')
            ->orderBy('name')
            ->get();

        return view('people.create', [
            'neighborhoods' => $bairros,
            'patientUsers' => $usuariosPacientes,
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->cpf),
            'phone' => preg_replace('/\D/', '', (string) $request->phone),
            'zip_code' => preg_replace('/\D/', '', (string) $request->zip_code),
            'state' => strtoupper((string) $request->state),
        ]);

        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|cpf|unique:people',
            'age' => 'required|integer|min:0|max:150',
            'phone' => 'required|string|min:10|max:11',
            'neighborhood' => 'required|array|min:1',
            'neighborhood.*' => 'required|string|in:' . implode(',', self::CARATINGA_NEIGHBORHOODS),
            'zip_code' => 'required|digits:8',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
            'housing_type' => 'required|string|in:casa,apartamento',
            'address_complement' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('user_type', 'person'),
                Rule::unique('people', 'user_id'),
            ],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'house_number.required' => 'O número é obrigatório.',
        ]);

        $dadosValidados['neighborhood'] = implode(', ', $dadosValidados['neighborhood']);
        $dadosValidados['user_id'] = $dadosValidados['user_id'] ?? null;

        Person::create($dadosValidados);
        return redirect()->route('people.index')->with('success', 'Pessoa cadastrada com sucesso!');
    }

    public function show(Person $person)
    {
        $person->load([
            'diagnoses' => function ($query) {
                $query->with(['disease', 'conversation.messages.user'])
                    ->latest();
            },
        ]);

        return view('people.show', compact('person'));
    }

    public function edit(Person $person)
    {
        $bairros = self::CARATINGA_NEIGHBORHOODS;
        $bairrosSelecionados = array_map('trim', explode(',', (string) $person->neighborhood));
        $usuariosPacientes = User::where('user_type', 'person')
            ->where(function ($query) use ($person) {
                $query->whereDoesntHave('people')
                    ->orWhere('id', $person->user_id);
            })
            ->orderBy('name')
            ->get();

        return view('people.edit', [
            'person' => $person,
            'neighborhoods' => $bairros,
            'selectedNeighborhoods' => $bairrosSelecionados,
            'patientUsers' => $usuariosPacientes,
        ]);
    }

    public function update(Request $request, Person $person)
    {
        $request->merge([
            'phone' => preg_replace('/\D/', '', (string) $request->phone),
            'zip_code' => preg_replace('/\D/', '', (string) $request->zip_code),
            'state' => strtoupper((string) $request->state),
        ]);

        $dadosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'phone' => 'required|string|min:10|max:11',
            'neighborhood' => 'required|array|min:1',
            'neighborhood.*' => 'required|string|in:' . implode(',', self::CARATINGA_NEIGHBORHOODS),
            'zip_code' => 'required|digits:8',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
            'housing_type' => 'required|string|in:casa,apartamento',
            'address_complement' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('user_type', 'person'),
                Rule::unique('people', 'user_id')->ignore($person->id),
            ],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'house_number.required' => 'O número é obrigatório.',
        ]);

        $dadosValidados['neighborhood'] = implode(', ', $dadosValidados['neighborhood']);
        $dadosValidados['user_id'] = $dadosValidados['user_id'] ?? null;

        $person->update($dadosValidados);
        return redirect()->route('people.show', $person)->with('success', 'Pessoa atualizada com sucesso!');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('people.index')->with('success', 'Pessoa deletada com sucesso!');
    }
}
