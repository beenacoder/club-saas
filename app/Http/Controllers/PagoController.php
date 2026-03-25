<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\SocioCuota;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use App\Services\PagoService;

class PagoController extends Controller
{
    // public function webhook(Request $request, PagoService $pagoService)
    // {
    //     Log::info('Webhook completo', $request->all());

    //     MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

    //     // 👇 detectar tipo
    //     $topic = $request->input('topic');

    //     if ($topic === 'payment') {

    //         $paymentId = $request->input('data.id');
    //     } elseif ($topic === 'merchant_order') {

    //         // viene como URL
    //         $resource = $request->input('resource');

    //         // extraer ID desde la URL
    //         $parts = explode('/', $resource);
    //         $merchantOrderId = end($parts);

    //         Log::info('Merchant Order ID', [$merchantOrderId]);

    //         // buscar pagos dentro de la orden
    //         $client = new \MercadoPago\Client\MerchantOrder\MerchantOrderClient();
    //         $order = $client->get($merchantOrderId);

    //         if (empty($order->payments)) {
    //             return response()->json(['ok' => true]);
    //         }

    //         $paymentId = $order->payments[0]->id;
    //     } else {
    //         return response()->json(['ok' => true]);
    //     }

    //     if (!$paymentId) {
    //         Log::info('No hay paymentId');
    //         return response()->json(['ok' => true]);
    //     }

    //     // 👇 buscar pago real
    //     $client = new \MercadoPago\Client\Payment\PaymentClient();
    //     $payment = $client->get($paymentId);

    //     Log::info('Pago MP', [
    //         'status' => $payment->status,
    //         'external_reference' => $payment->external_reference
    //     ]);

    //     if ($payment->status !== 'approved') {
    //         return response()->json(['ok' => true]);
    //     }

    //     $cuota = SocioCuota::find($payment->external_reference);

    //     if (!$cuota || $cuota->estado === 'pagado') {
    //         return response()->json(['ok' => true]);
    //     }

    //     $saldo = $cuota->monto - $cuota->monto_pagado;

    //     // 🔴 REGLA DE NEGOCIO: ONLINE = TOTAL
    //     $pagoService->aplicarPago($cuota, $saldo, 'mp', $payment->id);

    //     // if ($saldo > 0) {

    //     //     $pago = Pago::create([
    //     //         'club_id' => $cuota->socio->club_id,
    //     //         'socio_id' => $cuota->socio_id,
    //     //         'monto' => $payment->transaction_amount,
    //     //         'fecha' => now(),
    //     //         'tipo' => 'mp',
    //     //         'mp_payment_id' => $payment->id
    //     //     ]);

    //     //     // relación pivot (como ya usás)
    //     //     $pago->cuotas()->attach($cuota->id, [
    //     //         'monto' => $payment->transaction_amount
    //     //     ]);

    //     //     // actualizar acumulado
    //     //     $cuota->monto_pagado += $payment->transaction_amount;

    //     //     if ($cuota->monto_pagado >= $cuota->monto) {
    //     //         $cuota->estado = 'pagado';
    //     //     }

    //     //     $cuota->save();

    //     //     Log::info('Cuota pagada OK (MP)');
    //     // }

    //     Log::info('Cuota pagada OK');

    //     return response()->json(['ok' => true]);
    // }

    public function webhook(Request $request, PagoService $pagoService)
    {
        Log::info('Webhook completo', $request->all());

        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));

        $topic = $request->input('topic');

        if ($topic === 'payment') {

            $paymentId = $request->input('data.id');
        } elseif ($topic === 'merchant_order') {

            $resource = $request->input('resource');
            $parts = explode('/', $resource);
            $merchantOrderId = end($parts);

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
            return response()->json(['ok' => true]);
        }

        $client = new PaymentClient();
        $payment = $client->get($paymentId);

        if ($payment->status !== 'approved') {
            return response()->json(['ok' => true]);
        }

        $cuota = SocioCuota::find($payment->external_reference);

        if (!$cuota || $cuota->estado === 'pagado') {
            return response()->json(['ok' => true]);
        }

        // 🔥 REGLA: ONLINE = SOLO TOTAL
        $saldo = $cuota->monto - $cuota->monto_pagado;

        if ($saldo <= 0) {
            return response()->json(['ok' => true]);
        }

        if (Pago::where('mp_payment_id', $payment->id)->exists()) {
            return response()->json(['ok' => true]);
        }

        // 🔥 TODO PASA POR EL SERVICE
        $pagoService->aplicarPago(
            $cuota,
            $saldo,
            'mp',
            $payment->id
        );

        Log::info('Pago aplicado correctamente (MP)', [
            'cuota_id' => $cuota->id,
            'payment_id' => $payment->id
        ]);

        return response()->json(['ok' => true]);
    }

    public function store(Request $request, PagoService $pagoService)
    {
        $request->validate([
            'cuota_id' => 'required|exists:socio_cuotas,id',
            'monto' => 'required|numeric|min:1'
        ]);

        $cuota = SocioCuota::findOrFail($request->cuota_id);

        $pagoService->aplicarPago($cuota, $request->monto, 'manual');

        return back()->with('success', 'Pago registrado');
    }
}
