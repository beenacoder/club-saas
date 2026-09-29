<x-layouts.app>

    <div class="max-w-2xl mx-auto p-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Editar Actividad
            </h1>
            <p class="text-gray-500 text-sm">
                Modificá el nombre de la actividad
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">

            <form action="{{ route('actividades.update', $actividad) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre de la actividad
                    </label>

                    <input type="text" name="nombre" value="{{ old('nombre', $actividad->nombre) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                        required>

                    @error('nombre')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="flex justify-between items-center pt-4">

                    <a href="{{ route('actividades.index') }}" class="text-sm text-gray-500 hover:underline">
                        ← Volver
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layouts.app>
