document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    const API_URL = '../../controllers/AreaController.php';

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
    const cerrarModalHabilitadoBtns = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const cerrarModalCrearBtns = document.querySelectorAll('.cerrar-modal-crear');
    const cerrarModalCreadoBtns = document.querySelectorAll('.cerrar-modal-creado');
    const cerrarModalDetalleBtns = document.querySelectorAll('.cerrar-modal-detalle');

    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnCrearArea = document.getElementById('btn-crear-area');
    
    const gridAreas = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.gap-6');
    const contadorTexto = document.getElementById('contador-areas');

    let areasData = [];
    let paginaActual = 1;
    const elementosPorPagina = 9; // puedes ajustar
    let areasFiltradas = [];
    let timeoutEditado = null, intervalContadorEditado = null;
    let timeoutDeshabilitado = null, intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null, intervalContadorHabilitado = null;
    let timeoutCreado = null, intervalContadorCreado = null;

    // ===== FUNCIÓN PARA MOSTRAR ALERTAS BONITAS (TOASTS) =====
    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        // Si no existe el contenedor, lo crea
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        
        // Colores según el tipo (warning, error, success)
        let bgClass = tipo === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 
                    tipo === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-yellow-50 border-yellow-200 text-yellow-800';
        
        toast.id = toastId;
        toast.className = `pointer-events-auto flex items-start gap-3 p-4 rounded-lg border shadow-lg ${bgClass} animate-fade-in-down`;
        toast.innerHTML = `
            <div class="flex-shrink-0 font-bold">${tipo === 'error' ? '✕' : tipo === 'success' ? '✓' : '⚠'}</div>
            <div class="text-sm">${mensaje}</div>
        `;
        
        toastContainer.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }

    // ===== VALIDACIÓN DE DESCRIPCIÓN =====
    const MIN_DESCRIPCION_LENGTH = 30;

    function validarDescripcion(descripcion) {
        return descripcion && descripcion.length >= MIN_DESCRIPCION_LENGTH;
    }

    // ===== ACTUALIZAR CONTADOR EN CREACIÓN =====
    function actualizarContadorCrear() {
        const textarea = document.getElementById('descripcion-nueva');
        const contador = document.getElementById('contador-caracteres-crear');
        const alerta = document.getElementById('alerta-minimo-crear');
        
        if (textarea && contador) {
            const longitud = textarea.value.length;
            contador.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contador.className = 'text-xs text-sena';
                if (alerta) alerta.classList.add('hidden');
            } else {
                contador.className = 'text-xs text-red-500';
                if (alerta) alerta.classList.remove('hidden');
            }
        }
    }

    // ===== ACTUALIZAR CONTADOR EN EDICIÓN =====
    function actualizarContadorEditar() {
        const textarea = document.getElementById('descripcion-area');
        const contador = document.getElementById('contador-caracteres-editar');
        const alerta = document.getElementById('alerta-minimo-editar');
        
        if (textarea && contador) {
            const longitud = textarea.value.length;
            contador.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contador.className = 'text-xs text-sena';
                if (alerta) alerta.classList.add('hidden');
            } else {
                contador.className = 'text-xs text-red-500';
                if (alerta) alerta.classList.remove('hidden');
            }
        }
    }

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
                if(contadorTexto) contadorTexto.textContent = `${areasData.length} áreas encontradas`;
            } else {
                gridAreas.innerHTML = '<div class="col-span-full text-center text-red-500">No se encontraron áreas.</div>';
                console.error('Respuesta del servidor:', result);
            }
        } catch (error) {
            console.error('Error fetching areas:', error);
            gridAreas.innerHTML = '<div class="col-span-full text-center text-red-500">Error de conexión.</div>';
        }
    }

    // ===== 2. RENDERIZAR HTML =====
    function renderizarAreas(areas) {
        if (!gridAreas) return;
        gridAreas.innerHTML = '';

        areasFiltradas = areas;

        if (areas.length === 0) {
            gridAreas.innerHTML = '<div class="col-span-full text-center text-gray-500">No hay áreas registradas.</div>';
            return;
        }

        const totalPaginas = Math.ceil(areas.length / elementosPorPagina);

        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }

        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const areasPagina = areas.slice(inicio, fin);

        areasPagina.forEach(area => {
            const isActive = area.estado == 1;
            const estadoClass = isActive ? 'active' : '';
            const estadoTitle = isActive ? 'Activo' : 'Inactivo';
            const colorIcono = isActive ? 'text-green-600' : 'text-gray-400';
            const desc = (area.descripcion_area && area.descripcion_area.trim() !== '') ? area.descripcion_area : 'Sin descripción';

            const cardHTML = `
                <div class="border rounded-xl p-5 bg-white shadow-sm cursor-pointer hover:shadow-md transition-shadow" data-id="${area.id_area}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 ${colorIcono}"></i>
                        </div>
                        <div class="flex items-center gap-1">
                            <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors z-20 relative" title="Editar área">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                            </button>
                            <div class="switch-sena ${estadoClass} z-20 relative" title="${estadoTitle}"></div>
                        </div>
                    </div>
                    <h3 class="font-semibold">${area.nombre_area}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">${desc}</p>
                </div>
            `;
            gridAreas.insertAdjacentHTML('beforeend', cardHTML);
        });
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        asignarEventosDinamicos();
        actualizarPaginacionAreas(areas.length);
    }

    function actualizarPaginacionAreas(totalElementos) {
        const contenedor = document.getElementById("paginacion-areas");
        if (!contenedor) return;

        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);

        if (totalPaginas <= 1) {
            contenedor.classList.add("hidden");
            return;
        }

        contenedor.classList.remove("hidden");

        let paginasHTML = '';

        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);

        if (paginaActual <= 3) {
            fin = Math.min(5, totalPaginas);
        }

        if (paginaActual >= totalPaginas - 2) {
            inicio = Math.max(totalPaginas - 4, 1);
        }

        // Primera página
        if (inicio > 1) {
            paginasHTML += `<button class="btn-pagina-area px-3 py-2 rounded-lg border">${1}</button>`;
            if (inicio > 2) paginasHTML += `<span>...</span>`;
        }

        // Intermedias
        for (let i = inicio; i <= fin; i++) {
            paginasHTML += `
                <button class="btn-pagina-area px-3 py-2 rounded-lg ${
                    paginaActual === i 
                    ? 'bg-sena text-white' 
                    : 'border border-gray-300 hover:bg-sena-soft'
                }" data-pagina="${i}">
                    ${i}
                </button>
            `;
        }

        // Última página
        if (fin < totalPaginas) {
            if (fin < totalPaginas - 1) paginasHTML += `<span>...</span>`;
            paginasHTML += `<button class="btn-pagina-area px-3 py-2 rounded-lg border">${totalPaginas}</button>`;
        }

        contenedor.innerHTML = `
            <div class="flex flex-col items-center gap-3">

                <div class="text-sm text-gray-500">
                    Mostrando 
                    <span class="font-semibold">${(paginaActual - 1) * elementosPorPagina + 1}</span> -
                    <span class="font-semibold">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span>
                    de <span class="font-semibold">${totalElementos}</span> áreas
                </div>

                <div class="flex items-center gap-2 flex-wrap justify-center">

                    <!-- Primera -->
                    <button class="btn-primera-area px-3 py-2 rounded-lg border">«</button>

                    <!-- Anterior -->
                    <button class="btn-anterior-area px-3 py-2 rounded-lg border">‹</button>

                    ${paginasHTML}

                    <!-- Siguiente -->
                    <button class="btn-siguiente-area px-3 py-2 rounded-lg border">›</button>

                    <!-- Última -->
                    <button class="btn-ultima-area px-3 py-2 rounded-lg border">»</button>

                </div>
            </div>
        `;

        // EVENTOS
        document.querySelectorAll('.btn-pagina-area').forEach(btn => {
            btn.addEventListener('click', () => {
                paginaActual = parseInt(btn.dataset.pagina);
                renderizarAreas(areasFiltradas);
            });
        });

        document.querySelector('.btn-anterior-area')?.addEventListener('click', () => {
            if (paginaActual > 1) {
                paginaActual--;
                renderizarAreas(areasFiltradas);
            }
        });

        document.querySelector('.btn-siguiente-area')?.addEventListener('click', () => {
            if (paginaActual < totalPaginas) {
                paginaActual++;
                renderizarAreas(areasFiltradas);
            }
        });

        document.querySelector('.btn-primera-area')?.addEventListener('click', () => {
            paginaActual = 1;
            renderizarAreas(areasFiltradas);
        });

        document.querySelector('.btn-ultima-area')?.addEventListener('click', () => {
            paginaActual = totalPaginas;
            renderizarAreas(areasFiltradas);
        });
    }

    const buscador = document.getElementById("buscador-areas");
    let timeoutBusqueda = null;

    if (buscador) {
        buscador.addEventListener("keyup", function () {
            clearTimeout(timeoutBusqueda);
            const texto = this.value.trim();
            
            console.log('Texto ingresado:', texto);
            
            // Si está vacío, cargar todas
            if (texto.length === 0) {
                paginaActual = 1;
                cargarAreas();
                return;
            }
            
            // Esperar al menos 2 caracteres
            if (texto.length < 2) {
                return;
            }
            
            timeoutBusqueda = setTimeout(async () => {
                try {
                    const url = `${API_URL}?accion=buscar&q=${encodeURIComponent(texto)}`;
                    console.log('URL de búsqueda:', url);
                    
                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    console.log('Respuesta del servidor:', result);
                    
                    // Verificar la estructura de la respuesta
                    if (result.success === true && Array.isArray(result.data)) {
                        if (result.data.length === 0) {
                            // No hay resultados
                            if (gridAreas) {
                                const buscador = document.getElementById("buscador-areas");
                                const hayBusqueda = buscador && buscador.value.trim().length > 0;
                                const textoBusqueda = document.getElementById('buscador-areas')?.value?.toLowerCase()?.trim() || '';

                                if (hayBusqueda) {
                                    // ✅ Mensaje: No se encontraron resultados
                                    gridAreas.innerHTML = `
                                        <div class="col-span-full flex flex-col items-center justify-center py-10 px-4 bg-white border border-gray-200 rounded-xl">
                                            <div class="w-20 h-20 mb-5 bg-sena-soft rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No se encontraron resultados</h3>
                                            <p class="text-sm text-gray-500 text-center max-w-sm">No hay áreas que coincidan con "${textoBusqueda}".</p>
                                        </div>
                                    `;
                                }else {
                                // ✅ Mensaje: No hay registros
                                gridAreas.innerHTML = `
                                    <div class="col-span-full flex flex-col items-center justify-center py-10 px-4 bg-white border border-gray-200 rounded-xl">
                                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-sena">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay áreas registradas</h3>
                                        <p class="text-sm text-gray-500 text-center max-w-sm mb-6">Comienza creando tu primera área.</p>
                                        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Crear primera área
                                        </button>
                                    </div>
                                `;
                                }
                                const btnEmpty = document.getElementById('btn-crear-desde-empty');
                                if (btnEmpty) btnEmpty.addEventListener('click', () => {
                                    modalCrear.classList.remove('hidden');
                                });
                            }
                        } else {
                            // Renderizar resultados
                            paginaActual = 1;
                            renderizarAreas(result.data);
                        } 
                    } else {
                        console.error('Estructura de respuesta inesperada:', result);
                        if (gridAreas) {
                            gridAreas.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Error en la respuesta del servidor.</div>';
                        }
                    }
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    if (gridAreas) {
                        gridAreas.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Error de conexión.</div>';
                    }
                }
            }, 300);
        });
    }

    // ===== 3. ASIGNAR EVENTOS =====
    function asignarEventosDinamicos() {
        // Botón Editar
        document.querySelectorAll('.btn-editar-area').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const card = this.closest('.border');
                const idArea = card.getAttribute('data-id');
                const nombreArea = card.querySelector('h3').textContent;
                const descripcionArea = card.querySelector('p').textContent.trim();
                const switchEl = card.querySelector('.switch-sena');
                const estadoArea = switchEl.classList.contains('active') ? 'activo' : 'inactivo';
                
                const nombreInput = document.getElementById('nombre-area');
                const descInput = document.getElementById('descripcion-area');
                const estadoSelect = document.getElementById('estado-area');
                
                if (nombreInput) nombreInput.value = nombreArea;
                if (descInput) descInput.value = descripcionArea;
                if (estadoSelect) estadoSelect.value = estadoArea;
                
                if (modalEditar) modalEditar.setAttribute('data-card-id', idArea);
                abrirModal(modalEditar);
            });
        });

        // Switches
        document.querySelectorAll('.switch-sena').forEach(switchEl => {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const card = this.closest('.border');
                const idArea = card.getAttribute('data-id');
                const nombreArea = card.querySelector('h3').textContent;
                
                if (this.classList.contains('active')) {
                    const span = modalDeshabilitar.querySelector('span.font-bold');
                    if(span) span.textContent = ` "${nombreArea} "`;
                    modalDeshabilitar.setAttribute('data-switch-id', idArea);
                    abrirModal(modalDeshabilitar);
                } else {
                    const span = modalHabilitar.querySelector('span.font-semibold');
                    if(span) span.textContent = ` "${nombreArea} "`;
                    modalHabilitar.setAttribute('data-switch-id', idArea);
                    modalHabilitar.setAttribute('data-nombre-area', nombreArea);
                    abrirModal(modalHabilitar);
                }
            });
        });

        // Click en Tarjeta (Detalle)
        document.querySelectorAll('.border[data-id]').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.closest('.btn-editar-area') || e.target.closest('.switch-sena')) return;
                
                const nombreArea = this.querySelector('h3').textContent;
                const descripcionArea = this.querySelector('p').textContent.trim();
                const switchEl = this.querySelector('.switch-sena');
                const estadoArea = switchEl.classList.contains('active') ? 'Activo' : 'Inactivo';
                const colorEstado = switchEl.classList.contains('active') ? '#39A900' : '#787878';
                 
                document.getElementById('detalle-titulo').textContent = `Detalle de: ${nombreArea}`;
                document.getElementById('detalle-nombre').textContent = nombreArea;
                document.getElementById('detalle-descripcion').textContent = descripcionArea;
                document.getElementById('detalle-estado').textContent = estadoArea;
                document.getElementById('detalle-estado-indicador').style.backgroundColor = colorEstado;
                
                abrirModal(modalDetalle);
            });
        });
    }

    // ===== UTILIDADES MODALES =====
    function abrirModal(modal) { if(modal){ modal.classList.remove('hidden'); document.body.classList.add('overflow-hidden'); } }
    function cerrarModal(modal) { if(modal){ modal.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); } }
    
    function limpiarTemporizadores(tipo) {
        const t = tipo.charAt(0).toUpperCase() + tipo.slice(1);
        if(window[`timeout${t}`]) clearTimeout(window[`timeout${t}`]);
        if(window[`intervalContador${t}`]) clearInterval(window[`intervalContador${t}`]);
        const pb = document.getElementById(`progress-bar-${tipo}`);
        if(pb) pb.style.width = '0%';
        const cnt = document.getElementById(`contador-segundos-${tipo}`);
        if(cnt) cnt.textContent = '3';
    }

    function cerrarModalEditado() { cerrarModal(modalEditadoConfirmacion); limpiarTemporizadores('editado'); }
    function cerrarModalDeshabilitado() { cerrarModal(modalDeshabilitadoConfirmacion); limpiarTemporizadores('deshabilitado'); }
    function cerrarModalHabilitado() { cerrarModal(modalHabilitadoConfirmacion); limpiarTemporizadores('habilitado'); }
    function cerrarModalCreado() { cerrarModal(modalCreadoConfirmacion); limpiarTemporizadores('creado'); }

    function mostrarModalConTemporizador(modal, spanId, nombre, tipo) {
        if(!modal) return;
        const span = document.getElementById(spanId);
        if(span) span.textContent = ` "${nombre} "`;
        abrirModal(modal);
        
        let seg = 3;
        const cnt = document.getElementById(`contador-segundos-${tipo}`);
        if(cnt) cnt.textContent = seg;
        
        const tCap = tipo.charAt(0).toUpperCase() + tipo.slice(1);
        if(window[`intervalContador${tCap}`]) clearInterval(window[`intervalContador${tCap}`]);
        
        window[`intervalContador${tCap}`] = setInterval(() => {
            seg--;
            if(cnt) cnt.textContent = seg;
            if(seg <= 0) clearInterval(window[`intervalContador${tCap}`]);
        }, 1000);
        
        const pb = document.getElementById(`progress-bar-${tipo}`);
        if(pb) {
            pb.style.width = '0%';
            setTimeout(() => pb.style.width = '100%', 50);
        }
        
        if(window[`timeout${tCap}`]) clearTimeout(window[`timeout${tCap}`]);
        window[`timeout${tCap}`] = setTimeout(() => {
            if(tipo==='editado') cerrarModalEditado();
            if(tipo==='deshabilitado') cerrarModalDeshabilitado();
            if(tipo==='habilitado') cerrarModalHabilitado();
            if(tipo==='creado') cerrarModalCreado();
        }, 3000);
    }

    const mostrarModalEditado = (n) => mostrarModalConTemporizador(modalEditadoConfirmacion, 'nombre-area-editado', n, 'editado');
    const mostrarModalDeshabilitado = (n) => mostrarModalConTemporizador(modalDeshabilitadoConfirmacion, 'nombre-area-deshabilitado', n, 'deshabilitado');
    const mostrarModalHabilitado = (n) => mostrarModalConTemporizador(modalHabilitadoConfirmacion, 'nombre-area-habilitado', n, 'habilitado');
    const mostrarModalCreado = (n) => mostrarModalConTemporizador(modalCreadoConfirmacion, 'nombre-area-creado', n, 'creado');

    // ===== ACCIONES CON EL SERVER (FETCH) =====

    // 1. CREAR (CORREGIDO)
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
                    mostrarModalCreado(nombreVal);
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
            const oldText = btn.textContent;

            const nombreVal = document.getElementById('nombre-area').value.trim();
            const descVal = document.getElementById('descripcion-area').value.trim();

            if (!nombreVal) {
                mostrarToastValidacion('El nombre del área es obligatorio', 'warning');
                return;
            }
            if (!validarDescripcion(descVal)) {
                mostrarToastValidacion(`La descripción debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres`, 'warning');
                return;
            }

            const data = {
                id_area: idArea,
                nombre_area: nombreVal,
                descripcion_area: descVal,
            };

            try {
                const res = await fetch(`${API_URL}?accion=actualizar`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) });
                const result = await res.json();
                
                if(result.success) {
                    cerrarModal(modalEditar);
                    mostrarModalEditado(data.nombre_area);
                    cargarAreas();
                } else {
                   mostrarToastValidacion(result.message || result.error);
                }
            } catch(err) { 
                console.error(err); 
                mostrarToastValidacion('Error al conectar con el servidor'); 
            }
        });

        const descEditar = document.getElementById('descripcion-area');
        if (descEditar) descEditar.addEventListener('input', actualizarContadorEditar);
    }

    // 3. DESHABILITAR
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            const id = modalDeshabilitar ? modalDeshabilitar.getAttribute('data-switch-id') : null;
            const span = modalDeshabilitar ? modalDeshabilitar.querySelector('span.font-bold') : null;
            const nombre = span ? span.textContent.replace(/"/g,'').trim() : '';
            if(!id) return;
            
            try {
                const res = await fetch(`${API_URL}?accion=desactivar&id_area=${id}`);
                const result = await res.json();
                if(result.success) {
                    cerrarModal(modalDeshabilitar);
                    if(nombre) mostrarModalDeshabilitado(nombre);
                    cargarAreas();
                } else mostrarToastValidacion(result.error);
            } catch(err) { console.error(err); }
        });
    }

    // 4. HABILITAR
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            const id = modalHabilitar ? modalHabilitar.getAttribute('data-switch-id') : null;
            const nombre = modalHabilitar ? modalHabilitar.getAttribute('data-nombre-area') : '';
            if(!id) return;

            try {
                const res = await fetch(`${API_URL}?accion=activar&id_area=${id}`);
                const result = await res.json();
                if(result.success) {
                    cerrarModal(modalHabilitar);
                    if(nombre) mostrarModalHabilitado(nombre);
                    cargarAreas();
                } else mostrarToastValidacion(result.error);
            } catch(err) { console.error(err); }
        });
    }

    // ===== CIERRES GENÉRICOS =====
    cerrarModalBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalEditar)));
    cerrarModalEditadoBtns.forEach(b => b.addEventListener('click', cerrarModalEditado));
    cerrarModalDeshabilitarBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalDeshabilitar)));
    cerrarModalDeshabilitadoBtns.forEach(b => b.addEventListener('click', cerrarModalDeshabilitado));
    cerrarModalHabilitarBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalHabilitar)));
    cerrarModalHabilitadoBtns.forEach(b => b.addEventListener('click', cerrarModalHabilitado));
    cerrarModalCrearBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalCrear)));
    cerrarModalCreadoBtns.forEach(b => b.addEventListener('click', cerrarModalCreado));
    cerrarModalDetalleBtns.forEach(b => b.addEventListener('click', () => cerrarModal(modalDetalle)));

    [modalEditar, modalEditadoConfirmacion, modalDeshabilitar, modalDeshabilitadoConfirmacion, modalHabilitar, modalHabilitadoConfirmacion, modalCrear, modalCreadoConfirmacion, modalDetalle].forEach(modal => {
        if(modal) {
            modal.addEventListener('click', e => {
                if(e.target === modal || e.target.classList.contains('fixed')) {
                    if(modal === modalEditadoConfirmacion) cerrarModalEditado();
                    else if(modal === modalDeshabilitadoConfirmacion) cerrarModalDeshabilitado();
                    else if(modal === modalHabilitadoConfirmacion) cerrarModalHabilitado();
                    else if(modal === modalCreadoConfirmacion) cerrarModalCreado();
                    else cerrarModal(modal);
                }
            });
        }
    });

    document.addEventListener('keydown', e => {
        if(e.key === 'Escape') {
            if(modalEditar && !modalEditar.classList.contains('hidden')) cerrarModal(modalEditar);
            else if(modalEditadoConfirmacion && !modalEditadoConfirmacion.classList.contains('hidden')) cerrarModalEditado();
            else if(modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) cerrarModal(modalDeshabilitar);
            else if(modalDeshabilitadoConfirmacion && !modalDeshabilitadoConfirmacion.classList.contains('hidden')) cerrarModalDeshabilitado();
            else if(modalHabilitar && !modalHabilitar.classList.contains('hidden')) cerrarModal(modalHabilitar);
            else if(modalHabilitadoConfirmacion && !modalHabilitadoConfirmacion.classList.contains('hidden')) cerrarModalHabilitado();
            else if(modalCrear && !modalCrear.classList.contains('hidden')) cerrarModal(modalCrear);
            else if(modalCreadoConfirmacion && !modalCreadoConfirmacion.classList.contains('hidden')) cerrarModalCreado();
            else if(modalDetalle && !modalDetalle.classList.contains('hidden')) cerrarModal(modalDetalle);
        }
    });

    if (btnCrearArea) {
        btnCrearArea.addEventListener('click', e => {
            e.preventDefault();
            const form = document.getElementById('form-nueva-area');
            if(form) form.reset();
            abrirModal(modalCrear);
        });
    }

    // ===== LÓGICA DEL CONTADOR DE CARACTERES =====
    const descCrear = document.getElementById('descripcion-nueva');
    const contadorCrear = document.getElementById('contador-caracteres-crear'); // Asegúrate que este ID exista en tu HTML
    const descEditar = document.getElementById('descripcion-area');
    const contadorEditar = document.getElementById('contador-caracteres-editar'); // Asegúrate que este ID exista en tu HTML

    if (descCrear) {
        descCrear.addEventListener('input', function() {
            const len = this.value.length;
            if (contadorCrear) contadorCrear.textContent = `${len} / 30 caracteres`;
            // Cambia color si es válido o inválido
            if (len >= 30) contadorCrear.classList.replace('text-red-500', 'text-green-600');
            else contadorCrear.classList.replace('text-green-600', 'text-red-500');
        });
    }

    if (descEditar) {
        descEditar.addEventListener('input', function() {
            const len = this.value.length;
            if (contadorEditar) contadorEditar.textContent = `${len} / 30 caracteres`;
            if (len >= 30) contadorEditar.classList.replace('text-red-500', 'text-green-600');
            else contadorEditar.classList.replace('text-green-600', 'text-red-500');
        });
    }

    // INICIAR
    cargarAreas();
});