<!-- modal_detalle_programa.php -->
<!-- Modal para ver detalle de programa de formación -->
<div id="modal-detalle-programa" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">
<link rel="stylesheet" href="../../../assets/css/output.css">
<link rel="stylesheet" href="../../assets/css/globals.css">
<!-- Overlay -->
<div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
<div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-3xl transform overflow-hidden rounded-2xl border border-sena-border bg-white shadow-[0_20px_60px_rgba(0,0,0,0.15)] transition-all animate-modalFadeIn">
          <!-- Header -->
          <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4 bg-gradient-to-r from-white to-sena-soft/30">
              <div class="w-12 h-12 rounded-xl bg-sena-soft flex items-center justify-center flex-shrink-0 shadow-inner">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                  </svg>
              </div>

              <div>
                  <h3 class="text-xl font-bold text-sena-text-main" id="detalle-programa-titulo">
                      Detalle del Programa
                  </h3>
                  <p class="text-xs text-sena-text-soft">
                      Información completa del programa de formación
                  </p>
              </div>

              <button class="cerrar-modal-detalle-programa absolute right-4 top-4 text-gray-500 hover:text-gray-700 transition">
                   <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
              </button>
          </div>

          <!-- Content -->
          <div class="px-6 py-6 space-y-5">

              <!-- Código + Estado -->
              <div class="grid grid-cols-2 gap-4">
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs font-medium text-sena-text-soft uppercase">Código</span>
                      <p class="text-sm font-semibold text-sena-text-main mt-1" id="detalle-codigo">codigo</p>
                  </div>

                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs font-medium text-sena-text-soft uppercase">Estado</span>
                      <div class="mt-1">
                          <span id="detalle-estado-badge" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 shadow-sm">
                              <span id="detalle-estado-indicador" class="w-2 h-2 rounded-full bg-gray-500"></span>
                              <span id="detalle-estado">estado</span>
                          </span>
                      </div>
                  </div>
              </div>

              <!-- Nombre -->
              <div class="bg-white border border-sena-border rounded-xl p-4 shadow-sm">
                  <span class="text-xs font-medium text-sena-text-soft uppercase">Nombre del Programa</span>
                  <p class="text-lg text-sena-text-main mt-1 leading-snug" id="detalle-nombre">
                      programa
                  </p>
              </div>

              <!-- Info -->
              <div class="grid grid-cols-3 gap-3 text-center">
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs text-sena-text-soft uppercase">Nivel</span>
                      <p class="text-sm font-medium mt-1" id="detalle-nivel">Tecnólogo</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs text-sena-text-soft uppercase">Modalidad</span>
                      <p class="text-sm font-medium mt-1" id="detalle-modalidad">Presencial</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs text-sena-text-soft uppercase">Cupos</span>
                      <p class="text-sm font-medium mt-1" id="detalle-cupos">30</p>
                  </div>
              </div>

              <div class="grid grid-cols-2 gap-3">
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs text-sena-text-soft uppercase">Área</span>
                      <p class="text-sm mt-1" id="detalle-area">Tecnologías de la Información</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-3">
                      <span class="text-xs text-sena-text-soft uppercase">Duración</span>
                      <div class="mt-1 space-y-1">
                          <p class="text-xs" id="detalle-fecha-inicio">Inicio: ...</p>
                          <p class="text-xs" id="detalle-fecha-fin">Fin: ...</p>
                      </div>
                  </div>
              </div>

              <!-- 🔥 DESCRIPCIÓN DESTACADA -->
              <div>
                  <div class="flex items-center gap-2 mb-2">
                      <!-- ICONO -->
                      <span class="text-sm font-bold text-sena-text-main uppercase tracking-wide">
                          Descripción del Programa
                      </span>
                  </div>

                  <div class="relative bg-gradient-to-br from-sena-soft/40 to-white rounded-xl p-5 border border-sena-border shadow-inner">
                      <div class="absolute top-0 left-0 w-1 h-full bg-sena rounded-l-xl"></div>

                      <p class="text-sm text-sena-text-main leading-relaxed pl-2" id="detalle-descripcion">
                          ...
                      </p>
                  </div>
              </div>

          </div>

          <!-- Footer -->
          <div class="border-t border-sena-border px-6 py-4 flex justify-end bg-gray-50">
              <button class="cerrar-modal-detalle-programa px-5 py-2 text-sm font-semibold text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-all hover:shadow">
                  Cerrar
              </button>
          </div>

      </div>
    </div>
</div>
</div>

<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
.animate-modalFadeIn {
    animation: modalFadeIn 0.3s ease-out;
}

/* =========================
   📱 LAPTOP / PANTALLAS MEDIANAS
   ========================= */
@media (max-width: 1366px) {

    #modal-detalle-programa .max-w-3xl {
        transform: scale(0.95);
    }

    #modal-detalle-programa .px-6 {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
    }

    #modal-detalle-programa .py-6,
    #modal-detalle-programa .py-5 {
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }

    #modal-detalle-programa p {
        font-size: 0.85rem;
    }

    #modal-detalle-programa span {
        font-size: 0.7rem;
    }

    #modal-detalle-programa h3 {
        font-size: 1rem;
    }

    #detalle-descripcion {
        font-size: 0.95rem;
    }
}


/* =========================
   💻 PANTALLAS MÁS PEQUEÑAS
   ========================= */
@media (max-width: 1024px) {

    #modal-detalle-programa .max-w-3xl {
        transform: scale(0.9);
    }

    #modal-detalle-programa .grid {
        gap: 0.5rem !important;
    }

    #modal-detalle-programa .p-6 {
        padding: 1rem !important;
    }

    #modal-detalle-programa .p-5 {
        padding: 0.9rem !important;
    }

    #modal-detalle-programa .p-4 {
        padding: 0.75rem !important;
    }

    #modal-detalle-programa .p-3 {
        padding: 0.6rem !important;
    }

    #modal-detalle-programa h3 {
        font-size: 0.95rem;
    }

    #detalle-descripcion {
        font-size: 0.9rem;
        line-height: 1.5;
    }
}


/* =========================
   📱 MÓVIL
   ========================= */
@media (max-width: 640px) {

    #modal-detalle-programa .max-w-3xl {
        transform: scale(0.85);
    }

    #modal-detalle-programa .grid {
        grid-template-columns: 1fr !important;
    }

    #modal-detalle-programa h3 {
        font-size: 0.9rem;
    }

    #modal-detalle-programa p {
        font-size: 0.8rem;
    }

    #detalle-descripcion {
        font-size: 0.85rem;
    }

}
</style>