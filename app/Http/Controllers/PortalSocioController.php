<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

use Illuminate\Http\Request;

class PortalSocioController extends Controller
{
    public function show($token)
    {
        $socio = Socio::where('token', $token)
            ->with(['cuotas', 'pagos'])
            ->firstOrFail();

        $cuotas = $socio->cuotas->sortBy('fecha');

        $deuda = $cuotas->sum(function ($c) {
            return $c->monto - ($c->monto_pagado ?? 0);
        });

        return view('portal.socio', compact('socio', 'cuotas', 'deuda'));
    }

    public function pagarCuota($token, $cuotaId)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        $socio = Socio::where('token', $token)->firstOrFail();

        $cuota = \App\Models\SocioCuota::where('id', $cuotaId)
            ->where('socio_id', $socio->id)
            ->firstOrFail();

        $saldo = $cuota->monto - $cuota->monto_pagado;

        if ($saldo <= 0) {
            return back()->with('error', 'La cuota ya está pagada');
        }

        $client = new PreferenceClient();

        try {
        $preference = $client->create([
            "items" => [
                [
                    "title" => "Cuota " . $cuota->fecha,
                    "quantity" => 1,
                    "unit_price" => (float)$saldo
                ]
            ],
            "back_urls" => [
                "success" => route('portal.success'),
                "failure" => route('portal.failure'),
                "pending" => route('portal.pending'),
            ],
            "notification_url" => 'https://nongospel-lavonda-uncurtailably.ngrok-free.dev/webhook/mercadopago',
            "external_reference" => $cuota->id
        ]);

        return redirect($preference->init_point);
        } catch (MPApiException$e) {
            dd($e->getApiResponse()->getContent());
        }
    }
}
