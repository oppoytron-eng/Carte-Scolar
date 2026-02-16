<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Etablissement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'localisation',
        'ville',
        'commune',
        'type',
        'grade',
        'code_etablissement',
        'logo',
        'telephone',
        'email',
        'adresse',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Utilisateurs rattachés à l'établissement
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'etablissement_user')
                    ->withPivot(['role_etablissement', 'date_debut', 'date_fin', 'is_principal'])
                    ->withTimestamps();
    }

    /**
     * Proviseur de l'établissement
     */
    public function proviseur()
    {
        return $this->belongsToMany(User::class, 'etablissement_user')
                    ->wherePivot('role_etablissement', 'Proviseur')
                    ->first();
    }

    /**
     * Surveillants généraux de l'établissement
     */
    public function surveillants()
    {
        return $this->belongsToMany(User::class, 'etablissement_user')
                    ->wherePivot('role_etablissement', 'Surveillant_General');
    }

    /**
     * Opérateurs photo de l'établissement
     */
    public function operateurs()
    {
        return $this->belongsToMany(User::class, 'etablissement_user')
                    ->wherePivot('role_etablissement', 'Operateur_Photo');
    }

    /**
     * Classes de l'établissement
     */
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    /**
     * Classes actives
     */
    public function classesActives()
    {
        return $this->hasMany(Classe::class)->where('is_active', true);
    }

    /**
     * Élèves de l'établissement
     */
    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    /**
     * Élèves actifs
     */
    public function elevesActifs()
    {
        return $this->hasMany(Eleve::class)->where('statut', 'Actif');
    }

    /**
     * Cartes scolaires de l'établissement
     */
    public function cartesScolaires()
    {
        return $this->hasMany(CarteScolaire::class);
    }

    /**
     * Obtenir l'URL du logo
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        return asset('images/default-school-logo.png');
    }

    /**
     * Obtenir le nombre total d'élèves
     */
    public function getTotalElevesAttribute(): int
    {
        return $this->eleves()->count();
    }

    /**
     * Obtenir le nombre d'élèves actifs
     */
    public function getTotalElevesActifsAttribute(): int
    {
        return $this->elevesActifs()->count();
    }

    /**
     * Obtenir le nombre de classes
     */
    public function getTotalClassesAttribute(): int
    {
        return $this->classes()->count();
    }

    /**
     * Obtenir le nombre de cartes générées
     */
    public function getTotalCartesGenereeAttribute(): int
    {
        return $this->cartesScolaires()
                    ->where('statut', 'Carte_generee')
                    ->orWhere('statut', 'Carte_imprimee')
                    ->orWhere('statut', 'Carte_distribuee')
                    ->count();
    }

    /**
     * Scope pour établissements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope par type d'établissement
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope par grade (public/privé)
     */
    public function scopeGrade($query, string $grade)
    {
        return $query->where('grade', $grade);
    }

    /**
     * Scope par ville
     */
    public function scopeVille($query, string $ville)
    {
        return $query->where('ville', $ville);
    }
}
