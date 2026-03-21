<h1>Hola {{ $socio->nombre }}</h1>

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
            <td>
                @if ($c->estado == 'pendiente')
                    <span style="color:red;">Pendiente</span>
                @elseif($c->estado == 'parcial')
                    <span style="color:orange;">Parcial</span>
                @elseif($c->estado == 'pagado')
                    <span style="color:green;">Pagada</span>
                @endif
            </td>

            <td>
                @if ($c->monto - ($c->monto_pagado ?? 0) > 0)
                    <form method="POST" action="{{ route('portal.pagar.cuota', [$socio->token, $c->id]) }}">
                        @csrf
                        <button type="submit">Pagar cuota</button>
                    </form>
                @else
                    <button type="submit">Imprimir recibo</button>
                @endif
            </td>
        </tr>
    @endforeach
</table>
