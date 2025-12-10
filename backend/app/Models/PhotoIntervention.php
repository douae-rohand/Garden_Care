<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoIntervention extends Model
{
    use HasFactory;

    protected $table = 'photointervention';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'interventionId',
        'url',
        'description',
    ];

    /**
     * Get the intervention that owns this photo.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class, 'interventionId', 'id');
    }
}
