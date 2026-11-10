@extends('layouts.app')

@section('title', 'Nouvelle tâche')
@section('page_title', 'Gestion des tâches')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ request('idProjet') ? route('projets.taches', request('idProjet')) : route('taches.index') }}" class="btn btn-sm btn-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> {{ request('idProjet') ? 'Retour au projet' : 'Retour aux tâches' }}
                </a>
                <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">Créer une tâche</h2>
                <p class="text-muted small mb-0">Définissez une action opérationnelle à rattacher à un projet administratif.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card-custom">
            <div class="card-custom-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-wrapper stat-icon-primary" style="width: 38px; height: 38px; font-size: 18px;">
                        <i class="bi bi-check2-square"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Paramètres de la tâche</h6>
                        <small class="text-muted">Affectation et calendrier</small>
                    </div>
                </div>
            </div>

            <div class="card-custom-body">
                <form action="{{ route('taches.store') }}" method="POST">
                    @csrf
                    @if(request('idProjet'))
                        <input type="hidden" name="from_projet" value="{{ request('idProjet') }}">
                    @endif

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
                                placeholder="ex. Rédiger le cahier des charges technique"
                                value="{{ old('titre') }}"
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
                                    <option value="">-- Choisir un projet --</option>
                                    @foreach($projets as $p)
                                        <option value="{{ $p->idProjet }}" {{ (old('idProjet', request('idProjet')) == $p->idProjet) ? 'selected' : '' }}>
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
                                        <option value="{{ $u->idUtilisateur }}" {{ old('idUtilisateur') == $u->idUtilisateur ? 'selected' : '' }}>
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

                    <!-- Dates -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="dateDebut">
                                Date de début (facultatif)
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
                                >
                            </div>
                            @error('dateDebut')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
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
                                    value="{{ old('dateEcheance') }}"
                                    required
                                >
                            </div>
                            @error('dateEcheance')
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
                            placeholder="Détaillez les attendus, les livrables et les critères d'acceptation..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Créer la tâche</span>
                        </button>

                        <a href="{{ request('idProjet') ? route('projets.taches', request('idProjet')) : route('taches.index') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
