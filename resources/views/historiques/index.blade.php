@extends('layouts.app')

@section('title', 'Journal d\'historique')
@section('page_title', 'Historique des activités')

@section('content')
<!-- Header Bar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: #070d1e; letter-spacing: -0.02em;">Journal d'audit & Historique</h2>
        <p class="text-muted mb-0 small">
            Traçabilité chronologique des créations, modifications et changements d'états sur l'ensemble de la plateforme.
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold">
            <i class="bi bi-shield-check me-1"></i> Traçabilité active
        </span>
    </div>
</div>

<!-- Main Table Card -->
<div class="card-custom">
    <div class="card-custom-header flex-column flex-sm-row gap-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary fs-5"></i>
            <h6 class="mb-0 fw-bold">Derniers événements enregistrés</h6>
            <span class="badge bg-light text-secondary border px-2 py-1 ms-1 small">
                {{ $historiques->count() }}
            </span>
        </div>

        <div class="input-group input-group-sm w-100 w-sm-auto" style="max-width: 280px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
                <i class="bi bi-search"></i>
            </span>
            <input type="text"
                   id="historySearchInput"
                   class="form-control border-start-0 ps-0"
                   placeholder="Filtrer par action, utilisateur...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-custom" id="historyTable">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Date & Heure</th>
                    <th>Action</th>
                    <th>Description détaillée</th>
                    <th>Auteur</th>
                    <th>Projet / Tâche</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historiques as $hist)
                    <tr class="history-row" data-search="{{ strtolower($hist->action . ' ' . $hist->description . ' ' . ($hist->utilisateur->nom ?? '') . ' ' . ($hist->projet->nomProjet ?? '')) }}">
                        <td>
                            <span class="badge bg-light text-secondary border fw-mono">
                                #{{ $hist->idHistorique }}
                            </span>
                        </td>

                        <td>
                            <div class="small fw-semibold text-dark">
                                <i class="bi bi-clock me-1 text-primary"></i>
                                {{ \Carbon\Carbon::parse($hist->dateModification)->format('d/m/Y H:i') }}
                            </div>
                            <div class="text-muted" style="font-size: 11px;">
                                {{ \Carbon\Carbon::parse($hist->dateModification)->diffForHumans() }}
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill small fw-semibold">
                                <i class="bi bi-lightning-charge me-1"></i>
                                {{ $hist->action }}
                            </span>
                        </td>

                        <td>
                            <div class="text-dark small">
                                {{ $hist->description ?: 'Aucun détail supplémentaire' }}
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-circle" style="width: 28px; height: 28px; font-size: 11px;">
                                    {{ strtoupper(substr($hist->utilisateur->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($hist->utilisateur->nom ?? 'S', 0, 1)) }}
                                </div>
                                <span class="small fw-medium">
                                    {{ $hist->utilisateur->prenom ?? '' }} {{ $hist->utilisateur->nom ?? 'Système' }}
                                </span>
                            </div>
                        </td>

                        <td>
                            @if($hist->projet)
                                <div class="small fw-medium text-primary">
                                    <i class="bi bi-folder2 me-1"></i> {{ $hist->projet->nomProjet }}
                                </div>
                            @endif
                            @if($hist->tache)
                                <div class="small text-muted">
                                    <i class="bi bi-check2-square me-1"></i> {{ $hist->tache->titre }}
                                </div>
                            @endif
                            @if(!$hist->projet && !$hist->tache)
                                <span class="text-muted small">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Aucun événement dans l'historique</h6>
                                <p class="small text-muted mb-0">Les actions de création et mise à jour apparaîtront automatiquement ici au fil de l'utilisation.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-custom-header border-top border-bottom-0 py-3 bg-light">
        <span class="small text-muted">
            Total des entrées d'audit : <strong>{{ $historiques->count() }}</strong>
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('historySearchInput')?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.history-row');
        rows.forEach(row => {
            const dataText = row.getAttribute('data-search') || '';
            row.style.display = dataText.includes(query) ? '' : 'none';
        });
    });
</script>
@endpush
