document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const API_URL = '../../controllers/SugerenciasController.php';
    const idUsuarioActual = window.idUsuarioActual || null;
    
    let todasLasTarjetas = []; // Almacenar todas las sugerencias
    let valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' }; // Guardar valores originales
    
    // Variables para paginación y filtros
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let sugerenciasFiltradas = [];
    let mostrarSoloMisCreaciones = false; // Variable para controlar el filtro de "Mis creaciones"
    
    // Elementos del DOM
    const tarjetasContainer = document.getElementById('tarjetas-container');
    const loader = document.getElementById('loader');
    const errorMensaje = document.getElementById('error-mensaje');
    const resultadosCount = document.getElementById('resultados-count');
    const filtroActivo = document.getElementById('filtro-activo');
    const contadorTodos = document.getElementById('contador-todos');
    const contadorActivos = document.getElementById('contador-activos');
    const contadorInactivos = document.getElementById('contador-inactivos');

    // Funcionalidad del modal de crear
    const modalCrear = document.getElementById('modal-crear-sugerencia');
    const btnAbrirModal = document.getElementById('btn-nuevo-perfil');
    const btnMisCreaciones = document.querySelector('a[href="../../view/perfiles/mis_creaciones_perfiles.php"]');
    const btnCerrarModal = document.querySelectorAll('.cerrar-modal-crear');
    const formCrear = document.getElementById('form-nueva-proyeccion-futuro');
    const inputTitulo = document.getElementById('input-titulo-sugerencia');
    const inputContenido = document.getElementById('textarea-contenido-sugerencia');

    // Funcionalidad del modal de detalles
    const modalDetalles = document.getElementById('modal-ver-detalles');
    const btnCerrarDetalles = document.querySelectorAll('.cerrar-modal-detalles');

    // Funcionalidad del modal de éxito (crear)
    const modalExito = document.getElementById('modal-creado-confirmacion-proyeccion-futura');
    const btnCerrarExito = document.querySelectorAll('.cerrar-modal-creado');
    const nombreSugerenciaSpan = document.getElementById('nombre-sugerencia-creada');
    const contadorSegundos = document.getElementById('contador-segundos-creado');
    const progressBar = document.getElementById('progress-bar-creado');

    // Funcionalidad del modal de editar
    const modalEditar = document.getElementById('modal-editar-sugerencia');
    const btnCerrarModalEditar = document.querySelectorAll('.cerrar-modal-editar');
    const formEditar = document.querySelector('#modal-editar-sugerencia form');
    const inputTituloEditar = document.querySelector('#modal-editar-sugerencia input[name="titulo"]');
    const textareaDescripcionEditar = document.querySelector('#modal-editar-sugerencia textarea[name="descripcion"]');

    // Funcionalidad del modal de éxito (editar)
    const modalEditadoExito = document.getElementById('modal-editado-confirmacion');
    const btnCerrarEditadoExito = document.querySelectorAll('.cerrar-modal-editado');
    const nombreProyeccionEditado = document.getElementById('nombre-proyeccion-editado');
    const contadorSegundosEditado = document.getElementById('contador-segundos-editado');
    const progressBarEditado = document.getElementById('progress-bar-editado');

    // Funcionalidad del modal de deshabilitar
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-sugerencia');
    const btnCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombreSugerenciaDeshabilitar = document.getElementById('nombre-sugerencia-deshabilitar');
    
    // Funcionalidad del modal de éxito (deshabilitar)
    const modalDeshabilitadoExito = document.getElementById('modal-deshabilitado-sugerencia');
    const btnCerrarDeshabilitadoExito = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const nombreProyeccionDeshabilitado = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
    const contadorSegundosDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
    const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

    // Funcionalidad del modal de habilitar
    const modalHabilitar = document.getElementById('modal-habilitar-sugerencia');
    const btnCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const nombreSugerenciaHabilitar = document.getElementById('nombre-suegerencia-habilitar');

    // Funcionalidad del modal de éxito (habilitar)
    const modalHabilitadoExito = document.getElementById('modal-habilitado-confirmacion-sugerencia');
    const btnCerrarHabilitadoExito = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const nombreProyeccionHabilitado = document.getElementById('nombre-proyeccion-habilitado-exito');
    const contadorSegundosHabilitado = document.getElementById('contador-segundos-habilitado');
    const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

    let switches = [];
    let idDeshabilitando = null;
    let nombreDeshabilitando = null;
    let idHabilitando = null;
    let nombreHabilitando = null;
    let idEditando = null;
    
    let timeoutExito;
    let intervaloContador;
    let timeoutExitoEditado;
    let intervaloContadorEditado;
    let timeoutExitoDeshabilitado;
    let intervaloContadorDeshabilitado;
    let timeoutExitoHabilitado;
    let intervaloContadorHabilitado;

    // ==================== FUNCIÓN PARA MOSTRAR TOAST DE VALIDACIÓN ====================
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
            info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="16" x2="12" y2="12"/>
                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                  </svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                       <path d="M12 9v4"/>
                       <path d="M12 17h.01"/>
                       <circle cx="12" cy="12" r="10"/>
                     </svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                  </svg>`,
            success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                       <polyline points="20 6 9 17 4 12"/>
                     </svg>`
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

    // ==================== FUNCIÓN PARA VERIFICAR SI HUBO CAMBIOS EN EDITAR ====================
    function verificarCambiosEditar() {
        const tipoActual = document.querySelector('input[name="tipo_sugerencia_editar"]:checked')?.value || '';
        const tituloActual = inputTituloEditar ? inputTituloEditar.value.trim() : '';
        const descripcionActual = textareaDescripcionEditar ? textareaDescripcionEditar.value.trim() : '';
        
        const hayCambios = tipoActual !== valoresOriginalesEditar.tipo ||
                          tituloActual !== valoresOriginalesEditar.titulo ||
                          descripcionActual !== valoresOriginalesEditar.descripcion;
        
        return hayCambios;
    }

    // ==================== FUNCIÓN PARA ORDENAR SUGERENCIAS (Activas primero, luego inactivas, ambas por fecha descendente) ====================
    function ordenarSugerencias(sugerencias) {
        return sugerencias.sort((a, b) => {
            // Primero ordenar por estado (activos primero)
            if (a.estado !== b.estado) {
                return b.estado - a.estado; // 1 (activo) va antes que 0 (inactivo)
            }
            // Luego por fecha (más reciente primero)
            const fechaA = new Date(a.fecha_creacion);
            const fechaB = new Date(b.fecha_creacion);
            return fechaB - fechaA;
        });
    }

    // ==================== FUNCIÓN PARA FILTRAR SUGERENCIAS POR USUARIO ACTUAL ====================
    function filtrarPorMisCreaciones(sugerencias) {
        if (!mostrarSoloMisCreaciones || !idUsuarioActual) return sugerencias;
        return sugerencias.filter(s => s.id_usuario == idUsuarioActual);
    }

    // ==================== FUNCIÓN PARA OBTENER SUGERENCIAS FILTRADAS Y ORDENADAS ====================
    function obtenerSugerenciasFiltradas() {
        let sugerencias = [...todasLasTarjetas];
        
        // Filtrar por "Mis creaciones"
        sugerencias = filtrarPorMisCreaciones(sugerencias);
        
        // Ordenar: activas primero, luego inactivas, ambas por fecha descendente
        sugerencias = ordenarSugerencias(sugerencias);
        
        return sugerencias;
    }

    // ==================== FUNCIÓN PARA ABRIR MODAL DE DETALLES ====================
    function abrirModalDetalles(sugerencia) {
        if (!modalDetalles) return;
        
        // Establecer título
        document.getElementById('detalle-titulo-completo').textContent = sugerencia.titulo || 'Sin título';
        
        // Establecer descripción
        document.getElementById('detalle-descripcion').textContent = sugerencia.contenido || 'Sin descripción';
        
        // Establecer autor
        const autor = sugerencia.representante_legal || sugerencia.nombre_empresa || 'Usuario';
        document.getElementById('detalle-autor').textContent = autor;
        
        // Establecer fecha
        let fecha = 'Fecha no disponible';
        if (sugerencia.fecha_creacion) {
            try {
                fecha = new Date(sugerencia.fecha_creacion).toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                fecha = 'Fecha inválida';
            }
        }
        document.getElementById('detalle-fecha').textContent = fecha;
        
        // Establecer tipo de sugerencia con badge
        const tipoBadge = document.getElementById('detalle-tipo-badge');
        const tipo = sugerencia.tipo_sugerencia || '';
        
        tipoBadge.className = 'px-3 py-1 text-xs font-medium rounded-full';
        if (tipo === 'PROGRAMA') {
            tipoBadge.textContent = 'Programa';
            tipoBadge.classList.add('badge-programa');
        } else if (tipo === 'LINEA_TECNOLOGICA') {
            tipoBadge.textContent = 'Línea Tecnológica';
            tipoBadge.classList.add('badge-linea');
        } else if (tipo === 'OTROS') {
            tipoBadge.textContent = 'Otros';
            tipoBadge.classList.add('badge-otros');
        } else {
            tipoBadge.textContent = 'No especificado';
            tipoBadge.classList.add('badge-otros');
        }
        
        // Establecer estado con badge
        const estadoBadge = document.getElementById('detalle-estado-badge');
        const estado = sugerencia.estado == 1 ? 'activo' : 'inactivo';
        
        estadoBadge.className = 'px-3 py-1 text-xs font-medium rounded-full';
        if (estado === 'activo') {
            estadoBadge.textContent = 'Activo';
            estadoBadge.classList.add('badge-activo');
        } else {
            estadoBadge.textContent = 'Inactivo';
            estadoBadge.classList.add('badge-inactivo');
        }
        
        // Abrir modal
        modalDetalles.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // ==================== FUNCIÓN PARA CERRAR MODAL DE DETALLES ====================
    function cerrarModalDetalles() {
        if (modalDetalles) {
            modalDetalles.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // ==================== FUNCIONES DE PAGINACIÓN ====================

    // Obtener sugerencias de la página actual
    function obtenerSugerenciasPagina(sugerencias) {
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        return sugerencias.slice(inicio, fin);
    }

    // Actualizar paginación
    function actualizarPaginacion(totalElementos) {
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        const paginacionContainer = document.getElementById('paginacion-container');
        
        if (!paginacionContainer) return;
        
        if (totalPaginas <= 1) {
            paginacionContainer.classList.add('hidden');
            return;
        }
        
        paginacionContainer.classList.remove('hidden');
        
        let paginasHTML = '';
        
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);
        
        if (paginaActual <= 3) {
            fin = Math.min(5, totalPaginas);
        }
        
        if (paginaActual >= totalPaginas - 2) {
            inicio = Math.max(totalPaginas - 4, 1);
        }
        
        if (totalPaginas > 1) {
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
        
        let paginacionHTML = `
            <div class="flex flex-col items-center gap-3  mb-6">
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${((paginaActual - 1) * elementosPorPagina) + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> sugerencias
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
        
        document.querySelectorAll('.btn-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                paginaActual = parseInt(btn.dataset.pagina);
                renderizarTarjetas(obtenerSugerenciasFiltradas());
            });
        });
        
        document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual > 1) {
                    paginaActual--;
                    renderizarTarjetas(obtenerSugerenciasFiltradas());
                }
            });
        });
        
        document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual < totalPaginas) {
                    paginaActual++;
                    renderizarTarjetas(obtenerSugerenciasFiltradas());
                }
            });
        });
        
        document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== 1) {
                    paginaActual = 1;
                    renderizarTarjetas(obtenerSugerenciasFiltradas());
                }
            });
        });
        
        document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== totalPaginas) {
                    paginaActual = totalPaginas;
                    renderizarTarjetas(obtenerSugerenciasFiltradas());
                }
            });
        });
    }

    // ==================== FUNCIONES API ====================

    // Cargar sugerencias desde la base de datos
    async function cargarSugerencias() {
        try {
            loader.classList.remove('hidden');
            errorMensaje.classList.add('hidden');
            tarjetasContainer.innerHTML = '';

            const response = await fetch(`${API_URL}?accion=listarTodas`);
            const data = await response.json();

            if (data.status === 'success' || data.success === true) {
                const sugerencias = data.data || data;
                todasLasTarjetas = Array.isArray(sugerencias) ? sugerencias : [];
                
                // Resetear página al cargar nuevas sugerencias
                paginaActual = 1;
                
                // Obtener sugerencias filtradas y ordenadas
                const sugerenciasFiltradas = obtenerSugerenciasFiltradas();
                renderizarTarjetas(sugerenciasFiltradas);
                actualizarContadores(todasLasTarjetas);
            } else {
                console.error('Error en la respuesta:', data);
                throw new Error('Error al cargar las sugerencias');
            }
        } catch (error) {
            console.error('Error:', error);
            errorMensaje.classList.remove('hidden');
            tarjetasContainer.innerHTML = '';
            mostrarToastValidacion('Error al cargar las sugerencias', 'error');
        } finally {
            loader.classList.add('hidden');
        }
    }

    // Renderizar tarjetas en el DOM
    function renderizarTarjetas(sugerencias) {
        if (!sugerencias || sugerencias.length === 0) {
            let mensaje = 'No hay sugerencias';
            if (mostrarSoloMisCreaciones) {
                mensaje = 'No has creado ninguna sugerencia aún';
            }
            
            tarjetasContainer.innerHTML = `
                <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                    <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" x2="12.01" y1="17" y2="17"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-sena-text-main mb-2">${mensaje}</h3>
                    <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">
                        ${mostrarSoloMisCreaciones ? 'Comienza creando tu primera sugerencia innovadora.' : 'Comienza creando tu primera sugerencia innovadora para fortalecer el desarrollo tecnológico de Risaralda.'}
                    </p>
                    <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        Crear primera sugerencia
                    </button>
                </div>
            `;

            const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
            if (btnCrearEmpty && modalCrear) {
                btnCrearEmpty.addEventListener('click', function() {
                    modalCrear.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            const paginacionContainer = document.getElementById('paginacion-container');
            if (paginacionContainer) paginacionContainer.classList.add('hidden');
            return;
        }

        // Asegurar que la página actual no exceda el total de páginas
        const totalPaginas = Math.ceil(sugerencias.length / elementosPorPagina);
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }

        tarjetasContainer.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-stretch';
        
        const sugerenciasPagina = obtenerSugerenciasPagina(sugerencias);
        
        tarjetasContainer.innerHTML = sugerenciasPagina.map((s, index) => crearTarjetaHTML(s, index)).join('');
        
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach((tarjeta, index) => {
            tarjeta.addEventListener('click', function(e) {
                if (e.target.closest('.btn-editar-proyeccion') || e.target.closest('.switch-sena')) {
                    return;
                }
                const sugerencia = sugerenciasPagina[index];
                if (sugerencia) {
                    abrirModalDetalles(sugerencia);
                }
            });
        });
        
        switches = document.querySelectorAll('.switch-sena');
        asignarEventosASwitches();
        
        const nuevosBtnsEditar = document.querySelectorAll('.btn-editar-proyeccion');
        nuevosBtnsEditar.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                abrirModalEditar(this);
            });
        });
        
        actualizarPaginacion(sugerencias.length);
    }

    // Crear HTML de una tarjeta
    function crearTarjetaHTML(s, index) {
        let fecha = 'Fecha no disponible';
        if (s.fecha_creacion) {
            try {
                fecha = new Date(s.fecha_creacion).toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            } catch (e) {
                fecha = 'Fecha inválida';
            }
        }
        
        const estadoClass = s.estado == 1 ? 'active' : '';
        const estadoTitle = s.estado == 1 ? 'Activo' : 'Inactivo';
        
        const tituloCorto = s.titulo && s.titulo.length > 50 ? s.titulo.substring(0, 50) + '...' : (s.titulo || 'Sin título');
        const contenidoCorto = s.contenido && s.contenido.length > 80 ? s.contenido.substring(0, 80) + '...' : (s.contenido || 'Sin contenido');

        const tituloEscapado = (s.titulo || '').replace(/"/g, '&quot;');
        const contenidoEscapado = (s.contenido || '').replace(/"/g, '&quot;');

        return `
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer flex flex-col h-full" 
                 data-id="${s.id_sugerencia}" 
                 data-nombre="${tituloEscapado}"
                 data-descripcion="${contenidoEscapado}"
                 data-tipo="${s.tipo_sugerencia || ''}"
                 data-estado="${s.estado == 1 ? 'activo' : 'inactivo'}"
                 data-fecha="${fecha}">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-sena">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" x2="12.01" y1="17" y2="17"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" 
                                title="Editar sugerencia" 
                                data-id="${s.id_sugerencia}" 
                                data-nombre="${tituloEscapado}" 
                                data-descripcion="${contenidoEscapado}"
                                data-tipo="${s.tipo_sugerencia || ''}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                            </svg>
                        </button>
                        <div class="switch-sena ${estadoClass}" title="${estadoTitle}" data-id="${s.id_sugerencia}" data-nombre="${tituloEscapado}"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-sena-text-main mb-1 line-clamp-2">${tituloCorto}</h3>
                    <p class="text-xs text-sena-text-soft line-clamp-3 min-h-[3rem]">${contenidoCorto}</p>
                </div>
                <hr class="my-3 mt-auto border-sena-border">
                <div class="flex items-center justify-end gap-2 mt-2 text-xs text-sena-text-soft">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>${s.representante_legal || s.nombre_empresa || 'Usuario'}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <span>${fecha}</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Asignar eventos a los switches
    function asignarEventosASwitches() {
        switches.forEach(switchEl => {
            switchEl.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const id = this.dataset.id;
                const nombre = this.dataset.nombre;
                
                if (this.classList.contains('active')) {
                    abrirModalDeshabilitar(id, nombre);
                } else {
                    abrirModalHabilitar(id, nombre);
                }
            });
        });
    }

    // Actualizar contadores
    function actualizarContadores(sugerencias) {
        const activos = sugerencias.filter(s => s.estado == 1).length;
        const inactivos = sugerencias.filter(s => s.estado == 0).length;
        
        contadorTodos.textContent = sugerencias.length;
        contadorActivos.textContent = activos;
        contadorInactivos.textContent = inactivos;
        
        const filtroActual = document.querySelector('.filtro-btn.active')?.dataset.filtro || 'todos';
        filtrarTarjetas(filtroActual);
    }

    // Filtrar tarjetas según estado (solo visual, no afecta el orden)
    function filtrarTarjetas(filtro) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        let visibleCount = 0;
        
        tarjetas.forEach(tarjeta => {
            const estado = tarjeta.dataset.estado;
            let mostrar = false;
            
            if (filtro === 'todos') {
                mostrar = true;
            } else if (filtro === 'activos' && estado === 'activo') {
                mostrar = true;
            } else if (filtro === 'inactivos' && estado === 'inactivo') {
                mostrar = true;
            }
            
            if (mostrar) {
                tarjeta.style.display = 'block';
                visibleCount++;
            } else {
                tarjeta.style.display = 'none';
            }
        });
        
        resultadosCount.textContent = `${visibleCount} sugerencia${visibleCount !== 1 ? 's' : ''}`;
        const filtroTexto = filtro === 'todos' ? 'todos' : filtro === 'activos' ? 'activos' : 'inactivos';
        filtroActivo.textContent = `Mostrando ${filtroTexto}`;
    }

    // ==================== FUNCIONES CRUD ====================

    // Crear nueva sugerencia
    async function crearSugerencia(tipoSugerencia, titulo, contenido) {
        try {
            const response = await fetch(`${API_URL}?accion=crear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_usuario: idUsuarioActual,
                    tipo_sugerencia: tipoSugerencia,
                    titulo: titulo,
                    contenido: contenido,
                    estado: 1
                })
            });

            const data = await response.json();
            
            if (data.success) {
                await cargarSugerencias();
                return { success: true, id: data.id_sugerencia };
            } else {
                return { success: false, error: data.error || 'Error al crear' };
            }
        } catch (error) {
            console.error('Error al crear:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    // Actualizar sugerencia
    async function actualizarSugerencia(id, tipoSugerencia, titulo, contenido) {
        try {
            const response = await fetch(`${API_URL}?accion=actualizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id_sugerencia: id,
                    tipo_sugerencia: tipoSugerencia,
                    titulo: titulo,
                    contenido: contenido
                })
            });

            const data = await response.json();
            
            if (data.success) {
                await cargarSugerencias();
                return { success: true };
            } else {
                return { success: false, error: data.error || 'Error al actualizar' };
            }
        } catch (error) {
            console.error('Error al actualizar:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    // Cambiar estado (activar/desactivar)
    async function cambiarEstado(id, accion) {
        try {
            const response = await fetch(`${API_URL}?accion=${accion}&id_sugerencia=${id}`, {
                method: 'POST'
            });

            const data = await response.json();
            
            if (data.success) {
                await cargarSugerencias();
                return { success: true };
            } else {
                return { success: false, error: data.error || `Error al ${accion}` };
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    // ==================== FUNCIONES DE MODALES ====================

    // Abrir modal de crear
    if (btnAbrirModal && modalCrear) {
        btnAbrirModal.addEventListener('click', function() {
            modalCrear.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    // Botón "Mis creaciones" - Toggle entre mostrar todas y mostrar solo las del usuario
    if (btnMisCreaciones) {
        btnMisCreaciones.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle el filtro
            mostrarSoloMisCreaciones = !mostrarSoloMisCreaciones;
            
            // Cambiar estilo visual del botón - Sin hover blanco cuando está activo
            if (mostrarSoloMisCreaciones) {
                btnMisCreaciones.classList.add('bg-sena', 'text-white', 'border-sena');
                btnMisCreaciones.classList.remove('border-sena-border', 'bg-white', 'text-sena-text-main', 'hover:bg-sena-soft');
            } else {
                btnMisCreaciones.classList.remove('bg-sena', 'text-white', 'border-sena');
                btnMisCreaciones.classList.add('border-sena-border', 'bg-white', 'text-sena-text-main', 'hover:bg-sena-soft');
            }
            
            // Resetear página a 1
            paginaActual = 1;
            
            // Obtener sugerencias filtradas y ordenadas
            const sugerenciasFiltradas = obtenerSugerenciasFiltradas();
            renderizarTarjetas(sugerenciasFiltradas);
            
            // Ya no se muestran alertas al activar/desactivar el filtro
        });
    }

    // Abrir modal de editar
    function abrirModalEditar(btn) {
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const descripcion = btn.dataset.descripcion;
        const tipo = btn.dataset.tipo || '';
        
        idEditando = id;
        
        valoresOriginalesEditar = {
            tipo: tipo,
            titulo: nombre,
            descripcion: descripcion
        };
        
        if (inputTituloEditar) {
            inputTituloEditar.value = nombre;
        }
        if (textareaDescripcionEditar) {
            textareaDescripcionEditar.value = descripcion;
        }
        
        const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
        radiosEditar.forEach(radio => radio.checked = false);
        
        if (tipo) {
            let radioId = '';
            if (tipo === 'PROGRAMA') {
                radioId = 'editar-tipo-programa';
            } else if (tipo === 'LINEA_TECNOLOGICA') {
                radioId = 'editar-tipo-linea';
            } else if (tipo === 'OTROS') {
                radioId = 'editar-tipo-otros';
            }
            
            if (radioId) {
                const radioBtn = document.getElementById(radioId);
                if (radioBtn) {
                    radioBtn.checked = true;
                }
            }
        }
        
        if (formEditar) {
            formEditar.dataset.editandoId = id;
        }
        
        modalEditar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Abrir modal de deshabilitar
    function abrirModalDeshabilitar(id, nombre) {
        idDeshabilitando = id;
        nombreDeshabilitando = nombre;
        
        if (nombreSugerenciaDeshabilitar) {
            nombreSugerenciaDeshabilitar.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Abrir modal de habilitar
    function abrirModalHabilitar(id, nombre) {
        idHabilitando = id;
        nombreHabilitando = nombre;
        
        if (nombreSugerenciaHabilitar) {
            nombreSugerenciaHabilitar.textContent = `"${nombre}"`;
        }
        
        modalHabilitar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Cerrar modal de crear y editar
    btnCerrarModal.forEach(btn => {
        btn.addEventListener('click', function() {
            if (modalCrear && !modalCrear.classList.contains('hidden')) {
                modalCrear.classList.add('hidden');
                const radios = document.querySelectorAll('input[name="tipo_sugerencia"]');
                radios.forEach(radio => radio.checked = false);
            }
            if (modalEditar && !modalEditar.classList.contains('hidden')) {
                modalEditar.classList.add('hidden');
                idEditando = null;
                const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
                radiosEditar.forEach(radio => radio.checked = false);
                valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' };
            }
            document.body.style.overflow = '';
        });
    });

    // Cerrar modal de editar con su propio botón
    if (btnCerrarModalEditar) {
        btnCerrarModalEditar.forEach(btn => {
            btn.addEventListener('click', function() {
                if (modalEditar && !modalEditar.classList.contains('hidden')) {
                    modalEditar.classList.add('hidden');
                    idEditando = null;
                    const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
                    radiosEditar.forEach(radio => radio.checked = false);
                    valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' };
                    document.body.style.overflow = '';
                }
            });
        });
    }

    // Cerrar modal de detalles
    if (btnCerrarDetalles) {
        btnCerrarDetalles.forEach(btn => {
            btn.addEventListener('click', cerrarModalDetalles);
        });
    }

    // Cerrar modal de deshabilitar
    btnCerrarDeshabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
            modalDeshabilitar.classList.add('hidden');
            document.body.style.overflow = '';
            idDeshabilitando = null;
            nombreDeshabilitando = null;
        });
    });

    // Cerrar modal de habilitar
    btnCerrarHabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
            modalHabilitar.classList.add('hidden');
            document.body.style.overflow = '';
            idHabilitando = null;
            nombreHabilitando = null;
        });
    });

    // Confirmar deshabilitar
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            if (idDeshabilitando && nombreDeshabilitando) {
                const resultado = await cambiarEstado(idDeshabilitando, 'desactivar');
                
                if (resultado.success) {
                    modalDeshabilitar.classList.add('hidden');
                    abrirModalDeshabilitadoExito(nombreDeshabilitando);
                } else {
                    mostrarToastValidacion('Ocurrió un error al deshabilitar la sugerencia: ' + resultado.error, 'error');
                }
                
                idDeshabilitando = null;
                nombreDeshabilitando = null;
            }
        });
    }

    // Confirmar habilitar
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            if (idHabilitando && nombreHabilitando) {
                const resultado = await cambiarEstado(idHabilitando, 'activar');
                
                if (resultado.success) {
                    modalHabilitar.classList.add('hidden');
                    abrirModalHabilitadoExito(nombreHabilitando);
                } else {
                    mostrarToastValidacion('Ocurrió un error al habilitar la sugerencia: ' + resultado.error, 'error');
                }
                
                idHabilitando = null;
                nombreHabilitando = null;
            }
        });
    }

    // Manejar envío del formulario de crear
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const tipoSugerencia = document.querySelector('input[name="tipo_sugerencia"]:checked')?.value || '';
            const titulo = inputTitulo ? inputTitulo.value.trim() : '';
            const contenido = inputContenido ? inputContenido.value.trim() : '';
            
            if (!tipoSugerencia) {
                mostrarToastValidacion('Por favor selecciona un tipo de sugerencia', 'warning');
                return;
            }
            
            if (!titulo) {
                mostrarToastValidacion('Por favor ingresa un título para la sugerencia', 'warning');
                return;
            }
            
            if (!contenido) {
                mostrarToastValidacion('Por favor ingresa una descripción para la sugerencia', 'warning');
                return;
            }
            
            const resultado = await crearSugerencia(tipoSugerencia, titulo, contenido);
            
            if (resultado.success) {
                modalCrear.classList.add('hidden');
                formCrear.reset();
                const radios = document.querySelectorAll('input[name="tipo_sugerencia"]');
                radios.forEach(radio => radio.checked = false);
                abrirModalExito(titulo);
            } else {
                mostrarToastValidacion('Error al crear: ' + resultado.error, 'error');
            }
        });
    }

    // Manejar envío del formulario de editar
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!verificarCambiosEditar()) {
                mostrarToastValidacion('No se detectaron cambios. Modifica algún campo antes de guardar.', 'info');
                return;
            }
            
            const tipoSugerencia = document.querySelector('input[name="tipo_sugerencia_editar"]:checked')?.value || '';
            const titulo = inputTituloEditar ? inputTituloEditar.value.trim() : '';
            const descripcion = textareaDescripcionEditar ? textareaDescripcionEditar.value.trim() : '';
            const id = this.dataset.editandoId;
            
            if (!tipoSugerencia) {
                mostrarToastValidacion('Por favor selecciona un tipo de sugerencia', 'warning');
                return;
            }
            
            if (!titulo) {
                mostrarToastValidacion('Por favor ingresa un título para la sugerencia', 'warning');
                return;
            }
            
            if (!descripcion) {
                mostrarToastValidacion('Por favor ingresa una descripción para la sugerencia', 'warning');
                return;
            }
            
            const resultado = await actualizarSugerencia(id, tipoSugerencia, titulo, descripcion);
            
            if (resultado.success) {
                modalEditar.classList.add('hidden');
                formEditar.reset();
                delete formEditar.dataset.editandoId;
                const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
                radiosEditar.forEach(radio => radio.checked = false);
                valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' };
                abrirModalEditadoExito(titulo);
            } else {
                mostrarToastValidacion('Error al actualizar: ' + resultado.error, 'error');
            }
        });
    }

    // ==================== FUNCIONES DE MODALES DE ÉXITO ====================

    function cerrarModalExito() {
        if (modalExito) {
            modalExito.classList.add('hidden');
            document.body.style.overflow = '';
            
            if (timeoutExito) clearTimeout(timeoutExito);
            if (intervaloContador) clearInterval(intervaloContador);
            
            if (progressBar) progressBar.style.width = '0%';
            if (contadorSegundos) contadorSegundos.textContent = '3';
        }
    }

    function abrirModalExito(titulo) {
        if (nombreSugerenciaSpan) {
            nombreSugerenciaSpan.textContent = `"${titulo}"`;
        }
        
        modalExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundos) contadorSegundos.textContent = segundos;
        if (progressBar) {
            progressBar.style.width = '0%';
            void progressBar.offsetWidth;
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 50);
        }
        
        if (intervaloContador) clearInterval(intervaloContador);
        intervaloContador = setInterval(() => {
            segundos--;
            if (contadorSegundos) {
                contadorSegundos.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervaloContador);
            }
        }, 1000);
        
        if (timeoutExito) clearTimeout(timeoutExito);
        timeoutExito = setTimeout(() => {
            cerrarModalExito();
        }, 3000);
    }

    function cerrarModalEditadoExito() {
        if (modalEditadoExito) {
            modalEditadoExito.classList.add('hidden');
            document.body.style.overflow = '';
            
            if (timeoutExitoEditado) clearTimeout(timeoutExitoEditado);
            if (intervaloContadorEditado) clearInterval(intervaloContadorEditado);
            
            if (progressBarEditado) progressBarEditado.style.width = '0%';
            if (contadorSegundosEditado) contadorSegundosEditado.textContent = '3';
        }
    }

    function abrirModalEditadoExito(titulo) {
        if (!modalEditadoExito) {
            console.error('Modal de éxito editado no encontrado');
            return;
        }
        
        if (nombreProyeccionEditado) {
            nombreProyeccionEditado.textContent = `"${titulo}"`;
        }
        
        modalEditadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosEditado) contadorSegundosEditado.textContent = segundos;
        if (progressBarEditado) {
            progressBarEditado.style.width = '0%';
            void progressBarEditado.offsetWidth;
            setTimeout(() => {
                progressBarEditado.style.width = '100%';
            }, 50);
        }
        
        if (intervaloContadorEditado) clearInterval(intervaloContadorEditado);
        intervaloContadorEditado = setInterval(() => {
            segundos--;
            if (contadorSegundosEditado) {
                contadorSegundosEditado.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervaloContadorEditado);
            }
        }, 1000);
        
        if (timeoutExitoEditado) clearTimeout(timeoutExitoEditado);
        timeoutExitoEditado = setTimeout(() => {
            cerrarModalEditadoExito();
        }, 3000);
    }

    function cerrarModalDeshabilitadoExito() {
        if (modalDeshabilitadoExito) {
            modalDeshabilitadoExito.classList.add('hidden');
            document.body.style.overflow = '';
            
            if (timeoutExitoDeshabilitado) clearTimeout(timeoutExitoDeshabilitado);
            if (intervaloContadorDeshabilitado) clearInterval(intervaloContadorDeshabilitado);
            
            if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
            if (contadorSegundosDeshabilitado) contadorSegundosDeshabilitado.textContent = '3';
        }
    }

    function abrirModalDeshabilitadoExito(nombre) {
        if (!modalDeshabilitadoExito) {
            console.error('Modal de éxito deshabilitado no encontrado');
            return;
        }
        
        if (nombreProyeccionDeshabilitado) {
            nombreProyeccionDeshabilitado.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosDeshabilitado) contadorSegundosDeshabilitado.textContent = segundos;
        if (progressBarDeshabilitado) {
            progressBarDeshabilitado.style.width = '0%';
            void progressBarDeshabilitado.offsetWidth;
            setTimeout(() => {
                progressBarDeshabilitado.style.width = '100%';
            }, 50);
        }
        
        if (intervaloContadorDeshabilitado) clearInterval(intervaloContadorDeshabilitado);
        intervaloContadorDeshabilitado = setInterval(() => {
            segundos--;
            if (contadorSegundosDeshabilitado) {
                contadorSegundosDeshabilitado.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervaloContadorDeshabilitado);
            }
        }, 1000);
        
        if (timeoutExitoDeshabilitado) clearTimeout(timeoutExitoDeshabilitado);
        timeoutExitoDeshabilitado = setTimeout(() => {
            cerrarModalDeshabilitadoExito();
        }, 3000);
    }

    function cerrarModalHabilitadoExito() {
        if (modalHabilitadoExito) {
            modalHabilitadoExito.classList.add('hidden');
            document.body.style.overflow = '';
            
            if (timeoutExitoHabilitado) clearTimeout(timeoutExitoHabilitado);
            if (intervaloContadorHabilitado) clearInterval(intervaloContadorHabilitado);
            
            if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
            if (contadorSegundosHabilitado) contadorSegundosHabilitado.textContent = '3';
        }
    }

    function abrirModalHabilitadoExito(nombre) {
        if (!modalHabilitadoExito) {
            console.error('Modal de éxito habilitado no encontrado');
            return;
        }
        
        if (nombreProyeccionHabilitado) {
            nombreProyeccionHabilitado.textContent = `"${nombre}"`;
        }
        
        modalHabilitadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosHabilitado) contadorSegundosHabilitado.textContent = segundos;
        if (progressBarHabilitado) {
            progressBarHabilitado.style.width = '0%';
            void progressBarHabilitado.offsetWidth;
            setTimeout(() => {
                progressBarHabilitado.style.width = '100%';
            }, 50);
        }
        
        if (intervaloContadorHabilitado) clearInterval(intervaloContadorHabilitado);
        intervaloContadorHabilitado = setInterval(() => {
            segundos--;
            if (contadorSegundosHabilitado) {
                contadorSegundosHabilitado.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervaloContadorHabilitado);
            }
        }, 1000);
        
        if (timeoutExitoHabilitado) clearTimeout(timeoutExitoHabilitado);
        timeoutExitoHabilitado = setTimeout(() => {
            cerrarModalHabilitadoExito();
        }, 3000);
    }

    // Cerrar modal de éxito (crear)
    btnCerrarExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalExito);
    });

    // Cerrar modal de éxito (editar)
    btnCerrarEditadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalEditadoExito);
    });

    // Cerrar modal de éxito (deshabilitar)
    btnCerrarDeshabilitadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalDeshabilitadoExito);
    });

    // Cerrar modal de éxito (habilitar)
    btnCerrarHabilitadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalHabilitadoExito);
    });

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modalCrear && !modalCrear.classList.contains('hidden')) {
                modalCrear.classList.add('hidden');
                const radios = document.querySelectorAll('input[name="tipo_sugerencia"]');
                radios.forEach(radio => radio.checked = false);
                document.body.style.overflow = '';
            }
            if (modalDetalles && !modalDetalles.classList.contains('hidden')) {
                cerrarModalDetalles();
            }
            if (modalEditar && !modalEditar.classList.contains('hidden')) {
                modalEditar.classList.add('hidden');
                idEditando = null;
                const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
                radiosEditar.forEach(radio => radio.checked = false);
                valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' };
                document.body.style.overflow = '';
            }
            if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
                modalDeshabilitar.classList.add('hidden');
                document.body.style.overflow = '';
            }
            if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
                modalHabilitar.classList.add('hidden');
                document.body.style.overflow = '';
            }
            if (modalExito && !modalExito.classList.contains('hidden')) {
                cerrarModalExito();
            }
            if (modalEditadoExito && !modalEditadoExito.classList.contains('hidden')) {
                cerrarModalEditadoExito();
            }
            if (modalDeshabilitadoExito && !modalDeshabilitadoExito.classList.contains('hidden')) {
                cerrarModalDeshabilitadoExito();
            }
            if (modalHabilitadoExito && !modalHabilitadoExito.classList.contains('hidden')) {
                cerrarModalHabilitadoExito();
            }
        }
    });

    // Prevenir cierre al hacer clic dentro del modal
    [modalCrear, modalExito, modalEditar, modalEditadoExito, modalDeshabilitar, modalDeshabilitadoExito, modalHabilitar, modalHabilitadoExito, modalDetalles].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('fixed')) {
                    if (modal === modalExito) {
                        cerrarModalExito();
                    } else if (modal === modalEditadoExito) {
                        cerrarModalEditadoExito();
                    } else if (modal === modalDeshabilitadoExito) {
                        cerrarModalDeshabilitadoExito();
                    } else if (modal === modalHabilitadoExito) {
                        cerrarModalHabilitadoExito();
                    } else if (modal === modalDetalles) {
                        cerrarModalDetalles();
                    } else {
                        modal.classList.add('hidden');
                        document.body.style.overflow = '';
                        if (modal === modalCrear) {
                            const radios = document.querySelectorAll('input[name="tipo_sugerencia"]');
                            radios.forEach(radio => radio.checked = false);
                        }
                        if (modal === modalEditar) {
                            const radiosEditar = document.querySelectorAll('input[name="tipo_sugerencia_editar"]');
                            radiosEditar.forEach(radio => radio.checked = false);
                            valoresOriginalesEditar = { tipo: '', titulo: '', descripcion: '' };
                        }
                    }
                }
            });
        }
    });

    // Filtros
    const filtroBtns = document.querySelectorAll('.filtro-btn');
    filtroBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filtroBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filtro = this.dataset.filtro;
            filtrarTarjetas(filtro);
        });
    });

    // Cargar sugerencias al iniciar
    cargarSugerencias();
});