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
              <p id="historial-stat-creaciones" class="text-2xl font-bold text-sena-text-main">0</p>
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
              <p id="historial-stat-ediciones" class="text-2xl font-bold text-sena-text-main">0</p>
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
              <p id="historial-stat-desactivaciones" class="text-2xl font-bold text-sena-text-main">0</p>
              <p class="text-sm text-sena-text-main">Desactivaciones</p>
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
            <option value="">Todos los módulos...</option>
          </select>
        </div>
        <div class="min-w-[170px]">
          <select id="historial-filter-rol" class="linea-tec-select w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none">
            <option value="">Todos los roles...</option>
          </select>
        </div>
      </div>
      <p id="historial-results" class="text-sm text-sena-text-soft m-4 mb-3">Mostrando 0 de 0 registros</p>

      <div id="historial-list" class="space-y-4"></div>

      <div id="historial-empty-state" class="bg-white rounded-xl border border-sena-border p-8 text-center">
        <p class="text-sm text-sena-text-soft">No hay acciones registradas por el momento.</p>
      </div>

    </div>
  </main>

  <script src="../../assets/js/historial/historial.js"></script>

<?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>