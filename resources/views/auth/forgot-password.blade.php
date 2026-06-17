<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a senha - EpiMonitor</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .password-card {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .password-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 35px 25px;
            text-align: center;
        }

        .password-header i {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .password-header h1 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: bold;
        }

        .password-body {
            padding: 32px 30px;
            color: #555;
        }

        .password-body p {
            line-height: 1.6;
            margin-bottom: 22px;
            text-align: center;
        }

        .form-label {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }

        .btn-submit,
        .btn-back {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            background-color: var(--secondary-color);
            color: white;
            font-weight: 600;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-submit {
            margin-top: 18px;
        }

        .btn-submit:hover,
        .btn-back:hover {
            background-color: #2980b9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.35);
        }

        .btn-back {
            background-color: transparent;
            color: var(--secondary-color);
            margin-top: 14px;
            box-shadow: none;
        }

        .btn-back:hover {
            background-color: #eef7fd;
            color: #2980b9;
            box-shadow: none;
            transform: none;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 18px;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <div class="password-card">
        <div class="password-header">
            <i class="fas fa-key"></i>
            <h1>Esqueceu a senha?</h1>
        </div>

        <div class="password-body">
            <p>Informe o e-mail cadastrado. Enviaremos um link para você criar uma nova senha.</p>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('auth.password.email') }}" method="POST">
                @csrf

                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> E-mail
                </label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="seu.email@exemplo.com"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Enviar link de redefinição
                </button>
            </form>

            <a href="{{ route('auth.login') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Voltar para o login
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

