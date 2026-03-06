<?php include __DIR__ . '../../../includes/header.php'; ?>

<main class="flex-1">
<link rel="stylesheet" href="../../../assets/css/output.css">
<link rel="stylesheet" href="../../assets/css/globals.css">
<script src="https://unpkg.com/lucide@latest"></script>
<div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">
    <!-- TITULO -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Áreas</h1>
            <p class="text-gray-500 text-sm">
                Gestión de las áreas de los programas de formación.
            </p>
        </div>
        <button id="btn-crear-area" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Crear Área
        </button>
    </div>

    <!-- BUSCADOR -->
    <div class="mb-4">
        <div class="relative w-[420px]">
            <input type="text" placeholder="Buscar áreas..." class="w-full border rounded-lg py-2 px-4 pl-10 focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
        </div>
    </div>

    <p class="text-sm text-gray-500 mb-4">9 áreas encontradas</p>

    <!-- GRID AREAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- AREA 1 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="1">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Producción</h3>
            <p class="text-sm text-gray-500">
                Área encargada de la fabricación y transformación de materias primas en productos terminados.
            </p>
        </div>

        <!-- AREA 2 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="2">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Logística</h3>
            <p class="text-sm text-gray-500">
                Gestión del transporte, almacenamiento y distribución de productos y materiales.
            </p>
        </div>

        <!-- AREA 3 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="3">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Recursos Humanos</h3>
            <p class="text-sm text-gray-500">
                Administración del talento humano, contratación, capacitación y bienestar laboral.
            </p>
        </div>

        <!-- AREA 4 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="4">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Tecnología e Innovación</h3>
            <p class="text-sm text-gray-500">
                Desarrollo e implementación de soluciones tecnológicas para mejorar los procesos empresariales.
            </p>
        </div>

        <!-- AREA 5 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="5">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Ventas y Comercialización</h3>
            <p class="text-sm text-gray-500">
                Estrategias de venta, distribución comercial y relaciones con clientes.
            </p>
        </div>
    </div>
</div>

<!-- ========== MODALES ========== -->

<!-- Modal Crear Área -->
<div id="modal-crear-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button class="cerrar-modal-crear absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <h2 class="text-xl font-bold mb-4">Crear nueva área</h2>
        <form id="form-nueva-area">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del área</label>
                <input type="text" name="area" class="w-full border rounded-lg px-3 py-2 focus:border-sena focus:ring-2 focus:ring-sena/15" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3" class="w-full border rounded-lg px-3 py-2 focus:border-sena focus:ring-2 focus:ring-sena/15" required></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="cerrar-modal-crear px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-sena text-white rounded-lg hover:opacity-90">Crear área</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Editar Área -->
<div id="modal-editar-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button class="cerrar-modal-editar absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <h2 class="text-xl font-bold mb-4">Editar área</h2>
        <form id="form-editar-area">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" id="nombre-area" class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="descripcion-area" rows="3" class="w-full border rounded-lg px-3 py-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select id="estado-area" class="w-full border rounded-lg px-3 py-2">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha creación</label>
                    <input type="date" id="fecha-creacion" class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha expiración</label>
                    <input type="date" id="fecha-expiracion" class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="cerrar-modal-editar px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-sena text-white rounded-lg hover:opacity-90">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detalle Área -->
<div id="modal-detalle-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button class="cerrar-modal-detalle absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <h2 id="detalle-titulo" class="text-xl font-bold mb-4">Detalle del área</h2>
        <div class="space-y-3">
            <p><strong>Nombre:</strong> <span id="detalle-nombre"></span></p>
            <p><strong>Descripción:</strong> <span id="detalle-descripcion"></span></p>
            <p><strong>Estado:</strong> <span id="detalle-estado"></span> <span id="detalle-estado-indicador" class="inline-block w-3 h-3 rounded-full ml-2"></span></p>
        </div>
    </div>
</div>

<!-- Modal Deshabilitar Área -->
<div id="modal-deshabilitar-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button class="cerrar-modal-deshabilitar absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <h2 class="text-lg font-bold mb-2">Deshabilitar área</h2>
            <p class="text-gray-600 mb-4">¿Estás seguro de deshabilitar el área <span class="font-medium"></span>?</p>
            <div class="flex justify-center gap-4">
                <button id="btn-confirmar-deshabilitar" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:opacity-90">Sí, deshabilitar</button>
                <button class="cerrar-modal-deshabilitar px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Habilitar Área -->
<div id="modal-habilitar-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button class="cerrar-modal-habilitar absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <h2 class="text-lg font-bold mb-2">Habilitar área</h2>
            <p class="text-gray-600 mb-4">¿Quieres habilitar el área <span class="font-semibold"></span>?</p>
            <div class="flex justify-center gap-4">
                <button id="btn-confirmar-habilitar" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:opacity-90">Sí, habilitar</button>
                <button class="cerrar-modal-habilitar px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmación Editado -->
<div id="modal-editado-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6 relative">
        <button class="cerrar-modal-editado absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">¡Área actualizada!</h3>
            <p class="text-gray-600 mb-4">El área <span id="nombre-area-editado" class="font-semibold"></span> se ha editado correctamente.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progress-bar-editado" class="bg-sena h-2 rounded-full transition-all duration-3000 ease-linear" style="width:0%"></div>
            </div>
            <p class="text-sm text-gray-500">Cerrando en <span id="contador-segundos-editado">3</span> segundos...</p>
        </div>
    </div>
</div>

<!-- Modal Confirmación Deshabilitado -->
<div id="modal-deshabilitado-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6 relative">
        <button class="cerrar-modal-deshabilitado absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">Área deshabilitada</h3>
            <p class="text-gray-600 mb-4">El área <span id="nombre-area-deshabilitado" class="font-semibold"></span> ahora está inactiva.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progress-bar-deshabilitado" class="bg-sena h-2 rounded-full transition-all duration-3000 ease-linear" style="width:0%"></div>
            </div>
            <p class="text-sm text-gray-500">Cerrando en <span id="contador-segundos-deshabilitado">3</span> segundos...</p>
        </div>
    </div>
</div>

<!-- Modal Confirmación Habilitado -->
<div id="modal-habilitado-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6 relative">
        <button class="cerrar-modal-habilitado absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">Área habilitada</h3>
            <p class="text-gray-600 mb-4">El área <span id="nombre-area-habilitado" class="font-semibold"></span> ya está activa.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progress-bar-habilitado" class="bg-sena h-2 rounded-full transition-all duration-3000 ease-linear" style="width:0%"></div>
            </div>
            <p class="text-sm text-gray-500">Cerrando en <span id="contador-segundos-habilitado">3</span> segundos...</p>
        </div>
    </div>
</div>

<!-- Modal Confirmación Creado -->
<div id="modal-creado-area" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl w-full max-w-sm p-6 relative">
        <button class="cerrar-modal-creado absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        </button>
        <div class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">¡Área creada!</h3>
            <p class="text-gray-600 mb-4">El área <span id="nombre-area-creado" class="font-semibold"></span> se ha creado exitosamente.</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progress-bar-creado" class="bg-sena h-2 rounded-full transition-all duration-3000 ease-linear" style="width:0%"></div>
            </div>
            <p class="text-sm text-gray-500">Cerrando en <span id="contador-segundos-creado">3</span> segundos...</p>
        </div>
    </div>
</div>

</main>

<script>
    lucide.createIcons();
</script>

<!-- Incluir el nuevo script de áreas -->
<script src="/observatorio/Observatorio-CTI/src/assets/js/areas.js"></script>

<?php include __DIR__ . '../../../includes/footer.php'; ?>