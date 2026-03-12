<?php
// filepath: c:\wamp64\www\Observatorio-CTI\src\view\historial\historial.php
include __DIR__ . '../../../includes/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Actividad - SENA</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
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
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">


  <!-- Contenido principal -->
  <main class="flex-1 bg-white">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">
      
      <!-- Título -->
      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 shrink-0 rounded-full border border-sena-border flex items-center justify-center bg-white">
          <svg class="w-[17px] h-[17px] text-sena-text-soft" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1 0 3-6.708" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v6h6" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
          </svg>
        </div>
        <div>
          <h1 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Historial de Actividad</h1>
          <p class="mt-2 text-sm text-sena-text-soft">Registro de todas las acciones realizadas por los usuarios en la plataforma</p>
        </div>
      </div>

      <!-- Tarjetas de estadísticas -->
      <div class="flex gap-3 mb-6 overflow-x-auto pb-1">
        <!-- Creaciones -->
        <div class="min-w-[230px] flex-1 bg-white rounded-xl border border-sena-border px-4 h-20 flex items-center">
          <div class="flex items-center gap-3 w-full justify-center">
            <div class="w-10 h-10 shrink-0 rounded-full bg-sena-soft flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
            </div>
            <div class="flex items-center gap-2 whitespace-nowrap">
              <p class="text-2xl font-bold text-sena-text-main">4</p>
              <p class="text-sm text-sena-text-main">Creaciones</p>
            </div>
          </div>
        </div>
        <!-- Ediciones -->
        <div class="min-w-[230px] flex-1 bg-white rounded-xl border border-sena-border px-4 h-20 flex items-center">
          <div class="flex items-center gap-3 w-full justify-center">
            <div class="w-10 h-10 shrink-0 rounded-full bg-amber-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
              </svg>
            </div>
            <div class="flex items-center gap-2 whitespace-nowrap">
              <p class="text-2xl font-bold text-sena-text-main">3</p>
              <p class="text-sm text-sena-text-main">Ediciones</p>
            </div>
          </div>
        </div>
        <!-- Desactivaciones -->
        <div class="min-w-[230px] flex-1 bg-white rounded-xl border border-sena-border px-4 h-20 flex items-center">
          <div class="flex items-center gap-3 w-full justify-center">
            <div class="w-10 h-10 shrink-0 rounded-full bg-rose-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12"/>
              </svg>
            </div>
            <div class="flex items-center gap-2 whitespace-nowrap">
              <p class="text-2xl font-bold text-sena-text-main">1</p>
              <p class="text-sm text-sena-text-main">Desactivaciones</p>
            </div>
          </div>
        </div>
        <!-- Sesiones / Registros -->
        <div class="min-w-[230px] flex-1 bg-white rounded-xl border border-sena-border px-4 h-20 flex items-center">
          <div class="flex items-center gap-3 w-full justify-center">
            <div class="w-10 h-10 shrink-0 rounded-full bg-sky-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
              </svg>
            </div>
            <div class="flex items-center gap-2 whitespace-nowrap">
              <p class="text-2xl font-bold text-sena-text-main">2</p>
              <p class="text-sm text-sena-text-main">Sesiones / Registros</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtros -->
      <div class="flex flex-wrap items-center gap-3 mb-2">
        <div class="relative grow basis-[420px] min-w-[300px]">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input id="historial-search" type="text" placeholder="Buscar por Rol, detalle, acción o módulo." class="w-full h-11 pl-10 pr-4 text-sm border border-sena-border rounded-xl bg-white text-sena-text-main placeholder:text-sena-text-soft shadow-sm focus:outline-none focus:ring-2 focus:ring-sena/20 focus:border-sena transition-all">
        </div>
        <div class="min-w-[170px]">
          <select id="historial-filter-accion" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none">
            <option value="">Todas las acciones...</option>
          </select>
        </div>
        <div class="min-w-[200px]">
          <select id="historial-filter-modulo" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none">
            <option>Todos los módulos...</option>
          </select>
        </div>
        <div class="min-w-[170px]">
          <select id="historial-filter-rol" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none">
            <option>Todos los roles...</option>
          </select>
        </div>
      </div>
      <p id="historial-results" class="text-sm text-sena-text-soft m-4 mb-3">Mostrando 10 de 10 registros</p>

      <!-- Lunes, 23 De Febrero De 2026 -->
      <div class="historial-day-section mb-4">
        <div class="flex items-center gap-2 mb-3">
          <svg class="w-5 h-5 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
          </svg>
          <span class="text-sm font-medium text-sena-text-main">Lunes, 23 De Febrero De 2026</span>
          <span class="historial-day-count bg-sena text-white text-xs font-medium px-2 py-0.5 rounded-full">2</span>
        </div>
        <div class="bg-white rounded-xl border border-sena-border overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="border-b border-sena-border">
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Hora</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Usuario</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Rol</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Acción</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Módulo</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Detalle</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-sena-border">
                <td class="px-4 py-3 text-sm text-sena-text-soft">09:28</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Administrador SENA</p>
                  <p class="text-xs text-sena-text-soft">admin@sena.edu.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Administrador</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-sena-soft text-sena-strong text-xs font-medium px-2.5 py-1 rounded-md">Creación</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Perfil</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Creo el perfil 'Desarrollador Full Stack Senior'</td>
              </tr>
              <tr>
                <td class="px-4 py-3 text-sm text-sena-text-soft">09:15</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Administrador SENA</p>
                  <p class="text-xs text-sena-text-soft">admin@sena.edu.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Administrador</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-sena-soft text-sena-strong text-xs font-medium px-2.5 py-1 rounded-md">Creación</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Empresa</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Creo la empresa 'TechColombia S.A.S.'</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Domingo, 22 De Febrero De 2026 -->
      <div class="historial-day-section mb-4">
        <div class="flex items-center gap-2 mb-3">
          <svg class="w-5 h-5 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
          </svg>
          <span class="text-sm font-medium text-sena-text-main">Domingo, 22 De Febrero De 2026</span>
          <span class="historial-day-count bg-sena text-white text-xs font-medium px-2 py-0.5 rounded-full">2</span>
        </div>
        <div class="bg-white rounded-xl border border-sena-border overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="border-b border-sena-border">
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Hora</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Usuario</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Rol</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Acción</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Módulo</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Detalle</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-sena-border">
                <td class="px-4 py-3 text-sm text-sena-text-soft">14:30</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Carlos Mendoza</p>
                  <p class="text-xs text-sena-text-soft">carlos@techcolombia.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Empresa</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-amber-100 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-md">Edición</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Perfil</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Editó el perfil 'Analista de Datos'</td>
              </tr>
              <tr>
                <td class="px-4 py-3 text-sm text-sena-text-soft">11:45</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Administrador SENA</p>
                  <p class="text-xs text-sena-text-soft">admin@sena.edu.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Administrador</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-sena-soft text-sena-strong text-xs font-medium px-2.5 py-1 rounded-md">Creación</span>
                </td>
                <td class="px-4 py-3">
                  <p class="text-sm text-sena-text-main">Linea</p>
                  <p class="text-xs text-sena-text-soft">Tecnológica</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Creo la linea tecnológica 'Inteligencia Artificial Avanzada'</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Sábado, 21 De Febrero De 2026 -->
      <div class="historial-day-section mb-4">
        <div class="flex items-center gap-2 mb-3">
          <svg class="w-5 h-5 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
          </svg>
          <span class="text-sm font-medium text-sena-text-main">Sábado, 21 De Febrero De 2026</span>
          <span class="historial-day-count bg-sena text-white text-xs font-medium px-2 py-0.5 rounded-full">3</span>
        </div>
        <div class="bg-white rounded-xl border border-sena-border overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="border-b border-sena-border">
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Hora</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Usuario</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Rol</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Acción</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Módulo</th>
                <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Detalle</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-sena-border">
                <td class="px-4 py-3 text-sm text-sena-text-soft">16:42</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">María Lopez</p>
                  <p class="text-xs text-sena-text-soft">maria.lopez@gmail.com</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Persona</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-sky-100 text-sky-700 text-xs font-medium px-2.5 py-1 rounded-md">Registro</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Usuario</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Se registró como Persona Natural - Soluciones Digitales ML</td>
              </tr>
              <tr class="border-b border-sena-border">
                <td class="px-4 py-3 text-sm text-sena-text-soft">14:28</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Administrador SENA</p>
                  <p class="text-xs text-sena-text-soft">admin@sena.edu.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Administrador</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-rose-100 text-rose-700 text-xs font-medium px-2.5 py-1 rounded-md">Desactivación</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Perfil</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Desactivó el perfil 'Técnico en Redes'</td>
              </tr>
              <tr>
                <td class="px-4 py-3 text-sm text-sena-text-soft">18:15</td>
                <td class="px-4 py-3">
                  <p class="text-sm font-medium text-sena-text-main">Carlos Mendoza</p>
                  <p class="text-xs text-sena-text-soft">carlos@techcolombia.co</p>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Empresa</td>
                <td class="px-4 py-3">
                  <span class="inline-block bg-amber-100 text-amber-700 text-xs font-medium px-2.5 py-1 rounded-md">Edición</span>
                </td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Empresa</td>
                <td class="px-4 py-3 text-sm text-sena-text-main">Editó los datos de la empresa 'TechColombia S.A.S.'</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  <script src="../../assets/js/historial.js"></script>

<?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>