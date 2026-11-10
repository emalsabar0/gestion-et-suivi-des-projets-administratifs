@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page_title', 'Tableau de bord')

@section('content')
@php
    $allProjets = \App\Models\Projet::with(['utilisateur', 'taches'])->get();
    $totalProjets = $allProjets->count();
    $totalTaches = \App\Models\Tache::count();
    $projetsExecution = $allProjets->where('statut', 'Exécution')->count();
    $projetsClotures = $allProjets->where('statut', 'Clôture')->count();
    $projetsEnRetard = $allProjets->filter(function($p) { return $p->est_en_retard; })->count();
    $derniersProjets = $allProjets->sortByDesc('created_at')->take(5);
@endphp

<!-- Welcome Banner - Bleu Nuit Élégant -->
<div class="card-custom mb-4 p-4" style="background: linear-gradient(135deg, #0e1c40 0%, #070d1e 100%); color: #ffffff; border: 1px solid rgba(59, 130, 246, 0.2);">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-1 text-uppercase fw-semibold border border-primary-subtle" style="letter-spacing: 0.05em; font-size: 11px;">
                    {{ optional(optional(auth()->user())->role)->nomRole ?? 'Collaborateur' }}
                </span>
                <span class="text-white-50 small">&bull; Espace Administrateur</span>
            </div>
            <h2 class="fw-bold mb-1" style="letter-spacing: -0.02em;">
                Bienvenue, {{ optional(auth()->user())->prenom }} {{ optional(auth()->user())->nom }} !
            </h2>
            <p class="text-white-50 mb-0 small" style="max-width: 650px;">
                Voici la vue d'ensemble de l'avancement de vos projets administratifs, du suivi des tâches et des alertes de retard.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            @if(auth()->user()->role && in_array(auth()->user()->role->nomRole, ['Administrateur', 'Responsable de projet']))
                <a href="{{ route('projets.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nouveau projet</span>
                </a>
                <a href="{{ route('taches.create') }}" class="btn btn-secondary text-white border-0" style="background: rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-check2-square"></i>
                    <span>Nouvelle tâche</span>
                </a>
            @endif

            @if(auth()->user()->role && auth()->user()->role->nomRole === 'Administrateur')
                <a href="{{ route('utilisateurs.create') }}" class="btn btn-secondary">
                    <i class="bi bi-person-plus"></i>
                    <span>Ajouter utilisateur</span>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Metrics Overview -->
