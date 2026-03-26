<!-- Modal Editar Perfil -->
<div id="modalEditar" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-hidden="true">
    <!-- Overlay SEPARADO (fondo negro) -->
    <div class="fixed inset-0 bg-black bg-opacity-30 transition-opacity"></div>
    
    <!-- Contenedor del contenido -->
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <!-- Contenido del modal -->
            <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6 px-8 pt-8">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Editar perfil</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Modifica tu información personal y guarda los cambios.
                        </p>
                    </div>
                    <button id="btnCerrarModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="px-8 pb-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                Nombre completo
                            </label>
                            <input
                                type="text"
                                id="inputNombre"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sena focus:border-transparent transition-all"
                                placeholder="Tu nombre completo"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                id="inputCorreo"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sena focus:border-transparent transition-all"
                                placeholder="tu@correo.com"
                            >
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="flex gap-3 mt-8">
                        <button
                            id="btnCancelar"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors font-medium"
                        >
                            Cancelar
                        </button>
                        <button
                            id="btnGuardar"
                            class="flex-1 px-4 py-2 bg-sena text-white rounded-xl hover:bg-sena-strong transition-colors font-medium"
                        >
                            Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cerrar modal con botón X
document.getElementById('btnCerrarModal')?.addEventListener('click', () => {
    const modal = document.getElementById('modalEditar');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
});

// Inicializar iconos de Lucide
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>