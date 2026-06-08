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
    .linea-tec-select {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      padding-right: 2.75rem !important;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5.5 7.5L10 12l4.5-4.5' stroke='%236B7280' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.85rem center;
      background-size: 0.9rem;
    }

    .linea-tec-select::-ms-expand {
      display: none;
    }

    @media (max-width: 768px) {
      .estadisticas-main-wrap {
        padding-top: 1.25rem;
        padding-bottom: 1.5rem;
      }

      .estadisticas-titulo {
        margin-bottom: 1.25rem;
      }

      .estadisticas-grid-resumen {
        gap: 0.75rem;
        margin-bottom: 1rem;
      }

      .estadisticas-resumen-item {
        padding: 1rem;
      }

      .estadisticas-resumen-item .estadisticas-resumen-contenido {
        justify-content: flex-start;
      }

      .estadisticas-resumen-item .estadisticas-resumen-texto {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
      }

      .estadisticas-global-report {
        width: 100%;
        justify-content: center;
      }

      .estadisticas-global-report-wrap {
        margin-bottom: 1rem;
      }

      .estadisticas-card {
        padding: 1rem;
        margin-bottom: 1rem;
      }

      .estadisticas-bar-header {
        margin-bottom: 0.75rem;
      }

      .estadisticas-filtros-barra {
        width: 100%;
      }

      .estadisticas-filtros-barra > div {
        width: 100%;
      }

      .estadisticas-filtros-barra select {
        width: 100%;
      }

      .estadisticas-bar-report-wrap {
        justify-content: stretch;
      }

      .estadisticas-bar-report {
        width: 100%;
        justify-content: center;
      }

      .estadisticas-pie-header {
        align-items: stretch;
      }

      .estadisticas-pie-report {
        width: 100%;
        justify-content: center;
        margin-left: 0;
      }

      .estadisticas-pie-content {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
      }

      .estadisticas-bar-chart-wrap {
        height: 290px;
      }

      .estadisticas-pie-chart-wrap {
        width: 100%;
        max-width: 220px;
        margin: 0 auto;
      }

      .estadisticas-pie-legend {
        width: 100%;
        gap: 0.5rem;
      }
    }
  </style>
</head>

