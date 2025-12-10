<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Intervenant extends Model
{
    use HasFactory;

    protected $table = 'intervenant';

    protected $primaryKey = 'id';

    public $incrementing = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'address',
        'ville',
        'bio',
        'isActive',
        'adminId',
    ];

    protected function casts(): array
    {
        return [
            'isActive' => 'boolean',
        ];
    }

    /**
     * Get the utilisateur record associated with the intervenant.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id', 'id');
    }

    /**
     * Get the admin that manages this intervenant.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminId', 'id');
    }

    /**
     * Get the interventions for this intervenant.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'intervenantId', 'id');
    }

    /**
     * Get the disponibilites for this intervenant.
     */
    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class, 'intervenantId', 'id');
    }

    /**
     * Get the taches that this intervenant can perform.
     */
    public function taches()
    {
        return $this->belongsToMany(
            Tache::class,
            'intervenanttache',
            'intervenantId',
            'tacheId'
        )->withPivot('tarif', 'experience')
            ->withTimestamps();
    }

    /**
     * Get the materiels owned by this intervenant.
     */
    public function materiels()
    {
        return $this->belongsToMany(
            Materiel::class,
            'intervenantmateriel',
            'intervenantId',
            'materielId'
        )->withTimestamps();
    }

    /**
     * Get the clients who favorited this intervenant.
     */
    public function clientsFavoris()
    {
        return $this->belongsToMany(
            Client::class,
            'favorise',
            'intervenantId',
            'clientId'
        )->withTimestamps();
    }

    /**
     * Scope a query to only include active intervenants.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('isActive', true);
    }

    /**
     * Scope a query to only include inactive intervenants.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('isActive', false);
    }
}
