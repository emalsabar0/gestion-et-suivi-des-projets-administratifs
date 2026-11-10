@extends('layouts.app')

@section('title', 'Modifier un utilisateur')
@section('page_title', 'Gestion des utilisateurs')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-sm btn-secondary mb-2">
                    <i class="bi bi-arrow-left"></i> Retour aux utilisateurs
                </a>
                <h2 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.02em;">Modifier un compte</h2>
                <p class="text-muted small mb-0">Mettez à jour les informations, le mot de passe et les privilèges de l'utilisateur.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card-custom">
            <div class="card-custom-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-wrapper stat-icon-warning" style="width: 38px; height: 38px; font-size: 18px;">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Compte #{{ $utilisateur->idUtilisateur }} &bull; {{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h6>
                        <small class="text-muted">Dernière mise à jour du profil</small>
                    </div>
                </div>
            </div>

            <div class="card-custom-body">
                <form action="{{ route('utilisateurs.update', $utilisateur->idUtilisateur) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom & Prénom -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="nom">
                                Nom de famille <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    class="form-control border-start-0 ps-0 @error('nom') is-invalid @enderror"
                                    value="{{ old('nom', $utilisateur->nom) }}"
                                    required
                                >
                            </div>
                            @error('nom')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="prenom">
                                Prénom <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    class="form-control border-start-0 ps-0 @error('prenom') is-invalid @enderror"
                                    value="{{ old('prenom', $utilisateur->prenom) }}"
                                    required
                                >
                            </div>
                            @error('prenom')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label" for="email">
                            Adresse e-mail professionnelle <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                value="{{ old('email', $utilisateur->email) }}"
                                required
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Mot de passe & Rôle -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="password">
                                Nouveau mot de passe
                                <span class="text-muted fw-normal small">(laisser vide pour conserver l'actuel)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                >
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="idRole">
                                Rôle utilisateur <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <select id="idRole" name="idRole" class="form-select border-start-0 ps-0 @error('idRole') is-invalid @enderror" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->idRole }}"
                                            {{ old('idRole', $utilisateur->idRole) == $role->idRole ? 'selected' : '' }}>
                                            {{ $role->nomRole }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('idRole')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            <span>Enregistrer les modifications</span>
                        </button>

                        <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection