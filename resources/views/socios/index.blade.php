@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Socios</h1>

            <a href="/socios/create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nuevo Socio
            </a>
        </div>

        <!-- Tabla -->
        <div class="bg-white shadow rounded-lg overflow-hidden">

            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($socios as $socio)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3 font-medium">
                                {{ $socio->nombre }}
                            </td>

                            <td class="p-3">
                                {{ $socio->email }}
                            </td>

                            <td class="p-3">
                                @php
                                    $pendientes = $socio->cuotas->where('estado', 'pendiente')->count();
                                @endphp

                                @if ($pendientes > 0)
                                    <span class="text-red-600 font-semibold">
                                        {{ $pendientes }} pendientes
                                    </span>
                                @else
                                    <span class="text-green-600 font-semibold">
                                        Al día
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 space-x-2">

                                <a href="/socios/{{ $socio->id }}" class="text-blue-600 hover:underline">
                                    Ver
                                </a>

                                <a href="/portal/{{ $socio->token }}" target="_blank"
                                    class="text-green-600 hover:underline">
                                    Portal
                                </a>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>
@endsection
