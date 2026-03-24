<div id="modalEditar" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-[999]">

    <div class="bg-white rounded-2xl w-full max-w-md p-6">

        <!-- HEADER -->
        <div class="flex items-center gap-3 mb-5">
            <i data-lucide="pencil" class="w-5 h-5 text-green-700"></i>
            <div>
                <h4 class="font-semibold text-sm">Editar perfil</h4>
                <p class="text-xs text-gray-500">
                    Modifica tu información personal y guarda los cambios.
                </p>
            </div>
        </div>

        <!-- FORM -->
        <div class="space-y-4">

            <!-- NOMBRE -->
            <div>
                <label class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                    <i data-lucide="user" class="w-4 h-4 text-green-600"></i>
                    Nombre completo
                </label>
                <input id="inputNombre"
                       type="text"
                       value="<?= $usuario['nombre'] ?>"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
            </div>

            <!-- CORREO -->
            <div>
                <label class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                    <i data-lucide="mail" class="w-4 h-4 text-green-600"></i>
                    Correo electrónico
                </label>
                <input id="inputCorreo"
                       type="email"
                       value="<?= $usuario['correo'] ?>"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 outline-none">
            </div>

        </div>

        <!-- BOTONES -->
        <div class="flex justify-end gap-3 mt-6">
            <button id="btnCancelar"
                    class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
                Cancelar
            </button>

            <button id="btnGuardar"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Guardar cambios
            </button>
        </div>

    </div>

</div>