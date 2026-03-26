<!-- modal_registro_exitoso.php -->
<div id="modal-registro-exitoso" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-hidden="true">

  <!-- Overlay -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity modal-backdrop opacity-0 transition-all duration-300"></div>

  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">

      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-[#39A900]/30 bg-white shadow-2xl transition-all modal-panel opacity-0 scale-95 duration-300">

        <!-- Header con icono de éxito -->
        <div class="bg-gradient-to-r from-[#39A900]/10 to-[#39A900]/5 border-b border-[#39A900]/20 px-6 py-6 text-center">

          <!-- Icono circular de éxito -->
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#39A900]/20 mb-4">
            <svg class="h-8 w-8 text-[#39A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>

          <h3 class="text-xl font-semibold text-gray-900">
            ¡Cuenta Creada Exitosamente!
          </h3>
          
        </div>

        <!-- Contenido -->
        <div class="px-6 py-6">

          <!-- Mensaje principal -->
          <p class="text-center text-gray-600 text-base leading-relaxed mb-4">
            Tu cuenta ha sido registrada correctamente en el sistema.
          </p>

          <!-- Alerta importante: Verificación de correo -->
          <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
            <div class="flex items-start gap-3">
              
              <!-- Icono de correo -->
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
              </div>

              <div>
                <h4 class="text-sm font-semibold text-amber-800 mb-1">
                  Verificación Requerida
                </h4>
                <p class="text-sm text-amber-700 leading-relaxed">
                  Hemos enviado un correo de verificación a tu dirección de email. 
                  <strong>Por favor revisa tu bandeja de entrada</strong> (y carpeta de spam) 
                  para activar tu cuenta antes de iniciar sesión.
                </p>
              </div>

            </div>
          </div>

          <!-- Nota adicional -->
          <p class="text-xs text-gray-500 text-center">
            Si no recibes el correo en los próximos minutos, puedes solicitar uno nuevo desde la página de inicio de sesión.
          </p>

        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-6 py-4 flex justify-center">

          <a
            href="../../auth/login/login.php"
            id="btn-ir-login"
            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-[#39A900] rounded-lg hover:bg-[#2d8a00] transition-colors shadow-lg shadow-[#39A900]/30 focus:outline-none focus:ring-2 focus:ring-[#39A900] focus:ring-offset-2"
          >
            <span>Ir al inicio de sesión</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
          </a>

        </div>

      </div>
    </div>
  </div>
</div>

<style>
  /* Estados del modal */
  #modal-registro-exitoso.show .modal-backdrop {
    opacity: 1;
  }
  #modal-registro-exitoso.show .modal-panel {
    opacity: 1;
    transform: scale(1);
  }
  
  /* Animación de entrada */
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
    animation: modalFadeIn 0.3s ease-out forwards;
  }
</style>

<script>
(function() {
  const modal = document.getElementById('modal-registro-exitoso');
  const backdrop = modal.querySelector('.modal-backdrop');
  
  // Función global para mostrar el modal
  window.mostrarModalRegistroExitoso = function() {
    modal.classList.remove('hidden');
    // Forzar reflow
    void modal.offsetWidth;
    modal.classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevenir scroll
  };
  
  // Función para ocultar el modal
  window.ocultarModalRegistroExitoso = function() {
    modal.classList.remove('show');
    setTimeout(() => {
      modal.classList.add('hidden');
      document.body.style.overflow = '';
    }, 300);
  };
  
  // Cerrar al hacer clic en el backdrop
  backdrop.addEventListener('click', function() {
    window.ocultarModalRegistroExitoso();
  });
  
  // Cerrar con tecla ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
      window.ocultarModalRegistroExitoso();
    }
  });
})();
</script>