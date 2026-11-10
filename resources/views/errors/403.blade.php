<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accès non autorisé | GestAdmin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            margin: 0;
        }
        .error-card {
            background: #ffffff;
            color: #0f172a;
            border-radius: 16px;
            padding: 48px 36px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }
        .error-code {
            font-size: 72px;
            font-weight: 800;
            color: #ef4444;
            line-height: 1;
            margin-bottom: 12px;
        }
        .error-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .error-desc {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="mb-3">
            <span class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 70px; height: 70px; font-size: 32px;">
                <i class="bi bi-shield-x"></i>
            </span>
        </div>
        <div class="error-code">403</div>
        <h1 class="error-title">Accès non autorisé</h1>
        <p class="error-desc">
            Vous ne disposez pas des privilèges nécessaires pour accéder à cette ressource. Veuillez contacter votre administrateur si vous pensez qu'il s'agit d'une erreur.
        </p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Retour au tableau de bord</span>
        </a>
    </div>
</body>
</html>
