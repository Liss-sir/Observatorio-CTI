<div id="modal-crear-area" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl transform transition-all animate-modalFadeIn">
        <!-- HEADER -->
        <div class="flex justify-between items-center border-b px-6 py-4">
          <h3 class="font-semibold">Nueva Área</h3>
          <button class="cerrar-modal-crear text-gray-500">✕</button>
        </div>
        <!-- FORM -->
        <form id="form-nueva-area">
          <div class="p-6 space-y-4">
            <div class="mb-4">
                    <label for="area-nueva" class="block text-sm font-medium text-gray-700 mb-1">Nombre del área</label>
                    <!-- CORRECCIÓN: Agregados id="area-nueva" y name="area" -->
                    <input 
                        type="text" 
                        id="area-nueva" 
                        name="area" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sena/20 focus:border-sena transition-all"
                        placeholder="Ej: Producción, Logística..."
                    >
              </div>
            <div class="mb-6">
                    <label for="descripcion-nueva" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <!-- CORRECCIÓN: Agregados id="descripcion-nueva" y name="descripcion" -->
                    <textarea 
                        id="descripcion-nueva" 
                        name="descripcion" 
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sena/20 focus:border-sena transition-all resize-none"
                        placeholder="Breve descripción de las funciones del área..."
                    ></textarea>
                    <div class="flex justify-end items-center gap-2 mb-4 mt-1">
                       <span id="contador-caracteres-crear" class="text-xs text-sena-text-soft">0 / 30</span>
                      <span id="alerta-minimo-crear" class="text-xs text-red-500 hidden">Mínimo 30 caracteres requerido</span>
                    </div>
                </div>
          </div>
          <!-- FOOTER -->
          <div class="border-t px-6 py-4 flex justify-end gap-3">
            <button type="button" class="cerrar-modal-crear border px-4 py-2 rounded-lg">Cancelar</button>
            <button type="submit" class="bg-sena hover:opacity-90 text-white px-4 py-2 rounded-lg">Crear Área</button>
          </div>
        </form>
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
</style>