<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\HistoriqueController;

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tableau de bord
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Gestion des utilisateurs (Réservé aux Administrateurs)
Route::middleware(['auth', 'role:Administrateur'])->group(function () {
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])
        ->name('utilisateurs.index');

    Route::get('/utilisateurs/ajouter', [UtilisateurController::class, 'create'])
        ->name('utilisateurs.create');

    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])
        ->name('utilisateurs.store');

    Route::get('/utilisateurs/{id}/modifier', [UtilisateurController::class, 'edit'])
        ->name('utilisateurs.edit');

    Route::put('/utilisateurs/{id}', [UtilisateurController::class, 'update'])
        ->name('utilisateurs.update');

    Route::put('/utilisateurs/{id}/statut', [UtilisateurController::class, 'toggleStatus'])
        ->name('utilisateurs.toggleStatus');
});

// Gestion des projets
Route::middleware(['auth'])->group(function () {
    // Consulter la liste des projets
    Route::get('/projets', [ProjetController::class, 'index'])
        ->name('projets.index');

    // Consulter les tâches d'un projet spécifique
    Route::get('/projets/{id}/taches', [ProjetController::class, 'taches'])
        ->name('projets.taches');

    // Alias direct pour consulter un projet
    Route::get('/projets/{id}', [ProjetController::class, 'taches'])
        ->name('projets.show');

    // Formulaire de création de projet
    Route::get('/projets/ajouter', [ProjetController::class, 'create'])
        ->middleware('role:Administrateur,Responsable de projet')
        ->name('projets.create');

    // Enregistrer un nouveau projet
    Route::post('/projets', [ProjetController::class, 'store'])
        ->middleware('role:Administrateur,Responsable de projet')
        ->name('projets.store');

    // Modifier un projet
    Route::get('/projets/{id}/modifier', [ProjetController::class, 'edit'])
        ->middleware('role:Administrateur,Responsable de projet')
        ->name('projets.edit');

    // Enregistrer les modifications d'un projet
    Route::put('/projets/{id}', [ProjetController::class, 'update'])
        ->middleware('role:Administrateur,Responsable de projet')
        ->name('projets.update');

    // Changer l'état d'un projet dans le cycle de vie
    Route::put('/projets/{id}/statut', [ProjetController::class, 'changerStatut'])
        ->name('projets.statut');
});

// Gestion des tâches & Journal d'historique
Route::middleware(['auth'])->group(function () {
    Route::get('/taches', [TacheController::class, 'index'])->name('taches.index');
    Route::get('/taches/ajouter', [TacheController::class, 'create'])->name('taches.create');
    Route::post('/taches', [TacheController::class, 'store'])->name('taches.store');
    Route::get('/taches/{id}/modifier', [TacheController::class, 'edit'])->name('taches.edit');
    Route::put('/taches/{id}', [TacheController::class, 'update'])->name('taches.update');
    Route::put('/taches/{id}/statut', [TacheController::class, 'changerStatut'])->name('taches.statut');

    // Journal d'historique
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historiques.index');
});

// Page d'accueil -> Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});
