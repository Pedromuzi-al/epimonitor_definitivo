@extends('layouts.app')

@section('title', 'Novo Diagnóstico')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h1 class="display-6">
                <i class="fas fa-stethoscope"></i> Novo Diagnóstico
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('diagnoses.store') }}" method="POST" id="diagnosisForm">
                        @csrf

                        <!-- Step 1: Select Person -->
                        <div class="mb-4">
                            <label for="person_id" class="form-label">
                                <strong>1. Selecione a Pessoa</strong>
                            </label>
                            <select class="form-select @error('person_id') is-invalid @enderror" 
                                    id="person_id" name="person_id" required onchange="updatePersonInfo()">
                                <option value="">-- Selecione uma pessoa --</option>
                                @forelse($people as $person)
                                    <option value="{{ $person->id }}" 
                                            data-age="{{ $person->age }}" 
                                            data-neighborhood="{{ $person->neighborhood }}">
                                        {{ $person->name }} (CPF: {{ $person->cpf }})
                                    </option>
                                @empty
                                    <option value="" disabled>Nenhuma pessoa cadastrada</option>
                                @endforelse
                            </select>
                            @error('person_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Person Info Card -->
                        <div id="personInfoCard" class="card mb-4" style="display: none;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Idade:</small>
                                        <p class="mb-0"><strong id="personAge">-</strong></p>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Bairro:</small>
                                        <p class="mb-0"><strong id="personNeighborhood">-</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Select Symptoms -->
                        <div class="mb-4">
                            <label class="form-label">
                                <strong>2. Selecione os Sintomas Presentes</strong>
                            </label>
                            <p class="text-muted small">Marque todos os sintomas que a pessoa está apresentando</p>

                            <div class="row">
                                @foreach($symptoms as $symptom)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="symptom_{{ str_replace(' ', '_', $symptom) }}" 
                                                   name="symptoms[]" value="{{ $symptom }}">
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

                        <!-- Selected Symptoms Summary -->
                        <div id="symptomsCard" class="card mb-4" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0">Sintomas Selecionados</h5>
                            </div>
                            <div class="card-body">
                                <div id="selectedSymptomsList"></div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('diagnoses.index') }}" class="btn btn-secondary">
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

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações</h5>
                </div>
                <div class="card-body small">
                    <p>
                        <strong>Como funciona:</strong>
                    </p>
                    <ol>
                        <li>Selecione a pessoa que será analisada</li>
                        <li>Marque todos os sintomas presentes</li>
                        <li>O sistema analisará os sintomas e fornecerá diagnósticos com probabilidades</li>
                    </ol>
                    <hr>
                    <p class="mb-0">
                        <strong>Aviso:</strong> Este sistema não substitui diagnóstico médico real. Consulte sempre um profissional de saúde.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-virus-covid"></i> 12 Sintomas Monitorados</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled small">
                        @foreach($symptoms as $symptom)
                            <li><i class="fas fa-check text-success"></i> {{ $symptom }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePersonInfo() {
            const select = document.getElementById('person_id');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                document.getElementById('personAge').textContent = option.dataset.age;
                document.getElementById('personNeighborhood').textContent = option.dataset.neighborhood;
                document.getElementById('personInfoCard').style.display = 'block';
            } else {
                document.getElementById('personInfoCard').style.display = 'none';
            }
            validateForm();
        }

        document.querySelectorAll('input[name="symptoms[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedSymptoms);
        });

        function updateSelectedSymptoms() {
            const selected = Array.from(document.querySelectorAll('input[name="symptoms[]"]:checked'))
                .map(cb => cb.value);
            
            const list = document.getElementById('selectedSymptomsList');
            
            if (selected.length > 0) {
                document.getElementById('symptomsCard').style.display = 'block';
                list.innerHTML = selected.map(s => 
                    `<span class="badge bg-primary me-2 mb-2">${s}</span>`
                ).join('');
            } else {
                document.getElementById('symptomsCard').style.display = 'none';
            }
            
            validateForm();
        }

        function validateForm() {
            const person = document.getElementById('person_id').value;
            const symptoms = document.querySelectorAll('input[name="symptoms[]"]:checked').length > 0;
            
            document.getElementById('submitBtn').disabled = !(person && symptoms);
        }
    </script>
@endsection

