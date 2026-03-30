<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8">

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <x-ui.stat
                    title="Socios activos"
                    :value="$totalSocios ?? 120"
                    color="blue"
                />

                <x-ui.stat
                    title="Cuotas pagadas"
                    :value="'$' . number_format($totalPagado ?? 50000, 2)"
                    color="green"
                />

                <x-ui.stat
                    title="Deuda total"
                    :value="'$' . number_format($deudaTotal ?? 12000, 2)"
                    color="red"
                />

                <x-ui.stat
                    title="Cuotas pendientes"
                    :value="$cuotasPendientes ?? 32"
                />

            </div>

            <!-- Contenido principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Últimos movimientos -->
                <div class="lg:col-span-2">
                    <x-ui.card>
                        <h3 class="font-semibold text-gray-700 mb-4">
                            Últimos pagos
                        </h3>

                        <div class="space-y-3 text-sm">

                            @forelse ($ultimosPagos ?? [] as $pago)
                                <div class="flex justify-between border-b pb-2">
                                    <span>{{ $pago->socio->nombre }}</span>
                                    <span class="text-green-600 font-medium">
                                        ${{ $pago->monto }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-400">Sin movimientos</p>
                            @endforelse

                        </div>
                    </x-ui.card>
                </div>

                <!-- Acciones rápidas -->
                <div>
                    <x-ui.card>
                        <h3 class="font-semibold text-gray-700 mb-4">
                            Acciones
                        </h3>

                        <div class="flex flex-col gap-3">

                            <x-ui.button variant="primary">
                                Nuevo socio
                            </x-ui.button>

                            <x-ui.button variant="success">
                                Registrar pago
                            </x-ui.button>

                            <x-ui.button variant="secondary">
                                Ver reportes
                            </x-ui.button>

                        </div>
                    </x-ui.card>
                </div>

            </div>

        </div>
    </div>
</x-layouts.app>
