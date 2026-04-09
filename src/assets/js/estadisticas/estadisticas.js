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

function buildBarData(periodData) {
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

    // Configuración de la gráfica de barras
const barConfig = {
      type: 'bar',
  data: buildBarData(getEmptyPeriodData()),
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
            max: 10
          }
        }
      }
    };

    // Configuración de la gráfica circular
const pieConfig = {
      type: 'doughnut',
  data: buildPieData(getEmptyPeriodData()),
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
  const resultados = await Promise.allSettled([
    fetchJson(`${API_URL}?accion=completas`),
    fetchJson(`${API_URL}?accion=comparativa`),
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

  hideStatsError();
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

function updateCharts(periodData) {
  barChart.data = buildBarData(periodData);
  pieChart.data = buildPieData(periodData);

  barChart.update();
  pieChart.update();

  renderPieLegend(periodData.pie.labels, periodData.pie.values, pieColors, periodData.pie.inactive);
  updateCounters(periodData);
}

function updateBarChart(periodData) {
  barChart.data = buildBarData(periodData);
  const maxSerie = Math.max(...periodData.bar.demand, ...periodData.bar.offer, 0);
  barChart.options.scales.y.max = Math.max(10, Math.ceil(maxSerie * 1.2));
  barChart.update();
}

function reportStyles() {
  return `
    <style>
      body { font-family: Arial, sans-serif; margin: 24px; color: #1f2937; }
      h1, h2 { margin: 0 0 10px; }
      .meta { margin-bottom: 18px; font-size: 14px; }
      .section { margin-top: 22px; }
      .chart { margin-top: 10px; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; }
      img { max-width: 100%; height: auto; }
      table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
      th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
      th { background: #f3f4f6; }
    </style>
  `;
}

function buildBarTableHtml(periodData) {
  const rows = periodData.bar.labels
    .map((label, idx) => {
      const demand = periodData.bar.demand[idx] || 0;
      const offer = periodData.bar.offer[idx] || 0;
      return `<tr><td>${label}</td><td>${demand}</td><td>${offer}</td></tr>`;
    })
    .join('');

  return `
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
  `;
}

function buildPieTableHtml(periodData) {
  const total = periodData.pie.values.reduce((acc, value) => acc + value, 0);
  const rows = periodData.pie.labels
    .map((label, idx) => {
      const value = periodData.pie.values[idx] || 0;
      const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : '0.0';
      return `<tr><td>${label}</td><td>${value}</td><td>${percentage}%</td></tr>`;
    })
    .join('');

  return `
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
  `;
}

function openReportWindow(reportType) {
  const now = new Date();
  const date = now.toLocaleDateString('es-CO');
  const time = now.toLocaleTimeString('es-CO');
  const filtersText = getAppliedFiltersTextForReport(reportType);

  const barPeriodData = getCurrentPeriodData();
  const piePeriodData = getCurrentPeriodData();

  const reportWindow = window.open('', '_blank');
  if (!reportWindow) return;

  const barImage = barChart.toBase64Image();
  const pieImage = pieChart.toBase64Image();

  let title = 'Reporte Estadístico Global';
  let content = '';

  if (reportType === 'global') {
    content = `
      <div class="section">
        <h2>Gráfica: Necesidades Empresariales vs Oferta SENA</h2>
        <div class="chart"><img src="${barImage}" alt="Gráfica de barras"></div>
        ${buildBarTableHtml(barPeriodData)}
      </div>
      <div class="section">
        <h2>Gráfica: Distribución de Solicitudes por Línea Tecnológica</h2>
        <div class="chart"><img src="${pieImage}" alt="Gráfica de pastel"></div>
        ${buildPieTableHtml(piePeriodData)}
      </div>
    `;
  } else if (reportType === 'bar') {
    title = 'Reporte Individual: Necesidades Empresariales vs Oferta SENA';
    content = `
      <div class="section">
        <h2>Gráfica: Necesidades Empresariales vs Oferta SENA</h2>
        <div class="chart"><img src="${barImage}" alt="Gráfica de barras"></div>
        ${buildBarTableHtml(barPeriodData)}
      </div>
    `;
  } else {
    title = 'Reporte Individual: Distribución de Solicitudes por Línea Tecnológica';
    content = `
      <div class="section">
        <h2>Gráfica: Distribución de Solicitudes por Línea Tecnológica</h2>
        <div class="chart"><img src="${pieImage}" alt="Gráfica de pastel"></div>
        ${buildPieTableHtml(piePeriodData)}
      </div>
    `;
  }

  reportWindow.document.write(`
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="UTF-8">
        <title>${title}</title>
        ${reportStyles()}
      </head>
      <body>
        <h1>${title}</h1>
        <div class="meta"><strong>Fecha de descarga:</strong> ${date}</div>
        <div class="meta"><strong>Hora de descarga:</strong> ${time}</div>
        <div class="meta"><strong>Filtros aplicados:</strong> ${filtersText}</div>
        ${content}
      </body>
    </html>
  `);

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

  const applyBarFilter = function() {
    // El backend actual expone estadísticas consolidadas, por lo que el filtro
    // de mes/año se conserva visualmente y reutiliza el dataset vigente.
    updateBarChart(getCurrentPeriodData());
  };

  monthFilter?.addEventListener('change', applyBarFilter);
  yearFilter?.addEventListener('change', applyBarFilter);

  globalReportBtn?.addEventListener('click', function() {
    openReportWindow('global');
  });

  barReportBtn?.addEventListener('click', function() {
    openReportWindow('bar');
  });

  pieReportBtn?.addEventListener('click', function() {
    openReportWindow('pie');
  });

  cargarEstadisticasDesdeBackend()
    .then(() => {
      updateCharts(getCurrentPeriodData());
      applyBarFilter();
    })
    .catch((error) => {
      console.error('Error cargando estadísticas:', error);
      showStatsError(`No fue posible cargar estadísticas desde backend: ${String(error?.message || error)}`);
      updateCharts(getEmptyPeriodData());
    });

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
});