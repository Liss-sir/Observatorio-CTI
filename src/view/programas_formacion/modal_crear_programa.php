<!-- MODAL CREAR PROGRAMA -->
<div id="modal-crear-programa" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-80"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <!-- Ancho responsive: móvil → lg → portátil → xl → escritorio -->
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl transform transition-all animate-modalFadeIn">
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
                            class="w-full px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                            <option value="">Cargando áreas...</option>
                        </select>
                    </div>

                    <!-- CODIGO Y CUPOS -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Código del Programa</label>
                            <input type="text" id="codigoNuevoPrograma" placeholder="Ej: 2928793"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Cupos</label>
                            <input type="number" id="cuposNuevoPrograma" placeholder="Ej: 30"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                    </div>

                    <!-- NOMBRE -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nombre del Programa</label>
                        <input type="text" id="nombreNuevoPrograma" placeholder="Ej: Desarrollo de Software"
                            class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcionNuevoPrograma" rows="3" placeholder="Breve descripción del programa"
                            class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition resize-none"></textarea>
                        <div class="flex justify-end items-center gap-2 mb-4 mt-1">
                            <span id="contador-caracteres-crear" class="text-xs text-sena-text-soft ml-auto">0 / 30 caracteres</span>
                            <span id="alerta-minimo-crear" class="text-xs text-red-500 hidden">Mínimo 30 caracteres requerido</span>
                        </div>
                    </div>

                    <!-- NIVEL Y MODALIDAD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Nivel de Formación</label>
                            <select id="nivelNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
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
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
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
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Fecha de Fin</label>
                            <input type="date" id="fechaFinNuevoPrograma"
                                class="w-full mt-1 px-3 py-2 border border-sena-border rounded-lg focus:ring-2 focus:ring-sena/20 focus:border-sena outline-none transition">
                        </div>
                    </div>
                </form>
            </div>

            <!-- FOOTER -->
            <div class="border-t px-6 py-4 flex justify-end gap-3">
                <button class="cerrar-modal-crear px-4 py-2 border border-gray-300 rounded-lg hover:bg-sena-soft text-sm font-medium transition-colors">
                    Cancelar
                </button>
                <button id="btn-guardar-nuevo-programa" class="px-4 py-2 bg-sena text-white rounded-lg hover:opacity-90 transition text-sm font-medium">
                    Crear Programa
                </button>
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


/* 💻 PORTÁTILES */

#modal-crear-programa > div > div {
    max-height: 90vh;
    overflow-y: auto;
}

@media (max-width: 1366px) {

    #modal-crear-programa label {
        font-size: 0.8rem;
    }

    #modal-crear-programa input,
    #modal-crear-programa select,
    #modal-crear-programa textarea {
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    #modal-crear-programa .px-6 {
        padding-left: 1.2rem;
        padding-right: 1.2rem;
    }

    #modal-crear-programa .py-6 {
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }

    #modal-crear-programa h3 {
        font-size: 1rem;
    }
}


/* 📱 TABLET */
@media (max-width: 1024px) {

    #modal-crear-programa .grid {
        gap: 0.6rem;
    }

    #modal-crear-programa input,
    #modal-crear-programa select,
    #modal-crear-programa textarea {
        font-size: 0.8rem;
        padding: 6px 8px;
    }

    #modal-crear-programa h3 {
        font-size: 0.95rem;
    }
}


/* 📱 MÓVIL */
@media (max-width: 640px) {

    #modal-crear-programa .grid {
        grid-template-columns: 1fr !important;
    }

    #modal-crear-programa {
        padding: 0.5rem;
    }

    #modal-crear-programa h3 {
        font-size: 0.9rem;
    }

    #modal-crear-programa label {
        font-size: 0.75rem;
    }
}

</style>