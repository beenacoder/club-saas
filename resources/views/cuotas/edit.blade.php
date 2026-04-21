<x-layouts.app>

<div class="max-w-xl mx-auto p-6">

    <h1 class="text-xl font-bold mb-4">
        Editar cuota
    </h1>

    <form method="POST"
          action="{{ route('actividades.cuotas.update', [$actividad, $cuota]) }}"
          class="space-y-4">

        @csrf
        @method('PUT')

        <input type="text" name="nombre"
               value="{{ $cuota->nombre }}"
               class="w-full border p-2 rounded">

        <input type="number" name="monto"
               value="{{ $cuota->monto }}"
               class="w-full border p-2 rounded">

        <select name="frecuencia" class="w-full border p-2 rounded">
            <option value="mensual" {{ $cuota->frecuencia == 'mensual' ? 'selected' : '' }}>Mensual</option>
            <option value="semanal" {{ $cuota->frecuencia == 'semanal' ? 'selected' : '' }}>Semanal</option>
            <option value="anual" {{ $cuota->frecuencia == 'anual' ? 'selected' : '' }}>Anual</option>
        </select>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Actualizar
        </button>
    </form>

</div>

</x-layouts.app>
