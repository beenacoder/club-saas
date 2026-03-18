<h1>Socios</h1>

<a href="{{ route('socios.create') }}">Nuevo Socio</a>

<ul>
@foreach($socios as $socio)
    <li>{{ $socio->nombre }} - {{ $socio->email }}</li>
@endforeach
</ul>
