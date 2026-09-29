{{-- @extends('layouts.app')

@section('content')
    <h1>Nuevo Socio</h1>

    <form method="POST" action="{{ route('socios.store') }}">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre"><br>
        <input type="email" name="email" placeholder="Email"><br>
        <input type="text" name="telefono" placeholder="Teléfono"><br>

        <button type="submit">Guardar</button>
    </form>
@endsection --}}


<x-layouts.app>

    <div class="max-w-2xl mx-auto p-6 space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Nuevo Socio
            </h1>
            <p class="text-gray-500 text-sm">
                Completá los datos para registrar un nuevo socio
            </p>
        </div>

        <!-- Form -->
        <x-ui.card class="w-full">
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded-lg text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('socios.store') }}" class="space-y-4">
                @csrf

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>

                    <x-text-input type="text" name="nombre" value="{{ old('nombre') }}" required />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>

                    <x-text-input type="email" name="email" value="{{ old('email') }}" required />
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono
                    </label>

                    <x-text-input type="text" name="telefono" value="{{ old('telefono') }}" />
                </div>

                <!-- Actividades -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Actividades
                    </label>

                    <select name="actividades[]" multiple class="w-full border rounded p-2">
                        @foreach ($actividades as $actividad)
                            <option value="{{ $actividad->id }}" @if (collect(old('actividades'))->contains($actividad->id)) selected @endif>
                                {{ $actividad->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Podés seleccionar varias. Si no seleccionás ninguna, será socio general.
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-2 pt-4">
                    <a href="/socios">
                        <x-ui.button variant="secondary">
                            Cancelar
                        </x-ui.button>
                    </a>

                    <x-ui.button type="submit">
                        Guardar
                    </x-ui.button>
                </div>
            </form>

        </x-ui.card>

    </div>

</x-layouts.app>
