<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    use HasFactory;

    protected $table = 'partitiontable';
    protected $primaryKey = 'idPartition';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'idIntervenant',
        'jour',
        'status',
        'heureDepart',
        'heureFin',
    ];

    protected function casts(): array
    {
        return [
            'jour' => 'date',
            'heureDepart' => 'datetime:H:i',
            'heureFin' => 'datetime:H:i',
        ];
    }

    /**
     * Get the intervenant that owns this disponibilite.
     */
    public function intervenant()
    {
        return $this->belongsTo(Intervenant::class, 'idIntervenant', 'idIntervenant');
    }
}
