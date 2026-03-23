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

        $cuotaId = $payment->external_reference;

        $cuota = \App\Models\SocioCuota::find($cuotaId);

        if (!$cuota) {
            Log::info('Cuota no encontrada');
            return response()->json(['ok' => true]);
        }

        if ($cuota->estado === 'pagado') {
            return response()->json(['ok' => true]);
        }

        \App\Models\Pago::pagarCuotaEspecifica($cuota->socio_id, $cuota->id);

        Log::info('Cuota pagada OK');

        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cuota_id' => 'required|exists:socio_cuotas,id',
            'monto' => 'required|numeric|min:1'
        ]);

        $cuota = SocioCuota::with('pagos')->findOrFail($request->cuota_id);

        // calcular saldo real
        $pagado = $cuota->pagos->sum('monto');
        $saldo = $cuota->monto - $pagado;

        // 🔴 VALIDACIONES CLAVE
        if ($saldo <= 0) {
            return back()->with('error', 'La cuota ya está pagada');
        }

        if ($request->monto > $saldo) {
            return back()->with('error', 'No puede pagar más que el saldo');
        }

        // 💰 registrar pago
        Pago::create([
            'cuota_id' => $cuota->id,
            'monto' => $request->monto,
            'tipo' => 'manual' // 🔥 importante
        ]);

        // actualizar estado
        if ($request->monto == $saldo) {
            $cuota->estado = 'pagado';
            $cuota->save();
        }

        return back()->with('success', 'Pago registrado');
    }
}
