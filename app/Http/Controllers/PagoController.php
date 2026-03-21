<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\SocioCuota;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        $paymentId = $request->input('data.id');

        if (!$paymentId) return response()->json(['ok' => true]);

        $client = new PaymentClient();
        $payment = $client->get($paymentId);

        if ($payment->status !== 'approved') {
            return response()->json(['ok' => true]);
        }

        $cuotaId = $payment->external_reference;

        $cuota = SocioCuota::find($cuotaId);

        if (!$cuota) return response()->json(['ok' => true]);

        // evitar duplicados
        if ($cuota->estado === 'pagado') {
            return response()->json(['ok' => true]);
        }

        Pago::pagarCuotaEspecifica($cuota->socio_id, $cuota->id);

        return response()->json(['ok' => true]);
    }
}
