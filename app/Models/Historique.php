<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $table = 'historiques';

    protected $primaryKey = 'idHistorique';

    protected $fillable = [
        'action',
        'description',
        'dateModification',
        'idUtilisateur',
        'idProjet',
        'idTache',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(
            Utilisateur::class,
            'idUtilisateur',
            'idUtilisateur'
        );
    }

    public function projet()
    {
        return $this->belongsTo(
            Projet::class,
            'idProjet',
            'idProjet'
        );
    }

    public function tache()
    {
        return $this->belongsTo(
            Tache::class,
            'idTache',
            'idTache'
        );
    }
}

