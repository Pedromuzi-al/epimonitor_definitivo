@extends('layouts.app')

@section('title', 'Pessoas Cadastradas')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-users"></i> Pessoas Cadastradas
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('people.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-user-plus"></i> Adicionar Pessoa
            </a>
        </div>
    </div>

    @if($people->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Idade</th>
                            <th>Telefone</th>
                            <th>Bairro</th>
                            <th>Diagnósticos</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($people as $person)
                            <tr>
                                <td>
                                    <strong>{{ $person->name }}</strong>
                                </td>
                                <td>{{ $person->cpf }}</td>
                                <td>{{ $person->age }} anos</td>
                                <td>{{ $person->phone }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $person->neighborhood }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $person->diagnoses_count ?? 0 }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('people.show', $person) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('people.edit', $person) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('people.destroy', $person) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                            <i class="fas fa-trash"></i> Deletar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $people->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhuma pessoa cadastrada. 
            <a href="{{ route('people.create') }}">Cadastre a primeira pessoa</a>
        </div>
    @endif
@endsection
