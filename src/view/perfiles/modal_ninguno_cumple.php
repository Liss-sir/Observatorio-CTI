<!-- modal_ninguno_cumple.php -->
<div id="modal-ninguno-cumple" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Close button -->
        <button type="button" class="cerrar-modal-ninguno absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main transition-colors">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Icon section -->
        <div class="flex justify-center pt-8">
          <div class="w-16 h-16 rounded-full bg-blue-50 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-sena">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.35-4.35"></path>
            </svg>
          </div>
        </div>

        <!-- Header -->
        <div class="text-center px-6 pt-6 pb-2">
          <h3 class="text-lg font-semibold text-sena-text-main">No encontraste lo que buscabas</h3>
        </div>

        <!-- Content -->
        <div class="px-6 py-4 text-center">
          <p class="text-sm text-sena-text-soft leading-relaxed">
            Lamentamos no encontrar un programa que se ajuste a tus necesidades. Tu opinión es importante para nosotros y nos ayuda a mejorar continuamente nuestros servicios.
          </p>
        </div>

        <!-- Footer buttons -->
        <div class="flex flex-col gap-2 border-t border-sena-border px-6 py-4">
          <button type="button" id="btn-ir-sugerencias" class="w-full rounded-lg bg-sena px-4 py-2.5 text-sm font-medium text-white hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            Ir a sugerencias
          </button>
          <button type="button" class="cerrar-modal-ninguno w-full rounded-lg border border-sena-border px-4 py-2.5 text-sm font-medium text-sena-text-main hover:bg-sena-soft transition-colors">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
