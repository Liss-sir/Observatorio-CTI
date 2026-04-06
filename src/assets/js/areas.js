document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    const API_URL = '../../controllers/AreaController.php';
    const MIN_DESCRIPCION_LENGTH = 30;

    // ===== VARIABLES DE MODALES =====
    const modalEditar = document.getElementById('modal-editar-area');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-area');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-area');
    const modalDeshabilitadoConfirmacion = document.getElementById('modal-deshabilitado-area');
    const modalHabilitar = document.getElementById('modal-habilitar-area');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-area');
    const modalCrear = document.getElementById('modal-crear-area');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-area');
    const modalDetalle = document.getElementById('modal-detalle-area');

    const cerrarModalBtns = document.querySelectorAll('.cerrar-modal-editar');
    const cerrarModalEditadoBtns = document.querySelectorAll('.cerrar-modal-editado');
    const cerrarModalDeshabilitarBtns = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const cerrarModalDeshabilitadoBtns = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const cerrarModalHabilitarBtns = document.querySelectorAll('.cerrar-modal-habilitar');
    const cerrarModalHabilitadoBtns = document.querySelectorAll('.cerrar-modal-habilitado');
    const cerrarModalCrearBtns = document.querySelectorAll('.cerrar-modal-crear');
    const cerrarModalCreadoBtns = document.querySelectorAll('.cerrar-modal-creado');
    const cerrarModalDetalleBtns = document.querySelectorAll('.cerrar-modal-detalle');

    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnCrearArea = document.getElementById('btn-crear-area');
    const gridAreas = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.gap-6');
    const contadorTexto = document.getElementById('contador-areas');

    let areasData = [];
    let timeoutEditado = null, intervalContadorEditado = null;
    let timeoutDeshabilitado = null, intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null, intervalContadorHabilitado = null;
    let timeoutCreado = null, intervalContadorCreado = null;

    // ===== 1. CARGAR DATOS =====
    async function cargarAreas() {
        if (!gridAreas) return;
        gridAreas.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">Cargando áreas...</div>';

        try {
            const response = await fetch(`${API_URL}?accion=listarTodas`);
            if (!response.ok) throw new Error('Error de red');

            const result = await response.json();

            if (result.status === 'success' && result.data) {
                areasData = result.data;
                renderizarAreas(areasData);
                if (contadorTexto) contadorTexto.textContent = `${areasData.length} áreas encontradas`;
            } else {
                gridAreas.innerHTML = '<div class="col-span-full text-center text-red-500">No se encontraron áreas.</div>';
            }
        } catch (error) {
            console.error('Error fetching areas:', error);
            gridAreas.innerHTML = '<div class="col-span-full text-center text-red-500">Error de conexión.</div>';
        }
    }

    // ===== 2. RENDERIZAR HTML CON ESTADOS VACÍOS =====
    function renderizarAreas(areas) {
        if (!gridAreas) return;
        gridAreas.innerHTML = '';

        if (areas.length === 0) {
            // Buscador activo?
            const buscador = document.getElementById("buscador-areas");
            const hayBusqueda = buscador && buscador.value.trim().length > 0;

            if (hayBusqueda) {
                // ✅ Mensaje: No se encontraron resultados
                gridAreas.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-10 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-gray-500 text-center max-w-sm">No hay áreas que coincidan con tu búsqueda.</p>
                    </div>
                `;
            } else {
                // ✅ Mensaje: No hay registros
                gridAreas.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-10 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay áreas registradas</h3>
                        <p class="text-sm text-gray-500 text-center max-w-sm mb-6">Comienza creando tu primera área.</p>
                        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-[#39A900] rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear primera área
                        </button>
                    </div>
                `;
                
                // Evento para el botón de crear desde estado vacío
                const btnEmpty = document.getElementById('btn-crear-desde-empty');
                if (btnEmpty) btnEmpty.addEventListener('click', () => {
                    modalCrear.classList.remove('hidden');
                });
            }
            return;
        }

        areas.forEach(area => {
            const isActive = area.estado == 1;
            const estadoClass = isActive ? 'active' : '';
            const estadoTitle = isActive ? 'Activo' : 'Inactivo';
            const colorIcono = isActive ? 'text-green-600' : 'text-gray-400';
            const desc = (area.descripcion_area && area.descripcion_area.trim() !== '') ? area.descripcion_area : 'Sin descripción';

            const cardHTML = `
                <div class="border rounded-xl p-5 bg-white shadow-sm cursor-pointer hover:shadow-md transition-shadow" data-id="${area.id_area}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="bg-green-50 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ${colorIcono}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <div class="flex items-center gap-1">
                            <button class="btn-editar-area p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-[#39A900] transition-colors z-20 relative" title="Editar área">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                </svg>
                            </button>
                            <div class="switch-sena ${estadoClass} z-20 relative" title="${estadoTitle}"></div>
                        </div>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">${area.nombre_area}</h3>
                    <p class="text-xs text-gray-500 line-clamp-2 h-8">${desc}</p>
                </div>
            `;
            gridAreas.insertAdjacentHTML('beforeend', cardHTML);
        });

        asignarEventosDinamicos();
    }

    // ===== 3. FUNCIONES DE UTILIDAD =====
    function abrirModal(modal) { if (modal) { modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); } }
    function cerrarModal(modal) { if (modal) { modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); } }

    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }

        const container = document.getElementById('toast-container');
        const titulo = tipo === 'warning' ? 'Atención' : tipo === 'error' ? 'Error' : 'Éxito';
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        
        // Colores base
        let bgClass = tipo === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
                      tipo === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800';
        let iconColor = tipo === 'error' ? 'text-red-500' : tipo === 'success' ? 'text-green-500' : 'text-yellow-500';
        let iconPath = tipo === 'error' ? '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="15" y2="9"/><line x1="15" y1="12" x2="15" y2="15"/>' : 
                       '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>';

        toast.className = `pointer-events-auto flex items-start gap-3 p-4 rounded-lg border shadow-lg ${bgClass} animate-fade-in-down`;
        toast.innerHTML = `
            <div class="flex-shrink-0 ${iconColor}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    ${iconPath}
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold">${titulo}</h4>
                <p class="text-xs mt-0.5 opacity-90">${mensaje}</p>
            </div>
        `;
        
        container.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }

    // ===== VALIDACIÓN Y CONTADORES =====
    function validarDescripcion(descripcion) {
        return descripcion && descripcion.trim().length >= MIN_DESCRIPCION_LENGTH; // 🔥 FIX (trim)
    }

    function actualizarContadorCrear() {
        const textarea = document.getElementById('descripcion-nueva');
        const contador = document.getElementById('contador-caracteres-crear');
        const alerta = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.querySelector('#form-nueva-area button[type="submit"]');

        if (textarea && contador) {
            const len = textarea.value.trim().length;
            contador.textContent = `${len} / ${MIN_DESCRIPCION_LENGTH}`;
            
            if (len >= MIN_DESCRIPCION_LENGTH) {
                contador.className = 'text-xs text-green-600';
                if (alerta) alerta.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                contador.className = 'text-xs text-red-500';
                if (alerta) alerta.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = true; // Deshabilitar si es menor
            }
        }
    }

    function actualizarContadorEditar() {
        const textarea = document.getElementById('descripcion-area');
        const contador = document.getElementById('contador-caracteres-editar');
        const alerta = document.getElementById('alerta-minimo-editar');
        const btnSubmit = document.querySelector('#form-editar-area button[type="submit"]');

        if (textarea && contador) {
            const len = textarea.value.trim().length;
            contador.textContent = `${len} / ${MIN_DESCRIPCION_LENGTH}`;
            
            if (len >= MIN_DESCRIPCION_LENGTH) {
                contador.className = 'text-xs text-green-600';
                if (alerta) alerta.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                contador.className = 'text-xs text-red-500';
                if (alerta) alerta.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = true;
            }
        }
    }

    // ===== 4. ASIGNAR EVENTOS =====
    function asignarEventosDinamicos() {
        document.querySelectorAll('.btn-editar-area').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                const card = this.closest('.border');
                const id = card.dataset.id;
                const nombre = card.querySelector('h3').textContent;
                const desc = card.querySelector('p').textContent === 'Sin descripción' ? '' : card.querySelector('p').textContent;
                const sw = card.querySelector('.switch-sena');
                const estado = sw.classList.contains('active') ? 'activo' : 'inactivo';

                document.getElementById('nombre-area').value = nombre;
                document.getElementById('descripcion-area').value = desc;
                document.getElementById('estado-area').value = estado;
                modalEditar.setAttribute('data-card-id', id);
                
                actualizarContadorEditar(); // Actualizar contador al abrir
                abrirModal(modalEditar);
            });
        });

        document.querySelectorAll('.switch-sena').forEach(sw => {
            sw.addEventListener('click', function(e) {
                e.preventDefault(); e.stopPropagation();
                const card = this.closest('.border');
                const id = card.dataset.id;
                const nombre = card.querySelector('h3').textContent;
                
                if (this.classList.contains('active')) {
                    modalDeshabilitar.setAttribute('data-switch-id', id);
                    modalDeshabilitar.querySelector('span').textContent = `"${nombre}"`;
                    abrirModal(modalDeshabilitar);
                } else {
                    modalHabilitar.setAttribute('data-switch-id', id);
                    modalHabilitar.setAttribute('data-nombre-area', nombre);
                    modalHabilitar.querySelector('span').textContent = `"${nombre}"`;
                    abrirModal(modalHabilitar);
                }
            });
        });
    }

    // ===== BUSCADOR =====
    if (buscador) {
        buscador.addEventListener("keyup", function () {
            clearTimeout(timeoutBusqueda);
            const texto = this.value.trim();

            if (texto.length === 0) { 
                cargarAreas(); 
                return; 
            }

            if (texto.length < 2) {
                renderizarAreas([]); // 🔥 FIX (mostrar estado vacío)
                return;
            }
            
            timeoutBusqueda = setTimeout(async () => {
                try {
                    const res = await fetch(`${API_URL}?accion=buscar&q=${encodeURIComponent(texto)}`);
                    const result = await res.json();

                    if (result.success) {
                        renderizarAreas(result.data || []); // 🔥 FIX
                    } else {
                        renderizarAreas([]); // 🔥 FIX
                    }

                } catch (error) { 
                    console.error(error);
                    renderizarAreas([]); // 🔥 FIX
                }
            }, 300);
        });
    }

    // ===== ACCIONES SERVER (FETCH) =====

    // 1. CREAR
    const formCrear = document.getElementById('form-nueva-area');
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            
            const nombreVal = document.getElementById('area-nueva').value.trim();
            const descVal = document.getElementById('descripcion-nueva').value.trim();

            if (!nombreVal) {
                mostrarToastValidacion('El nombre del área es obligatorio', 'warning');
                return;
            }
            if (!validarDescripcion(descVal)) {
                mostrarToastValidacion(`La descripción debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres`, 'warning');
                return;
            }

            const oldText = btn.textContent;
            btn.disabled = true; btn.textContent = 'Guardando...';

            try {
                const res = await fetch(`${API_URL}?accion=crear`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nombre_area: nombreVal, descripcion_area: descVal })
                });
                const result = await res.json();
                if (result.success) {
                    cerrarModal(modalCrear);
                    mostrarToastValidacion('Área creada exitosamente', 'success');
                    cargarAreas();
                    this.reset();
                    if(document.getElementById('contador-caracteres-crear')) document.getElementById('contador-caracteres-crear').textContent = `0 / ${MIN_DESCRIPCION_LENGTH}`;
                } else {
                    mostrarToastValidacion(result.error || 'Error desconocido', 'error');
                }
            } catch (err) {
                mostrarToastValidacion('Error de conexión', 'error');
            } finally {
                btn.disabled = false; btn.textContent = oldText;
            }
        });
        
        // Listeners contadores creación
        const descCrear = document.getElementById('descripcion-nueva');
        if (descCrear) descCrear.addEventListener('input', actualizarContadorCrear);
    }

    // 2. EDITAR
    const formEditar = document.getElementById('form-editar-area');
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            const idArea = modalEditar ? modalEditar.getAttribute('data-card-id') : null;
            if (!idArea) return;

            const btn = this.querySelector('button[type="submit"]');
            const nombreVal = document.getElementById('nombre-area').value.trim();
            const descVal = document.getElementById('descripcion-area').value.trim();
            const estadoVal = document.getElementById('estado-area').value;

            if (!nombreVal) {
                mostrarToastValidacion('El nombre del área es obligatorio', 'warning');
                return;
            }
            if (!validarDescripcion(descVal)) {
                mostrarToastValidacion(`La descripción debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres`, 'warning');
                return;
            }

            const oldText = btn.textContent;
            btn.disabled = true; btn.textContent = 'Actualizando...';

            try {
                const res = await fetch(`${API_URL}?accion=actualizar`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id_area: idArea,
                        nombre_area: nombreVal,
                        descripcion_area: descVal,
                        estado: estadoVal === 'activo' ? 1 : 0
                    })
                });
                const result = await res.json();
                if (result.success) {
                    cerrarModal(modalEditar);
                    mostrarToastValidacion('Área actualizada exitosamente', 'success');
                    cargarAreas();
                } else {
                    mostrarToastValidacion(result.message || result.error, 'error');
                }
            } catch (err) {
                mostrarToastValidacion('Error de conexión', 'error');
            } finally {
                btn.disabled = false; btn.textContent = oldText;
            }
        });

        // Listeners contadores edición
        const descEditar = document.getElementById('descripcion-area');
        if (descEditar) descEditar.addEventListener('input', actualizarContadorEditar);
    }

    // 3. DESHABILITAR
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            const id = modalDeshabilitar.getAttribute('data-switch-id');
            const nombre = modalDeshabilitar.querySelector('span').textContent.replace(/"/g, '').trim();
            try {
                const res = await fetch(`${API_URL}?accion=desactivar&id_area=${id}`);
                const result = await res.json();
                if (result.success) {
                    cerrarModal(modalDeshabilitar);
                    mostrarToastValidacion(`Área "${nombre}" deshabilitada`, 'success');
                    cargarAreas();
                }
            } catch (err) { mostrarToastValidacion('Error', 'error'); }
        });
    }

    // 4. HABILITAR
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            const id = modalHabilitar.getAttribute('data-switch-id');
            const nombre = modalHabilitar.getAttribute('data-nombre-area');
            try {
                const res = await fetch(`${API_URL}?accion=activar&id_area=${id}`);
                const result = await res.json();
                if (result.success) {
                    cerrarModal(modalHabilitar);
                    mostrarToastValidacion(`Área "${nombre}" habilitada`, 'success');
                    cargarAreas();
                }
            } catch (err) { mostrarToastValidacion('Error', 'error'); }
        });
    }

    // ===== CIERRES GENÉRICOS =====
    cerrarModalBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalEditar)));
    cerrarModalEditarBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalEditadoConfirmacion)));
    cerrarModalDeshabilitarBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalDeshabilitar)));
    cerrarModalDeshabilitadoBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalDeshabilitadoConfirmacion)));
    cerrarModalHabilitarBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalHabilitar)));
    cerrarModalHabilitadoBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalHabilitadoConfirmacion)));
    cerrarModalCrearBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalCrear)));
    cerrarModalCreadoBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalCreadoConfirmacion)));
    cerrarModalDetalleBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalDetalle)));

    // Clic fuera del modal
    [modalEditar, modalDeshabilitar, modalHabilitar, modalCrear].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', e => {
                if (e.target === modal) cerrarModal(modal);
            });
        }
    });

    // ESC key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (modalEditar && !modalEditar.classList.contains('hidden')) cerrarModal(modalEditar);
            if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) cerrarModal(modalDeshabilitar);
            if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) cerrarModal(modalHabilitar);
            if (modalCrear && !modalCrear.classList.contains('hidden')) cerrarModal(modalCrear);
            if (modalDetalle && !modalDetalle.classList.contains('hidden')) cerrarModal(modalDetalle);
        }
    });

    if (btnCrearArea) {
        btnCrearArea.addEventListener('click', e => {
            e.preventDefault();
            if(formCrear) formCrear.reset();
            if(document.getElementById('contador-caracteres-crear')) document.getElementById('contador-caracteres-crear').textContent = `0 / ${MIN_DESCRIPCION_LENGTH}`;
            abrirModal(modalCrear);
            actualizarContadorCrear();
        });
    }

    cargarAreas();
});