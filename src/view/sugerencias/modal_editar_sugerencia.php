<!-- modal_editar_sugerencia.php -->
<div id="modal-editar-sugerencia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Header con botón de cerrar integrado -->
        <div class="border-b border-sena-border px-6 py-4">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-sena-text-main">Editar Sugerencia</h3>
              <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para editar la sugerencia.</p>
            </div>
            <button class="cerrar-modal-editar text-sena-text-soft hover:text-sena-text-main transition-colors">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>

        <!-- Form -->
        <form id="form-editar-sugerencia" class="px-6 py-4">
          <!-- Tipo de Sugerencia -->
          <div class="mb-4">
            <label class="mb-2 block text-sm font-medium text-sena-text-main">Tipo de Sugerencia</label>
            <div class="flex gap-2">
              <input type="radio" name="tipo_sugerencia_editar" id="editar-tipo-programa" value="PROGRAMA" class="hidden peer/editar-programa">
              <label for="editar-tipo-programa" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/editar-programa:border-sena peer-checked/editar-programa:bg-sena/5 peer-checked/editar-programa:text-sena hover:border-gray-300">
                Programa
              </label>

              <input type="radio" name="tipo_sugerencia_editar" id="editar-tipo-linea" value="LINEA_TECNOLOGICA" class="hidden peer/editar-linea">
              <label for="editar-tipo-linea" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/editar-linea:border-sena peer-checked/editar-linea:bg-sena/5 peer-checked/editar-linea:text-sena hover:border-gray-300">
                Línea Tec.
              </label>

              <input type="radio" name="tipo_sugerencia_editar" id="editar-tipo-otros" value="OTROS" class="hidden peer/editar-otros">
              <label for="editar-tipo-otros" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/editar-otros:border-sena peer-checked/editar-otros:bg-sena/5 peer-checked/editar-otros:text-sena hover:border-gray-300">
                Otros
              </label>
            </div>
          </div>
          
          <!-- Título -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Título</label>
            <input type="text" 
                   name="titulo" 
                   id="input-titulo-editar"
                   placeholder="Ingresa el título de la sugerencia..."
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none">
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" 
                      id="textarea-descripcion-editar"
                      rows="4" 
                      placeholder="Describe la sugerencia..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none resize-none"></textarea>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-editar rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
              Guardar Cambios
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>