<x-layouts.app>

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">
                {{ $socio->nombre }}
            </h1>

            <x-ui.button href="/portal/{{ $socio->token }}" target="_blank">
                Ver portal
            </x-ui.button>
        </div>

        <!-- Datos del socio -->
        <x-ui.card>
            <h2 class="text-lg font-semibold mb-2">Datos</h2>

            <p><strong>Email:</strong> {{ $socio->email }}</p>
            <p><strong>DNI:</strong> {{ $socio->dni }}</p>
        </x-ui.card>

        <!-- Cuotas -->
        <x-ui.card>

            <h2 class="text-lg font-semibold mb-4">Cuotas</h2>

            <table class="w-full text-left">
                <thead class="border-b">
                    <tr>
                        <th class="p-2">Periodo</th>
                        <th class="p-2">Monto</th>
                        <th class="p-2">Pagado</th>
                        <th class="p-2">Saldo</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($socio->cuotas as $cuota)
                        @php
                            $pagado = $cuota->pagos->sum('monto');
                            $saldo = $cuota->monto - $pagado;
                        @endphp

                        <tr class="border-b">

                            <td class="p-2">{{ $cuota->periodo }}</td>

                            <td class="p-2">${{ $cuota->monto }}</td>

                            <td class="p-2 text-green-600">
                                ${{ $pagado }}
                            </td>

                            <td class="p-2 text-red-600">
                                ${{ $saldo }}
                            </td>

                            <td class="p-2">
                                @if ($saldo <= 0)
                                    <x-ui.badge type="success">Pagado</x-ui.badge>
                                @elseif ($pagado > 0)
                                    <x-ui.badge type="warning">Parcial</x-ui.badge>
                                @else
                                    <x-ui.badge type="danger">Pendiente</x-ui.badge>
                                @endif
                            </td>

                            <td class="p-2 space-y-2">

                                @if ($saldo > 0)
                                    <!-- Pago total -->
                                    <form method="POST" action="/pagos">
                                        @csrf
                                        <input type="hidden" name="cuota_id" value="{{ $cuota->id }}">
                                        <input type="hidden" name="monto" value="{{ $saldo }}">

                                        <button class="bg-green-600 text-white px-3 py-1 rounded text-sm">
                                            Pagar total
                                        </button>
                                    </form>

                                    <!-- Pago parcial -->
                                    <form method="POST" action="/pagos" class="flex gap-2">
                                        @csrf
                                        <input type="hidden" name="cuota_id" value="{{ $cuota->id }}">

                                        <x-ui.input type="number" name="monto" placeholder="Monto" step="0.01"
                                            required />

                                        <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            OK
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </x-ui.card>

    </div>

</x-layouts.app>
