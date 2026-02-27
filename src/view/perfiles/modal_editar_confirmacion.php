<!-- modal_editar_confirmacion.php -->
<!-- Modal de éxito - Perfil editado correctamente (desaparece en 3 segundos) -->
<div id="modal-editado-confirmacion" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header con icono más bonito y azul institucional #00304D -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
          <!-- Círculo con el azul institucional al 10% de opacidad -->
          <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: rgba(0, 48, 77, 0.1);">
            <!-- Icono de check con círculo (más elegante) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: #00304D;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">¡Perfil Editado!</h3>
            <p class="text-sm text-sena-text-soft">Los cambios se guardaron correctamente</p>
          </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            El perfil <span class="font-semibold" style="color: #00304D;" id="nombre-perfil-editado">"Desarrollador Full Stack Senior"</span> ha sido actualizado correctamente.
          </p>
          <div class="mt-4 flex items-center gap-2 text-xs text-sena-text-soft">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>Esta ventana se cerrará automáticamente en <span id="contador-segundos-editado">3</span> segundos</span>
          </div>
        </div>

        <!-- Barra de progreso animada con azul institucional -->
        <div class="h-2 bg-gray-100 w-full rounded-b-xl overflow-hidden">
          <div id="progress-bar-editado" class="h-1 transition-all duration-[3000ms] ease-linear" style="width: 0%; background-color: #00304D;"></div>
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