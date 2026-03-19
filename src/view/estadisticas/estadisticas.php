<?php
include __DIR__ . '../../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estadísticas - SENA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>

<body class="bg-white min-h-screen">
  <!-- Contenido principal -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">
      <!-- Título -->
      <div class="mb-8">
        <h1 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Estadísticas</h1>
        <p class="mt-2 text-sm text-sena-text-soft">Análisis de perfiles tecnológicos, líneas tecnológicas y tecnologías emergentes del SENA</p>
      </div>
      
      <!-- Tarjetas de estadísticas -->
      <div class="grid grid-cols-4 gap-5 mb-8">
        <!-- Total Perfiles -->
        <div class="bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sena-soft flex items-center justify-center">
              <i data-lucide="users" class="w-6 h-6 text-sena-strong"></i>
            </div>
            <div>
              <p class="text-3xl font-bold text-sena-text-main">156</p>
              <p class="text-[12px] text-sena-text-muted">Total Perfiles</p>
            </div>
          </div>
        </div>

        <!-- Líneas Tecnológicas -->
        <div class="bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
              <i data-lucide="grid-3x3" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div>
              <p class="text-3xl font-bold text-sena-text-main">12</p>
              <p class="text-[12px] text-sena-text-muted">Líneas Tecnológicas</p>
            </div>
          </div>
        </div>

        <!-- Tecnologías Emergentes -->
        <div class="bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
              <i data-lucide="lightbulb" class="w-6 h-6 text-amber-600"></i>
            </div>
            <div>
              <p class="text-3xl font-bold text-sena-text-main">48</p>
              <p class="text-[12px] text-sena-text-muted">Tecnologías Emergentes</p>
            </div>
          </div>
        </div>

        <!-- Empresas Registradas -->
        <div class="bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
              <i data-lucide="building" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div>
              <p class="text-3xl font-bold text-sena-text-main">89</p>
              <p class="text-[12px] text-sena-text-muted">Empresas Registradas</p>
            </div>
          </div>
        </div>
    </div>

      <!-- Selector de mes -->
      <div class="flex justify-center mb-8">
        <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm">
          <i data-lucide="calendar" class="w-4 h-4 text-sena-text-muted"></i>
          Seleccione el mes
          <i data-lucide="chevron-down" class="w-4 h-4 text-sena-text-muted"></i>
        </button>
      </div>

      <!-- Gráfica de barras -->
      <div class="bg-white rounded-xl border border-sena-border p-6 mb-8 shadow-sm">
        <!-- Título -->
        <div class="flex items-start gap-3 mb-4">
          <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-slate-600"></i>
          </div>
          <div>
            <h2 class="text-[15px] font-semibold text-sena-text-main">Necesidades Empresariales vs Oferta SENA</h2>
            <p class="text-[12px] text-sena-text-muted mt-0.5 leading-relaxed">Comparación entre los perfiles solicitados por las empresas en cada línea tecnológica y las tecnologías emergentes que el SENA ofrece para cubrir esas necesidades.</p>
          </div>
        </div>

      <!-- Canvas para Chart.js -->
      <div class="relative h-[350px]">
        <canvas id="barChart"></canvas>
      </div>
    </div>

      <!-- Gráfica circular -->
      <div class="bg-white rounded-xl border border-sena-border p-6 mb-8 shadow-sm">
        <!-- Título -->
        <div class="flex items-start gap-3 mb-6">
          <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
            <i data-lucide="pie-chart" class="w-5 h-5 text-slate-600"></i>
          </div>
          <div>
            <h2 class="text-[15px] font-semibold text-sena-text-main">Distribución de Tecnologías Emergentes por Línea</h2>
            <p class="text-[12px] text-sena-text-muted mt-0.5">Porcentaje de tecnologías emergentes asociadas a cada línea tecnológica del SENA.</p>
          </div>
        </div>

      <div class="flex items-center gap-12">
        <!-- Canvas para gráfico circular -->
        <div class="w-[220px] h-[220px] flex-shrink-0">
          <canvas id="pieChart"></canvas>
        </div>

        <!-- Leyenda -->
        <div class="flex-1 space-y-3">
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#22c55e]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Tecnologías de la Información</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">6</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">50.0%</span>
          </div>
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#3b82f6]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Automatización Industrial</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">2</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">25.0%</span>
          </div>
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#eab308]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Energías Renovables</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">3</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">18.0%</span>
          </div>
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#ef4444]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Biotecnología</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">2</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">15.0%</span>
          </div>
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#8b5cf6]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Nanotecnología</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">1</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">12.0%</span>
          </div>
          <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
            <div class="flex items-center gap-3 flex-1">
              <div class="w-3 h-3 rounded-full bg-[#f97316]"></div>
              <span class="text-[13px] text-sena-text-main font-medium">Materiales Avanzados</span>
            </div>
            <span class="text-[13px] text-sena-text-muted font-medium">1</span>
            <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">10.0%</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
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
  </script>
</main>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>