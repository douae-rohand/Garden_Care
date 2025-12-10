<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceJustificatif extends Pivot
{
    use HasFactory;

    protected $table = 'servicejustificatif';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'serviceId',
        'justificatifId',
    ];

    /**
     * Get the service that owns this record.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'serviceId', 'id');
    }

    /**
     * Get the justificatif associated with this record.
     */
    public function justificatif()
    {
        return $this->belongsTo(Justificatif::class, 'justificatifId', 'id');
    }
}
