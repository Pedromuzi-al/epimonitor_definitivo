@extends('layouts.app')

@section('title', 'Diagnósticos')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-stethoscope"></i> Diagnósticos
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('diagnoses.create') }}" class="btn btn-success btn-lg">
                <i class="fas fa-plus"></i> Novo Diagnóstico
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('diagnoses.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ request('name') }}" placeholder="Ex.: Maria Silva">
                    </div>
                    <div class="col-md-3">
                        <label for="neighborhood" class="form-label">Bairro</label>
                        <input type="text" id="neighborhood" name="neighborhood" class="form-control" value="{{ request('neighborhood') }}" placeholder="Ex.: Centro">
                    </div>
                    <div class="col-md-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" id="cpf" name="cpf" class="form-control" value="{{ request('cpf') }}" placeholder="000.000.000-00">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('diagnoses.index') }}" class="btn btn-outline-secondary">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($diagnoses->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Pessoa</th>
                            <th>Doença</th>
                            <th>Probabilidade</th>
                            <th>Bairro</th>
                            <th>Alerta</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($diagnoses as $diagnosis)
                            <tr>
                                <td>{{ $diagnosis->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('people.show', $diagnosis->person) }}">
                                        {{ $diagnosis->person->name }}
                                    </a>
                                </td>
                                <td><strong>{{ $diagnosis->disease->name }}</strong></td>
                                <td>
                                    <div style="width: 120px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $diagnosis->probability }}%;" 
                                                 aria-valuenow="{{ $diagnosis->probability }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($diagnosis->probability, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $diagnosis->neighborhood }}</span>
                                </td>
                                <td>
                                    @switch($diagnosis->alert_level)
                                        @case('critical')
                                            <span class="badge badge-critical"><i class="fas fa-exclamation"></i> Crítico</span>
                                            @break
                                        @case('high')
                                            <span class="badge badge-high"><i class="fas fa-exclamation-triangle"></i> Alto</span>
                                            @break
                                        @case('moderate')
                                            <span class="badge badge-moderate"><i class="fas fa-info-circle"></i> Moderado</span>
                                            @break
                                        @default
                                            <span class="badge badge-low"><i class="fas fa-check"></i> Baixo</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('diagnoses.show', $diagnosis) }}" class="btn btn-sm btn-info" title="Visualizar diagnóstico">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <button 
                                        class="btn btn-sm btn-success"
                                        data-resolve-diagnosis="{{ $diagnosis->id }}"
                                        data-resolve-url="{{ route('diagnoses.resolve', $diagnosis) }}"
                                        title="Marcar como resolvido">
                                        <i class="fas fa-check"></i> Resolver
                                    </button>
                                    <form action="{{ route('diagnoses.destroy', $diagnosis) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')" title="Deletar diagnóstico">
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

        <div class="d-flex justify-content-center mt-4">
            {{ $diagnoses->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Nenhum diagnóstico realizado. 
            <a href="{{ route('diagnoses.create') }}">Realizar o primeiro diagnóstico</a>
        </div>
    @endif
@endsection
