<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $table = 'materiel';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Get the taches that require this materiel.
     */
    public function taches()
    {
        return $this->belongsToMany(
            Tache::class,
            'tachemateriel',
            'materielId',
            'tacheId'
        )->withPivot('quantite')
            ->withTimestamps();
    }

    /**
     * Get the intervenants who own this materiel.
     */
    public function intervenants()
    {
        return $this->belongsToMany(
            Intervenant::class,
            'intervenantmateriel',
            'materielId',
            'intervenantId'
        )->withTimestamps();
    }

    /**
     * Get the interventions that use this materiel.
     */
    public function interventions()
    {
        return $this->belongsToMany(
            Intervention::class,
            'interventionmateriel',
            'materielId',
            'interventionId'
        )->withTimestamps();
    }
}
