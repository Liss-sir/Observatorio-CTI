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

        if (areas.length === 0) {
            gridAreas.innerHTML = '<div class="col-span-full text-center text-gray-500">No hay áreas registradas.</div>';
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
                                gridAreas.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">No se encontraron áreas con ese término.</div>';
                            }
                        } else {
                            // Renderizar resultados
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
                    const span = modalDeshabilitar.querySelector('span.font-medium');
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
                const colorEstado = switchEl.classList.contains('active') ? '#39A900' : '#9ca3af';
                 
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
            const oldText = btn ? btn.textContent : 'Guardar';
            if(btn) { btn.disabled = true; btn.textContent = 'Guardando...'; }

            // Obtener valores directamente de los inputs por ID para evitar errores de name
            const nombreVal = document.getElementById('area-nueva')?.value || document.querySelector('input[name="area"]')?.value;
            const descVal = document.getElementById('descripcion-nueva')?.value || document.querySelector('textarea[name="descripcion"]')?.value;

            // Validación básica
            if (!nombreVal || nombreVal.trim() === '') {
                alert('El nombre del área es obligatorio');
                if(btn) { btn.disabled = false; btn.textContent = oldText; }
                return;
            }

            const data = {
                nombre_area: nombreVal.trim(),
                descripcion_area: descVal ? descVal.trim() : ''
            };

            console.log('Enviando datos:', data); // Para depurar

            try {
                const res = await fetch(`${API_URL}?accion=crear`, { 
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' }, 
                    body: JSON.stringify(data) 
                });
                
                // Leer respuesta texto primero para ver errores de PHP si los hay
                const textResponse = await res.text();
                console.log('Respuesta raw:', textResponse);
                
                let result;
                try {
                    result = JSON.parse(textResponse);
                } catch (e) {
                    throw new Error('La respuesta no es JSON válido: ' + textResponse);
                }

                if(result.success) {
                    cerrarModal(modalCrear);
                    mostrarModalCreado(data.nombre_area);
                    cargarAreas();
                    this.reset();
                } else {
                    alert(result.error || result.message || 'Error desconocido al crear');
                }
            } catch(err) { 
                console.error(err); 
                alert('Error de conexión: ' + err.message); 
            }
            finally { if(btn) { btn.disabled = false; btn.textContent = oldText; } }
        });
    }

    // 2. EDITAR
    const formEditar = document.getElementById('form-editar-area');
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            const idArea = modalEditar ? modalEditar.getAttribute('data-card-id') : null;
            if(!idArea) return alert("Error: ID no encontrado");

            const btn = this.querySelector('button[type="submit"]');
            const oldText = btn ? btn.textContent : 'Guardar Cambios';
            if(btn) { btn.disabled = true; btn.textContent = 'Actualizando...'; }

            const nombreVal = document.getElementById('nombre-area')?.value || '';
            const descVal = document.getElementById('descripcion-area')?.value || '';
            const estadoVal = document.getElementById('estado-area')?.value || 'activo';

            const data = {
                id_area: idArea,
                nombre_area: nombreVal,
                descripcion_area: descVal,
                estado: estadoVal === 'activo' ? 1 : 0
            };

            try {
                const res = await fetch(`${API_URL}?accion=actualizar`, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data) });
                const result = await res.json();
                
                if(result.success) {
                    cerrarModal(modalEditar);
                    mostrarModalEditado(data.nombre_area);
                    cargarAreas();
                } else {
                    alert(result.message || result.error);
                }
            } catch(err) { 
                console.error(err); 
                alert('Error al conectar con el servidor'); 
            }
            finally { if(btn) { btn.disabled = false; btn.textContent = oldText; } }
        });
    }

    // 3. DESHABILITAR
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            const id = modalDeshabilitar ? modalDeshabilitar.getAttribute('data-switch-id') : null;
            const span = modalDeshabilitar ? modalDeshabilitar.querySelector('span.font-medium') : null;
            const nombre = span ? span.textContent.replace(/"/g,'').trim() : '';
            if(!id) return;
            
            try {
                const res = await fetch(`${API_URL}?accion=desactivar&id_area=${id}`);
                const result = await res.json();
                if(result.success) {
                    cerrarModal(modalDeshabilitar);
                    if(nombre) mostrarModalDeshabilitado(nombre);
                    cargarAreas();
                } else alert(result.error);
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
                } else alert(result.error);
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

    // INICIAR
    cargarAreas();
});