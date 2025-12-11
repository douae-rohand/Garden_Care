<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificatif extends Model
{
    use HasFactory;

    protected $table = 'justificatif';
    protected $primaryKey = 'idJustificatif';
    public $timestamps = false;

    const CREATED_AT = 'createdAt';

    protected $fillable = [
        'cheminFichier',
        'type',
        'idMateriel',
    ];

    /**
     * Get the materiel that owns this justificatif.
     */
    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'idMateriel', 'idMateriel');
    }

    /**
     * Get the intervenant services using this justificatif.
     */
    public function intervenantServices()
    {
        return $this->hasMany(IntervenantService::class, 'idJustificatif', 'idJustificatif');
    }
}
