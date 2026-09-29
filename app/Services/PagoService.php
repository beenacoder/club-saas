<?php

namespace App\Services;

use App\Events\PagoRegistrado;
use App\Models\Pago;
use App\Models\SocioCuota;
use Illuminate\Support\Facades\DB;

class PagoService
{
    public function aplicarPago(SocioCuota $cuota, float $monto, string $metodo, $mpPaymentId = null): Pago
    {
        return DB::transaction(function () use ($cuota, $monto, $metodo, $mpPaymentId) {

            $cuota->refresh();

            $saldo = $cuota->monto - $cuota->monto_pagado;

            if ($saldo <= 0) {
                throw new \Exception('Cuota ya pagada');
            }

            if ($monto > $saldo) {
                throw new \Exception('Monto mayor al saldo');
            }

            $pago = Pago::create([
                'club_id' => $cuota->club_id,
                'socio_id' => $cuota->socio_id,
                'monto' => $monto,
                'fecha' => now(),
                'metodo' => $metodo,
            ]);

            $pago->cuotas()->attach($cuota->id, [
                'monto' => $monto
            ]);

            $cuota->monto_pagado += $monto;

            if ($cuota->monto_pagado >= $cuota->monto) {
                $cuota->estado = 'pagado';
                $cuota->fecha_pago = now();
            } else {
                $cuota->estado = 'parcial';
            }

            $cuota->save();

            event(new PagoRegistrado($pago));

            return $pago;
        });
    }
}
