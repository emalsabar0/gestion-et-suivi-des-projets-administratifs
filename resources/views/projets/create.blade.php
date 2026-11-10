@extends('layouts.app')

@section('title', 'Nouveau projet')
@section('page_title', 'Créer un projet')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('projets.index') }}" class="btn btn-sm btn-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Retour aux projets
                </a>
                <h2 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.02em;">Nouveau projet</h2>
                <p class="text-muted small mb-0">Renseignez les paramètres et informations du nouveau projet administratif.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card-custom">
            <div class="card-custom-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-wrapper stat-icon-primary" style="width: 38px; height: 38px; font-size: 18px;">
                        <i class="bi bi-folder-plus"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Informations générales</h6>
                        <small class="text-muted">Champs requis pour initialiser le dossier de projet</small>
                    </div>
                </div>
            </div>

            <div class="card-custom-body">
                <form action="{{ route('projets.store') }}" method="POST">
                    @csrf

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
                                placeholder="ex. Numérisation des archives administratives"
                                value="{{ old('nomProjet') }}"
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
                            placeholder="Détaillez les objectifs, le contexte et les livrables attendus..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Période / Dates -->
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
                                    value="{{ old('dateDebut') }}"
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
                                    value="{{ old('dateFin') }}"
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

                    <!-- Statut initial -->
                    <div class="mb-4">
                        <label class="form-label" for="statut">
                            Statut initial du projet <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-flag-fill"></i>
                            </span>
                            <select id="statut" name="statut" class="form-select border-start-0 ps-0 @error('statut') is-invalid @enderror" required>
                                <option value="">-- Choisir un statut initial --</option>
                                <option value="Création" {{ old('statut') == 'Création' ? 'selected' : '' }}>Création (Ébauche / cadrage)</option>
                                <option value="Planification" {{ old('statut') == 'Planification' ? 'selected' : '' }}>Planification (Organisation des ressources)</option>
                                <option value="Exécution" {{ old('statut') == 'Exécution' ? 'selected' : '' }}>Exécution (En cours de réalisation)</option>
                                <option value="Clôture" {{ old('statut') == 'Clôture' ? 'selected' : '' }}>Clôture (Projet terminé)</option>
                            </select>
                        </div>
                        @error('statut')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Enregistrer le projet</span>
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