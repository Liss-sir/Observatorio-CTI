<!-- modal_detalle_tendencia_act.php -->
<!-- Modal para ver detalle de tendencia actual -->
<div id="modal-detalle-area" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
<link rel="stylesheet" href="../../../assets/css/output.css">
<link rel="stylesheet" href="../../assets/css/globals.css">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header con icono de información -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-sena-soft flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 16v-4"/>
              <path d="M12 8h.01"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main" id="detalle-titulo">Detalle de Area</h3>
            <p class="text-sm text-sena-text-soft">Información completa del area seleccionada</p>
          </div>
          
          <!-- Close button -->
          <button class="cerrar-modal-detalle absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">
          <!-- Área/Nombre y Estado en la misma fila -->
          <div class="grid grid-cols-2 mb-6">
            <div class="pr-8">
              <span class="text-xs font-medium text-sena-text-soft uppercase tracking-wider">Nombre del área</span>
              <p class="text-base font-semibold text-sena-text-main mt-1" id="detalle-nombre">Inteligencia Artificial</p>
            </div>
            
            <!-- Estado con badge de colores institucionales -->
            <div>
              <span class="text-xs font-medium text-sena-text-soft uppercase tracking-wider">Estado</span>
              <div class="mt-1">
                <span id="detalle-estado-badge" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena">
                  <span id="detalle-estado-indicador" class="w-2 h-2 rounded-full bg-[#39A900] mr-2"></span>
                  <span id="detalle-estado">Activo</span>
                </span>
              </div>
            </div>
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <span class="text-xs font-medium text-sena-text-soft uppercase tracking-wider">Descripción</span>
            <p class="text-sm text-sena-text-soft mt-1 leading-relaxed" id="detalle-descripcion">Computación cuántica accesible desde la nube para desarrolladores y empresas, permitiendo experimentación real en problemas de optimización y criptografía.</p>
          </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-sena-border px-6 py-4 flex justify-end">
          <button class="cerrar-modal-detalle px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-colors">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  @keyframes modalFadeIn {
    from {
      opacity: 0;
      transform: scale(0.95) translateY(-10px);
    }
    to {
      opacity: 1;
      transform: scale(1) translateY(0);
    }
  }
  .animate-modalFadeIn {
    animation: modalFadeIn 0.3s ease-out;
  }
</style>