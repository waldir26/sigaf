<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Gastos</title>
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
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1a2a4f, #2a3a6f);
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
            font-size: 11px;
            border-bottom: 1px solid #e0e0e0;
        }

        .resumen {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .resumen-card {
            text-align: center;
            flex: 1;
        }

        .resumen-card .label {
            font-size: 11px;
            color: #6c7a8a;
            margin-bottom: 3px;
        }

        .resumen-card .valor {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
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
    <?php date_default_timezone_set('America/El_Salvador');
    $fechaEmision = date('d/m/Y g:i:s A'); ?>
    <div class="reporte">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
            <h1 class="titulo">FUSALMO</h1>
            <p class="subtitulo">Fundación Salvador del Mundo</p>
        </div>
        <div class="fecha-reporte">Reporte generado el: {{ $fechaEmision }}</div>
        <div class="resumen">
            <div class="resumen-card">
                <div class="label">Total Gastos</div>
                <div class="valor">{{ $gastos->count() }}</div>
            </div>
            <div class="resumen-card">
                <div class="label">Total Monto</div>
                <div class="valor">${{ number_format($totalGastos, 2, '.', ',') }}</div>
            </div>
        </div>
        <div class="tabla-container">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gastos as $gasto)
                    <tr>
                        <td>{{ $gasto->id_gasto }}</td>
                        <td>{{ $gasto->fecha }}</td>
                        <td>{{ $gasto->categoria }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($gasto->descripcion, 40) ?? '-' }}</td>
                        <td><strong>${{ number_format($gasto->monto, 2, '.', ',') }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No hay gastos registrados</td>
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