<div id="modal-deshabilitar-area" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
  <div class="fixed inset-0 overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative w-full max-w-md transform overflow-hidden rounded-xl border border-sena-border bg-white shadow-xl animate-modalFadeIn">
        <button class="cerrar-modal-deshabilitar absolute right-4 top-4 text-sena-text-soft">✕</button>
        <div class="border-b px-6 py-4">
          <h3 class="text-lg font-semibold">Deshabilitar Área</h3>
        </div>
        <div class="px-6 py-4">
          <p class="text-sm">
            ¿Estás seguro de deshabilitar el area <span class="font-bold text-[#e65100]">"Nombre del área"</span>? El registro no se eliminará, solo se marcará como inactivo.
          </p>
        </div>
        <div class="flex justify-end gap-3 border-t px-6 py-4">
          <button type="button" class="cerrar-modal-deshabilitar border px-4 py-2 rounded-lg">Cancelar</button>
          <button type="button" id="btn-confirmar-deshabilitar" class="bg-[#e65100] text-white px-4 py-2 rounded-lg">Desactivar</button>
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
</style>