<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IntervenantMateriel extends Pivot
{
    use HasFactory;

    protected $table = 'intervenantmateriel';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'intervenantId',
        'materielId',
        'quantite',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
        ];
    }

    /**
     * Get the intervenant that owns this record.
     */
    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class, 'intervenantId', 'id');
    }

    /**
     * Get the materiel associated with this record.
     */
    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materielId', 'id');
    }
}
