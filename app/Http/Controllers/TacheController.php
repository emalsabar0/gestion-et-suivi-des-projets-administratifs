<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tache;
use App\Models\Projet;
use App\Models\Utilisateur;
use App\Models\Historique;

class TacheController extends Controller
{
    public function index(Request $request)
    {
        $query = Tache::with(['projet', 'utilisateur']);

        if ($request->filled('idProjet')) {
            $query->where('idProjet', $request->idProjet);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $taches = $query->latest('idTache')->get();
        $projets = Projet::all();

        return view('taches.index', compact('taches', 'projets'));
    }

    public function create(Request $request)
    {
        $projets = Projet::all();
        $utilisateurs = Utilisateur::where('actif', true)->get();
        $projetSelectionne = $request->filled('idProjet') ? Projet::find($request->idProjet) : null;

        return view('taches.create', compact('projets', 'utilisateurs', 'projetSelectionne'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:191',
            'description' => 'nullable|string',
            'dateDebut' => 'nullable|date',
            'dateEcheance' => 'required|date',
            'idProjet' => 'required|exists:projets,idProjet',
            'idUtilisateur' => 'nullable|exists:utilisateurs,idUtilisateur',
            'statut' => 'nullable|string',
        ]);

        $statut = $request->statut ?: 'À faire';
        $normalized = mb_strtolower(trim($statut));
        if (in_array($normalized, ['terminée', 'terminee', 'terminé', 'termine', 'clôturé', 'cloture', 'fait'])) {
            $statut = 'Terminée';
        } elseif (in_array($normalized, ['en cours', 'cours'])) {
            $statut = 'En cours';
        } else {
            $statut = 'À faire';
        }

        $tache = Tache::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'dateDebut' => $request->dateDebut,
            'dateEcheance' => $request->dateEcheance,
            'statut' => $statut,
            'idProjet' => $request->idProjet,
            'idUtilisateur' => $request->idUtilisateur,
        ]);

        try {
            Historique::create([
                'action' => 'Création de tâche',
                'description' => 'Ajout de la tâche "' . $tache->titre . '" au projet ID #' . $tache->idProjet,
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $tache->idProjet,
                'idTache' => $tache->idTache,
            ]);
        } catch (\Exception $e) {
            // Ignorer si la table historique est optionnelle
        }

        if ($request->boolean('redirect_to_projet') || $request->filled('from_projet')) {
            return redirect()->route('projets.taches', $tache->idProjet)
                ->with('success', 'Tâche créée et ajoutée au projet avec succès.');
        }

        return redirect()->route('taches.index')
            ->with('success', 'Tâche créée avec succès.');
    }

    public function edit($id)
    {
        $tache = Tache::findOrFail($id);
        $projets = Projet::all();
        $utilisateurs = Utilisateur::where('actif', true)->get();

        return view('taches.edit', compact('tache', 'projets', 'utilisateurs'));
    }

    public function update(Request $request, $id)
    {
        $tache = Tache::findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:191',
            'description' => 'nullable|string',
            'dateDebut' => 'nullable|date',
            'dateEcheance' => 'required|date',
            'idProjet' => 'required|exists:projets,idProjet',
            'idUtilisateur' => 'nullable|exists:utilisateurs,idUtilisateur',
            'statut' => 'required|string',
        ]);

        $statut = $request->statut;
        $normalized = mb_strtolower(trim($statut));
        if (in_array($normalized, ['terminée', 'terminee', 'terminé', 'termine', 'clôturé', 'cloture', 'fait'])) {
            $statut = 'Terminée';
        } elseif (in_array($normalized, ['en cours', 'cours'])) {
            $statut = 'En cours';
        } else {
            $statut = 'À faire';
        }

        $tache->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'dateDebut' => $request->dateDebut,
            'dateEcheance' => $request->dateEcheance,
            'statut' => $statut,
            'idProjet' => $request->idProjet,
            'idUtilisateur' => $request->idUtilisateur,
        ]);

        try {
            Historique::create([
                'action' => 'Modification de tâche',
                'description' => 'Mise à jour de la tâche "' . $tache->titre . '" (Statut: ' . $tache->statut . ')',
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $tache->idProjet,
                'idTache' => $tache->idTache,
            ]);
        } catch (\Exception $e) {
            // Ignorer silencieusement
        }

        if ($request->boolean('redirect_to_projet') || $request->filled('from_projet')) {
            return redirect()->route('projets.taches', $tache->idProjet)
                ->with('success', 'Tâche modifiée avec succès.');
        }

        return redirect()->route('taches.index')
            ->with('success', 'Tâche modifiée avec succès.');
    }

    public function changerStatut(Request $request, $id)
    {
        $tache = Tache::findOrFail($id);

        $statut = $request->input('statut');
        $normalized = mb_strtolower(trim($statut));
        if (in_array($normalized, ['terminée', 'terminee', 'terminé', 'termine', 'clôturé', 'cloture', 'fait'])) {
            $statut = 'Terminée';
        } elseif (in_array($normalized, ['en cours', 'cours'])) {
            $statut = 'En cours';
        } else {
            $statut = 'À faire';
        }

        $ancienStatut = $tache->statut;
        $tache->update(['statut' => $statut]);

        try {
            Historique::create([
                'action' => 'Statut tâche modifié',
                'description' => 'Tâche "' . $tache->titre . '" passée de "' . $ancienStatut . '" à "' . $statut . '"',
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $tache->idProjet,
                'idTache' => $tache->idTache,
            ]);
        } catch (\Exception $e) {
            // Ignorer silencieusement
        }

        return redirect()->back()
            ->with('success', 'Statut de la tâche mis à jour avec succès.');
    }
}
