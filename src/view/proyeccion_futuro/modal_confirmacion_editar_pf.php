<!-- modal_confirmacion_editar_pf.php -->
<div id="modal-editado-confirmacion" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-[#39A900] bg-opacity-10 flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#39A900]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">¡Proyección a Futuro Actualizada!</h3>
            <p class="text-sm text-sena-text-soft">La operación se realizó correctamente</p>
          </div>
        </div>

        <div class="px-6 py-5">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            La proyección a futuro <span class="text-sena" id="nombre-proyeccion-editado"></span> ha sido actualizada correctamente.
          </p>
          <div class="mt-4 flex items-center gap-2 text-xs text-sena-text-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Esta ventana se cerrará automáticamente en <span id="contador-segundos-editado">3</span> segundos</span>
          </div>
        </div>

        <div class="h-1.5 bg-gray-100 w-full rounded-b-xl overflow-hidden">
          <div id="progress-bar-editado" class="h-1 bg-[#39A900] transition-all duration-[3000ms] ease-linear" style="width: 0%;"></div>
        </div>

        <div class="absolute top-3 right-3">
          <button class="cerrar-modal-editado p-1 text-sena-text-soft hover:text-sena-text-main transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6 6 18"/>
              <path d="m6 6 12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>