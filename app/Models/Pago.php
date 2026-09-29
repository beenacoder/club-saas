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

    // public static function aplicarPago(SocioCuota $cuota, float $monto, string $metodo, $mpPaymentId = null)
    // {
    //     $saldo = $cuota->monto - $cuota->monto_pagado;

    //     if ($saldo <= 0) {
    //         throw new \Exception('Cuota ya pagada');
    //     }

    //     if ($monto > $saldo) {
    //         throw new \Exception('Monto mayor al saldo');
    //     }

    //     $pago = self::create([
    //         'club_id' => $cuota->club_id,
    //         'socio_id' => $cuota->socio_id,
    //         'monto' => $monto,
    //         'fecha' => now(),
    //         'metodo' => $metodo,
    //     ]);

    //     $pago->cuotas()->attach($cuota->id, [
    //         'monto' => $monto
    //     ]);

    //     // actualizar cuota
    //     $cuota->monto_pagado += $monto;

    //     if ($cuota->monto_pagado >= $cuota->monto) {
    //         $cuota->estado = 'pagado';
    //     } else {
    //         $cuota->estado = 'parcial';
    //     }

    //     $cuota->save();

    //     return $pago;
    //}
}
