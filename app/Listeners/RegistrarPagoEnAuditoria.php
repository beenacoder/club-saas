<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PagoRegistrado;
use Illuminate\Support\Facades\Log;

class RegistrarPagoEnAuditoria
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

        Log::info("Auditoría pago ID {$pago->id}");
    }
}
