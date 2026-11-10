<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historique;

class HistoriqueController extends Controller
{
    public function index()
    {
        $historiques = Historique::with(['utilisateur', 'projet', 'tache'])
            ->latest('dateModification')
            ->take(100)
            ->get();

        return view('historiques.index', compact('historiques'));
    }
}

