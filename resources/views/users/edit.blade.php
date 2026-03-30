<x-layouts.app>
<div class="p-6 max-w-xl">

<form method="POST" action="{{ route('users.update', $user) }}">
@csrf
@method('PUT')

<x-ui.input name="name" value="{{ $user->name }}" />

<select name="role" class="w-full border p-2 mt-2">
    <option value="staff" @selected($user->role=='staff')>Staff</option>
    <option value="admin" @selected($user->role=='admin')>Admin</option>
</select>

<x-ui.button class="mt-4">Actualizar</x-ui.button>

</form>

</div>
</x-layouts.app>
