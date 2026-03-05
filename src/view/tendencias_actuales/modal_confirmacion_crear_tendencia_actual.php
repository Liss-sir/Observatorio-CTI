<!-- modal_confirmacion_crear_tendencia_actual -->
<!-- Modal de éxito - Perfil creado correctamente (desaparece en 3 segundos) -->
<div id="modal-creado-confirmacion-tendencia-actual" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header con icono de creación y verde SENA -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <!-- Círculo con el verde SENA al 10% de opacidad -->
          <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(57, 169, 0, 0.1);">
            <!-- Icono de "plus" con círculo (para creación) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: #39A900;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 8v8"></path>
              <path d="M8 12h8"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">¡Tendencia Actual Creada!</h3>
            <p class="text-sm text-sena-text-soft">La tendencia actual se ha creado exitosamente</p>
          </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            La tendencia actual <span class="font-semibold" style="color: #39A900;" id="nombre-tendencia-creada">"Desarrollador Full Stack Senior"</span> ha sido creada correctamente.
          </p>
          <div class="mt-4 flex items-center gap-2 text-xs text-sena-text-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Esta ventana se cerrará automáticamente en <span id="contador-segundos-creado">3</span> segundos</span>
          </div>
        </div>

        <!-- Barra de progreso animada con verde SENA -->
        <div class="h-2 bg-gray-100 w-full rounded-b-xl overflow-hidden">
          <div id="progress-bar-creado" class="h-1 transition-all duration-[3000ms] ease-linear" style="width: 0%; background-color: #39A900;"></div>
        </div>

        <!-- Botón para cerrar manualmente -->
        <div class="absolute top-3 right-3">
          <button class="cerrar-modal-creado p-1 text-sena-text-soft hover:text-sena-text-main transition-colors">
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