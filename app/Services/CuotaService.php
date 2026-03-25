<?php

namespace App\Services;

use App\Models\Socio;
use App\Models\Cuota;
use App\Models\SocioCuota;
use Carbon\Carbon;

class CuotaService
{
    public function generarParaSocio(Socio $socio)
    {
        $fecha = Carbon::now()->startOfMonth();

        // 🔥 CUOTAS POR ACTIVIDAD
        foreach ($socio->actividades as $actividad) {

            $cuotas = Cuota::where('club_id', $socio->club_id)
                ->where('actividad_id', $actividad->id)
                ->get();

            foreach ($cuotas as $cuota) {

                $existe = SocioCuota::where('socio_id', $socio->id)
                    ->where('cuota_id', $cuota->id)
                    ->whereDate('fecha', $fecha)
                    ->exists();

                if (!$existe) {
                    SocioCuota::create([
                        'club_id' => $socio->club_id,
                        'socio_id' => $socio->id,
                        'cuota_id' => $cuota->id,
                        'fecha' => $fecha,
                        'monto' => $cuota->monto,
                        'estado' => 'pendiente',
                    ]);
                }
            }
        }

        // 🔥 CUOTAS GENERALES
        $cuotasGenerales = Cuota::where('club_id', $socio->club_id)
            ->whereNull('actividad_id')
            ->get();

        foreach ($cuotasGenerales as $cuota) {

            $existe = SocioCuota::where('socio_id', $socio->id)
                ->where('cuota_id', $cuota->id)
                ->whereDate('fecha', $fecha)
                ->exists();

            if (!$existe) {
                SocioCuota::create([
                    'club_id' => $socio->club_id,
                    'socio_id' => $socio->id,
                    'cuota_id' => $cuota->id,
                    'fecha' => $fecha,
                    'monto' => $cuota->monto,
                    'estado' => 'pendiente',
                ]);
            }
        }
    }
}
