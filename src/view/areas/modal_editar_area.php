<div id="modal-editar-area" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl">
        <button class="cerrar-modal-editar absolute right-4 top-4 text-sena-text-soft hover:text-sena-text-main">✕</button>
        <div class="border-b px-6 py-4">
          <h3 class="text-lg font-semibold">Editar Área</h3>
          <p class="text-sm text-sena-text-soft">Modifica los datos del área.</p>
        </div>
        <form id="form-editar-area" class="px-6 py-4">
          <div class="mb-4">
            <label class="block text-sm font-medium">Nombre del área</label>
            <input type="text" id="nombre-area" class="w-full border rounded-lg px-3 py-2" required>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium">Descripción</label>
            <textarea id="descripcion-area" rows="4" class="w-full border rounded-lg px-3 py-2" required></textarea>
            <div class="flex justify-between mt-1">
                <span id="alerta-minimo-editar" class="text-xs text-red-500 hidden">Mínimo 30 caracteres</span>
                <span id="contador-caracteres-editar" class="text-xs text-gray-500">0 / 30</span>
            </div>
          </div>
          <div class="flex justify-end gap-3 border-t pt-4">
            <button type="button" class="cerrar-modal-editar border px-4 py-2 rounded-lg">Cancelar</button>
            <button type="submit" class="bg-sena text-white px-4 py-2 rounded-lg">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>