<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PagoRegistrado;
use Illuminate\Support\Facades\Log;

class EnviarReciboPago
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PagoRegistrado $event): void
    {
        $pago = $event->pago;

        // después lo conectamos a Mail
        Log::info("Enviar recibo pago ID {$pago->id}");
    }
}
