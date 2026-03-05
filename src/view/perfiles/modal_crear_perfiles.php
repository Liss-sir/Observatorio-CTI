<!-- modal_nuevo_perfil.php -->
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
          <h3 class="text-lg font-semibold text-sena-text-main">Nuevo Perfil</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Completa los datos para crear un nuevo perfil de trabajo.</p>
        </div>

        <!-- Form -->
        <form id="form-nuevo-perfil" class="px-6 py-4">
          <!-- Nombre -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre</label>
            <input type="text" name="nombre"  placeholder="Ej: Desarrollador Full Stack"
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/20">
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" rows="3"  placeholder="Describe las responsabilidades y requisitos del perfil..."
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main placeholder-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/20"></textarea>
          </div>

          <!-- Fechas -->
          <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Fecha Creación</label>
              <input type="date" name="fechaCreacion"  id="fecha-creacion-nuevo"
                     class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Fecha Expiración</label>
              <input type="date" name="fechaExpiracion" 
                     class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
            </div>
          </div>

          <!-- Estado y Línea Tecnológica -->
          <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Estado</label>
              <select name="estado" 
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
                <option value="vigente" selected>Vigente</option>
                <option value="expirado">Expirado</option>
              </select>
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Línea Tecnológica</label>
              <select name="lineaTecnologica" 
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
                <option value="" disabled selected>Seleccionar línea...</option>
                <option value="1">Tecnologias de la Informacion y las Comunicaciones</option>
                <option value="2">Biotecnologia y Nanotecnologia</option>
                <option value="3">Diseno e Innovacion</option>
                <option value="4">Produccion y Transformacion</option>
                <option value="5">Materiales e Industria</option>
                <option value="6">Electronica y Automatizacion</option>
                <option value="7">Servicios y Gestion Empresarial</option>
              </select>
            </div>
          </div>

          <!-- Empresa -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Empresa</label>
            <select name="empresa" 
                    class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
              <option value="" disabled selected>Seleccionar empresa...</option>
              <option value="1">BioTech Andina</option>
              <option value="2">Tech Solutions</option>
              <option value="3">InnovaTech</option>
              <option value="4">CloudNet Colombia</option>
              <option value="5">DataSoft</option>
            </select>
          </div>

          <!-- Tecnologías Emergentes -->
          <div class="mb-6">
            <span class="mb-2 block text-sm font-medium text-sena-text-main">Tecnologías Emergentes</span>
            <div class="flex flex-wrap gap-2">
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="1">Inteligencia Artificial</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="2">Blockchain</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="3">Internet de las Cosas (IoT)</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="4">Computacion en la Nube</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="5">Ciberseguridad Avanzada</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="6">Big Data y Analitica</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="7">Realidad Aumentada/Virtual</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="8">Robotica Colaborativa</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="9">Manufactura Aditiva (Impresion 3D)</button>
              <button type="button" class="chip-nuevo rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="10">Automatizacion de Procesos (RPA)</button>
            </div>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal-crear rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:bg-sena-dark">
              Crear Perfil
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>