<x-layouts.app>

    <div class="max-w-xl mx-auto p-6">

        <h1 class="text-xl font-bold mb-4">Nueva Actividad</h1>

        <form method="POST" action="{{ route('actividades.store') }}" class="space-y-4">
            @csrf

            <input type="text" name="nombre"
                   placeholder="Nombre de la actividad"
                   class="w-full border rounded-lg px-4 py-2">

            <button class="w-full bg-green-600 text-white py-2 rounded-lg">
                Guardar
            </button>
        </form>

    </div>

</x-layouts.app>
