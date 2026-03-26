<!-- Modal Editar Perfil -->
<div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Editar perfil</h3>
            <button id="btnCerrarModal" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <p class="text-sm text-gray-500 mb-6">
            Modifica tu información personal y guarda los cambios.
        </p>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre completo
                </label>
                <input 
                    type="text" 
                    id="inputNombre" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sena focus:border-transparent"
                    placeholder="Tu nombre completo"
                >
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Correo electrónico
                </label>
                <input 
                    type="email" 
                    id="inputCorreo" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-sena focus:border-transparent"
                    placeholder="tu@correo.com"
                >
            </div>
        </div>
        
        <div class="flex gap-3 mt-8">
            <button 
                id="btnCancelar" 
                class="flex-1 px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50"
            >
                Cancelar
            </button>
            <button 
                id="btnGuardar" 
                class="flex-1 px-4 py-2 bg-sena text-white rounded-xl hover:bg-sena-strong"
            >
                Guardar cambios
            </button>
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
</script>