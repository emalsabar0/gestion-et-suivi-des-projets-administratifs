<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'GestAdmin') - Gestion & Suivi des Projets</title>

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
            --primary: #1d4ed8;
            --primary-accent: #2563eb;
            --primary-hover: #1e40af;
            --primary-light: #eff6ff;
            --secondary: #475569;
            --sidebar-bg: #070d1e;
            --sidebar-card: #0e1c40;
            --sidebar-hover: #172a5a;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --body-bg: #f8fafc;
            --card-border: #e2e8f0;
            --text-dark: #070d1e;
            --text-muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0284c7;
            --radius-lg: 16px;
            --radius-md: 10px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --transition-smooth: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--body-bg);
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Layout structure */
        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling - Bleu Nuit Profond */
        .app-sidebar {
            width: 270px;
            background: linear-gradient(180deg, #070d1e 0%, #0d1b3e 100%);
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(59, 130, 246, 0.15);
            box-shadow: 4px 0 24px rgba(7, 13, 30, 0.35);
        }

        .sidebar-brand {
            padding: 24px 22px 20px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 22px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }

        .sidebar-brand-info h4 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-brand-info span {
            font-size: 11px;
            font-weight: 600;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: #64748b;
            padding: 12px 12px 8px 12px;
            margin-top: 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            transition: var(--transition-smooth);
            position: relative;
        }

        .sidebar-link i {
            font-size: 18px;
            color: #94a3b8;
            transition: var(--transition-smooth);
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: rgba(37, 99, 235, 0.15);
            transform: translateX(3px);
        }

        .sidebar-link:hover i {
            color: #60a5fa;
        }

        .sidebar-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.3) 0%, rgba(37, 99, 235, 0.08) 100%);
            border-left: 3px solid #3b82f6;
            font-weight: 600;
        }

        .sidebar-link.active i {
            color: #60a5fa;
        }

        .sidebar-link .badge-soon {
            font-size: 10px;
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            padding: 2px 7px;
            border-radius: 20px;
            margin-left: auto;
        }

        /* Sidebar Footer / User Chip */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(7, 13, 30, 0.7);
        }

        .user-profile-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            background: var(--sidebar-card);
            border-radius: var(--radius-md);
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .user-info-text {
            flex-grow: 1;
            overflow: hidden;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            font-size: 11px;
            color: #93c5fd;
            display: block;
            margin-top: 1px;
            font-weight: 500;
        }

        .btn-sidebar-logout {
            background: none;
            border: none;
            color: #f87171;
            padding: 6px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: var(--transition-smooth);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .btn-sidebar-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        /* Main Content wrapper */
        .app-main {
            flex-grow: 1;
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navbar */
        .app-topbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-menu-toggle {
            display: none;
            background: none;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-sm);
            padding: 6px 10px;
            font-size: 18px;
            color: var(--secondary);
            cursor: pointer;
        }

        .page-header-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-date {
            font-size: 13px;
            color: #1d4ed8;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #bfdbfe;
        }

        /* Content Container */
        .app-content {
            padding: 32px;
            flex-grow: 1;
        }

        /* Modern Card Component */
        .card-custom {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition-smooth);
            overflow: hidden;
        }

        .card-custom:hover {
            box-shadow: var(--shadow-md);
        }

        .card-custom-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .card-custom-body {
            padding: 24px;
        }

        /* Dashboard Metric Cards */
        .stat-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-border);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
            transition: var(--transition-smooth);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: #93c5fd;
        }

        .stat-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon-primary {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .stat-icon-success {
            background: #ecfdf5;
            color: #059669;
        }

        .stat-icon-warning {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-icon-purple {
            background: #f5f3ff;
            color: #7c3aed;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 12px 0 4px 0;
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .stat-subtext {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
            margin-bottom: 0;
        }

        /* Buttons Bleu Nuit */
        .btn {
            font-weight: 600;
            font-size: 13px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            transition: var(--transition-smooth);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            border-color: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.45);
            color: #ffffff;
        }

        .btn-outline-primary {
            color: #1d4ed8;
            border-color: #bfdbfe;
            background-color: #eff6ff;
        }

        .btn-outline-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .btn-warning {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: #ffffff;
        }

        .btn-warning:hover {
            background-color: #d97706;
            border-color: #d97706;
            color: #ffffff;
        }

        .btn-danger {
            background-color: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #059669;
            color: #ffffff;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-color: #047857;
        }

        /* Tables */
        .table-custom {
            margin-bottom: 0;
            width: 100%;
        }

        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 18px;
            border-bottom: 1px solid var(--card-border);
            white-space: nowrap;
        }

        .table-custom td {
            padding: 14px 18px;
            vertical-align: middle;
            color: #334155;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-custom tbody tr {
            transition: var(--transition-smooth);
        }

        .table-custom tbody tr:hover {
            background-color: #eff6ff;
        }

        /* Status Badges */
        .badge-status {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-status-creation {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .badge-status-creation::before { background: #3b82f6; }

        .badge-status-planification {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
        }
        .badge-status-planification::before { background: #f59e0b; }

        .badge-status-execution {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-status-execution::before { background: #10b981; }

        .badge-status-cloture {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .badge-status-cloture::before { background: #64748b; }

        .badge-role-admin {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .badge-role-resp {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .badge-role-collab {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
        }

        /* Form elements */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            font-size: 13.5px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid #cbd5e1;
            color: #070d1e;
            transition: var(--transition-smooth);
        }

        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            outline: none;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Alerts */
        .alert {
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            padding: 14px 18px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* Mobile Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(7, 13, 30, 0.6);
            backdrop-filter: blur(2px);
            z-index: 1030;
        }

        .sidebar-backdrop.active {
            display: block;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .app-main {
                margin-left: 0;
            }

            .btn-menu-toggle {
                display: block;
            }

            .app-topbar {
                padding: 0 16px;
            }

            .app-content {
                padding: 20px 16px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="app-layout">
    <!-- Mobile Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Navigation Sidebar -->
    <aside class="app-sidebar" id="appSidebar">
        <div>
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-folder-check"></i>
                </div>
                <div class="sidebar-brand-info">
                    <h4>GestAdmin</h4>
                    <span>Suivi des Projets</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="sidebar-menu">
                <div class="sidebar-section-title">Navigation principale</div>

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Tableau de bord</span>
                </a>

                <a href="{{ route('projets.index') }}"
                   class="sidebar-link {{ request()->routeIs('projets.*') ? 'active' : '' }}">
                    <i class="bi bi-kanban-fill"></i>
                    <span>Projets</span>
                </a>

                @if(auth()->check() && optional(auth()->user())->role && optional(auth()->user()->role)->nomRole === 'Administrateur')
                    <div class="sidebar-section-title">Administration</div>
                    <a href="{{ route('utilisateurs.index') }}"
                       class="sidebar-link {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Utilisateurs</span>
                    </a>
                @endif

                <div class="sidebar-section-title">Opérations</div>
                <a href="{{ route('taches.index') }}"
                   class="sidebar-link {{ request()->routeIs('taches.*') ? 'active' : '' }}">
                    <i class="bi bi-check2-square"></i>
                    <span>Tâches</span>
                </a>
                <a href="{{ route('historiques.index') }}"
                   class="sidebar-link {{ request()->routeIs('historiques.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Historique</span>
                </a>
            </div>
        </div>

        <!-- Sidebar User Footer -->
        <div class="sidebar-footer">
            <div class="user-profile-card">
                <div class="user-avatar-circle">
                    {{ strtoupper(substr(optional(auth()->user())->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(optional(auth()->user())->nom ?? 'A', 0, 1)) }}
                </div>
                <div class="user-info-text">
                    <p class="user-name" title="{{ optional(auth()->user())->prenom }} {{ optional(auth()->user())->nom }}">
                        {{ optional(auth()->user())->prenom }} {{ optional(auth()->user())->nom }}
                    </p>
                    <span class="user-role-badge">
                        {{ optional(optional(auth()->user())->role)->nomRole ?? 'Utilisateur' }}
                    </span>
                </div>

                <!-- Formulaire de déconnexion -->
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" title="Déconnexion">
                    @csrf
                    <button type="submit" class="btn-sidebar-logout" aria-label="Se déconnecter">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="app-main">
        <!-- Top Navbar -->
        <header class="app-topbar">
            <div class="topbar-left">
                <button class="btn-menu-toggle" type="button" onclick="toggleSidebar()" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-header-title">@yield('page_title', 'Tableau de bord')</h1>
            </div>

            <div class="topbar-right">
                <div class="topbar-date d-none d-md-flex">
                    <i class="bi bi-calendar3"></i>
                    <span id="currentDateDisplay">Aujourd'hui</span>
                </div>

                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-sm-inline">{{ optional(auth()->user())->prenom }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" style="border-radius: var(--radius-md);">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">{{ optional(auth()->user())->prenom }} {{ optional(auth()->user())->nom }}</div>
                            <div class="small text-muted">{{ optional(auth()->user())->email }}</div>
                            <div class="badge bg-primary-subtle text-primary border mt-1">{{ optional(optional(auth()->user())->role)->nomRole ?? 'Utilisateur' }}</div>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 mt-1">
                                    <i class="bi bi-box-arrow-right"></i> Se déconnecter
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="app-content">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Responsive sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('appSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('active');
    }

    // Dynamic current date in French
    document.addEventListener('DOMContentLoaded', function () {
        const dateElement = document.getElementById('currentDateDisplay');
        if (dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date().toLocaleDateString('fr-FR', options);
            dateElement.textContent = today.charAt(0).toUpperCase() + today.slice(1);
        }
    });
</script>
@stack('scripts')
</body>
</html>