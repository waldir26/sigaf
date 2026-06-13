<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Donaciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        .reporte {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }

        .header {
            background: #1a2a4f;
            padding: 20px;
            text-align: center;
            color: white;
        }

        .logo {
            max-width: 60px;
            margin-bottom: 8px;
        }

        .titulo {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .subtitulo {
            font-size: 11px;
            opacity: 0.8;
        }

        .fecha-reporte {
            background: #f0f2f5;
            padding: 8px;
            text-align: center;
            color: #6c7a8a;
            font-size: 11px;
            border-bottom: 1px solid #e0e0e0;
        }

        .resumen {
            display: table;
            width: 100%;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .resumen-card {
            display: table-cell;
            text-align: center;
            width: 25%;
        }

        .resumen-card .label {
            font-size: 11px;
            color: #6c7a8a;
            margin-bottom: 3px;
        }

        .resumen-card .valor {
            font-size: 20px;
            font-weight: bold;
            color: #1a2a4f;
        }

        .resumen-card .valor.total {
            color: #28a745;
        }

        .tabla-container {
            padding: 15px 20px;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla th {
            background: #1a2a4f;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        .tabla td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 10px;
            color: #343a40;
        }

        .tipo-badge {
            padding: 2px 8px;
            border-radius: 15px;
            font-size: 9px;
            display: inline-block;
        }

        .tipo-monetaria {
            background: #d4edda;
            color: #155724;
        }

        .tipo-especie {
            background: #cfe2ff;
            color: #084298;
        }

        .footer {
            background: #f8f9fa;
            padding: 12px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 9px;
            color: #6c7a8a;
        }
    </style>
</head>

<body>
    <?php
    date_default_timezone_set('America/El_Salvador');
    $fechaEmision = date('d/m/Y g:i:s A');
    ?>

    <div class="reporte">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" class="logo" alt="Logo">
            <h1 class="titulo">FUSALMO</h1>
            <p class="subtitulo">Fundación Salvador del Mundo</p>
        </div>

        <div class="fecha-reporte">
            Reporte generado el: {{ $fechaEmision }}
        </div>

        <div class="resumen">
            <div class="resumen-card">
                <div class="label">Total Donaciones</div>
                <div class="valor">{{ $donaciones->count() }}</div>
            </div>
            <div class="resumen-card">
                <div class="label">Monetarias</div>
                <div class="valor">{{ $donaciones->where('tipo_donacion', 'monetaria')->count() }}</div>
            </div>
            <div class="resumen-card">
                <div class="label">En Especie</div>
                <div class="valor">{{ $donaciones->where('tipo_donacion', 'especie')->count() }}</div>
            </div>
            <div class="resumen-card">
                <div class="label">Total Monetario</div>
                <div class="valor total">${{ number_format($totalMonetario, 2, '.', ',') }}</div>
            </div>
        </div>

        <div class="tabla-container">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Donante</th>
                        <th>Tipo</th>
                        <th>Monto/Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donaciones as $donacion)
                    <tr>
                        <td>{{ $donacion->id_donacion }}</td>
                        <td>{{ $donacion->fecha }}</td>
                        <td>{{ $donacion->donante->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="tipo-badge tipo-{{ $donacion->tipo_donacion }}">
                                {{ $donacion->tipo_donacion == 'monetaria' ? 'Monetaria' : 'Especie' }}
                            </span>
                        </td>
                        <td>
                            @if($donacion->tipo_donacion == 'monetaria')
                            <strong>${{ number_format($donacion->monto, 2, '.', ',') }}</strong>
                            @else
                            {{ Str::limit($donacion->descripcion, 40) ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay donaciones registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Este reporte es generado automáticamente por el sistema SIGAF</p>
            <p>Fundación Salvador del Mundo - FUSALMO</p>
        </div>
    </div>
</body>

</html>