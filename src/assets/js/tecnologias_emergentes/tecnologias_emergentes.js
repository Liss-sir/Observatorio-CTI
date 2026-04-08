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
    const detalleAreaTecnologia = document.getElementById('detalle-area-tecnologia');

    // Elementos del modal de deshabilitar
    const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const spanTecnologiaDeshabilitar = document.getElementById('nombre-tecnologia-deshabilitar');

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

    // Variables para paginación
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let tecnologiasFiltradas = [];
    let ultimoTerminoBusqueda = '';
    let filtrarDesactivadosActivo = false;

    // ===== CONSTANTES =====
    const MIN_DESCRIPCION_LENGTH = 30;

    // ===== FUNCIÓN PARA VALIDAR DESCRIPCIÓN =====
    function validarDescripcion(descripcion) {
        if (!descripcion || descripcion.length < MIN_DESCRIPCION_LENGTH) {
            return false;
        }
        return true;
    }

    // ===== FUNCIÓN PARA ORDENAR TECNOLOGÍAS =====
    function ordenarTecnologias(tecnologias) {
        return [...tecnologias].sort((a, b) => {
            if (a.estado !== b.estado) {
                return b.estado - a.estado;
            }
            return b.id_tendencia - a.id_tendencia;
        });
    }

    // ===== FUNCIÓN PARA OBTENER TECNOLOGÍAS DE LA PÁGINA ACTUAL =====
    function obtenerTecnologiasPagina(tecnologias) {
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        return tecnologias.slice(inicio, fin);
    }

    // ===== FUNCIÓN PARA ACTUALIZAR PAGINACIÓN MEJORADA =====
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
                    <span class="font-medium text-sena">${totalElementos}</span> tecnologías
                </div>
                ` : `
                <div class="text-sm text-sena-text-soft">
                    Total: <span class="font-medium text-sena">${totalElementos}</span> tecnologías ${filtrarDesactivadosActivo ? 'deshabilitadas' : ''}
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
                        Tecnologías Inactivas
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
                filtrarTecnologias();
            });
        }
        
        // Solo agregar eventos de paginación si hay más de 1 página
        if (totalPaginas > 1) {
            document.querySelectorAll('.btn-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    paginaActual = parseInt(btn.dataset.pagina);
                    renderizarTecnologias(tecnologiasFiltradas);
                });
            });
            
            document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual > 1) {
                        paginaActual--;
                        renderizarTecnologias(tecnologiasFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual < totalPaginas) {
                        paginaActual++;
                        renderizarTecnologias(tecnologiasFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== 1) {
                        paginaActual = 1;
                        renderizarTecnologias(tecnologiasFiltradas);
                    }
                });
            });
            
            document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== totalPaginas) {
                        paginaActual = totalPaginas;
                        renderizarTecnologias(tecnologiasFiltradas);
                    }
                });
            });
        }
    }

    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL CREAR =====
    function actualizarContadorCrear() {
        const textarea = document.getElementById('descripcion-crear');
        const contadorSpan = document.getElementById('contador-caracteres-crear');
        const alertaSpan = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.getElementById('btn-submit-crear');
        
        if (textarea && contadorSpan) {
            const longitud = textarea.value.length;
            contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contadorSpan.classList.remove('text-red-500', 'text-sena-text-soft');
                contadorSpan.classList.add('text-sena');
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else {
                contadorSpan.classList.remove('text-sena', 'text-green-600');
                contadorSpan.classList.add('text-red-500');
                if (alertaSpan) alertaSpan.classList.remove('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        }
    }

    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL EDITAR =====
    function actualizarContadorEditar() {
        const textarea = document.getElementById('descripcion-editar');
        const contadorSpan = document.getElementById('contador-caracteres-editar');
        const alertaSpan = document.getElementById('alerta-minimo-editar');
        const btnSubmit = document.getElementById('btn-submit-editar');
        
        if (textarea && contadorSpan) {
            const longitud = textarea.value.length;
            contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            
            if (longitud >= MIN_DESCRIPCION_LENGTH) {
                contadorSpan.classList.remove('text-red-500', 'text-sena-text-soft');
                contadorSpan.classList.add('text-sena');
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            } else if (longitud > 0 && longitud < MIN_DESCRIPCION_LENGTH) {
                contadorSpan.classList.remove('text-sena', 'text-sena-text-soft');
                contadorSpan.classList.add('text-red-500');
                if (alertaSpan) {
                    alertaSpan.classList.remove('hidden');
                    alertaSpan.classList.add('text-red-500');
                }
                if (btnSubmit) btnSubmit.disabled = true;
            } else {
                contadorSpan.classList.remove('text-red-500', 'text-sena');
                contadorSpan.classList.add('text-sena-text-soft');
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        }
    }

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
        
        // Iconos corregidos
        const iconos = {
            info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 9v4"/>
                <path d="M12 17h.01"/>
                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
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

    // ===== FUNCIONES DE API =====
    async function cargarTecnologias() {
        try {
            const response = await fetch(`${API_URL}?accion=listarTodas`);
            const data = await response.json();
            
            if (data.status === 'success') {
                tecnologiasActuales = data.data;
                const ordenadas = ordenarTecnologias(tecnologiasActuales);
                tecnologiasFiltradas = ordenadas; // Agregar esta línea
                renderizarTecnologias(ordenadas);
                actualizarContador(ordenadas.length);
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
                body: JSON.stringify({
                    id_area: data.id_area,
                    nombre: data.descripcion,
                    estado: data.estado
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const area = areasActuales.find(a => a.id_area == data.id_area);
                const nuevaTecnologia = {
                    id_tendencia: result.id_tendencia,
                    id_area: data.id_area,
                    nombre: data.descripcion,
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
                body: JSON.stringify({
                    id_tendencia: data.id_tendencia,
                    id_area: data.id_area,
                    nombre: data.descripcion,
                    estado: data.estado
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const tecnologiaIndex = tecnologiasActuales.findIndex(t => t.id_tendencia == data.id_tendencia);
                if (tecnologiaIndex !== -1) {
                    tecnologiasActuales[tecnologiaIndex] = {
                        ...tecnologiasActuales[tecnologiaIndex],
                        nombre: data.descripcion,
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
            const response = await fetch(`${API_URL}?accion=verificarNombre`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    nombre: descripcion,
                    id_area: id_area, 
                    excluir_id: excluir_id 
                })
            });
            
            const result = await response.json();
            return result.existe;
        } catch (error) {
            console.error('Error al verificar descripción:', error);
            return false;
        }
    }

    // ===== FUNCIONES VISUALES ====
    function renderizarTecnologias(tecnologias) {
        const contenedor = document.getElementById('contenedor-tecnologias');
        
        if (!contenedor) return;
        
        // Asegurarse de que tecnologias sea un array y esté ordenado
        const ordenadas = ordenarTecnologias(tecnologias);
        
        contenedor.innerHTML = '';
        
        if (ordenadas.length === 0) {
            const terminoBusqueda = document.getElementById('buscador-tecnologias')?.value.trim() || '';
            const hayBusquedaActiva = terminoBusqueda.length > 0;
            
            contenedor.className = '';
            
            if (hayBusquedaActiva || filtrarDesactivadosActivo) {
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <h3 class=" text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm">
                            ${filtrarDesactivadosActivo ? 'No hay tecnologías deshabilitadas.' : `No hay tecnologías que coincidan con "${terminoBusqueda}".`}
                        </p>
                    </div>
                `;
            } else {
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-sena">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        </div>
                        <h3 class=" text-lg font-semibold text-sena-text-main mb-2">No hay tecnologías emergentes</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primera tecnología emergente.</p>
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
            }
            
            const paginacionContainer = document.getElementById('paginacion-container');
            if (paginacionContainer) paginacionContainer.classList.add('hidden');
            return;
        }
        
        // Asegurar que la página actual no exceda el total de páginas
        const totalPaginas = Math.ceil(ordenadas.length / elementosPorPagina);
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }
        
        contenedor.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-stretch';
        
        const tecnologiasPagina = obtenerTecnologiasPagina(ordenadas);
        
        tecnologiasPagina.forEach(tecnologia => {
            const tarjeta = crearTarjetaHTML(tecnologia);
            contenedor.appendChild(tarjeta);
        });
        
        asignarEventosTarjetas();
        actualizarContador(ordenadas.length);
        actualizarPaginacion(ordenadas.length);
    }

    function crearTarjetaHTML(tecnologia) {
        const div = document.createElement('div');
        const estaDeshabilitada = tecnologia.estado != 1;
        const opacidadClass = estaDeshabilitada ? 'opacity-60' : '';
        
        div.className = `border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia flex flex-col h-full ${opacidadClass}`;
        div.setAttribute('data-id', tecnologia.id_tendencia);
        div.setAttribute('data-area', tecnologia.id_area);
        div.setAttribute('data-nombre', tecnologia.nombre);
        div.setAttribute('data-descripcion', tecnologia.nombre);
        div.setAttribute('data-estado', tecnologia.estado == 1 ? 'activo' : 'inactivo');
        
        div.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tecnología" data-id="${tecnologia.id_tendencia}" data-nombre="${tecnologia.nombre.replace(/"/g, '&quot;')}" data-descripcion="${tecnologia.nombre.replace(/"/g, '&quot;')}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${tecnologia.estado == 1 ? 'active' : ''}" title="${tecnologia.estado == 1 ? 'Activo' : 'Inactivo'}"></div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-sena-text-soft line-clamp-3">${tecnologia.nombre}</p>
            </div>
            <div class="mt-2 text-xs text-sena-text-soft">
                <hr class="my-1 border-sena-border mb-2">
                <div class="flex items-center gap-2">
                    <span class="estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${tecnologia.estado == 1 ? 'bg-sena' : 'bg-gray-400'}"></span>
                    <span class="badge-area">${tecnologia.nombre_area || 'Área'}</span>
                </div>
            </div>
        `;
        
        return div;
    }

    function agregarTarjetaVisual(tecnologia) {
        const contenedor = document.getElementById('contenedor-tecnologias');
        if (contenedor) {
            // Volver a aplicar el filtro actual
            filtrarTecnologias();
        }
    }

    function actualizarSwitchVisual(id, activar) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach(tarjeta => {
            if (tarjeta.getAttribute('data-id') == id) {
                const switchEl = tarjeta.querySelector('.switch-sena');
                const estadoBolita = tarjeta.querySelector('.estado-bolita');
                
                if (activar) {
                    tarjeta.classList.remove('opacity-60');
                } else {
                    tarjeta.classList.add('opacity-60');
                }
                
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
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${activar ? 'bg-sena' : 'bg-gray-400'}`;
                }
                tarjeta.setAttribute('data-estado', activar ? 'activo' : 'inactivo');
            }
        });
        
        renderizarTecnologias(tecnologiasActuales);
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
                
                if (tituloEl) tituloEl.textContent = data.descripcion;
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
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${data.estado == 1 ? 'bg-sena' : 'bg-gray-400'}`;
                }
                tarjeta.setAttribute('data-nombre', data.descripcion);
                tarjeta.setAttribute('data-descripcion', data.descripcion);
                tarjeta.setAttribute('data-estado', data.estado == 1 ? 'activo' : 'inactivo');
                tarjeta.setAttribute('data-area', data.id_area);
            }
        });
    }

    function actualizarContador(total) {
        const contador = document.getElementById('total-tecnologias');
        if (contador) {
            contador.textContent = total;
        }
    }

    function filtrarTecnologias() {
        const terminoBusqueda = document.getElementById('buscador-tecnologias').value.toLowerCase();
        
        let filtradas = tecnologiasActuales;
        
        // Filtrar por término de búsqueda
        if (terminoBusqueda) {
            filtradas = filtradas.filter(t => 
                t.nombre.toLowerCase().includes(terminoBusqueda) ||
                (t.nombre_area && t.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        // Filtrar por estado (desactivados)
        if (filtrarDesactivadosActivo) {
            filtradas = filtradas.filter(t => t.estado === 0);
        }
        
        // Ordenar las tecnologías
        const ordenadas = ordenarTecnologias(filtradas);
        
        // ACTUALIZAR tecnologiasFiltradas
        tecnologiasFiltradas = ordenadas;
        
        // Resetear página al filtrar
        paginaActual = 1;
        
        renderizarTecnologias(ordenadas);
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
        
        const textareaCrear = document.getElementById('descripcion-crear');
        if (textareaCrear) {
            textareaCrear.value = '';
            actualizarContadorCrear();
        }
        
        const contadorSpan = document.getElementById('contador-caracteres-crear');
        const alertaSpan = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.getElementById('btn-submit-crear');
        
        if (contadorSpan) {
            contadorSpan.classList.remove('text-red-500', 'text-sena');
            contadorSpan.classList.add('text-sena-text-soft');
            contadorSpan.textContent = `0 / ${MIN_DESCRIPCION_LENGTH} caracteres`;
        }
        
        if (alertaSpan) {
            alertaSpan.classList.add('hidden');
            alertaSpan.classList.remove('text-sena-text-soft');
            alertaSpan.classList.add('text-sena-text-soft');
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

    function abrirModalEditar(id, nombre, descripcion) {
        tecnologiaEditadaId = id;
        
        const areaSelect = document.getElementById('area-tecnologia');
        if (areaSelect && areasActuales.length > 0) {
            areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
            areasActuales.forEach(area => {
                areaSelect.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
            });
        }
        
        if (textareaDescripcionEditar) {
            textareaDescripcionEditar.value = descripcion;
            
            const contadorSpan = document.getElementById('contador-caracteres-editar');
            const alertaSpan = document.getElementById('alerta-minimo-editar');
            const btnSubmit = document.getElementById('btn-submit-editar');
            
            if (contadorSpan) {
                contadorSpan.classList.remove('text-red-500', 'text-sena', 'text-sena-text-soft');
                contadorSpan.classList.add('text-sena-text-soft');
                const longitud = descripcion.length;
                contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
            }
            
            if (alertaSpan) {
                alertaSpan.classList.add('hidden');
                alertaSpan.classList.remove('text-red-500');
            }
            
            if (btnSubmit) {
                btnSubmit.disabled = false;
            }
        }
        
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
        const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == id);
        
        if (detalleTitulo) detalleTitulo.textContent = `Detalle: ${nombre}`;
        
        if (detalleAreaTecnologia && tecnologia) {
            detalleAreaTecnologia.textContent = tecnologia.nombre_area || 'Área no especificada';
        }
        
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
        if (spanTecnologiaDeshabilitar) {
            spanTecnologiaDeshabilitar.textContent = `"${nombre}"`;
        }
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
        if (spanTecnologiaHabilitar) {
            spanTecnologiaHabilitar.textContent = `"${nombre}"`;
        }
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
        if (!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') {
            if (spanTecnologiaDeshabilitar) {
                let nombreSpan = spanTecnologiaDeshabilitar.textContent;
                if (nombreSpan && nombreSpan !== 'null' && nombreSpan !== '') {
                    nombreTecnologia = nombreSpan.replace(/^"|"$/g, '');
                }
            }
            if ((!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') && tecnologiaDeshabilitarId) {
                const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == tecnologiaDeshabilitarId);
                if (tecnologia && tecnologia.nombre) {
                    nombreTecnologia = tecnologia.nombre;
                }
            }
        }
        
        if (!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') {
            nombreTecnologia = 'Tecnología sin nombre';
        }
        
        if (nombreTecnologiaDeshabilitadaSpan) {
            nombreTecnologiaDeshabilitadaSpan.textContent = `"${nombreTecnologia}"`;
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

    function abrirModalConfirmacionHabilitar(nombreTecnologia) {
        if (!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') {
            if (spanTecnologiaHabilitar) {
                let nombreSpan = spanTecnologiaHabilitar.textContent;
                if (nombreSpan && nombreSpan !== 'null' && nombreSpan !== '') {
                    nombreTecnologia = nombreSpan.replace(/^"|"$/g, '');
                }
            }
            if ((!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') && tecnologiaHabilitarId) {
                const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == tecnologiaHabilitarId);
                if (tecnologia && tecnologia.nombre) {
                    nombreTecnologia = tecnologia.nombre;
                }
            }
        }
        
        if (!nombreTecnologia || nombreTecnologia === 'null' || nombreTecnologia === '') {
            nombreTecnologia = 'Tecnología sin nombre';
        }
        
        if (nombreTecnologiaHabilitadaSpan) {
            nombreTecnologiaHabilitadaSpan.textContent = `"${nombreTecnologia}"`;
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
                
                if (textareaDescripcionEditar) textareaDescripcionEditar.value = tecnologiaSeleccionada.nombre;
                if (areaSelect) areaSelect.value = tecnologiaSeleccionada.id_area;
                if (selectEstadoEditar) selectEstadoEditar.value = tecnologiaSeleccionada.estado == 1 ? 'activo' : 'inactivo';
                
                if (modalEditar) modalEditar.setAttribute('data-id', id);
                abrirModalEditar(id, tecnologiaSeleccionada.nombre, tecnologiaSeleccionada.nombre);
            });
        }
        
        if (switchEl) {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                let nombre = tarjeta.querySelector('h3')?.textContent;
                
                if (!nombre || nombre === '' || nombre === 'null') {
                    nombre = tarjeta.getAttribute('data-nombre');
                }
                
                if (!nombre || nombre === '' || nombre === 'null') {
                    const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == id);
                    if (tecnologia) {
                        nombre = tecnologia.nombre;
                    }
                }
                
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
            const nombre = tecnologia.nombre;
            const estado = tecnologia.estado == 1 ? 'activo' : 'inactivo';
            abrirModalDetalle(id, nombre, tecnologia.nombre, estado);
        });
    }

    // ===== EVENTOS PRINCIPALES =====
    
    cargarTecnologias();
    cargarAreas();
    
    const buscador = document.getElementById('buscador-tecnologias');
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
    
    const textareaCrear = document.getElementById('descripcion-crear');
    if (textareaCrear) {
        textareaCrear.addEventListener('input', actualizarContadorCrear);
    }
    
    const textareaEditar = document.getElementById('descripcion-editar');
    if (textareaEditar) {
        textareaEditar.addEventListener('input', actualizarContadorEditar);
    }
    
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            const areaSelect = this.querySelector('select[name="area"]');
            const descripcionTextarea = document.getElementById('descripcion-crear');
            
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
            
            if (!validarDescripcion(descripcion)) {
                mostrarToastValidacion(`La descripción debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres. Actualmente tiene ${descripcion.length} caracteres.`, 'warning');
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
                abrirModalConfirmacionCrear(descripcion);
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
            
            if (!validarDescripcion(descripcion)) {
                mostrarToastValidacion(`La descripción debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres. Actualmente tiene ${descripcion.length} caracteres.`, 'warning');
                return;
            }
            
            const datosOriginales = tecnologiaSeleccionada;
            const descripcionCambio = descripcion !== datosOriginales?.nombre;
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
                abrirModalConfirmacionEditar(descripcion);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al actualizar la tecnología', 'error');
            }
        });
    }
    
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            let nombreParaMostrar = tecnologiaDeshabilitarNombre;
            if ((!nombreParaMostrar || nombreParaMostrar === 'null' || nombreParaMostrar === '') && tecnologiaDeshabilitarId) {
                const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == tecnologiaDeshabilitarId);
                if (tecnologia && tecnologia.nombre) {
                    nombreParaMostrar = tecnologia.nombre;
                }
            }
            
            if (tecnologiaDeshabilitarId && nombreParaMostrar) {
                const resultado = await cambiarEstadoTecnologia(tecnologiaDeshabilitarId, 'desactivar');
                if (resultado.success) {
                    cerrarModalDeshabilitar();
                    abrirModalConfirmacionDeshabilitar(nombreParaMostrar);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al deshabilitar la tecnología', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la tecnología', 'error');
            }
        });
    }
    
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            let nombreParaMostrar = tecnologiaHabilitarNombre;
            if ((!nombreParaMostrar || nombreParaMostrar === 'null' || nombreParaMostrar === '') && tecnologiaHabilitarId) {
                const tecnologia = tecnologiasActuales.find(t => t.id_tendencia == tecnologiaHabilitarId);
                if (tecnologia && tecnologia.nombre) {
                    nombreParaMostrar = tecnologia.nombre;
                }
            }
            
            if (tecnologiaHabilitarId && nombreParaMostrar) {
                const resultado = await cambiarEstadoTecnologia(tecnologiaHabilitarId, 'activar');
                if (resultado.success) {
                    cerrarModalHabilitar();
                    abrirModalConfirmacionHabilitar(nombreParaMostrar);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al habilitar la tecnología', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la tecnología', 'error');
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