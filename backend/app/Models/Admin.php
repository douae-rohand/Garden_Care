<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'idAdmin';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idAdmin',
    ];

    /**
     * Get the utilisateur record associated with the admin.
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'idAdmin', 'idUtilisateur');
    }
}
