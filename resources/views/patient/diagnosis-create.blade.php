@extends('layouts.app')

@section('title', 'Meu Diagnóstico')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-stethoscope"></i> Meu Diagnóstico
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>{{ $person->name }}</strong>, selecione os sintomas que você está sentindo para registrar seu diagnóstico.
                    </div>

                    <form action="{{ route('patient.diagnoses.store') }}" method="POST" id="diagnosisForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">
                                <strong>Sintomas presentes</strong>
                            </label>
                            <p class="text-muted small">Marque todos os sintomas que se aplicam ao seu caso.</p>

                            <div class="row">
                                @foreach($symptoms as $symptom)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="symptom_{{ str_replace(' ', '_', $symptom) }}"
                                                   name="symptoms[]" value="{{ $symptom }}"
                                                   {{ in_array($symptom, old('symptoms', []), true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="symptom_{{ str_replace(' ', '_', $symptom) }}">
                                                {{ $symptom }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('symptoms')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="symptomsCard" class="card mb-4" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0">Sintomas Selecionados</h5>
                            </div>
                            <div class="card-body">
                                <div id="selectedSymptomsList"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="patient_notes" class="form-label">
                                <strong>Descreva o que você está sentindo</strong>
                            </label>
                            <textarea
                                class="form-control @error('patient_notes') is-invalid @enderror"
                                id="patient_notes"
                                name="patient_notes"
                                rows="4"
                                maxlength="2000"
                                placeholder="Exemplo: estou com febre desde ontem, dor forte na garganta e cansaço ao caminhar...">{{ old('patient_notes') }}</textarea>
                            <div class="form-text">Escreva detalhes como intensidade, quando começou, frequência e se houve piora.</div>
                            @error('patient_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-check"></i> Realizar Diagnóstico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Seus Dados</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Nome:</strong><br>{{ $person->name }}</p>
                    <p><strong>Idade:</strong><br>{{ $person->age }} anos</p>
                    <p class="mb-0"><strong>Bairro:</strong><br>{{ $person->neighborhood }}</p>
                </div>
            </div>

            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-warning"></i> Aviso Importante</h5>
                </div>
                <div class="card-body small">
                    <p>Este diagnóstico é apenas uma simulação baseada em análise de sintomas.</p>
                    <p class="mb-0"><strong>Procure atendimento presencial em caso de piora, sintomas graves ou emergência.</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    document.querySelectorAll('input[name="symptoms[]"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelectedSymptoms);
    });

    function updateSelectedSymptoms() {
        var selected = Array.from(document.querySelectorAll('input[name="symptoms[]"]:checked'))
            .map(function (checkbox) {
                return checkbox.value;
            });
        var list = document.getElementById('selectedSymptomsList');

        if (selected.length > 0) {
            document.getElementById('symptomsCard').style.display = 'block';
            list.innerHTML = selected.map(function (symptom) {
                return '<span class="badge bg-primary me-2 mb-2">' + symptom + '</span>';
            }).join('');
        } else {
            document.getElementById('symptomsCard').style.display = 'none';
        }

        document.getElementById('submitBtn').disabled = selected.length === 0;
    }

    updateSelectedSymptoms();
</script>
@endsection
