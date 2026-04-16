// landing.js

const controllerUrl = '../../controllers/LandingController.php';

// Rutas de destino por módulo
const routes = {
  perfiles: '../perfiles/perfiles.php',
  lineas: '../lineas_tecnologicas/lineas_tecnologicas.php',
  programas: '../programas_formacion/programas.php'
};

// ================= UTILIDADES =================

function escapeHtml(text) {
  if (text === null || text === undefined) return '';
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function truncateText(text, maxLength = 90, fallback = 'Sin información disponible.') {
  if (!text) return fallback;

  const cleanText = String(text).trim();

  return cleanText.length > maxLength
    ? cleanText.substring(0, maxLength).trim() + '...'
    : cleanText;
}

function getInitials(text = '') {
  const clean = text.trim();
  if (!clean) return 'NA';

  const words = clean.split(/\s+/);
  if (words.length === 1) {
    return words[0].substring(0, 2).toUpperCase();
  }

  return (words[0][0] + words[1][0]).toUpperCase();
}

function getProgramInitials(text = '') {
  const clean = text.trim();
  if (!clean) return 'NA';

  const ignoredWords = ['de', 'del', 'la', 'el', 'en', 'y', 'para'];
  const words = clean
    .split(/\s+/)
    .filter(word => !ignoredWords.includes(word.toLowerCase()));

  if (words.length === 0) return 'NA';
  if (words.length === 1) return words[0].substring(0, 2).toUpperCase();

  return words.slice(0, 2).map(word => word[0]).join('').toUpperCase();
}

// ================= EMPTY STATE =================

function renderEmptyState(containerId, config = {}) {
  const container = document.getElementById(containerId);

  const {
    icon = 'bar-chart-3',
    title = 'No hay información disponible',
    subtitle = 'Aún no existen registros para mostrar.',
    href = '#'
  } = config;

  container.innerHTML = `
    <div class="col-span-full">
      <a
        href="${escapeHtml(href)}"
        class="group block"
      >
        <div class="flex min-h-[420px] w-full cursor-pointer flex-col items-center justify-center rounded-[20px] border border-[#d9dde3] bg-white px-6 py-12 text-center transition-all duration-200 hover:border-[#bfc7d1] hover:bg-[#f3f4f6]">
          
          <div class="flex h-[90px] w-[90px] items-center justify-center rounded-[20px] bg-[#e8f0df] transition-transform duration-200 group-hover:scale-105">
            <i data-lucide="${icon}" class="h-10 w-10 text-[#39A900]"></i>
          </div>

          <h3 class="mt-7 text-[22px] font-semibold text-[#1f2937]">
            ${escapeHtml(title)}
          </h3>

          <p class="mt-3 text-[18px] font-normal text-[#6b7280]">
            ${escapeHtml(subtitle)}
          </p>
        </div>
      </a>
    </div>
  `;

  if (window.lucide) {
    lucide.createIcons();
  }
}

// ================= RENDER =================

function renderPerfiles(perfiles) {
  const container = document.getElementById('perfiles-recientes');

  if (!perfiles || perfiles.length === 0) {
    renderEmptyState('perfiles-recientes', {
      icon: 'briefcase-business',
      title: 'No hay perfiles ocupacionales',
      subtitle: 'Haz clic para ir a la vista de perfiles ocupacionales.',
      href: routes.perfiles
    });
    return;
  }

  container.innerHTML = perfiles.map(perfil => `
    <a href="${escapeHtml(routes.perfiles)}" class="block">
      <article class="tarjeta-tecnologia group flex h-full min-h-[200px] flex-col cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
        
        <div class="flex items-center justify-between gap-2">
          <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">
            ${escapeHtml(truncateText(perfil.nombre_linea, 26, 'Sin línea'))}
          </span>

          <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">
            ${Number(perfil.estado) === 1 ? 'Vigente' : 'Inactivo'}
          </span>
        </div>

        <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main">
          ${escapeHtml(truncateText(perfil.nombre, 42, 'Sin nombre'))}
        </h3>

        <p class="mt-1 text-xs font-semibold text-sena-text-muted">
          ${escapeHtml(truncateText(perfil.descripcion, 110))}
        </p>

        <div class="mt-3 flex flex-wrap gap-1.5">
          <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">
            ${escapeHtml(perfil.nombre_area || 'Sin área')}
          </span>

          <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">
            ${escapeHtml(perfil.nombre_nivel || 'Sin nivel')}
          </span>

          ${perfil.nombre_programa ? `
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">
              ${escapeHtml(truncateText(perfil.nombre_programa, 18, 'Sin programa'))}
            </span>
          ` : ''}
        </div>

        <div class="mt-auto flex items-center justify-between border-t border-sena-border pt-3">
          <div class="text-xs font-bold text-sena-text-muted">
            Cupos: ${escapeHtml(perfil.cupos ?? 0)}
          </div>
        </div>

      </article>
    </a>
  `).join('');
}

function renderLineas(lineas) {
  const container = document.getElementById('lineas-tecnologicas');

  if (!lineas || lineas.length === 0) {
    renderEmptyState('lineas-tecnologicas', {
      icon: 'bar-chart-3',
      title: 'No hay líneas tecnológicas',
      subtitle: 'Haz clic para ir a la vista de líneas tecnológicas.',
      href: routes.lineas
    });
    return;
  }

  container.innerHTML = lineas.map(linea => `
    <a href="${escapeHtml(routes.lineas)}" class="block">
      <article class="tarjeta-tecnologia group flex h-full min-h-[118px] cursor-pointer items-center rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
        
        <div class="flex items-center gap-4">
          <div class="flex h-12 w-12 min-h-[48px] min-w-[48px] items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong">
            ${escapeHtml(getInitials(linea.nombre_linea))}
          </div>

          <div>
            <div class="font-[Montserrat] text-[13px] font-bold leading-[1.25] text-sena-text-main">
              ${escapeHtml(truncateText(linea.nombre_linea, 26, 'Sin nombre'))}
            </div>

            <div class="text-xs font-bold text-sena-text-muted">
              ${escapeHtml(linea.total_perfiles ?? 0)} perfiles
            </div>

            <div class="text-[11px] font-semibold text-sena-text-muted mt-1">
              Área: ${escapeHtml(truncateText(linea.nombre_area, 28, 'Sin área'))}
            </div>
          </div>
        </div>

      </article>
    </a>
  `).join('');
}

function renderProgramas(programas) {
  const container = document.getElementById('programas-formacion');

  if (!programas || programas.length === 0) {
    renderEmptyState('programas-formacion', {
      icon: 'graduation-cap',
      title: 'No hay programas de formación',
      subtitle: 'Haz clic para ir a la vista de programas de formación.',
      href: routes.programas
    });
    return;
  }

  container.innerHTML = programas.map(programa => `
    <a href="${escapeHtml(routes.programas)}" class="block">
      <article class="tarjeta-tecnologia group flex h-full min-h-[118px] cursor-pointer items-center rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
        
        <div class="flex items-center gap-4">
          <div class="flex h-14 w-14 min-h-[56px] min-w-[56px] items-center justify-center rounded-xl bg-sena-soft text-sm font-bold text-sena-strong">
            ${escapeHtml(getProgramInitials(programa.nombre_programa))}
          </div>

          <div>
            <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
              ${escapeHtml(truncateText(programa.nombre_programa, 20, 'Sin nombre'))}
            </div>

            <div class="text-xs font-bold text-sena-text-muted">
              ${escapeHtml(programa.cupos_formacion ?? 0)} cupos
            </div>

            <div class="text-[11px] font-semibold text-sena-text-muted mt-1">
              ${escapeHtml(truncateText(programa.nombre_nivel, 18, 'Sin nivel'))} · ${escapeHtml(truncateText(programa.modalidad, 18, 'Sin modalidad'))}
            </div>
          </div>
        </div>

      </article>
    </a>
  `).join('');
}

// ================= FETCH =================

async function fetchData(action) {
  const response = await fetch(`${controllerUrl}?accion=${action}`);

  if (!response.ok) {
    throw new Error(`Error HTTP ${response.status}`);
  }

  const result = await response.json();

  if (result.error) {
    throw new Error(result.error);
  }

  return result.data || [];
}

// ================= INIT =================

async function cargarLanding() {
  try {
    const [perfiles, lineas, programas] = await Promise.all([
      fetchData('ultimosPerfiles'),
      fetchData('lineasConMasPerfiles'),
      fetchData('programasConMasCupos')
    ]);

    renderPerfiles(perfiles);
    renderLineas(lineas);
    renderProgramas(programas);

    if (window.lucide) {
      lucide.createIcons();
    }

  } catch (error) {
    console.error('Error cargando landing:', error);

    renderEmptyState('perfiles-recientes', {
      icon: 'briefcase-business',
      title: 'No se pudieron cargar los perfiles',
      subtitle: 'Haz clic para ir a la vista de perfiles ocupacionales.',
      href: routes.perfiles
    });

    renderEmptyState('lineas-tecnologicas', {
      icon: 'bar-chart-3',
      title: 'No se pudieron cargar las líneas tecnológicas',
      subtitle: 'Haz clic para ir a la vista de líneas tecnológicas.',
      href: routes.lineas
    });

    renderEmptyState('programas-formacion', {
      icon: 'graduation-cap',
      title: 'No se pudieron cargar los programas',
      subtitle: 'Haz clic para ir a la vista de programas de formación.',
      href: routes.programas
    });
  }
}

document.addEventListener('DOMContentLoaded', cargarLanding);