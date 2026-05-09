@extends('layouts.app')

@section('title', 'Cadastrar Pessoa')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="display-6 mb-0"><i class="fas fa-user-plus"></i> Cadastro de Pessoa</h1>
            <a href="{{ route('people.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0">Dados Pessoais e Endereco</h5></div>
            <div class="card-body">
                <form action="{{ route('people.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="cpf" class="form-label">CPF *</label>
                            <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14" required>
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label for="age" class="form-label">Idade</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" min="0" max="150" required>
                            @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="phone" class="form-label">Telefone *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000" maxlength="15" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label">CEP *</label>
                            <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" placeholder="00000-000" maxlength="9" required>
                            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="housing_type" class="form-label">Tipo de Moradia</label>
                            <select class="form-select @error('housing_type') is-invalid @enderror" id="housing_type" name="housing_type" required>
                                <option value="">Selecione...</option>
                                <option value="casa" {{ old('housing_type') === 'casa' ? 'selected' : '' }}>Casa</option>
                                <option value="apartamento" {{ old('housing_type') === 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                            </select>
                            @error('housing_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="street" class="form-label">Logradouro</label>
                            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}" required>
                            @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label for="house_number" class="form-label">Numero *</label>
                            <input type="text" class="form-control @error('house_number') is-invalid @enderror" id="house_number" name="house_number" value="{{ old('house_number') }}" required>
                            @error('house_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="address_complement" class="form-label">Complemento</label>
                            <input type="text" class="form-control @error('address_complement') is-invalid @enderror" id="address_complement" name="address_complement" value="{{ old('address_complement') }}">
                            @error('address_complement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="city" class="form-label">Cidade</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label for="state" class="form-label">UF</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" maxlength="2" required>
                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label for="neighborhood" class="form-label">Bairros (Caratinga) *</label>
                            <select class="form-select @error('neighborhood') is-invalid @enderror @error('neighborhood.*') is-invalid @enderror" id="neighborhood" name="neighborhood[]" multiple size="6" required>
                                @foreach($neighborhoods as $neighborhood)
                                    <option value="{{ $neighborhood }}" {{ in_array($neighborhood, old('neighborhood', []), true) ? 'selected' : '' }}>{{ $neighborhood }}</option>
                                @endforeach
                            </select>
                            @error('neighborhood')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @error('neighborhood.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                        <a href="{{ route('people.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Cadastro</button>
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
function maskCPF(value){ const d=onlyDigits(value).slice(0,11); return d.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2'); }
function maskPhone(value){ const d=onlyDigits(value).slice(0,11); return d.length<=10 ? d.replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{4})(\d)/,'$1-$2') : d.replace(/(\d{2})(\d)/,'($1) $2').replace(/(\d{5})(\d)/,'$1-$2'); }
function maskZip(value){ const d=onlyDigits(value).slice(0,8); return d.replace(/(\d{5})(\d)/, '$1-$2'); }

const cpfInput=document.getElementById('cpf');
const phoneInput=document.getElementById('phone');
const zipInput=document.getElementById('zip_code');

cpfInput.addEventListener('input', function(){ this.value=maskCPF(this.value); });
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
    if(data.bairro){
      const neighborhoodSelect=document.getElementById('neighborhood');
      const options=[...neighborhoodSelect.options];
      const matched=options.find(o => o.value.toLowerCase()===data.bairro.toLowerCase());
      if(matched){ matched.selected=true; }
    }
  } catch (e) {
    console.error(e);
  }
});
</script>
@endsection
