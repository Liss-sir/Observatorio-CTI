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

const dataByPeriod = {
  all: {
    bar: {
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
      demand: [28, 18, 15, 20, 14, 22, 16, 18],
      offer: [24, 16, 13, 18, 12, 20, 14, 16]
    },
    pie: {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial',
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados'
      ],
      values: [28, 18, 15, 20, 14, 22]
    },
    programsCount: 64
  },
  '2026-01': {
    bar: {
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
      demand: [21, 14, 11, 15, 10, 16, 13, 14],
      offer: [19, 12, 10, 13, 9, 15, 11, 13]
    },
    pie: {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial',
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados'
      ],
      values: [21, 14, 11, 15, 10, 16]
    },
    programsCount: 62
  },
  '2026-02': {
    bar: {
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
      demand: [24, 16, 13, 18, 12, 19, 14, 16],
      offer: [21, 14, 11, 16, 10, 17, 12, 14]
    },
    pie: {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial',
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados'
      ],
      values: [24, 16, 13, 18, 12, 19]
    },
    programsCount: 63
  },
  '2026-03': {
    bar: {
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
      demand: [28, 18, 15, 20, 14, 22, 16, 18],
      offer: [24, 16, 13, 18, 12, 20, 14, 16]
    },
    pie: {
      labels: [
        'Tecnologías de la Información',
        'Automatización Industrial',
        'Energías Renovables',
        'Biotecnología',
        'Nanotecnología',
        'Materiales Avanzados'
      ],
      values: [28, 18, 15, 20, 14, 22]
    },
    programsCount: 64
  }
};

const pieColors = ['#22c55e', '#3b82f6', '#eab308', '#ef4444', '#8b5cf6', '#f97316'];

let currentPeriodKey = 'all';
let barChart;
let pieChart;

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
      data: buildBarData(dataByPeriod[currentPeriodKey]),
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

    // Configuración de la gráfica circular
const pieConfig = {
      type: 'doughnut',
      data: buildPieData(dataByPeriod[currentPeriodKey]),
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

function getPeriodData(month, year) {
  if (month === 'all' && year === 'all') {
    return { key: 'all', data: dataByPeriod.all };
  }

  if (month !== 'all' && year !== 'all') {
    const exactKey = `${year}-${month}`;
    return { key: exactKey, data: dataByPeriod[exactKey] || dataByPeriod.all };
  }

  if (month === 'all' && year !== 'all') {
    const yearKeys = Object.keys(dataByPeriod).filter((k) => k.startsWith(`${year}-`));
    if (!yearKeys.length) {
      return { key: 'all', data: dataByPeriod.all };
    }

    const base = JSON.parse(JSON.stringify(dataByPeriod[yearKeys[0]]));
    for (let i = 1; i < yearKeys.length; i += 1) {
      const entry = dataByPeriod[yearKeys[i]];
      base.bar.demand = base.bar.demand.map((v, idx) => v + entry.bar.demand[idx]);
      base.bar.offer = base.bar.offer.map((v, idx) => v + entry.bar.offer[idx]);
      base.pie.values = base.pie.values.map((v, idx) => v + entry.pie.values[idx]);
      base.programsCount += entry.programsCount;
    }

    return { key: `${year}-all`, data: base };
  }

  return { key: 'all', data: dataByPeriod.all };
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

function renderPieLegend(labels, values, colors) {
  const legend = document.getElementById('pieLegend');
  if (!legend) return;

  const total = values.reduce((acc, value) => acc + value, 0);

  legend.innerHTML = labels
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
}

function updateCounters(periodData) {
  const totalPerfiles = periodData.bar.demand.reduce((acc, value) => acc + value, 0);
  const totalSolicitudes = periodData.pie.values.reduce((acc, value) => acc + value, 0);

  const totalPerfilesEl = document.getElementById('totalPerfilesCount');
  const lineasCountEl = document.getElementById('lineasCount');

  const programasCountEl = document.getElementById('programasCount');
  const solicitudesCountEl = document.getElementById('solicitudesCount');

  if (totalPerfilesEl) totalPerfilesEl.textContent = String(totalPerfiles);
  if (lineasCountEl) lineasCountEl.textContent = String(periodData.pie.labels.length);
  if (programasCountEl) programasCountEl.textContent = String(periodData.programsCount);
  if (solicitudesCountEl) solicitudesCountEl.textContent = String(totalSolicitudes);
}

function updateCharts(periodData) {
  barChart.data = buildBarData(periodData);
  pieChart.data = buildPieData(periodData);

  barChart.update();
  pieChart.update();

  renderPieLegend(periodData.pie.labels, periodData.pie.values, pieColors);
  updateCounters(periodData);
}

function updateBarChart(periodData) {
  barChart.data = buildBarData(periodData);
  barChart.update();

  const totalPerfiles = periodData.bar.demand.reduce((acc, value) => acc + value, 0);
  const totalPerfilesEl = document.getElementById('totalPerfilesCount');
  if (totalPerfilesEl) totalPerfilesEl.textContent = String(totalPerfiles);
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

  const barPeriodData = getPeriodData(
    document.getElementById('monthFilter')?.value || 'all',
    document.getElementById('yearFilter')?.value || 'all'
  ).data;
  const piePeriodData = dataByPeriod.all;

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

  updateCharts(dataByPeriod[currentPeriodKey]);

  const applyBarFilter = function() {
    const month = monthFilter?.value || 'all';
    const year = yearFilter?.value || 'all';

    const result = getPeriodData(month, year);
    currentPeriodKey = result.key;
    updateBarChart(result.data);
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

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
});