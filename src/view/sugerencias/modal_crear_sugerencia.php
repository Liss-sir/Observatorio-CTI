<!-- modal_crear_sugerencia.php -->
<div id="modal-crear-sugerencia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
   <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Close button -->
        <button class="cerrar-modal-crear absolute right-5 top-5 text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="px-6 pt-6 pb-2">
          <h3 class="text-xl font-semibold text-gray-900">Nueva sugerencia</h3>
          <p class="mt-1 text-sm text-gray-500">Completa los datos para crear una nueva sugerencia.</p>
        </div>

        <!-- Form -->
        <form id="form-nueva-proyeccion-futuro" class="px-6 py-4">
          <!-- Tipo de Sugerencia - Minimalista -->
          <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">Tipo de sugerencia</label>
            <div class="flex gap-2">
              <input type="radio" name="tipo_sugerencia" id="tipo-programa" value="PROGRAMA" class="hidden peer/programa">
              <label for="tipo-programa" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/programa:border-sena peer-checked/programa:bg-sena/5 peer-checked/programa:text-sena hover:border-gray-300">
                Programa
              </label>

              <input type="radio" name="tipo_sugerencia" id="tipo-linea" value="LINEA_TECNOLOGICA" class="hidden peer/linea">
              <label for="tipo-linea" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/linea:border-sena peer-checked/linea:bg-sena/5 peer-checked/linea:text-sena hover:border-gray-300">
                Línea Tec.
              </label>

              <input type="radio" name="tipo_sugerencia" id="tipo-otros" value="OTROS" class="hidden peer/otros">
              <label for="tipo-otros" 
                     class="flex-1 text-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-600 cursor-pointer transition-all peer-checked/otros:border-sena peer-checked/otros:bg-sena/5 peer-checked/otros:text-sena hover:border-gray-300">
                Otros
              </label>
            </div>
          </div>
          
          <!-- Título -->
          <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">Título</label>
            <input type="text" 
                   id="input-titulo-sugerencia"
                   name="titulo" 
                   placeholder="Ingresa el título de la sugerencia..."
                   class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-sena focus:ring-1 focus:ring-sena/20 transition-all outline-none">
          </div>

          <!-- Descripción -->
          <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" 
                      id="textarea-contenido-sugerencia"
                      rows="4" 
                      placeholder="Describe la sugerencia..."
                      class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-sena focus:ring-1 focus:ring-sena/20 transition-all outline-none resize-none"></textarea>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
            <button type="button" class="cerrar-modal-crear px-4 py-2 text-sm font-medium rounded-lg border border-sena-border text-gray-600 hover:text-gray-800 transition-colors">
              Cancelar
            </button>
            <button type="submit" class="px-5 py-2 text-sm font-medium  text-white bg-sena rounded-lg hover:opacity-90 transition-opacity">
              Crear sugerencia
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>