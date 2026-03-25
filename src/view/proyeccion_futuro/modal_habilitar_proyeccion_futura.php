<!-- modal_habilitar_proyeccion_futura.php -->
<!-- Modal de confirmación para habilitar proyección a futuro-->
<div id="modal-habilitar-proyeccion-futura" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header con icono de advertencia/confirmación -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 16v-4"/>
              <path d="M12 8h.01"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">¿Habilitar proyección a futuro?</h3>
            <p class="text-sm text-sena-text-soft">Esta acción activará la proyección a futuro nuevamente</p>
          </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            ¿Estás seguro de que deseas habilitar la proyección a futuro <span class="font-semibold text-sena-text-main" id="nombre-proyeccion-habilitar">"Nombre de la proyección"</span>?
          </p>
          <p class="text-xs text-sena-text-soft mt-3 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
            <span class="font-medium text-yellow-800">Información:</span> La proyección volverá a estar visible y disponible para su uso.
          </p>
        </div>

        <!-- Footer con botones -->
        <div class="border-t border-sena-border px-6 py-4 flex justify-end gap-3">
          <button type="button" class="cerrar-modal-habilitar px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-colors">
            Cancelar
          </button>
          <button type="button" id="btn-confirmar-habilitar" class="px-4 py-2 text-sm font-medium text-white bg-[#39A900] rounded-lg hover:bg-green-700 transition-colors">
            <span class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Sí, habilitar proyección a futuro
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

