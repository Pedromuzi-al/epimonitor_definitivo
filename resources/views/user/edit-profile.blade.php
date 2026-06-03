@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Editar Meu Perfil</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Erro na validacao!</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('user.update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Nome Completo
                            </label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Digite seu nome completo"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="seu.email@exemplo.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_type" class="form-label">
                                <i class="fas fa-user-tag"></i> Tipo de Usuario
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="user_type"
                                value="{{ $user->user_type === 'doctor' ? 'Medico' : 'Pessoa Normal' }}"
                                disabled
                            >
                            <small class="text-muted">O tipo de usuario nao pode ser alterado.</small>
                        </div>

                        <div class="mb-4">
                            <label for="profile_photo" class="form-label">
                                <i class="fas fa-image"></i> Foto de Perfil
                            </label>

                            @if(!empty($user->profile_photo_path))
                                <div class="mb-2">
                                    <img src="{{ route('user.profile-photo', $user) }}" alt="Foto atual" style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 2px solid #dee2e6;">
                                </div>
                            @endif

                            <input
                                type="file"
                                class="form-control @error('profile_photo') is-invalid @enderror"
                                id="profile_photo"
                                name="profile_photo"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >
                            <small class="text-muted d-block mt-2">Formatos aceitos: JPG, PNG ou WEBP (maximo 5MB).</small>
                            @error('profile_photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            @if(!empty($user->profile_photo_path))
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_profile_photo" name="remove_profile_photo">
                                    <label class="form-check-label" for="remove_profile_photo">
                                        Remover foto atual
                                    </label>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Nova Senha (opcional)
                            </label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Deixe em branco para manter a senha atual"
                            >
                            <small class="text-muted d-block mt-2">Minimo de 6 caracteres. Deixe em branco se nao deseja alterar.</small>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> Confirmar Nova Senha
                            </label>
                            <input
                                type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Repita a nova senha"
                            >
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-shield-alt"></i> <strong>Seguranca:</strong> Use uma senha forte com letras, numeros e caracteres especiais.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alteracoes
                            </button>
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-lightbulb"></i> Dicas de Seguranca</h6>
                    <ul class="mb-0 small">
                        <li>Nao compartilhe sua senha com ninguem</li>
                        <li>Use uma senha unica para esta conta</li>
                        <li>Altere sua senha a cada 3 meses</li>
                        <li>Se suspeitar de atividade incomum, altere sua senha imediatamente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

