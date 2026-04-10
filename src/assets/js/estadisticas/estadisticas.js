const monthNames = {
  '01': 'Enero',
  '02': 'Febrero',
  '03': 'Marzo',
  '04': 'Abril',
  '05': 'Mayo',
  '06': 'Junio',
  '07': 'Julio',
  '08': 'Agosto',
  '09': 'Septiembre',
  '10': 'Octubre',
  '11': 'Noviembre',
  '12': 'Diciembre'
};

const pieColors = ['#22c55e', '#3b82f6', '#eab308', '#ef4444', '#8b5cf6', '#f97316'];
const API_URL = new URL('../../controllers/EstadisticaController.php', window.location.href).toString();

let barChart;
let pieChart;
let statsBootstrapped = false;
let reportDownloadInProgress = false;
const statsState = {
  summary: {
    total_perfiles_activos: 0,
    total_lineas_activas: 0,
    total_cupos_demandados: 0,
  },
  bar: {
    labels: [],
    demand: [],
    offer: [],
  },
  pie: {
    labels: [],
    values: [],
    inactive: [],  // Líneas sin actividad (0%)
  },
};

function getEmptyPeriodData() {
  return {
    bar: { labels: [], demand: [], offer: [] },
    pie: { labels: [], values: [], inactive: [] },
  };
}

function getCurrentPeriodData() {
  return {
    bar: {
      labels: statsState.bar.labels,
      demand: statsState.bar.demand,
      offer: statsState.bar.offer,
    },
    pie: {
      labels: statsState.pie.labels,
      values: statsState.pie.values,
      inactive: statsState.pie.inactive,
    },
  };
}

function getSelectedBarFilters() {
  const month = document.getElementById('monthFilter')?.value || 'all';
  const year = document.getElementById('yearFilter')?.value || 'all';

  return { month, year };
}

function hasValidBarFilters(filters) {
  return !(filters.month !== 'all' && filters.year === 'all');
}

function buildComparativaUrl(filters = getSelectedBarFilters()) {
  const url = new URL(API_URL);
  url.searchParams.set('accion', 'comparativa');

  if (filters.year !== 'all') {
    url.searchParams.set('anio', filters.year);
  }

  if (filters.month !== 'all') {
    url.searchParams.set('mes', filters.month);
  }

  return url.toString();
}

function syncMonthFilterState(monthFilter, yearFilter) {
  if (!monthFilter || !yearFilter) {
    return;
  }

  const yearSelected = yearFilter.value !== 'all';
  monthFilter.disabled = !yearSelected;

  if (!yearSelected) {
    monthFilter.value = 'all';
  }

  monthFilter.classList.toggle('opacity-60', !yearSelected);
  monthFilter.classList.toggle('cursor-not-allowed', !yearSelected);
  monthFilter.classList.toggle('bg-slate-100', !yearSelected);
}

function showStatsError(message) {
  const existing = document.getElementById('stats-error-banner');
  if (existing) {
    existing.textContent = message;
    existing.classList.remove('hidden');
    return;
  }

  const mainTitle = document.querySelector('main h1');
  if (!mainTitle) {
    return;
  }

  const banner = document.createElement('div');
  banner.id = 'stats-error-banner';
  banner.className = 'mt-3 rounded-lg border border-orange-300 bg-orange-50 px-3 py-2 text-sm text-orange-700';
  banner.textContent = message;

  const container = mainTitle.closest('div');
  if (container) {
    container.appendChild(banner);
  }
}

function hideStatsError() {
  const existing = document.getElementById('stats-error-banner');
  if (existing) {
    existing.classList.add('hidden');
  }
}

function setReportButtonsState(isLoading) {
  const buttonIds = ['globalReportBtn', 'barReportBtn', 'pieReportBtn'];

  buttonIds.forEach((id) => {
    const button = document.getElementById(id);
    if (!button) return;

    button.disabled = isLoading;
    button.classList.toggle('opacity-60', isLoading);
    button.classList.toggle('cursor-not-allowed', isLoading);
  });
}

