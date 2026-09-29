<x-layouts.app>

    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                Socios
            </h1>

            <a href="/socios/create">
                <x-ui.button>
                    + Nuevo Socio
                </x-ui.button>
            </a>
        </div>

        <!-- Tabla -->
        <x-ui.card>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">

                    <!-- Header -->
                    <thead class="bg-gray-50 border-b text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody class="divide-y">

                        @forelse ($socios as $socio)
                            @php
                                $pendientes = $socio->cuotas->where('estado', 'pendiente')->count();
                            @endphp

                            <tr class="hover:bg-gray-50 transition">

                                <!-- Nombre -->
                                <td class="px-4 py-3 font-medium">
                                    {{ $socio->nombre }}
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $socio->email }}
                                </td>

                                <!-- Estado -->
                                <td class="px-4 py-3">
                                    @if ($pendientes > 0)
                                        <x-ui.badge type="danger">
                                            {{ $pendientes }} pendientes
                                        </x-ui.badge>
                                    @else
                                        <x-ui.badge type="success">
                                            Al día
                                        </x-ui.badge>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-4 py-3 text-right space-x-2">

                                    <a href="/socios/{{ $socio->id }}">
                                        <x-ui.button variant="secondary">
                                            Ver
                                        </x-ui.button>
                                    </a>

                                    <a href="/portal/{{ $socio->token }}" target="_blank">
                                        <x-ui.button variant="success">
                                            Portal
                                        </x-ui.button>
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400">
                                    No hay socios cargados
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </x-ui.card>

    </div>

</x-layouts.app>



{{-- <x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">
                Socios
            </h2>

            <a href="/socios/create">
                <x-ui.button>
                    + Nuevo Socio
                </x-ui.button>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <x-ui.card>

                <x-ui.table :headers="['Nombre', 'Email', 'Estado', 'Acciones']">

                    @foreach ($socios as $socio)

                        @php
                            $pendientes = $socio->cuotas->where('estado', 'pendiente')->count();
                        @endphp

                        <x-ui.tr>

                            <!-- Nombre -->
                            <x-ui.td class="font-medium">
                                {{ $socio->nombre }}
                            </x-ui.td>

                            <!-- Email -->
                            <x-ui.td>
                                {{ $socio->email }}
                            </x-ui.td>

                            <!-- Estado -->
                            <x-ui.td>
                                @if ($pendientes > 0)
                                    <x-ui.badge type="danger">
                                        {{ $pendientes }} pendientes
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge type="success">
                                        Al día
                                    </x-ui.badge>
                                @endif
                            </x-ui.td>

                            <!-- Acciones -->
                            <x-ui.td class="space-x-2">

                                <a href="/socios/{{ $socio->id }}">
                                    <x-ui.button variant="secondary">
                                        Ver
                                    </x-ui.button>
                                </a>

                                <a href="/portal/{{ $socio->token }}" target="_blank">
                                    <x-ui.button variant="success">
                                        Portal
                                    </x-ui.button>
                                </a>

                            </x-ui.td>

                        </x-ui.tr>

                    @endforeach

                </x-ui.table>

            </x-ui.card>

        </div>
    </div>

</x-app-layout> --}}
