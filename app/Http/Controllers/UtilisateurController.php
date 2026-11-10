<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = Utilisateur::with('role')->get();

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('utilisateurs.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|min:8',
            'idRole' => 'required|exists:roles,idRole',
        ]);

        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'motDePasse' => Hash::make($request->password),
            'actif' => true,
            'idRole' => $request->idRole,
        ]);

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function edit($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $roles = Role::all();

        return view('utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id . ',idUtilisateur',
            'password' => 'nullable|min:8',
            'idRole' => 'required|exists:roles,idRole',
        ]);

        $utilisateur->nom = $request->nom;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->email = $request->email;
        $utilisateur->idRole = $request->idRole;

        if ($request->filled('password')) {
            $utilisateur->motDePasse = Hash::make($request->password);
        }

        $utilisateur->save();

        return redirect()->route('utilisateurs.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }
    public function toggleStatus($id)
{
    $utilisateur = Utilisateur::findOrFail($id);
    $utilisateur->actif = !$utilisateur->actif;
    $utilisateur->save();
    return redirect()->route('utilisateurs.index')
        ->with('success', 'Statut de l’utilisateur modifié avec succès.');
}
}