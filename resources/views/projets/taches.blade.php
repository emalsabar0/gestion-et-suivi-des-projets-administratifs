@extends('layouts.app')

@section('title', 'Tâches du projet - ' . $projet->nomProjet)
@section('page_title', 'Tâches du projet')

@section('content')
<!-- Header & Back Navigation -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <a href="{{ route('projets.index') }}" class="btn btn-sm btn-secondary mb-2">
            <i class="bi bi-arrow-left"></i> Retour aux projets
        </a>
        <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">
            {{ $projet->nomProjet }}
        </h2>
        <p class="text-muted mb-0 small">
            Suivi opérationnel des tâches, échéances et avancement pour ce projet administratif.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Bouton Modal Ajout Rapide -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelleTache">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Ajouter une tâche</span>
        </button>
    </div>
</div>

<!-- Notifications flash -->
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

<!-- Project Overview Card with Progress Rate & Retard -->
<div class="card-custom mb-4 p-4" style="background: linear-gradient(135deg, #0e1c40 0%, #070d1e 100%); color: #ffffff; border: 1px solid rgba(59, 130, 246, 0.2);">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <!-- Status Badge -->
                @if($projet->statut === 'Création')
                    <span class="badge bg-primary px-3 py-1 text-uppercase fw-semibold" style="font-size: 11px;">Création</span>
                @elseif($projet->statut === 'Planification')
                    <span class="badge bg-warning text-dark px-3 py-1 text-uppercase fw-semibold" style="font-size: 11px;">Planification</span>
                @elseif($projet->statut === 'Exécution')
                    <span class="badge bg-success px-3 py-1 text-uppercase fw-semibold" style="font-size: 11px;">Exécution</span>
                @elseif($projet->statut === 'Clôture')
                    <span class="badge bg-secondary px-3 py-1 text-uppercase fw-semibold" style="font-size: 11px;">Clôturé</span>
                @else
                    <span class="badge bg-info px-3 py-1 text-uppercase fw-semibold" style="font-size: 11px;">{{ $projet->statut }}</span>
                @endif

                <!-- Retard Alert -->
                @if($projet->est_en_retard)
                    <span class="badge bg-danger px-3 py-1 text-uppercase fw-bold d-inline-flex align-items-center gap-1" style="font-size: 11px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> En retard ({{ $projet->jours_retard }} jour{{ $projet->jours_retard > 1 ? 's' : '' }})
                    </span>
                @else
                    <span class="badge bg-success-subtle text-success px-3 py-1 fw-semibold border border-success-subtle" style="font-size: 11px;">
                        <i class="bi bi-check-circle-fill"></i> Dans les délais
                    </span>
                @endif
            </div>

            <p class="text-white-50 mb-3 small" style="max-width: 620px;">
                {{ $projet->description ?: 'Aucune description détaillée enregistrée pour ce projet.' }}
            </p>

            <div class="d-flex align-items-center gap-4 text-white-50 small flex-wrap">
                <div>
                    <i class="bi bi-person-circle me-1 text-primary"></i>
                    Responsable : <span class="text-white fw-semibold">{{ optional($projet->utilisateur)->nom ?? 'Non défini' }}</span>
                </div>
                <div>
                    <i class="bi bi-calendar3 me-1 text-primary"></i>
                    {{ $projet->dateDebut ? \Carbon\Carbon::parse($projet->dateDebut)->format('d/m/Y') : 'N/A' }} &rarr;
                    {{ $projet->dateFin ? \Carbon\Carbon::parse($projet->dateFin)->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
        </div>

        <!-- Taux d'avancement (Progress Gauge) -->
        <div class="col-lg-5">
            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-uppercase fw-bold text-white-50" style="font-size: 11px; letter-spacing: 0.05em;">
                        Taux d'avancement
                    </span>
                    <span class="fw-bold fs-4 text-white">
                        {{ $projet->taux_avancement }}%
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="progress mb-2" style="height: 12px; background-color: rgba(255, 255, 255, 0.15); border-radius: 6px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $projet->taux_avancement >= 100 ? 'bg-success' : ($projet->taux_avancement >= 50 ? 'bg-primary' : 'bg-warning') }}"
                         role="progressbar"
                         style="width: {{ $projet->taux_avancement }}%;"
                         aria-valuenow="{{ $projet->taux_avancement }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>

                <div class="d-flex justify-content-between text-white-50 small" style="font-size: 12px;">
                    <span>
                        <strong class="text-white">{{ $projet->nb_taches_terminees }}</strong> / {{ $projet->nb_taches_total }} tâche(s) terminée(s)
                    </span>
                    <span>
                        <strong class="text-white">{{ max(0, $projet->nb_taches_total - $projet->nb_taches_terminees) }}</strong> restante(s)
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tasks Table for This Project -->
<div class="card-custom">
    <div class="card-custom-header flex-column flex-sm-row gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-list-task text-primary fs-5"></i>
            <h6 class="mb-0 fw-bold">Tâches affectées au projet</h6>
            <span class="badge bg-light text-secondary border px-2 py-1 ms-1 small">
                {{ $taches->count() }}
            </span>
        </div>

        <div class="input-group input-group-sm w-100 w-sm-auto" style="max-width: 280px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-search"></i>
            </span>
            <input type="text"
                   id="tacheSearchInput"
                   class="form-control border-start-0 ps-0"
                   placeholder="Filtrer les tâches...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom" id="tachesTable">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Titre de la tâche</th>
                    <th>Assignée à</th>
                    <th>Date d'échéance</th>
                    <th>Statut</th>
                    <th class="text-end" style="min-width: 190px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($taches as $tache)
                    @php
                        $echeance = $tache->dateEcheance ? \Carbon\Carbon::parse($tache->dateEcheance) : null;
                        $isLate = $echeance ? ($echeance->isPast() && !$tache->est_terminee) : false;
                    @endphp
                    <tr class="tache-row" data-search="{{ strtolower($tache->titre . ' ' . $tache->description . ' ' . (optional($tache->utilisateur)->nom ?? '') . ' ' . $tache->statut) }}">
                        <td>
                            <span class="badge bg-light text-secondary border fw-mono">
                                #{{ $tache->idTache }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">{{ $tache->titre }}</div>
                            <div class="small text-muted text-truncate" style="max-width: 340px;">
                                {{ $tache->description ?: 'Aucune consigne saisie' }}
                            </div>
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
                            <div class="small {{ $isLate ? 'text-danger fw-bold' : 'text-muted' }}">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ $echeance ? $echeance->format('d/m/Y') : 'Non définie' }}
                                @if($isLate)
                                    <span class="badge bg-danger ms-1" style="font-size: 10px;">En retard</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            @if($tache->est_terminee)
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
                                <a href="{{ route('taches.edit', $tache->idTache) }}" class="btn btn-warning btn-sm py-1 px-2" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <!-- Changement direct d'état -->
                                <form action="{{ route('taches.statut', $tache->idTache) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('PUT')
                                    <select name="statut" class="form-select form-select-sm py-0.5 px-1.5 d-inline-block" style="width: auto; font-size: 11.5px;" onchange="this.form.submit()" title="Changer l'état">
                                        <option value="À faire" {{ $tache->statut == 'À faire' ? 'selected' : '' }}>À faire</option>
                                        <option value="En cours" {{ $tache->statut == 'En cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="Terminée" {{ $tache->est_terminee ? 'selected' : '' }}>Terminée</option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-check2-circle fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Aucune tâche rattachée à ce projet</h6>
                                <p class="small text-muted mb-3">Découpez ce projet en actions concrètes en ajoutant la première tâche.</p>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNouvelleTache">
                                    <i class="bi bi-plus-lg"></i> Ajouter une tâche à ce projet
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout Rapide de Tâche -->
<div class="modal fade" id="modalNouvelleTache" tabindex="-1" aria-labelledby="modalNouvelleTacheLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #070d1e 0%, #0d1b3e 100%);">
                <h5 class="modal-title fw-bold" id="modalNouvelleTacheLabel">
                    <i class="bi bi-plus-circle me-1 text-primary"></i> Ajouter une tâche
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('taches.store') }}" method="POST">
                @csrf
                <input type="hidden" name="idProjet" value="{{ $projet->idProjet }}">
                <input type="hidden" name="redirect_to_projet" value="1">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="modal_titre">Titre de la tâche <span class="text-danger">*</span></label>
                        <input type="text" id="modal_titre" name="titre" class="form-control" placeholder="ex. Préparer le rapport d'étape" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="modal_description">Description / Consignes</label>
                        <textarea id="modal_description" name="description" class="form-control" rows="3" placeholder="Détails des livrables attendus..."></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="modal_dateDebut">Date début</label>
                            <input type="date" id="modal_dateDebut" name="dateDebut" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="modal_dateEcheance">Échéance <span class="text-danger">*</span></label>
                            <input type="date" id="modal_dateEcheance" name="dateEcheance" class="form-control" value="{{ $projet->dateFin ?: date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="modal_idUtilisateur">Assigner à</label>
                            <select id="modal_idUtilisateur" name="idUtilisateur" class="form-select">
                                <option value="">-- Non assignée --</option>
                                @foreach($utilisateurs as $u)
                                    <option value="{{ $u->idUtilisateur }}">{{ $u->prenom }} {{ $u->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold" for="modal_statut">Statut initial</label>
                            <select id="modal_statut" name="statut" class="form-select">
                                <option value="À faire" selected>À faire</option>
                                <option value="En cours">En cours</option>
                                <option value="Terminée">Terminée</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg"></i> Enregistrer la tâche
                    </button>
                </div>
            </form>
        </div>
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
