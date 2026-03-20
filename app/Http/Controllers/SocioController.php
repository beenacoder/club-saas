<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;


class SocioController extends Controller
{


    public function index(Request $request)
    {
        $socios = Socio::where('club_id', $request->user()->club_id)->get();

        return view('socios.index', compact('socios'));
    }


    public function create()
    {
        return view('socios.create');
    }


    public function store(Request $request)
    {
        Socio::create([
            'club_id' => $request->user()->club_id,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'estado' => 'activo',
        ]);

        return redirect()->route('socios.index');
    }




    public function show(Socio $socio, Request $request)
    {
        // seguridad: que pertenezca al club
        if ($socio->club_id !== $request->user()->club_id) {
            abort(403);
        }

        // cargar relaciones
        $socio->load([
            'cuotas',
            'pagos'
        ]);

        // calcular deuda
        $deuda = $socio->cuotas->sum(function ($c) {
            return $c->monto - ($c->monto_pagado ?? 0);
        });

        // ordenar cuotas por fecha
        $cuotas = $socio->cuotas->sortBy('fecha');

        return view('socios.show', compact('socio', 'deuda', 'cuotas'));
    }

    public function pagar(Request $request, Socio $socio)
    {
        // seguridad
        if ($socio->club_id !== $request->user()->club_id) {
            abort(403);
        }

        $request->validate([
            'monto' => 'required|numeric|min:1'
        ]);

        \App\Models\Pago::cobrar($socio->id, $request->monto);

        return redirect()
            ->route('socios.show', $socio->id)
            ->with('success', 'Pago registrado correctamente');
    }


    public function edit(Socio $socio)
    {
        //
    }


    public function update(Request $request, Socio $socio)
    {
        //
    }

    public function destroy(Socio $socio)
    {
        //
    }
}
