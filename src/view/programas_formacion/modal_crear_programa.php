<!-- MODAL CREAR PROGRAMA -->
<div id="modal-crear-programa" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-80"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl">
                <!-- HEADER -->
                <div class="border-b px-6 py-5 flex justify-between items-center">

                    <div>
                        <h3 class="text-lg font-semibold">Nuevo Programa de Formación</h3>
                            <p class="text-sm text-gray-500">
                            Registra un nuevo programa de formación.
                            </p>
                    </div>

                    <button class="cerrar-modal-crear text-gray-500 text-xl">✕</button>

                </div>

                <!-- BODY -->
                <div class="px-6 py-6">
                <form id="form-crear-programa" class="space-y-4">

                <!-- AREA -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                    <select id="areaPrograma" name="id_area" required 
                        class="w-full px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        <option value="">Cargando áreas...</option>
                    </select>
                </div>

                <!-- CODIGO Y CUPOS -->
                <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-sm">Código del Programa</label>
                        <input
                        type="text"
                        id="codigoNuevoPrograma"
                        placeholder="Ej: PF-007"
                        class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
                </div>

                <div>
                    <label class="text-sm">Cupos</label>
                        <input
                        type="number"
                        id="cuposNuevoPrograma"
                        placeholder="Ej: 30"
                        class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
                </div>

                </div>

                <!-- NOMBRE -->
                <div>
                    <label class="text-sm">Nombre del Programa</label>
                    <input
                    type="text"
                    id="nombreNuevoPrograma"
                    placeholder="Ej: Desarrollo de Software"
                    class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
                </div>

                <!-- DESCRIPCIÓN DEL PROGRAMA -->
                <div>
                    <label class="text-sm">Descripción</label>
                    <textarea
                        id="descripcionNuevoPrograma"
                        rows="3"
                        placeholder="Breve descripción del programa"
                        class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100"
                    ></textarea>
                </div>

                <!-- NIVEL Y MODALIDAD -->
                <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-sm">Nivel de Formación</label>
                    <select id="nivelNuevoPrograma" class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
                        <option value="">Seleccione nivel</option>
                        <option value="1">Técnico</option>
                        <option value="2">Tecnólogo</option>
                        <option value="3">Especialización</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm">Modalidad</label>
                    <select id="modalidadNuevoPrograma" class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
                        <option value="">Seleccione modalidad</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                        <option value="Mixta">Mixta</option>
                    </select>
                </div>

            </div>

            <!-- FECHAS -->
            <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="text-sm">Fecha de Inicio</label>
                <input
                type="date"
                id="fechaInicioNuevoPrograma"
                class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
            </div>

            <div>
                <label class="text-sm">Fecha de Fin</label>
                <input
                type="date"
                id="fechaFinNuevoPrograma"
                class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100">
            </div>
            </div>
            </form>
        </div>

        <!-- FOOTER -->
        <div class="border-t px-6 py-4 flex justify-end gap-3">

        <button
            class="cerrar-modal-crear px-4 py-2 border rounded-lg">
            Cancelar
        </button>

        <button
            id="btn-guardar-nuevo-programa"
            class="px-4 py-2 bg-[#39A900] text-white rounded-lg">
            Crear Programa
        </button>
        
        </div>
        </div>
    </div>
</div>