function buildBarData(periodData) {
  function buildBarData(periodData) {
  // Convierte todos los valores a números
  const demand = (periodData.bar.demand || []).map(v => Number(v) || 0);
  const offer = (periodData.bar.offer || []).map(v => Number(v) || 0);
  
  const hasPositiveValues = [...demand, ...offer].some(v => v > 0);
  
  // ... resto del código, pero usando demand y offer en lugar de periodData.bar.demand/offer
  if (!hasPositiveValues) {
    return {
      labels: [],
      datasets: [
        {
          label: 'Perfiles Solicitados',
          data: [],
          backgroundColor: '#22c55e',
          borderRadius: 4,
          barThickness: 20
        },
        {
          label: 'Programas de Formación (Oferta)',
          data: [],
          backgroundColor: '#3b82f6',
          borderRadius: 4,
          barThickness: 20
        }
      ]
    };
  }
}
  return {
    labels: periodData.bar.labels,
    datasets: [
      {
        label: 'Perfiles Solicitados',
        data: periodData.bar.demand,
        backgroundColor: '#22c55e',
        borderRadius: 4,
        barThickness: 20
      },
      {
        label: 'Programas de Formación (Oferta)',
        data: periodData.bar.offer,
        backgroundColor: '#3b82f6',
        borderRadius: 4,
        barThickness: 20
      }
    ]
  };
}

function buildPieData(periodData) {
  return {
    labels: periodData.pie.labels,
    datasets: [
      {
        data: periodData.pie.values,
        backgroundColor: pieColors,
        borderWidth: 0,
        cutout: '60%'
      }
    ]
  };
}

function hasChartData(chart) {
  const labels = Array.isArray(chart?.data?.labels) ? chart.data.labels : [];
  const datasets = Array.isArray(chart?.data?.datasets) ? chart.data.datasets : [];

  if (labels.length === 0) {
    return false;
  }

  return datasets.some((dataset) => {
    const values = Array.isArray(dataset?.data) ? dataset.data : [];
    return values.some((value) => Number(value) > 0);
  });
}

const noDataPlugin = {
  id: 'noDataPlugin',
  beforeDraw(chart) {
    if (hasChartData(chart)) {
      return;
    }

    const { ctx, chartArea } = chart;
    if (!chartArea) {
      return;
    }

    const text = chart?.options?.plugins?.noDataPlugin?.text || 'No hay datos para mostrar';
    const subtext = chart?.options?.plugins?.noDataPlugin?.subtext || 'Ajusta el filtro o selecciona otro período';
    const centerX = (chartArea.left + chartArea.right) / 2;
    const centerY = (chartArea.top + chartArea.bottom) / 2;

    ctx.save();
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    ctx.fillStyle = '#94a3b8';
    ctx.font = '600 15px Inter, sans-serif';
    ctx.fillText(text, centerX, centerY - 8);

    ctx.fillStyle = '#cbd5e1';
    ctx.font = '400 12px Inter, sans-serif';
    ctx.fillText(subtext, centerX, centerY + 14);

    ctx.restore();
  }
};

    // Configuración de la gráfica de barras
const barConfig = {
      type: 'bar',
  data: buildBarData(getEmptyPeriodData()),
      plugins: [noDataPlugin],
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          noDataPlugin: {
            text: 'No hay datos para esta gráfica',
            subtext: 'Prueba con otro filtro de año o mes'
          },
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
              callback: function(value) {
                // Formatea números grandes
                if (value >= 1000) return (value / 1000) + 'k';
                return value;
              }
            },
            beginAtZero: true,
            // No fijes max aquí, se calculará dinámicamente
          }
        }
      }
    };

    // Configuración de la gráfica circular
const pieConfig = {
      type: 'doughnut',
  data: buildPieData(getEmptyPeriodData()),
      plugins: [noDataPlugin],
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          noDataPlugin: {
            text: 'No hay solicitudes registradas',
            subtext: 'No existen líneas activas para mostrar'
          },
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
                const values = context.dataset.data || [];
                const total = values.reduce((acc, value) => acc + value, 0);
                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0.0';
                return context.label + ': ' + context.parsed + ' perfiles (' + percentage + '%)';
              }
            }
          }
        }
      }
    };

function adaptComparativaToBarData(payload) {
  const areas = Array.isArray(payload?.por_area) ? payload.por_area : [];
  return {
    labels: areas.map((item) => String(item?.nombre_area || 'Sin área')),
    demand: areas.map((item) => Number(item?.necesidades_empresariales?.cupos_demandados || 0)),
    offer: areas.map((item) => Number(item?.ofertas_formacion?.cupos_ofertados || 0)),
  };
}

