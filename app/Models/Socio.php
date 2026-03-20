<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;

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

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class, 'socio_actividad');
    }

    public function cuotas()
    {
        return $this->hasMany(SocioCuota::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
