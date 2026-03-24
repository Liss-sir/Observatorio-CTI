<!-- modal_ver_detalles_sugerencia.php -->
<div id="modal-ver-detalles" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Header con badge de tipo y botón de cerrar reposicionado -->
        <div class="px-6 pt-6 pb-2">
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-2 flex-1">
              <div class="w-1 h-6 bg-sena rounded-full"></div>
              <h3 class="text-lg font-semibold text-gray-900" id="detalle-titulo">Detalle de sugerencia</h3>
            </div>
            <div class="flex items-center gap-3">
              <span id="detalle-tipo-badge" class="px-3 py-1 text-xs font-medium rounded-full"></span>
              <button class="cerrar-modal-detalles text-gray-300 hover:text-gray-500 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
          <!-- Título completo -->
          <div class="mb-5">
            <label class="mb-2 block text-xs font-medium text-gray-500 uppercase tracking-wide">Título</label>
            <p id="detalle-titulo-completo" class="text-base font-medium text-gray-900 leading-relaxed"></p>
          </div>

          <!-- Descripción completa -->
          <div class="mb-6">
            <label class="mb-2 block text-xs font-medium text-gray-500 uppercase tracking-wide">Descripción</label>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
              <p id="detalle-descripcion" class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap"></p>
            </div>
          </div>

          <!-- Información adicional -->
          <div class="grid grid-cols-2 gap-4 mb-6 pt-2 border-t border-gray-100">
            <div>
              <label class="mb-1 block text-xs font-medium text-gray-500 uppercase tracking-wide">Autor</label>
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <p id="detalle-autor" class="text-sm text-gray-600"></p>
              </div>
            </div>
            <div>
              <label class="mb-1 block text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha de creación</label>
              <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                  <line x1="16" y1="2" x2="16" y2="6"></line>
                  <line x1="8" y1="2" x2="8" y2="6"></line>
                  <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <p id="detalle-fecha" class="text-sm text-gray-600"></p>
              </div>
            </div>
          </div>

          <!-- Estado -->
          <div class="mb-2">
            <label class="mb-2 block text-xs font-medium text-gray-500 uppercase tracking-wide">Estado</label>
            <div class="inline-flex items-center gap-2">
              <div id="detalle-estado-badge" class="px-3 py-1 text-xs font-medium rounded-full"></div>
            </div>
          </div>
        </div>

        <!-- Footer buttons -->
        <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4">
          <button type="button" class="cerrar-modal-detalles px-4 py-2 text-sm  border border-sena-border rounded-lg font-medium text-gray-600 hover:text-gray-800 transition-colors">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Badges de tipo */
  .badge-programa {
    background-color: #e6f7e6;
    color: #2e7d32;
  }
  .badge-linea {
    background-color: #e3f2fd;
    color: #1565c0;
  }
  .badge-otros {
    background-color: #f3e5f5;
    color: #7b1fa2;
  }
  .badge-activo {
    background-color: #e8f5e9;
    color: #2e7d32;
  }
  .badge-inactivo {
    background-color: #ffebee;
    color: #c62828;
  }
</style>