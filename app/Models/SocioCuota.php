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

    public function pagar()
    {
        $this->estado = 'pagado';
        $this->fecha_pago = now();
        $this->save();
    }

    // public function pagos()
    // {
    //     return $this->belongsToMany(Pago::class, 'pago_socio_cuota')
    //         ->withPivot('monto')
    //         ->withTimestamps();
    // }

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

    public function pagos()
    {
        return $this->belongsToMany(
            Pago::class,
            'pago_socio_cuota',
            'socio_cuota_id',
            'pago_id'
        )->withPivot('monto')->withTimestamps();
    }
}
