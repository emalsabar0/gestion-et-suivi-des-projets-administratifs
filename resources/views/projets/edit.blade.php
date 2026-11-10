@extends('layouts.app')

@section('title', 'Modifier le projet')
@section('page_title', 'Modifier le projet')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('projets.index') }}" class="btn btn-sm btn-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Retour aux projets
                </a>
                <h2 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.02em;">Modifier un projet</h2>
                <p class="text-muted small mb-0">Mettez à jour les informations et dates prévisionnelles du projet.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card-custom">
            <div class="card-custom-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-wrapper stat-icon-warning" style="width: 38px; height: 38px; font-size: 18px;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Détails du projet #{{ $projet->idProjet }}</h6>
                        <small class="text-muted">Modifications enregistrées en base de données</small>
                    </div>
                </div>
            </div>

            <div class="card-custom-body">
                <form action="{{ route('projets.update', $projet->idProjet) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom du projet -->
                    <div class="mb-4">
                        <label class="form-label" for="nomProjet">
                            Nom du projet <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-bookmark-fill"></i>
                            </span>
                            <input
                                type="text"
                                id="nomProjet"
                                name="nomProjet"
                                class="form-control border-start-0 ps-0 @error('nomProjet') is-invalid @enderror"
                                value="{{ old('nomProjet', $projet->nomProjet) }}"
                                required
                            >
                        </div>
                        @error('nomProjet')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label" for="description">
                            Description du projet
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                        >{{ old('description', $projet->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Dates -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="dateDebut">
                                Date de début <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-calendar-check"></i>
                                </span>
                                <input
                                    type="date"
                                    id="dateDebut"
                                    name="dateDebut"
                                    class="form-control border-start-0 ps-0 @error('dateDebut') is-invalid @enderror"
                                    value="{{ old('dateDebut', $projet->dateDebut) }}"
                                    required
                                >
                            </div>
                            @error('dateDebut')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="dateFin">
                                Date de fin <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-calendar-x"></i>
                                </span>
                                <input
                                    type="date"
                                    id="dateFin"
                                    name="dateFin"
                                    class="form-control border-start-0 ps-0 @error('dateFin') is-invalid @enderror"
                                    value="{{ old('dateFin', $projet->dateFin) }}"
                                    required
                                >
                            </div>
                            @error('dateFin')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Statut actuel (Lecture seule) -->
                    <div class="mb-4 p-3 bg-light rounded-3 border">
                        <label class="form-label mb-1">Statut actuel du cycle de vie</label>
                        <div class="d-flex align-items-center gap-2 mb-2">
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
                        </div>
                        <small class="text-muted d-block">
                            <i class="bi bi-info-circle me-1"></i>
                            Le changement d'état s'effectue directement depuis la liste des projets conformément aux règles du cycle de vie.
                        </small>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            <span>Enregistrer les modifications</span>
                        </button>

                        <a href="{{ route('projets.index') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection