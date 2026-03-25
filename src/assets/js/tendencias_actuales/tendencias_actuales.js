document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    const API_URL = '../../controllers/EtapaDesarrolloController.php';
    
    // ===== VARIABLES DE MODALES =====
    const modalEditar = document.getElementById('modal-editar-tendencia');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-confirmacion');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-tendencias-act');
    const modalDeshabilitadoConfirmacion = document.getElementById('modal-deshabilitado-tendencia-act');
    const modalHabilitar = document.getElementById('modal-habilitar-tendencia');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion-tendencia_act');
    const modalCrear = document.getElementById('modal-crear-tendencia-actual');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-confirmacion-tendencia-actual');
    const modalDetalle = document.getElementById('modal-detalle-tendencia');
    
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
    const btnCrearTendencia = document.getElementById('btn-crear-tendencia');

    // Variables para el contador y progreso
    let timeoutEditado = null;
    let intervalContadorEditado = null;
    let timeoutDeshabilitado = null;
    let intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let timeoutCreado = null;
    let intervalContadorCreado = null;
    
    // Variables para almacenar datos actuales
    let etapasActuales = [];
    let areasActuales = [];
    let etapaSeleccionada = null;
    
    // Variables para paginación
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let etapasFiltradas = [];
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

    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL CREAR =====
    function actualizarContadorCrear() {
        const textarea = document.getElementById('nombre-crear');
        const contadorSpan = document.getElementById('contador-caracteres-crear');
        const alertaSpan = document.getElementById('alerta-minimo-crear');
        const btnSubmit = document.getElementById('btn-submit-etapa');
        
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
                if (btnSubmit) btnSubmit.disabled = true;
            }
        }
    }

    // ===== FUNCIÓN PARA ACTUALIZAR CONTADOR DE CARACTERES EN MODAL EDITAR =====
    function actualizarContadorEditar() {
        const textarea = document.getElementById('nombre-tendencia');
        const contadorSpan = document.getElementById('contador-caracteres-editar');
        const alertaSpan = document.getElementById('alerta-minimo-editar');
        const btnSubmit = document.getElementById('btn-submit-editar-etapa');
        
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
                if (btnSubmit) btnSubmit.disabled = true;
            }
        }
    }

    // ===== FUNCIÓN PARA ORDENAR ETAPAS =====
    function ordenarEtapas(etapas) {
        return [...etapas].sort((a, b) => {
            if (a.estado !== b.estado) {
                return b.estado - a.estado;
            }
            return b.id_etapa - a.id_etapa;
        });
    }

    // ===== FUNCIÓN PARA OBTENER ETAPAS DE LA PÁGINA ACTUAL =====
    function obtenerEtapasPagina(etapas) {
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        return etapas.slice(inicio, fin);
    }

    // ===== FUNCIÓN PARA ACTUALIZAR PAGINACIÓN =====
    function actualizarPaginacion(totalElementos) {
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        const paginacionContainer = document.getElementById('paginacion-container');
        
        if (!paginacionContainer) return;
        
        if (totalPaginas <= 1 && !filtrarDesactivadosActivo) {
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
        }
        
        let paginacionHTML = `
            <div class="flex flex-col items-center gap-3 mb-6">
                ${totalPaginas > 1 ? `
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${((paginaActual - 1) * elementosPorPagina) + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> etapas
                </div>
                ` : `
                <div class="text-sm text-sena-text-soft">
                    Total: <span class="font-medium text-sena">${totalElementos}</span> etapas ${filtrarDesactivadosActivo ? 'inactivas' : ''}
                </div>
                `}
                
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    <button id="btn-filtro-desactivados-paginacion" class="flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-lg whitespace-nowrap h-10 transition-all ${filtrarDesactivadosActivo 
                        ? 'bg-sena text-white border-sena cursor-default' 
                        : 'border-sena-border text-sena-text-soft hover:bg-sena-soft hover:border-sena/30'}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M3 7v10a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V7"/>
                            <path d="M3 7h18M8 3h8M6 7h1M17 7h1"/>
                        </svg>
                        Etapas Inactivas
                    </button>
                    
                    ${totalPaginas > 1 ? `
                    <div class="w-px h-6 bg-sena-border"></div>
                    <button class="btn-primera-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="btn-pagina-anterior px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    ${paginasHTML}
                    <button class="btn-pagina-siguiente px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button class="btn-ultima-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M13 5l7 7-7 7M6 5l7 7-7 7" />
                        </svg>
                    </button>
                    ` : ''}
                </div>
            </div>
        `;
        
        paginacionContainer.innerHTML = paginacionHTML;
        
        const btnFiltro = document.getElementById('btn-filtro-desactivados-paginacion');
        if (btnFiltro) {
            const newBtn = btnFiltro.cloneNode(true);
            btnFiltro.parentNode.replaceChild(newBtn, btnFiltro);
            newBtn.addEventListener('click', function() {
                filtrarDesactivadosActivo = !filtrarDesactivadosActivo;
                if (filtrarDesactivadosActivo) {
                    this.classList.add('bg-sena', 'text-white', 'border-sena', 'cursor-default');
                    this.classList.remove('border-sena-border', 'text-sena-text-soft', 'hover:bg-sena-soft', 'hover:border-sena/30');
                } else {
                    this.classList.remove('bg-sena', 'text-white', 'border-sena', 'cursor-default');
                    this.classList.add('border-sena-border', 'text-sena-text-soft', 'hover:bg-sena-soft', 'hover:border-sena/30');
                }
                paginaActual = 1;
                filtrarEtapas();
            });
        }
        
        if (totalPaginas > 1) {
            document.querySelectorAll('.btn-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    paginaActual = parseInt(btn.dataset.pagina);
                    renderizarEtapas(etapasFiltradas);
                });
            });
            document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual > 1) {
                        paginaActual--;
                        renderizarEtapas(etapasFiltradas);
                    }
                });
            });
            document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual < totalPaginas) {
                        paginaActual++;
                        renderizarEtapas(etapasFiltradas);
                    }
                });
            });
            document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== 1) {
                        paginaActual = 1;
                        renderizarEtapas(etapasFiltradas);
                    }
                });
            });
            document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (paginaActual !== totalPaginas) {
                        paginaActual = totalPaginas;
                        renderizarEtapas(etapasFiltradas);
                    }
                });
            });
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
    async function cargarEtapas() {
        try {
            const response = await fetch(`${API_URL}?accion=listarTodas`);
            const data = await response.json();
            
            if (data.status === 'success') {
                etapasActuales = data.data;
                renderizarEtapas(etapasActuales);
                actualizarContador(etapasActuales.length);
            }
        } catch (error) {
            console.error('Error al cargar etapas:', error);
            mostrarToastValidacion('Error al cargar las etapas', 'error');
        }
    }

    async function cargarAreas() {
        try {
            const response = await fetch('../../controllers/AreaController.php?accion=listar');
            const data = await response.json();
            
            if (data.status === 'success') {
                areasActuales = data.data;
                
                const selectCrear = document.querySelector('#form-nueva-tendencia-actual select[name="area"]');
                const selectEditar = document.getElementById('area-tendencia');
                
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

    async function crearEtapa(data) {
        try {
            const response = await fetch(`${API_URL}?accion=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_area: data.id_area,
                    nombre: data.nombre,
                    estado: data.estado
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const area = areasActuales.find(a => a.id_area == data.id_area);
                const nuevaEtapa = {
                    id_etapa: result.id_etapa,
                    id_area: data.id_area,
                    nombre: data.nombre,
                    estado: data.estado,
                    nombre_area: area ? area.nombre_area : 'Área'
                };
                
                etapasActuales.push(nuevaEtapa);
                renderizarEtapas(etapasActuales);
                actualizarContador(etapasActuales.length);
                return { success: true, id: result.id_etapa };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al crear etapa:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function actualizarEtapa(data) {
        try {
            const response = await fetch(`${API_URL}?accion=actualizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_etapa: data.id_etapa,
                    id_area: data.id_area,
                    nombre: data.nombre,
                    estado: data.estado
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const index = etapasActuales.findIndex(e => e.id_etapa == data.id_etapa);
                if (index !== -1) {
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    etapasActuales[index] = {
                        ...etapasActuales[index],
                        nombre: data.nombre,
                        id_area: data.id_area,
                        estado: data.estado,
                        nombre_area: area ? area.nombre_area : 'Área'
                    };
                }
                
                renderizarEtapas(etapasActuales);
                actualizarContador(etapasActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error('Error al actualizar etapa:', error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function cambiarEstadoEtapa(id, accion) {
        try {
            const response = await fetch(`${API_URL}?accion=${accion}&id_etapa=${id}`, {
                method: 'POST'
            });
            
            const result = await response.json();
            
            if (result.success) {
                const index = etapasActuales.findIndex(e => e.id_etapa == id);
                if (index !== -1) {
                    etapasActuales[index].estado = accion === 'activar' ? 1 : 0;
                }
                
                renderizarEtapas(etapasActuales);
                actualizarContador(etapasActuales.length);
                return { success: true };
            } else {
                return { success: false, error: result.error };
            }
        } catch (error) {
            console.error(`Error al ${accion} etapa:`, error);
            return { success: false, error: 'Error de conexión' };
        }
    }

    async function verificarNombreExistente(nombre, id_area, excluir_id = null) {
        try {
            const response = await fetch(`${API_URL}?accion=verificarNombre`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    nombre: nombre,
                    id_area: id_area, 
                    excluir_id: excluir_id 
                })
            });
            
            const result = await response.json();
            return result.existe;
        } catch (error) {
            console.error('Error al verificar nombre:', error);
            return false;
        }
    }

    // ===== FUNCIONES VISUALES =====
    function renderizarEtapas(etapas) {
        const contenedor = document.getElementById('contenedor-tendencias');
        const terminoBusqueda = document.getElementById('buscador-tendencias')?.value.trim() || '';
        
        if (!contenedor) return;
        
        let filtradas = etapas;
        if (terminoBusqueda) {
            filtradas = filtradas.filter(e => 
                e.nombre.toLowerCase().includes(terminoBusqueda) ||
                (e.nombre_area && e.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        if (filtrarDesactivadosActivo) {
            filtradas = filtradas.filter(e => e.estado != 1);
        }
        
        etapasFiltradas = filtradas;
        
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
                        <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm">
                            ${filtrarDesactivadosActivo ? 'No hay etapas inactivas.' : `No hay etapas que coincidan con "${terminoBusqueda}".`}
                        </p>
                    </div>
                `;
            } else {
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 6.75h2.25c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 8.625 20.625V7.875c0-.621.504-1.125 1.125-1.125ZM16.5 3.75h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15.375 20.625V4.875c0-.621.504-1.125 1.125-1.125Z"/>
                            </svg>
                        </div>
                        <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No hay etapas de desarrollo</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primera etapa de desarrollo.</p>
                        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Crear primera etapa
                        </button>
                    </div>
                `;
                
                const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
                if (btnCrearEmpty) {
                    btnCrearEmpty.addEventListener('click', function() {
                        const form = document.getElementById('form-nueva-tendencia-actual');
                        if (form) form.reset();
                        abrirModal(modalCrear);
                    });
                }
            }
            
            const paginacionContainer = document.getElementById('paginacion-container');
            if (paginacionContainer) paginacionContainer.classList.add('hidden');
            return;
        }
        
        const totalPaginas = Math.ceil(filtradas.length / elementosPorPagina);
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }
        
        contenedor.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 items-stretch';
        
        const etapasPagina = obtenerEtapasPagina(filtradas);
        
        etapasPagina.forEach(etapa => {
            const tarjeta = crearTarjetaHTML(etapa);
            contenedor.appendChild(tarjeta);
        });
        
        asignarEventosTarjetas();
        actualizarContador(filtradas.length);
        actualizarPaginacion(filtradas.length);
    }

    function crearTarjetaHTML(etapa) {
        const div = document.createElement('div');
        const estaDeshabilitada = etapa.estado != 1;
        const opacidadClass = estaDeshabilitada ? 'opacity-60' : '';
        
        div.className = `border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia flex flex-col h-full ${opacidadClass}`;
        div.setAttribute('data-id', etapa.id_etapa);
        div.setAttribute('data-area', etapa.id_area);
        div.setAttribute('data-nombre', etapa.nombre);
        div.setAttribute('data-estado', etapa.estado == 1 ? 'activo' : 'inactivo');
        
        div.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 6.75h2.25c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 8.625 20.625V7.875c0-.621.504-1.125 1.125-1.125ZM16.5 3.75h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15.375 20.625V4.875c0-.621.504-1.125 1.125-1.125Z" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar etapa" data-id="${etapa.id_etapa}" data-nombre="${etapa.nombre.replace(/"/g, '&quot;')}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${etapa.estado == 1 ? 'active' : ''}" title="${etapa.estado == 1 ? 'Activo' : 'Inactivo'}" data-id="${etapa.id_etapa}" data-nombre="${etapa.nombre.replace(/"/g, '&quot;')}"></div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-sena-text-soft line-clamp-3">${etapa.nombre}</p>
            </div>
            <div class="mt-2 text-xs text-sena-text-soft">
                <hr class="my-1 border-sena-border mb-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${etapa.estado == 1 ? 'bg-sena' : 'bg-gray-400'}"></span>
                        <span class="badge-area">${etapa.nombre_area || 'Área'}</span>
                    </div>
                </div>
            </div>
        `;
        
        return div;
    }

    function actualizarContador(total) {
        const contador = document.getElementById('total-tendencias');
        if (contador) {
            contador.textContent = total;
        }
    }

    function filtrarEtapas() {
        const terminoBusqueda = document.getElementById('buscador-tendencias').value.toLowerCase();
        
        let filtradas = etapasActuales;
        
        if (terminoBusqueda) {
            filtradas = filtradas.filter(e => 
                e.nombre.toLowerCase().includes(terminoBusqueda) ||
                (e.nombre_area && e.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        if (filtrarDesactivadosActivo) {
            filtradas = filtradas.filter(e => e.estado != 1);
        }
        
        paginaActual = 1;
        renderizarEtapas(filtradas);
    }

    // ===== FUNCIONES DE MODALES =====
    function abrirModal(modal) {
        if (modal) {
            // Si es el modal de crear, resetear el contador
            if (modal === modalCrear) {
                const contadorSpan = document.getElementById('contador-caracteres-crear');
                const alertaSpan = document.getElementById('alerta-minimo-crear');
                const btnSubmit = document.getElementById('btn-submit-etapa');
                const textarea = document.getElementById('nombre-crear');
                
                if (textarea) textarea.value = '';
                if (contadorSpan) {
                    contadorSpan.classList.remove('text-red-500', 'text-sena');
                    contadorSpan.classList.add('text-sena-text-soft');
                    contadorSpan.textContent = `0 / ${MIN_DESCRIPCION_LENGTH} caracteres`;
                }
                if (alertaSpan) alertaSpan.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = true;
            }
            
            // Si es el modal de editar, cargar el contador con la longitud actual
            if (modal === modalEditar && etapaSeleccionada) {
                const contadorSpan = document.getElementById('contador-caracteres-editar');
                const alertaSpan = document.getElementById('alerta-minimo-editar');
                const btnSubmit = document.getElementById('btn-submit-editar-etapa');
                const textarea = document.getElementById('nombre-tendencia');
                
                if (textarea && etapaSeleccionada) {
                    const longitud = etapaSeleccionada.nombre.length;
                    
                    if (contadorSpan) {
                        contadorSpan.classList.remove('text-red-500', 'text-sena');
                        contadorSpan.classList.add('text-sena-text-soft');
                        contadorSpan.textContent = `${longitud} / ${MIN_DESCRIPCION_LENGTH} caracteres`;
                        
                        if (longitud >= MIN_DESCRIPCION_LENGTH) {
                            contadorSpan.classList.remove('text-sena-text-soft');
                            contadorSpan.classList.add('text-sena');
                            if (alertaSpan) alertaSpan.classList.add('hidden');
                            if (btnSubmit) btnSubmit.disabled = false;
                        } else {
                            if (alertaSpan) alertaSpan.classList.remove('hidden');
                            if (btnSubmit) btnSubmit.disabled = true;
                        }
                    }
                }
            }
            
            modal.classList.remove('hidden');
            modal.offsetHeight;
            document.body.classList.add('overflow-hidden');
        }
    }
    
    function cerrarModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
    
    function cerrarModalEditado() {
        if (modalEditadoConfirmacion) {
            modalEditadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutEditado) {
            clearTimeout(timeoutEditado);
            timeoutEditado = null;
        }
        if (intervalContadorEditado) {
            clearInterval(intervalContadorEditado);
            intervalContadorEditado = null;
        }
        const progressBar = document.getElementById('progress-bar-editado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-editado');
        if (contador) contador.textContent = '3';
    }
    
    function cerrarModalDeshabilitado() {
        if (modalDeshabilitadoConfirmacion) {
            modalDeshabilitadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutDeshabilitado) {
            clearTimeout(timeoutDeshabilitado);
            timeoutDeshabilitado = null;
        }
        if (intervalContadorDeshabilitado) {
            clearInterval(intervalContadorDeshabilitado);
            intervalContadorDeshabilitado = null;
        }
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) contador.textContent = '3';
    }

    function cerrarModalHabilitado() {
        if (modalHabilitadoConfirmacion) {
            modalHabilitadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutHabilitado) {
            clearTimeout(timeoutHabilitado);
            timeoutHabilitado = null;
        }
        if (intervalContadorHabilitado) {
            clearInterval(intervalContadorHabilitado);
            intervalContadorHabilitado = null;
        }
        const progressBar = document.getElementById('progress-bar-habilitado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-habilitado');
        if (contador) contador.textContent = '3';
    }

    function cerrarModalCreado() {
        if (modalCreadoConfirmacion) {
            modalCreadoConfirmacion.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        if (timeoutCreado) {
            clearTimeout(timeoutCreado);
            timeoutCreado = null;
        }
        if (intervalContadorCreado) {
            clearInterval(intervalContadorCreado);
            intervalContadorCreado = null;
        }
        const progressBar = document.getElementById('progress-bar-creado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-creado');
        if (contador) contador.textContent = '3';
    }
    
    function mostrarModalEditado(nombreEtapa) {
        if (!modalEditadoConfirmacion) return;
        
        const nombrePerfilEditadoSpan = document.getElementById('nombre-perfil-editado');
        if (nombrePerfilEditadoSpan) {
            nombrePerfilEditadoSpan.textContent = `"${nombreEtapa}"`;
        }
        
        abrirModal(modalEditadoConfirmacion);
        
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
        
        const progressBar = document.getElementById('progress-bar-editado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }
        
        if (timeoutEditado) clearTimeout(timeoutEditado);
        
        timeoutEditado = setTimeout(() => {
            cerrarModalEditado();
        }, 3000);
    }
    
    function mostrarModalDeshabilitado(nombreEtapa) {
        if (!modalDeshabilitadoConfirmacion) return;
        
        const nombreTendenciaSpan = document.getElementById('nombre-tendencia-act-deshabilitado');
        if (nombreTendenciaSpan) {
            nombreTendenciaSpan.textContent = `"${nombreEtapa}"`;
        }
        
        abrirModal(modalDeshabilitadoConfirmacion);
        
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
        
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }
        
        if (timeoutDeshabilitado) clearTimeout(timeoutDeshabilitado);
        
        timeoutDeshabilitado = setTimeout(() => {
            cerrarModalDeshabilitado();
        }, 3000);
    }

    function mostrarModalHabilitado(nombreEtapa) {
        if (!modalHabilitadoConfirmacion) return;

        const nombreSpan = document.getElementById('nombre-tendencia-act-habilitado-exito');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombreEtapa}"`;
        }

        abrirModal(modalHabilitadoConfirmacion);

        let segundos = 3;
        const contador = document.getElementById('contador-segundos-habilitado');
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

        const progressBar = document.getElementById('progress-bar-habilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }

        if (timeoutHabilitado) clearTimeout(timeoutHabilitado);

        timeoutHabilitado = setTimeout(() => {
            cerrarModalHabilitado();
        }, 3000);
    }

    function mostrarModalCreado(nombreEtapa) {
        if (!modalCreadoConfirmacion) return;

        const nombreSpan = document.getElementById('nombre-tendencia-creada');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombreEtapa}"`;
        }

        abrirModal(modalCreadoConfirmacion);

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

        const progressBar = document.getElementById('progress-bar-creado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }

        if (timeoutCreado) clearTimeout(timeoutCreado);

        timeoutCreado = setTimeout(() => {
            cerrarModalCreado();
        }, 3000);
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
                etapaSeleccionada = etapasActuales.find(e => e.id_etapa == id);
                if (!etapaSeleccionada) return;
                
                const areaSelect = document.getElementById('area-tendencia');
                if (areaSelect && areasActuales.length > 0) {
                    areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                    areasActuales.forEach(area => {
                        areaSelect.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                    });
                }
                
                const nombreInput = document.getElementById('nombre-tendencia');
                const estadoSelect = document.getElementById('estado-tendencia');
                
                if (nombreInput) nombreInput.value = etapaSeleccionada.nombre;
                if (areaSelect) areaSelect.value = etapaSeleccionada.id_area;
                if (estadoSelect) estadoSelect.value = etapaSeleccionada.estado == 1 ? 'activo' : 'inactivo';
                
                if (modalEditar) modalEditar.setAttribute('data-id', id);
                abrirModal(modalEditar);
            });
        }
        
        if (switchEl) {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const id = parseInt(tarjeta.getAttribute('data-id'));
                let nombre = tarjeta.getAttribute('data-nombre');
                if (!nombre || nombre === '' || nombre === 'null') {
                    nombre = switchEl.getAttribute('data-nombre');
                }
                nombre = nombre.replace(/^["']|["']$/g, '');
                const estaActivo = this.classList.contains('active');
                
                if (estaActivo) {
                    const spanNombre = modalDeshabilitar.querySelector('span.font-medium');
                    if (spanNombre) spanNombre.textContent = `"${nombre}"`;
                    modalDeshabilitar.setAttribute('data-id', id);
                    abrirModal(modalDeshabilitar);
                } else {
                    const spanNombre = modalHabilitar.querySelector('span.font-semibold');
                    if (spanNombre) spanNombre.textContent = `"${nombre}"`;
                    modalHabilitar.setAttribute('data-id', id);
                    modalHabilitar.setAttribute('data-nombre', nombre);
                    abrirModal(modalHabilitar);
                }
            });
        }
        
        tarjeta.addEventListener('click', function(e) {
            if (e.target.closest('.btn-editar-tendencia') || e.target.closest('.switch-sena')) return;
            const id = parseInt(tarjeta.getAttribute('data-id'));
            const etapa = etapasActuales.find(e => e.id_etapa == id);
            if (!etapa) return;
            
            const detalleTitulo = document.getElementById('detalle-titulo');
            const detalleNombre = document.getElementById('detalle-nombre');
            const detalleDescripcion = document.getElementById('detalle-descripcion');
            const detalleEstado = document.getElementById('detalle-estado');
            const detalleEstadoIndicador = document.getElementById('detalle-estado-indicador');
            const detalleArea = document.getElementById('detalle-area');
            
            if (detalleTitulo) detalleTitulo.textContent = `Detalle de: ${etapa.nombre}`;
            if (detalleNombre) detalleNombre.textContent = etapa.nombre;
            if (detalleDescripcion) detalleDescripcion.textContent = etapa.nombre;
            
            const estadoTexto = etapa.estado == 1 ? 'Activo' : 'Inactivo';
            const colorEstado = etapa.estado == 1 ? '#39A900' : '#9ca3af';
            
            if (detalleEstado) detalleEstado.textContent = estadoTexto;
            if (detalleEstadoIndicador) detalleEstadoIndicador.style.backgroundColor = colorEstado;
            
            const area = areasActuales.find(a => a.id_area == etapa.id_area);
            if (detalleArea) detalleArea.textContent = area ? area.nombre_area : 'No especificada';
            
            abrirModal(modalDetalle);
        });
    }

    // ===== EVENTOS PRINCIPALES =====
    cargarEtapas();
    cargarAreas();
    
    // Event listener para el contador de caracteres en creación
    const textareaCrear = document.getElementById('nombre-crear');
    if (textareaCrear) {
        textareaCrear.addEventListener('input', actualizarContadorCrear);
    }
    
    // Event listener para el contador de caracteres en edición
    const textareaEditar = document.getElementById('nombre-tendencia');
    if (textareaEditar) {
        textareaEditar.addEventListener('input', actualizarContadorEditar);
    }
    
    const buscador = document.getElementById('buscador-tendencias');
    if (buscador) buscador.addEventListener('input', filtrarEtapas);
    
    if (btnCrearTendencia) {
        btnCrearTendencia.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectCrear = document.querySelector('#form-nueva-tendencia-actual select[name="area"]');
            if (selectCrear && areasActuales.length > 0) {
                selectCrear.innerHTML = '<option value="">Seleccione un área</option>';
                areasActuales.forEach(area => {
                    if (area.estado == 1) {
                        selectCrear.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                    }
                });
            }
            
            const form = document.getElementById('form-nueva-tendencia-actual');
            if (form) form.reset();
            abrirModal(modalCrear);
        });
    }
    
    // Cerrar modales
    cerrarModalBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalEditar));
    });
    
    cerrarModalEditadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalEditado);
    });
    
    cerrarModalDeshabilitarBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalDeshabilitar));
    });
    
    cerrarModalDeshabilitadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalDeshabilitado);
    });
    
    cerrarModalHabilitarBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalHabilitar));
    });
    
    cerrarModalHabilitadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalHabilitado);
    });
    
    cerrarModalCrearBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalCrear));
    });
    
    cerrarModalCreadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalCreado);
    });
    
    cerrarModalDetalleBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalDetalle));
    });
    
    // Cerrar modales haciendo clic en overlay
    [modalEditar, modalEditadoConfirmacion, modalDeshabilitar, modalDeshabilitadoConfirmacion, 
     modalHabilitar, modalHabilitadoConfirmacion, modalCrear, modalCreadoConfirmacion, modalDetalle].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('fixed')) {
                    if (modal === modalEditadoConfirmacion) {
                        cerrarModalEditado();
                    } else if (modal === modalDeshabilitadoConfirmacion) {
                        cerrarModalDeshabilitado();
                    } else if (modal === modalHabilitadoConfirmacion) {
                        cerrarModalHabilitado();
                    } else if (modal === modalCreadoConfirmacion) {
                        cerrarModalCreado();
                    } else {
                        cerrarModal(modal);
                    }
                }
            });
        }
    });
    
    // Formulario crear
    const formCrear = document.getElementById('form-nueva-tendencia-actual');
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const areaSelect = this.querySelector('select[name="area"]');
            const nombreTextarea = this.querySelector('textarea[name="nombre"]');
            
            const id_area = areaSelect.value;
            const nombre = nombreTextarea.value.trim();
            
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            if (!nombre) {
                mostrarToastValidacion('El nombre de la etapa es requerido', 'warning');
                return;
            }
            
            // Validar mínimo de caracteres
            if (!validarDescripcion(nombre)) {
                mostrarToastValidacion(`El nombre de la etapa debe tener al menos ${MIN_DESCRIPCION_LENGTH} caracteres. Actualmente tiene ${nombre.length} caracteres.`, 'warning');
                return;
            }
            
            const existe = await verificarNombreExistente(nombre, id_area);
            if (existe) {
                mostrarToastValidacion('Ya existe una etapa con ese nombre en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await crearEtapa({
                id_area: parseInt(id_area),
                nombre: nombre,
                estado: 1
            });
            
            if (resultado.success) {
                cerrarModal(modalCrear);
                mostrarModalCreado(nombre);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al crear la etapa', 'error');
            }
        });
    }
    
    // Formulario editar
    const formEditar = document.getElementById('form-editar-tendencia');
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalEditar.getAttribute('data-id');
            const nombre = document.getElementById('nombre-tendencia').value.trim();
            const id_area = document.getElementById('area-tendencia').value;
            const estado = document.getElementById('estado-tendencia').value === 'activo' ? 1 : 0;
            
            if (!nombre) {
                mostrarToastValidacion('El nombre de la etapa es requerido', 'warning');
                return;
            }
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            
            // Verificar cambios
            const datosOriginales = etapaSeleccionada;
            const nombreCambio = nombre !== datosOriginales?.nombre;
            const areaCambio = parseInt(id_area) !== datosOriginales?.id_area;
            const estadoCambio = estado !== datosOriginales?.estado;
            
            if (!nombreCambio && !areaCambio && !estadoCambio) {
                mostrarToastValidacion('No se ha realizado ningún cambio en la etapa', 'info');
                return;
            }
            
            const existe = await verificarNombreExistente(nombre, id_area, id_etapa);
            if (existe) {
                mostrarToastValidacion('Ya existe una etapa con ese nombre en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await actualizarEtapa({
                id_etapa: parseInt(id_etapa),
                id_area: parseInt(id_area),
                nombre: nombre,
                estado: estado
            });
            
            if (resultado.success) {
                cerrarModal(modalEditar);
                mostrarModalEditado(nombre);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al actualizar la etapa', 'error');
            }
        });
    }
    
    // Confirmar deshabilitar
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalDeshabilitar.getAttribute('data-id');
            const nombreEtapa = modalDeshabilitar.querySelector('span.font-medium')?.textContent.replace(/"/g, '');
            
            if (id_etapa && nombreEtapa) {
                const resultado = await cambiarEstadoEtapa(id_etapa, 'desactivar');
                if (resultado.success) {
                    cerrarModal(modalDeshabilitar);
                    mostrarModalDeshabilitado(nombreEtapa);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al deshabilitar la etapa', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la etapa', 'error');
            }
        });
    }
    
    // Confirmar habilitar
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalHabilitar.getAttribute('data-id');
            const nombreEtapa = modalHabilitar.getAttribute('data-nombre');
            
            if (id_etapa && nombreEtapa) {
                const resultado = await cambiarEstadoEtapa(id_etapa, 'activar');
                if (resultado.success) {
                    cerrarModal(modalHabilitar);
                    mostrarModalHabilitado(nombreEtapa);
                } else {
                    mostrarToastValidacion(resultado.error || 'Error al habilitar la etapa', 'error');
                }
            } else {
                mostrarToastValidacion('Error: No se pudo identificar la etapa', 'error');
            }
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modalEditar && !modalEditar.classList.contains('hidden')) cerrarModal(modalEditar);
            else if (modalEditadoConfirmacion && !modalEditadoConfirmacion.classList.contains('hidden')) cerrarModalEditado();
            else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) cerrarModal(modalDeshabilitar);
            else if (modalDeshabilitadoConfirmacion && !modalDeshabilitadoConfirmacion.classList.contains('hidden')) cerrarModalDeshabilitado();
            else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) cerrarModal(modalHabilitar);
            else if (modalHabilitadoConfirmacion && !modalHabilitadoConfirmacion.classList.contains('hidden')) cerrarModalHabilitado();
            else if (modalCrear && !modalCrear.classList.contains('hidden')) cerrarModal(modalCrear);
            else if (modalCreadoConfirmacion && !modalCreadoConfirmacion.classList.contains('hidden')) cerrarModalCreado();
            else if (modalDetalle && !modalDetalle.classList.contains('hidden')) cerrarModal(modalDetalle);
        }
    });
});