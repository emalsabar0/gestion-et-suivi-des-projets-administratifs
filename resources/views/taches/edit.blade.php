@extends('layouts.app')

@section('title', 'Modifier la tâche')
@section('page_title', 'Gestion des tâches')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <div class="d-flex gap-2 mb-2">
                    <a href="{{ route('projets.taches', $tache->idProjet) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour au projet
                    </a>
                    <a href="{{ route('taches.index') }}" class="btn btn-sm btn-outline-secondary">
                        Toutes les tâches
                    </a>
                </div>
                <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">Modifier une tâche</h2>
                <p class="text-muted small mb-0">Mettez à jour les consignes, le responsable ou l'état d'avancement.</p>
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
                        <h6 class="mb-0 fw-bold">Tâche #{{ $tache->idTache }} &bull; {{ $tache->titre }}</h6>
                        <small class="text-muted">Édition des paramètres</small>
                    </div>
                </div>
            </div>

            <div class="card-custom-body">
                <form action="{{ route('taches.update', $tache->idTache) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to_projet" value="1">

                    <!-- Titre -->
                    <div class="mb-4">
                        <label class="form-label" for="titre">
                            Titre de la tâche <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-card-heading"></i>
                            </span>
                            <input
                                type="text"
                                id="titre"
                                name="titre"
                                class="form-control border-start-0 ps-0 @error('titre') is-invalid @enderror"
                                value="{{ old('titre', $tache->titre) }}"
                                required
                            >
                        </div>
                        @error('titre')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Projet et Utilisateur -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="idProjet">
                                Projet associé <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-folder2"></i>
                                </span>
                                <select id="idProjet" name="idProjet" class="form-select border-start-0 ps-0 @error('idProjet') is-invalid @enderror" required>
                                    @foreach($projets as $p)
                                        <option value="{{ $p->idProjet }}" {{ old('idProjet', $tache->idProjet) == $p->idProjet ? 'selected' : '' }}>
                                            {{ $p->nomProjet }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('idProjet')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="idUtilisateur">
                                Collaborateur assigné
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <select id="idUtilisateur" name="idUtilisateur" class="form-select border-start-0 ps-0 @error('idUtilisateur') is-invalid @enderror">
                                    <option value="">-- Non assignée (en attente) --</option>
                                    @foreach($utilisateurs as $u)
                                        <option value="{{ $u->idUtilisateur }}" {{ old('idUtilisateur', $tache->idUtilisateur) == $u->idUtilisateur ? 'selected' : '' }}>
                                            {{ $u->prenom }} {{ $u->nom }} ({{ $u->role->nomRole ?? 'Membre' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('idUtilisateur')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates & Statut -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="dateDebut">
                                Date de début
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
                                    value="{{ old('dateDebut', $tache->dateDebut) }}"
                                >
                            </div>
                            @error('dateDebut')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="dateEcheance">
                                Date d'échéance <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-calendar-x"></i>
                                </span>
                                <input
                                    type="date"
                                    id="dateEcheance"
                                    name="dateEcheance"
                                    class="form-control border-start-0 ps-0 @error('dateEcheance') is-invalid @enderror"
                                    value="{{ old('dateEcheance', $tache->dateEcheance) }}"
                                    required
                                >
                            </div>
                            @error('dateEcheance')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="statut">
                                État de la tâche <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-flag"></i>
                                </span>
                                <select id="statut" name="statut" class="form-select border-start-0 ps-0 @error('statut') is-invalid @enderror" required>
                                    <option value="À faire" {{ old('statut', $tache->statut) == 'À faire' ? 'selected' : '' }}>À faire</option>
                                    <option value="En cours" {{ old('statut', $tache->statut) == 'En cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="Terminée" {{ old('statut', $tache->statut) == 'Terminée' ? 'selected' : '' }}>Terminée</option>
                                </select>
                            </div>
                            @error('statut')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label" for="description">
                            Description & Consignes
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                        >{{ old('description', $tache->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            <span>Enregistrer les modifications</span>
                        </button>

                        <a href="{{ route('projets.taches', $tache->idProjet) }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
