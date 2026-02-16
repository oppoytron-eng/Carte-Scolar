<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Eleve extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'matricule',
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'nationalite',
        'contact_parent',
        'contact_parent_2',
        'nom_parent',
        'profession_parent',
        'adresse_parent',
        'email_parent',
        'etablissement_id',
        'classe_id',
        'annee_scolaire',
        'statut',
        'date_inscription',
        'observations',
        'redoublant',
        'groupe_sanguin',
        'allergies',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_inscription' => 'date',
        'redoublant' => 'boolean',
    ];

    /**
     * Établissement de l'élève
     */
    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    /**
     * Classe de l'élève
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    /**
     * Photos de l'élève
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Photo approuvée de l'élève
     */
    public function photoApprouvee()
    {
        return $this->hasOne(Photo::class)
                    ->where('statut', 'Approuvee')
                    ->where('is_active', true)
                    ->latest();
    }

    /**
     * Cartes scolaires de l'élève
     */
    public function cartesScolaires()
    {
        return $this->hasMany(CarteScolaire::class);
    }

    /**
     * Carte scolaire active
     */
    public function carteActive()
    {
        return $this->hasOne(CarteScolaire::class)
                    ->where('est_valide', true)
                    ->where('annee_scolaire', $this->annee_scolaire)
                    ->latest();
    }

    /**
     * Obtenir le nom complet de l'élève
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->nom} {$this->prenoms}";
    }

    /**
     * Obtenir l'âge de l'élève
     */
    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_naissance)->age;
    }

    /**
     * Obtenir la photo de profil de l'élève
     */
    public function getPhotoUrlAttribute(): ?string
    {
        $photo = $this->photoApprouvee;
        
        if ($photo) {
            return asset('storage/' . $photo->chemin_redimensionne ?? $photo->chemin_original);
        }
        
        // Avatar par défaut
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nom_complet) . '&color=4F46E5&background=EEF2FF';
    }

    /**
     * Vérifier si l'élève a une photo approuvée
     */
    public function hasPhotoApprouvee(): bool
    {
        return $this->photoApprouvee()->exists();
    }

    /**
     * Vérifier si l'élève a une carte active
     */
    public function hasCarteActive(): bool
    {
        return $this->carteActive()->exists();
    }

    /**
     * Obtenir le statut de production de la carte
     */
    public function getStatutProductionAttribute(): string
    {
        if (!$this->hasPhotoApprouvee()) {
            return 'En_attente_photo';
        }
        
        $carte = $this->carteActive;
        
        if (!$carte) {
            return 'Photo_prise';
        }
        
        return $carte->statut;
    }

    /**
     * Scope pour élèves actifs
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'Actif');
    }

    /**
     * Scope par année scolaire
     */
    public function scopeAnneeScolaire($query, string $annee)
    {
        return $query->where('annee_scolaire', $annee);
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

    /**
     * Scope par sexe
     */
    public function scopeSexe($query, string $sexe)
    {
        return $query->where('sexe', $sexe);
    }

    /**
     * Scope de recherche
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'LIKE', "%{$search}%")
              ->orWhere('prenoms', 'LIKE', "%{$search}%")
              ->orWhere('matricule', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Scope pour redoublants
     */
    public function scopeRedoublant($query)
    {
        return $query->where('redoublant', true);
    }

    /**
     * Obtenir le libellé du sexe
     */
    public function getSexeLibelleAttribute(): string
    {
        return $this->sexe === 'M' ? 'Masculin' : 'Féminin';
    }

    /**
     * Obtenir la couleur du statut
     */
    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'Actif' => 'success',
            'Inactif' => 'secondary',
            'Transfere' => 'info',
            'Abandonne' => 'danger',
            default => 'secondary'
        };
    }
}