function adaptDistribucionToPieData(payload) {
  const distribucion = Array.isArray(payload?.distribucion) ? payload.distribucion : [];
  
  const active = [];
  const inactive = [];
  
  distribucion.forEach((item) => {
    const label = String(item?.nombre_linea || 'Sin línea');
    const value = Number(item?.estadisticas?.total_cupos_solicitados || 0);
    
    if (value > 0) {
      active.push({ label, value });
    } else {
      inactive.push(label);
    }
  });
  
  return {
    labels: active.map(item => item.label),
    values: active.map(item => item.value),
    inactive: inactive,
  };
}

async function fetchJson(url) {
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`HTTP ${response.status}`);
  }

  const contentType = String(response.headers.get('content-type') || '').toLowerCase();
  const raw = await response.text();

  let payload = null;
  try {
    payload = JSON.parse(raw);
  } catch (error) {
    const snippet = raw.slice(0, 180).replace(/\s+/g, ' ').trim();
    throw new Error(`Respuesta no JSON desde backend (${contentType || 'sin content-type'}): ${snippet || 'vacía'}`);
  }

  const success = payload?.success === true || payload?.status === 'success';
  if (!success) {
    throw new Error(String(payload?.error || payload?.message || 'Error al consultar estadísticas'));
  }

  return payload;
}

async function cargarEstadisticasDesdeBackend() {
  const barFilters = getSelectedBarFilters();
  const resultados = await Promise.allSettled([
    fetchJson(`${API_URL}?accion=completas`),
    fetchJson(buildComparativaUrl(barFilters)),
    fetchJson(`${API_URL}?accion=distribucion`),
  ]);

  const [completasResult, comparativaResult, distribucionResult] = resultados;

  if (completasResult.status === 'fulfilled') {
    const completas = completasResult.value?.data || {};
    statsState.summary = completas?.resumen || statsState.summary;

    const comparativaCompleta = completas?.comparativa_perfiles_vs_ofertas;
    if (comparativaCompleta && !comparativaCompleta.error) {
      statsState.bar = adaptComparativaToBarData(comparativaCompleta);
    }

    const distribucionCompleta = completas?.distribucion_lineas_tecnologicas;
    if (distribucionCompleta && !distribucionCompleta.error) {
      statsState.pie = adaptDistribucionToPieData(distribucionCompleta);
    }
  }

  if (comparativaResult.status === 'fulfilled') {
    const barFromComparativa = adaptComparativaToBarData(comparativaResult.value?.data || {});
    if ((statsState.bar.labels || []).length === 0 || barFromComparativa.labels.length > 0) {
      statsState.bar = barFromComparativa;
    }
  }

  if (distribucionResult.status === 'fulfilled') {
    const pieFromDistribucion = adaptDistribucionToPieData(distribucionResult.value?.data || {});
    if ((statsState.pie.labels || []).length === 0 || pieFromDistribucion.labels.length > 0) {
      statsState.pie = pieFromDistribucion;
    }
  }

  const errores = resultados
    .filter((item) => item.status === 'rejected')
    .map((item) => String(item.reason?.message || item.reason || 'Error desconocido'));

  if (errores.length) {
    console.error('Errores al cargar estadísticas:', errores);
    showStatsError(`No se pudieron cargar algunas fuentes de estadísticas: ${errores[0]}`);
  }

  const sinDatosBarra = (statsState.bar.labels || []).length === 0;
  const sinDatosPie = (statsState.pie.labels || []).length === 0;
  if (sinDatosBarra && sinDatosPie) {
    throw new Error(errores[0] || 'El backend no devolvió datos para estadísticas.');
  }

  statsBootstrapped = true;
  hideStatsError();
}

async function cargarBarChartDesdeBackend(animate = true) {
  const filters = getSelectedBarFilters();

  if (!hasValidBarFilters(filters)) {
    showStatsError('Para filtrar por mes debes seleccionar también un año.');
    return false;
  }

  try {
    const response = await fetchJson(buildComparativaUrl(filters));
    const comparativa = response?.data || {};

    if (!comparativa.error) {
      statsState.bar = adaptComparativaToBarData(comparativa);
      updateBarChart(getCurrentPeriodData(), animate);
    }

    hideStatsError();
    return true;
  } catch (error) {
    showStatsError(`No fue posible actualizar la gráfica de barras: ${String(error?.message || error)}`);
    return false;
  }
}

