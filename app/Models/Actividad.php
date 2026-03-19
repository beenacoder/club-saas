<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = [
        'club_id',
        'nombre',
    ];

    // 🔗 RELACIONES

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }

    public function socios()
    {
        return $this->belongsToMany(Socio::class, 'socio_actividad');
    }
}
