<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaire';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'contenu',
        'interventionId',
    ];

    /**
     * Get the intervention that owns this commentaire.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class, 'interventionId', 'id');
    }
}
