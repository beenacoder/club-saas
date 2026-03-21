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

    // public static function cobrar($socioId, $montoTotal)
    // {
    //     $socio = \App\Models\Socio::find($socioId);

    //     $pago = self::create([
    //         'club_id' => $socio->club_id,
    //         'socio_id' => $socioId,
    //         'monto' => $montoTotal,
    //         'fecha' => now(),
    //     ]);

    //     $cuotas = SocioCuota::where('socio_id', $socioId)
    //         ->whereIn('estado', ['pendiente', 'parcial'])
    //         ->whereColumn('monto_pagado', '<', 'monto')
    //         ->orderBy('fecha')
    //         ->get();

    //     foreach ($cuotas as $cuota) {

    //         if ($montoTotal <= 0) break;

    //         $montoAplicado = min($montoTotal, $cuota->monto - $cuota->monto_pagado);

    //         $pago->cuotas()->attach($cuota->id, [
    //             'monto' => $montoAplicado
    //         ]);

    //         $cuota->monto_pagado += $montoAplicado;

    //         // actualizar estado
    //         if ($cuota->monto_pagado >= $cuota->monto) {
    //             $cuota->estado = 'pagado';
    //         } elseif ($cuota->monto_pagado > 0) {
    //             $cuota->estado = 'parcial';
    //         }

    //         $cuota->save();

    //         $montoTotal -= $montoAplicado;
    //     }

    //     return $pago;
    // }


    public static function pagarCuotaEspecifica($socioId, $cuotaId)
    {
        $socio = \App\Models\Socio::find($socioId);

        $cuota = SocioCuota::where('id', $cuotaId)
            ->where('socio_id', $socioId)
            ->firstOrFail();

        $saldo = $cuota->monto - $cuota->monto_pagado;

        if ($saldo <= 0) {
            return null;
        }

        $pago = self::create([
            'club_id' => $socio->club_id,
            'socio_id' => $socioId,
            'monto' => $saldo,
            'fecha' => now(),
        ]);

        // relación
        $pago->cuotas()->attach($cuota->id, [
            'monto' => $saldo
        ]);

        // actualizar cuota
        $cuota->monto_pagado += $saldo;
        $cuota->estado = 'pagado';
        $cuota->save();

        return $pago;
    }

    public static function cobrar($socioId, $montoTotal)
    {
        $socio = \App\Models\Socio::find($socioId);

        $cuotas = SocioCuota::where('socio_id', $socioId)
            ->whereColumn('monto_pagado', '<', 'monto')
            ->orderBy('fecha')
            ->get();

        $deudaTotal = $cuotas->sum(function ($c) {
            return $c->monto - $c->monto_pagado;
        });

        if ($deudaTotal <= 0) {
            return null; // no hay deuda
        }

        $montoTotal = min($montoTotal, $deudaTotal);

        $pago = self::create([
            'club_id' => $socio->club_id,
            'socio_id' => $socioId,
            'monto' => $montoTotal,
            'fecha' => now(),
        ]);

        foreach ($cuotas as $cuota) {

            if ($montoTotal <= 0) break;

            $montoAplicado = min(
                $montoTotal,
                $cuota->monto - $cuota->monto_pagado
            );

            $pago->cuotas()->attach($cuota->id, [
                'monto' => $montoAplicado
            ]);

            $cuota->monto_pagado += $montoAplicado;

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
