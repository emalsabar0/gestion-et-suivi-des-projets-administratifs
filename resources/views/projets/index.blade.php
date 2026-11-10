@extends('layouts.app')

@section('title', 'Gestion des projets')
@section('page_title', 'Gestion des projets')

@section('content')
@php
    $nbEnRetard = $projets->filter(function($p) { return $p->est_en_retard; })->count();
    $nbExecution = $projets->where('statut', 'Exécution')->count();
@endphp

<!-- Page Header Action Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">Gestion des projets</h2>
        <p class="text-muted mb-0 small">
            Supervisez le cycle de vie, les échéances, le taux d'avancement et les retards sur vos projets.
        </p>
    </div>

    @if(auth()->user()->role && in_array(auth()->user()->role->nomRole, ['Administrateur', 'Responsable de projet']))
        <a href="{{ route('projets.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            <span>Nouveau projet</span>
        </a>
    @endif
</div>

<!-- Alert Cards Summary / Retards & Avancement -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-4">
        <div class="p-3 bg-white rounded-3 border shadow-sm d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Total Projets</span>
                <h4 class="fw-bold mb-0 text-dark">{{ $projets->count() }}</h4>
            </div>
            <div class="stat-icon-wrapper stat-icon-primary" style="width: 42px; height: 42px; font-size: 20px;">
                <i class="bi bi-folder-fill"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4">
        <div class="p-3 bg-white rounded-3 border shadow-sm d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">En Exécution</span>
                <h4 class="fw-bold mb-0 text-success">{{ $nbExecution }}</h4>
            </div>
            <div class="stat-icon-wrapper stat-icon-success" style="width: 42px; height: 42px; font-size: 20px;">
                <i class="bi bi-play-circle-fill"></i>
            </div>
        </div>
    </div>

    <!-- Alerte Projets en Retard -->
    <div class="col-sm-12 col-md-4">
        <div class="p-3 bg-white rounded-3 border shadow-sm d-flex align-items-center justify-content-between" style="border-left: 4px solid {{ $nbEnRetard > 0 ? '#ef4444' : '#10b981' }} !important;">
            <div>
                <span class="text-muted small text-uppercase fw-semibold" style="font-size: 11px;">Projets en retard</span>
                <h4 class="fw-bold mb-0 {{ $nbEnRetard > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $nbEnRetard }}
                </h4>
            </div>
            <div class="stat-icon-wrapper {{ $nbEnRetard > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}" style="width: 42px; height: 42px; font-size: 20px;">
                <i class="bi {{ $nbEnRetard > 0 ? 'bi-exclamation-triangle-fill' : 'bi-check2-circle' }}"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-custom">
    <div class="card-custom-header flex-column flex-sm-row gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-kanban text-primary fs-5"></i>
            <h6 class="mb-0 fw-bold">Liste des projets</h6>
            <span class="badge bg-light text-secondary border px-2 py-1 ms-1 small">
                {{ $projets->count() }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-sm-auto" style="max-width: 320px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       id="projectSearchInput"
                       class="form-control border-start-0 ps-0"
                       placeholder="Rechercher par nom, statut...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom" id="projectsTable">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nom du projet</th>
                    <th>Période & Délais</th>
                    <th>Statut</th>
                    <th style="min-width: 170px;">Taux d'avancement</th>
                    <th>Responsable</th>
                    <th class="text-end" style="min-width: 190px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projets as $projet)
                    <tr class="project-row" data-search="{{ strtolower($projet->nomProjet . ' ' . $projet->description . ' ' . $projet->statut . ' ' . ($projet->utilisateur->nom ?? '')) }}">
                        <td>
                            <span class="badge bg-light text-secondary border fw-mono">
                                #{{ $projet->idProjet }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark mb-0">
                                <a href="{{ route('projets.taches', $projet->idProjet) }}" class="text-decoration-none text-dark hover-primary" title="Cliquer pour voir les tâches">
                                    {{ $projet->nomProjet }}
                                </a>
                            </div>
                            <div class="text-muted small text-truncate" style="max-width: 240px;" title="{{ $projet->description }}">
                                {{ $projet->description ?: 'Aucune description fournie.' }}
                            </div>
                        </td>

                        <td>
                            <div class="small text-muted d-flex flex-column gap-1">
                                <span>
                                    <i class="bi bi-calendar-check text-primary me-1"></i>
                                    {{ \Carbon\Carbon::parse($projet->dateDebut)->format('d/m/Y') }} &rarr;
                                    {{ \Carbon\Carbon::parse($projet->dateFin)->format('d/m/Y') }}
                                </span>
                                @if($projet->est_en_retard)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle align-self-start fw-semibold" style="font-size: 10.5px;">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i>En retard ({{ $projet->jours_retard }}j)
                                    </span>
                                @endif
                            </div>
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

                        <!-- Taux d'avancement calculé -->
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1 small">
                                <span class="fw-bold text-dark">{{ $projet->taux_avancement }}%</span>
                                <span class="text-muted" style="font-size: 11px;">
                                    {{ $projet->nb_taches_terminees }}/{{ $projet->nb_taches_total }} tâche(s)
                                </span>
                            </div>
                            <div class="progress" style="height: 7px; background-color: #e2e8f0; border-radius: 4px;">
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
                                    {{ strtoupper(substr($projet->utilisateur->nom ?? 'U', 0, 1)) }}
                                </div>
                                <span class="small fw-medium">
                                    {{ $projet->utilisateur->nom ?? 'Non défini' }}
                                </span>
                            </div>
                        </td>

                        <td class="text-end">
                            <div class="d-flex flex-column gap-1.5 align-items-end">
                                <div class="d-flex align-items-center gap-1.5">
                                    <!-- Bouton Gérer les Tâches du projet -->
                                    <a href="{{ route('projets.taches', $projet->idProjet) }}"
                                       class="btn btn-outline-primary btn-sm py-1 px-2"
                                       title="Afficher et gérer les tâches de ce projet">
                                        <i class="bi bi-list-task"></i>
                                        <span>Tâches</span>
                                        <span class="badge bg-primary text-white rounded-pill ms-1" style="font-size: 10px;">
                                            {{ $projet->nb_taches_total }}
                                        </span>
                                    </a>

                                    <!-- Bouton Modifier -->
                                    @if(auth()->user()->role && in_array(auth()->user()->role->nomRole, ['Administrateur', 'Responsable de projet']))
                                        <a href="{{ route('projets.edit', $projet->idProjet) }}"
                                           class="btn btn-warning btn-sm py-1 px-2"
                                           title="Modifier les informations du projet">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                </div>

                                <!-- Formulaire Transition État (Responsable de projet uniquement) -->
                                @if(auth()->user()->role && auth()->user()->role->nomRole === 'Responsable de projet')
                                    @if($projet->statut !== 'Clôture')
                                        <form action="{{ route('projets.statut', $projet->idProjet) }}" method="POST" class="d-flex align-items-center gap-1 mt-1">
                                            @csrf
                                            @method('PUT')

                                            <select name="statut" class="form-select form-select-sm py-0.5 px-1.5" style="font-size: 11px; width: auto;" required>
                                                @if($projet->statut === 'Création')
                                                    <option value="Planification">&rarr; Planification</option>
                                                @elseif($projet->statut === 'Planification')
                                                    <option value="Exécution">&rarr; Exécution</option>
                                                @elseif($projet->statut === 'Exécution')
                                                    <option value="Clôture">&rarr; Clôture</option>
                                                @endif
                                            </select>

                                            <button type="submit" class="btn btn-success btn-sm py-0.5 px-1.5" style="font-size: 11px;" title="Passer au statut sélectionné">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyTableRow">
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Aucun projet enregistré</h6>
                                <p class="small text-muted mb-3">La liste des projets est actuellement vide.</p>
                                @if(auth()->user()->role && in_array(auth()->user()->role->nomRole, ['Administrateur', 'Responsable de projet']))
                                    <a href="{{ route('projets.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-lg"></i> Créer un nouveau projet
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-custom-header border-top border-bottom-0 py-3 bg-light">
        <span class="small text-muted">
            Affichage de <strong>{{ $projets->count() }}</strong> projet(s) au total
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filtrage instantané du tableau des projets
    document.getElementById('projectSearchInput')?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.project-row');

        rows.forEach(row => {
            const dataText = row.getAttribute('data-search') || '';
            row.style.display = dataText.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush