@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('styles')
@vite('resources/css/dashboard-custom.css')
@endsection

@section('content')
<div>
    <h1 style="color: var(--azul-marino); margin-bottom: 25px;">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </h1>

    <!-- Tarjetas de Gestión -->
    <div class="cards-container">
        <div class="card">
            <div class="card-icon"><i class="fas fa-users" style="color: #17a2b8;"></i></div>
            <div class="card-title">Beneficiarios</div>
            <div class="card-value">{{ number_format($totalBeneficiarios) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-school" style="color: #28a745;"></i></div>
            <div class="card-title">Escuelas Beneficiadas</div>
            <div class="card-value">{{ number_format($totalEscuelas) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-user-graduate" style="color: #ffc107;"></i></div>
            <div class="card-title">Participantes</div>
            <div class="card-value">{{ number_format($totalParticipantes) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-chalkboard" style="color: #fd7e14;"></i></div>
            <div class="card-title">Programas Activos</div>
            <div class="card-value">{{ number_format($programasActivos) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-venus-mars" style="color: #e83e8c;"></i></div>
            <div class="card-title">Participantes por Sexo</div>
            <div class="card-value">
                <div style="font-size: 14px;"><i class="fas fa-mars"></i> Masculino: {{ number_format($totalMasculino) }}</div>
                <div style="font-size: 14px;"><i class="fas fa-venus"></i> Femenino: {{ number_format($totalFemenino) }}</div>
            </div>
        </div>
    </div>

    <!-- Tarjetas Financieras -->
    <div class="cards-container">
        <div class="card">
            <div class="card-icon"><i class="fas fa-hand-holding-heart" style="color: #28a745;"></i></div>
            <div class="card-title">Donaciones</div>
            <div class="card-value">${{ number_format($totalDonaciones, 2) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-concierge-bell" style="color: #17a2b8;"></i></div>
            <div class="card-title">Servicios</div>
            <div class="card-value">${{ number_format($totalServicios, 2) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-tags" style="color: #ffc107;"></i></div>
            <div class="card-title">Ventas</div>
            <div class="card-value">${{ number_format($totalVentas, 2) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-money-bill-wave" style="color: #dc3545;"></i></div>
            <div class="card-title">Gastos</div>
            <div class="card-value negative">${{ number_format($totalGastos, 2) }}</div>
        </div>
    </div>

    <!-- Resumen Financiero -->
    <div class="cards-container">
        <div class="card">
            <div class="card-icon"><i class="fas fa-chart-line" style="color: #28a745;"></i></div>
            <div class="card-title">Total Ingresos</div>
            <div class="card-value positive">${{ number_format($totalIngresos, 2) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-chart-line" style="color: #dc3545;"></i></div>
            <div class="card-title">Total Gastos</div>
            <div class="card-value negative">${{ number_format($totalGastos, 2) }}</div>
        </div>
        <div class="card">
            <div class="card-icon"><i class="fas fa-balance-scale" style="color: #1a2a4f;"></i></div>
            <div class="card-title">Balance Financiero</div>
            <div class="card-value {{ $balance >= 0 ? 'positive' : 'negative' }}">
                ${{ number_format($balance, 2) }}
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="charts-container">
        <div class="chart-box">
            <h3><i class="fas fa-chart-bar"></i> Ingresos vs Gastos (últimos 6 meses)</h3>
            <canvas id="ingresosGastosChart"
                data-meses='@json($meses)'
                data-ingresos='@json($ingresosPorMes)'
                data-gastos='@json($gastosPorMes)'>
            </canvas>
        </div>
        <div class="chart-box">
            <h3><i class="fas fa-chart-pie"></i> Gastos por Categoría</h3>
            <canvas id="gastosCategoriaChart"
                data-categorias='@json($gastosPorCategoria->pluck("origen"))'
                data-montos='@json($gastosPorCategoria->pluck("total"))'>
            </canvas>
        </div>
        <div class="chart-box">
            <h3><i class="fas fa-venus-mars"></i> Participantes por Sexo</h3>
            <canvas id="sexoChart"
                data-masculino="{{ $totalMasculino }}"
                data-femenino="{{ $totalFemenino }}">
            </canvas>
        </div>
    </div>

    <!-- Últimos Movimientos -->
    <div style="margin-top: 30px;">
        <h2 class="section-title"><i class="fas fa-history"></i> Últimos Movimientos</h2>
        <div style="overflow-x: auto;">
            <table class="movimientos-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Origen</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimosMovimientos as $movimiento)
                    <tr>
                        <td>{{ $movimiento->fecha }}</td>
                        <td>
                            <span class="badge-{{ $movimiento->tipo == 'Ingreso' ? 'ingreso' : 'gasto' }}">
                                {{ $movimiento->tipo }}
                            </span>
                        </td>
                        <td>{{ $movimiento->origen }}</td>
                        <td>{{ Str::limit($movimiento->descripcion, 50) }}</td>
                        <td class="{{ $movimiento->tipo == 'Ingreso' ? 'positive' : 'negative' }}" style="font-weight: bold;">
                            ${{ number_format($movimiento->monto, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px;">
                            No hay movimientos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite('resources/js/dashboard-charts.js')
@endsection