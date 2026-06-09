<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Escuela;
use App\Models\Participante;
use App\Models\Donacion;
use App\Models\Servicio;
use App\Models\Venta;
use App\Models\Gasto;
use App\Models\MovimientoFinanciero;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Tarjetas de gestión
        $totalBeneficiarios = Participante::count();
        $totalEscuelas = Escuela::count();
        $totalParticipantes = Participante::count();
        $programasActivos = Programa::where('estado', 'activo')->count();

        // Estadísticas por sexo
        $totalMasculino = Participante::where('sexo', 'M')->count();
        $totalFemenino = Participante::where('sexo', 'F')->count();
        $totalSinSexo = Participante::whereNull('sexo')->orWhere('sexo', '')->count();

        // Tarjetas financieras
        $totalDonaciones = Donacion::where('tipo_donacion', 'monetaria')->sum('monto');
        $totalServicios = Servicio::sum('monto');
        $totalVentas = Venta::sum('monto');
        $totalGastos = Gasto::sum('monto');

        $totalIngresos = $totalDonaciones + $totalServicios + $totalVentas;
        $balance = $totalIngresos - $totalGastos;

        // Últimos movimientos
        $ultimosMovimientos = MovimientoFinanciero::orderBy('fecha', 'desc')->limit(10)->get();

        // Gráfico: Ingresos vs Gastos por mes (últimos 6 meses)
        $meses = [];
        $ingresosPorMes = [];
        $gastosPorMes = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = date('Y-m', strtotime("-$i months"));
            $nombreMes = date('M Y', strtotime("-$i months"));
            $meses[] = $nombreMes;

            $ingresosPorMes[] = MovimientoFinanciero::where('tipo', 'Ingreso')
                ->whereYear('fecha', date('Y', strtotime($mes)))
                ->whereMonth('fecha', date('m', strtotime($mes)))
                ->sum('monto');

            $gastosPorMes[] = MovimientoFinanciero::where('tipo', 'Gasto')
                ->whereYear('fecha', date('Y', strtotime($mes)))
                ->whereMonth('fecha', date('m', strtotime($mes)))
                ->sum('monto');
        }

        // Gráfico: Gastos por categoría
        $gastosPorCategoria = MovimientoFinanciero::where('tipo', 'Gasto')
            ->select('origen')
            ->selectRaw('SUM(monto) as total')
            ->groupBy('origen')
            ->get();

        return view('dashboard.index', compact(
            'totalBeneficiarios',
            'totalEscuelas',
            'totalParticipantes',
            'programasActivos',
            'totalMasculino',
            'totalFemenino',
            'totalSinSexo',
            'totalDonaciones',
            'totalServicios',
            'totalVentas',
            'totalGastos',
            'totalIngresos',
            'balance',
            'ultimosMovimientos',
            'meses',
            'ingresosPorMes',
            'gastosPorMes',
            'gastosPorCategoria'
        ));
    }
}
