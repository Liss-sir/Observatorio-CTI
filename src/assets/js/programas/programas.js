document.addEventListener("DOMContentLoaded", function () {
    // ===== REFERENCIAS A MODALES =====
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-confirmacion');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-confirmacion');
    const form = document.getElementById("formPrograma");   
    
    const nombreProgramaDeshabilitar = document.getElementById('nombre-programa-deshabilitar');
    const nombreProgramaHabilitar = document.getElementById('nombre-programa-habilitar');

    const btnCrearPrograma = document.getElementById("btn-abrir-crear-programa");
    const modalCrear = document.getElementById("modal-crear-programa");
    const modalEditar = document.getElementById("modal-editar-programa");
    const modalDetalle = document.getElementById("modal-detalle-programa");

    const AREA_URL = '../../controllers/AreaController.php';
    const PRO_URL = '../../controllers/ProgramaFormacionController.php';

    // ===== TIMERS =====
    let timeoutDeshabilitado = null, timeoutHabilitado = null;
    let timeoutCreado = null, timeoutEditado = null;
    let intervalContadorHabilitado = null, intervalContadorDeshabilitado = null;
    let intervalContadorEditado = null;
    let intervalContadorCreado = null;
    let programaPendienteNombre = null;

    // ===== NUEVO: VARIABLES PARA PAGINACIÓN =====
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let programaSeleccionado = null;
    let programasFiltrados = [];
    let ultimoTerminoBusqueda = '';

    // ===== NUEVO: VALIDACIÓN DE DESCRIPCIÓN =====
    const MIN_DESCRIPCION_LENGTH = 30;

    function validarDescripcion(descripcion) {
        if (!descripcion || descripcion.length < MIN_DESCRIPCION_LENGTH) {
            return false;
        }
        return true;
    }

    function actualizarContadorEditar() {
        const textarea = document.getElementById('descripcionProgramaEditar');
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

    function limpiarFormularioPrograma() {
        document.getElementById("areaPrograma").value = "";
        document.getElementById("codigoNuevoPrograma").value = "";
        document.getElementById("nombreNuevoPrograma").value = "";
        document.getElementById("nivelNuevoPrograma").value = "";
        document.getElementById("modalidadNuevoPrograma").value = "";
        document.getElementById("fechaInicioNuevoPrograma").value = "";
        document.getElementById("fechaFinNuevoPrograma").value = "";
        document.getElementById("cuposNuevoPrograma").value = "";
        document.getElementById("descripcionNuevoPrograma").value = "";
    }

    function actualizarContadorCrear() {
        const textarea = document.getElementById('descripcionNuevoPrograma');
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

    // ===== CARGAR ÁREAS =====
    async function cargarAreas() {
        try {
            const response = await fetch(`${AREA_URL}?accion=paraSelect`);
            const result = await response.json();
            return (result.success && result.data) ? result.data : [];
        } catch (error) {
            console.error('Error areas:', error);
            return [];
        }
    }

    // ===== LLENAR SELECT ÁREAS =====
    async function llenarSelectAreas(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        select.innerHTML = '<option value="">Cargando...</option>';
        select.disabled = true;
        
        try {
            const areas = await cargarAreas();
            select.innerHTML = '<option value="">Seleccione un area</option>';
            
            areas.forEach(area => {
                const option = document.createElement('option');
                option.value = area.id;
                option.textContent = area.text;
                select.appendChild(option);
            });
            select.disabled = false;
            
            const valorGuardado = select.dataset.valor;
            if (valorGuardado) select.value = valorGuardado;
        } catch (error) {
            select.innerHTML = '<option value="">Error</option>';
            select.disabled = false;
        }
    }

    // ===== CARGAR PROGRAMAS =====
    async function cargarProgramas() {
        const contenedor = document.getElementById('contenedorProgramas');
        if (!contenedor) return;
        
        contenedor.innerHTML = '<div class="col-span-3 text-center py-10"><p>Cargando...</p></div>';
        
        try {
            const response = await fetch(`${PRO_URL}?accion=listarTodas`);
            const result = await response.json();
            
            if (result.status === 'success' && result.data) {
                window.todosLosProgramas = result.data; // Guardar todos los programas
                paginaActual = 1; // Resetear a primera página
                renderizarProgramas(result.data);
            }
        } catch (error) {
            console.error('Error programas:', error);
            contenedor.innerHTML = '<div class="col-span-3 text-center py-10 text-red-500"><p>Error</p></div>';
        }
    }

     // ===== APLICAR FILTROS =====
    function aplicarFiltros() {
        const textoBusqueda = document.getElementById('buscador')?.value?.toLowerCase()?.trim() || '';
        const nivelFiltro = filtroNivel?.value || '';
        const modalidadFiltro = filtroModalidad?.value || '';
        const estadoFiltro = filtroEstado?.value || '';

        let programasFiltrados = todosLosProgramas.filter(programa => {
            // Filtro por texto (nombre o código)
            if (textoBusqueda && textoBusqueda.length >= 2) {
                const nombre = String(programa.nombre_programa || '').toLowerCase();
                const codigo = String(programa.codigo_programa || '').toLowerCase();
                if (!nombre.includes(textoBusqueda) && !codigo.includes(textoBusqueda)) {
                    return false;
                }
            }

            // Filtro por nivel
            if (nivelFiltro && String(programa.id_nivel) !== nivelFiltro) {
                return false;
            }

            // Filtro por modalidad
            if (modalidadFiltro && programa.modalidad !== modalidadFiltro) {
                return false;
            }

            // Filtro por estado
            if (estadoFiltro !== '' && String(programa.estado) !== estadoFiltro) {
                return false;
            }

            return true;
        });

        renderizarProgramas(programasFiltrados);
    }

    // ===== OBTENER NOMBRE NIVEL =====
    function getNombreNivel(idNivel) {
        const niveles = {
            '1': 'Técnico',
            '2': 'Tecnólogo',
            '3': 'Operario',
            '4': 'Auxiliar'
        };
        return niveles[idNivel] || 'N/A';
    }

    // ===== OBTENER COLOR NIVEL =====
    function getColorNivel(idNivel) {
        const colores = {
            '1': 'bg-green-100 text-green-800',
            '2': 'bg-green-100 text-green-800',
            '3': 'bg-green-100 text-green-800',
            '4': 'bg-green-100 text-green-800'
        };
        return colores[idNivel] || 'bg-gray-100 text-gray-800';
    }

    // ===== RENDERIZAR PROGRAMAS =====
    function renderizarProgramas(programas) {
        const contenedor = document.getElementById('contenedorProgramas');
        if (!contenedor) return;
        
        contenedor.innerHTML = '';
        
        const textoBusqueda = document.getElementById('buscador')?.value?.toLowerCase()?.trim() || '';
        
        // Filtrar programas
        let programasFiltrados = programas;
        if (textoBusqueda && textoBusqueda.length >= 2) {
            programasFiltrados = programas.filter(programa => {
                const nombre = String(programa.nombre_programa || '').toLowerCase();
                const codigo = String(programa.codigo_programa || '').toLowerCase();
                return nombre.includes(textoBusqueda) || codigo.includes(textoBusqueda);
            });
        }
        
        // Guardar programas filtrados para paginación
        window.programasFiltrados = programasFiltrados;
        
        if (!programasFiltrados || programasFiltrados.length === 0) {
            const hayBusquedaActiva = textoBusqueda.length >= 2;
            
            if (hayBusquedaActiva) {
                // No hay resultados de búsqueda
                contenedor.innerHTML = `
                    <div class="col-span-3 flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm">
                            No hay programas que coincidan con "${textoBusqueda}".
                        </p>
                    </div>
                `;
            } else {
                // No hay programas registrados
                contenedor.innerHTML = `
                    <div class="col-span-3 flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-sena">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.499 5.221 69.17 69.17 0 0 0-2.658.814M12 14.904a48.62 48.62 0 0 0 8.232-4.41 48.62 48.62 0 0 0-8.232-4.41m0 0a48.62 48.62 0 0 0-8.232 4.41m8.232-4.41a48.62 48.62 0 0 1 8.232 4.41M12 14.904a48.62 48.62 0 0 1-8.232-4.41m8.232 4.41a48.62 48.62 0 0 1-8.232 4.41" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sena-text-main mb-2">No hay programas de formación</h3>
                        <p data-permiso="crear_programa" class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primer programa de formación.</p>
                        <button data-permiso="crear_programa" id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14"></path>
                                <path d="M12 5v14"></path>
                            </svg>
                            Crear primer programa
                        </button>
                    </div>
                `;
                
                const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
                if (btnCrearEmpty) {
                    btnCrearEmpty.addEventListener('click', () => {
                        if (modalCrear) modalCrear.classList.remove('hidden');
                    });
                }
            }
            
            // Ocultar paginación cuando no hay resultados
            const paginacionContainer = document.getElementById('paginacion-container');
            if (paginacionContainer) paginacionContainer.classList.add('hidden');

            if (typeof Auth !== 'undefined' && Auth.aplicarPermisosPorAccion) {
                Auth.aplicarPermisosPorAccion();
            }
            return;
        }
        
        // Asegurar que la página actual no exceda el total de páginas
        const totalPaginas = Math.ceil(programasFiltrados.length / elementosPorPagina);
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }
        
        // Paginación: obtener programas de la página actual
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const programasPagina = programasFiltrados.slice(inicio, fin);
        
        // ✅ VERIFICAR PERMISOS UNA VEZ AL INICIO
        const puedeEditar = typeof Auth !== 'undefined' && Auth.tienePermiso('editar_programa');
        const puedeDesactivar = typeof Auth !== 'undefined' && Auth.tienePermiso('desactivar_programa');
        
        programasPagina.forEach(programa => {
            const card = document.createElement('div');
            card.className = 'tarjeta-tecnologia bg-white border border-gray-200 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow programa-card cursor-pointer';
            card.setAttribute('data-id', programa.id_programa);

            // ✅ Guardamos TODA la info en data-* para el modal de detalle
            card.setAttribute('data-codigo', programa.codigo_programa || '');
            card.setAttribute('data-nombre', (programa.nombre_programa || '').replace(/"/g, '&quot;'));
            card.setAttribute('data-nivel', programa.id_nivel || '');
            card.setAttribute('data-modalidad', programa.modalidad || '');
            card.setAttribute('data-cupos', programa.cupos_formacion || '');
            card.setAttribute('data-area', (programa.nombre_area || '').replace(/"/g, '&quot;'));
            card.setAttribute('data-fechainicio', programa.fecha_creacion || '');
            card.setAttribute('data-fechafin', programa.fecha_fin || '');
            card.setAttribute('data-descripcion', (programa.descripcion || '').replace(/"/g, '&quot;'));
            card.setAttribute('data-estado', programa.estado ?? 1);

            // ⚠️ CRUCIAL: Estas variables deben mantenerse aquí para el switch y los badges
            const estadoActivo = programa.estado == 1;
            const nombreNivel = getNombreNivel(programa.id_nivel);
            const colorNivel = getColorNivel(programa.id_nivel);
            const nombreArea = programa.nombre_area || 'N/A';

            let switchHTML = '';
            let editButtonHTML = '';
            
            if (puedeDesactivar) {
                switchHTML = `
                    <div class="switch-sena ${estadoActivo ? 'active' : ''}" data-id="${programa.id_programa}"></div>
                `;
            }
            
            if (puedeEditar) {
                editButtonHTML = `
                    <button data-permiso="editar_programa" class="btn-editar-programa p-2 hover:bg-sena-soft hover:text-sena rounded-lg transition-colors"
                        data-id="${programa.id_programa}"
                        data-codigo="${programa.codigo_programa || ''}"
                        data-nombre="${programa.nombre_programa || ''}"
                        data-nivel="${programa.id_nivel || ''}"
                        data-modalidad="${programa.modalidad || ''}"
                        data-fechainicio="${programa.fecha_creacion || ''}"
                        data-fechafin="${programa.fecha_fin || ''}"
                        data-cupos="${programa.cupos_formacion || ''}"
                        data-area="${programa.id_area || ''}"
                        data-descripcion="${programa.descripcion || ''}">
                        <i data-lucide="pencil" class="w-4 h-4 text-gray-500 hover:text-sena"></i>
                    </button>
                `;
            }
            
           card.innerHTML = `
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 text-base leading-tight truncate">
                        ${programa.nombre_programa || 'Sin nombre'}
                    </h3>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    ${editButtonHTML}
                    ${switchHTML}
                </div>
            </div>
            <div class="flex flex-col items-center">
                <div class="flex flex-wrap gap-2 mb-3 justify-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        #${programa.codigo_programa || 'N/A'}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ${colorNivel}">
                        <i data-lucide="layers" class="w-3 h-3"></i>
                        ${nombreNivel}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-sena-soft text-green-700">
                        <i data-lucide="monitor" class="w-3 h-3"></i>
                        ${capitalizarPrimeraLetra(programa.modalidad)}
                    </span>
                </div>
                
                <div class="space-y-2 text-sm text-gray-500 text-center">
                    <div class="flex items-center justify-center gap-4">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            ${programa.cupos_formacion || 'N/A'} cupos
                        </span>
                        <span class="text-gray-300 mx-1">|</span>
                        <span>Área: ${nombreArea}</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            ${formatearFecha(programa.fecha_creacion)}
                        </span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-gray-400"></i>
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            ${formatearFecha(programa.fecha_fin)}
                        </span>
                    </div>
                </div>
            </div>
        `;
            
            contenedor.appendChild(card);
        });
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        inicializarSwitches();
        inicializarBotonesEditar();
        inicializarClicksTarjetas();
        
        // Aplicar permisos después de renderizar
        if (typeof Auth !== 'undefined' && Auth.aplicarPermisosPorAccion) {
            Auth.aplicarPermisosPorAccion();
        }
        
        // Actualizar paginación
        actualizarPaginacion(programasFiltrados.length);
    }

    // ===== FORMATEAR FECHA =====
    function formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        const date = new Date(fecha);
        const meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        const dia = date.getDate();
        const mes = meses[date.getMonth()];
        const anio = date.getFullYear();
        return `${dia} de ${mes} de ${anio}`;
    }

    function capitalizarPrimeraLetra(texto) {
        if (!texto) return 'N/A';
        return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
    }

    // ===== INICIALIZAR SWITCHES =====
    function inicializarSwitches() {
        document.querySelectorAll('.switch-sena').forEach(switchEl => {
            if (switchEl.dataset.inicializado === 'true') return;
            switchEl.dataset.inicializado = 'true';
            
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const card = this.closest('.programa-card');
                const idPrograma = card.getAttribute('data-id');
                const nombrePrograma = card.querySelector('h3').textContent.trim();
                const estadoActual = this.classList.contains('active');

                // ✅ Guardamos el nombre en la variable compartida
                programaPendienteNombre = nombrePrograma;

                if (estadoActual) {
                    // Modal de confirmación DESHABILITAR
                    if (modalDeshabilitar) {
                        modalDeshabilitar.setAttribute('data-switch-id', idPrograma);
                        if (nombreProgramaDeshabilitar) nombreProgramaDeshabilitar.textContent = `"${nombrePrograma}"`;
                        modalDeshabilitar.classList.remove('hidden');
                    }
                } else {
                    // Modal de confirmación HABILITAR
                    if (modalHabilitar) {
                        modalHabilitar.setAttribute('data-switch-id', idPrograma);
                        if (nombreProgramaHabilitar) nombreProgramaHabilitar.textContent = `"${nombrePrograma}"`;
                        modalHabilitar.classList.remove('hidden');
                    }
                }
            });
        });
    }

    // ===== INICIALIZAR BOTONES EDITAR =====
    function inicializarBotonesEditar() {
        document.querySelectorAll(".btn-editar-programa").forEach(btn => {
            if (btn.dataset.inicializado === 'true') return;
            btn.dataset.inicializado = 'true';
            
            btn.addEventListener("click", async function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const boton = e.currentTarget;

                programaSeleccionado = {
                    id_programa: parseInt(boton.dataset.id),
                    codigo_programa: boton.dataset.codigo || '',
                    nombre_programa: boton.dataset.nombre || '',
                    id_area: parseInt(boton.dataset.area) || '',
                    id_nivel: boton.dataset.nivel || '',
                    modalidad: boton.dataset.modalidad || '',
                    fecha_creacion: boton.dataset.fechainicio || '',
                    fecha_fin: boton.dataset.fechafin || '',
                    cupos_formacion: boton.dataset.cupos || '',
                    descripcion: boton.dataset.descripcion || ''
                };
                
                
                // ✅ CORREGIDO: IDs que COINCIDEN con tu modal HTML
                const idInput = document.getElementById("idProgramaEditar");
                const codigoInput = document.getElementById("codigoProgramaEditar");
                const nombreInput = document.getElementById("nombreProgramaEditar");
                const cuposInput = document.getElementById("cuposProgramaEditar");
                const descripcionInput = document.getElementById("descripcionProgramaEditar");
                const nivelSelect = document.getElementById("nivelFormacionEditar");
                const modalidadSelect = document.getElementById("modalidadProgramaEditar");
                const fechaInicioInput = document.getElementById("fechaInicioEditar");
                const fechaFinInput = document.getElementById("fechaFinEditar");
                const areaSelect = document.getElementById("areaProgramaEditar");
                
                // ✅ Asignar valores
                if (idInput) idInput.value = boton.dataset.id || '';
                if (codigoInput) codigoInput.value = boton.dataset.codigo || '';
                if (nombreInput) nombreInput.value = boton.dataset.nombre || '';
                if (cuposInput) cuposInput.value = boton.dataset.cupos || '';
                if (descripcionInput) descripcionInput.value = boton.dataset.descripcion || '';
                actualizarContadorEditar(); 
                if (nivelSelect) nivelSelect.value = boton.dataset.nivel || '';
                
                // ✅ Fechas: usar dataset en minúsculas (como vienen del HTML)
                if (fechaInicioInput) fechaInicioInput.value = boton.dataset.fechainicio || '';
                if (fechaFinInput) fechaFinInput.value = boton.dataset.fechafin || '';
                
                // ✅ Modalidad: manejar caso
                if (modalidadSelect && boton.dataset.modalidad) {
                    const modalidadValor = boton.dataset.modalidad.trim().toUpperCase();
                    modalidadSelect.value = modalidadValor;
                    if (modalidadSelect.value !== modalidadValor) {
                        modalidadSelect.value = boton.dataset.modalidad;
                    }
                }
                
                // ✅ Área: cargar opciones primero, luego seleccionar
                if (areaSelect && boton.dataset.area) {
                    areaSelect.innerHTML = '<option value="">Cargando áreas...</option>';
                    areaSelect.disabled = true;
                    
                    try {
                        const areas = await cargarAreas();
                        areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                        
                        areas.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.text;
                            areaSelect.appendChild(option);
                        });
                        
                        areaSelect.value = boton.dataset.area;
                        areaSelect.disabled = false;
                        
                    } catch (error) {
                        console.error('Error cargando áreas:', error);
                        areaSelect.innerHTML = '<option value="">Error al cargar</option>';
                        areaSelect.disabled = false;
                    }
                }
                
                // ✅ Abrir modal
                const modal = document.getElementById("modal-editar-programa");
                if (modal) modal.classList.remove("hidden");
            });
        });
    }

    // ✅ Abre el modal y llena los datos desde la tarjeta
    function abrirModalDetallePrograma(card) {
        const modalDetalle = document.getElementById('modal-detalle-programa');
        if (!modalDetalle) return;

        document.getElementById('detalle-codigo').textContent = card.dataset.codigo || 'N/A';
        document.getElementById('detalle-nombre').textContent = card.dataset.nombre || 'Sin nombre';
        document.getElementById('detalle-nivel').textContent = getNombreNivel(card.dataset.nivel);
        document.getElementById('detalle-modalidad').textContent = capitalizarPrimeraLetra(card.dataset.modalidad);
        document.getElementById('detalle-cupos').textContent = card.dataset.cupos || 'N/A';
        document.getElementById('detalle-area').textContent = card.dataset.area || 'N/A';
        
        document.getElementById('detalle-fecha-inicio').textContent = `Inicio: ${formatearFecha(card.dataset.fechainicio)}`;
        document.getElementById('detalle-fecha-fin').textContent = `Fin: ${formatearFecha(card.dataset.fechafin)}`;
        document.getElementById('detalle-descripcion').textContent = card.dataset.descripcion || 'Sin descripción disponible.';


        const estado = card.dataset.estado;
        const esActivo = Number(estado) === 1;
        const badge = document.getElementById('detalle-estado-badge');
        const indicador = document.getElementById('detalle-estado-indicador');
        const estadoText = document.getElementById('detalle-estado');

        badge.className = `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${esActivo ? 'bg-sena/10 text-sena' : 'bg-red-100 text-red-700'}`;
        indicador.className = `w-1.5 h-1.5 rounded-full mr-1.5 ${esActivo ? 'bg-[#39A900]' : 'bg-red-500'}`;
        estadoText.textContent = esActivo ? 'Activo' : 'Inactivo';

        abrirModal(modalDetalle);
    }

    // ✅ Asigna el clic a las tarjetas
    function inicializarClicksTarjetas() {
        document.querySelectorAll('.programa-card').forEach(card => {
            if (card.dataset.inicializadoDetalle === 'true') return;
            card.dataset.inicializadoDetalle = 'true';
            
            card.addEventListener('click', function(e) {
                // Evita abrir el modal si se hace clic en los botones internos
                if (e.target.closest('.btn-editar-programa') || e.target.closest('.switch-sena')) return;
                abrirModalDetallePrograma(this);
            });
        });
    }

    // ===== BUSCADOR =====
    const buscador = document.getElementById("buscador");
    let timeoutBusqueda = null;

    if (buscador) {
        buscador.addEventListener("keyup", function () {
            clearTimeout(timeoutBusqueda);
            const texto = this.value.trim();
            
            if (texto.length < 2) {
                paginaActual = 1; // Resetear paginación
                cargarProgramas();
                return;
            }
            
            timeoutBusqueda = setTimeout(async () => {
                try {
                    paginaActual = 1; // Resetear paginación en búsqueda
                    const response = await fetch(`${PRO_URL}?accion=buscar&q=${encodeURIComponent(texto)}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        window.todosLosProgramas = result.data;
                        renderizarProgramas(result.data);
                    }
                } catch (error) {
                    console.error('Error busqueda:', error);
                }
            }, 300);
        });
    }

    // ===== ABRIR/CERRAR MODALES =====
    function abrirModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function cerrarModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // ===== CERRAR MODALES =====
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitar, .cerrar-modal-deshabilitado, .cerrar-modal-habilitado-confirmacion').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // ✅ Llama a la función específica según el modal para limpiar timers correctamente
            if (this.classList.contains('cerrar-modal-deshabilitado')) {
                cerrarModalDeshabilitado();
            } else if (this.classList.contains('cerrar-modal-habilitado-confirmacion')) {
                cerrarModalHabilitado();
            } else {
                cerrarModal(modalDeshabilitar);
                cerrarModal(modalHabilitar);
            }
        });
    });

    document.querySelectorAll(".cerrar-modal-editar").forEach(btn => {
        btn.addEventListener("click", () => { if (modalEditar) modalEditar.classList.add("hidden"); });
        programaSeleccionado = null;
    });

    document.querySelectorAll(".cerrar-modal-crear").forEach(btn => {
        btn.addEventListener("click", () => { if (modalCrear) modalCrear.classList.add("hidden"); });
    });

    document.querySelectorAll(".cerrar-modal-creado").forEach(btn => {
        btn.addEventListener("click", () => { cerrarModalCreado(); });
    });
    
    document.querySelectorAll(".cerrar-modal-editado").forEach(btn => {
        btn.addEventListener("click", () => { cerrarModalEditado(); });
    });

    //confirmar deshabilitar
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            const switchId = modalDeshabilitar?.getAttribute('data-switch-id');
            if (!switchId) return;
            
            const nombre = programaPendienteNombre || 'Programa';
            
            try {
                const response = await fetch(`${PRO_URL}?accion=desactivar&id_programa=${switchId}`, { method: 'POST' });
                const result = await response.json();
                
                if (result.success) {
                    document.querySelectorAll('.programa-card').forEach(card => {
                        if (card.getAttribute('data-id') === switchId) {
                            const sw = card.querySelector('.switch-sena');
                            if (sw) { 
                                sw.classList.remove('active');
                                sw.setAttribute('title', 'Inactivo'); 
                            }
                        }
                    });
                    cerrarModal(modalDeshabilitar);
                    mostrarModalDeshabilitado(nombre); // ✅ Envía nombre al modal de éxito
                    programaPendienteNombre = null; // ✅ Limpia la variable
                } else {
                    alert(result.error || 'Error');
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexion');
            }
        });
    }


    // ===== CONFIRMAR HABILITAR =====
   
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            const switchId = modalHabilitar?.getAttribute('data-switch-id');
            if (!switchId) return;
            
            const nombre = programaPendienteNombre || 'Programa';
            
            try {
                const response = await fetch(`${PRO_URL}?accion=activar&id_programa=${switchId}`, { method: 'POST' });
                const result = await response.json();
                
                if (result.success) {
                    document.querySelectorAll('.programa-card').forEach(card => {
                        if (card.getAttribute('data-id') === switchId) {
                            const sw = card.querySelector('.switch-sena');
                            if (sw) { 
                                sw.classList.add('active');
                                sw.setAttribute('title', 'Activo'); 
                            }
                        }
                    });
                    cerrarModal(modalHabilitar);
                    mostrarModalHabilitado(nombre); // ✅ Envía nombre al modal de éxito
                    programaPendienteNombre = null; // ✅ Limpia la variable
                } else {  
                    alert(result.error || 'Error');
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexion');
            }
        });
    }

    // ===== GUARDAR NUEVO PROGRAMA ====
    const btnGuardarNuevo = document.getElementById('btn-guardar-nuevo-programa');
    if (btnGuardarNuevo) {
        btnGuardarNuevo.addEventListener('click', async () => {
            const nombreInput = document.getElementById("nombreNuevoPrograma");
            const datos = {
                id_area: document.getElementById("areaPrograma")?.value,
                codigo_programa: document.getElementById("codigoNuevoPrograma")?.value,
                nombre_programa: nombreInput?.value,
                id_nivel: document.getElementById("nivelNuevoPrograma")?.value,
                modalidad: document.getElementById("modalidadNuevoPrograma")?.value,
                fecha_creacion: document.getElementById("fechaInicioNuevoPrograma")?.value,
                fecha_fin: document.getElementById("fechaFinNuevoPrograma")?.value,
                cupos_formacion: document.getElementById("cuposNuevoPrograma")?.value,
                descripcion: document.getElementById("descripcionNuevoPrograma")?.value
            };

            // =========================
            // 1. VALIDACIÓN DE CAMPOS REQUERIDOS
            // =========================
            if (!datos.id_area || !datos.codigo_programa || !datos.nombre_programa || !datos.id_nivel) {
                // 🚨 ANTES: alert('Complete los campos requeridos');
                mostrarToastValidacion('Complete los campos requeridos (*)', 'error'); 
                return;
            }

            // =========================
            // 2. VALIDACIÓN DE DESCRIPCIÓN
            // =========================
            if (!datos.descripcion || !validarDescripcion(datos.descripcion)) {
                // 🚨 ANTES: alert('La descripción debe tener...');
                const longitud = datos.descripcion ? datos.descripcion.length : 0;
                mostrarToastValidacion(`La descripción es muy corta (${longitud}/30 caracteres)`, 'warning');
                return;
            }

            try {
                const response = await fetch(`${PRO_URL}?accion=crear`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });
                const resultado = await response.json();
                if (resultado.success) {
                    if (modalCrear) modalCrear.classList.add("hidden");
                    limpiarFormularioPrograma(); 
                    mostrarModalCreado(datos.nombre_programa); 
                    cargarProgramas();
                } else {
                    mostrarToastValidacion(result.error || result.message, 'error');
                }
            } catch (error) {
                console.error(error);
                alert("Error al crear");
            }
        });
    }

    // ===== GUARDAR EDITADA =====
    const btnGuardarEditar = document.getElementById('btn-guardar-programa-editado');
    if (btnGuardarEditar) {
        btnGuardarEditar.addEventListener('click', async () => {
            const nombreInput = document.getElementById("nombreProgramaEditar");
            const descInput = document.getElementById("descripcionProgramaEditar");
            
            const datosActuales = {
                id_programa: document.getElementById("idProgramaEditar")?.value,
                id_area: document.getElementById("areaProgramaEditar")?.value,
                codigo_programa: document.getElementById("codigoProgramaEditar")?.value,
                nombre_programa: nombreInput?.value,
                id_nivel: document.getElementById("nivelFormacionEditar")?.value,
                modalidad: document.getElementById("modalidadProgramaEditar")?.value,
                fecha_creacion: document.getElementById("fechaInicioEditar")?.value,
                fecha_fin: document.getElementById("fechaFinEditar")?.value,
                cupos_formacion: document.getElementById("cuposProgramaEditar")?.value,
                descripcion: descInput?.value
            };

            // =========================
            // 1. VALIDACIÓN DE ID Y ÁREA
            // =========================
            if (!datosActuales.id_programa) {
                mostrarToastValidacion('Error interno: ID del programa no encontrado', 'error');
                return;
            }

            if (!datosActuales.id_area) {
                mostrarToastValidacion('El área es requerida', 'warning');
                return;
            }

            // =========================
            // 2. VALIDACIÓN DE DESCRIPCIÓN
            // =========================
            if (!validarDescripcion(datosActuales.descripcion)) {
                mostrarToastValidacion('La descripción debe tener al menos 30 caracteres', 'warning');
                return;
            }

            // =========================
            // 3. 🔥 VALIDACIÓN DE CAMBIOS 🔥
            // =========================
            if (!programaSeleccionado) {
                console.error('No hay datos originales del programa');
                mostrarToastValidacion('Error al cargar los datos originales', 'error');
                return;
            }

            // Comparar cada campo relevante
            const codigoCambio = datosActuales.codigo_programa !== programaSeleccionado.codigo_programa;
            const nombreCambio = datosActuales.nombre_programa !== programaSeleccionado.nombre_programa;
            const areaCambio = parseInt(datosActuales.id_area) !== programaSeleccionado.id_area;
            const nivelCambio = datosActuales.id_nivel !== programaSeleccionado.id_nivel;
            const modalidadCambio = datosActuales.modalidad !== programaSeleccionado.modalidad;
            const fechaInicioCambio = datosActuales.fecha_creacion !== programaSeleccionado.fecha_creacion;
            const fechaFinCambio = datosActuales.fecha_fin !== programaSeleccionado.fecha_fin;
            const cuposCambio = datosActuales.cupos_formacion !== programaSeleccionado.cupos_formacion;
            const descripcionCambio = datosActuales.descripcion !== programaSeleccionado.descripcion;

            // Verificar si hay algún cambio
            const hayCambios = codigoCambio || nombreCambio || areaCambio || nivelCambio || 
                            modalidadCambio || fechaInicioCambio || fechaFinCambio || 
                            cuposCambio || descripcionCambio;

            if (!hayCambios) {
                mostrarToastValidacion('No se ha realizado ningún cambio en el programa', 'info');
                return;
            }

            try {
                const response = await fetch(`${PRO_URL}?accion=actualizar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datosActuales)
                });
                const resultado = await response.json();
                
                if (resultado.success) {
                    if (modalEditar) modalEditar.classList.add("hidden");
                    mostrarModalEditado(datosActuales.nombre_programa);
                    cargarProgramas();
                    // Limpiar variable después de guardar
                    programaSeleccionado = null;
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al actualizar', 'error');
                }
            } catch (error) {
                console.error(error);
                mostrarToastValidacion('Error al conectar con el servidor', 'error');
            }
        });
        
        const descEditar = document.getElementById('descripcionProgramaEditar');
        if (descEditar) descEditar.addEventListener('input', actualizarContadorEditar);
}

    // ===== ABRIR MODAL CREAR =====
    if (btnCrearPrograma) {
        btnCrearPrograma.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (modalCrear) modalCrear.classList.remove('hidden');

            // 🔥 LIMPIAR FORMULARIO AL ABRIR
            const form = document.getElementById("formPrograma");

            if (form) {
                form.reset();

                // limpieza forzada (por si hay valores persistentes)
                form.querySelectorAll("input, textarea, select").forEach(el => {
                    if (el.type === "checkbox" || el.type === "radio") {
                        el.checked = false;
                    } else {
                        el.value = "";
                    }
                });
            }
        });
    }

    // ===== MODAL DESHABILITADO =====
   function mostrarModalDeshabilitado(nombre) {
        if (!modalDeshabilitado) return;
        
        // ✅ Búsqueda directa por ID
        const spanNombre = document.getElementById('nombre-perfil-deshabilitado');
        if (spanNombre) {
            spanNombre.textContent = `"${nombre}"`;
        } else {
            console.warn('⚠️ [HTML] No existe <span id="nombre-perfil-deshabilitado"> en tu HTML');
        }

        abrirModal(modalDeshabilitado);
        
        let segundos = 3;
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) contador.textContent = segundos;
        if (intervalContadorDeshabilitado) clearInterval(intervalContadorDeshabilitado);
        
        intervalContadorDeshabilitado = setInterval(() => {
            segundos--;
            if (contador) contador.textContent = segundos;
            if (segundos <= 0) {
                clearInterval(intervalContadorDeshabilitado);
                intervalContadorDeshabilitado = null;
            }
        }, 1000);
        
        const pb = document.getElementById('progress-bar-deshabilitado');
        if (pb) { pb.style.width = '0%'; setTimeout(() => { pb.style.width = '100%'; }, 50); }
        if (timeoutDeshabilitado) clearTimeout(timeoutDeshabilitado);
        timeoutDeshabilitado = setTimeout(() => { cerrarModalDeshabilitado(); }, 3000);
    }


    // ===== MODAL HABILITADO =====
    
    function mostrarModalHabilitado(nombre) {
        if (!modalHabilitadoConfirmacion) return;
        
        // ✅ Búsqueda directa por ID
        const spanNombre = document.getElementById('nombre-perfil-habilitado-exito');
        if (spanNombre) {
            spanNombre.textContent = `"${nombre}"`;
        } else {
            console.warn('⚠️ [HTML] No existe <span id="nombre-perfil-habilitado-exito"> en tu HTML');
        }

        abrirModal(modalHabilitadoConfirmacion);
        
        let segundos = 3;
        const contador = document.getElementById('contador-segundos');
        if (contador) contador.textContent = segundos;
        if (intervalContadorHabilitado) clearInterval(intervalContadorHabilitado);
        
        intervalContadorHabilitado = setInterval(() => {
            segundos--;
            if (contador) contador.textContent = segundos;
            if (segundos <= 0) {
                clearInterval(intervalContadorHabilitado);
                intervalContadorHabilitado = null;
            }
        }, 1000);
        
        const pb = document.getElementById('progress-bar-habilitado');
        if (pb) { pb.style.width = '0%'; setTimeout(() => { pb.style.width = '100%'; }, 50); }
        if (timeoutHabilitado) clearTimeout(timeoutHabilitado);
        timeoutHabilitado = setTimeout(() => { cerrarModalHabilitado(); }, 3000);
    }

    // ===== CERRAR MODAL DESHABILITADO =====
    function cerrarModalDeshabilitado() {
        if (modalDeshabilitado) { modalDeshabilitado.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        if (timeoutDeshabilitado) { clearTimeout(timeoutDeshabilitado); timeoutDeshabilitado = null; }
        if (intervalContadorDeshabilitado) { clearInterval(intervalContadorDeshabilitado); intervalContadorDeshabilitado = null; }
        const pb = document.getElementById('progress-bar-deshabilitado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos-deshabilitado'); if (c) c.textContent = '3';
    }

    // ===== CERRAR MODAL HABILITADO =====
    function cerrarModalHabilitado() {
        if (modalHabilitadoConfirmacion) { modalHabilitadoConfirmacion.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        if (timeoutHabilitado) { clearTimeout(timeoutHabilitado); timeoutHabilitado = null; }
        if (intervalContadorHabilitado) { clearInterval(intervalContadorHabilitado); intervalContadorHabilitado = null; }
        const pb = document.getElementById('progress-bar-habilitado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos'); if (c) c.textContent = '3';
    }

    // ===== MODAL CREADO =====
    function mostrarModalCreado(nombre) {
        if (!modalCreadoConfirmacion) return;
        const span = document.getElementById('nombre-proyeccion-creada');
        if (span) span.textContent = `"${nombre}"`;

        modalCreadoConfirmacion.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        let segundos = 3;
        const contador = document.getElementById('contador-segundos-creado');
        if (contador) contador.textContent = segundos;
        if (intervalContadorCreado) clearInterval(intervalContadorCreado);
        
        intervalContadorCreado = setInterval(() => {
            segundos--;
            if (contador) contador.textContent = segundos;
            if (segundos <= 0) {
                clearInterval(intervalContadorCreado);
                intervalContadorCreado = null;
            }
        }, 1000);
        
        const pb = document.getElementById('progress-bar-creado');
        if (pb) { pb.style.width = '0%'; setTimeout(() => { pb.style.width = '100%'; }, 50); }
        if (timeoutCreado) clearTimeout(timeoutCreado);
        timeoutCreado = setTimeout(() => { cerrarModalCreado(); }, 3000);
    }

    function cerrarModalCreado() {
        if (modalCreadoConfirmacion) {
            modalCreadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutCreado) { clearTimeout(timeoutCreado); timeoutCreado = null; }
        if (intervalContadorCreado) { clearInterval(intervalContadorCreado); intervalContadorCreado = null; }
        const pb = document.getElementById('progress-bar-creado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos-creado'); if (c) c.textContent = '3';
    }

    // ===== MODAL EDITADO =====
    function mostrarModalEditado(nombre) {
        if (!modalEditadoConfirmacion) return;
        const span = document.getElementById('nombre-proyeccion-editado');
        if (span) span.textContent = `"${nombre}"`;

        modalEditadoConfirmacion.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        let segundos = 3;
        const contador = document.getElementById('contador-segundos-editado');
        if (contador) contador.textContent = segundos;
        if (intervalContadorEditado) clearInterval(intervalContadorEditado);
        
        intervalContadorEditado = setInterval(() => {
            segundos--;
            if (contador) contador.textContent = segundos;
            if (segundos <= 0) {
                clearInterval(intervalContadorEditado);
                intervalContadorEditado = null;
            }
        }, 1000);
        
        const pb = document.getElementById('progress-bar-editado');
        if (pb) { pb.style.width = '0%'; setTimeout(() => { pb.style.width = '100%'; }, 50); }
        if (timeoutEditado) clearTimeout(timeoutEditado);
        timeoutEditado = setTimeout(() => { cerrarModalEditado(); }, 3000);
    }

    function cerrarModalEditado() {
        if (modalEditadoConfirmacion) {
            modalEditadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutEditado) { clearTimeout(timeoutEditado); timeoutEditado = null; }
        if (intervalContadorEditado) { clearInterval(intervalContadorEditado); intervalContadorEditado = null; }
        const pb = document.getElementById('progress-bar-editado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos-editado'); if (c) c.textContent = '3';
    }

    // ===== ACTUALIZAR PAGINACIÓN =====
    function actualizarPaginacion(totalElementos) {
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        const paginacionContainer = document.getElementById('paginacion-container');
        
        if (!paginacionContainer) return;
        
        if (totalPaginas <= 1) {
            paginacionContainer.classList.add('hidden');
            return;
        }
        
        paginacionContainer.classList.remove('hidden');
        
        // Generar los botones de páginas con elipsis
        let paginasHTML = '';
        
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);
        
        if (paginaActual <= 3) {
            fin = Math.min(5, totalPaginas);
        }
        
        if (paginaActual >= totalPaginas - 2) {
            inicio = Math.max(totalPaginas - 4, 1);
        }
        
        if (inicio > 1) {
            paginasHTML += `
                <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="1">
                    1
                </button>
            `;
            if (inicio > 2) {
                paginasHTML += `<span class="px-2 text-sena-text-soft">...</span>`;
            }
        }
        
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
        
        if (fin < totalPaginas) {
            if (fin < totalPaginas - 1) {
                paginasHTML += `<span class="px-2 text-sena-text-soft">...</span>`;
            }
            paginasHTML += `
                <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="${totalPaginas}">
                    ${totalPaginas}
                </button>
            `;
        }
        
        let paginacionHTML = `
            <div class="flex flex-col items-center gap-3 mb-6">
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${((paginaActual - 1) * elementosPorPagina) + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> programas
                </div>
                
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    <button class="btn-primera-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <button class="btn-pagina-anterior px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    ${paginasHTML}
                    
                    <button class="btn-pagina-siguiente px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    
                    <button class="btn-ultima-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M6 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        paginacionContainer.innerHTML = paginacionHTML;
        
        // Event listeners para paginación
        document.querySelectorAll('.btn-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                paginaActual = parseInt(btn.dataset.pagina);
                renderizarProgramas(window.todosLosProgramas || []);
            });
        });
        
        document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual > 1) {
                    paginaActual--;
                    renderizarProgramas(window.todosLosProgramas || []);
                }
            });
        });
        
        document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual < totalPaginas) {
                    paginaActual++;
                    renderizarProgramas(window.todosLosProgramas || []);
                }
            });
        });
        
        document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== 1) {
                    paginaActual = 1;
                    renderizarProgramas(window.todosLosProgramas || []);
                }
            });
        });
        
        document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== totalPaginas) {
                    paginaActual = totalPaginas;
                    renderizarProgramas(window.todosLosProgramas || []);
                }
            });
        });
    }

    // ===== EVENT LISTENERS PARA CONTADORES =====
    const descCrear = document.getElementById('descripcionNuevoPrograma');
    if (descCrear) {
        descCrear.addEventListener('input', actualizarContadorCrear);
    }

    const descEditar = document.getElementById('descripcionProgramaEditar');
    if (descEditar) {
        descEditar.addEventListener('input', actualizarContadorEditar);
    }

    // Cerrar modal detalle al hacer clic en botón o overlay
    document.querySelectorAll('.cerrar-modal-detalle-programa').forEach(btn => {
        btn.addEventListener('click', (e) => { e.preventDefault(); cerrarModal(modalDetalle); });
    });

    if (modalDetalle) {
        modalDetalle.addEventListener('click', (e) => {
            if (e.target === modalDetalle || e.target.classList.contains('overflow-y-auto')) {
                cerrarModal(modalDetalle);
            }
        });
    }

    // ===== INICIALIZAR =====
    llenarSelectAreas('areaPrograma');
    llenarSelectAreas('areaProgramaEditar');

    // ✅ ESPERAR A QUE AUTH ESTÉ LISTO - SIN FALLBACK
    if (typeof Auth !== 'undefined' && typeof Auth.whenReady === 'function') {
        Auth.whenReady(() => {
            cargarProgramas();
        });
    } else {
        // Solo fallback si Auth realmente no existe (no si está cargando)
        setTimeout(() => {
            if (typeof Auth !== 'undefined' && typeof Auth.whenReady === 'function') {
                Auth.whenReady(() => cargarProgramas());
            } else {
                cargarProgramas(); // Último recurso
            }
        }, 500);
    }
});