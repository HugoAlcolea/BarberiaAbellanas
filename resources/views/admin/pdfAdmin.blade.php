<!DOCTYPE html>
<html>
<head>
    <title>Facturación - {{ $carbonFechaFormateada }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Facturación - {{ $carbonFechaFormateada }}</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Dinero Cobrado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDinero = 0;
            @endphp
            @foreach($citas as $cita)
            <tr>
                <td>{{ $cita->id }}</td>
                <td>{{ $cita->usuario->name }} {{ $cita->usuario->surname }}</td>
                <td>{{ $cita->fecha }}</td>
                <td>{{ $cita->dinero_cobrado }}€</td>
            </tr>
            @php
                $totalDinero += $cita->dinero_cobrado;
            @endphp
            @endforeach
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>{{ $totalDinero }}€</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
