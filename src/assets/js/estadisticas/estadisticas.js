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
  const demand = (periodData?.bar?.demand || []).map((v) => Number(v) || 0);
  const offer = (periodData?.bar?.offer || []).map((v) => Number(v) || 0);
  const hasPositiveValues = [...demand, ...offer].some((v) => v > 0);

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

  return {
    labels: periodData?.bar?.labels || [],
    datasets: [
      {
        label: 'Perfiles Solicitados',
        data: demand,
        backgroundColor: '#22c55e',
        borderRadius: 4,
        barThickness: 20
      },
      {
        label: 'Programas de Formación (Oferta)',
        data: offer,
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

function wrapTooltipText(text, maxLineLength = 24) {
  const safeText = String(text || '').trim();
  if (!safeText) return [''];

  const words = safeText.split(/\s+/);
  const lines = [];
  let current = '';

  words.forEach((word) => {
    if (word.length > maxLineLength) {
      if (current) {
        lines.push(current);
        current = '';
      }

      for (let i = 0; i < word.length; i += maxLineLength) {
        lines.push(word.slice(i, i + maxLineLength));
      }
      return;
    }

    const candidate = current ? `${current} ${word}` : word;
    if (candidate.length <= maxLineLength) {
      current = candidate;
    } else {
      lines.push(current);
      current = word;
    }
  });

  if (current) {
    lines.push(current);
  }

  return lines;
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
              title: function(items) {
                const label = items?.[0]?.label || '';
                return wrapTooltipText(label, 24);
              },
              label: function(context) {
                const values = context.dataset.data || [];
                const total = values.reduce((acc, value) => acc + value, 0);
                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : '0.0';
                return context.parsed + ' perfiles (' + percentage + '%)';
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
  // Forzar actualización de canvas
  barChart?.update('none');
  pieChart?.update('none');
  
  // Esperar múltiples frames de animación
  await new Promise(resolve => requestAnimationFrame(resolve));
  await new Promise(resolve => requestAnimationFrame(resolve));
  await new Promise(resolve => setTimeout(resolve, 200));
  
  // Forzar reflow de los canvas
  if (barChart?.canvas) {
    barChart.canvas.style.transform = 'scale(1)';
    barChart.canvas.offsetHeight; // Forzar reflow
  }
  if (pieChart?.canvas) {
    pieChart.canvas.style.transform = 'scale(1)';
    pieChart.canvas.offsetHeight;
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
    return '';
  }

  return getAppliedFiltersText();
}

function renderPieLegend(labels, values, colors) {
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

  legend.innerHTML = activeHtml;
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

  renderPieLegend(periodData.pie.labels, periodData.pie.values, pieColors);
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
              ${reportModel.filtersText ? `<p class="meta" style="grid-column: 1 / -1;"><strong>Filtros aplicados:</strong> ${reportModel.filtersText}</p>` : ''}
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

// Reemplazar la función downloadReportPdf completa
async function downloadReportPdf(reportType) {
  if (reportDownloadInProgress) {
    return;
  }

  reportDownloadInProgress = true;
  setReportButtonsState(true);
  showLoadingScreen();

  try {
    updateLoadingProgress('Validando datos...', 15);
    const ready = await ensureReportDataReady(reportType);
    if (!ready) {
      return;
    }

    updateLoadingProgress('Preparando gráficas...', 35);
    
    // Esperar a que los canvas estén completamente renderizados
    await waitForStableCharts({ forceScroll: true });
    
    // Pequeña espera adicional para asegurar renderizado
    await new Promise(resolve => setTimeout(resolve, 300));
    
    // Forzar actualización de los canvas
    barChart.update('none');
    pieChart.update('none');
    
    await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
    
    updateLoadingProgress('Capturando gráficas...', 50);
    
    // Capturar los canvas directamente como imágenes
    const barCanvas = document.getElementById('barChart');
    const pieCanvas = document.getElementById('pieChart');
    
    let barImageData = null;
    let pieImageData = null;
    
    // Capturar gráfico de barras (necesario para bar y global)
    if (reportType === 'global' || reportType === 'bar') {
      barImageData = await captureCanvasWithRetry(barCanvas);
      if (!barImageData) {
        throw new Error('No se pudo capturar el gráfico de barras');
      }
    }
    
    // Capturar gráfico de pastel (necesario para pie y global)
    if (reportType === 'global' || reportType === 'pie') {
      pieImageData = await captureCanvasWithRetry(pieCanvas);
      if (!pieImageData) {
        throw new Error('No se pudo capturar el gráfico de pastel');
      }
    }
    
    updateLoadingProgress('Cargando librerías PDF...', 65);
    
    // Cargar librerías necesarias
    await loadPdfLibraries();
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      unit: 'mm',
      format: 'a4',
      orientation: 'portrait'
    });
    
    updateLoadingProgress('Generando PDF...', 75);
    
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 15;
    const contentWidth = pageWidth - (margin * 2);
    
    // === FUNCIÓN PARA AGREGAR ENCABEZADO COMÚN ===
    function addHeader(doc, title, yOffset, filtersText = getAppliedFiltersTextForReport(reportType)) {
      doc.setFontSize(18);
      doc.setFont('helvetica', 'bold');
      doc.text(title, margin, yOffset);
      yOffset += 12;
      
      doc.setFontSize(10);
      doc.setFont('helvetica', 'normal');
      const now = new Date();
      doc.text(`Fecha: ${now.toLocaleDateString('es-CO')}`, margin, yOffset);
      yOffset += 6;
      doc.text(`Hora: ${now.toLocaleTimeString('es-CO')}`, margin, yOffset);
      yOffset += 6;
      if (filtersText) {
        doc.text(`Filtros: ${filtersText}`, margin, yOffset);
        yOffset += 12;
      } else {
        yOffset += 6;
      }
      
      doc.setDrawColor(34, 197, 94);
      doc.setLineWidth(0.5);
      doc.line(margin, yOffset, pageWidth - margin, yOffset);
      yOffset += 10;
      
      return yOffset;
    }
    
    if (reportType === 'global') {
      // === PÁGINA 1: Gráfico de Barras ===
      let yOffset = 20;
      yOffset = addHeader(doc, 'Reporte Estadístico Global - Observatorio CTI', yOffset);
      
      // Título del gráfico de barras
      doc.setFontSize(14);
      doc.setFont('helvetica', 'bold');
      doc.text('1. Necesidades Empresariales vs Oferta SENA', margin, yOffset);
      yOffset += 10;
      
      // Gráfico de barras (tamaño grande)
      if (barImageData) {
        const imgWidth = contentWidth;
        const imgHeight = (barCanvas.height / barCanvas.width) * imgWidth;
        
        // Ajustar altura máxima para que quepa bien en la página
        const maxHeight = doc.internal.pageSize.getHeight() - yOffset - 60;
        let finalImgHeight = imgHeight;
        if (imgHeight > maxHeight) {
          finalImgHeight = maxHeight;
        }
        
        doc.addImage(barImageData, 'PNG', margin, yOffset, imgWidth, finalImgHeight);
        yOffset += finalImgHeight + 10;
      }
      
      // Tabla del gráfico de barras
      yOffset = addBarTableToPdf(doc, getCurrentPeriodData(), margin, yOffset, pageWidth);
      
      // === PÁGINA 2: Gráfico Circular ===
      doc.addPage();
      yOffset = 20;
      yOffset = addHeader(doc, 'Reporte Estadístico Global - Observatorio CTI ', yOffset, '');
      
      // Título del gráfico circular
      doc.setFontSize(14);
      doc.setFont('helvetica', 'bold');
      doc.text('2. Distribución de Solicitudes por Línea Tecnológica', margin, yOffset);
      yOffset += 10;
      
      // Gráfico circular (tamaño adecuado para la página)
      if (pieImageData) {
        // Reducir a la mitad del tamaño previo
        const imgWidth = Math.min(contentWidth * 0.3, 43);
        const imgHeight = (pieCanvas.height / pieCanvas.width) * imgWidth;
        
        // Centrar el gráfico
        const xOffset = margin + (contentWidth - imgWidth) / 2;
        
        doc.addImage(pieImageData, 'PNG', xOffset, yOffset, imgWidth, imgHeight);
        yOffset += imgHeight + 15;
      }
      
      // Tabla del gráfico circular
      yOffset = addPieTableToPdf(doc, getCurrentPeriodData(), margin, yOffset, pageWidth);
      
    } else if (reportType === 'bar') {
      // Reporte individual de barras - una página
      let yOffset = 20;
      yOffset = addHeader(doc, 'Reporte Individual - Necesidades vs Oferta SENA', yOffset);
      
      doc.setFontSize(14);
      doc.setFont('helvetica', 'bold');
      doc.text('Necesidades Empresariales vs Oferta SENA', margin, yOffset);
      yOffset += 10;
      
      if (barImageData) {
        const imgWidth = contentWidth;
        const imgHeight = (barCanvas.height / barCanvas.width) * imgWidth;
        
        const maxHeight = doc.internal.pageSize.getHeight() - yOffset - 50;
        let finalImgHeight = imgHeight;
        if (imgHeight > maxHeight) {
          finalImgHeight = maxHeight;
        }
        
        doc.addImage(barImageData, 'PNG', margin, yOffset, imgWidth, finalImgHeight);
        yOffset += finalImgHeight + 10;
      }
      
      yOffset = addBarTableToPdf(doc, getCurrentPeriodData(), margin, yOffset, pageWidth);
      
    } else {
      // Reporte individual de pastel - una página
      let yOffset = 20;
      yOffset = addHeader(doc, 'Reporte Individual - Distribución por Línea Tecnológica', yOffset);
      
      doc.setFontSize(14);
      doc.setFont('helvetica', 'bold');
      doc.text('Distribución de Solicitudes por Línea Tecnológica', margin, yOffset);
      yOffset += 10;
      
      if (pieImageData) {
        // Reducir a la mitad del tamaño previo
        const imgWidth = Math.min(contentWidth * 0.3, 48);
        const imgHeight = (pieCanvas.height / pieCanvas.width) * imgWidth;
        
        const xOffset = margin + (contentWidth - imgWidth) / 2;
        
        doc.addImage(pieImageData, 'PNG', xOffset, yOffset, imgWidth, imgHeight);
        yOffset += imgHeight + 15;
      }
      
      yOffset = addPieTableToPdf(doc, getCurrentPeriodData(), margin, yOffset, pageWidth);
    }
    
    updateLoadingProgress('Guardando PDF...', 90);
    
    // Guardar el PDF
    const fileName = buildReportPdfFileName(reportType);
    doc.save(fileName);
    
    updateLoadingProgress('Completado', 100);
    await new Promise(resolve => setTimeout(resolve, 500));
    
    hideStatsError();
  } catch (error) {
    console.error('Error generando PDF:', error);
    showStatsError(`No fue posible generar el PDF: ${String(error?.message || error)}`);
  } finally {
    hideLoadingScreen();
    reportDownloadInProgress = false;
    setReportButtonsState(false);
  }
}

// Nueva función para capturar canvas con reintentos
async function captureCanvasWithRetry(canvas, maxRetries = 3) {
  for (let i = 0; i < maxRetries; i++) {
    try {
      // Convertir a PNG de alta calidad
      const dataUrl = canvas.toDataURL('image/png', 1.0);
      
      // Verificar que la imagen no está vacía
      if (dataUrl && dataUrl.length > 1000) {
        return dataUrl;
      }
    } catch (e) {
      console.warn(`Intento ${i + 1} falló:`, e);
    }
    
    // Esperar antes de reintentar
    await new Promise(resolve => setTimeout(resolve, 200));
    
    // Forzar actualización del canvas
    canvas.style.transform = 'scale(1.001)';
    await new Promise(resolve => requestAnimationFrame(resolve));
    canvas.style.transform = '';
    await new Promise(resolve => requestAnimationFrame(resolve));
  }
  
  return null;
}

// Función para cargar librerías PDF
async function loadPdfLibraries() {
  if (typeof window.jspdf !== 'undefined' && window.jspdf.jsPDF) {
    return;
  }
  
  return new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.onload = () => {
      // También necesitamos html2canvas para posibles capturas adicionales
      if (typeof window.html2canvas === 'undefined') {
        const h2cScript = document.createElement('script');
        h2cScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
        h2cScript.onload = () => resolve();
        h2cScript.onerror = () => reject(new Error('Error cargando html2canvas'));
        document.head.appendChild(h2cScript);
      } else {
        resolve();
      }
    };
    script.onerror = () => reject(new Error('Error cargando jsPDF'));
    document.head.appendChild(script);
  });
}

// Función para agregar tabla de barras al PDF
function addBarTableToPdf(doc, periodData, margin, startY, pageWidth) {
  const labels = periodData.bar.labels || [];
  const demand = periodData.bar.demand || [];
  const offer = periodData.bar.offer || [];
  
  if (labels.length === 0) {
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text('No hay datos disponibles para mostrar en tabla', margin, startY);
    return startY + 10;
  }
  
  // Configurar tabla
  const colWidths = [80, 40, 40];
  const tableWidth = colWidths[0] + colWidths[1] + colWidths[2];
  const contentWidth = pageWidth - (margin * 2);
  const tableX = margin + ((contentWidth - tableWidth) / 2);
  const rowHeight = 8;
  let y = startY;
  let sectionStartY = y;
  
  // Encabezados
  doc.setFontSize(10);
  doc.setFont('helvetica', 'bold');
  doc.setFillColor(240, 253, 244);
  doc.rect(tableX, y, colWidths[0], rowHeight, 'F');
  doc.rect(tableX + colWidths[0], y, colWidths[1], rowHeight, 'F');
  doc.rect(tableX + colWidths[0] + colWidths[1], y, colWidths[2], rowHeight, 'F');
  doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
  doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
  doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
  
  doc.text('Área', tableX + 2, y + 5);
  doc.text('Perfiles Solicitados', tableX + colWidths[0] + 2, y + 5);
  doc.text('Programas Ofertados', tableX + colWidths[0] + colWidths[1] + 2, y + 5);
  y += rowHeight;
  
  // Filas de datos
  doc.setFont('helvetica', 'normal');
  for (let i = 0; i < labels.length; i++) {
    // Verificar si necesitamos nueva página
    if (y + rowHeight > doc.internal.pageSize.getHeight() - margin) {
      // Cerrar contorno redondeado de la sección actual antes de cambiar de página
      const sectionHeight = y - sectionStartY;
      if (sectionHeight > 0) {
        doc.roundedRect(tableX, sectionStartY, tableWidth, sectionHeight, 2, 2, 'S');
      }

      doc.addPage();
      y = margin;
      sectionStartY = y;
      
      // Re-dibujar encabezados en nueva página
      doc.setFont('helvetica', 'bold');
      doc.setFillColor(240, 253, 244);
      doc.rect(tableX, y, colWidths[0], rowHeight, 'F');
      doc.rect(tableX + colWidths[0], y, colWidths[1], rowHeight, 'F');
      doc.rect(tableX + colWidths[0] + colWidths[1], y, colWidths[2], rowHeight, 'F');
      doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
      doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
      doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
      doc.text('Área', tableX + 2, y + 5);
      doc.text('Perfiles Solicitados', tableX + colWidths[0] + 2, y + 5);
      doc.text('Programas Ofertados', tableX + colWidths[0] + colWidths[1] + 2, y + 5);
      y += rowHeight;
      doc.setFont('helvetica', 'normal');
    }
    
    // Dibujar solo la grilla interna; el borde externo lo define el contorno redondeado
    doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
    doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
    const isLastOverallRow = i === labels.length - 1;
    const nextRowWouldBreakPage = (i < labels.length - 1)
      && (y + (rowHeight * 2) > doc.internal.pageSize.getHeight() - margin);

    if (!isLastOverallRow && !nextRowWouldBreakPage) {
      doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
    }
    
    // Texto de celdas (truncar si es muy largo)
    let label = labels[i];
    if (label.length > 25) label = label.substring(0, 22) + '...';
    
    doc.text(label, tableX + 2, y + 5);
    doc.text(String(demand[i] || 0), tableX + colWidths[0] + 2, y + 5);
    doc.text(String(offer[i] || 0), tableX + colWidths[0] + colWidths[1] + 2, y + 5);
    
    y += rowHeight;
  }

  // Contorno redondeado para la última sección de la tabla
  const finalSectionHeight = y - sectionStartY;
  if (finalSectionHeight > 0) {
    doc.roundedRect(tableX, sectionStartY, tableWidth, finalSectionHeight, 3, 3, 'S');
  }
  
  return y + 5;
}

// Función para agregar tabla de pastel al PDF
function addPieTableToPdf(doc, periodData, margin, startY, pageWidth) {
  const labels = periodData.pie.labels || [];
  const values = periodData.pie.values || [];
  const total = values.reduce((a, b) => a + b, 0);
  
  if (labels.length === 0) {
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text('No hay datos disponibles para mostrar en tabla', margin, startY);
    return startY + 10;
  }
  
  // Configurar tabla
  const colWidths = [80, 40, 40];
  const tableWidth = colWidths[0] + colWidths[1] + colWidths[2];
  const contentWidth = pageWidth - (margin * 2);
  const tableX = margin + ((contentWidth - tableWidth) / 2);
  const rowHeight = 8;
  let y = startY;
  let sectionStartY = y;
  
  // Encabezados
  doc.setFontSize(10);
  doc.setFont('helvetica', 'bold');
  doc.setFillColor(240, 253, 244);
  doc.rect(tableX, y, colWidths[0], rowHeight, 'F');
  doc.rect(tableX + colWidths[0], y, colWidths[1], rowHeight, 'F');
  doc.rect(tableX + colWidths[0] + colWidths[1], y, colWidths[2], rowHeight, 'F');
  doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
  doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
  doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
  
  doc.text('Línea Tecnológica', tableX + 2, y + 5);
  doc.text('Solicitudes', tableX + colWidths[0] + 2, y + 5);
  doc.text('Proporción', tableX + colWidths[0] + colWidths[1] + 2, y + 5);
  y += rowHeight;
  
  // Filas de datos
  doc.setFont('helvetica', 'normal');
  for (let i = 0; i < labels.length; i++) {
    // Verificar si necesitamos nueva página
    if (y + rowHeight > doc.internal.pageSize.getHeight() - margin) {
      // Cerrar contorno redondeado de la sección actual antes de cambiar de página
      const sectionHeight = y - sectionStartY;
      if (sectionHeight > 0) {
        doc.roundedRect(tableX, sectionStartY, tableWidth, sectionHeight, 2, 2, 'S');
      }

      doc.addPage();
      y = margin;
      sectionStartY = y;
      
      // Re-dibujar encabezados
      doc.setFont('helvetica', 'bold');
      doc.setFillColor(240, 253, 244);
      doc.rect(tableX, y, colWidths[0], rowHeight, 'F');
      doc.rect(tableX + colWidths[0], y, colWidths[1], rowHeight, 'F');
      doc.rect(tableX + colWidths[0] + colWidths[1], y, colWidths[2], rowHeight, 'F');
      doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
      doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
      doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
      doc.text('Línea Tecnológica', tableX + 2, y + 5);
      doc.text('Solicitudes', tableX + colWidths[0] + 2, y + 5);
      doc.text('Proporción', tableX + colWidths[0] + colWidths[1] + 2, y + 5);
      y += rowHeight;
      doc.setFont('helvetica', 'normal');
    }
    
    const percentage = total > 0 ? ((values[i] / total) * 100).toFixed(1) : '0.0';
    
    // Dibujar solo la grilla interna; el borde externo lo define el contorno redondeado
    doc.line(tableX + colWidths[0], y, tableX + colWidths[0], y + rowHeight);
    doc.line(tableX + colWidths[0] + colWidths[1], y, tableX + colWidths[0] + colWidths[1], y + rowHeight);
    const isLastOverallRow = i === labels.length - 1;
    const nextRowWouldBreakPage = (i < labels.length - 1)
      && (y + (rowHeight * 2) > doc.internal.pageSize.getHeight() - margin);

    if (!isLastOverallRow && !nextRowWouldBreakPage) {
      doc.line(tableX, y + rowHeight, tableX + tableWidth, y + rowHeight);
    }
    
    // Texto (truncar si es muy largo)
    let label = labels[i];
    if (label.length > 25) label = label.substring(0, 22) + '...';
    
    doc.text(label, tableX + 2, y + 5);
    doc.text(String(values[i]), tableX + colWidths[0] + 2, y + 5);
    doc.text(`${percentage}%`, tableX + colWidths[0] + colWidths[1] + 2, y + 5);
    
    y += rowHeight;
  }

  // Contorno redondeado para la última sección de la tabla
  const finalSectionHeight = y - sectionStartY;
  if (finalSectionHeight > 0) {
    doc.roundedRect(tableX, sectionStartY, tableWidth, finalSectionHeight, 3, 3, 'S');
  }
  
  doc.setTextColor(0, 0, 0);
  return y + 5;
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