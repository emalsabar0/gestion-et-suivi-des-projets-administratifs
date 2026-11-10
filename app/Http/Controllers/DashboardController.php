<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUtilisateurs = Utilisateur::count();

        return view('dashboard', compact('totalUtilisateurs'));
    }
}