async function ensureReportDataReady(reportType) {
  if (!statsBootstrapped) {
    await cargarEstadisticasDesdeBackend();
    updateCharts(getCurrentPeriodData(), false);
  }

  if (reportType === 'global' || reportType === 'bar') {
    const barOk = await cargarBarChartDesdeBackend(false);
    if (!barOk) {
      return false;
    }
  }

  // Renderiza sin animación para capturar imágenes estables en el PDF.
  barChart?.update('none');
  pieChart?.update('none');

  await waitForStableCharts({ forceScroll: true });

  return true;
}

function waitMs(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

async function waitForStableCharts({ forceScroll = false } = {}) {
  const originalScrollTop = window.scrollY || document.documentElement.scrollTop || 0;
  const barContainer = barChart?.canvas?.parentElement;

  if (forceScroll && barContainer) {
    barContainer.scrollIntoView({ behavior: 'auto', block: 'center' });
    await waitMs(180);
  }

  barChart?.update('none');
  pieChart?.update('none');
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  await waitMs(220);

  barChart?.update('none');
  pieChart?.update('none');
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
  await waitMs(220);

  if (forceScroll) {
    window.scrollTo(0, originalScrollTop);
  }
}

function getAppliedFiltersText() {
  const month = document.getElementById('monthFilter')?.value || 'all';
  const year = document.getElementById('yearFilter')?.value || 'all';

  const monthLabel = month === 'all' ? 'Todos' : monthNames[month];
  const yearLabel = year === 'all' ? 'Todos' : year;

  return `Mes y Año (gráfica de barras): ${monthLabel} / ${yearLabel}`;
}

function getAppliedFiltersTextForReport(reportType) {
  if (reportType === 'pie') {
    return 'No aplica. Esta gráfica no usa filtro de mes/año.';
  }

  return getAppliedFiltersText();
}

function renderPieLegend(labels, values, colors, inactiveLines = []) {
  const legend = document.getElementById('pieLegend');
  if (!legend) return;

  const total = values.reduce((acc, value) => acc + value, 0);

  const activeHtml = labels
    .map((label, index) => {
      const value = values[index] || 0;
      const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
      const color = colors[index] || '#94a3b8';

      return `
        <div class="flex items-center gap-3 justify-between px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200">
          <div class="flex items-center gap-3 flex-1">
            <div class="w-3 h-3 rounded-full" style="background-color:${color}"></div>
            <span class="text-[13px] text-sena-text-main font-medium">${label}</span>
          </div>
          <span class="text-[13px] text-sena-text-muted font-medium">${value}</span>
          <span class="text-[13px] text-sena-text-muted font-medium min-w-[60px] text-right">${percentage}%</span>
        </div>
      `;
    })
    .join('');

  const inactiveHtml = inactiveLines.length > 0 ? `
    <div class="mt-4 pt-3 border-t border-slate-200">
      <p class="text-[10px] font-semibold text-slate-500 mb-2">Líneas Tecnológicas sin solicitudes registradas</p>
      <div class="grid grid-cols-2 gap-1">
        ${inactiveLines
          .map((label) => `
            <div class="flex items-center gap-1 px-2 py-0.5 bg-slate-50 rounded border border-slate-200">
              <div class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:#d1d5db"></div>
              <span class="text-[10px] text-slate-500 truncate">${label}</span>
              <span class="text-[9px] text-slate-400 flex-shrink-0">(0)</span>
          </div>
          `)
          .join('')}
      </div>
    </div>
  ` : '';

  legend.innerHTML = activeHtml + inactiveHtml;
}

function updateCounters(periodData) {
  const totalPerfilesEl = document.getElementById('totalPerfilesCount');
  const lineasCountEl = document.getElementById('lineasCount');
  const solicitudesCountEl = document.getElementById('solicitudesCount');

  const totalPerfiles = Number(statsState.summary?.total_perfiles_activos || 0);
  const totalLineas = Number(statsState.summary?.total_lineas_activas || periodData.pie.labels.length || 0);
  const totalSolicitudes = Number(statsState.summary?.total_cupos_demandados || periodData.pie.values.reduce((acc, value) => acc + value, 0));

  if (totalPerfilesEl) totalPerfilesEl.textContent = String(totalPerfiles);
  if (lineasCountEl) lineasCountEl.textContent = String(totalLineas);
  if (solicitudesCountEl) solicitudesCountEl.textContent = String(totalSolicitudes);
}

function updateCharts(periodData, animate = true) {
  barChart.data = buildBarData(periodData);
  pieChart.data = buildPieData(periodData);

  barChart.update(animate ? undefined : 'none');
  pieChart.update(animate ? undefined : 'none');

  renderPieLegend(periodData.pie.labels, periodData.pie.values, pieColors, periodData.pie.inactive);
  updateCounters(periodData);
}

function updateBarChart(periodData, animate = true) {
  barChart.data = buildBarData(periodData);
  const maxSerie = Math.max(...periodData.bar.demand, ...periodData.bar.offer, 0);
  // Aumenta el margen superior para que las barras no se corten
  barChart.options.scales.y.max = Math.max(10, Math.ceil(maxSerie * 1.15));
  barChart.update(animate ? undefined : 'none');
}

function reportStyles() {
  return `
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      body { 
        font-family: Inter, Arial, sans-serif; 
        font-size: 11px; 
        padding: 20px;
      }
      .report-header { 
        border-bottom: 2px solid #22c55e; 
        padding-bottom: 10px; 
        margin-bottom: 20px; 
      }
      .report-title { 
        font-size: 18px; 
        font-weight: 700; 
        margin-bottom: 10px;
      }
      .meta-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 5px; 
      }
      .meta { 
        font-size: 10px; 
        color: #334155; 
      }
      .section { 
        margin-top: 12px; 
        margin-bottom: 16px; 
        border: 1px solid #e2e8f0; 
        border-radius: 10px; 
        padding: 12px; 
        page-break-inside: avoid; 
        break-inside: avoid;
        page-break-after: avoid;
      }
      .section h2 { 
        font-size: 14px; 
        margin-bottom: 15px; 
      }
      .chart { 
        text-align: center; 
        margin: 15px 0;
      }
      .chart img { 
        max-width: 100%; 
        height: auto !important;
        display: inline-block;
      }
      table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 15px;
      }
      th, td { 
        border: 1px solid #ddd; 
        padding: 8px; 
        text-align: left; 
      }
      th { 
        background: #f0fdf4; 
      }
    </style>
  `;
}
function buildBarTableHtml(periodData) {
  if (!Array.isArray(periodData.bar.labels) || periodData.bar.labels.length === 0) {
    return '<div class="empty-state">Sin datos para la gráfica de barras con los filtros seleccionados.</div>';
  }

  const rows = periodData.bar.labels
    .map((label, idx) => {
      const demand = periodData.bar.demand[idx] || 0;
      const offer = periodData.bar.offer[idx] || 0;
      return `<tr><td>${label}</td><td>${demand}</td><td>${offer}</td></tr>`;
    })
    .join('');

  return `
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Línea tecnológica</th>
            <th>Perfiles solicitados</th>
            <th>Oferta SENA (programas)</th>
          </tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>
    </div>
  `;
}

function buildPieTableHtml(periodData) {
  if (!Array.isArray(periodData.pie.labels) || periodData.pie.labels.length === 0) {
    return '<div class="empty-state">Sin datos para la gráfica circular.</div>';
  }

  const total = periodData.pie.values.reduce((acc, value) => acc + value, 0);
  const rows = periodData.pie.labels
    .map((label, idx) => {
      const value = periodData.pie.values[idx] || 0;
      const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
      return `<tr><td>${label}</td><td>${value}</td><td>${percentage}%</td></tr>`;
    })
    .join('');

  return `
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Línea tecnológica</th>
            <th>Solicitudes</th>
            <th>Proporción</th>
          </tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>
    </div>
  `;
}

let html2pdfLoaderPromise = null;

function ensureHtml2PdfLoaded() {
  if (typeof window.html2pdf === 'function') {
    return Promise.resolve(window.html2pdf);
  }

  if (html2pdfLoaderPromise) {
    return html2pdfLoaderPromise;
  }

  html2pdfLoaderPromise = new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
    script.async = true;
    script.onload = () => resolve(window.html2pdf);
    script.onerror = () => reject(new Error('No se pudo cargar la librería de PDF.'));
    document.head.appendChild(script);
  });

  return html2pdfLoaderPromise;
}

