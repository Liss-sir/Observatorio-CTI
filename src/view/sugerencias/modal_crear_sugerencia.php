<!-- modal_crear_sugerencia.php -->
<div id="modal-crear-sugerencia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Close button -->
        <button class="cerrar-modal-crear absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-4">
          <h3 class="text-lg font-semibold text-sena-text-main">Nueva Sugerencia</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para crear una nueva sugerencia.</p>
        </div>

        <!-- Form -->
        <form id="form-nueva-proyeccion-futuro" class="px-6 py-4">
          <!-- Título -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Título</label>
            <input type="text" 
                   id="input-titulo-sugerencia"
                   name="titulo" 
                   placeholder="Ingresa el título de la sugerencia..."
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/20">
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" 
                      id="textarea-contenido-sugerencia"
                      rows="4" 
                      placeholder="Describe la sugerencia..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/20"></textarea>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-crear rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
              Crear Sugerencia
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
