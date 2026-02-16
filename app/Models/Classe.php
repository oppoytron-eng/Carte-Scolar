<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'niveau',
        'etablissement_id',
        'filiere_id',
        'salle',
        'effectif_max',
        'effectif_actuel',
        'annee_scolaire',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effectif_max' => 'integer',
        'effectif_actuel' => 'integer',
    ];

    /**
     * Établissement de la classe
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Filière de la classe
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Élèves de la classe
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    /**
     * Élèves actifs de la classe
     */
    public function elevesActifs()
    {
        return $this->hasMany(Eleve::class)->where('statut', 'Actif');
    }

    /**
     * Cartes scolaires de la classe
     */
    public function cartesScolaires()
    {
        return $this->hasMany(CarteScolaire::class);
    }

    /**
     * Horaires de la classe
     */
    public function horaires()
    {
        return $this->hasMany(Horaire::class);
    }

    /**
     * Obtenir le nom complet de la classe
     */
    public function getNomCompletAttribute(): string
    {
        if ($this->filiere) {
            return "{$this->niveau} {$this->filiere->code} - {$this->nom}";
        }
        return "{$this->niveau} - {$this->nom}";
    }

    /**
     * Vérifier si la classe est complète
     */
    public function isComplete(): bool
    {
        return $this->effectif_actuel >= $this->effectif_max;
    }

    /**
     * Obtenir le taux de remplissage
     */
    public function getTauxRemplissageAttribute(): float
    {
        if ($this->effectif_max === 0) {
            return 0;
        }
        return round(($this->effectif_actuel / $this->effectif_max) * 100, 2);
    }

    /**
     * Obtenir le nombre de places disponibles
     */
    public function getPlacesDisponiblesAttribute(): int
    {
        return max(0, $this->effectif_max - $this->effectif_actuel);
    }

    /**
     * Mettre à jour l'effectif actuel
     */
    public function updateEffectif()
    {
        $this->effectif_actuel = $this->elevesActifs()->count();
        $this->save();
    }

    /**
     * Scope pour classes actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope par année scolaire
     */
    public function scopeAnneeScolaire($query, string $annee)
    {
        return $query->where('annee_scolaire', $annee);
    }

    /**
     * Scope par niveau
     */
    public function scopeNiveau($query, string $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    /**
     * Scope par établissement
     */
    public function scopeEtablissement($query, int $etablissementId)
    {
        return $query->where('etablissement_id', $etablissementId);
    }
}
