<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $fillable = [
        'club_id',
        'actividad_id',
        'nombre',
        'monto',
        'frecuencia',
    ];

    public function actividad()
    {
        return $this->belongsTo(Actividad::class);
    }
}
