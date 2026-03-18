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
 dd($request->user());
    return redirect()->route('socios.index');

    }


    public function show(Socio $socio)
    {
        //
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
