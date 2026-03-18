<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    protected $fillable = [
        'club_id',
        'nombre',
        'email',
        'telefono',
        'estado',
    ];
}
