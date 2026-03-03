<!-- modal_editar_tendencias.php -->
<div id="modal-editar-tendencia" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <!-- Modal panel -->
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transition-all">
        
        <!-- Close button -->
        <button class="cerrar-modal-editar absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-4">
          <h3 class="text-lg font-semibold text-sena-text-main">Editar Tendencia</h3>
          <p class="mt-1 text-sm text-sena-text-soft">Modifica los datos de la tendencia tecnológica.</p>
        </div>

        <!-- Form -->
        <form id="form-editar-tendencia" class="px-6 py-4">
          <!-- Nombre -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre del área</label>
            <input type="text" name="nombre" id="nombre-tendencia" value="Inteligencia Artificial" required
                   class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label class="mb-1 block text-sm font-medium text-sena-text-main">Descripción</label>
            <textarea name="descripcion" id="descripcion-tendencia" rows="4" required
                      class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20">Computación cuántica accesible desde la nube para desarrolladores y empresas, permitiendo experimentación real en problemas de optimización y criptografía.</textarea>
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

<!-- Incluir este script al final del body o en un archivo separado -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Variables para el modal
    const modalEditar = document.getElementById('modal-editar-tendencia');
    const cerrarModalBtns = document.querySelectorAll('.cerrar-modal-editar');
    
    // Función para abrir modal
    function abrirModalEditar() {
      if (modalEditar) {
        modalEditar.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      }
    }
    
    // Función para cerrar modal
    function cerrarModalEditar() {
      if (modalEditar) {
        modalEditar.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    }
    
    // Asignar evento a los botones de editar en las tarjetas
    document.querySelectorAll('button[title="Editar tendencia"]').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Obtener datos de la tarjeta
        const card = this.closest('.border');
        const nombreTendencia = card.querySelector('h3').textContent;
        const descripcionTendencia = card.querySelector('p.text-xs').textContent;
        
        // Obtener el estado del switch
        const switchEl = card.querySelector('.switch-sena');
        const estadoTendencia = switchEl.classList.contains('active') ? 'activo' : 'inactivo';
        
        // Llenar el formulario con los datos
        document.getElementById('nombre-tendencia').value = nombreTendencia;
        document.getElementById('descripcion-tendencia').value = descripcionTendencia;
        
        // Abrir modal
        abrirModalEditar();
      });
    });
    
    // Cerrar modal con botones
    cerrarModalBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModalEditar();
      });
    });
    
    // Cerrar modal haciendo clic en overlay
    if (modalEditar) {
      modalEditar.addEventListener('click', function(e) {
        if (e.target === modalEditar || e.target.classList.contains('fixed')) {
          cerrarModalEditar();
        }
      });
    }
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modalEditar && !modalEditar.classList.contains('hidden')) {
        cerrarModalEditar();
      }
    });
    
    // Manejar envío del formulario
    const formEditar = document.getElementById('form-editar-tendencia');
    if (formEditar) {
      formEditar.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Recopilar datos del formulario
        const data = {
          nombre: document.getElementById('nombre-tendencia').value,
          descripcion: document.getElementById('descripcion-tendencia').value,
          fechaCreacion: document.getElementById('fecha-creacion').value,
          fechaExpiracion: document.getElementById('fecha-expiracion').value,
          estado: document.getElementById('estado-tendencia').value,
          lineaTecnologica: document.getElementById('linea-tecnologica').value
        };
        
        console.log('Datos de tendencia actualizados:', data);
        
        // Actualizar la tarjeta correspondiente (opcional)
        // Buscar la tarjeta que estaba siendo editada
        const nombreAnterior = data.nombre;
        const tarjetas = document.querySelectorAll('.border h3');
        tarjetas.forEach(h3 => {
          if (h3.textContent === nombreAnterior) {
            const card = h3.closest('.border');
            card.querySelector('h3').textContent = data.nombre;
            card.querySelector('p.text-xs').textContent = data.descripcion;
            
            // Actualizar estado del switch
            const switchEl = card.querySelector('.switch-sena');
            if (data.estado === 'activo') {
              switchEl.classList.add('active');
              switchEl.setAttribute('title', 'Activo');
            } else {
              switchEl.classList.remove('active');
              switchEl.setAttribute('title', 'Inactivo');
            }
          }
        });
        
        // Cerrar modal
        cerrarModalEditar();
        
        // Mostrar mensaje de éxito (opcional)
        alert('Tendencia actualizada correctamente');
      });
    }
  });
</script>