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
          <h3 class="text-lg font-semibold text-sena-text-main">Editar Perfil</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Modifica los datos del perfil de trabajo.</p>
        </div>

        <!-- Form -->
        <form id="profileForm" class="px-6 py-4">
          <!-- Nombre -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre</label>
            <input type="text" name="nombre" value="Desarrollador Full Stack Senior" required
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" rows="4" required
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas utilizando frameworks modernos de frontend y backend. Debe tener conocimiento en arquitecturas de microservicios, bases de datos relacionales y no relacionales, y metodologias agiles de desarrollo.</textarea>
          </div>

          <!-- Fechas -->
          <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Fecha Creación</label>
              <input type="date" name="fechaCreacion" value="2025-11-15" required
                     class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Fecha Expiración</label>
              <input type="date" name="fechaExpiracion" value="2026-05-15" required
                     class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
            </div>
          </div>

          <!-- Estado y Línea Tecnológica -->
          <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Estado</label>
              <div class="select-container">
                <select name="estado" class="custom-select">
                  <option value="vigente" selected>Vigente</option>
                  <option value="expirado">Expirado</option>
                </select>
              </div>
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-sena-text-main">Línea Tecnológica</label>
              <div class="select-container">
                <select name="lineaTecnologica" class="custom-select">
                  <option value="" disabled>Seleccionar línea...</option>
                  <option value="1" selected>Tecnologias de la Informacion y las Comunicaciones</option>
                  <option value="2">Biotecnologia y Nanotecnologia</option>
                  <option value="3">Diseno e Innovacion</option>
                  <option value="4">Produccion y Transformacion</option>
                  <option value="5">Materiales e Industria</option>
                  <option value="6">Electronica y Automatizacion</option>
                  <option value="7">Servicios y Gestion Empresarial</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Empresa -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Empresa</label>
            <div class="select-container">
              <select name="empresa" class="custom-select">
                <option value="" disabled>Seleccionar empresa...</option>
                <option value="1">BioTech Andina</option>
                <option value="2" selected>Tech Solutions</option>
                <option value="3">InnovaTech</option>
                <option value="4">CloudNet Colombia</option>
                <option value="5">DataSoft</option>
              </select>
            </div>
          </div>

          <!-- Tecnologías Emergentes -->
          <div class="mb-6">
            <span class="mb-2 block text-sm font-medium text-sena-text-main">Tecnologías Emergentes</span>
            <div class="flex flex-wrap gap-2">
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="1">Inteligencia Artificial</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="2">Blockchain</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="3">Internet de las Cosas (IoT)</button>
              <button type="button" class="chip selected rounded-full border-sena bg-sena px-3 py-1 text-sm text-white" data-value="4">Computacion en la Nube</button>
              <button type="button" class="chip selected rounded-full border-sena bg-sena px-3 py-1 text-sm text-white" data-value="5">Ciberseguridad Avanzada</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="6">Big Data y Analitica</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="7">Realidad Aumentada/Virtual</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="8">Robotica Colaborativa</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="9">Manufactura Aditiva (Impresion 3D)</button>
              <button type="button" class="chip rounded-full border border-sena-border px-3 py-1 text-sm text-sena-text-main hover:bg-sena-soft" data-value="10">Automatizacion de Procesos (RPA)</button>
            </div>
          </div>

          <!-- Footer buttons -->
          <div class="flex justify-end gap-3 border-t border-sena-border pt-4">
            <button type="button" class="cerrar-modal rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">
              Cancelar
            </button>
            <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:bg-sena-dark">
              Guardar Cambios
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Toggle de chips
  document.querySelectorAll('.chip').forEach(function(chip) {
    chip.addEventListener('click', function() {
      this.classList.toggle('selected');
      this.classList.toggle('border-sena');
      this.classList.toggle('bg-sena');
      this.classList.toggle('text-white');
      this.classList.toggle('border-sena-border');
      this.classList.toggle('bg-transparent');
      this.classList.toggle('text-sena-text-main');
    });
  });

  // Submit del formulario
  document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var selectedChips = [];
    document.querySelectorAll('.chip.selected').forEach(function(chip) {
      selectedChips.push(chip.getAttribute('data-value'));
    });

    var data = {
      nombre: document.querySelector('input[name="nombre"]').value,
      descripcion: document.querySelector('textarea[name="descripcion"]').value,
      fechaCreacion: document.querySelector('input[name="fechaCreacion"]').value,
      fechaExpiracion: document.querySelector('input[name="fechaExpiracion"]').value,
      estado: document.querySelector('select[name="estado"]').value,
      lineaTecnologica: document.querySelector('select[name="lineaTecnologica"]').value,
      tecnologiasEmergentes: selectedChips,
      empresa: document.querySelector('select[name="empresa"]').value
    };

    
    // Cerrar modal después de guardar
    document.getElementById('modal-editar-perfil').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  });
</script>