<x-layouts.app>

<div class="max-w-xl mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">
        Nueva cuota - {{ $actividad->nombre }}
    </h1>

    <form method="POST" action="{{ route('actividades.cuotas.store', $actividad) }}" class="space-y-4">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre"
               class="w-full border p-2 rounded">

        <input type="number" name="monto" placeholder="Monto"
               class="w-full border p-2 rounded">

        <select name="frecuencia" class="w-full border p-2 rounded">
            <option value="mensual">Mensual</option>
            <option value="semanal">Semanal</option>
            <option value="anual">Anual</option>
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar
        </button>
    </form>

</div>

</x-layouts.app>
