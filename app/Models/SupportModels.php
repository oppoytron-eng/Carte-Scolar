<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCarte extends Model
{
    use HasFactory;

    protected $table = 'medias_carte';

    protected $fillable = [
        'carte_scolaire_id',
        'nom',
        'description',
        'type',
        'chemin',
        'format',
        'taille',
        'ordre',
    ];

    protected $casts = [
        'taille' => 'integer',
        'ordre' => 'integer',
    ];

    public function carteScolaire()
    {
        return $this->belongsTo(CarteScolaire::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin);
    }
}

// Note: Notification and Action models are in their own files (Notification.php, Action.php)

class Horaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'classe_id',
        'jour_semaine',
        'heure_debut',
        'heure_fin',
        'activite',
        'detail',
    ];

    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function scopeJour($query, string $jour)
    {
        return $query->where('jour_semaine', $jour);
    }

    public function getDureeAttribute(): string
    {
        $debut = \Carbon\Carbon::parse($this->heure_debut);
        $fin = \Carbon\Carbon::parse($this->heure_fin);
        
        $minutes = $debut->diffInMinutes($fin);
        $heures = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($heures > 0) {
            return "{$heures}h" . ($mins > 0 ? "{$mins}min" : '');
        }
        return "{$mins}min";
    }
}