function buildReportModel(reportType) {
  const now = new Date();
  const date = now.toLocaleDateString('es-CO');
  const time = now.toLocaleTimeString('es-CO');
  const filtersText = getAppliedFiltersTextForReport(reportType);

  const barPeriodData = getCurrentPeriodData();
  const piePeriodData = getCurrentPeriodData();

  // Forzar renderizado completo antes de capturar
  barChart.update('none');
  pieChart.update('none');

  // Capturar imágenes
  const barImage = barChart.toBase64Image('image/png', 2);
  const pieImage = pieChart.toBase64Image('image/png', 2);

  const hasBarRows = Array.isArray(barPeriodData.bar.labels) && barPeriodData.bar.labels.length > 0;
  const hasPieRows = Array.isArray(piePeriodData.pie.labels) && piePeriodData.pie.labels.length > 0;

  let title = 'Reporte Estadístico Global';
  let content = '';

  if (reportType === 'global') {
    content = `
      <div class="section">
        <h2>Gráfica: Necesidades Empresariales vs Oferta SENA</h2>
        ${hasBarRows
          ? `<div class="chart chart--bar"><img src="${barImage}" alt="Gráfica de barras"></div>`
          : '<div class="empty-state">Sin datos de barras para los filtros seleccionados.</div>'}
        ${buildBarTableHtml(barPeriodData)}
      </div>
      <div class="section">
        <h2>Gráfica: Distribución de Solicitudes por Línea Tecnológica</h2>
        ${hasPieRows
          ? `<div class="chart chart--pie"><img src="${pieImage}" alt="Gráfica de pastel"></div>`
          : '<div class="empty-state">Sin datos para la distribución circular.</div>'}
        ${buildPieTableHtml(piePeriodData)}
      </div>
    `;
  } else if (reportType === 'bar') {
    title = 'Reporte Individual: Necesidades Empresariales vs Oferta SENA';
    content = `
      <div class="section">
        <h2>Gráfica: Necesidades Empresariales vs Oferta SENA</h2>
        ${hasBarRows
          ? `<div class="chart chart--bar"><img src="${barImage}" alt="Gráfica de barras"></div>`
          : '<div class="empty-state">Sin datos de barras para los filtros seleccionados.</div>'}
        ${buildBarTableHtml(barPeriodData)}
      </div>
    `;
  } else {
    title = 'Reporte Individual: Distribución de Solicitudes por Línea Tecnológica';
    content = `
      <div class="section">
        <h2>Gráfica: Distribución de Solicitudes por Línea Tecnológica</h2>
        ${hasPieRows
          ? `<div class="chart chart--pie"><img src="${pieImage}" alt="Gráfica de pastel"></div>`
          : '<div class="empty-state">Sin datos para la distribución circular.</div>'}
        ${buildPieTableHtml(piePeriodData)}
      </div>
    `;
  }

  return {
    title,
    date,
    time,
    filtersText,
    content,
  };
}

