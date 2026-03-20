    // Datos para la gráfica de barras
    const barData = {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial', 
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados',
        'Robótica',
        'Inteligencia Artificial'
      ],
      datasets: [
        {
          label: 'Personal Solicitado',
          data: [28, 18, 15, 20, 14, 22, 16, 18],
          backgroundColor: '#22c55e',
          borderRadius: 4,
          barThickness: 20
        },
        {
          label: 'Cupos Disponibles',
          data: [24, 16, 13, 18, 12, 20, 14, 16],
          backgroundColor: '#3b82f6',
          borderRadius: 4,
          barThickness: 20
        }
      ]
    };

    // Configuración de la gráfica de barras
    const barConfig = {
      type: 'bar',
      data: barData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              pointStyle: 'rect',
              padding: 20,
              font: {
                size: 12,
                family: 'Inter'
              },
              color: '#475569'
            }
          },
          tooltip: {
            backgroundColor: '#1e293b',
            titleFont: {
              size: 12,
              family: 'Inter'
            },
            bodyFont: {
              size: 11,
              family: 'Inter'
            },
            padding: 10,
            cornerRadius: 8
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                size: 10,
                family: 'Inter'
              },
              color: '#94a3b8',
              maxRotation: 45,
              minRotation: 45,
              callback: function(value, index) {
                const label = this.getLabelForValue(value);
                return label.length > 15 ? label.substring(0, 13) + '...' : label;
              }
            }
          },
          y: {
            grid: {
              color: '#e2e8f0',
              drawBorder: false
            },
            ticks: {
              font: {
                size: 11,
                family: 'Inter'
              },
              color: '#94a3b8',
              stepSize: 2
            },
            beginAtZero: true,
            max: 32
          }
        }
      }
    };

    // Datos para la gráfica circular
    const pieData = {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial',
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados'
      ],
      datasets: [{
        data: [25, 20, 18, 15, 12, 10],
        backgroundColor: [
          '#22c55e',
          '#3b82f6',
          '#eab308',
          '#ef4444',
          '#8b5cf6',
          '#f97316'
        ],
        borderWidth: 0,
        cutout: '60%'
      }]
    };

    // Configuración de la gráfica circular
    const pieConfig = {
      type: 'doughnut',
      data: pieData,
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#1e293b',
            titleFont: {
              size: 12,
              family: 'Inter'
            },
            bodyFont: {
              size: 11,
              family: 'Inter'
            },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                return context.label + ': ' + context.parsed + '%';
              }
            }
          }
        }
      }
    };

    // Inicializar las gráficas
    document.addEventListener('DOMContentLoaded', function() {
      const barCtx = document.getElementById('barChart').getContext('2d');
      new Chart(barCtx, barConfig);

      const pieCtx = document.getElementById('pieChart').getContext('2d');
      new Chart(pieCtx, pieConfig);

      // Inicializar iconos de Lucide
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });