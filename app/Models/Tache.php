<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $table = 'taches';

    protected $primaryKey = 'idTache';

    protected $fillable = [
        'titre',
        'description',
        'dateDebut',
        'dateEcheance',
        'statut',
        'idProjet',
        'idUtilisateur',
    ];

    // Une tâche appartient à un projet
    public function projet()
    {
        return $this->belongsTo(
            Projet::class,
            'idProjet',
            'idProjet'
        );
    }

    // Une tâche peut être affectée à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(
            Utilisateur::class,
            'idUtilisateur',
            'idUtilisateur'
        );
    }

    // Savoir si la tâche est terminée
    public function getEstTermineeAttribute()
    {
        $statut = trim(mb_strtolower($this->statut ?? ''));
        return in_array($statut, [
            'terminée',
            'terminee',
            'terminé',
            'termine',
            'clôturé',
            'cloture',
            'fait',
            'done',
            'complété',
            'complete'
        ]);
    }
}
