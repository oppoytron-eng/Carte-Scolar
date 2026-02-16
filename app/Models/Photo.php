<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photo_id',
        'eleve_id',
        'operateur_id',
        'chemin_original',
        'chemin_redimensionne',
        'chemin_miniature',
        'format',
        'largeur',
        'hauteur',
        'taille_fichier',
        'methode_capture',
        'appareil',
        'date_capture',
        'statut',
        'validateur_id',
        'date_validation',
        'motif_rejet',
        'score_qualite',
        'visage_detecte',
        'yeux_ouverts',
        'luminosite_correcte',
        'fond_uniforme',
        'is_active',
        'version',
    ];

    protected $casts = [
        'date_capture' => 'datetime',
        'date_validation' => 'datetime',
        'visage_detecte' => 'boolean',
        'yeux_ouverts' => 'boolean',
        'luminosite_correcte' => 'boolean',
        'fond_uniforme' => 'boolean',
        'is_active' => 'boolean',
        'largeur' => 'integer',
        'hauteur' => 'integer',
        'taille_fichier' => 'integer',
        'score_qualite' => 'integer',
        'version' => 'integer',
    ];

    /**
     * Élève de la photo
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Opérateur qui a pris la photo
     */
    public function operateur()
    {
        return $this->belongsTo(User::class, 'operateur_id');
    }

    /**
     * Validateur de la photo
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    /**
     * Cartes utilisant cette photo
     */
    public function cartesScolaires()
    {
        return $this->hasMany(CarteScolaire::class);
    }

    /**
     * Obtenir l'URL de la photo originale
     */
    public function getPhotoOriginalUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin_original);
    }

    /**
     * Obtenir l'URL de la photo redimensionnée
     */
    public function getPhotoRedimensionneeUrlAttribute(): ?string
    {
        if ($this->chemin_redimensionne) {
            return asset('storage/' . $this->chemin_redimensionne);
        }
        return $this->photo_original_url;
    }

    /**
     * Obtenir l'URL de la miniature
     */
    public function getPhotoMiniatureUrlAttribute(): ?string
    {
        if ($this->chemin_miniature) {
            return asset('storage/' . $this->chemin_miniature);
        }
        return $this->photo_redimensionnee_url;
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getTailleFormateeAttribute(): string
    {
        if ($this->taille_fichier < 1024) {
            return $this->taille_fichier . ' KB';
        }
        return round($this->taille_fichier / 1024, 2) . ' MB';
    }

    /**
     * Obtenir les dimensions
     */
    public function getDimensionsAttribute(): string
    {
        return "{$this->largeur} x {$this->hauteur} px";
    }

    /**
     * Vérifier si la photo est approuvée
     */
    public function isApprouvee(): bool
    {
        return $this->statut === 'Approuvee';
    }

    /**
     * Vérifier si la photo est rejetée
     */
    public function isRejetee(): bool
    {
        return $this->statut === 'Rejetee';
    }

    /**
     * Vérifier si la photo est en attente
     */
    public function isEnAttente(): bool
    {
        return $this->statut === 'En_attente';
    }

    /**
     * Obtenir le score de qualité formaté
     */
    public function getScoreQualiteFormatAttribute(): string
    {
        if (!$this->score_qualite) {
            return 'N/A';
        }
        
        if ($this->score_qualite >= 80) {
            return "Excellent ({$this->score_qualite}%)";
        } elseif ($this->score_qualite >= 60) {
            return "Bon ({$this->score_qualite}%)";
        } elseif ($this->score_qualite >= 40) {
            return "Moyen ({$this->score_qualite}%)";
        } else {
            return "Faible ({$this->score_qualite}%)";
        }
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'Approuvee' => 'success',
            'En_attente' => 'warning',
            'Rejetee' => 'danger',
            'A_refaire' => 'info',
            default => 'secondary'
        };
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        return match($this->statut) {
            'Approuvee' => 'Approuvée',
            'En_attente' => 'En attente',
            'Rejetee' => 'Rejetée',
            'A_refaire' => 'À refaire',
            default => $this->statut
        };
    }

    /**
     * Scope pour photos approuvées
     */
    public function scopeApprouvee($query)
    {
        return $query->where('statut', 'Approuvee');
    }

    /**
     * Scope pour photos en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'En_attente');
    }

    /**
     * Scope pour photos rejetées
     */
    public function scopeRejetee($query)
    {
        return $query->where('statut', 'Rejetee');
    }

    /**
     * Scope pour photos actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope par opérateur
     */
    public function scopeOperateur($query, int $operateurId)
    {
        return $query->where('operateur_id', $operateurId);
    }

    /**
     * Scope par méthode de capture
     */
    public function scopeMethodeCapture($query, string $methode)
    {
        return $query->where('methode_capture', $methode);
    }

    /**
     * Scope pour photos avec bonne qualité
     */
    public function scopeBonneQualite($query)
    {
        return $query->where('score_qualite', '>=', 70)
                    ->where('visage_detecte', true);
    }
}
