<!-- modal_editar_programa.php -->

<div id="modal-editar-programa" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">

  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">

      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- Header -->
        <div class="border-b border-sena-border px-6 py-5 flex justify-between items-center">

          <div>
            <h3 class="text-lg font-semibold text-sena-text-main">
              Editar Programa de Formación
            </h3>
            <p class="text-sm text-sena-text-soft">
              Modifica los datos del programa.
            </p>
          </div>

          <button class="cerrar-modal-editar text-gray-500 hover:text-gray-700 text-xl">
            ✕
          </button>

        </div>


        <!-- Contenido -->
        <div class="px-6 py-6">

          <form id="form-editar-programa" class="space-y-4">
            
            <!-- CAMPO OCULTO PARA EL ID -->
            <input type="hidden" id="idProgramaEditar">

            <!-- Codigo y cupos -->
            <div class="grid grid-cols-2 gap-4">

              <div>
                <label class="text-sm text-sena-text-main">Codigo del Programa</label>
                <input
                  type="text"
                  id="codigoPrograma"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
              </div>

              <div>
                <label class="text-sm text-sena-text-main">Cupos</label>
                <input
                  type="number"
                  id="cuposPrograma"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
              </div>

            </div>


            <!-- Nombre -->
            <div>
              <label class="text-sm text-sena-text-main">Nombre del Programa</label>
              <input
                type="text"
                id="nombrePrograma"
                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
              >
            </div>


            <!-- Nivel y modalidad -->
            <div class="grid grid-cols-2 gap-4">

              <div>
                <label class="text-sm text-sena-text-main">Nivel de Formación</label>
                <select
                  id="nivelFormacion"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
                  <option value="1">Técnico</option>
                  <option value="2">Tecnólogo</option>
                  <option value="3">Especialización</option>
                  <option value="4">Curso</option>
                </select>
              </div>

              <div>
                <label class="text-sm text-sena-text-main">Modalidad</label>
                <select
                  id="modalidadPrograma"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
                  <option value="PRESENCIAL">Presencial</option>
                  <option value="VIRTUAL">Virtual</option>
                  <option value="MIXTA">Mixta</option>
                </select>
              </div>

            </div>
            
            <!-- ÁREA - FALTABA ESTE CAMPO -->
            <div>
              <label class="text-sm text-sena-text-main">Área</label>
              <select
                id="areaProgramaEditar"
                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
              >
                <option value="">Seleccione un área</option>
              </select>
            </div>


            <!-- Fechas -->
            <div class="grid grid-cols-2 gap-4">

              <div>
                <label class="text-sm text-sena-text-main">Fecha de Inicio</label>
                <input
                  type="date"
                  id="fechaInicio"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
              </div>

              <div>
                <label class="text-sm text-sena-text-main">Fecha de Fin</label>
                <input
                  type="date"
                  id="fechaFin"
                  class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-100 text-sm"
                >
              </div>

            </div>

          </form>

        </div>


        <!-- Footer -->
        <div class="border-t border-sena-border px-6 py-4 flex justify-end gap-3">

          <button
            type="button"
            class="cerrar-modal-editar px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-colors"
          >
            Cancelar
          </button>

          <button
            type="button"
            id="btn-guardar-programa-editado"
            class="px-4 py-2 text-sm font-medium text-white bg-[#39A900] rounded-lg hover:bg-green-700 transition-colors"
          >
            Guardar Cambios
          </button>

        </div>

      </div>
    </div>
  </div>
</div>