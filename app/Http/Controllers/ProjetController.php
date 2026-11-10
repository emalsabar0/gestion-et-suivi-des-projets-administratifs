<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use App\Models\Utilisateur;
use App\Models\Tache;
use App\Models\Historique;

class ProjetController extends Controller
{
    // Afficher la liste des projets
    public function index()
    {
        $projets = Projet::with(['utilisateur', 'taches.utilisateur'])->latest('idProjet')->get();

        return view('projets.index', compact('projets'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('projets.create');
    }

    // Enregistrer un nouveau projet
    public function store(Request $request)
    {
        $request->validate([
            'nomProjet' => 'required|string|max:191',
            'description' => 'nullable|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
            'statut' => 'required|in:Création,Planification,Exécution,Clôture',
        ]);

        $projet = Projet::create([
            'nomProjet' => $request->nomProjet,
            'description' => $request->description,
            'dateDebut' => $request->dateDebut,
            'dateFin' => $request->dateFin,
            'statut' => $request->statut,
            'idUtilisateur' => auth()->id() ?: 1,
        ]);

        try {
            Historique::create([
                'action' => 'Création de projet',
                'description' => 'Création du projet "' . $projet->nomProjet . '" (Statut: ' . $projet->statut . ')',
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $projet->idProjet,
            ]);
        } catch (\Exception $e) {
            // Ignorer silencieusement
        }

        return redirect()->route('projets.index')
            ->with('success', 'Projet créé avec succès.');
    }

    // Afficher le formulaire de modification
    public function edit($id)
    {
        $projet = Projet::findOrFail($id);

        return view('projets.edit', compact('projet'));
    }

    // Enregistrer les modifications
    public function update(Request $request, $id)
    {
        $projet = Projet::findOrFail($id);

        $request->validate([
            'nomProjet' => 'required|string|max:191',
            'description' => 'nullable|string',
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut',
        ]);

        $projet->update([
            'nomProjet' => $request->nomProjet,
            'description' => $request->description,
            'dateDebut' => $request->dateDebut,
            'dateFin' => $request->dateFin,
        ]);

        try {
            Historique::create([
                'action' => 'Modification de projet',
                'description' => 'Mise à jour des informations du projet "' . $projet->nomProjet . '"',
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $projet->idProjet,
            ]);
        } catch (\Exception $e) {
            // Ignorer silencieusement
        }

        return redirect()->route('projets.index')
            ->with('success', 'Projet modifié avec succès.');
    }

    // Changer l'état d'un projet
    public function changerStatut(Request $request, $id)
    {
        $projet = Projet::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:Création,Planification,Exécution,Clôture',
        ]);

        // Définir les transitions autorisées
        $transitions = [
            'Création' => 'Planification',
            'Planification' => 'Exécution',
            'Exécution' => 'Clôture',
        ];

        $nouveauStatut = $request->statut;

        // Vérifier l'ordre du cycle de vie
        if (
            !isset($transitions[$projet->statut]) ||
            $transitions[$projet->statut] !== $nouveauStatut
        ) {
            return back()->with(
                'error',
                'Cette transition n’est pas autorisée.'
            );
        }

        // Vérifier les tâches avant la clôture
        if ($nouveauStatut === 'Clôture') {
            $nombreTaches = $projet->nb_taches_total;
            $tachesNonTerminees = $projet->nb_taches_total - $projet->nb_taches_terminees;

            if ($nombreTaches === 0 || $tachesNonTerminees > 0) {
                return back()->with(
                    'error',
                    'Impossible de clôturer ce projet : toutes ses tâches doivent être terminées.'
                );
            }
        }

        $ancienStatut = $projet->statut;

        // Enregistrer le nouvel état
        $projet->update([
            'statut' => $nouveauStatut,
        ]);

        try {
            Historique::create([
                'action' => 'Cycle de vie projet',
                'description' => 'Projet "' . $projet->nomProjet . '" passé de "' . $ancienStatut . '" à "' . $nouveauStatut . '"',
                'dateModification' => now(),
                'idUtilisateur' => auth()->id() ?: 1,
                'idProjet' => $projet->idProjet,
            ]);
        } catch (\Exception $e) {
            // Ignorer silencieusement
        }

        return redirect()->route('projets.index')
            ->with('success', 'État du projet modifié avec succès.');
    }

    // Afficher et gérer les tâches d'un projet spécifique
    public function taches($id)
    {
        $projet = Projet::with(['utilisateur', 'taches.utilisateur'])->findOrFail($id);
        $taches = $projet->taches()->with('utilisateur')->latest('idTache')->get();
        $utilisateurs = Utilisateur::where('actif', true)->get();

        return view('projets.taches', compact('projet', 'taches', 'utilisateurs'));
    }
}
