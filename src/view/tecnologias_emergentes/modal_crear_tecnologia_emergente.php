<!-- modal_crear_tecnologia_emergente.php -->
<div id="modal-crear-tecnologia-emergente" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
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
          <h3 class="text-lg font-semibold text-sena-text-main">Nueva Tecnología Emergente</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para crear una nueva tecnología emergente.</p>
        </div>

        <!-- Form -->
        <form id="form-nueva-tecnologia-emergente" class="px-6 py-4">
          <!-- área -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Área</label>
            <div class="select-container">
              <select name="area" id="area-crear" class="custom-select">
                <option value="">Selecciona un área</option>
                <!-- Las opciones se cargarán dinámicamente desde JavaScript -->
              </select>
            </div>
          </div>

          <!-- Descripción (este valor se enviará como "nombre" al backend) -->
          <div class="mb-2">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea 
                name="descripcion" 
                id="descripcion-crear"
                rows="3"  
                placeholder="Describe la tecnología emergente."
                class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none resize-none">
            </textarea>
          </div>
          
          <!-- Contador de caracteres -->
          <div class="flex justify-end items-center gap-2 mb-4">
            <span id="contador-caracteres-crear" class="text-xs text-sena-text-soft">0 / 30 caracteres</span>
            <span id="alerta-minimo-crear" class="text-xs text-sena-text-soft hidden">Mínimo 30 caracteres requerido</span>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-crear rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" id="btn-submit-crear" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
              Crear Tecnología Emergente
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>