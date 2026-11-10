<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $primaryKey = 'idUtilisateur';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'motDePasse',
        'actif',
        'idRole'
    ];

    protected $hidden = [
        'motDePasse',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->motDePasse;
    }

    public function role()
    {
     return $this->belongsTo(Role::class, 'idRole', 'idRole');
    }
    public function projets()
{
    return $this->hasMany(
        Projet::class,
        'idUtilisateur',
        'idUtilisateur'
    );
}
}