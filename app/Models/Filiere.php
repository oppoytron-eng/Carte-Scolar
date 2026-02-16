<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'niveau',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Classes de cette filière
     */
    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    /**
     * Scope pour filières actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}