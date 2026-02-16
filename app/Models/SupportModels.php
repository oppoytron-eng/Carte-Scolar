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

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'data',
        'priorite',
        'date_notification',
        'est_lu',
        'date_lecture',
        'lien',
        'icone',
    ];

    protected $casts = [
        'date_notification' => 'datetime',
        'date_lecture' => 'datetime',
        'est_lu' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marquerCommeLu()
    {
        if (!$this->est_lu) {
            $this->est_lu = true;
            $this->date_lecture = now();
            $this->save();
        }
    }

    public function scopeNonLu($query)
    {
        return $query->where('est_lu', false);
    }

    public function scopePriorite($query, string $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    public function scopeRecent($query, int $jours = 7)
    {
        return $query->where('date_notification', '>=', now()->subDays($jours));
    }
}

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_action',
        'module',
        'description',
        'entite_type',
        'entite_id',
        'donnees_avant',
        'donnees_apres',
        'adresse_ip',
        'user_agent',
        'statut',
        'message_erreur',
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type_action', $type);
    }

    public function scopeModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeEntite($query, string $type, int $id)
    {
        return $query->where('entite_type', $type)
                    ->where('entite_id', $id);
    }

    public function scopeRecent($query, int $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    public function scopeSucces($query)
    {
        return $query->where('statut', 'Succes');
    }

    public function scopeEchec($query)
    {
        return $query->where('statut', 'Echec');
    }

    public function getIconeAttribute(): string
    {
        return match($this->type_action) {
            'create' => 'fa-plus-circle',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'view' => 'fa-eye',
            'download' => 'fa-download',
            'upload' => 'fa-upload',
            'print' => 'fa-print',
            'export' => 'fa-file-export',
            'import' => 'fa-file-import',
            default => 'fa-info-circle'
        };
    }
}

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
