document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    const API_URL = '../../controllers/ProyeccionFuturoController.php';
    
    // ===== VARIABLES DE MODALES =====
    const modalCrear = document.getElementById('modal-crear-proyeccion-futuro');
    const modalConfirmacionCrear = document.getElementById('modal-creado-confirmacion-proyeccion-futura');
    const modalEditar = document.getElementById('modal-editar-proyeccion-futuro');
    const modalConfirmacionEditar = document.getElementById('modal-editado-confirmacion');
    const modalDetalle = document.getElementById('modal-detalle-proyeccion-futuro');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-proyeccion-futura');
    const modalConfirmacionDeshabilitar = document.getElementById('modal-deshabilitado-proyeccion-futuro');
    const modalHabilitar = document.getElementById('modal-habilitar-proyeccion-futura');
    const modalConfirmacionHabilitar = document.getElementById('modal-habilitado-confirmacion-proyeccion_futura');
    
    // Elementos del modal de creación
    const btnAbrirModalCrear = document.getElementById('btn-crear-proyeccion');
    const botonesCerrarCrear = document.querySelectorAll('.cerrar-modal-crear');
    const formCrear = document.getElementById('form-nueva-proyeccion-futuro');
    const btnSubmitCrear = document.getElementById('btn-submit-proyeccion');
    
    // Elementos del modal de edición
    const botonesCerrarEditar = document.querySelectorAll('.cerrar-modal-editar');
    const formEditar = document.getElementById('form-editar-proyeccion-futuro');
    const selectAreaEditar = document.getElementById('area-proyeccion-editar');
    const selectAnioEditar = document.getElementById('anio-proyeccion-editar');
    const textareaNombreEditar = document.getElementById('nombre-proyeccion-editar');
    
    // Elementos del modal de detalle
    const botonesCerrarDetalle = document.querySelectorAll('.cerrar-modal-detalle');
    const detalleTitulo = document.getElementById('detalle-titulo');
    const detalleNombre = document.getElementById('detalle-nombre-proyeccion_futuro');
    const detalleDescripcion = document.getElementById('detalle-descripcion-proyeccion_futuro');
    const detalleEstadoTexto = document.getElementById('detalle-estado-proyeccion_futuro');
    const detalleEstadoBadge = document.getElementById('detalle-estado-badge');
    const detalleEstadoIndicador = document.getElementById('detalle-estado-indicador');
    const detalleArea = document.getElementById('detalle-area-proyeccion');
    const detalleAnio = document.getElementById('detalle-anio-proyeccion');
    
    // Elementos del modal de deshabilitar
    const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const spanProyeccionDeshabilitar = document.getElementById('nombre-proyeccion-deshabilitar');
    
    // Elementos del modal de confirmación de deshabilitar
    const botonesCerrarConfirmacionDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitado');
    let nombreProyeccionDeshabilitadaSpan = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
    const contadorSegundosDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
    const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');
    
    // Elementos del modal de habilitar
    const botonesCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const spanProyeccionHabilitar = document.getElementById('nombre-proyeccion-habilitar');
    
    // Elementos del modal de confirmación de habilitar
    const botonesCerrarConfirmacionHabilitar = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const nombreProyeccionHabilitadaSpan = document.getElementById('nombre-proyeccion-habilitado-exito');
    const contadorSegundosHabilitado = document.getElementById('contador-segundos');
    const progressBarHabilitado = document.getElementById('progress-bar-habilitado');
    
    // Elementos del modal de confirmación de creación
    const botonesCerrarConfirmacionCrear = document.querySelectorAll('.cerrar-modal-creado');
    const nombreProyeccionSpan = document.getElementById('nombre-proyeccion-creada');
    const contadorSegundosCrear = document.getElementById('contador-segundos-creado');
    const progressBarCrear = document.getElementById('progress-bar-creado');
    
    // Elementos del modal de confirmación de edición
    const botonesCerrarConfirmacionEditar = document.querySelectorAll('.cerrar-modal-editado');
    const nombreProyeccionEditadaSpan = document.getElementById('nombre-proyeccion-editado');
    const contadorSegundosEditar = document.getElementById('contador-segundos-editado');
    const progressBarEditar = document.getElementById('progress-bar-editado');
    
    // Variables para almacenar datos
    let proyeccionesActuales = [];
    let areasActuales = [];
    let proyeccionSeleccionada = null;
    let proyeccionEditadaId = null;
    let proyeccionDeshabilitarId = null;
    let proyeccionDeshabilitarNombre = null;
    let proyeccionHabilitarId = null;
    let proyeccionHabilitarNombre = null;
    
    let timeoutId = null;
    let intervalId = null;
    
    // Variables para paginación
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let proyeccionesFiltradas = [];
    let ultimoTerminoBusqueda = '';
    let filtrarDesactivadosActivo = false;
    
    // Variables para almacenar valores originales en edición
    let valoresOriginalesEdicion = {
        id_area: null,
        anio: null,
        nombre: null
    };
    
    // ===== CONSTANTES =====
    const MIN_DESCRIPCION_LENGTH = 30;
    
    // ===== FUNCIÓN PARA VALIDAR DESCRIPCIÓN =====
    function validarDescripcion(descripcion) {
        if (!descripcion || descripcion.length < MIN_DESCRIPCION_LENGTH) {
            return false;
        }
        return true;
    }
    
    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL CREAR =====
    function actualizarContadorCrear() {
        const textarea = document.getElementById('nombre-crear');
        const contadorSpan = document.getElementById('contador-caracteres-crear');
        const alertaSpan = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.getElementById('btn-submit-proyeccion');
        
        if (textarea && contadorSpan) {
            const longitud = textarea.value.length;
            contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contadorSpan.classList.remove('text-sena-text-soft', 'text-red-500');
                contadorSpan.classList.add('text-sena');
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                contadorSpan.classList.remove('text-sena', 'text-red-500');
                contadorSpan.classList.add('text-sena-text-soft');
                if (alertaSpan) alertaSpan.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        }
    }

    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL EDITAR =====
    function actualizarContadorEditar() {
        const textarea = document.getElementById('nombre-proyeccion-editar');
        const contadorSpan = document.getElementById('contador-caracteres-editar');
        const alertaSpan = document.getElementById('alerta-minimo-editar');
        const btnSubmit = document.getElementById('btn-submit-editar-proyeccion');
        
        if (textarea && contadorSpan) {
            const longitud = textarea.value.length;
            contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contadorSpan.classList.remove('text-sena-text-soft', 'text-red-500');
                contadorSpan.classList.add('text-sena');
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                contadorSpan.classList.remove('text-sena', 'text-red-500');
                contadorSpan.classList.add('text-sena-text-soft');
                if (alertaSpan) alertaSpan.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        }
    }
    
    // ===== FUNCIONES DE API =====
    async function cargarProyecciones() {
        try {
            const response = await fetch(`${API_URL}?accion=listarTodas`);
            const data = await response.json();
            
            if (data.status === 'success') {
                proyeccionesActuales = data.data;
                renderizarProyecciones(proyeccionesActuales);
                actualizarContador(proyeccionesActuales.length);
            }
        } catch (error) {
            console.error('Error al cargar proyecciones:', error);
            mostrarToastValidacion('Error al cargar las proyecciones', 'error');
        }
    }
    
    async function cargarAreas() {
        try {
            const response = await fetch('../../controllers/AreaController.php?accion=listar');
            const data = await response.json();
            
            if (data.status === 'success') {
                areasActuales = data.data;
                
                const selectCrear = document.querySelector('#form-nueva-proyeccion-futuro select[name="area"]');
                const selectEditar = document.getElementById('area-proyeccion-editar');
                
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
    
    async function cargarAnios() {
        try {
            const response = await fetch(`${API_URL}?accion=listaAnios`);
            const data = await response.json();
            
            if (data.success) {
                const selectCrear = document.querySelector('#form-nueva-proyeccion-futuro select[name="anio"]');
                const selectEditar = document.getElementById('anio-proyeccion-editar');
                
                const aniosHTML = '<option value="">Seleccione un período</option>' + 
                    data.data.map(anio => `<option value="${anio.value}">${anio.label}</option>`).join('');
                
                if (selectCrear) selectCrear.innerHTML = aniosHTML;
                if (selectEditar) selectEditar.innerHTML = aniosHTML;
            }
        } catch (error) {
            console.error('Error al cargar años:', error);
        }
    }
    
    async function crearProyeccion(data) {
        try {
            const response = await fetch(`${API_URL}?accion=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_area: data.id_area,
                    anio: data.anio,
                    nombre: data.nombre,
                    estado: 1
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const area = areasActuales.find(a => a.id_area == data.id_area);
                const nuevaProyeccion = {
                    id_proyeccion: result.id_proyeccion,
                    id_area: data.id_area,
                    anio: data.anio,
                    nombre: data.nombre,
                    estado: 1,
                    nombre_area: area ? area.nombre_area : 'Área',
                    anio_texto: obtenerTextoAnio(data.anio)
                };
                
                proyeccionesActuales.push(nuevaProyeccion);
                renderizarProyecciones(proyeccionesActuales);
                actualizarContador(proyeccionesActuales.length);
                return { success: true, id: result.id_proyeccion };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al crear proyección:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }
    
    async function actualizarProyeccion(data) {
        try {
            const response = await fetch(`${API_URL}?accion=actualizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_proyeccion: data.id_proyeccion,
                    id_area: data.id_area,
                    anio: data.anio,
                    nombre: data.nombre,
                    estado: data.estado
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const index = proyeccionesActuales.findIndex(p => p.id_proyeccion == data.id_proyeccion);
                if (index !== -1) {
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    proyeccionesActuales[index] = {
                        ...proyeccionesActuales[index],
                        nombre: data.nombre,
                        id_area: data.id_area,
                        anio: data.anio,
                        estado: data.estado,
                        nombre_area: area ? area.nombre_area : 'Área',
                        anio_texto: obtenerTextoAnio(data.anio)
                    };
                }
                
                renderizarProyecciones(proyeccionesActuales);
                actualizarContador(proyeccionesActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al actualizar proyección:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }
    
    async function cambiarEstadoProyeccion(id, accion) {
        try {
            const response = await fetch(`${API_URL}?accion=${accion}&id_proyeccion=${id}`, {
                method: 'POST'
            });
            
            const result = await response.json();
            
            if (result.success) {
                const index = proyeccionesActuales.findIndex(p => p.id_proyeccion == id);
                if (index !== -1) {
                    proyeccionesActuales[index].estado = accion === 'activar' ? 1 : 0;
                }
                
                renderizarProyecciones(proyeccionesActuales);
                actualizarContador(proyeccionesActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error(`Error al ${accion} proyección:`, error);
            return { success: false, error: 'Error de conexión' };
        }
    }
    
    // ===== FUNCIONES UTILITARIAS =====
    function obtenerTextoAnio(anio) {
        const anios = {
            '1': '1 año',
            '2': '2 años',
            '3': '3 años',
            '4': '4 años',
            '5': '5 años',
            '6': '6 años',
            '7': '7 años',
            '8': '8 años',
            '9': '9 años',
            '10': '10 años'
        };
        return anios[anio] || `${anio} años`;
    }
    
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
            info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
            success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
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
    
    // ===== FUNCIONES VISUALES =====
    function renderizarProyecciones(proyecciones) {
        const contenedor = document.getElementById('contenedor-proyecciones');
        const terminoBusqueda = document.getElementById('buscador-proyecciones')?.value.trim() || '';
        
        if (!contenedor) return;
        
        let filtradas = proyecciones;
        if (terminoBusqueda) {
            filtradas = filtradas.filter(p => 
                p.nombre.toLowerCase().includes(terminoBusqueda) ||
                (p.nombre_area && p.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        // Filtrar por estado (desactivados)
        if (filtrarDesactivadosActivo) {
            filtradas = filtradas.filter(p => p.estado != 1);
        }
        
        // Ordenar proyecciones: primero habilitadas (estado = 1), luego deshabilitadas (estado = 0)
        // Dentro de cada grupo, ordenar por ID descendente (más reciente primero)
        filtradas.sort((a, b) => {
            // Si tienen diferente estado, las habilitadas van primero
            if (a.estado !== b.estado) {
                return b.estado - a.estado; // 1 viene antes que 0
            }
            // Si tienen el mismo estado, ordenar por ID descendente (más reciente primero)
            return b.id_proyeccion - a.id_proyeccion;
        });
        
        proyeccionesFiltradas = filtradas;
        
        if (terminoBusqueda !== ultimoTerminoBusqueda) {
            paginaActual = 1;
            ultimoTerminoBusqueda = terminoBusqueda;
        }
        
        contenedor.innerHTML = '';
        
        if (filtradas.length === 0) {
            const hayBusquedaActiva = terminoBusqueda.length > 0;
            
            contenedor.className = '';
            
            if (hayBusquedaActiva || filtrarDesactivadosActivo) {
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <h3 class=" text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm">
                            ${filtrarDesactivadosActivo ? 'No hay proyecciones deshabilitadas.' : `No hay proyecciones que coincidan con "${terminoBusqueda}".`}
                        </p>
                    </div>
                `;
            } else {
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-sena">
                                <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                <polyline points="12 6 12 12 16 14" stroke="currentColor"/>
                            </svg>
                        </div>
                        <h3 class=" text-lg font-semibold text-sena-text-main mb-2">No hay proyecciones a futuro</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primera proyección a futuro.</p>
                        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Crear primera proyección
                        </button>
                    </div>
                `;
                
                const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
                if (btnCrearEmpty) {
                    btnCrearEmpty.addEventListener('click', abrirModalCrear);
                }
            }
            
            const paginacionContainer = document.getElementById('paginacion-container');
            if (paginacionContainer) paginacionContainer.classList.add('hidden');
            return;
        }
        
        // Asegurar que la página actual no exceda el total de páginas
        const totalPaginas = Math.ceil(filtradas.length / elementosPorPagina);
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }
        
        contenedor.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-stretch';
        
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const proyeccionesPagina = filtradas.slice(inicio, fin);
        
        proyeccionesPagina.forEach(proyeccion => {
            const tarjeta = crearTarjetaHTML(proyeccion);
            contenedor.appendChild(tarjeta);
        });
        
        asignarEventosTarjetas();
        actualizarContador(filtradas.length);
        actualizarPaginacion(filtradas.length);
    }
    
    function crearTarjetaHTML(proyeccion) {
        const div = document.createElement('div');
        const estaDeshabilitada = proyeccion.estado != 1;
        const opacidadClass = estaDeshabilitada ? 'opacity-60' : '';
        
        div.className = `border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-proyeccion flex flex-col h-full ${opacidadClass}`;
        div.setAttribute('data-id', proyeccion.id_proyeccion);
        div.setAttribute('data-area', proyeccion.id_area);
        div.setAttribute('data-nombre', proyeccion.nombre);
        div.setAttribute('data-descripcion', proyeccion.nombre);
        div.setAttribute('data-estado', proyeccion.estado == 1 ? 'activo' : 'inactivo');
        div.setAttribute('data-anio', proyeccion.anio);
        
        div.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                        <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                        <polyline points="12 6 12 12 16 14" stroke="currentColor"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="${proyeccion.id_proyeccion}" data-nombre="${proyeccion.nombre.replace(/"/g, '&quot;')}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${proyeccion.estado == 1 ? 'active' : ''}" title="${proyeccion.estado == 1 ? 'Activo' : 'Inactivo'}" data-id="${proyeccion.id_proyeccion}" data-nombre="${proyeccion.nombre.replace(/"/g, '&quot;')}"></div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-sena-text-soft line-clamp-3">${proyeccion.nombre}</p>
            </div>
            <div class="mt-2 text-xs text-sena-text-soft">
                <hr class="my-1 border-sena-border mb-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${proyeccion.estado == 1 ? 'bg-sena' : 'bg-gray-400'}"></span>
                        <span class="badge-area">${proyeccion.nombre_area || 'Área'}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <span class="text-xs text-sena-text-soft">${proyeccion.anio_texto || obtenerTextoAnio(proyeccion.anio)}</span>
                    </div>
                </div>
            </div>
        `;
        
        return div;
    }
    
    function actualizarContador(total) {
        const contador = document.getElementById('total-proyecciones');
        if (contador) {
            contador.textContent = total;
        }
    }
    
    function actualizarPaginacion(totalElementos) {
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        const paginacionContainer = document.getElementById('paginacion-container');
        
        if (!paginacionContainer) return;
        
        // Mostrar paginación si:
        // 1. Hay más de 1 página, O
        // 2. El filtro de deshabilitados está activo (para mostrar el botón de filtro)
        if (totalPaginas <= 1 && !filtrarDesactivadosActivo) {
            paginacionContainer.classList.add('hidden');
            return;
        }
        
        paginacionContainer.classList.remove('hidden');
        
        // Generar los botones de páginas con elipsis
        let paginasHTML = '';
        
        // Determinar qué páginas mostrar
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);
        
        // Ajustar si estamos al inicio
        if (paginaActual <= 3) {
            fin = Math.min(5, totalPaginas);
        }
        
        // Ajustar si estamos al final
        if (paginaActual >= totalPaginas - 2) {
            inicio = Math.max(totalPaginas - 4, 1);
        }
        
        // Solo mostrar botones de páginas si hay más de 1 página
        if (totalPaginas > 1) {
            // Primera página y elipsis al inicio
            if (inicio > 1) {
                paginasHTML += `
                    <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="1">
                        1
                    </button>
                `;
                if (inicio > 2) {
                    paginasHTML += `
                        <span class="px-2 text-sena-text-soft">...</span>
                    `;
                }
            }
            
            // Páginas intermedias
            for (let i = inicio; i <= fin; i++) {
                const isActive = paginaActual === i;
                paginasHTML += `
                    <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        isActive 
                            ? 'bg-sena text-white shadow-md scale-100' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" data-pagina="${i}">
                        ${i}
                    </button>
                `;
            }
            
            // Última página y elipsis al final
            if (fin < totalPaginas) {
                if (fin < totalPaginas - 1) {
                    paginasHTML += `
                        <span class="px-2 text-sena-text-soft">...</span>
                    `;
                }
                paginasHTML += `
                    <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="${totalPaginas}">
                        ${totalPaginas}
                    </button>
                `;
            }
        }
        
        // Construir el HTML completo de paginación
        let paginacionHTML = `
            <div class="flex flex-col items-center gap-3 mb-6">
                <!-- Información de página - solo mostrar si hay más de 1 página -->
                ${totalPaginas > 1 ? `
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${((paginaActual - 1) * elementosPorPagina) + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> proyecciones
                </div>
                ` : `
                <div class="text-sm text-sena-text-soft">
                    Total: <span class="font-medium text-sena">${totalElementos}</span> proyecciones ${filtrarDesactivadosActivo ? 'deshabilitadas' : ''}
                </div>
                `}
                
                <!-- Controles de paginación y filtro -->
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    <!-- Botón de filtro desactivados - SIN HOVER cuando está activo -->
                    <button id="btn-filtro-desactivados-paginacion" class="flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-lg whitespace-nowrap h-10 transition-all ${filtrarDesactivadosActivo 
                        ? 'bg-sena text-white border-sena cursor-default' 
                        : 'border-sena-border text-sena-text-soft hover:bg-sena-soft hover:border-sena/30'}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M3 7v10a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V7"/>
                            <path d="M3 7h18M8 3h8M6 7h1M17 7h1"/>
                        </svg>
                        Proyecciones Inactivas
                    </button>
                    
                    <!-- Solo mostrar controles de navegación si hay más de 1 página -->
                    ${totalPaginas > 1 ? `
                    <!-- Separador visual -->
                    <div class="w-px h-6 bg-sena-border"></div>
                    <!-- Primera página -->
                    <button class="btn-primera-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Anterior -->
                    <button class="btn-pagina-anterior px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Botones de páginas -->
                    ${paginasHTML}
                    
                    <!-- Siguiente -->
                    <button class="btn-pagina-siguiente px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    
                    <!-- Última página -->
                    <button class="btn-ultima-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M6 5l7 7-7 7" />
                        </svg>
                    </button>
                    ` : ''}
                </div>
            </div>
        `;
        
        paginacionContainer.innerHTML = paginacionHTML;
        
        // Event listener para el botón de filtro desactivados en la paginación
        const btnFiltroDesactivadosPaginacion = document.getElementById('btn-filtro-desactivados-paginacion');
        if (btnFiltroDesactivadosPaginacion) {
            // Remover event listeners existentes para evitar duplicados
            const newBtn = btnFiltroDesactivadosPaginacion.cloneNode(true);
            btnFiltroDesactivadosPaginacion.parentNode.replaceChild(newBtn, btnFiltroDesactivadosPaginacion);
            
            newBtn.addEventListener('click', function() {
                filtrarDesactivadosActivo = !filtrarDesactivadosActivo;
                
                // Cambiar estilo del botón
                if (filtrarDesactivadosActivo) {
                    this.classList.add('bg-sena', 'text-white', 'border-sena', 'cursor-default');
                    this.classList.remove('border-sena-border', 'text-sena-text-soft', 'hover:bg-sena-soft', 'hover:border-sena/30');
                } else {
                    this.classList.remove('bg-sena', 'text-white', 'border-sena', 'cursor-default');
                    this.classList.add('border-sena-border', 'text-sena-text-soft', 'hover:bg-sena-soft', 'hover:border-sena/30');
                }
                
                paginaActual = 1;
                filtrarProyecciones();
            });
        }
        
        // Solo agregar eventos de paginación si hay más de 1 página
        if (totalPaginas > 1) {
            document.querySelectorAll('.btn-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    paginaActual = parseInt(btn.dataset.pagina);
                    renderizarProyecciones(proyeccionesFiltradas);
                });
            });
            
            document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual > 1) {
                        paginaActual--;
                        renderizarProyecciones(proyeccionesFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual < totalPaginas) {
                        paginaActual++;
                        renderizarProyecciones(proyeccionesFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== 1) {
                        paginaActual = 1;
                        renderizarProyecciones(proyeccionesFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== totalPaginas) {
                        paginaActual = totalPaginas;
                        renderizarProyecciones(proyeccionesFiltradas);
                    }
                });
            });
        }
    }
    
    function filtrarProyecciones() {
        const terminoBusqueda = document.getElementById('buscador-proyecciones').value.toLowerCase();
        
        let filtradas = proyeccionesActuales;
        
        // Filtrar por término de búsqueda
        if (terminoBusqueda) {
            filtradas = filtradas.filter(p => 
                p.nombre.toLowerCase().includes(terminoBusqueda) ||
                (p.nombre_area && p.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        // Filtrar por estado (desactivados)
        if (filtrarDesactivadosActivo) {
            filtradas = filtradas.filter(p => p.estado != 1);
        }
        
        paginaActual = 1;
        renderizarProyecciones(filtradas);
    }
    
    // ===== FUNCIONES DE MODALES =====
    function abrirModalCrear() {
        if (formCrear) formCrear.reset();
        // Resetear el contador al abrir el modal
        const contadorSpan = document.getElementById('contador-caracteres-crear');
        const alertaSpan = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.getElementById('btn-submit-proyeccion');
        const textarea = document.getElementById('nombre-crear');
        
        if (textarea) {
            textarea.value = '';
        }
        
        if (contadorSpan) {
            contadorSpan.classList.remove('text-red-500', 'text-sena');
            contadorSpan.classList.add('text-sena-text-soft');
            contadorSpan.textContent = `0 / ${MIN_DESCRIPCION_LENGTH} caracteres`;
        }
        
        if (alertaSpan) {
            alertaSpan.classList.add('hidden');
        }
        
        if (btnSubmit) {
            btnSubmit.disabled = false;
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
    
    function abrirModalEditar(id) {
        const proyeccion = proyeccionesActuales.find(p => p.id_proyeccion == id);
        if (!proyeccion) return;
        
        proyeccionEditadaId = id;
        proyeccionSeleccionada = proyeccion;
        
        // Guardar valores originales para comparar después
        valoresOriginalesEdicion.id_area = proyeccion.id_area;
        valoresOriginalesEdicion.anio = proyeccion.anio;
        valoresOriginalesEdicion.nombre = proyeccion.nombre;
        
        if (textareaNombreEditar) textareaNombreEditar.value = proyeccion.nombre;
        if (selectAreaEditar) selectAreaEditar.value = proyeccion.id_area;
        if (selectAnioEditar) selectAnioEditar.value = proyeccion.anio;
        
        // Actualizar el contador cuando se abre el modal
        actualizarContadorEditar();
        
        modalEditar.classList.remove('hidden');
        modalEditar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }
    
    function cerrarModalEditar() {
        modalEditar.classList.add('hidden');
        document.body.style.overflow = '';
        if (formEditar) formEditar.reset();
        proyeccionEditadaId = null;
        // Resetear valores originales
        valoresOriginalesEdicion = {
            id_area: null,
            anio: null,
            nombre: null
        };
    }
    
    function abrirModalDetalle(id) {
        const proyeccion = proyeccionesActuales.find(p => p.id_proyeccion == id);
        if (!proyeccion) return;
        
        if (detalleTitulo) detalleTitulo.textContent = `Detalle: ${proyeccion.nombre}`;
        if (detalleNombre) detalleNombre.textContent = proyeccion.nombre_area || 'Área no especificada';
        if (detalleDescripcion) detalleDescripcion.textContent = proyeccion.nombre;
        if (detalleArea) detalleArea.textContent = proyeccion.nombre_area || 'Área no especificada';
        if (detalleAnio) detalleAnio.textContent = proyeccion.anio_texto || obtenerTextoAnio(proyeccion.anio);
        
        const estadoTexto = proyeccion.estado == 1 ? 'Activo' : 'Inactivo';
        if (detalleEstadoTexto) detalleEstadoTexto.textContent = estadoTexto;
        
        if (detalleEstadoBadge) {
            if (proyeccion.estado == 1) {
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
        proyeccionDeshabilitarId = id;
        proyeccionDeshabilitarNombre = nombre;
        
        if (spanProyeccionDeshabilitar) {
            spanProyeccionDeshabilitar.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitar.classList.remove('hidden');
        modalDeshabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }
    
    function cerrarModalDeshabilitar() {
        modalDeshabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        proyeccionDeshabilitarId = null;
        proyeccionDeshabilitarNombre = null;
    }
    
    function abrirModalHabilitar(id, nombre) {
        proyeccionHabilitarId = id;
        proyeccionHabilitarNombre = nombre;
        if (spanProyeccionHabilitar) {
            spanProyeccionHabilitar.textContent = `"${nombre}"`;
        }
        modalHabilitar.classList.remove('hidden');
        modalHabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
    }
    
    function cerrarModalHabilitar() {
        modalHabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        proyeccionHabilitarId = null;
        proyeccionHabilitarNombre = null;
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
    
    function abrirModalConfirmacionCrear(nombre) {
        if (nombreProyeccionSpan) nombreProyeccionSpan.textContent = `"${nombre}"`;
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
    
    function abrirModalConfirmacionEditar(nombre) {
        if (nombreProyeccionEditadaSpan) nombreProyeccionEditadaSpan.textContent = `"${nombre}"`;
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
    
    function abrirModalConfirmacionDeshabilitar(nombre) {
        if (!nombreProyeccionDeshabilitadaSpan) {
            nombreProyeccionDeshabilitadaSpan = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
        }
        
        if (nombreProyeccionDeshabilitadaSpan) {
            const textoFinal = nombre && nombre.trim() ? nombre : 'Proyección';
            nombreProyeccionDeshabilitadaSpan.textContent = `"${textoFinal}"`;
        }
        
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
    
    function abrirModalConfirmacionHabilitar(nombre) {
        if (!nombreProyeccionHabilitadaSpan) {
            nombreProyeccionHabilitadaSpan = document.getElementById('nombre-proyeccion-habilitado-exito');
        }
        
        if (nombreProyeccionHabilitadaSpan) {
            nombreProyeccionHabilitadaSpan.textContent = `"${nombre || 'Proyección'}"`;
        }
        
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
    
    function asignarEventosTarjetas() {
        document.querySelectorAll('.tarjeta-proyeccion').forEach(tarjeta => {
            asignarEventosTarjeta(tarjeta);
        });
    }
    
    function asignarEventosTarjeta(tarjeta) {
        const btnEditar = tarjeta.querySelector('.btn-editar-proyeccion');
        const switchEl = tarjeta.querySelector('.switch-sena');
        
        if (btnEditar) {
            btnEditar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                abrirModalEditar(id);
            });
        }
        
        if (switchEl) {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                // Obtener el nombre desde el h3 de la tarjeta (texto visible)
                const tituloElement = tarjeta.querySelector('h3');
                let nombre = tituloElement ? tituloElement.textContent : '';
                
                // Si no se encontró en h3, intentar desde el atributo data-nombre
                if (!nombre || nombre === '' || nombre === 'null') {
                    nombre = tarjeta.getAttribute('data-nombre');
                }
                
                // Si aún no hay nombre, intentar desde el switch
                if (!nombre || nombre === '' || nombre === 'null') {
                    nombre = switchEl.getAttribute('data-nombre');
                }
                
                // Limpiar el nombre por si tiene comillas extras
                nombre = nombre.replace(/^["']|["']$/g, '');
                
                const estaActivo = this.classList.contains('active');
                
                if (estaActivo) {
                    abrirModalDeshabilitar(id, nombre);
                } else {
                    abrirModalHabilitar(id, nombre);
                }
            });
        }
        
        tarjeta.addEventListener('click', function(e) {
            if (e.target.closest('.btn-editar-proyeccion') || e.target.closest('.switch-sena')) return;
            const id = parseInt(tarjeta.getAttribute('data-id'));
            abrirModalDetalle(id);
        });
    }
    
    // ===== EVENTOS PRINCIPALES =====
    cargarProyecciones();
    cargarAreas();
    cargarAnios();
    
    // Event listener para el contador de caracteres en creación
    const textareaCrear = document.getElementById('nombre-crear');
    if (textareaCrear) {
        textareaCrear.addEventListener('input', actualizarContadorCrear);
    }

    // Event listener para el contador de caracteres en edición
    const textareaEditar = document.getElementById('nombre-proyeccion-editar');
    if (textareaEditar) {
        textareaEditar.addEventListener('input', actualizarContadorEditar);
    }
    
    const buscador = document.getElementById('buscador-proyecciones');
    if (buscador) buscador.addEventListener('input', filtrarProyecciones);
    
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
            const anioSelect = this.querySelector('select[name="anio"]');
            const nombreTextarea = this.querySelector('textarea[name="nombre"]');
            
            const id_area = areaSelect.value;
            const anio = anioSelect.value;
            const nombre = nombreTextarea.value.trim();
            
            // Validaciones
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                areaSelect.classList.add('border-red-500');
                setTimeout(() => areaSelect.classList.remove('border-red-500'), 3000);
                return;
            }
            if (!anio) {
                mostrarToastValidacion('Debe seleccionar un período', 'warning');
                anioSelect.classList.add('border-red-500');
                setTimeout(() => anioSelect.classList.remove('border-red-500'), 3000);
                return;
            }
            if (!nombre) {
                mostrarToastValidacion('El nombre de la proyección es requerido', 'warning');
                nombreTextarea.classList.add('border-red-500');
                setTimeout(() => nombreTextarea.classList.remove('border-red-500'), 3000);
                return;
            }
            
            // Validar mínimo de caracteres
            if (!validarDescripcion(nombre)) {
                mostrarToastValidacion(`El nombre de la proyección debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres. Actualmente tiene ${nombre.length} caracteres.`, 'warning');
                nombreTextarea.classList.add('border-red-500');
                setTimeout(() => nombreTextarea.classList.remove('border-red-500'), 3000);
                return;
            }
            
            const resultado = await crearProyeccion({
                id_area: parseInt(id_area),
                anio: anio,
                nombre: nombre
            });
            
            if (resultado.success) {
                cerrarModalCrear();
                abrirModalConfirmacionCrear(nombre);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al crear la proyección', 'error');
            }
        });
    }
    
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id_proyeccion = proyeccionEditadaId;
            const id_area = selectAreaEditar.value;
            const anio = selectAnioEditar.value;
            const nombre = textareaNombreEditar.value.trim();
            
            // Verificar si se han realizado cambios
            const areaCambio = parseInt(id_area) !== valoresOriginalesEdicion.id_area;
            const anioCambio = anio !== valoresOriginalesEdicion.anio;
            const nombreCambio = nombre !== valoresOriginalesEdicion.nombre;
            
            // Si no hay cambios, mostrar alerta de información
            if (!areaCambio && !anioCambio && !nombreCambio) {
                mostrarToastValidacion('No se ha realizado ningún cambio en la proyección', 'info');
                return;
            }
            
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                selectAreaEditar.classList.add('border-red-500');
                setTimeout(() => selectAreaEditar.classList.remove('border-red-500'), 3000);
                return;
            }
            if (!anio) {
                mostrarToastValidacion('Debe seleccionar un período', 'warning');
                selectAnioEditar.classList.add('border-red-500');
                setTimeout(() => selectAnioEditar.classList.remove('border-red-500'), 3000);
                return;
            }
            if (!nombre) {
                mostrarToastValidacion('El nombre de la proyección es requerido', 'warning');
                textareaNombreEditar.classList.add('border-red-500');
                setTimeout(() => textareaNombreEditar.classList.remove('border-red-500'), 3000);
                return;
            }
            
            // Validar mínimo de caracteres en edición
            if (!validarDescripcion(nombre)) {
                mostrarToastValidacion(`El nombre de la proyección debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres. Actualmente tiene ${nombre.length} caracteres.`, 'warning');
                textareaNombreEditar.classList.add('border-red-500');
                setTimeout(() => textareaNombreEditar.classList.remove('border-red-500'), 3000);
                return;
            }
            
            const resultado = await actualizarProyeccion({
                id_proyeccion: parseInt(id_proyeccion),
                id_area: parseInt(id_area),
                anio: anio,
                nombre: nombre,
                estado: proyeccionSeleccionada?.estado || 1
            });
            
            if (resultado.success) {
                cerrarModalEditar();
                abrirModalConfirmacionEditar(nombre);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al actualizar la proyección', 'error');
            }
        });
    }
    
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            if (proyeccionDeshabilitarId && proyeccionDeshabilitarNombre) {
                const nombreParaConfirmacion = proyeccionDeshabilitarNombre;
                
                const resultado = await cambiarEstadoProyeccion(proyeccionDeshabilitarId, 'desactivar');
                if (resultado.success) {
                    cerrarModalDeshabilitar();
                    abrirModalConfirmacionDeshabilitar(nombreParaConfirmacion);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al deshabilitar la proyección', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la proyección', 'error');
            }
        });
    }
    
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            if (proyeccionHabilitarId && proyeccionHabilitarNombre) {
                const nombreParaConfirmacion = proyeccionHabilitarNombre;
                
                const resultado = await cambiarEstadoProyeccion(proyeccionHabilitarId, 'activar');
                if (resultado.success) {
                    cerrarModalHabilitar();
                    abrirModalConfirmacionHabilitar(nombreParaConfirmacion);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al habilitar la proyección', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la proyección', 'error');
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