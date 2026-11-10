<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $utilisateur = Auth::user();

        // Si l'utilisateur n'a aucun rôle rattaché en base
        if (!$utilisateur || !$utilisateur->role) {
            abort(403, 'Accès interdit : aucun rôle attribué à ce compte.');
        }

        $userRole = mb_strtolower(trim($utilisateur->role->nomRole ?? ''));

        // Les administrateurs ont un accès global
        if ($userRole === 'administrateur' || $userRole === 'admin') {
            return $next($request);
        }

        // Si aucune restriction spécifique n'est passée, laisser passer
        if (empty($roles)) {
            return $next($request);
        }

        // Normaliser les rôles requis (insensible à la casse et aux espaces)
        $allowedRoles = array_map(function ($r) {
            return mb_strtolower(trim($r));
        }, $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Accès interdit : vous n’avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}
