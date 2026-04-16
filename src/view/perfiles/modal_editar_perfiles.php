<!-- modal_editar_perfiles.php -->
<div id="modal-editar-perfil" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all animate-slideDownModal">
        
        <!-- Close button -->
        <button class="cerrar-modal absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-4">
          <h3 class="text-lg font-semibold text-sena-text-main">Editar Perfil Ocupacional</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Modifica los datos del perfil de trabajo.</p>
        </div>

        <!-- Form -->
        <form id="form-editar-perfil" class="px-6 py-4">
          <!-- ID del perfil (oculto) -->
          <input type="hidden" name="id_perfil" id="id_perfil_editar" value="">

          <!-- Nombre -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre del perfil ocupacional *</label>
            <input type="text" name="nombre" id="editar_nombre" placeholder="Ej: Desarrollador Full Stack Senior"
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none" required>
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción *</label>
            <textarea name="descripcion" id="editar_descripcion" rows="4" placeholder="Describe las responsabilidades, requisitos y habilidades necesarias..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none" required></textarea>
            <div class="mt-2 flex justify-between items-center">
              <span id="alerta-minimo-editar-perfil" class="hidden text-xs text-red-500">Mínimo 30 caracteres requeridos</span>
              <span id="contador-caracteres-editar-perfil" class="text-xs text-sena-text-soft">0 / 30 caracteres</span>
            </div>
          </div>

          <!-- Nivel de Formación -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nivel de Formación *</label>
            <select name="id_nivel" id="editar_nivel_formacion" class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none" required>
              <option value="" disabled selected>Cargando niveles...</option>
            </select>
          </div>

          <!-- Cupos -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Cupos disponibles *</label>
            <input type="number" name="cupos" id="editar_cupos" placeholder="Número de personas necesarias" min="1"
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20 focus:outline-none" required>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft transition-colors">
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