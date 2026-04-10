<!-- modal_crear_perfiles.php -->
<div id="modal-crear-perfil" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
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
          <h3 class="text-lg font-semibold text-sena-text-main">Nuevo Perfil Ocupacional</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para crear un nuevo perfil ocupacional.</p>
        </div>

        <!-- Form -->
        <form id="form-crear-perfil" class="px-6 py-4">
          <!-- ID del usuario (oculto) -->
          <input type="hidden" name="id_usuario" id="id_usuario_crear" value="">
          
          <!-- Nombre del perfil ocupacional -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre del perfil ocupacional</label>
            <input type="text" name="nombre" id="nombre_perfil" placeholder="Ej: Desarrollador Full Stack Senior"
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none" >
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" id="descripcion_perfil" rows="3" placeholder="Describe las responsabilidades, requisitos y habilidades necesarias para este perfil..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none resize-none"></textarea>
          </div>

          <!-- Línea Tecnológica -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Línea Tecnológica</label>
            <div class="select-container">
              <select name="id_linea" id="linea_tecnologica" class="custom-select w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none">
                <option value="" disabled selected>Cargando líneas tecnológicas...</option>
              </select>
            </div>
          </div>

          <!-- Programa de Formación -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Programa de Formación</label>
            <div class="select-container">
              <select name="id_programa" id="programa_formacion" class="custom-select w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none" disabled>
                <option value="" disabled selected>Primero selecciona una línea tecnológica</option>
              </select>
            </div>
          </div>

          <!-- Nivel de Formación -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nivel de Formación</label>
            <div class="select-container">
              <select name="id_nivel" id="nivel_formacion" class="custom-select w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none">
                <option value="" disabled selected>Cargando niveles de formación...</option>
              </select>
            </div>
          </div>

          <!-- Cupos -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Cupos disponibles</label>
            <input type="number" name="cupos" id="cupos_perfil" placeholder="Número de personas necesarias" min="1" value="1"
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena focus:ring-opacity-20 focus:outline-none">
          </div>

          <!-- Estado (por defecto activo) -->
          <input type="hidden" name="estado" value="1">

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-crear rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft transition-colors">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
              Crear Perfil
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>