<div class="row g-4 mb-4">
    <!-- Total Utilisateurs -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">Utilisateurs</p>
                    <div class="stat-value">{{ $totalUtilisateurs }}</div>
                    <p class="stat-subtext text-primary">
                        <i class="bi bi-check-circle-fill"></i> Comptes enregistrés
                    </p>
                </div>
                <div class="stat-icon-wrapper stat-icon-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            @if(auth()->user()->role && auth()->user()->role->nomRole === 'Administrateur')
                <div class="mt-3 pt-2 border-top">
                    <a href="{{ route('utilisateurs.index') }}" class="small text-decoration-none text-primary fw-semibold d-inline-flex align-items-center gap-1">
                        Gérer les utilisateurs <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Total Projets -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">Total Projets</p>
                    <div class="stat-value">{{ $totalProjets }}</div>
                    <p class="stat-subtext text-muted">
                        <i class="bi bi-folder-fill text-primary"></i> {{ $totalTaches }} tâche(s) au total
                    </p>
                </div>
                <div class="stat-icon-wrapper" style="background: #eff6ff; color: #1d4ed8;">
                    <i class="bi bi-kanban-fill"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top">
                <a href="{{ route('projets.index') }}" class="small text-decoration-none text-primary fw-semibold d-inline-flex align-items-center gap-1">
                    Voir tous les projets <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Projets en Retard (Alerte Visuelle) -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="{{ $projetsEnRetard > 0 ? 'border-left: 4px solid #ef4444 !important;' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">Projets en retard</p>
                    <div class="stat-value {{ $projetsEnRetard > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $projetsEnRetard }}
                    </div>
                    <p class="stat-subtext {{ $projetsEnRetard > 0 ? 'text-danger' : 'text-success' }}">
                        <i class="bi {{ $projetsEnRetard > 0 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }}"></i>
                        {{ $projetsEnRetard > 0 ? 'Attention requise' : 'Aucun retard constaté' }}
                    </p>
                </div>
                <div class="stat-icon-wrapper {{ $projetsEnRetard > 0 ? 'bg-danger-subtle text-danger' : 'stat-icon-success' }}">
                    <i class="bi {{ $projetsEnRetard > 0 ? 'bi-alarm-fill' : 'bi-calendar-check-fill' }}"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top">
                <a href="{{ route('projets.index') }}" class="small text-decoration-none {{ $projetsEnRetard > 0 ? 'text-danger' : 'text-muted' }} fw-semibold d-inline-flex align-items-center gap-1">
                    {{ $projetsEnRetard > 0 ? 'Vérifier les retards' : 'Tous les délais respectés' }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Projets Clôturés -->
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">Clôturés</p>
                    <div class="stat-value text-secondary">{{ $projetsClotures }}</div>
                    <p class="stat-subtext text-info">
                        <i class="bi bi-patch-check-fill"></i> Objectifs atteints
                    </p>
                </div>
                <div class="stat-icon-wrapper stat-icon-warning">
                    <i class="bi bi-check-all"></i>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top">
                <span class="small text-muted">{{ $projetsExecution }} actuellement en exécution</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Projects Table Card -->
<div class="card-custom">
    <div class="card-custom-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary fs-5"></i>
            <div>
                <h6 class="mb-0 fw-bold">Projets Récents & Taux d'Avancement</h6>
                <small class="text-muted">Aperçu rapide des projets et progression des tâches</small>
            </div>
        </div>
        <a href="{{ route('projets.index') }}" class="btn btn-sm btn-outline-primary">
            Consulter tout
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Projet</th>
                    <th>Période & Retard</th>
                    <th>Statut</th>
                    <th style="min-width: 150px;">Taux d'avancement</th>
                    <th>Responsable</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($derniersProjets as $projet)
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark">
                                <a href="{{ route('projets.taches', $projet->idProjet) }}" class="text-decoration-none text-dark" title="Voir les tâches">
                                    {{ $projet->nomProjet }}
                                </a>
                            </div>
                            <div class="small text-muted text-truncate" style="max-width: 250px;">
                                {{ $projet->description ?: 'Aucune description disponible' }}
                            </div>
                        </td>

                        <td>
                            <div class="small text-muted">
                                <i class="bi bi-calendar-event me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($projet->dateDebut)->format('d/m/Y') }} &rarr;
                                {{ \Carbon\Carbon::parse($projet->dateFin)->format('d/m/Y') }}
                            </div>
                            @if($projet->est_en_retard)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 10px;">
                                    <i class="bi bi-exclamation-circle-fill"></i> Retard {{ $projet->jours_retard }}j
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($projet->statut === 'Création')
                                <span class="badge-status badge-status-creation">Création</span>
                            @elseif($projet->statut === 'Planification')
                                <span class="badge-status badge-status-planification">Planification</span>
                            @elseif($projet->statut === 'Exécution')
                                <span class="badge-status badge-status-execution">Exécution</span>
                            @elseif($projet->statut === 'Clôture')
                                <span class="badge-status badge-status-cloture">Clôture</span>
                            @else
                                <span class="badge bg-secondary">{{ $projet->statut }}</span>
                            @endif
                        </td>

                        <!-- Progression & Taux d'avancement -->
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1 small">
                                <span class="fw-bold text-dark">{{ $projet->taux_avancement }}%</span>
                                <span class="text-muted" style="font-size: 11px;">
                                    {{ $projet->nb_taches_terminees }}/{{ $projet->nb_taches_total }} tâche(s)
                                </span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #e2e8f0; border-radius: 4px;">
                                <div class="progress-bar progress-bar-striped {{ $projet->taux_avancement >= 100 ? 'bg-success' : ($projet->taux_avancement >= 50 ? 'bg-primary' : 'bg-warning') }}"
                                     role="progressbar"
                                     style="width: {{ $projet->taux_avancement }}%;"
                                     aria-valuenow="{{ $projet->taux_avancement }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-circle" style="width: 28px; height: 28px; font-size: 11px;">
                                    {{ strtoupper(substr($projet->utilisateur->nom ?? 'N', 0, 1)) }}
                                </div>
                                <span class="fw-medium small">{{ $projet->utilisateur->nom ?? 'Non défini' }}</span>
                            </div>
                        </td>

                        <td class="text-end">
                            <a href="{{ route('projets.taches', $projet->idProjet) }}" class="btn btn-sm btn-primary py-1 px-2.5" title="Gérer les tâches de ce projet">
                                <i class="bi bi-list-task"></i>
                                <span>Tâches</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <p class="fw-medium mb-1">Aucun projet enregistré pour le moment.</p>
                                <p class="small text-muted mb-3">Commencez dès maintenant en créant votre premier projet administratif.</p>
                                @if(auth()->user()->role && in_array(auth()->user()->role->nomRole, ['Administrateur', 'Responsable de projet']))
                                    <a href="{{ route('projets.create') }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-plus-lg"></i> Créer un projet
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection