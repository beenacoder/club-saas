<h1>Nuevo Socio</h1>

<form method="POST" action="{{ route('socios.store') }}">
    @csrf

    <input type="text" name="nombre" placeholder="Nombre"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="text" name="telefono" placeholder="Teléfono"><br>

    <button type="submit">Guardar</button>
</form>