function buildReportDocumentHtml(reportModel) {
  return `
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="UTF-8">
        <title>${reportModel.title}</title>
        ${reportStyles()}
        <style>
          /* Estilos adicionales para evitar corte en tablas largas */
          .table-wrap {
            page-break-inside: avoid;
            break-inside: avoid;
          }
          table {
            page-break-inside: auto;
          }
          tr {
            page-break-inside: avoid;
            break-inside: avoid;
          }
        </style>
      </head>
      <body>
        <div class="report-root">
          <div class="report-header">
            <h1 class="report-title">${reportModel.title}</h1>
            <div class="meta-grid">
              <p class="meta"><strong>Fecha de descarga:</strong> ${reportModel.date}</p>
              <p class="meta"><strong>Hora de descarga:</strong> ${reportModel.time}</p>
              <p class="meta" style="grid-column: 1 / -1;"><strong>Filtros aplicados:</strong> ${reportModel.filtersText}</p>
            </div>
          </div>
          ${reportModel.content}
        </div>
      </body>
    </html>
  `;
}

function sanitizeFileNamePart(value) {
  return String(value || '')
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-zA-Z0-9]+/g, '_')
    .replace(/^_+|_+$/g, '');
}

function buildReportPdfFileName(reportType) {
  const filters = getSelectedBarFilters();

  const base = reportType === 'global'
    ? 'estadisticas_observatorio_CTI-reporte_global'
    : reportType === 'bar'
      ? 'estadisticas_observatorio_CTI-reporte_barras'
      : 'estadisticas_observatorio_CTI-reporte_circular';

  const safeBase = sanitizeFileNamePart(base);

  if (reportType !== 'bar') {
    return `${safeBase}.pdf`;
  }

  if (filters.year === 'all') {
    return `${safeBase}.pdf`;
  }

  if (filters.month === 'all') {
    return `${safeBase}-${filters.year}.pdf`;
  }

  return `${safeBase}-${filters.year}-${filters.month}.pdf`;
}

