@extends('layouts.app')

@section('title', 'Editar Pessoa')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <h1 class="display-6 mb-4"><i class="fas fa-edit"></i> Editar Pessoa</h1>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('people.update', $person) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $person->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CPF</label>
                            <input type="text" class="form-control" value="{{ $person->cpf }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label for="age" class="form-label">Idade</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age', $person->age) }}" required min="0" max="150">
                            @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label">Telefone *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $person->phone) }}" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label">CEP *</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code', $person->zip_code) }}" maxlength="9" required>
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="housing_type" class="form-label">Tipo de Moradia *</label>
                            <select class="form-select @error('housing_type') is-invalid @enderror" id="housing_type" name="housing_type" required>
                                <option value="">Selecione...</option>
                                <option value="casa" {{ old('housing_type', $person->housing_type) === 'casa' ? 'selected' : '' }}>Casa</option>
                                <option value="apartamento" {{ old('housing_type', $person->housing_type) === 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                            </select>
                            @error('housing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8">
                            <label for="street" class="form-label">Logradouro</label>
                            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', $person->street) }}" required>
                            @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="house_number" class="form-label">Numero *</label>
                            <input type="text" class="form-control @error('house_number') is-invalid @enderror" id="house_number" name="house_number" value="{{ old('house_number', $person->house_number) }}" required>
                            @error('house_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $person->city) }}" required>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label for="state" class="form-label">UF</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $person->state) }}" maxlength="2" required>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label for="neighborhood" class="form-label">Bairros (Caratinga) *</label>
                            <select class="form-select @error('neighborhood') is-invalid @enderror @error('neighborhood.*') is-invalid @enderror" id="neighborhood" name="neighborhood[]" multiple size="6" required>
                                @foreach($neighborhoods as $neighborhood)
                                    <option value="{{ $neighborhood }}" {{ in_array($neighborhood, old('neighborhood', $selectedNeighborhoods), true) ? 'selected' : '' }}>{{ $neighborhood }}</option>
                                @endforeach
                            </select>
                            @error('neighborhood')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('neighborhood.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('people.show', $person) }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Alteracoes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function onlyDigits(value){ return value.replace(/\D/g, ''); }
function maskPhone(value){ const d=onlyDigits(value).slice(0,11); return d.length<=10 ? d.replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{4})(\d)/,'$1-$2') : d.replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{5})(\d)/,'$1-$2'); }
function maskZip(value){ const d=onlyDigits(value).slice(0,8); return d.replace(/(\d{5})(\d)/, '$1-$2'); }
const phoneInput=document.getElementById('phone');
const zipInput=document.getElementById('zip_code');
phoneInput.addEventListener('input', function(){ this.value=maskPhone(this.value); });
zipInput.addEventListener('input', function(){ this.value=maskZip(this.value); });
zipInput.addEventListener('blur', async function(){
  const cep=onlyDigits(this.value);
  if(cep.length!==8) return;
  try {
    const res=await fetch(`https://viacep.com.br/ws/${cep}/json/`);
    const data=await res.json();
    if(data.erro) return;
    document.getElementById('street').value=data.logradouro || '';
    document.getElementById('city').value=data.localidade || '';
    document.getElementById('state').value=data.uf || '';
  } catch (e) {
    console.error(e);
  }
});
</script>
@endsection
