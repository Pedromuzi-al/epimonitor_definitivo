@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-circle"></i> Meu Perfil</h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <div class="profile-avatar mb-3">
                                @if(!empty($user->profile_photo_path))
                                    <img src="{{ route('user.profile-photo', $user) }}" alt="Foto de perfil" style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 12px rgba(0,0,0,.2);">
                                @else
                                    <div style="width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="font-size: 60px; color: white;"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="mt-3">{{ $user->name }}</h5>
                            <p class="text-muted mb-2">
                                @if ($user->user_type === 'doctor')
                                    <span class="badge bg-primary"><i class="fas fa-stethoscope"></i> Medico</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-user-check"></i> Pessoa Normal</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-9">
                            <div class="profile-info">
                                <div class="profile-item mb-3">
                                    <label class="text-muted small">Email</label>
                                    <p class="mb-0"><i class="fas fa-envelope text-primary"></i> {{ $user->email }}</p>
                                </div>

                                <div class="profile-item mb-3">
                                    <label class="text-muted small">Tipo de Usuario</label>
                                    <p class="mb-0">
                                        @if ($user->user_type === 'doctor')
                                            <i class="fas fa-stethoscope text-primary"></i> Medico - Acesso a recursos avancados
                                        @else
                                            <i class="fas fa-user-check text-success"></i> Pessoa Normal - Registro e acompanhamento de sintomas
                                        @endif
                                    </p>
                                </div>

                                <div class="profile-item mb-3">
                                    <label class="text-muted small">Membro desde</label>
                                    <p class="mb-0"><i class="fas fa-calendar text-primary"></i> {{ $user->created_at->format('d/m/Y') }}</p>
                                </div>

                                <div class="profile-item">
                                    <label class="text-muted small">Ultima atualizacao</label>
                                    <p class="mb-0"><i class="fas fa-sync text-primary"></i> {{ $user->updated_at->format('d/m/Y \a\s H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('user.edit-profile') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </a>
                        <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt"></i> Seguranca</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Suas informacoes estao protegidas com criptografia de primeira classe. Voce pode alterar sua senha a qualquer momento na pagina de edicao do perfil.</p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Dica:</strong> Altere sua senha periodicamente para manter sua conta segura.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-item {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .profile-item label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-item p {
            color: #333;
            font-size: 0.95rem;
        }
    </style>
@endsection
