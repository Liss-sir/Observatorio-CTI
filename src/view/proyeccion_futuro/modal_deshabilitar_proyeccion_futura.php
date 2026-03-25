<!-- modal_deshabilitar_proyeccion_futura.php -->
<div id="modal-deshabilitar-proyeccion-futura" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all animate-modalFadeIn">
        
        <!-- Close button -->
        <button class="cerrar-modal-deshabilitar absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-4">
          <h3 class="text-lg font-semibold text-sena-text-main">Deshabilitar Proyección a Futuro</h3>
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            ¿Estás seguro de deshabilitar <span id="nombre-proyeccion-deshabilitar" class="font-medium  text-[#e65100]">"Nombre de la proyección"</span>? 
            El registro no se eliminará, solo se marcará como inactivo.
          </p>
        </div>

        <!-- Footer buttons -->
        <div class="flex justify-end gap-3 border-t border-sena-border px-6 py-4">
          <button type="button" class="cerrar-modal-deshabilitar rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
            Cancelar
          </button>
          <button type="button" id="btn-confirmar-deshabilitar" class="rounded-lg bg-[#e65100] px-4 py-2 text-sm font-medium text-white hover:bg-[#e65100]/90">
            Desactivar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>