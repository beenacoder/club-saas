<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    public function index(Request $request)
    {
        $actividades = Actividad::where('club_id', $request->user()->club_id)->get();

        return view('actividades.index', compact('actividades'));
    }

    public function create()
    {
        return view('actividades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Actividad::create([
            'club_id' => $request->user()->club_id,
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad creada correctamente');
    }

    public function show(Actividad $actividad, Request $request)
    {
        $this->authorizeActividad($actividad, $request);

        return view('actividades.show', compact('actividad'));
    }

    public function edit(Actividad $actividad, Request $request)
    {
        $this->authorizeActividad($actividad, $request);

        return view('actividades.edit', compact('actividad'));
    }

    public function update(Request $request, Actividad $actividad)
    {
        $this->authorizeActividad($actividad, $request);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $actividad->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('actividades.index')
            ->with('success', 'Actividad actualizada');
    }

    public function destroy(Actividad $actividad, Request $request)
    {
        $this->authorizeActividad($actividad, $request);

        $actividad->delete();

        return back()->with('success', 'Actividad eliminada');
    }

    // 🔒 Seguridad multi-club
    private function authorizeActividad($actividad, Request $request)
    {
        if ($actividad->club_id !== $request->user()->club_id) {
            abort(403);
        }
    }
}
