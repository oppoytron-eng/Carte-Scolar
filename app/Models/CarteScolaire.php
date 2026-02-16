<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarteScolaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cartes_scolaires';

    protected $fillable = [
        'numero_carte',
        'eleve_id',
        'photo_id',
        'etablissement_id',
        'classe_id',
        'generateur_id',
        'date_generation',
        'annee_scolaire',
        'chemin_recto',
        'chemin_verso',
        'chemin_pdf',
        'qr_code_data',
        'chemin_qr_code',
        'code_barres',
        'statut',
        'date_impression',
        'imprimeur_id',
        'nombre_impressions',
        'date_distribution',
        'distributeur_id',
        'signature_parent',
        'modele_carte',
        'donnees_carte',
        'date_debut_validite',
        'date_fin_validite',
        'est_valide',
        'observations',
        'is_duplicate',
        'carte_originale_id',
    ];

    protected $casts = [
        'date_generation' => 'datetime',
        'date_impression' => 'datetime',
        'date_distribution' => 'datetime',
        'date_debut_validite' => 'date',
        'date_fin_validite' => 'date',
        'donnees_carte' => 'array',
        'est_valide' => 'boolean',
        'is_duplicate' => 'boolean',
        'nombre_impressions' => 'integer',
    ];

    /**
     * Élève de la carte
     */
    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Photo utilisée sur la carte
     */
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    /**
     * Établissement
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Classe
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Utilisateur qui a généré la carte
     */
    public function generateur()
    {
        return $this->belongsTo(User::class, 'generateur_id');
    }

    /**
     * Utilisateur qui a imprimé la carte
     */
    public function imprimeur()
    {
        return $this->belongsTo(User::class, 'imprimeur_id');
    }

    /**
     * Utilisateur qui a distribué la carte
     */
    public function distributeur()
    {
        return $this->belongsTo(User::class, 'distributeur_id');
    }

    /**
     * Médias associés à la carte
     */
    public function medias()
    {
        return $this->hasMany(MediaCarte::class);
    }

    /**
     * Carte originale (en cas de duplicata)
     */
    public function carteOriginale()
    {
        return $this->belongsTo(CarteScolaire::class, 'carte_originale_id');
    }

    /**
     * Duplicatas de cette carte
     */
    public function duplicatas()
    {
        return $this->hasMany(CarteScolaire::class, 'carte_originale_id');
    }

    /**
     * Obtenir l'URL du recto
     */
    public function getRectoUrlAttribute(): ?string
    {
        if ($this->chemin_recto) {
            return asset('storage/' . $this->chemin_recto);
        }
        return null;
    }

    /**
     * Obtenir l'URL du verso
     */
    public function getVersoUrlAttribute(): ?string
    {
        if ($this->chemin_verso) {
            return asset('storage/' . $this->chemin_verso);
        }
        return null;
    }

    /**
     * Obtenir l'URL du PDF
     */
    public function getPdfUrlAttribute(): ?string
    {
        if ($this->chemin_pdf) {
            return asset('storage/' . $this->chemin_pdf);
        }
        return null;
    }

    /**
     * Obtenir l'URL du QR Code
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if ($this->chemin_qr_code) {
            return asset('storage/' . $this->chemin_qr_code);
        }
        return null;
    }

    /**
     * Vérifier si la carte est valide
     */
    public function isValide(): bool
    {
        return $this->est_valide 
            && now()->between($this->date_debut_validite, $this->date_fin_validite);
    }

    /**
     * Vérifier si la carte est imprimée
     */
    public function isImprimee(): bool
    {
        return in_array($this->statut, ['Carte_imprimee', 'Carte_distribuee']);
    }

    /**
     * Vérifier si la carte est distribuée
     */
    public function isDistribuee(): bool
    {
        return $this->statut === 'Carte_distribuee';
    }

    /**
     * Obtenir le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        return match($this->statut) {
            'Photo_prise' => 'Photo prise',
            'Informations_validees' => 'Informations validées',
            'Carte_generee' => 'Carte générée',
            'Carte_imprimee' => 'Carte imprimée',
            'Carte_distribuee' => 'Carte distribuée',
            'Perdue' => 'Perdue',
            'Annulee' => 'Annulée',
            default => $this->statut
        };
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'Photo_prise' => 'info',
            'Informations_validees' => 'primary',
            'Carte_generee' => 'success',
            'Carte_imprimee' => 'success',
            'Carte_distribuee' => 'success',
            'Perdue' => 'warning',
            'Annulee' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Obtenir le pourcentage de progression
     */
    public function getProgressionAttribute(): int
    {
        return match($this->statut) {
            'Photo_prise' => 20,
            'Informations_validees' => 40,
            'Carte_generee' => 60,
            'Carte_imprimee' => 80,
            'Carte_distribuee' => 100,
            default => 0
        };
    }

    /**
     * Incrémenter le nombre d'impressions
     */
    public function incrementImpressions()
    {
        $this->increment('nombre_impressions');
        $this->date_impression = now();
        $this->save();
    }

    /**
     * Scope pour cartes valides
     */
    public function scopeValide($query)
    {
        return $query->where('est_valide', true)
                    ->whereDate('date_debut_validite', '<=', now())
                    ->whereDate('date_fin_validite', '>=', now());
    }

    /**
     * Scope par année scolaire
     */
    public function scopeAnneeScolaire($query, string $annee)
    {
        return $query->where('annee_scolaire', $annee);
    }

    /**
     * Scope par statut
     */
    public function scopeStatut($query, string $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour cartes imprimées
     */
    public function scopeImprimee($query)
    {
        return $query->whereIn('statut', ['Carte_imprimee', 'Carte_distribuee']);
    }

    /**
     * Scope pour cartes distribuées
     */
    public function scopeDistribuee($query)
    {
        return $query->where('statut', 'Carte_distribuee');
    }

    /**
     * Scope pour cartes non distribuées
     */
    public function scopeNonDistribuee($query)
    {
        return $query->whereIn('statut', ['Carte_generee', 'Carte_imprimee']);
    }

    /**
     * Scope pour cartes perdues
     */
    public function scopePerdue($query)
    {
        return $query->where('statut', 'Perdue');
    }

    /**
     * Scope par établissement
     */
    public function scopeEtablissement($query, int $etablissementId)
    {
        return $query->where('etablissement_id', $etablissementId);
    }

    /**
     * Scope par classe
     */
    public function scopeClasse($query, int $classeId)
    {
        return $query->where('classe_id', $classeId);
    }
}
