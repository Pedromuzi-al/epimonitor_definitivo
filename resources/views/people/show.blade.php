@extends('layouts.app')

@section('title', $person->name)

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1 class="display-6"><i class="fas fa-user"></i> {{ $person->name }}</h1>
    </div>
    <div class="col-auto">
        <a href="{{ route('people.edit', $person) }}" class="btn btn-warning"><i class="fas fa-edit"></i> Editar</a>
        <a href="{{ route('people.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-id-card"></i> Informacoes Pessoais</h5></div>
            <div class="card-body">
                <p><span class="text-muted">CPF:</span><br><strong>{{ $person->cpf }}</strong></p>
                <p><span class="text-muted">Idade:</span><br><strong>{{ $person->age }} anos</strong></p>
                <p><span class="text-muted">Telefone:</span><br><strong>{{ $person->phone }}</strong></p>
                <p class="mb-0"><span class="text-muted">Bairro:</span><br><span class="badge bg-secondary">{{ $person->neighborhood }}</span></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Endereco</h5></div>
            <div class="card-body">
                <p><span class="text-muted">CEP:</span><br><strong>{{ $person->zip_code ?? '-' }}</strong></p>
                <p><span class="text-muted">Logradouro:</span><br><strong>{{ $person->street ?? '-' }}</strong></p>
                <p><span class="text-muted">Numero:</span><br><strong>{{ $person->house_number ?? '-' }}</strong></p>
                <p><span class="text-muted">Complemento:</span><br><strong>{{ $person->address_complement ?: '-' }}</strong></p>
                <p><span class="text-muted">Tipo de moradia:</span><br><strong>{{ ucfirst($person->housing_type ?? '-') }}</strong></p>
                <p class="mb-0"><span class="text-muted">Cidade/UF:</span><br><strong>{{ $person->city ?? '-' }} / {{ $person->state ?? '-' }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
