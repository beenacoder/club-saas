<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Cuota;
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    // 🔥 helper para no repetir código
    private function getActividad($slug, Request $request)
    {
        return Actividad::where('slug', $slug)
            ->where('club_id', $request->user()->club_id)
            ->firstOrFail();
    }

    public function index($actividad, Request $request)
    {
        $actividad = $this->getActividad($actividad, $request);

        $cuotas = $actividad->cuotas;

        return view('cuotas.index', compact('actividad', 'cuotas'));
    }

    public function create($actividad, Request $request)
    {
        $actividad = $this->getActividad($actividad, $request);

        return view('cuotas.create', compact('actividad'));
    }

    public function store(Request $request, $actividad)
    {
        $actividad = $this->getActividad($actividad, $request);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'monto' => 'required|numeric',
            'frecuencia' => 'required|string',
        ]);

        Cuota::create([
            'club_id' => $request->user()->club_id,
            'actividad_id' => $actividad->id,
            'nombre' => $request->nombre,
            'monto' => $request->monto,
            'frecuencia' => $request->frecuencia,
        ]);

        return redirect()
            ->route('actividades.cuotas.index', $actividad->slug)
            ->with('success', 'Cuota creada');
    }

    public function edit($actividad, $cuotaId, Request $request)
    {
        $actividad = $this->getActividad($actividad, $request);

        $cuota = Cuota::where('id', $cuotaId)
            ->where('actividad_id', $actividad->id)
            ->firstOrFail();

        return view('cuotas.edit', compact('actividad', 'cuota'));
    }

    public function update(Request $request, $actividad, $cuotaId)
    {
        $actividad = $this->getActividad($actividad, $request);

        $cuota = Cuota::where('id', $cuotaId)
            ->where('actividad_id', $actividad->id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'monto' => 'required|numeric',
            'frecuencia' => 'required|string',
        ]);

        $cuota->update($request->only(['nombre', 'monto', 'frecuencia']));

        return redirect()
            ->route('actividades.cuotas.index', $actividad->slug)
            ->with('success', 'Cuota actualizada');
    }

    public function destroy($actividad, $cuotaId, Request $request)
    {
        $actividad = $this->getActividad($actividad, $request);

        $cuota = Cuota::where('id', $cuotaId)
            ->where('actividad_id', $actividad->id)
            ->firstOrFail();

        $cuota->delete();

        return back()->with('success', 'Cuota eliminada');
    }
}
