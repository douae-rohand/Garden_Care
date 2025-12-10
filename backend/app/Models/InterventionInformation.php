<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InterventionInformation extends Pivot
{
    use HasFactory;

    protected $table = 'interventioninformation';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'interventionId',
        'informationId',
        'valeur',
    ];

    /**
     * Get the intervention that owns this record.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class, 'interventionId', 'id');
    }

    /**
     * Get the information associated with this record.
     */
    public function information()
    {
        return $this->belongsTo(Information::class, 'informationId', 'id');
    }
}
