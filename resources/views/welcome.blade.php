<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo - EpiMonitor</title>
    
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
            --info-color: #16a085;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .welcome-container {
            width: 100%;
            max-width: 1000px;
        }

        .welcome-header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .welcome-header h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .welcome-header .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .welcome-header p {
            font-size: 1.3rem;
            opacity: 0.95;
            margin: 0;
        }

        .welcome-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .welcome-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .welcome-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.3);
        }

        .welcome-card-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: inline-block;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .welcome-card:nth-child(1) .welcome-card-icon {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }

        .welcome-card:nth-child(2) .welcome-card-icon {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .welcome-card h2 {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.8rem;
            text-align: center;
        }

        .welcome-card p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
            text-align: center;
        }

        .welcome-card ul {
            list-style: none;
            margin-bottom: 30px;
            padding: 0;
        }

        .welcome-card ul li {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            font-size: 0.95rem;
        }

        .welcome-card ul li:last-child {
            border-bottom: none;
        }

        .welcome-card ul li i {
            color: var(--success-color);
            margin-right: 10px;
            font-weight: bold;
        }

        .welcome-card:nth-child(2) ul li i {
            color: var(--secondary-color);
        }

        .welcome-card .btn {
            width: 100%;
            padding: 13px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            transition: all 0.3s ease;
            font-size: 1.05rem;
        }

        .forgot-password-link {
            display: block;
            margin-top: 12px;
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
        }

        .forgot-password-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .welcome-card:nth-child(1) .btn {
            background-color: var(--secondary-color);
            color: white;
        }

        .welcome-card:nth-child(1) .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        }

        .welcome-card:nth-child(2) .btn {
            background-color: var(--success-color);
            color: white;
        }

        .welcome-card:nth-child(2) .btn:hover {
            background-color: #229954;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        }

        .welcome-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-footer p {
            margin: 0;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .welcome-content {
                grid-template-columns: 1fr;
            }

            .welcome-header h1 {
                font-size: 2.5rem;
            }

            .welcome-header .icon {
                font-size: 3rem;
            }

            .welcome-header p {
                font-size: 1.1rem;
            }

            .welcome-card {
                padding: 30px 20px;
            }

            .welcome-card h2 {
                font-size: 1.5rem;
            }
        }

        .feature-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #555;
            font-size: 0.9rem;
        }

        .feature-row i {
            width: 25px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-header">
            <div class="icon"><i class="fas fa-heartbeat"></i></div>
            <h1>EpiMonitor</h1>
            <p>Sistema Inteligente de Monitoramento de Sintomas</p>
        </div>

        <div class="welcome-content">
            <!-- Card: Usuário Existente -->
            <div class="welcome-card">
                <div>
                    <div class="welcome-card-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h2>Bem-vindo de volta!</h2>
                    <p>Faça login na sua conta para acessar o sistema</p>
                    <div class="welcome-card-features">
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Acesso ao histórico</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Consultar diagnósticos</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Gestão de perfil</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Notificações em tempo real</span>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('auth.login') }}" class="btn">
                        <i class="fas fa-arrow-right"></i> Fazer Login
                    </a>
                    <a href="{{ route('auth.password.request') }}" class="forgot-password-link">
                        Esqueceu a senha?
                    </a>
                </div>
            </div>

            <!-- Card: Novo Usuário -->
            <div class="welcome-card">
                <div>
                    <div class="welcome-card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2>Novo por aqui?</h2>
                    <p>Crie sua conta agora e comece a monitorar sua saúde</p>
                    <div class="welcome-card-features">
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Registro rápido e seguro</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Diagnósticos instantâneos</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Acompanhe seus sintomas</span>
                        </div>
                        <div class="feature-row">
                            <i class="fas fa-check"></i>
                            <span>Alertas personalizados</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('auth.register') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Criar Conta
                </a>
            </div>
        </div>

        <div class="welcome-footer">
            <p><i class="fas fa-shield-alt"></i> Seus dados estão seguros e criptografados • EpiMonitor 2026</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
