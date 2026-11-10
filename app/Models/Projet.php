<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Projet extends Model
{
    use HasFactory;

    protected $table = 'projets';

    protected $primaryKey = 'idProjet';

    protected $fillable = [
        'nomProjet',
        'description',
        'dateDebut',
        'dateFin',
        'statut',
        'idUtilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(
            Utilisateur::class,
            'idUtilisateur',
            'idUtilisateur'
        );
    }

    // Un projet possède plusieurs tâches
    public function taches()
    {
        return $this->hasMany(
            Tache::class,
            'idProjet',
            'idProjet'
        );
    }

    // Nombre total de tâches
    public function getNbTachesTotalAttribute()
    {
        return $this->relationLoaded('taches') ? $this->taches->count() : $this->taches()->count();
    }

    // Nombre de tâches terminées (insensible à la casse, aux accents et aux variantes)
    public function getNbTachesTermineesAttribute()
    {
        $taches = $this->relationLoaded('taches') ? $this->taches : $this->taches()->get();

        return $taches->filter(function ($t) {
            $statut = trim(mb_strtolower($t->statut ?? ''));
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
        })->count();
    }

    // Calcul dynamique du taux d'avancement (en pourcentage 0% - 100%)
    public function getTauxAvancementAttribute()
    {
        $total = $this->nb_taches_total;
        if ($total > 0) {
            $terminees = $this->nb_taches_terminees;
            return (int) round(($terminees / $total) * 100);
        }

        // Taux indicatif selon le cycle de vie si aucune tâche n'est encore rattachée
        $statutProjet = trim(mb_strtolower($this->statut ?? ''));
        if (in_array($statutProjet, ['clôture', 'cloture'])) return 100;
        if (in_array($statutProjet, ['exécution', 'execution'])) return 60;
        if (in_array($statutProjet, ['planification'])) return 25;
        if (in_array($statutProjet, ['création', 'creation'])) return 10;

        return 0;
    }

    // Détection de projet en retard (dateFin passée et projet non clôturé)
    public function getEstEnRetardAttribute()
    {
        $statutProjet = trim(mb_strtolower($this->statut ?? ''));
        if (in_array($statutProjet, ['clôture', 'cloture'])) {
            return false;
        }

        if (!$this->dateFin) {
            return false;
        }

        try {
            return Carbon::parse($this->dateFin)->endOfDay()->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    // Nombre de jours de retard
    public function getJoursRetardAttribute()
    {
        if (!$this->est_en_retard || !$this->dateFin) {
            return 0;
        }

        try {
            $fin = Carbon::parse($this->dateFin)->endOfDay();
            return (int) max(1, ceil(abs(now()->diffInDays($fin, false))));
        } catch (\Exception $e) {
            return 0;
        }
    }
}
