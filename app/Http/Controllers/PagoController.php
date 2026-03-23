<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\SocioCuota;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info('Webhook completo', $request->all());

        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

        // 👇 detectar tipo
        $topic = $request->input('topic');

        if ($topic === 'payment') {

            $paymentId = $request->input('data.id');
        } elseif ($topic === 'merchant_order') {

            // viene como URL
            $resource = $request->input('resource');

            // extraer ID desde la URL
            $parts = explode('/', $resource);
            $merchantOrderId = end($parts);

            Log::info('Merchant Order ID', [$merchantOrderId]);

            // buscar pagos dentro de la orden
            $client = new \MercadoPago\Client\MerchantOrder\MerchantOrderClient();
            $order = $client->get($merchantOrderId);

            if (empty($order->payments)) {
                return response()->json(['ok' => true]);
            }

            $paymentId = $order->payments[0]->id;
        } else {
            return response()->json(['ok' => true]);
        }

        if (!$paymentId) {
            Log::info('No hay paymentId');
            return response()->json(['ok' => true]);
        }

        // 👇 buscar pago real
        $client = new \MercadoPago\Client\Payment\PaymentClient();
        $payment = $client->get($paymentId);

        Log::info('Pago MP', [
            'status' => $payment->status,
            'external_reference' => $payment->external_reference
        ]);

        if ($payment->status !== 'approved') {
            return response()->json(['ok' => true]);
        }

        $cuota = SocioCuota::find($payment->external_reference);

        if (!$cuota || $cuota->estado === 'pagado') {
            return response()->json(['ok' => true]);
        }

        $saldo = $cuota->monto - $cuota->monto_pagado;

        // 🔴 REGLA DE NEGOCIO: ONLINE = TOTAL
        Pago::aplicarPago($cuota, $saldo, 'mp', $payment->id);

        if ($saldo > 0) {

            $pago = Pago::create([
                'club_id' => $cuota->socio->club_id,
                'socio_id' => $cuota->socio_id,
                'monto' => $payment->transaction_amount,
                'fecha' => now(),
                'tipo' => 'mp',
                'mp_payment_id' => $payment->id
            ]);

            // relación pivot (como ya usás)
            $pago->cuotas()->attach($cuota->id, [
                'monto' => $payment->transaction_amount
            ]);

            // actualizar acumulado
            $cuota->monto_pagado += $payment->transaction_amount;

            if ($cuota->monto_pagado >= $cuota->monto) {
                $cuota->estado = 'pagado';
            }

            $cuota->save();

            Log::info('Cuota pagada OK (MP)');
        }

        Log::info('Cuota pagada OK');

        return response()->json(['ok' => true]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'cuota_id' => 'required|exists:socio_cuotas,id',
    //         'monto' => 'required|numeric|min:1'
    //     ]);

    //     $cuota = SocioCuota::with('pagos')->findOrFail($request->cuota_id);

    //     // calcular saldo real
    //     $pagado = $cuota->pagos->sum('monto');
    //     $saldo = $cuota->monto - $pagado;

    //     // 🔴 VALIDACIONES CLAVE
    //     if ($saldo <= 0) {
    //         return back()->with('error', 'La cuota ya está pagada');
    //     }

    //     if ($request->monto > $saldo) {
    //         return back()->with('error', 'No puede pagar más que el saldo');
    //     }

    //     $saldo = $cuota->monto - $cuota->monto_pagado;

    //     if ($request->monto > $saldo) {
    //         return back()->with('error', 'No puede pagar más que el saldo');
    //     }

    //     $pago = Pago::create([
    //         'club_id' => $cuota->socio->club_id,
    //         'socio_id' => $cuota->socio_id,
    //         'monto' => $request->monto,
    //         'fecha' => now(),
    //         'tipo' => 'manual'
    //     ]);

    //     $pago->cuotas()->attach($cuota->id, [
    //         'monto' => $request->monto
    //     ]);

    //     $cuota->monto_pagado += $request->monto;

    //     if ($cuota->monto_pagado >= $cuota->monto) {
    //         $cuota->estado = 'pagado';
    //     }

    //     $cuota->save();

    //     // actualizar estado
    //     if ($request->monto == $saldo) {
    //         $cuota->estado = 'pagado';
    //         $cuota->save();
    //     }

    //     return back()->with('success', 'Pago registrado');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'cuota_id' => 'required|exists:socio_cuotas,id',
            'monto' => 'required|numeric|min:1'
        ]);

        $cuota = SocioCuota::findOrFail($request->cuota_id);

        Pago::aplicarPago($cuota, $request->monto, 'manual');

        return back()->with('success', 'Pago registrado');
    }
}
