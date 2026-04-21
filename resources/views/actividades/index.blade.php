<x-layouts.app>

    <div class="max-w-5xl mx-auto p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Actividades
                </h1>
                <p class="text-gray-500 text-sm">
                    Gestioná las actividades de tu club
                </p>
            </div>

            <a href="{{ route('actividades.create') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Nueva actividad
            </a>
        </div>

        <!-- Listado -->
        <div class="bg-white rounded-xl shadow-sm border divide-y">

            @forelse ($actividades as $actividad)
                <div class="p-4 flex justify-between items-center">

                    <div>
                        <h2 class="font-semibold text-gray-800">
                            {{ $actividad->nombre }}
                        </h2>
                        <p class="text-sm text-gray-400">
                            {{ $actividad->socios()->count() }} socios
                        </p>
                    </div>

                    <div class="flex gap-2">

                        {{-- <a href="{{ route('actividades.show', $actividad) }}"
                            class="text-sm text-green-600 hover:underline">
                            Ver
                        </a> --}}

                        <a href="{{ url('/actividades/' . $actividad->slug . '/dashboard') }}"
                            class="text-green-600 text-sm hover:underline">
                            Ver dashboard →
                        </a>

                        <a href="{{ route('actividades.cuotas.index', $actividad) }}"
                            class="text-sm text-blue-600 hover:underline">
                            Ver cuotas
                        </a>

                        <a href="{{ route('actividades.edit', $actividad) }}"
                            class="text-sm text-gray-600 hover:underline">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('actividades.destroy', $actividad) }}">
                            @csrf
                            @method('DELETE')

                            <button class="text-sm text-red-600 hover:underline">
                                Eliminar
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <div class="p-6 text-center text-gray-400">
                    No hay actividades creadas
                </div>
            @endforelse

        </div>

    </div>

</x-layouts.app>
