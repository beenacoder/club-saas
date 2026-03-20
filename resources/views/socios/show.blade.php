@if (session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

<h1>{{ $socio->nombre }}</h1>



<p><strong>Deuda total:</strong> ${{ $deuda }}</p>

<h2>Cuotas</h2>

<table border="1">
    <tr>
        <th>Fecha</th>
        <th>Monto</th>
        <th>Pagado</th>
        <th>Saldo</th>
        <th>Estado</th>
    </tr>

    @foreach ($cuotas as $c)
        <tr>
            <td>{{ $c->fecha }}</td>
            <td>${{ $c->monto }}</td>
            <td>${{ $c->monto_pagado ?? 0 }}</td>
            <td>${{ $c->monto - ($c->monto_pagado ?? 0) }}</td>
            <td>{{ $c->estado }}</td>
        </tr>
    @endforeach
</table>

<h2>Pagos</h2>

<ul>
    @foreach ($socio->pagos as $p)
        <li>{{ $p->fecha }} - ${{ $p->monto }}</li>
    @endforeach
</ul>

<h2>Registrar Pago</h2>

<form method="POST" action="{{ route('socios.pagar', $socio->id) }}">
    @csrf

    <input type="number" name="monto" placeholder="Monto a pagar" step="0.01" required>

    <button type="submit">Pagar</button>
</form>
