document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    const API_URL = '../../controllers/TendenciaEmergenteController.php';
    
    // ===== VARIABLES DE MODALES =====
    const modalCrear = document.getElementById('modal-crear-tecnologia-emergente');
    const modalConfirmacionCrear = document.getElementById('modal-creado-confirmacion-tecnologia-emergente');
    const modalEditar = document.getElementById('modal-editar-tecnologia-emergente');
    const modalConfirmacionEditar = document.getElementById('modal-editado-confirmacion-tecnologia-emergente');
    const modalDetalle = document.getElementById('modal-detalle-tecnologia-emergente');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-tecnologia-emergente');
    const modalConfirmacionDeshabilitar = document.getElementById('modal-deshabilitado-tecnologia-emergente');
    const modalHabilitar = document.getElementById('modal-habilitar-tecnologia-emergente');
    const modalConfirmacionHabilitar = document.getElementById('modal-habilitado-confirmacion-tecnologia');
    
    // Elementos del modal de creación
    const btnAbrirModalCrear = document.getElementById('btn-crear-tendencia');
    const botonesCerrarCrear = document.querySelectorAll('.cerrar-modal-crear');
    const formCrear = document.getElementById('form-nueva-tecnologia-emergente');
    
    // Elementos del modal de edición
    const botonesCerrarEditar = document.querySelectorAll('.cerrar-modal-editar');
    const formEditar = document.getElementById('form-editar-tecnologia-emergente');
    const selectAreaEditar = document.getElementById('area-tecnologia');
    const textareaDescripcionEditar = document.getElementById('descripcion-editar');
    const selectEstadoEditar = document.getElementById('estado-tecnologia');
    
    // Elementos del modal de confirmación de creación
    const botonesCerrarConfirmacionCrear = document.querySelectorAll('.cerrar-modal-creado');
    const nombreTecnologiaSpan = document.getElementById('nombre-tecnologia-creada');
    const contadorSegundosCrear = document.getElementById('contador-segundos-creado');
    const progressBarCrear = document.getElementById('progress-bar-creado');

    // Elementos del modal de confirmación de edición
    const botonesCerrarConfirmacionEditar = document.querySelectorAll('.cerrar-modal-editado');
    const nombreTecnologiaEditadaSpan = document.getElementById('nombre-tecnologia-editada');
    const contadorSegundosEditar = document.getElementById('contador-segundos-editado');
    const progressBarEditar = document.getElementById('progress-bar-editado');

    // Elementos del modal de detalle
    const botonesCerrarDetalle = document.querySelectorAll('.cerrar-modal-detalle');
    const detalleTitulo = document.getElementById('detalle-titulo');
    const detalleNombre = document.getElementById('detalle-nombre-tecnologia');
    const detalleDescripcion = document.getElementById('detalle-descripcion-tecnologia');
    const detalleEstadoTexto = document.getElementById('detalle-estado-tecnologia');
    const detalleEstadoBadge = document.getElementById('detalle-estado-badge');
    const detalleEstadoIndicador = document.getElementById('detalle-estado-indicador');

    // Elementos del modal de deshabilitar
    const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const spanTecnologiaDeshabilitar = document.querySelector('#modal-deshabilitar-tecnologia-emergente .font-medium.text-sena-text-main');

    // Elementos del modal de confirmación de deshabilitar
    const botonesCerrarConfirmacionDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const nombreTecnologiaDeshabilitadaSpan = document.getElementById('nombre-tecnologia-emergente-deshabilitada');
    const contadorSegundosDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
    const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

    // Elementos del modal de habilitar
    const botonesCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const spanTecnologiaHabilitar = document.getElementById('nombre-tecnologia-habilitar');

    // Elementos del modal de confirmación de habilitar
    const botonesCerrarConfirmacionHabilitar = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const nombreTecnologiaHabilitadaSpan = document.getElementById('nombre-tecnología-habilitado-exito');
    const contadorSegundosHabilitado = document.getElementById('contador-segundos');
    const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

    // Variables para almacenar datos
    let tecnologiasActuales = [];
    let areasActuales = [];
    let tecnologiaSeleccionada = null;
    let tecnologiaEditadaId = null;
    let tecnologiaDeshabilitarId = null;
    let tecnologiaDeshabilitarNombre = null;
    let tecnologiaHabilitarId = null;
    let tecnologiaHabilitarNombre = null;

    let timeoutId = null;
    let intervalId = null;

    // ===== FUNCIÓN PARA MOSTRAR TOAST DE VALIDACIÓN =====
    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        const toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }
        
        const container = document.getElementById('toast-container');
        
        const titulo = tipo === 'warning' ? 'Campo requerido' : 
                       tipo === 'error' ? 'Error' : 
                       tipo === 'info' ? 'Información' : 'Éxito';
        
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast-validation ${tipo}`;
        
        const iconos = {
            info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
            success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`
        };

        toast.innerHTML = `
            <div class="toast-contenido">
                <div class="toast-icono-wrapper">
                    <div class="toast-icono">${iconos[tipo] || iconos.warning}</div>
                </div>
                <div class="toast-mensaje-wrapper">
                    <div class="toast-titulo">${titulo}</div>
                    <div class="toast-mensaje">${mensaje}</div>
                </div>
            </div>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.classList.add('exit');
                setTimeout(() => {
                    if (toastElement.parentNode) toastElement.remove();
                }, 200);
            }
        }, 3000);
    }

    // ===== FUNCIONES DE API =====
    async function cargarTecnologias() {
        try {
            const response = await fetch(`${API_URL}?accion=listarTodas`);
            const data = await response.json();
            
            if (data.status === 'success') {
                tecnologiasActuales = data.data;
                renderizarTecnologias(tecnologiasActuales);
                actualizarContador(tecnologiasActuales.length);
            }
        } catch (error) {
            console.error('Error al cargar tecnologías:', error);
            mostrarToastValidacion('Error al cargar las tecnologías', 'error');
        }
    }

    async function cargarAreas() {
        try {
            const response = await fetch('../../controllers/AreaController.php?accion=listar');
            const data = await response.json();
            
            if (data.status === 'success') {
                areasActuales = data.data;
                
                const selectCrear = document.querySelector('#form-nueva-tecnologia-emergente select[name="area"]');
                const selectEditar = document.getElementById('area-tecnologia');
                
                if (selectCrear) {
                    selectCrear.innerHTML = '<option value="">Seleccione un área</option>';
                    areasActuales.forEach(area => {
                        if (area.estado == 1) {
                            selectCrear.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                        }
                    });
                }
                
                if (selectEditar) {
                    selectEditar.innerHTML = '<option value="">Seleccione un área</option>';
                    areasActuales.forEach(area => {
                        selectEditar.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                    });
                }
            }
        } catch (error) {
            console.error('Error al cargar áreas:', error);
            mostrarToastValidacion('Error al cargar las áreas', 'error');
        }
    }

    async function crearTecnologia(data) {
        try {
            const response = await fetch(`${API_URL}?accion=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                const area = areasActuales.find(a => a.id_area == data.id_area);
                const nuevaTecnologia = {
                    id_tendencia: result.id_tendencia,
                    id_area: data.id_area,
                    descripcion: data.descripcion,
                    estado: data.estado,
                    nombre_area: area ? area.nombre_area : 'Área'
                };
                
                tecnologiasActuales.push(nuevaTecnologia);
                const estabaVacio = tecnologiasActuales.length === 1;
                
                if (estabaVacio) {
                    renderizarTecnologias(tecnologiasActuales);
                } else {
                    agregarTarjetaVisual(nuevaTecnologia);
                }
                
                actualizarContador(tecnologiasActuales.length);
                return { success: true, id: result.id_tendencia };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al crear tecnología:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function actualizarTecnologia(data) {
        try {
            const response = await fetch(`${API_URL}?accion=actualizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                const tecnologiaIndex = tecnologiasActuales.findIndex(t => t.id_tendencia == data.id_tendencia);
                if (tecnologiaIndex !== -1) {
                    tecnologiasActuales[tecnologiaIndex] = {
                        ...tecnologiasActuales[tecnologiaIndex],
                        descripcion: data.descripcion,
                        id_area: data.id_area,
                        estado: data.estado
                    };
                    
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    if (area) {
                        tecnologiasActuales[tecnologiaIndex].nombre_area = area.nombre_area;
                    }
                }
                
                actualizarTarjetaVisual(data);
                actualizarContador(tecnologiasActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al actualizar tecnología:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function cambiarEstadoTecnologia(id, accion) {
        try {
            const response = await fetch(`${API_URL}?accion=${accion}&id_tendencia=${id}`, {
                method: 'POST'
            });
            
            const result = await response.json();
            
            if (result.success) {
                const tecnologiaIndex = tecnologiasActuales.findIndex(t => t.id_tendencia == id);
                if (tecnologiaIndex !== -1) {
                    tecnologiasActuales[tecnologiaIndex].estado = accion === 'activar' ? 1 : 0;
                }
                
                actualizarSwitchVisual(id, accion === 'activar');
                actualizarContador(tecnologiasActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error(`Error al ${accion} tecnología:`, error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function verificarDescripcionExistente(descripcion, id_area, excluir_id = null) {
        try {
            const response = await fetch(`${API_URL}?accion=verificarDescripcion`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ descripcion, id_area, excluir_id })
            });
            
            const result = await response.json();
            return result.existe;
        } catch (error) {
            console.error('Error al verificar descripción:', error);
            return false;
        }
    }

    // ===== FUNCIONES VISUALES =====
    function renderizarTecnologias(tecnologias) {
        const contenedor = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
        if (!contenedor) return;
        
        contenedor.innerHTML = '';
        
        if (tecnologias.length === 0) {
            contenedor.innerHTML = `
                <div class="col-span-3 w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                    <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 6.75h2.25c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 8.625 20.625V7.875c0-.621.504-1.125 1.125-1.125ZM16.5 3.75h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15.375 20.625V4.875c0-.621.504-1.125 1.125-1.125Z"/>
                        </svg>
                    </div>
                    <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No hay tecnologías emergentes</h3>
                    <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primera tecnología emergente para fortalecer el desarrollo tecnológico de Risaralda.</p>
                    <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Crear primera tecnología
                    </button>
                </div>
            `;
            
            const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
            if (btnCrearEmpty) {
                btnCrearEmpty.addEventListener('click', abrirModalCrear);
            }
            return;
        }
        
        contenedor.className = 'grid grid-cols-1 md:grid-cols-3 gap-3';
        
        tecnologias.forEach(tecnologia => {
            const tarjeta = crearTarjetaHTML(tecnologia);
            contenedor.appendChild(tarjeta);
        });
        
        asignarEventosTarjetas();
    }

    function crearTarjetaHTML(tecnologia) {
        const div = document.createElement('div');
        div.className = 'border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia';
        div.setAttribute('data-id', tecnologia.id_tendencia);
        div.setAttribute('data-area', tecnologia.id_area);
        div.setAttribute('data-nombre', tecnologia.descripcion.substring(0, 50) + (tecnologia.descripcion.length > 50 ? '...' : ''));
        div.setAttribute('data-descripcion', tecnologia.descripcion);
        div.setAttribute('data-estado', tecnologia.estado == 1 ? 'activo' : 'inactivo');
        
        div.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tecnología" data-id="${tecnologia.id_tendencia}" data-nombre="${tecnologia.descripcion.substring(0, 50)}" data-descripcion="${tecnologia.descripcion.replace(/"/g, '&quot;')}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${tecnologia.estado == 1 ? 'active' : ''}" title="${tecnologia.estado == 1 ? 'Activo' : 'Inactivo'}"></div>
                </div>
            </div>
            <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">${tecnologia.descripcion.substring(0, 50)}${tecnologia.descripcion.length > 50 ? '...' : ''}</h3>
            <p class="text-xs text-sena-text-soft line-clamp-2">${tecnologia.descripcion}</p>
            <div class="mt-2 text-xs text-sena-text-soft">
                <hr class="my-1 border-sena-border mb-2">
                <div class="flex items-center gap-2">
                    <span class="estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${tecnologia.estado == 1 ? 'bg-sena' : 'bg-gray-300'}"></span>
                    <span class="badge-area">${tecnologia.nombre_area || 'Área'}</span>
                </div>
            </div>
        `;
        
        return div;
    }

    function agregarTarjetaVisual(tecnologia) {
        const contenedor = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3');
        if (contenedor) {
            const tarjeta = crearTarjetaHTML(tecnologia);
            contenedor.appendChild(tarjeta);
            asignarEventosTarjeta(tarjeta);
        }
    }

    function actualizarSwitchVisual(id, activar) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach(tarjeta => {
            if (tarjeta.getAttribute('data-id') == id) {
                const switchEl = tarjeta.querySelector('.switch-sena');
                const estadoBolita = tarjeta.querySelector('.estado-bolita');
                if (switchEl) {
                    if (activar) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    } else {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                    }
                }
                if (estadoBolita) {
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${activar ? 'bg-sena' : 'bg-gray-300'}`;
                }
                tarjeta.setAttribute('data-estado', activar ? 'activo' : 'inactivo');
            }
        });
    }

    function actualizarTarjetaVisual(data) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach(tarjeta => {
            if (tarjeta.getAttribute('data-id') == data.id_tendencia) {
                const tituloEl = tarjeta.querySelector('h3');
                const descripcionEl = tarjeta.querySelector('p.text-xs');
                const badgeArea = tarjeta.querySelector('.badge-area');
                const switchEl = tarjeta.querySelector('.switch-sena');
                const estadoBolita = tarjeta.querySelector('.estado-bolita');
                
                if (tituloEl) tituloEl.textContent = data.descripcion.substring(0, 50) + (data.descripcion.length > 50 ? '...' : '');
                if (descripcionEl) descripcionEl.textContent = data.descripcion;
                if (badgeArea) {
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    badgeArea.textContent = area ? area.nombre_area : 'Área';
                }
                if (switchEl) {
                    if (data.estado == 1) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    } else {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                    }
                }
                if (estadoBolita) {
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${data.estado == 1 ? 'bg-sena' : 'bg-gray-300'}`;
                }
                tarjeta.setAttribute('data-nombre', data.descripcion.substring(0, 50));
                tarjeta.setAttribute('data-descripcion', data.descripcion);
                tarjeta.setAttribute('data-estado', data.estado == 1 ? 'activo' : 'inactivo');
                tarjeta.setAttribute('data-area', data.id_area);
            }
        });
    }

    function actualizarContador(total) {
        const contador = document.querySelector('.text-sm.text-sena-text-soft strong');
        if (contador) {
            contador.textContent = total;
        }
    }

    function filtrarTecnologias() {
        const terminoBusqueda = document.querySelector('input[placeholder*="Buscar tecnologías"]').value.toLowerCase();
        
        let filtradas = tecnologiasActuales;
        
        if (terminoBusqueda) {
            filtradas = filtradas.filter(t => 
                t.descripcion.toLowerCase().includes(terminoBusqueda) ||
                (t.nombre_area && t.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        renderizarTecnologias(filtradas);
        actualizarContador(filtradas.length);
    }

    // ===== FUNCIONES DE MODALES =====
    function abrirModalCrear() {
        if (formCrear) formCrear.reset();
        const selectCrear = document.querySelector('#form-nueva-tecnologia-emergente select[name="area"]');
        if (selectCrear && areasActuales.length > 0) {
            selectCrear.innerHTML = '<option value="">Seleccione un área</option>';
            areasActuales.forEach(area => {
                if (area.estado == 1) {
                    selectCrear.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                }
            });
        }
        modalCrear.classList.remove('hidden');
        modalCrear.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalCrear() {
        modalCrear.classList.add('hidden');
        document.body.style.overflow = '';
        if (formCrear) formCrear.reset();
    }

    function abrirModalEditar(id, nombre, descripcion) {
        tecnologiaEditadaId = id;
        
        const areaSelect = document.getElementById('area-tecnologia');
        if (areaSelect && areasActuales.length > 0) {
            areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
            areasActuales.forEach(area => {
                areaSelect.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
            });
        }
        
        if (textareaDescripcionEditar) textareaDescripcionEditar.value = descripcion;
        if (areaSelect && tecnologiaSeleccionada) areaSelect.value = tecnologiaSeleccionada.id_area;
        if (selectEstadoEditar) selectEstadoEditar.value = tecnologiaSeleccionada?.estado == 1 ? 'activo' : 'inactivo';
        
        modalEditar.classList.remove('hidden');
        modalEditar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalEditar() {
        modalEditar.classList.add('hidden');
        document.body.style.overflow = '';
        if (formEditar) formEditar.reset();
        tecnologiaEditadaId = null;
    }

    function abrirModalDetalle(id, nombre, descripcion, estado) {
        if (detalleTitulo) detalleTitulo.textContent = `Detalle: ${nombre}`;
        if (detalleNombre) detalleNombre.textContent = nombre;
        if (detalleDescripcion) detalleDescripcion.textContent = descripcion;
        if (detalleEstadoTexto) detalleEstadoTexto.textContent = estado === 'activo' ? 'Activo' : 'Inactivo';
        
        if (detalleEstadoBadge) {
            if (estado === 'activo') {
                detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena';
                if (detalleEstadoIndicador) detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-[#39A900] mr-2';
            } else {
                detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600';
                if (detalleEstadoIndicador) detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-gray-400 mr-2';
            }
        }
        
        modalDetalle.classList.remove('hidden');
        modalDetalle.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalDetalle() {
        modalDetalle.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function abrirModalDeshabilitar(id, nombre) {
        tecnologiaDeshabilitarId = id;
        tecnologiaDeshabilitarNombre = nombre;
        if (spanTecnologiaDeshabilitar) spanTecnologiaDeshabilitar.textContent = `"${nombre}"`;
        modalDeshabilitar.classList.remove('hidden');
        modalDeshabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalDeshabilitar() {
        modalDeshabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        tecnologiaDeshabilitarId = null;
        tecnologiaDeshabilitarNombre = null;
    }

    function abrirModalHabilitar(id, nombre) {
        tecnologiaHabilitarId = id;
        tecnologiaHabilitarNombre = nombre;
        if (spanTecnologiaHabilitar) spanTecnologiaHabilitar.textContent = `"${nombre}"`;
        modalHabilitar.classList.remove('hidden');
        modalHabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalHabilitar() {
        modalHabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        tecnologiaHabilitarId = null;
        tecnologiaHabilitarNombre = null;
    }

    function abrirModalConfirmacionCrear(nombreTecnologia) {
        if (nombreTecnologiaSpan) nombreTecnologiaSpan.textContent = `"${nombreTecnologia}"`;
        iniciarContadorYBarra(progressBarCrear, contadorSegundosCrear, () => cerrarModalConfirmacionCrear());
        modalConfirmacionCrear.classList.remove('hidden');
        modalConfirmacionCrear.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalConfirmacionCrear() {
        modalConfirmacionCrear.classList.add('hidden');
        document.body.style.overflow = '';
        detenerContadorYBarra();
    }

    function abrirModalConfirmacionEditar(nombreTecnologia) {
        if (nombreTecnologiaEditadaSpan) nombreTecnologiaEditadaSpan.textContent = `"${nombreTecnologia}"`;
        iniciarContadorYBarra(progressBarEditar, contadorSegundosEditar, () => cerrarModalConfirmacionEditar());
        modalConfirmacionEditar.classList.remove('hidden');
        modalConfirmacionEditar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalConfirmacionEditar() {
        modalConfirmacionEditar.classList.add('hidden');
        document.body.style.overflow = '';
        detenerContadorYBarra();
    }

    function abrirModalConfirmacionDeshabilitar(nombreTecnologia) {
        if (nombreTecnologiaDeshabilitadaSpan) nombreTecnologiaDeshabilitadaSpan.textContent = `"${nombreTecnologia}"`;
        iniciarContadorYBarra(progressBarDeshabilitado, contadorSegundosDeshabilitado, () => cerrarModalConfirmacionDeshabilitar());
        modalConfirmacionDeshabilitar.classList.remove('hidden');
        modalConfirmacionDeshabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalConfirmacionDeshabilitar() {
        modalConfirmacionDeshabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        detenerContadorYBarra();
    }

    function abrirModalConfirmacionHabilitar(nombreTecnologia) {
        if (nombreTecnologiaHabilitadaSpan) nombreTecnologiaHabilitadaSpan.textContent = `"${nombreTecnologia}"`;
        iniciarContadorYBarra(progressBarHabilitado, contadorSegundosHabilitado, () => cerrarModalConfirmacionHabilitar());
        modalConfirmacionHabilitar.classList.remove('hidden');
        modalConfirmacionHabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalConfirmacionHabilitar() {
        modalConfirmacionHabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        detenerContadorYBarra();
    }

    function iniciarContadorYBarra(progressBar, contador, callback) {
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
            void progressBar.offsetWidth;
            progressBar.style.transition = 'width 3000ms linear';
            setTimeout(() => { progressBar.style.width = '100%'; }, 50);
        }
        
        let segundos = 3;
        if (contador) contador.textContent = segundos;
        
        if (timeoutId) clearTimeout(timeoutId);
        if (intervalId) clearInterval(intervalId);
        
        intervalId = setInterval(() => {
            segundos--;
            if (contador) contador.textContent = segundos;
        }, 1000);
        
        timeoutId = setTimeout(callback, 3000);
    }

    function detenerContadorYBarra() {
        if (timeoutId) { clearTimeout(timeoutId); timeoutId = null; }
        if (intervalId) { clearInterval(intervalId); intervalId = null; }
    }

    function asignarEventosTarjetas() {
        document.querySelectorAll('.tarjeta-tecnologia').forEach(tarjeta => {
            asignarEventosTarjeta(tarjeta);
        });
    }

    function asignarEventosTarjeta(tarjeta) {
        const btnEditar = tarjeta.querySelector('.btn-editar-tendencia');
        const switchEl = tarjeta.querySelector('.switch-sena');
        
        if (btnEditar) {
            btnEditar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                tecnologiaSeleccionada = tecnologiasActuales.find(t => t.id_tendencia == id);
                if (!tecnologiaSeleccionada) return;
                
                const areaSelect = document.getElementById('area-tecnologia');
                if (areaSelect && areasActuales.length > 0) {
                    areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                    areasActuales.forEach(area => {
                        areaSelect.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                    });
                }
                
                if (textareaDescripcionEditar) textareaDescripcionEditar.value = tecnologiaSeleccionada.descripcion;
                if (areaSelect) areaSelect.value = tecnologiaSeleccionada.id_area;
                if (selectEstadoEditar) selectEstadoEditar.value = tecnologiaSeleccionada.estado == 1 ? 'activo' : 'inactivo';
                
                if (modalEditar) modalEditar.setAttribute('data-id', id);
                abrirModalEditar(id, tecnologiaSeleccionada.descripcion.substring(0, 50), tecnologiaSeleccionada.descripcion);
            });
        }
        
        if (switchEl) {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                const nombre = tarjeta.querySelector('h3').textContent;
                const estaActivo = this.classList.contains('active');
                
                if (estaActivo) {
                    abrirModalDeshabilitar(id, nombre);
                } else {
                    abrirModalHabilitar(id, nombre);
                }
            });
        }
        
        tarjeta.addEventListener('click', function(e) {
            if (e.target.closest('.btn-editar-tendencia') || e.target.closest('.switch-sena')) return;
            const id = parseInt(tarjeta.getAttribute('data-id'));
            const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == id);
            if (!tecnologia) return;
            const nombre = tecnologia.descripcion.substring(0, 50);
            const estado = tecnologia.estado == 1 ? 'activo' : 'inactivo';
            abrirModalDetalle(id, nombre, tecnologia.descripcion, estado);
        });
    }

    // ===== EVENTOS PRINCIPALES =====
    
    cargarTecnologias();
    cargarAreas();
    
    const buscador = document.querySelector('input[placeholder*="Buscar tecnologías"]');
    if (buscador) buscador.addEventListener('input', filtrarTecnologias);
    
    if (btnAbrirModalCrear) btnAbrirModalCrear.addEventListener('click', abrirModalCrear);
    
    botonesCerrarCrear.forEach(boton => boton.addEventListener('click', cerrarModalCrear));
    if (modalCrear) modalCrear.addEventListener('click', function(e) {
        if (e.target === modalCrear || e.target.classList.contains('bg-black')) cerrarModalCrear();
    });
    
    botonesCerrarEditar.forEach(boton => boton.addEventListener('click', cerrarModalEditar));
    if (modalEditar) modalEditar.addEventListener('click', function(e) {
        if (e.target === modalEditar || e.target.classList.contains('bg-black')) cerrarModalEditar();
    });
    
    botonesCerrarDetalle.forEach(boton => boton.addEventListener('click', cerrarModalDetalle));
    if (modalDetalle) modalDetalle.addEventListener('click', function(e) {
        if (e.target === modalDetalle || e.target.classList.contains('bg-black')) cerrarModalDetalle();
    });
    
    botonesCerrarDeshabilitar.forEach(boton => boton.addEventListener('click', cerrarModalDeshabilitar));
    if (modalDeshabilitar) modalDeshabilitar.addEventListener('click', function(e) {
        if (e.target === modalDeshabilitar || e.target.classList.contains('bg-black')) cerrarModalDeshabilitar();
    });
    
    botonesCerrarHabilitar.forEach(boton => boton.addEventListener('click', cerrarModalHabilitar));
    if (modalHabilitar) modalHabilitar.addEventListener('click', function(e) {
        if (e.target === modalHabilitar || e.target.classList.contains('bg-black')) cerrarModalHabilitar();
    });
    
    botonesCerrarConfirmacionCrear.forEach(boton => boton.addEventListener('click', cerrarModalConfirmacionCrear));
    if (modalConfirmacionCrear) modalConfirmacionCrear.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionCrear || e.target.classList.contains('bg-black')) cerrarModalConfirmacionCrear();
    });
    
    botonesCerrarConfirmacionEditar.forEach(boton => boton.addEventListener('click', cerrarModalConfirmacionEditar));
    if (modalConfirmacionEditar) modalConfirmacionEditar.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionEditar || e.target.classList.contains('bg-black')) cerrarModalConfirmacionEditar();
    });
    
    botonesCerrarConfirmacionDeshabilitar.forEach(boton => boton.addEventListener('click', cerrarModalConfirmacionDeshabilitar));
    if (modalConfirmacionDeshabilitar) modalConfirmacionDeshabilitar.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionDeshabilitar || e.target.classList.contains('bg-black')) cerrarModalConfirmacionDeshabilitar();
    });
    
    botonesCerrarConfirmacionHabilitar.forEach(boton => boton.addEventListener('click', cerrarModalConfirmacionHabilitar));
    if (modalConfirmacionHabilitar) modalConfirmacionHabilitar.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionHabilitar || e.target.classList.contains('bg-black')) cerrarModalConfirmacionHabilitar();
    });
    
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            const areaSelect = this.querySelector('select[name="area"]');
            const descripcionTextarea = this.querySelector('textarea[name="descripcion"]');
            
            const id_area = areaSelect.value;
            const descripcion = descripcionTextarea.value.trim();
            
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            if (!descripcion) {
                mostrarToastValidacion('La descripción de la tecnología es requerida', 'warning');
                return;
            }
            
            const existe = await verificarDescripcionExistente(descripcion, id_area);
            if (existe) {
                mostrarToastValidacion('Ya existe una tecnología con esa descripción en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await crearTecnologia({
                id_area: parseInt(id_area),
                descripcion: descripcion,
                estado: 1
            });
            
            if (resultado.success) {
                cerrarModalCrear();
                abrirModalConfirmacionCrear(descripcion.substring(0, 50) + (descripcion.length > 50 ? '...' : ''));
            } else {
                mostrarToastValidacion(resultado.error || 'Error al crear la tecnología', 'error');
            }
        });
    }
    
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            const id_tendencia = modalEditar.getAttribute('data-id') || tecnologiaEditadaId;
            const id_area = document.getElementById('area-tecnologia').value;
            const descripcion = document.getElementById('descripcion-editar').value.trim();
            const estado = document.getElementById('estado-tecnologia').value === 'activo' ? 1 : 0;
            
            if (!descripcion) {
                mostrarToastValidacion('La descripción de la tecnología es requerida', 'warning');
                return;
            }
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            
            const datosOriginales = tecnologiaSeleccionada;
            const descripcionCambio = descripcion !== datosOriginales?.descripcion;
            const areaCambio = parseInt(id_area) !== datosOriginales?.id_area;
            const estadoCambio = estado !== datosOriginales?.estado;
            
            if (!descripcionCambio && !areaCambio && !estadoCambio) {
                mostrarToastValidacion('No se ha realizado ningún cambio en la tecnología', 'info');
                return;
            }
            
            const existe = await verificarDescripcionExistente(descripcion, id_area, id_tendencia);
            if (existe) {
                mostrarToastValidacion('Ya existe una tecnología con esa descripción en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await actualizarTecnologia({
                id_tendencia: parseInt(id_tendencia),
                id_area: parseInt(id_area),
                descripcion: descripcion,
                estado: estado
            });
            
            if (resultado.success) {
                cerrarModalEditar();
                abrirModalConfirmacionEditar(descripcion.substring(0, 50) + (descripcion.length > 50 ? '...' : ''));
            } else {
                mostrarToastValidacion(resultado.error || 'Error al actualizar la tecnología', 'error');
            }
        });
    }
    
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            if (tecnologiaDeshabilitarId && tecnologiaDeshabilitarNombre) {
                const resultado = await cambiarEstadoTecnologia(tecnologiaDeshabilitarId, 'desactivar');
                if (resultado.success) {
                    cerrarModalDeshabilitar();
                    abrirModalConfirmacionDeshabilitar(tecnologiaDeshabilitarNombre);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al deshabilitar la tecnología', 'error');
                }
            }
        });
    }
    
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            if (tecnologiaHabilitarId && tecnologiaHabilitarNombre) {
                const resultado = await cambiarEstadoTecnologia(tecnologiaHabilitarId, 'activar');
                if (resultado.success) {
                    cerrarModalHabilitar();
                    abrirModalConfirmacionHabilitar(tecnologiaHabilitarNombre);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al habilitar la tecnología', 'error');
                }
            }
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modalConfirmacionCrear && !modalConfirmacionCrear.classList.contains('hidden')) cerrarModalConfirmacionCrear();
            else if (modalConfirmacionEditar && !modalConfirmacionEditar.classList.contains('hidden')) cerrarModalConfirmacionEditar();
            else if (modalConfirmacionDeshabilitar && !modalConfirmacionDeshabilitar.classList.contains('hidden')) cerrarModalConfirmacionDeshabilitar();
            else if (modalConfirmacionHabilitar && !modalConfirmacionHabilitar.classList.contains('hidden')) cerrarModalConfirmacionHabilitar();
            else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) cerrarModalDeshabilitar();
            else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) cerrarModalHabilitar();
            else if (modalDetalle && !modalDetalle.classList.contains('hidden')) cerrarModalDetalle();
            else if (modalEditar && !modalEditar.classList.contains('hidden')) cerrarModalEditar();
            else if (modalCrear && !modalCrear.classList.contains('hidden')) cerrarModalCrear();
        }
    });
});