<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = [
        'club_id',
        'nombre',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($actividad) {

            if (empty($actividad->slug)) {
                $actividad->slug = Str::slug($actividad->nombre);
            }

            // 🔥 CLAVE
            if (!$actividad->club_id && auth()->check()) {
                $actividad->club_id = auth()->user()->club_id;
            }
        });

        static::updating(function ($actividad) {
            $actividad->slug = Str::slug($actividad->nombre);
        });
    }

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

    // 👇 CLAVE para rutas por slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function resolveRouteBinding($value, $field = null)
    // {
    //     return $this->where('slug', $value)
    //         ->where('club_id', auth()->user()->club_id)
    //         ->firstOrFail();
    // }
}
