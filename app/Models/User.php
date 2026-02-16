<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'profile_photo',
        'etablissement_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Établissements auxquels l'utilisateur est rattaché
     */
    public function etablissements()
    {
        return $this->belongsToMany(Etablissement::class, 'etablissement_user')
                    ->withPivot(['role_etablissement', 'date_debut', 'date_fin', 'is_principal'])
                    ->withTimestamps();
    }

    /**
     * Établissement principal de l'utilisateur
     */
    public function etablissementPrincipal()
    {
        return $this->belongsToMany(Etablissement::class, 'etablissement_user')
                    ->wherePivot('is_principal', true)
                    ->withPivot(['role_etablissement', 'date_debut', 'date_fin'])
                    ->withTimestamps()
                    ->first();
    }

    /**
     * Photos prises par cet opérateur
     */
    public function photosRealisees()
    {
        return $this->hasMany(Photo::class, 'operateur_id');
    }

    /**
     * Photos validées par cet utilisateur
     */
    public function photosValidees()
    {
        return $this->hasMany(Photo::class, 'validateur_id');
    }

    /**
     * Cartes générées par cet utilisateur
     */
    public function cartesGenerees()
    {
        return $this->hasMany(CarteScolaire::class, 'generateur_id');
    }

    /**
     * Cartes imprimées par cet utilisateur
     */
    public function cartesImprimees()
    {
        return $this->hasMany(CarteScolaire::class, 'imprimeur_id');
    }

    /**
     * Notifications de l'utilisateur
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('date_notification', 'desc');
    }

    /**
     * Notifications non lues
     */
    public function notificationsNonLues()
    {
        return $this->hasMany(Notification::class)->where('est_lu', false);
    }

    /**
     * Actions effectuées par l'utilisateur
     */
    public function actions()
    {
        return $this->hasMany(Action::class)->orderBy('created_at', 'desc');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur est administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Administrateur';
    }

    /**
     * Vérifier si l'utilisateur est proviseur
     */
    public function isProviseur(): bool
    {
        return $this->role === 'Proviseur';
    }

    /**
     * Vérifier si l'utilisateur est surveillant général
     */
    public function isSurveillant(): bool
    {
        return $this->role === 'Surveillant General';
    }

    /**
     * Vérifier si l'utilisateur est opérateur photo
     */
    public function isOperateur(): bool
    {
        return $this->role === 'Operateur Photo';
    }

    /**
     * Obtenir le nom complet
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->nom} {$this->prenoms}";
    }

    /**
     * Obtenir l'URL de la photo de profil
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        // Avatar par défaut basé sur les initiales
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
