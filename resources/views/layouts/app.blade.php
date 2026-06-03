<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EpiMonitor')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #16a085;
        }

        body {
            background-color: #ecf0f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.25s ease, transform 0.25s ease, opacity 0.25s ease;
        }

        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-1px);
            opacity: 1;
        }

        .nav-link.active {
            color: #fff !important;
            border-bottom: 2px solid #fff;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 80px);
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.14);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(41, 128, 185, 0.3);
        }

        .btn-success {
            background-color: var(--success-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-success:hover {
            background-color: #229954;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(39, 174, 96, 0.3);
        }

        .btn-danger {
            background-color: var(--danger-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(231, 76, 60, 0.3);
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover,
        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }

        /* Forms */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Alert Badges */
        .badge-critical {
            background-color: var(--danger-color);
        }

        .badge-high {
            background-color: var(--warning-color);
        }

        .badge-moderate {
            background-color: #f1c40f;
            color: #333;
        }

        .badge-low {
            background-color: var(--success-color);
        }

        /* Tables */
        .table {
            background-color: white;
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Stats */
        .stat-card {
            border-left: 4px solid var(--secondary-color);
            padding: 1.5rem;
            background: white;
            border-radius: 5px;
            text-align: center;
        }

        .stat-card h3 {
            color: var(--secondary-color);
            margin: 0;
        }

        .stat-card p {
            color: #7f8c8d;
            margin: 0.5rem 0 0 0;
        }

        /* Footer */
        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 3rem;
        }

        /* Notification Bell Badge Animation */
        @keyframes pulse-badge {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        #notificationBell {
            position: relative;
            border: none;
            background: none !important;
            padding: 0.5rem 0.75rem !important;
            transition: opacity 0.25s ease;
            color: rgba(255,255,255,0.8) !important;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 50px;
        }

        #notificationBell:hover {
            opacity: 0.8;
            color: #fff !important;
        }

        #notificationBell .badge {
            animation: pulse-badge 2s infinite;
            cursor: pointer;
            min-width: 22px;
            min-height: 22px;
            padding: 2px 6px !important;
            font-size: 0.7rem;
        }

        .dropdown-menu {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 10px;
            border: none;
        }

        .dropdown-item {
            border-radius: 5px;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Alerts */
        .alert {
            border-radius: 5px;
        }

        /* Modal */
        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }

        /* Badge */
        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Progress Bar */
        .progress {
            height: 25px;
        }

        .progress-bar {
            background-color: var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
    
    @yield('css')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-virus-covid"></i> EpiMonitor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-heartbeat"></i> Monitoramento de Sintomas
                        </a>
                    </li>
                    
                    {{-- Apenas para medicos --}}
                    @if(auth()->check() && auth()->user()->user_type === 'doctor')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('people.*') ? 'active' : '' }}" href="{{ route('people.index') }}">
                                <i class="fas fa-users"></i> Pessoas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('diagnoses.*') ? 'active' : '' }}" href="{{ route('diagnoses.index') }}">
                                <i class="fas fa-stethoscope"></i> Diagnosticos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}" href="{{ route('statistics.dashboard') }}">
                                <i class="fas fa-chart-bar"></i> Estatisticas
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        @include('components.notification-bell')
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            @if(!empty(Auth::user()->profile_photo_path))
                                <img src="{{ route('user.profile-photo', Auth::user()) }}" alt="Foto de perfil" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover; margin-right: 6px; border: 1px solid rgba(255,255,255,.6);">
                            @else
                                <i class="fas fa-user-circle"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="fas fa-user"></i> Meu Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('auth.logout') }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="cursor: pointer; border: none; background: none;">
                                        <i class="fas fa-sign-out-alt"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.register') }}">
                            <i class="fas fa-user-plus"></i> Registrar
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Erros encontrados:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Notifications Modal -->
    @include('components.notification-modal')

    <!-- Resolve Diagnosis Modal -->
    @include('components.resolve-diagnosis-modal')

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} EpiMonitor - Sistema de Monitoramento Epidemiologico | Desenvolvido com <i class="fas fa-heart"></i> para a saude publica</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    @yield('js')
</body>
</html>

