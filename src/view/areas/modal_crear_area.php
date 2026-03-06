<div id="modal-crear-area" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-2xl transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl">
        <!-- HEADER -->
        <div class="flex justify-between items-center border-b px-6 py-4">
          <h3 class="font-semibold">Nueva Área</h3>
          <button class="cerrar-modal-crear text-gray-500">✕</button>
        </div>
        <!-- FORM -->
        <form id="form-nueva-area">
          <div class="p-6 space-y-4">
            <div>
              <label class="text-sm">Nombre del área</label>
              <input type="text" name="area" class="w-full border rounded-lg px-3 py-2 bg-gray-100" required>
            </div>
            <div>
              <label class="text-sm">Descripción</label>
              <textarea name="descripcion" class="w-full border rounded-lg px-3 py-2 bg-gray-100" required></textarea>
            </div>
          </div>
          <!-- FOOTER -->
          <div class="border-t px-6 py-4 flex justify-end gap-3">
            <button type="button" class="cerrar-modal-crear border px-4 py-2 rounded-lg">Cancelar</button>
            <button type="submit" class="bg-sena text-white px-4 py-2 rounded-lg">Crear Área</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>