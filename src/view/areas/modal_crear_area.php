<div id="modal-crear-arearr" class="hidden fixed inset-0 z-50">
<link rel="stylesheet" href="../../../assets/css/output.css">
<link rel="stylesheet" href="../../assets/css/globals.css">
  <!-- Overlay (fondo oscuro) -->
  <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity"></div>
  
  <!-- Contenedor del modal centrado -->
  <div class="fixed inset-0 flex items-center justify-center">
    <div class="bg-white w-full max-w-md rounded-xl shadow-xl">
      <!-- HEADER -->
      <div class="flex justify-between items-center border-b px-6 py-4">
        <h3 class="font-semibold">Nueva Área</h3>
        <button class="cerrar-modal-area text-gray-500">✕</button>
      </div>

      <!-- BODY -->
      <div class="p-6 space-y-4">
        <div>
          <label class="text-sm">Nombre del área</label>
          <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100">
        </div>
        <div>
          <label class="text-sm">Descripción</label>
          <textarea class="w-full border rounded-lg px-3 py-2 bg-gray-100"></textarea>
        </div>
      </div>

      <!-- FOOTER -->
      <div class="border-t px-6 py-4 flex justify-end gap-3">
        <button class="cerrar-modal-area border px-4 py-2 rounded-lg">Cancelar</button>
        <button class="bg-sena text-white px-4 py-2 rounded-lg">Crear Área</button>
      </div>
    </div>
  </div>
</div>