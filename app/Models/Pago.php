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
        return $this->belongsToMany(
            SocioCuota::class,
            'pago_socio_cuota',
            'pago_id',
            'socio_cuota_id' // ✅ CORREGIDO
        )->withPivot('monto')->withTimestamps();
    }

    public static function aplicarPago(SocioCuota $cuota, float $monto, string $metodo, $mpPaymentId = null)
    {
        $saldo = $cuota->monto - $cuota->monto_pagado;

        if ($saldo <= 0) {
            throw new \Exception('Cuota ya pagada');
        }

        if ($monto > $saldo) {
            throw new \Exception('Monto mayor al saldo');
        }

        $pago = self::create([
            'club_id' => $cuota->club_id,
            'socio_id' => $cuota->socio_id,
            'monto' => $monto,
            'fecha' => now(),
            'metodo' => $metodo,
        ]);

        $pago->cuotas()->attach($cuota->id, [
            'monto' => $monto
        ]);

        // actualizar cuota
        $cuota->monto_pagado += $monto;

        if ($cuota->monto_pagado >= $cuota->monto) {
            $cuota->estado = 'pagado';
        } else {
            $cuota->estado = 'parcial';
        }

        $cuota->save();

        return $pago;
    }

    // public static function pagarCuota($cuota, $monto, $tipo = 'manual', $mpPaymentId = null)
    // {
    //     $pagado = $cuota->pagos()->sum('monto');
    //     $saldo = $cuota->monto - $pagado;

    //     if ($saldo <= 0) {
    //         return null;
    //     }

    //     if ($monto > $saldo) {
    //         throw new \Exception('Monto mayor al saldo');
    //     }

    //     $pago = self::create([
    //         // 'cuota_id' => $cuota->id,
    //         'monto' => $monto,
    //         'fecha' => now(),
    //         'tipo' => $tipo,
    //         'mp_payment_id' => $mpPaymentId,
    //     ]);

    //     // actualizar estado
    //     if ($monto == $saldo) {
    //         $cuota->estado = 'pagado';
    //     } else {
    //         $cuota->estado = 'parcial';
    //     }

    //     $cuota->save();

    //     return $pago;
    // }

    // public static function cobrar($socioId, $montoTotal)
    // {
    //     $socio = \App\Models\Socio::find($socioId);

    //     $cuotas = SocioCuota::where('socio_id', $socioId)
    //         ->whereColumn('monto_pagado', '<', 'monto')
    //         ->orderBy('fecha')
    //         ->get();

    //     $deudaTotal = $cuotas->sum(function ($c) {
    //         return $c->monto - $c->monto_pagado;
    //     });

    //     if ($deudaTotal <= 0) {
    //         return null; // no hay deuda
    //     }

    //     $montoTotal = min($montoTotal, $deudaTotal);

    //     $pago = self::create([
    //         'club_id' => $socio->club_id,
    //         'socio_id' => $socioId,
    //         'monto' => $montoTotal,
    //         'fecha' => now(),
    //     ]);

    //     foreach ($cuotas as $cuota) {

    //         if ($montoTotal <= 0) break;

    //         $montoAplicado = min(
    //             $montoTotal,
    //             $cuota->monto - $cuota->monto_pagado
    //         );

    //         $pago->cuotas()->attach($cuota->id, [
    //             'monto' => $montoAplicado
    //         ]);

    //         $cuota->monto_pagado += $montoAplicado;

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
}
