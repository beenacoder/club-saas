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
    // public function webhook(Request $request)
    // {
    //     $paymentId = $request->input('data.id');

    //     if (!$paymentId) return response()->json(['ok' => true]);

    //     $client = new PaymentClient();
    //     $payment = $client->get($paymentId);

    //     if ($payment->status !== 'approved') {
    //         return response()->json(['ok' => true]);
    //     }

    //     $cuotaId = $payment->external_reference;

    //     $cuota = SocioCuota::find($cuotaId);

    //     if (!$cuota) return response()->json(['ok' => true]);

    //     // evitar duplicados
    //     if ($cuota->estado === 'pagado') {
    //         return response()->json(['ok' => true]);
    //     }

    //     Pago::pagarCuotaEspecifica($cuota->socio_id, $cuota->id);
    //     return response()->json(['ok' => true]);
    // }

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
}
