<!-- modal_crear_proyeccion_futuro.php -->
<div id="modal-crear-proyeccion-futuro" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all">
        
        <!-- Close button -->
        <button class="cerrar-modal-crear absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-4">
          <h3 class="text-lg font-semibold text-sena-text-main">Nueva Proyección a Futuro</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para crear una nueva proyección a futuro.</p>
        </div>

        <!-- Form -->
        <form id="form-nueva-proyeccion-futuro" class="px-6 py-4">
          <!-- área -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Área</label>
            <div class="select-container">
              <select name="area" class="custom-select">
                <option value="">Selecciona un área</option>
                <option value="Inteligencia Artificial">Inteligencia Artificial</option>
                <option value="Blockchain">Blockchain</option>
                <option value="Internet de las Cosas (IoT)">Internet de las Cosas (IoT)</option>
                <option value="Computación en la Nube">Computación en la Nube</option>
                <option value="Ciberseguridad Avanzada">Ciberseguridad Avanzada</option>
                <option value="Big Data y Analítica">Big Data y Analítica</option>
                <option value="Realidad Aumentada/Virtual">Realidad Aumentada/Virtual</option>
                <option value="Robótica Colaborativa">Robótica Colaborativa</option>
                <option value="Manufactura Aditiva (Impresión 3D)">Manufactura Aditiva (Impresión 3D)</option>
                <option value="Automatización de Procesos (RPA)">Automatización de Procesos (RPA)</option>
              </select>
            </div>
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" rows="3" placeholder="Describe la proyección a futuro..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/20"></textarea>
          </div>

          <!-- Fecha (Años) -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Fecha (Años)</label>
            <div class="select-container">
              <select name="fecha" class="custom-select">
                <option value="">Selecciona el número de años</option>
                <option value="1">1 año</option>
                <option value="2">2 años</option>
                <option value="3">3 años</option>
                <option value="4">4 años</option>
                <option value="5">5 años</option>
                <option value="6">6 años</option>
                <option value="7">7 años</option>
                <option value="8">8 años</option>
                <option value="9">9 años</option>
                <option value="10">10 años</option>
              </select>
            </div>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-crear rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity">
              Crear Proyección a Futuro
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

