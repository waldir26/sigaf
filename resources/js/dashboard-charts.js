document.addEventListener('DOMContentLoaded', function () {
    // Gráfico Ingresos vs Gastos
    const ctx1 = document.getElementById('ingresosGastosChart');
    if (ctx1 && ctx1.dataset.meses) {
        const meses = JSON.parse(ctx1.dataset.meses);
        const ingresos = JSON.parse(ctx1.dataset.ingresos);
        const gastos = JSON.parse(ctx1.dataset.gastos);

        new Chart(ctx1.getContext('2d'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: ingresos,
                        backgroundColor: 'rgba(40, 167, 69, 0.6)',
                        borderColor: '#28a745',
                        borderWidth: 1
                    },
                    {
                        label: 'Gastos',
                        data: gastos,
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: '#dc3545',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico Gastos por Categoría
    const ctx2 = document.getElementById('gastosCategoriaChart');
    if (ctx2 && ctx2.dataset.categorias) {
        let categorias = JSON.parse(ctx2.dataset.categorias);
        let montos = JSON.parse(ctx2.dataset.montos);

        montos = montos.map(m => parseFloat(m));

        let total = 0;
        for (let i = 0; i < montos.length; i++) {
            total = total + montos[i];
        }

        const colores = ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8', '#6f42c1', '#e83e8c', '#20c997', '#6c757d'];

        new Chart(ctx2.getContext('2d'), {
            type: 'pie',
            data: {
                labels: categorias,
                datasets: [{
                    data: montos,
                    backgroundColor: colores.slice(0, categorias.length),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Participantes por Sexo
    const ctxSexo = document.getElementById('sexoChart');
    if (ctxSexo) {
        const masculino = parseInt(ctxSexo.dataset.masculino) || 0;
        const femenino = parseInt(ctxSexo.dataset.femenino) || 0;
        const total = masculino + femenino;

        new Chart(ctxSexo.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Masculino', 'Femenino'],
                datasets: [{
                    data: [masculino, femenino],
                    backgroundColor: ['#17a2b8', '#e83e8c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
});