<x-layouts.app>
    <div class="p-6 max-w-xl">

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <x-ui.input name="name" placeholder="Nombre" />
            <x-ui.input name="email" type="email" placeholder="Email" />
            <x-ui.input name="password" type="password" placeholder="Password" />

            <select name="role" class="w-full border p-2 mt-2">
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>

            <x-ui.button class="mt-4">Guardar</x-ui.button>

        </form>

    </div>
</x-layouts.app>
