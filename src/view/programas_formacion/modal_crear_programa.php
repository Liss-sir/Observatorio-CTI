<!-- MODAL CREAR PROGRAMA -->
<div id="modal-crear-programa" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-80"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <!-- Ancho responsive: móvil → lg → portátil → xl → escritorio -->
        <div class="bg-white w-full max-w-lg lg:max-w-2xl xl:max-w-3xl rounded-xl shadow-2xl transform transition-all">
            <!-- HEADER -->
            <div class="border-b px-6 py-5 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Nuevo Programa de Formación</h3>
                    <p class="text-sm text-gray-500">Registra un nuevo programa de formación.</p>
                </div>
                <button class="cerrar-modal-crear text-gray-500 text-xl hover:text-black p-1 transition-colors">✕</button>
            </div>

            <!-- BODY -->
            <div class="px-6 py-6">
                <form id="form-crear-programa" class="space-y-4">
                    <!-- AREA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                        <select id="areaPrograma" name="id_area" required
                            class="w-full px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                            <option value="">Cargando áreas...</option>
                        </select>
                    </div>

                    <!-- CODIGO Y CUPOS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Código del Programa</label>
                            <input type="text" id="codigoNuevoPrograma" placeholder="Ej: 2928793"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Cupos</label>
                            <input type="number" id="cuposNuevoPrograma" placeholder="Ej: 30"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                    </div>

                    <!-- NOMBRE -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nombre del Programa</label>
                        <input type="text" id="nombreNuevoPrograma" placeholder="Ej: Desarrollo de Software"
                            class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcionNuevoPrograma" rows="3" placeholder="Breve descripción del programa"
                            class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition resize-none"></textarea>
                    </div>

                    <!-- NIVEL Y MODALIDAD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nivel de Formación</label>
                            <select id="nivelNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                                <option value="">Seleccione nivel</option>
                                <option value="1">Técnico</option>
                                <option value="2">Tecnólogo</option>
                                <option value="3">Operario</option>
                                <option value="4">Auxiliar</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Modalidad</label>
                            <select id="modalidadNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                                <option value="">Seleccione modalidad</option>
                                <option value="Presencial">Presencial</option>
                                <option value="Virtual">Virtual</option>
                            </select>
                        </div>
                    </div>

                    <!-- FECHAS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Fecha de Inicio</label>
                            <input type="date" id="fechaInicioNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Fecha de Fin</label>
                            <input type="date" id="fechaFinNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg bg-gray-50 focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                    </div>
                </form>
            </div>

            <!-- FOOTER -->
            <div class="border-t px-6 py-4 flex justify-end gap-3">
                <button class="cerrar-modal-crear px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-sm font-medium transition-colors">
                    Cancelar
                </button>
                <button id="btn-guardar-nuevo-programa" class="px-4 py-2 bg-[#39A900] text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    Crear Programa
                </button>
            </div>
        </div>
    </div>
</div>