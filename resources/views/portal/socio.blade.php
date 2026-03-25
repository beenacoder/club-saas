<x-layouts.app>

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                Hola {{ $socio->nombre }}
            </h1>

            {{-- <x-ui.button href="/portal/{{ $socio->token }}" target="_blank">
                Ver portal
            </x-ui.button> --}}
        </div>

        <!-- Deuda -->
        <x-ui.card>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Deuda total</span>

                <span class="text-xl font-bold text-red-600">
                    ${{ number_format($deuda, 2) }}
                </span>
            </div>
        </x-ui.card>

        <!-- Cuotas -->
        {{-- <x-ui.card>

            <h2 class="text-lg font-semibold mb-4">Cuotas</h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">

                    <thead class="border-b text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="p-3">Fecha</th>
                            <th class="p-3">Monto</th>
                            <th class="p-3">Pagado</th>
                            <th class="p-3">Saldo</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3 text-right">Acción</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach ($cuotas as $c)

                        @php
                            $pagado = $c->monto_pagado ?? 0;
                            $saldo = $c->monto - $pagado;
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <!-- Fecha -->
                            <td class="p-3">
                                {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}
                            </td>

                            <!-- Monto -->
                            <td class="p-3 font-medium">
                                ${{ number_format($c->monto, 2) }}
                            </td>

                            <!-- Pagado -->
                            <td class="p-3 text-green-600">
                                ${{ number_format($pagado, 2) }}
                            </td>

                            <!-- Saldo -->
                            <td class="p-3 text-red-600 font-semibold">
                                ${{ number_format($saldo, 2) }}
                            </td>

                            <!-- Estado -->
                            <td class="p-3">
                                @if ($c->estado == 'pendiente')
                                    <x-ui.badge type="danger">Pendiente</x-ui.badge>
                                @elseif($c->estado == 'parcial')
                                    <x-ui.badge type="warning">Parcial</x-ui.badge>
                                @elseif($c->estado == 'pagado')
                                    <x-ui.badge type="success">Pagada</x-ui.badge>
                                @endif
                            </td>

                            <!-- Acción -->
                            <td class="p-3 text-right space-y-2">

                                @if ($saldo > 0)

                                    <!-- Pago total -->
                                    <form method="POST" action="{{ route('portal.pagar.cuota', [$socio->token, $c->id]) }}">
                                        @csrf
                                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                            Pagar
                                        </button>
                                    </form>

                                @else
                                    <button class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-xs">
                                        Recibo
                                    </button>
                                @endif

                            </td>

                        </tr>

                        @endforeach

                        @if ($cuotas->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-400">
                                    No hay cuotas
                                </td>
                            </tr>
                        @endif

                    </tbody>

                </table>
            </div>

        </x-ui.card> --}}


        <x-ui.card>

            <h2 class="text-lg font-semibold mb-4">Cuotas</h2>

            <x-ui.table :headers="['Fecha', 'Monto', 'Pagado', 'Saldo', 'Estado', 'Acción']">

                @foreach ($cuotas as $c)
                    @php
                        $pagado = $c->monto_pagado ?? 0;
                        $saldo = $c->monto - $pagado;
                    @endphp

                    <x-ui.tr>

                        <x-ui.td>
                            {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}
                        </x-ui.td>

                        <x-ui.td class="font-medium">
                            ${{ number_format($c->monto, 2) }}
                        </x-ui.td>

                        <x-ui.td class="text-green-600">
                            ${{ number_format($pagado, 2) }}
                        </x-ui.td>

                        <x-ui.td class="text-red-600 font-semibold">
                            ${{ number_format($saldo, 2) }}
                        </x-ui.td>

                        <x-ui.td>
                            @if ($c->estado == 'pendiente')
                                <x-ui.badge type="danger">Pendiente</x-ui.badge>
                            @elseif($c->estado == 'parcial')
                                <x-ui.badge type="warning">Parcial</x-ui.badge>
                            @elseif($c->estado == 'pagado')
                                <x-ui.badge type="success">Pagada</x-ui.badge>
                            @endif
                        </x-ui.td>

                        <x-ui.td class="text-right">

                            @if ($saldo > 0)
                                <form method="POST"
                                    action="{{ route('portal.pagar.cuota', [$socio->token, $c->id]) }}">
                                    @csrf

                                    <x-ui.button variant="success">
                                        Pagar
                                    </x-ui.button>
                                </form>
                            @else
                                <x-ui.button variant="secondary">
                                    Recibo
                                </x-ui.button>
                            @endif

                        </x-ui.td>

                    </x-ui.tr>
                @endforeach

            </x-ui.table>

        </x-ui.card>

    </div>

</x-layouts.app>
