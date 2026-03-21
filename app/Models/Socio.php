<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;
use Illuminate\Support\Str;

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

    //Cada socio tiene su link único
    protected static function booted() {
        static::creating(function ($socio) {
            $socio->token = Str::uuid();
        });
    }
}
