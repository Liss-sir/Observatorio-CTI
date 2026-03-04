<!-- modal_confirmacion_editar_te.php -->
<!-- Modal de éxito - tecnología emergente editada correctamente (desaparece en 3 segundos) -->
<div id="modal-editado-confirmacion-tecnologia-emergente" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header con icono de edición y verde SENA -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <!-- Círculo con el verde SENA al 10% de opacidad -->
          <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(57, 169, 0, 0.1);">
            <!-- Icono de lápiz (para edición) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: #39A900;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
              <path d="m15 5 4 4"/>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">¡Tecnología Emergente Editada!</h3>
            <p class="text-sm text-sena-text-soft">La tecnología emergente se ha actualizado exitosamente</p>
          </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            La tecnología emergente <span class="font-semibold" style="color: #39A900;" id="nombre-tecnologia-editada">"Inteligencia Artificial"</span> ha sido actualizada correctamente.
          </p>
          <div class="mt-4 flex items-center gap-2 text-xs text-sena-text-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Esta ventana se cerrará automáticamente en <span id="contador-segundos-editado">3</span> segundos</span>
          </div>
        </div>

        <!-- Barra de progreso animada con verde SENA -->
        <div class="h-1.5 bg-gray-100 w-full rounded-b-xl overflow-hidden">
          <div id="progress-bar-editado" class="h-full transition-all duration-[3000ms] ease-linear" style="width: 0%; background-color: #39A900;"></div>
        </div>

        <!-- Botón para cerrar manualmente -->
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