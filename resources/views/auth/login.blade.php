<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GestAdmin | Suivi des Projets Administratifs</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bleu-nuit-bg: #070d1e;
            --bleu-nuit-card: #0e1c40;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --surface: #ffffff;
            --body-bg: #f8fafc;
            --text-dark: #070d1e;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --radius-lg: 18px;
            --radius-md: 10px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #050a17 0%, #0c1838 50%, #060d1f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            margin: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient glowing circles - Bleu Nuit */
        body::before {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.28) 0%, rgba(37, 99, 235, 0) 70%);
            top: -100px;
            right: -100px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30, 58, 138, 0.35) 0%, rgba(30, 58, 138, 0) 70%);
            bottom: -150px;
            left: -150px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 42px 36px;
            box-shadow: 0 25px 50px -12px rgba(5, 10, 23, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-top: 4px solid #2563eb;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 16px;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.45);
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .brand-badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 8px;
            border: 1px solid #bfdbfe;
        }

        .brand-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
            margin: 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 7px;
            display: block;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: #3b82f6;
            font-size: 16px;
            pointer-events: none;
            transition: color 0.2s;
            z-index: 5;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-md);
            background-color: #f8fafc;
            color: #070d1e;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control-custom:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
            outline: none;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: color 0.2s;
            z-index: 5;
        }

        .password-toggle-btn:hover {
            color: #2563eb;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 14.5px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.5);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .alert-custom {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: var(--radius-md);
            padding: 12px 14px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .footer-note {
            text-align: center;
            color: #93c5fd;
            font-size: 12px;
            margin-top: 24px;
            opacity: 0.85;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Brand Header -->
        <div class="brand-header">
            <div class="brand-logo-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span class="brand-badge">GestAdmin &bull; Plateforme Administrative</span>
            </div>
            <h1 class="brand-title">Connexion</h1>
            <p class="brand-subtitle">
                Espace sécurisé de gestion et suivi des projets administratifs
            </p>
        </div>

        <!-- Alert messages -->
        @if (isset($errors) && $errors->any())
            <div class="alert-custom">
                <i class="bi bi-exclamation-circle-fill fs-6 mt-1 flex-shrink-0"></i>
                <div>
                    {{ $errors->first() }}
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Email Field -->
            <div class="mb-3">
                <label class="form-label" for="email">Adresse e-mail</label>
                <div class="input-group-custom">
                    <i class="bi bi-envelope-fill input-icon-left"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control-custom"
                        placeholder="exemple@organisation.mr"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-2">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="input-group-custom">
                    <i class="bi bi-lock-fill input-icon-left"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control-custom"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" aria-label="Afficher le mot de passe">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit">
                <span>Accéder à mon espace</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <a href="#" class="forgot-link">
            Mot de passe oublié ?
        </a>
    </div>

    <div class="footer-note">
        &copy; GestAdmin &bull; Plateforme administrative interne
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>