function createLoadingScreen() {
  const existing = document.getElementById('pdf-loading-screen');
  if (existing) return existing;

  const overlay = document.createElement('div');
  overlay.id = 'pdf-loading-screen';
  overlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
  `;

  const container = document.createElement('div');
  container.style.cssText = `
    background: white;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-width: 400px;
  `;

  container.innerHTML = `
    <div style="margin-bottom: 24px;">
      <div style="
        display: inline-block;
        width: 48px;
        height: 48px;
        border: 4px solid #e2e8f0;
        border-top-color: #22c55e;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
      "></div>
    </div>
    <h3 style="margin: 16px 0; color: #1e293b; font-size: 18px; font-weight: 600;">
      Generando reporte
    </h3>
    <p id="loading-message" style="color: #64748b; font-size: 14px; margin-bottom: 12px;">
      Preparando gráficas...
    </p>
    <div style="
      width: 100%;
      height: 4px;
      background: #e2e8f0;
      border-radius: 2px;
      overflow: hidden;
      margin-top: 16px;
    ">
      <div id="loading-progress" style="
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #22c55e, #3b82f6);
        transition: width 0.3s ease;
      "></div>
    </div>
    <style>
      @keyframes spin {
        to { transform: rotate(360deg); }
      }
    </style>
  `;

  overlay.appendChild(container);
  document.body.appendChild(overlay);

  return overlay;
}

function showLoadingScreen() {
  const screen = createLoadingScreen();
  screen.style.display = 'flex';
  updateLoadingProgress('Preparando gráficas...', 10);
}

function hideLoadingScreen() {
  const screen = document.getElementById('pdf-loading-screen');
  if (screen) {
    screen.style.display = 'none';
  }
}

function updateLoadingProgress(message, percentage) {
  const messageEl = document.getElementById('loading-message');
  const progressEl = document.getElementById('loading-progress');

  if (messageEl) messageEl.textContent = message;
  if (progressEl) progressEl.style.width = percentage + '%';
}

async function downloadReportPdf(reportType) {
  if (reportDownloadInProgress) {
    return;
  }

  reportDownloadInProgress = true;
  setReportButtonsState(true);
  showLoadingScreen();

  let iframe = null;

  try {
    updateLoadingProgress('Validando datos...', 15);
    const ready = await ensureReportDataReady(reportType);
    if (!ready) {
      return;
    }

    updateLoadingProgress('Cargando librería PDF...', 25);
    const html2pdf = await ensureHtml2PdfLoaded();

    updateLoadingProgress('Preparando gráficas...', 35);
    await waitForStableCharts({ forceScroll: true });
    const reportModel = buildReportModel(reportType);
    const reportHtml = buildReportDocumentHtml(reportModel);
    
    // Esperar a que el navegador procese el HTML
    await new Promise(resolve => setTimeout(resolve, 500));

    updateLoadingProgress('Configurando documento...', 45);
    iframe = document.createElement('iframe');
    iframe.setAttribute('aria-hidden', 'true');
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '800px';
    iframe.style.height = '1200px';
    iframe.style.opacity = '0';
    iframe.style.pointerEvents = 'none';
    iframe.style.border = '0';
    iframe.style.zIndex = '-1';

    document.body.appendChild(iframe);

    const iframeDocument = iframe.contentDocument || iframe.contentWindow?.document;
    iframeDocument.open();
    iframeDocument.write(reportHtml);
    iframeDocument.close();
    iframeDocument.body.style.overflow = 'visible';

    await new Promise((resolve) => {
      let resolved = false;
      const finish = () => {
        if (resolved) return;
        resolved = true;
        resolve();
      };

      iframe.onload = finish;
      setTimeout(finish, 800);
    });

    updateLoadingProgress('Renderizando contenido...', 55);
    const imageNodes = Array.from(iframeDocument.querySelectorAll('img'));
    await Promise.all(
      imageNodes.map((img) => {
        if (img.complete && img.naturalWidth > 0) {
          return Promise.resolve();
        }

        return new Promise((resolve) => {
          img.onload = () => resolve();
          img.onerror = () => resolve();
          setTimeout(resolve, 1000);
        });
      })
    );
    
    updateLoadingProgress('Estabilizando gráficas...', 65);
    // Esperas aumentadas para mejor renderizado
    await new Promise(resolve => setTimeout(resolve, 800));

    if (iframeDocument.fonts?.ready) {
      await iframeDocument.fonts.ready.catch(() => undefined);
    }

    updateLoadingProgress('Finalizando documento...', 75);
    const reportNode = iframeDocument.documentElement;

    await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
    
    // Espera adicional en punto crítico
    await new Promise(resolve => setTimeout(resolve, 600));

    updateLoadingProgress('Generando PDF...', 85);
    
    await html2pdf()
      .set({
        margin: 8,
        filename: buildReportPdfFileName(reportType),
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['css', 'legacy'] }
      })
      .from(reportNode)
      .save();

    updateLoadingProgress('Completado', 100);
    await new Promise(resolve => setTimeout(resolve, 500));
    
    hideStatsError();
  } catch (error) {
    showStatsError(`No fue posible generar el PDF: ${String(error?.message || error)}`);
  } finally {
    if (iframe && iframe.parentNode) {
      iframe.parentNode.removeChild(iframe);
    }

    hideLoadingScreen();
    reportDownloadInProgress = false;
    setReportButtonsState(false);
  }
}

function openReportWindow(reportType) {
  const reportModel = buildReportModel(reportType);

  const reportWindow = window.open('', '_blank');
  if (!reportWindow) return;

  reportWindow.document.write(buildReportDocumentHtml(reportModel));

  reportWindow.document.close();
}

// Inicializar las gráficas y controles
document.addEventListener('DOMContentLoaded', function() {
  const barCtx = document.getElementById('barChart').getContext('2d');
  barChart = new Chart(barCtx, barConfig);

  const pieCtx = document.getElementById('pieChart').getContext('2d');
  pieChart = new Chart(pieCtx, pieConfig);

  const monthFilter = document.getElementById('monthFilter');
  const yearFilter = document.getElementById('yearFilter');
  const globalReportBtn = document.getElementById('globalReportBtn');
  const barReportBtn = document.getElementById('barReportBtn');
  const pieReportBtn = document.getElementById('pieReportBtn');

  const applyBarFilter = async function(animate = true) {
    await cargarBarChartDesdeBackend(animate);
  };

  syncMonthFilterState(monthFilter, yearFilter);

  monthFilter?.addEventListener('change', applyBarFilter);
  yearFilter?.addEventListener('change', async function() {
    syncMonthFilterState(monthFilter, yearFilter);
    await applyBarFilter();
  });

  globalReportBtn?.addEventListener('click', async function() {
    if (!hasValidBarFilters(getSelectedBarFilters())) {
      showStatsError('Para generar el reporte de barras debes seleccionar también un año.');
      return;
    }
    await downloadReportPdf('global');
  });

  barReportBtn?.addEventListener('click', async function() {
    if (!hasValidBarFilters(getSelectedBarFilters())) {
      showStatsError('Para generar el reporte de barras debes seleccionar también un año.');
      return;
    }
    await downloadReportPdf('bar');
  });

  pieReportBtn?.addEventListener('click', async function() {
    await downloadReportPdf('pie');
  });

  cargarEstadisticasDesdeBackend()
    .then(() => {
      updateCharts(getCurrentPeriodData(), false);
      applyBarFilter(false);
    })
    .catch((error) => {
      statsBootstrapped = false;
      console.error('Error cargando estadísticas:', error);
      showStatsError(`No fue posible cargar estadísticas desde backend: ${String(error?.message || error)}`);
      updateCharts(getEmptyPeriodData(), false);
    });

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
});