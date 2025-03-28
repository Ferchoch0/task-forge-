document.addEventListener("DOMContentLoaded", function () {
    fetch('../Controller/balanceController.php') 
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data); // Debugging en la consola

            if (!data.daily.fechas || data.daily.fechas.length === 0) {
                console.error("No se recibieron datos válidos.");
                return;
            }

            

            // Gráfico de ventas diarias
            const ctx = document.getElementById('graficoBalance').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.daily.fechas,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: data.daily.ingresos,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Egresos',
                            data: data.daily.egresos,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico de ventas semanales
            const ctxs = document.getElementById('graficoBalanceSemanal').getContext('2d');
            new Chart(ctxs, {
                type: 'bar',
                data: {
                    labels: data.weekly.fechas,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: data.weekly.ingresos,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Egresos',
                            data: data.weekly.egresos,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico de ventas por hora
            const ctxh = document.getElementById('graficoBalancePorHora').getContext('2d');
            new Chart(ctxh, {
                type: 'line',
                data: {
                    labels: data.hourly.horas,
                    datasets: [
                        {
                            label: 'Ingresos por Hora',
                            data: data.hourly.ingresos,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            fill: true,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            const ctxw = document.getElementById('graficoBalancePorSemana').getContext('2d');
            new Chart(ctxw, {
                type: 'line',
                data: {
                    labels: data.weekly_sales_only.fechas,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: data.weekly_sales_only.ingresos,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            fill: true,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .catch(error => console.error('Error al cargar los datos del balance:', error));
});