<body class="bg-white min-h-screen">
  <!-- Contenido principal -->
  <main class="flex-1">
    <div class="estadisticas-main-wrap max-w-[80rem] mx-auto px-4 py-8 lg:px-8">
      <!-- Título -->
      <div class="estadisticas-titulo mb-8">
        <h1 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Estadísticas</h1>
        <p class="mt-2 text-sm text-sena-text-soft">Análisis de perfiles ocupacionales, oferta de formación y distribución de solicitudes por línea tecnológica.</p>
      </div>
      
      <!-- Tarjetas de estadísticas -->
      <div class="estadisticas-grid-resumen grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Total Perfiles -->
        <div class="estadisticas-resumen-item bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="estadisticas-resumen-contenido flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sena-soft flex items-center justify-center">
              <i data-lucide="users" class="w-6 h-6 text-sena-strong"></i>
            </div>
            <div class="estadisticas-resumen-texto flex items-center gap-2">
              <p id="totalPerfilesCount" class="text-3xl font-bold text-sena-text-main leading-none">156</p>
              <p class="text-sm text-sena-text-muted leading-none">Total Perfiles</p>
            </div>
          </div>
        </div>

        <!-- Líneas Tecnológicas -->
        <div class="estadisticas-resumen-item bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="estadisticas-resumen-contenido flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
              <i data-lucide="grid-3x3" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="estadisticas-resumen-texto flex items-center gap-2">
              <p id="lineasCount" class="text-3xl font-bold text-sena-text-main leading-none">12</p>
              <p class="text-sm text-sena-text-muted leading-none">Líneas Tecnológicas</p>
            </div>
          </div>
        </div>

        <!-- Solicitudes Registradas -->
        <div class="estadisticas-resumen-item bg-white rounded-xl border border-sena-border p-6 shadow-sm hover:shadow-md transition-shadow">
          <div class="estadisticas-resumen-contenido flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
              <i data-lucide="clipboard-list" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div class="estadisticas-resumen-texto flex items-center gap-2">
              <p id="solicitudesCount" class="text-3xl font-bold text-sena-text-main leading-none">117</p>
              <p class="text-sm text-sena-text-muted leading-none">Solicitudes Registradas</p>
            </div>
          </div>
        </div>
    </div>

      <!-- Reporte global -->
      <div class="estadisticas-global-report-wrap flex items-center justify-center gap-3 mb-8">
        <button id="globalReportBtn" class="estadisticas-global-report flex items-center gap-2 px-4 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm">
          <i data-lucide="file-text" class="w-4 h-4 text-sena-text-muted"></i>
          Generar reporte global de estadísticas
        </button>
      </div>

      <!-- Gráfica de barras -->
      <div class="estadisticas-card bg-white rounded-xl border border-sena-border p-6 mb-8 shadow-sm">
        <!-- Título -->
        <div class="estadisticas-bar-header mb-4 grid gap-3 lg:grid-cols-[1fr_auto] lg:items-start">
          <div class="min-w-0 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
              <i data-lucide="bar-chart-3" class="w-5 h-5 text-slate-600"></i>
            </div>
            <div class="min-w-0 max-w-none">
              <h2 class="text-[15px] font-semibold text-sena-text-main">Necesidades Empresariales vs Oferta SENA</h2>
              <p class="text-[12px] text-sena-text-muted mt-0.5 leading-relaxed">Comparación entre perfiles ocupacionales solicitados por el sector productivo y la oferta de programas de formación del SENA.</p>
            </div>
          </div>
          <div class="w-full lg:w-auto lg:justify-self-end">
            <div class="estadisticas-filtros-barra flex flex-wrap items-end justify-start lg:justify-end gap-2">
              <div>
                <label for="monthFilter" class="mb-1 block text-sm font-medium text-sena-text-main">Mes</label>
                <select id="monthFilter" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20">
                  <option value="all">Todos</option>
                  <option value="01">Enero</option>
                  <option value="02">Febrero</option>
                  <option value="03">Marzo</option>
                  <option value="04">Abril</option>
                  <option value="05">Mayo</option>
                  <option value="06">Junio</option>
                  <option value="07">Julio</option>
                  <option value="08">Agosto</option>
                  <option value="09">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
              <div>
                <label for="yearFilter" class="mb-1 block text-sm font-medium text-sena-text-main">Año</label>
                <select id="yearFilter" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20">
                  <option value="all">Todos</option>
                  <option value="2024">2024</option>
                  <option value="2025">2025</option>
                  <option value="2026">2026</option>
                </select>
              </div>
            </div>
          </div>
          <div class="estadisticas-bar-report-wrap flex justify-center lg:col-span-2">
            <button id="barReportBtn" class="estadisticas-bar-report inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm whitespace-nowrap">
              <i data-lucide="file-chart-column" class="w-4 h-4 text-sena-text-muted"></i>
              Reporte individual
            </button>
          </div>
        </div>

      <!-- Canvas para Chart.js -->
      <div class="estadisticas-bar-chart-wrap relative h-[350px]">
        <canvas id="barChart"></canvas>
      </div>
    </div>

      <!-- Gráfica circular -->
      <div class="estadisticas-card bg-white rounded-xl border border-sena-border p-6 mb-8 shadow-sm">
        <!-- Título -->
        <div class="estadisticas-pie-header flex flex-col gap-3 mb-6 md:flex-row md:items-start md:justify-between">
          <div class="flex items-start gap-3">
          <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
            <i data-lucide="pie-chart" class="w-5 h-5 text-slate-600"></i>
          </div>
          <div>
            <h2 class="text-[15px] font-semibold text-sena-text-main">Proporción de Perfiles Solicitados por Línea Tecnológica</h2>
            <p class="text-[12px] text-sena-text-muted mt-0.5">Muestra cuáles líneas tecnológicas concentran la mayor demanda de perfiles por parte del sector productivo.</p>
          </div>
          </div>
          <button id="pieReportBtn" class="estadisticas-pie-report inline-flex w-auto items-center gap-2 px-3 py-2.5 bg-white border border-sena-border rounded-lg text-[13px] text-sena-text-muted hover:bg-sena-soft hover:border-sena-strong transition-all duration-200 shadow-sm md:ml-4">
            <i data-lucide="file-chart-column" class="w-4 h-4 text-sena-text-muted"></i>
            Reporte individual
          </button>
        </div>

      <div class="estadisticas-pie-content flex items-center gap-12">
        <!-- Canvas para gráfico circular -->
        <div class="estadisticas-pie-chart-wrap w-[220px] h-[220px] flex-shrink-0">
          <canvas id="pieChart"></canvas>
        </div>

        <!-- Leyenda -->
        <div id="pieLegend" class="estadisticas-pie-legend flex-1 space-y-3"></div>
      </div>
    </div>

  </div>
<script src="../../assets/js/estadisticas/estadisticas.js"></script>
</main>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>