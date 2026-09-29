<x-layouts.app>

    <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $actividad->nombre }}
                </h1>
                <p class="text-gray-500 text-sm">
                    Dashboard de la actividad
                </p>
            </div>

            <a href="{{ route('actividades.index') }}"
               class="text-sm text-gray-500 hover:underline">
                ← Volver
            </a>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Socios -->
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Socios</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $cantidadSocios }}
                </p>
            </div>

            <!-- Cuotas -->
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Cuotas generadas</p>
                <p class="text-3xl font-bold text-blue-600">
                    {{ $cantidadCuotas }}
                </p>
            </div>

            <!-- Recaudación -->
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-500">Recaudado</p>
                <p class="text-3xl font-bold text-green-700">
                    ${{ number_format($totalRecaudado, 0, ',', '.') }}
                </p>
            </div>

        </div>

        <!-- Sección futura -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h2 class="text-lg font-semibold mb-2">
                Próximamente
            </h2>
            <p class="text-gray-500 text-sm">
                Acá vas a poder ver gráficos, asistencia, ingresos por mes y más métricas.
            </p>
        </div>

    </div>

</x-layouts.app>
