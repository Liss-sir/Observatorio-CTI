<?php include __DIR__ . '/../../includes/header.php'; ?>

  <main class="flex-1 text-sena-text-main">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

    <!-- ============ BACK LINK ============ -->
    <a href="lineas_tecnologicas.php" class="inline-flex items-center gap-1.5 text-sm text-sena-text-soft hover:text-sena-text-main transition-colors mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      Volver a resultados
    </a>

    <div class="mb-8">
      <!-- ============ BADGES ============ -->
      <div class="flex flex-wrap items-center gap-3 mb-3">
        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena-soft text-sena-strong">
          Lineas Tecnologicas
        </span>
        <span id="detalle-badge-estado" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4" />
          </svg>
          Vigente
        </span>
      </div>

      <!-- ============ TITLE + ACTIONS ROW ============ -->
      <div class="flex items-start justify-between gap-6 flex-wrap">
        <div class="min-w-0">
          <h1 id="detalle-linea-titulo" class="text-2xl lg:text-3xl font-bold text-sena-text-main break-words [overflow-wrap:anywhere]">
            Linea Tecnologica
          </h1>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
        <!-- Editar -->
        <button id="btn-detalle-editar" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena-border rounded-lg bg-white text-sena-text-main hover:bg-sena-soft transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
          Editar
        </button>
        <!-- Desactivar -->
        <button id="btn-detalle-deshabilitar" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
          Desactivar
        </button>
        </div>
      </div>
    </div>

    <!-- ============ TWO-COLUMN LAYOUT ============ -->
    <div class="grid gap-6 lg:grid-cols-[2fr_1fr] items-start">

      <!-- ======== LEFT COLUMN ======== -->
      <div class="flex flex-col gap-4">
        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-base font-bold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L12 12.75 6.429 9.75m11.142 0 4.179 2.25-9.75 5.25-9.75-5.25 4.179-2.25" /></svg>
            Tendencia Actual
          </h3>
          <p id="detalle-tendencia-texto" class="text-sm font-medium text-sena-text-soft break-words [overflow-wrap:anywhere]">No registrada</p>
        </div>

        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-base font-bold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            Proyección a Futuro
          </h3>
          <ul id="detalle-proyeccion-list" class="list-disc pl-5 space-y-2 text-sm text-sena-text-soft break-words [overflow-wrap:anywhere]">
            <li>No registrada</li>
          </ul>
        </div>

        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-base font-bold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
            Tecnologias Emergentes Relacionadas
          </h3>
          <ul id="detalle-tecnologias-list" class="list-disc pl-5 space-y-2 text-sm text-sena-text-soft break-words [overflow-wrap:anywhere]">
            <li>No registradas</li>
          </ul>
        </div>
      </div>

      <!-- ======== RIGHT COLUMN ======== -->
      <div class="flex flex-col gap-4">

        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-base font-bold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M3 7.5V12l9 4.5 9-4.5V7.5M3 12l9 4.5M21 12l-9 4.5" /></svg>
            Área Relacionada
          </h3>
          <p id="detalle-area-texto" class="text-sm font-medium text-sena-text-soft break-words [overflow-wrap:anywhere]">No registrada</p>
        </div>

        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-base font-bold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14 21 9l-9-5-9 5 9 5Zm0 0v6" /><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12.5v4.5c0 .6 2 2 4.5 2s4.5-1.4 4.5-2v-4.5" /></svg>
            Programa de Formación
          </h3>
          <p id="detalle-programa-formacion-texto" class="text-sm font-medium text-sena-text-soft break-words [overflow-wrap:anywhere]">No registrado</p>
        </div>

        <!-- Linea Vigente -->
        <div id="detalle-estado-card" class="border border-sena/30 rounded-xl bg-sena/5 p-5 flex items-center gap-3">
          <div class="flex items-center gap-3">
            <svg id="detalle-estado-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4"/></svg>
            <div>
              <h4 id="detalle-estado-titulo" class="text-sm font-semibold text-sena">Linea Vigente</h4>
              <p id="detalle-estado-texto" class="text-xs text-sena-text-soft">Esta linea se encuentra activa</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    </div>
  </main>

  <script src="../../assets/js/lineas_tecnologicas/lineas_tecnologicas.js"></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>