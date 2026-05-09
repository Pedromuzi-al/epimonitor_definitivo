<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

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
        $people = Person::withCount('diagnoses')->latest()->paginate(15);
        return view('people.index', compact('people'));
    }

    public function create()
    {
        $neighborhoods = self::CARATINGA_NEIGHBORHOODS;
        return view('people.create', compact('neighborhoods'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->cpf),
            'phone' => preg_replace('/\D/', '', (string) $request->phone),
            'zip_code' => preg_replace('/\D/', '', (string) $request->zip_code),
            'state' => strtoupper((string) $request->state),
        ]);

        $validated = $request->validate([
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
        ], [
            'name.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'house_number.required' => 'O número é obrigatório.',
        ]);

        $validated['neighborhood'] = implode(', ', $validated['neighborhood']);

        Person::create($validated);
        return redirect()->route('people.index')->with('success', 'Pessoa cadastrada com sucesso!');
    }

    public function show(Person $person)
    {
        $person->load(['diagnoses.disease']);
        return view('people.show', compact('person'));
    }

    public function edit(Person $person)
    {
        $neighborhoods = self::CARATINGA_NEIGHBORHOODS;
        $selectedNeighborhoods = array_map('trim', explode(',', (string) $person->neighborhood));

        return view('people.edit', compact('person', 'neighborhoods', 'selectedNeighborhoods'));
    }

    public function update(Request $request, Person $person)
    {
        $request->merge([
            'phone' => preg_replace('/\D/', '', (string) $request->phone),
            'zip_code' => preg_replace('/\D/', '', (string) $request->zip_code),
            'state' => strtoupper((string) $request->state),
        ]);

        $validated = $request->validate([
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
        ], [
            'name.required' => 'O nome é obrigatório.',
            'phone.required' => 'O telefone é obrigatório.',
            'zip_code.required' => 'O CEP é obrigatório.',
            'neighborhood.required' => 'O bairro é obrigatório.',
            'house_number.required' => 'O número é obrigatório.',
        ]);

        $validated['neighborhood'] = implode(', ', $validated['neighborhood']);

        $person->update($validated);
        return redirect()->route('people.show', $person)->with('success', 'Pessoa atualizada com sucesso!');
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('people.index')->with('success', 'Pessoa deletada com sucesso!');
    }
}
