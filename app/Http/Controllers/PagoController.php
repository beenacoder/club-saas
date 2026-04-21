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
