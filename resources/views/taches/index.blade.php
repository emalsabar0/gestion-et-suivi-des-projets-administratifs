@extends('layouts.app')

@section('title', 'Gestion des tâches')
@section('page_title', 'Gestion des tâches')

@section('content')
<!-- Header Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">Gestion des tâches</h2>
        <p class="text-muted mb-0 small">
            Suivez la répartition opérationnelle, les échéances et l'état d'avancement des tâches par projet.
        </p>
    </div>

    <a href="{{ route('taches.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill"></i>
        <span>Nouvelle tâche</span>
    </a>
</div>

<!-- Main Table Card -->
<div class="card-custom">
    <div class="card-custom-header flex-column flex-md-row gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check2-square text-primary fs-5"></i>
            <h6 class="mb-0 fw-bold">Liste des tâches</h6>
            <span class="badge bg-light text-secondary border px-2 py-1 ms-1 small">
                {{ $taches->count() }}
            </span>
        </div>

        <!-- Filtres et Recherche -->
        <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-md-auto">
            <form action="{{ route('taches.index') }}" method="GET" class="d-flex gap-2 align-items-center m-0">
                <select name="idProjet" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 200px; font-size: 12.5px;">
                    <option value="">Tous les projets</option>
                    @foreach($projets as $p)
                        <option value="{{ $p->idProjet }}" {{ request('idProjet') == $p->idProjet ? 'selected' : '' }}>
                            {{ $p->nomProjet }}
                        </option>
                    @endforeach
                </select>

                <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 150px; font-size: 12.5px;">
                    <option value="">Tous les statuts</option>
                    <option value="À faire" {{ request('statut') == 'À faire' ? 'selected' : '' }}>À faire</option>
                    <option value="En cours" {{ request('statut') == 'En cours' ? 'selected' : '' }}>En cours</option>
                    <option value="Terminée" {{ request('statut') == 'Terminée' ? 'selected' : '' }}>Terminée</option>
                </select>
            </form>

            <div class="input-group input-group-sm ms-md-auto" style="max-width: 250px;">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       id="tacheSearchInput"
                       class="form-control border-start-0 ps-0"
                       placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom" id="tachesTable">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Tâche</th>
                    <th>Projet</th>
                    <th>Assignée à</th>
                    <th>Échéance</th>
                    <th>Statut</th>
                    <th class="text-end" style="min-width: 170px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($taches as $tache)
                    <tr class="tache-row" data-search="{{ strtolower($tache->titre . ' ' . $tache->description . ' ' . ($tache->projet->nomProjet ?? '') . ' ' . ($tache->utilisateur->nom ?? '') . ' ' . $tache->statut) }}">
                        <td>
                            <span class="badge bg-light text-secondary border fw-mono">
                                #{{ $tache->idTache }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ $tache->titre }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 260px;">
                                {{ $tache->description ?: 'Aucune description' }}
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-semibold">
                                <i class="bi bi-folder2 me-1"></i>
                                {{ $tache->projet->nomProjet ?? 'Projet non lié' }}
                            </span>
                        </td>

                        <td>
                            @if($tache->utilisateur)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-circle" style="width: 28px; height: 28px; font-size: 11px;">
                                        {{ strtoupper(substr($tache->utilisateur->prenom ?? '', 0, 1)) }}{{ strtoupper(substr($tache->utilisateur->nom ?? '', 0, 1)) }}
                                    </div>
                                    <span class="small fw-medium">{{ $tache->utilisateur->prenom }} {{ $tache->utilisateur->nom }}</span>
                                </div>
                            @else
                                <span class="badge bg-light text-muted border">Non assignée</span>
                            @endif
                        </td>

                        <td>
                            @php
                                $echeance = \Carbon\Carbon::parse($tache->dateEcheance);
                                $isLate = $echeance->isPast() && $tache->statut !== 'Terminée';
                            @endphp
                            <div class="small {{ $isLate ? 'text-danger fw-bold' : 'text-muted' }}">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $echeance->format('d/m/Y') }}
                                @if($isLate)
                                    <span class="badge bg-danger ms-1" style="font-size: 10px;">En retard</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($tache->statut === 'Terminée')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i>Terminée
                                </span>
                            @elseif($tache->statut === 'En cours')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-hourglass-split me-1"></i>En cours
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                    <i class="bi bi-circle me-1"></i>À faire
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-1.5 flex-wrap">
                                <a href="{{ route('taches.edit', $tache->idTache) }}" class="btn btn-warning btn-sm py-1 px-2" title="Modifier la tâche">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <!-- Changer rapidement l'état -->
                                <form action="{{ route('taches.statut', $tache->idTache) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('PUT')
                                    <select name="statut" class="form-select form-select-sm py-0.5 px-1.5 d-inline-block" style="width: auto; font-size: 11.5px;" onchange="this.form.submit()">
                                        <option value="À faire" {{ $tache->statut == 'À faire' ? 'selected' : '' }}>À faire</option>
                                        <option value="En cours" {{ $tache->statut == 'En cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="Terminée" {{ $tache->statut == 'Terminée' ? 'selected' : '' }}>Terminée</option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-check2-circle fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Aucune tâche enregistrée</h6>
                                <p class="small text-muted mb-3">Créez votre première tâche pour lancer le suivi opérationnel.</p>
                                <a href="{{ route('taches.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg"></i> Ajouter une tâche
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-custom-header border-top border-bottom-0 py-3 bg-light">
        <span class="small text-muted">
            Total des tâches : <strong>{{ $taches->count() }}</strong>
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('tacheSearchInput')?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.tache-row');
        rows.forEach(row => {
            const dataText = row.getAttribute('data-search') || '';
            row.style.display = dataText.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
