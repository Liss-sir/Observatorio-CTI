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

      <!-- Selector de mes y generación de reporte -->
      <div class="flex items-center justify-center gap-3 mb-8">
        <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm">
          <i data-lucide="file-text" class="w-4 h-4 text-sena-text-muted"></i>
          Generar reporte de estadísticas
        </button>
      </div>

      <!-- Gráfica de barras -->
      <div class="bg-white rounded-xl border border-sena-border p-6 mb-8 shadow-sm">
        <!-- Título -->
        <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-start md:justify-between">
          <div class="flex items-start gap-3 md:pr-4">
            <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
              <i data-lucide="bar-chart-3" class="w-5 h-5 text-slate-600"></i>
            </div>
            <div>
              <h2 class="text-[15px] font-semibold text-sena-text-main">Necesidades Empresariales vs Oferta SENA</h2>
              <p class="text-[12px] text-sena-text-muted mt-0.5 leading-relaxed">Comparación entre los perfiles solicitados por las empresas en cada línea tecnológica y las tecnologías emergentes que el SENA ofrece para cubrir esas necesidades.</p>
            </div>
          </div>
          <button class="inline-flex w-auto items-center justify-between gap-3 px-4 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm md:ml-4">
            <span class="inline-flex items-center gap-2">
              <i data-lucide="calendar" class="w-4 h-4 text-sena-text-muted"></i>
              <span class="whitespace-nowrap">Seleccione el mes</span>
            </span>
            <i data-lucide="chevron-down" class="w-4 h-4 text-sena-text-muted"></i>
          </button>
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
<script src="../../assets/js/estadisticas/estadisticas.js"></script>
</main>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>