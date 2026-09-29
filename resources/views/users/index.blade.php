<x-layouts.app>
<div class="p-6">

    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Empleados</h1>

        <a href="{{ route('users.create') }}">
            <x-ui.button>Nuevo</x-ui.button>
        </a>
    </div>

    <x-ui.table  class="w-full border">
        <x-ui.th>
            <x-ui.tr>
                <x-ui.th>Nombre</x-ui.th>
                <x-ui.th>Email</x-ui.th>
                <x-ui.th>Rol</x-ui.th>
                <x-ui.th></x-ui.th>
            </x-ui.tr>
        </x-ui.th>
        <tbody>
            @foreach($users as $user)
            <x-ui.tr class="border-t">
                <x-ui.td>{{ $user->name }}</x-ui.td>
                <x-ui.td>{{ $user->email }}</x-ui.td>
                <x-ui.td>{{ $user->role }}</x-ui.td>
                <x-ui.td class="flex gap-2">
                    <x-ui.button variant='warning' href="{{ route('users.edit', $user) }}">Editar</x-ui.button>

                    <form method="POST" action="{{ route('users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <x-ui.button variant='danger'>Eliminar</x-ui.button>
                    </form>
                </x-ui.td>
            </x-ui.tr>
            @endforeach
        </tbody>
    </x-ui.table>

</div>
</x-layouts.app>
