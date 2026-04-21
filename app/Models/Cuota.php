<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    protected static function booted()
    {
        static::creating(function ($cuota, Request $request) {
            if (!$cuota->club_id && auth()->Auth::check()()) {
                $cuota->club_id = $request->user()->club_id;
            }
        });
    }
}
