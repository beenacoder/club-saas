<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocioCuota;

class Pago extends Model
{
    protected $fillable = [
        'club_id',
        'socio_id',
        'monto',
        'fecha',
        'metodo',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    public function cuotas()
    {
        return $this->belongsToMany(SocioCuota::class, 'pago_socio_cuota')
            ->withPivot('monto')
            ->withTimestamps();
    }

    public static function cobrar($socioId, $montoTotal)
    {
        $socio = \App\Models\Socio::find($socioId);

        $pago = self::create([
            'club_id' => $socio->club_id,
            'socio_id' => $socioId,
            'monto' => $montoTotal,
            'fecha' => now(),
        ]);

        $cuotas = SocioCuota::where('socio_id', $socioId)
            ->whereIn('estado', ['pendiente', 'parcial'])
            ->whereColumn('monto_pagado', '<', 'monto')
            ->orderBy('fecha')
            ->get();

        foreach ($cuotas as $cuota) {

            if ($montoTotal <= 0) break;

            $montoAplicado = min($montoTotal, $cuota->monto - $cuota->monto_pagado);

            $pago->cuotas()->attach($cuota->id, [
                'monto' => $montoAplicado
            ]);

            $cuota->monto_pagado += $montoAplicado;

            // actualizar estado
            if ($cuota->monto_pagado >= $cuota->monto) {
                $cuota->estado = 'pagado';
            } elseif ($cuota->monto_pagado > 0) {
                $cuota->estado = 'parcial';
            }

            $cuota->save();

            $montoTotal -= $montoAplicado;
        }

        return $pago;
    }
}
