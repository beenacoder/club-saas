<x-layouts.app>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold">
                    Cuotas - {{ $actividad->nombre }}
                </h1>
            </div>

            <a href="{{ route('actividades.cuotas.create', $actividad) }}"
                class="bg-green-600 text-white px-4 py-2 rounded-lg">
                + Nueva cuota
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border">

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3">Importe</th>
                        <th class="p-3">Vencimiento</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($cuotas as $cuota)
                        <tr class="border-t">
                            <td class="p-3">{{ $cuota->nombre }}</td>
                            <td>${{ $cuota->monto }}</td>
                            <td>{{ ucfirst($cuota->frecuencia) }}</td>
                            <td class="p-3 text-center">{{ $cuota->fecha_vencimiento }}</td>
                            <td class="p-3 text-center space-x-2">

                                <a href="{{ route('actividades.cuotas.edit', [$actividad, $cuota]) }}"
                                    class="text-blue-600">Editar</a>

                                <form action="{{ route('actividades.cuotas.destroy', [$actividad, $cuota]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600">
                                        Eliminar
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

</x-layouts.app>
