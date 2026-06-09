<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Comprobante de Gasto</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #f5f7fa;
            padding: 30px;
        }

        .comprobante {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            min-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #1a2a4f, #2a3a6f);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 15px;
        }

        .titulo {
            font-size: 26px;
            margin-bottom: 5px;
        }

        .subtitulo {
            font-size: 13px;
            opacity: 0.8;
        }

        .comprobante-titulo {
            background: #f0f2f5;
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #1a2a4f;
        }

        .comprobante-titulo h2 {
            color: #1a2a4f;
            font-size: 20px;
        }

        .comprobante-numero {
            color: #6c7a8a;
            font-size: 12px;
            margin-top: 5px;
        }

        .content {
            padding: 30px;
            flex: 1;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h3 {
            color: #1a2a4f;
            font-size: 16px;
            border-left: 4px solid #1a2a4f;
            padding-left: 12px;
            margin-bottom: 15px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            border-bottom: 1px solid #e0e0e0;
        }

        .info-label {
            padding: 10px;
            font-weight: bold;
            width: 120px;
            background: #f8f9fa;
        }

        .info-value {
            padding: 10px;
        }

        .monto {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }

        .footer {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 11px;
            color: #6c7a8a;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php date_default_timezone_set('America/El_Salvador');
    $fechaEmision = date('d/m/Y g:i:s A'); ?>
    <div class="comprobante">
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
            <h1 class="titulo">FUSALMO</h1>
            <p class="subtitulo">Fundación Salvador del Mundo</p>
        </div>
        <div class="comprobante-titulo">
            <h2>COMPROBANTE DE GASTO</h2>
            <div class="comprobante-numero">N° {{ $gasto->id_gasto }}</div>
        </div>
        <div class="content">
            <div class="info-section">
                <h3>Detalle del Gasto</h3>
                <table class="info-grid">
                    <tr class="info-row">
                        <td class="info-label">Categoría:</td>
                        <td class="info-value">{{ $gasto->categoria }}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Fecha:</td>
                        <td class="info-value">{{ $gasto->fecha }}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Monto:</td>
                        <td class="info-value monto">${{ number_format($gasto->monto, 2, '.', ',') }}</td>
                    </tr>
                    @if($gasto->descripcion)
                    <tr class="info-row">
                        <td class="info-label">Descripción:</td>
                        <td class="info-value">{{ $gasto->descripcion }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="footer">
            <p>Este documento es un comprobante oficial.</p>
            <p>Fecha de emisión: {{ $fechaEmision }}</p>
            <p>Fundación Salvador del Mundo - FUSALMO</p>
        </div>
    </div>
</body>

</html>