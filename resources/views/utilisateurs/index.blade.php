@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('page_title', 'Gestion des utilisateurs')

@section('content')
<!-- Header Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #0f172a; letter-spacing: -0.02em;">Gestion des utilisateurs</h2>
        <p class="text-muted mb-0 small">
            Supervisez les accès, attribuez les rôles et gérez l'activation des collaborateurs.
        </p>
    </div>

    <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i>
        <span>Ajouter un utilisateur</span>
    </a>
</div>

<!-- Users Table Card -->
<div class="card-custom">
    <div class="card-custom-header flex-column flex-sm-row gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people text-primary fs-5"></i>
            <h6 class="mb-0 fw-bold">Liste des comptes</h6>
            <span class="badge bg-light text-secondary border px-2 py-1 ms-1 small">
                {{ $utilisateurs->count() }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-2 w-100 w-sm-auto" style="max-width: 320px;">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       id="userSearchInput"
                       class="form-control border-start-0 ps-0"
                       placeholder="Rechercher par nom, email, rôle...">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom" id="usersTable">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-end" style="min-width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateurs as $utilisateur)
                    <tr class="user-row" data-search="{{ strtolower($utilisateur->nom . ' ' . $utilisateur->prenom . ' ' . $utilisateur->email . ' ' . ($utilisateur->role->nomRole ?? '')) }}">
                        <td>
                            <span class="badge bg-light text-secondary border fw-mono">
                                #{{ $utilisateur->idUtilisateur }}
                            </span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="user-avatar-circle" style="width: 36px; height: 36px; font-size: 13px;">
                                    {{ strtoupper(substr($utilisateur->prenom, 0, 1)) }}{{ strtoupper(substr($utilisateur->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
                                    <div class="small text-muted d-sm-none">{{ $utilisateur->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="text-muted small">
                                <i class="bi bi-envelope me-1 text-secondary"></i>
                                {{ $utilisateur->email }}
                            </span>
                        </td>

                        <td>
                            @php
                                $roleName = $utilisateur->role->nomRole ?? 'Non défini';
                            @endphp

                            @if($roleName === 'Administrateur')
                                <span class="badge-role-admin">
                                    <i class="bi bi-shield-check me-1"></i>Administrateur
                                </span>
                            @elseif($roleName === 'Responsable de projet')
                                <span class="badge-role-resp">
                                    <i class="bi bi-person-badge me-1"></i>Responsable
                                </span>
                            @else
                                <span class="badge-role-collab">
                                    <i class="bi bi-person me-1"></i>{{ $roleName }}
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($utilisateur->actif)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-semibold d-inline-flex align-items-center gap-1.5">
                                    <span class="bg-success rounded-circle" style="width: 6px; height: 6px;"></span>
                                    Actif
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill small fw-semibold d-inline-flex align-items-center gap-1.5">
                                    <span class="bg-danger rounded-circle" style="width: 6px; height: 6px;"></span>
                                    Désactivé
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('utilisateurs.edit', $utilisateur->idUtilisateur) }}"
                                   class="btn btn-warning btn-sm py-1 px-2.5"
                                   title="Modifier l'utilisateur">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Modifier</span>
                                </a>

                                <form action="{{ route('utilisateurs.toggleStatus', $utilisateur->idUtilisateur) }}"
                                      method="POST"
                                      class="d-inline m-0"
                                      onsubmit="return confirm('Voulez-vous vraiment changer le statut de cet utilisateur ?');">
                                    @csrf
                                    @method('PUT')

                                    @if ($utilisateur->actif)
                                        <button type="submit" class="btn btn-danger btn-sm py-1 px-2.5" title="Désactiver le compte">
                                            <i class="bi bi-person-x"></i>
                                            <span>Désactiver</span>
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-sm py-1 px-2.5" title="Activer le compte">
                                            <i class="bi bi-person-check"></i>
                                            <span>Activer</span>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Aucun utilisateur enregistré</h6>
                                <p class="small text-muted mb-3">La liste des utilisateurs est actuellement vide.</p>
                                <a href="{{ route('utilisateurs.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-person-plus-fill"></i> Ajouter un utilisateur
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
            Total des comptes enregistrés : <strong>{{ $utilisateurs->count() }}</strong>
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Recherche instantanée en temps réel
    document.getElementById('userSearchInput')?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const dataText = row.getAttribute('data-search') || '';
            row.style.display = dataText.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush