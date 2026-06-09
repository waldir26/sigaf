<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Donación</title>
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
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
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
        
        .logo-container {
            margin-bottom: 15px;
        }
        
        .logo {
            max-width: 100px;
            max-height: 100px;
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
            color: #343a40;
            width: 120px;
            background: #f8f9fa;
            font-size: 13px;
        }
        
        .info-value {
            padding: 10px;
            color: #343a40;
            font-size: 13px;
        }
        
        .monto {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        
        .tipo-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
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
        
        .gracias {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 12px;
        }
        
        .gracias p {
            color: #2e7d32;
            font-size: 14px;
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
    <?php
    date_default_timezone_set('America/El_Salvador');
    $fechaEmision = date('d/m/Y g:i:s A');
    ?>
    
    <div class="comprobante">
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
            </div>
            <h1 class="titulo">FUSALMO</h1>
            <p class="subtitulo">Fundación Salvador del Mundo</p>
        </div>
        
        <div class="comprobante-titulo">
            <h2>COMPROBANTE DE DONACIÓN</h2>
            <div class="comprobante-numero">N° {{ $donacion->id_donacion }}</div>
        </div>
        
        <div class="content">
            <div class="info-section">
                <h3>Información del Donante</h3>
                <table class="info-grid">
                    <tr class="info-row">
                        <td class="info-label">Nombre:</td>
                        <td class="info-value">{{ $donacion->donante->nombre ?? 'N/A' }}</td>
                    </tr>
                    @if($donacion->donante->telefono)
                    <tr class="info-row">
                        <td class="info-label">Teléfono:</td>
                        <td class="info-value">{{ $donacion->donante->telefono }}</td>
                    </tr>
                    @endif
                    @if($donacion->donante->correo)
                    <tr class="info-row">
                        <td class="info-label">Correo:</td>
                        <td class="info-value">{{ $donacion->donante->correo }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="info-section">
                <h3>Detalle de la Donación</h3>
                <table class="info-grid">
                    <tr class="info-row">
                        <td class="info-label">Fecha Donación:</td>
                        <td class="info-value">{{ $donacion->fecha }}</td>
                    </tr>
                    <tr class="info-row">
                        <td class="info-label">Tipo:</td>
                        <td class="info-value">
                            <span class="tipo-badge tipo-{{ $donacion->tipo_donacion }}">
                                {{ $donacion->tipo_donacion == 'monetaria' ? 'Monetaria' : 'En especie' }}
                            </span>
                        </td>
                    </tr>
                    @if($donacion->tipo_donacion == 'monetaria')
                    <tr class="info-row">
                        <td class="info-label">Monto:</td>
                        <td class="info-value monto">${{ number_format($donacion->monto, 2, '.', ',') }}</td>
                    </tr>
                    @endif
                    @if($donacion->descripcion)
                    <tr class="info-row">
                        <td class="info-label">Descripción:</td>
                        <td class="info-value">{{ $donacion->descripcion }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="gracias">
                <p>¡Gracias por su generosa contribución!</p>
                <p>Su donación ayuda a transformar vidas en El Salvador.</p>
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