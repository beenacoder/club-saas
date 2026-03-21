<?php

namespace App\Http\Controllers;

use App\Models\Socio;

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
        $socio = Socio::where('token', $token)->firstOrFail();

        $cuota = \App\Models\SocioCuota::where('id', $cuotaId)
            ->where('socio_id', $socio->id)
            ->firstOrFail();

        $saldo = $cuota->monto - $cuota->monto_pagado;

        if ($saldo <= 0) {
            return back()->with('error', 'La cuota ya está pagada');
        }

        \App\Models\Pago::pagarCuotaEspecifica($socio->id, $cuotaId);

        return back()->with('success', 'Cuota pagada correctamente');
    }
}
