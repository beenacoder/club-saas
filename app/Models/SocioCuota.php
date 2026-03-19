<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocioCuota extends Model
{
    protected $table = 'socio_cuotas';

    protected $fillable = [
        'club_id',
        'socio_id',
        'cuota_id',
        'fecha',
        'monto',
        'estado',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function cuota()
    {
        return $this->belongsTo(Cuota::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
