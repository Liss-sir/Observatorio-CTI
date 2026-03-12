<!-- modal_detalle_usuario.php -->
<!-- Modal para ver detalle de usuario -->

<div id="modal-detalle-usuario" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">

  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">

      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-2xl transition-all animate-modalFadeIn">

        <!-- HEADER -->
        <div class="border-b border-sena-border px-6 py-5 flex items-center justify-between">

          <h3 class="text-lg font-semibold text-sena-text-main">
            Detalle del usuario
          </h3>

          <button class="cerrar-modal-detalle-usuario text-sena-text-soft hover:text-sena-text-main">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>

        </div>

        <!-- CONTENT -->
        <div class="px-6 py-6">

          <!-- TARJETA USUARIO -->
          <div class="bg-gray-100 rounded-xl p-4 flex items-center gap-4 mb-6">

            <!-- Avatar -->
            <div id="detalle-avatar"
                 class="w-12 h-12 rounded-full bg-sena flex items-center justify-center text-white font-semibold">
                 CM
            </div>

            <!-- Información -->
            <div>
              <p id="detalle-nombre" class="font-semibold text-sena-text-main">
                Carlos Mendoza
              </p>

              <p id="detalle-cargo" class="text-sm text-sena-text-soft">
                Gerente de Talento · TechColombia S.A.S.
              </p>

              <p id="detalle-correo" class="text-sm text-sena-text-soft">
                carlos.mendoza@empresa.co
              </p>
            </div>

          </div>


          <!-- PERFILES -->
          <div class="mb-6">

            <h4 class="font-semibold text-sena-text-main mb-3">
              Perfiles creados (<span id="detalle-total-perfiles">2</span>)
            </h4>

            <div id="detalle-perfiles" class="space-y-3">

              <!-- PERFIL ITEM -->
              <div class="bg-gray-100 rounded-lg p-3 flex justify-between items-center">

                <div>
                  <p class="text-sm font-medium">
                    Desarrollador Full Stack Senior
                  </p>

                  <p class="text-xs text-sena-text-soft">
                    Tecnologías de la Información y las Comunicaciones
                  </p>
                </div>

                <span class="text-xs px-3 py-1 rounded-full bg-green-100 text-green-700">
                  Vigente
                </span>

              </div>

            </div>

            <button id="btn-ver-mas-perfiles"
                    class="text-sm text-sena mt-2 hover:underline">
              Ver más
            </button>

          </div>


          <!-- TECNOLOGÍAS -->
          <div class="mb-6">

            <h4 class="font-semibold text-sena-text-main mb-3">
              Tecnologías Emergentes utilizadas
            </h4>

            <div id="detalle-tecnologias"
                 class="flex flex-wrap gap-2">

              <span class="px-3 py-1 rounded-full bg-sena-soft text-sena text-sm">
                Inteligencia Artificial
              </span>

              <span class="px-3 py-1 rounded-full bg-sena-soft text-sena text-sm">
                Computación en la Nube
              </span>

              <span class="px-3 py-1 rounded-full bg-sena-soft text-sena text-sm">
                Internet de las Cosas (IoT)
              </span>

              <span class="px-3 py-1 rounded-full bg-sena-soft text-sena text-sm">
                Automatización de Procesos (RPA)
              </span>

            </div>

          </div>


          <!-- BOTÓN CERRAR -->
          <button
            class="cerrar-modal-detalle-usuario w-full border border-sena-border rounded-lg py-2 text-sm hover:bg-gray-100 transition">
            Cerrar
          </button>

        </div>

      </div>
    </div>
  </div>
</div>