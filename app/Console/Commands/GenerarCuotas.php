<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Socio;
use App\Models\Cuota;
use App\Models\SocioCuota;
use Carbon\Carbon;

class GenerarCuotas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generar:cuotas';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fecha = Carbon::now()->startOfMonth();

        $socios = Socio::with(['actividades', 'club'])->get();

        foreach ($socios as $socio) {

            foreach ($socio->actividades as $actividad) {

                // buscar cuota para esa actividad
                $cuotas = Cuota::where('club_id', $socio->club_id)
                    ->where('actividad_id', $actividad->id)
                    ->get();

                foreach ($cuotas as $cuota) {

                    // evitar duplicados
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

            // 🔥 CUOTAS GENERALES (sin actividad)
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

        $this->info('Cuotas generadas correctamente');

    }
}
