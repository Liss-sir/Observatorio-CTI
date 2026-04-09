// perfiles.js - Archivo completo para el módulo de perfiles

document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN API =====
    const API_BASE_URL = '../../controllers/PerfilOcupacionalController.php';
    
    // ===== VARIABLES GLOBALES =====
    let currentPerfilId = null;
    let currentPerfil = null;
    
    // Variables para almacenar datos originales en edición
    let datosOriginalesEditar = {
        nombre: '',
        descripcion: '',
        id_nivel: '',
        cupos: 1
    };
    
    // Detectar en qué página estamos
    const isDetallesPage = window.location.pathname.includes('perfiles_detalles.php');
    const perfilIdFromUrl = new URLSearchParams(window.location.search).get('id');
    
    // ===== MODALES =====
    const modalCrear = document.getElementById('modal-crear-perfil');
    const modalEditar = document.getElementById('modal-editar-perfil');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-confirmacion');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-confirmacion');
    
    // ===== BOTONES =====
    const btnNuevoPerfil = document.getElementById('btn-nuevo-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    
    // Botones de página de detalles
    const btnEditarDetalles = document.getElementById('btn-editar-perfil');
    const btnDeshabilitarDetalles = document.getElementById('btn-deshabilitar-perfil');
    const btnCambiarEstadoDetalles = document.getElementById('btn-cambiar-estado');
    
    // ===== ELEMENTOS DE TEXTO =====
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');
    const nombrePerfilEditadoSpan = document.getElementById('nombre-perfil-editado');
    const nombrePerfilCreadoSpan = document.getElementById('nombre-perfil-creado');
    const contadorPerfilesSpan = document.getElementById('contador-perfiles');

    // ===== VARIABLES PARA MIS CREACIONES =====
    let modoMisCreaciones = false;
    const btnMisCreaciones = document.getElementById('btn-mis-creaciones');
    
    // ===== FUNCIÓN PARA MOSTRAR TOAST DE VALIDACIÓN (mismo estilo que tendencias) =====
    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        let toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(toastContainer);
        }
        
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

        toastContainer.appendChild(toast);

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

    // ===== FUNCIONES DE VALIDACIÓN =====
    function validarNombre(nombre) {
        if (!nombre || nombre.trim() === '') {
            mostrarToastValidacion('El nombre del perfil es requerido', 'warning');
            return false;
        }
        if (nombre.length < 3) {
            mostrarToastValidacion('El nombre debe tener al menos 3 caracteres', 'warning');
            return false;
        }
        if (nombre.length > 100) {
            mostrarToastValidacion('El nombre no puede exceder los 100 caracteres', 'warning');
            return false;
        }
        return true;
    }
    
    function validarDescripcion(descripcion) {
        if (!descripcion || descripcion.trim() === '') {
            mostrarToastValidacion('La descripción del perfil es requerida', 'warning');
            return false;
        }
        if (descripcion.length < 10) {
            mostrarToastValidacion('La descripción debe tener al menos 10 caracteres', 'warning');
            return false;
        }
        if (descripcion.length > 1000) {
            mostrarToastValidacion('La descripción no puede exceder los 1000 caracteres', 'warning');
            return false;
        }
        return true;
    }
    
    function validarCupos(cupos) {
        const cuposNum = parseInt(cupos);
        if (isNaN(cuposNum)) {
            mostrarToastValidacion('Los cupos deben ser un número válido', 'warning');
            return false;
        }
        if (cuposNum < 1) {
            mostrarToastValidacion('Los cupos deben ser al menos 1', 'warning');
            return false;
        }
        if (cuposNum > 999) {
            mostrarToastValidacion('Los cupos no pueden exceder 999', 'warning');
            return false;
        }
        return true;
    }
    
    function validarLineaTecnologica(id_linea) {
        if (!id_linea || id_linea === '') {
            mostrarToastValidacion('Debes seleccionar una línea tecnológica', 'warning');
            return false;
        }
        return true;
    }
    
    function validarProgramaFormacion(id_programa) {
        if (!id_programa || id_programa === '') {
            mostrarToastValidacion('Debes seleccionar un programa de formación', 'warning');
            return false;
        }
        return true;
    }
    
    function validarNivelFormacion(id_nivel) {
        if (!id_nivel || id_nivel === '') {
            mostrarToastValidacion('Debes seleccionar un nivel de formación', 'warning');
            return false;
        }
        return true;
    }
    
    // ===== FUNCIONES DE UTILIDAD =====
    function abrirModal(modal) {
        if (modal && modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function cerrarModal(modal) {
        if (modal && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            setTimeout(() => {
                const modalesAbiertos = [modalCrear, modalEditar, modalDeshabilitar, modalDeshabilitado, modalHabilitar, modalHabilitadoConfirmacion, modalEditadoConfirmacion, modalCreadoConfirmacion].filter(m => 
                    m && m !== modal && !m.classList.contains('hidden')
                );
                if (modalesAbiertos.length === 0) {
                    document.body.style.overflow = '';
                }
            }, 0);
        }
    }
    
    function formatearFecha(fecha) {
        if (!fecha) return 'No especificada';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ===== FUNCIONES DE API =====
    
    async function cargarPerfiles() {
        if (!isDetallesPage) {
            try {
                mostrarCargando(true);
                
                const response = await fetch(`${API_BASE_URL}?accion=listarTodas`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    renderizarPerfiles(data.data);
                    actualizarContador(data.data.length);
                    modoMisCreaciones = false;
                    // Restaurar estilo visual del botón
                    if (btnMisCreaciones) {
                        btnMisCreaciones.classList.remove('bg-sena', 'text-white', 'border-sena');
                        btnMisCreaciones.classList.add('text-sena-text-main', 'border-sena-border');
                    }
                } else {
                    mostrarToastValidacion('Error al cargar perfiles', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarToastValidacion('Error de conexión', 'error');
            } finally {
                mostrarCargando(false);
            }
        }
    }

    // Cargar solo los perfiles del usuario logueado
    async function cargarMisCreaciones() {
        if (!isDetallesPage) {
            try {
                mostrarCargando(true);
                
                const userId = window.SENA_CONFIG?.USUARIO_ID;
                if (!userId) {
                    mostrarToastValidacion('No se pudo identificar el usuario', 'warning');
                    return;
                }
                
                const response = await fetch(`${API_BASE_URL}?accion=obtenerPorUsuario&id_usuario=${userId}`);
                const data = await response.json();
                
                if (data.success) {
                    renderizarPerfiles(data.data);
                    actualizarContador(data.data.length);
                    modoMisCreaciones = true;
                    // Cambiar estilo visual del botón
                    if (btnMisCreaciones) {
                        btnMisCreaciones.classList.add('bg-sena', 'text-white', 'border-sena');
                        btnMisCreaciones.classList.remove('text-sena-text-main', 'border-sena-border');
                    }
                } else {
                    mostrarToastValidacion('Error al cargar tus creaciones', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarToastValidacion('Error de conexión', 'error');
            } finally {
                mostrarCargando(false);
            }
        }
    }
    
    async function cargarPerfilDetalle() {
        if (isDetallesPage && perfilIdFromUrl) {
            try {
                mostrarSkeleton(true);
                
                const response = await fetch(`${API_BASE_URL}?accion=obtener&id_perfil=${perfilIdFromUrl}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    currentPerfil = result.data;
                    renderizarDetallePerfil(currentPerfil);
                    mostrarSkeleton(false);
                } else {
                    throw new Error(result.error || 'Perfil no encontrado');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarSkeleton(false);
                const nombreElement = document.getElementById('perfil-nombre');
                if (nombreElement) nombreElement.textContent = 'Error al cargar el perfil';
            }
        }
    }
    
    function renderizarPerfiles(perfiles) {
        const container = document.querySelector('#perfiles-container, .flex.flex-col.gap-3');
        if (!container) return;
        
        if (perfiles.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 border border-sena-border rounded-lg bg-white">
                    <svg class="mx-auto h-12 w-12 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-sena-text-main">No hay perfiles</h3>
                    <p class="mt-1 text-sm text-sena-text-soft">Comienza creando un nuevo perfil.</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = perfiles.map(perfil => `
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all" data-perfil-id="${perfil.id_perfil}">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-sm font-semibold text-sena-text-main truncate">${escapeHtml(perfil.nombre)}</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium ${perfil.estado == 1 ? 'bg-sena/10 text-sena' : 'bg-red-100 text-red-600'} flex-shrink-0">
                            ${perfil.estado == 1 ? 'Vigente' : 'Expirado'}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                                <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                                <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                            </svg>
                            ${escapeHtml(perfil.nombre_linea || 'Sin línea')}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                                <path d="M10 6h4"/>
                                <path d="M10 10h4"/>
                                <path d="M10 14h4"/>
                                <path d="M10 18h4"/>
                            </svg>
                            ${escapeHtml(perfil.nombre_empresa || 'Sin empresa')}
                        </span>
                        ${perfil.cupos ? `<span class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            ${perfil.cupos} cupos
                        </span>` : ''}
                    </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="${perfil.id_perfil}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${perfil.estado == 1 ? 'active' : ''}" title="${perfil.estado == 1 ? 'Activo' : 'Inactivo'}"></div>
                    <a href="../../view/perfiles/perfiles_detalles.php?id=${perfil.id_perfil}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Ver detalle
                    </a>
                </div>
            </div>
        `).join('');
        
        inicializarEventosPerfiles();
    }
    
    function renderizarDetallePerfil(perfil) {
        const isActive = perfil.estado == 1;
        
        document.title = `${perfil.nombre || 'Perfil'} - SENA Observatorio Tecnologico`;
        
        const nombreElement = document.getElementById('perfil-nombre');
        if (nombreElement) nombreElement.textContent = perfil.nombre || 'Sin nombre';
        
        const lineaBadge = document.getElementById('linea-badge');
        if (lineaBadge) lineaBadge.textContent = perfil.nombre_linea || 'Sin línea';
        
        const estadoTexto = document.getElementById('estado-texto');
        if (estadoTexto) estadoTexto.textContent = isActive ? 'Vigente' : 'Expirado';
        
        const estadoBadge = document.getElementById('estado-badge');
        if (estadoBadge) {
            estadoBadge.className = `inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium ${isActive ? 'bg-sena/10 text-sena' : 'bg-red-100 text-red-600'}`;
        }
        
        const btnEstadoTexto = document.getElementById('btn-estado-texto');
        const btnCambiarEstadoElem = document.getElementById('btn-cambiar-estado');
        if (btnEstadoTexto && btnCambiarEstadoElem) {
            if (isActive) {
                btnEstadoTexto.textContent = 'Desactivar';
                btnCambiarEstadoElem.className = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors';
            } else {
                btnEstadoTexto.textContent = 'Activar';
                btnCambiarEstadoElem.className = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena rounded-lg bg-white text-sena hover:bg-sena/5 transition-colors';
            }
        }
        
        const descripcionElement = document.getElementById('perfil-descripcion');
        if (descripcionElement) descripcionElement.textContent = perfil.descripcion || 'Sin descripción';
        
        const empresaElement = document.getElementById('empresa-nombre');
        if (empresaElement) empresaElement.textContent = perfil.nombre_empresa || 'Sin empresa';
        
        const lineaNombreElement = document.getElementById('linea-nombre');
        if (lineaNombreElement) lineaNombreElement.textContent = perfil.nombre_linea || 'Sin línea';
        
        const nivelElement = document.getElementById('nivel-nombre');
        if (nivelElement) nivelElement.textContent = perfil.nombre_nivel || 'Sin nivel';
        
        const cuposElement = document.getElementById('cupos-valor');
        if (cuposElement) cuposElement.textContent = perfil.cupos || 0;
        
        const fechaCreacionElement = document.getElementById('fecha-creacion');
        if (fechaCreacionElement) fechaCreacionElement.textContent = formatearFecha(perfil.fecha_creacion);
        
        const vigenciaCard = document.getElementById('vigencia-card');
        if (vigenciaCard) {
            if (isActive) {
                vigenciaCard.className = 'border border-sena/30 rounded-xl bg-sena/5 p-5 flex items-center gap-3';
                vigenciaCard.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-sena flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-sena">Perfil Vigente</p>
                        <p class="text-xs text-sena-text-soft">Este perfil se encuentra activo</p>
                    </div>
                `;
            } else {
                vigenciaCard.className = 'border border-red-200 rounded-xl bg-red-50 p-5 flex items-center gap-3';
                vigenciaCard.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-red-500 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-600">Perfil Expirado</p>
                        <p class="text-xs text-red-500">Este perfil se encuentra inactivo</p>
                    </div>
                `;
            }
        }
    }
    
    async function crearPerfil(data) {
        try {
            const response = await fetch(`${API_BASE_URL}?accion=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                cerrarModal(modalCrear);
                mostrarModalCreado(data.nombre);
                if (modoMisCreaciones) {
                    cargarMisCreaciones();
                } else {
                    cargarPerfiles();
                }
                return true;
            } else {
                mostrarToastValidacion(result.error || 'Error al crear perfil', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion('Error de conexión', 'error');
            return false;
        }
    }
    
    async function actualizarPerfil(id, data) {
        try {
            data.id_perfil = id;
            const response = await fetch(`${API_BASE_URL}?accion=actualizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                cerrarModal(modalEditar);
                mostrarModalEditado(data.nombre);
                if (isDetallesPage) {
                    cargarPerfilDetalle();
                } else {
                    if (modoMisCreaciones) {
                        cargarMisCreaciones();
                    } else {
                        cargarPerfiles();
                    }
                }
                return true;
            } else {
                mostrarToastValidacion(result.error || 'Error al actualizar perfil', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion('Error de conexión', 'error');
            return false;
        }
    }
    
    async function obtenerPerfil(id) {
        try {
            const response = await fetch(`${API_BASE_URL}?accion=obtener&id_perfil=${id}`);
            const result = await response.json();
            
            if (result.success) {
                return result.data;
            } else {
                mostrarToastValidacion(result.error || 'Error al obtener perfil', 'error');
                return null;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion('Error de conexión', 'error');
            return null;
        }
    }
    
    async function cambiarEstadoPerfil(id, accion) {
        try {
            const response = await fetch(`${API_BASE_URL}?accion=${accion}&id_perfil=${id}`, { method: 'POST' });
            const result = await response.json();
            
            if (result.success) {
                return true;
            } else {
                mostrarToastValidacion(result.error || `Error al ${accion} perfil`, 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion('Error de conexión', 'error');
            return false;
        }
    }
    
    // ===== FUNCIONES DE UI =====
    
    function mostrarCargando(mostrar) {
        const container = document.querySelector('#perfiles-container, .flex.flex-col.gap-3');
        if (container && mostrar && !isDetallesPage) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sena"></div>
                    <p class="mt-2 text-sm text-sena-text-soft">Cargando perfiles...</p>
                </div>
            `;
        }
    }
    
    function mostrarSkeleton(mostrar) {
        const skeleton = document.getElementById('skeleton-loader');
        const profileContent = document.getElementById('profile-content');
        if (skeleton && profileContent) {
            if (mostrar) {
                skeleton.classList.remove('hidden');
                profileContent.classList.add('hidden');
            } else {
                skeleton.classList.add('hidden');
                profileContent.classList.remove('hidden');
            }
        }
    }
    
    function actualizarContador(cantidad) {
        if (contadorPerfilesSpan) {
            contadorPerfilesSpan.textContent = cantidad;
        }
    }
    
    // ===== MODALES DE CONFIRMACIÓN =====
    
    function mostrarModalDeshabilitado(nombrePerfil) {
        if (nombrePerfilDeshabilitadoSpan) {
            nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        }
        abrirModal(modalDeshabilitado);
        setTimeout(() => cerrarModal(modalDeshabilitado), 3000);
    }
    
    function mostrarModalHabilitado(nombrePerfil) {
        if (nombrePerfilHabilitadoExitoSpan) {
            nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
        abrirModal(modalHabilitadoConfirmacion);
        setTimeout(() => cerrarModal(modalHabilitadoConfirmacion), 3000);
    }
    
    function mostrarModalEditado(nombrePerfil) {
        if (nombrePerfilEditadoSpan) {
            nombrePerfilEditadoSpan.textContent = `"${nombrePerfil}"`;
        }
        abrirModal(modalEditadoConfirmacion);
        setTimeout(() => cerrarModal(modalEditadoConfirmacion), 3000);
    }
    
    function mostrarModalCreado(nombrePerfil) {
        if (nombrePerfilCreadoSpan) {
            nombrePerfilCreadoSpan.textContent = `"${nombrePerfil}"`;
        }
        abrirModal(modalCreadoConfirmacion);
        setTimeout(() => cerrarModal(modalCreadoConfirmacion), 3000);
    }
    
    // ===== FUNCIONES PARA CARGAR DATOS EN EL MODAL CREAR =====
    
    async function cargarLineasTecnologicas() {
        const selectLinea = document.getElementById('linea_tecnologica');
        if (!selectLinea) return;
        
        try {
            selectLinea.innerHTML = '<option value="" disabled selected>Cargando líneas...</option>';
            const response = await fetch(`${API_BASE_URL}?accion=listarLineasTecnologicas`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                selectLinea.innerHTML = '<option value="" disabled selected>Seleccionar línea tecnológica...</option>';
                data.data.forEach(linea => {
                    const option = document.createElement('option');
                    option.value = linea.id_linea;
                    option.textContent = linea.nombre_linea;
                    selectLinea.appendChild(option);
                });
            } else {
                selectLinea.innerHTML = '<option value="" disabled selected>No hay líneas disponibles</option>';
            }
        } catch (error) {
            console.error('Error cargando líneas:', error);
            selectLinea.innerHTML = '<option value="" disabled selected>Error al cargar líneas</option>';
        }
    }
    
    async function cargarNivelesFormacion() {
        const selectNivel = document.getElementById('nivel_formacion');
        if (!selectNivel) return;
        
        try {
            selectNivel.innerHTML = '<option value="" disabled selected>Cargando niveles...</option>';
            const response = await fetch(`${API_BASE_URL}?accion=listarNivelesFormacion`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                selectNivel.innerHTML = '<option value="" disabled selected>Seleccionar nivel de formación...</option>';
                data.data.forEach(nivel => {
                    const option = document.createElement('option');
                    option.value = nivel.id_nivel;
                    option.textContent = nivel.nombre_nivel;
                    selectNivel.appendChild(option);
                });
            } else {
                selectNivel.innerHTML = '<option value="" disabled selected>No hay niveles disponibles</option>';
            }
        } catch (error) {
            console.error('Error cargando niveles:', error);
            selectNivel.innerHTML = '<option value="" disabled selected>Error al cargar niveles</option>';
        }
    }
    
    async function cargarProgramasPorLinea(id_linea) {
        const selectPrograma = document.getElementById('programa_formacion');
        if (!selectPrograma) return;
        
        if (!id_linea) {
            selectPrograma.innerHTML = '<option value="" disabled selected>Primero selecciona una línea tecnológica</option>';
            selectPrograma.disabled = true;
            return;
        }
        
        try {
            selectPrograma.innerHTML = '<option value="" disabled selected>Cargando programas...</option>';
            selectPrograma.disabled = true;
            
            const response = await fetch(`${API_BASE_URL}?accion=listarProgramasPorLinea&id_linea=${id_linea}`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                selectPrograma.innerHTML = '<option value="" disabled selected>Seleccionar programa de formación...</option>';
                selectPrograma.disabled = false;
                
                data.data.forEach(programa => {
                    const option = document.createElement('option');
                    option.value = programa.id_programa;
                    const texto = programa.codigo_programa ? `${programa.codigo_programa} - ${programa.nombre_programa}` : programa.nombre_programa;
                    option.textContent = texto;
                    selectPrograma.appendChild(option);
                });
            } else {
                selectPrograma.innerHTML = '<option value="" disabled selected>No hay programas para esta línea</option>';
                selectPrograma.disabled = true;
            }
        } catch (error) {
            console.error('Error cargando programas:', error);
            selectPrograma.innerHTML = '<option value="" disabled selected>Error al cargar programas</option>';
            selectPrograma.disabled = true;
        }
    }
    
    async function cargarTecnologiasEmergentes() {
        const container = document.getElementById('tecnologias-emergentes');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando tecnologías...</div>';
            const response = await fetch(`${API_BASE_URL}?accion=listarTecnologiasEmergentes`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(tecnologia => {
                    const chip = document.createElement('div');
                    chip.className = 'chip-crear inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border border-sena-border bg-white text-sena-text-main cursor-pointer transition-all hover:border-sena hover:bg-sena/5';
                    chip.setAttribute('data-value', tecnologia.id_tecnologia);
                    chip.textContent = tecnologia.nombre_tecnologia;
                    
                    chip.addEventListener('click', function() {
                        this.classList.toggle('selected');
                        this.classList.toggle('bg-sena');
                        this.classList.toggle('text-white');
                        this.classList.toggle('border-sena');
                        this.classList.toggle('border-sena-border');
                    });
                    
                    container.appendChild(chip);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay tecnologías disponibles</div>';
            }
        } catch (error) {
            console.error('Error cargando tecnologías:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar tecnologías</div>';
        }
    }
    
    // ===== FUNCIONES PARA CARGAR DATOS EN EL MODAL EDITAR =====
    
    async function cargarNivelesFormacionEditar() {
        const selectNivel = document.getElementById('editar_nivel_formacion');
        if (!selectNivel) return;
        
        try {
            selectNivel.innerHTML = '<option value="" disabled selected>Cargando niveles...</option>';
            const response = await fetch(`${API_BASE_URL}?accion=listarNivelesFormacion`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                selectNivel.innerHTML = '';
                data.data.forEach(nivel => {
                    const option = document.createElement('option');
                    option.value = nivel.id_nivel;
                    option.textContent = nivel.nombre_nivel;
                    selectNivel.appendChild(option);
                });
            } else {
                selectNivel.innerHTML = '<option value="" disabled>No hay niveles disponibles</option>';
            }
        } catch (error) {
            console.error('Error cargando niveles:', error);
            selectNivel.innerHTML = '<option value="" disabled>Error al cargar niveles</option>';
        }
    }
    
    function cargarDatosEnModalEditar(perfil) {
        const form = document.querySelector('#modal-editar-perfil form');
        if (!form) return;
        
        if (form.querySelector('input[name="id_perfil"]')) 
            form.querySelector('input[name="id_perfil"]').value = perfil.id_perfil || '';
        if (form.querySelector('input[name="nombre"]')) 
            form.querySelector('input[name="nombre"]').value = perfil.nombre || '';
        if (form.querySelector('textarea[name="descripcion"]')) 
            form.querySelector('textarea[name="descripcion"]').value = perfil.descripcion || '';
        if (form.querySelector('select[name="id_nivel"]')) 
            form.querySelector('select[name="id_nivel"]').value = perfil.id_nivel || '';
        if (form.querySelector('input[name="cupos"]')) 
            form.querySelector('input[name="cupos"]').value = perfil.cupos || 1;
        
        // Guardar los datos originales para comparar después
        datosOriginalesEditar = {
            nombre: perfil.nombre || '',
            descripcion: perfil.descripcion || '',
            id_nivel: perfil.id_nivel || '',
            cupos: perfil.cupos || 1
        };
    }
    
    // Función para verificar si hubo cambios en el formulario de edición
    function hayCambiosEnEditar() {
        const nombreActual = document.querySelector('#modal-editar-perfil input[name="nombre"]')?.value || '';
        const descripcionActual = document.querySelector('#modal-editar-perfil textarea[name="descripcion"]')?.value || '';
        const id_nivelActual = document.querySelector('#modal-editar-perfil select[name="id_nivel"]')?.value || '';
        const cuposActual = document.querySelector('#modal-editar-perfil input[name="cupos"]')?.value || '';
        
        const nombreCambio = nombreActual !== datosOriginalesEditar.nombre;
        const descripcionCambio = descripcionActual !== datosOriginalesEditar.descripcion;
        const nivelCambio = id_nivelActual != datosOriginalesEditar.id_nivel;
        const cuposCambio = parseInt(cuposActual) !== datosOriginalesEditar.cupos;
        
        return nombreCambio || descripcionCambio || nivelCambio || cuposCambio;
    }
    
    // ===== FUNCIONES PARA CARGAR FILTROS EN LA PÁGINA PRINCIPAL =====
    
    async function cargarFiltrosLineas() {
        const container = document.getElementById('filtros-lineas');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando líneas...</div>';
            const response = await fetch(`${API_BASE_URL}?accion=listarLineasTecnologicas`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(linea => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2.5 cursor-pointer';
                    label.innerHTML = `
                        <input type="checkbox" class="filtro-linea w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5" data-id="${linea.id_linea}">
                        <span class="text-sm text-sena-text-main">${escapeHtml(linea.nombre_linea)}</span>
                    `;
                    container.appendChild(label);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay líneas disponibles</div>';
            }
        } catch (error) {
            console.error('Error cargando filtros de líneas:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar líneas</div>';
        }
    }
    
    async function cargarFiltrosTecnologias() {
        const container = document.getElementById('filtros-tecnologias');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando tecnologías...</div>';
            const response = await fetch(`${API_BASE_URL}?accion=listarTecnologiasEmergentes`);
            const data = await response.json();
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(tecnologia => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2.5 cursor-pointer';
                    label.innerHTML = `
                        <input type="checkbox" class="filtro-tecnologia w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5" data-id="${tecnologia.id_tecnologia}">
                        <span class="text-sm text-sena-text-main">${escapeHtml(tecnologia.nombre_tecnologia)}</span>
                    `;
                    container.appendChild(label);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay tecnologías disponibles</div>';
            }
        } catch (error) {
            console.error('Error cargando filtros de tecnologías:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar tecnologías</div>';
        }
    }
    
    // ===== FUNCIONES DE FILTROS =====
    const btnAplicarFiltros = document.getElementById('btn-aplicar-filtros');
    const btnLimpiarFiltros = document.getElementById('btn-limpiar-filtros');
    const filtroEstado = document.getElementById('filtro-estado');
    
    async function aplicarFiltros() {
        const estado = filtroEstado ? filtroEstado.value : 'todos';
        
        const lineasSeleccionadas = [];
        document.querySelectorAll('.filtro-linea:checked').forEach(chk => {
            lineasSeleccionadas.push(chk.getAttribute('data-id'));
        });
        
        const tecnologiasSeleccionadas = [];
        document.querySelectorAll('.filtro-tecnologia:checked').forEach(chk => {
            tecnologiasSeleccionadas.push(chk.getAttribute('data-id'));
        });
        
        // Si está en modo "Mis creaciones", filtrar sobre esas
        if (modoMisCreaciones) {
            cargarMisCreaciones();
        } else {
            cargarPerfiles();
        }
    }
    
    function limpiarFiltros() {
        document.querySelectorAll('.filtro-linea').forEach(chk => {
            chk.checked = false;
        });
        
        document.querySelectorAll('.filtro-tecnologia').forEach(chk => {
            chk.checked = false;
        });
        
        if (filtroEstado) filtroEstado.value = 'todos';
        
        // Respetar el modo actual
        if (modoMisCreaciones) {
            cargarMisCreaciones();
        } else {
            cargarPerfiles();
        }
    }
    
    if (btnAplicarFiltros) {
        btnAplicarFiltros.addEventListener('click', aplicarFiltros);
    }
    
    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', limpiarFiltros);
    }
    
    // ===== EVENTO DEL BOTÓN MIS CREACIONES =====
    if (btnMisCreaciones) {
        const nuevoBtn = btnMisCreaciones.cloneNode(true);
        btnMisCreaciones.parentNode.replaceChild(nuevoBtn, btnMisCreaciones);
        
        nuevoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Si ya está en modo "Mis creaciones", recargar normalmente
            if (modoMisCreaciones) {
                cargarPerfiles();
            } else {
                cargarMisCreaciones();
            }
        });
    }
    
    // ===== INICIALIZAR EVENTOS =====
    
    function inicializarEventosPerfiles() {
        document.querySelectorAll('.btn-editar-perfil').forEach(btn => {
            btn.removeEventListener('click', handleEditarClick);
            btn.addEventListener('click', handleEditarClick);
        });
        
        document.querySelectorAll('.switch-sena').forEach(switchEl => {
            const newSwitch = switchEl.cloneNode(true);
            switchEl.parentNode.replaceChild(newSwitch, switchEl);
            newSwitch.addEventListener('click', handleSwitchClick);
        });
    }
    
    async function handleEditarClick(e) {
        e.preventDefault();
        const perfilId = this.getAttribute('data-perfil-id');
        if (!perfilId) return;
        
        currentPerfilId = perfilId;
        const perfil = await obtenerPerfil(perfilId);
        if (perfil) {
            await cargarNivelesFormacionEditar();
            cargarDatosEnModalEditar(perfil);
            abrirModal(modalEditar);
        }
    }
    
    async function handleSwitchClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const cardPerfil = this.closest('[data-perfil-id]');
        if (!cardPerfil) return;
        
        const perfilId = cardPerfil.getAttribute('data-perfil-id');
        const nombrePerfil = cardPerfil.querySelector('.text-sm.font-semibold')?.textContent.trim() || "Perfil";
        const estaActivo = this.classList.contains('active');
        
        if (estaActivo) {
            currentPerfilId = perfilId;
            currentPerfil = { id_perfil: perfilId, nombre: nombrePerfil };
            
            const nombreSpan = document.querySelector('#modal-deshabilitar-perfil #nombre-perfil-deshabilitar');
            if (nombreSpan) nombreSpan.textContent = nombrePerfil;
            
            abrirModal(modalDeshabilitar);
        } else {
            currentPerfilId = perfilId;
            currentPerfil = { id_perfil: perfilId, nombre: nombrePerfil };
            
            const nombreSpan = document.getElementById('nombre-perfil-habilitar');
            if (nombreSpan) nombreSpan.textContent = nombrePerfil;
            
            abrirModal(modalHabilitar);
        }
    }
    
    function actualizarEstadoPerfilEnCard(card, activo) {
        const badge = card.querySelector('.inline-flex.items-center.rounded-full');
        if (badge) {
            badge.textContent = activo ? 'Vigente' : 'Expirado';
            badge.className = `inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium ${activo ? 'bg-sena/10 text-sena' : 'bg-red-100 text-red-600'} flex-shrink-0`;
        }
    }
    
    // ===== EVENTOS DE FORMULARIOS CON VALIDACIONES =====
    
    const formCrear = document.querySelector('#modal-crear-perfil form');
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Obtener valores
            const nombre = document.querySelector('#modal-crear-perfil input[name="nombre"]')?.value || '';
            const descripcion = document.querySelector('#modal-crear-perfil textarea[name="descripcion"]')?.value || '';
            const cupos = document.querySelector('#modal-crear-perfil input[name="cupos"]')?.value || '';
            const id_linea = document.querySelector('#modal-crear-perfil select[name="id_linea"]')?.value || '';
            const id_programa = document.querySelector('#modal-crear-perfil select[name="id_programa"]')?.value || '';
            const id_nivel = document.querySelector('#modal-crear-perfil select[name="id_nivel"]')?.value || '';
            
            // Validaciones
            if (!validarNombre(nombre)) return;
            if (!validarDescripcion(descripcion)) return;
            if (!validarCupos(cupos)) return;
            if (!validarLineaTecnologica(id_linea)) return;
            if (!validarProgramaFormacion(id_programa)) return;
            if (!validarNivelFormacion(id_nivel)) return;
            
            // Si pasa todas las validaciones, enviar datos
            const formData = new FormData(formCrear);
            const data = {};
            
            formData.forEach((value, key) => {
                if (key === 'cupos') {
                    data[key] = parseInt(value) || 0;
                } else if (key === 'estado') {
                    data[key] = parseInt(value) || 1;
                } else {
                    data[key] = value;
                }
            });
            
            const selectedChips = [];
            document.querySelectorAll('#modal-crear-perfil .chip-crear.selected').forEach(chip => {
                selectedChips.push(chip.getAttribute('data-value'));
            });
            data.tecnologiasEmergentes = selectedChips;
            
            await crearPerfil(data);
        });
    }
    
    const formEditar = document.querySelector('#modal-editar-perfil form');
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentPerfilId && !currentPerfil) return;
            
            // Verificar si se realizaron cambios
            if (!hayCambiosEnEditar()) {
                mostrarToastValidacion('No se ha realizado ningún cambio en el perfil', 'info');
                return;
            }
            
            // Obtener valores
            const nombre = document.querySelector('#modal-editar-perfil input[name="nombre"]')?.value || '';
            const descripcion = document.querySelector('#modal-editar-perfil textarea[name="descripcion"]')?.value || '';
            const cupos = document.querySelector('#modal-editar-perfil input[name="cupos"]')?.value || '';
            const id_nivel = document.querySelector('#modal-editar-perfil select[name="id_nivel"]')?.value || '';
            
            // Validaciones
            if (!validarNombre(nombre)) return;
            if (!validarDescripcion(descripcion)) return;
            if (!validarCupos(cupos)) return;
            if (!validarNivelFormacion(id_nivel)) return;
            
            // Si pasa todas las validaciones, enviar datos
            const formData = new FormData(formEditar);
            const data = {
                id_perfil: formData.get('id_perfil'),
                nombre: formData.get('nombre'),
                descripcion: formData.get('descripcion'),
                id_nivel: formData.get('id_nivel'),
                cupos: parseInt(formData.get('cupos')) || 0
            };
            
            const id = currentPerfilId || (currentPerfil ? currentPerfil.id_perfil : null);
            await actualizarPerfil(id, data);
        });
    }
    
    // ===== EVENTO DEL BOTÓN NUEVO PERFIL =====
    
    const selectLinea = document.getElementById('linea_tecnologica');
    if (selectLinea) {
        selectLinea.addEventListener('change', function(e) {
            cargarProgramasPorLinea(e.target.value);
        });
    }
    
    if (btnNuevoPerfil) {
        const nuevoBtn = btnNuevoPerfil.cloneNode(true);
        btnNuevoPerfil.parentNode.replaceChild(nuevoBtn, btnNuevoPerfil);
        
        nuevoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.querySelector('#modal-crear-perfil form');
            if (form) form.reset();
            
            cargarLineasTecnologicas();
            cargarNivelesFormacion();
            cargarTecnologiasEmergentes();
            
            const selectPrograma = document.getElementById('programa_formacion');
            if (selectPrograma) {
                selectPrograma.innerHTML = '<option value="" disabled selected>Primero selecciona una línea tecnológica</option>';
                selectPrograma.disabled = true;
            }
            
            if (window.SENA_CONFIG && window.SENA_CONFIG.USUARIO_ID) {
                const idUsuarioInput = document.querySelector('#modal-crear-perfil input[name="id_usuario"]');
                if (idUsuarioInput) idUsuarioInput.value = window.SENA_CONFIG.USUARIO_ID;
            }
            
            abrirModal(modalCrear);
        });
    }
    
    // ===== EVENTOS PARA PÁGINA DE DETALLES =====
    
    if (btnEditarDetalles) {
        btnEditarDetalles.addEventListener('click', async function(e) {
            e.preventDefault();
            if (currentPerfil) {
                await cargarNivelesFormacionEditar();
                cargarDatosEnModalEditar(currentPerfil);
                abrirModal(modalEditar);
            }
        });
    }
    
    if (btnDeshabilitarDetalles) {
        btnDeshabilitarDetalles.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPerfil && currentPerfil.estado == 1) {
                abrirModal(modalDeshabilitar);
            }
        });
    }
    
    if (btnCambiarEstadoDetalles) {
        btnCambiarEstadoDetalles.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPerfil) {
                if (currentPerfil.estado == 1) {
                    abrirModal(modalDeshabilitar);
                } else {
                    const nombreSpan = document.getElementById('nombre-perfil-habilitar');
                    if (nombreSpan) nombreSpan.textContent = currentPerfil.nombre;
                    abrirModal(modalHabilitar);
                }
            }
        });
    }
    
    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
            const id = currentPerfilId || (currentPerfil ? currentPerfil.id_perfil : null);
            const nombre = currentPerfil ? currentPerfil.nombre : 'Perfil';
            
            if (id) {
                const success = await cambiarEstadoPerfil(id, 'desactivar');
                if (success) {
                    cerrarModal(modalDeshabilitar);
                    mostrarModalDeshabilitado(nombre);
                    
                    const cardPerfil = document.querySelector(`[data-perfil-id="${id}"]`);
                    if (cardPerfil) {
                        const switchBtn = cardPerfil.querySelector('.switch-sena');
                        if (switchBtn) {
                            switchBtn.classList.remove('active');
                            switchBtn.setAttribute('title', 'Inactivo');
                        }
                        actualizarEstadoPerfilEnCard(cardPerfil, false);
                    }
                    
                    if (isDetallesPage) {
                        cargarPerfilDetalle();
                    } else {
                        if (modoMisCreaciones) {
                            cargarMisCreaciones();
                        } else {
                            cargarPerfiles();
                        }
                    }
                }
            }
        });
    }
    
    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
            const id = currentPerfilId || (currentPerfil ? currentPerfil.id_perfil : null);
            const nombre = currentPerfil ? currentPerfil.nombre : 'Perfil';
            
            if (id) {
                const success = await cambiarEstadoPerfil(id, 'activar');
                if (success) {
                    cerrarModal(modalHabilitar);
                    mostrarModalHabilitado(nombre);
                    
                    const cardPerfil = document.querySelector(`[data-perfil-id="${id}"]`);
                    if (cardPerfil) {
                        const switchBtn = cardPerfil.querySelector('.switch-sena');
                        if (switchBtn) {
                            switchBtn.classList.add('active');
                            switchBtn.setAttribute('title', 'Activo');
                        }
                        actualizarEstadoPerfilEnCard(cardPerfil, true);
                    }
                    
                    if (isDetallesPage) {
                        cargarPerfilDetalle();
                    } else {
                        if (modoMisCreaciones) {
                            cargarMisCreaciones();
                        } else {
                            cargarPerfiles();
                        }
                    }
                }
            }
        });
    }
    
    // ===== BOTONES DE CERRAR MODALES =====
    document.querySelectorAll('.cerrar-modal-crear, .cerrar-modal, .cerrar-modal-deshabilitar, .cerrar-modal-habilitar, .cerrar-modal-habilitado-confirmacion, .cerrar-modal-deshabilitado, .cerrar-modal-editado, .cerrar-modal-creado').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let modal = this.closest('[id*="modal-"]');
            if (modal) cerrarModal(modal);
        });
    });
    
    // Cerrar modales con overlay
    const modales = [modalCrear, modalEditar, modalDeshabilitar, modalDeshabilitado, modalHabilitar, modalHabilitadoConfirmacion, modalEditadoConfirmacion, modalCreadoConfirmacion];
    modales.forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    cerrarModal(modal);
                }
            });
        }
    });
    
    // Cerrar con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modales.forEach(modal => {
                if (modal && !modal.classList.contains('hidden')) {
                    cerrarModal(modal);
                }
            });
        }
    });
    
    // Chips para modales
    document.querySelectorAll('#modal-crear-perfil .chip-crear, #modal-editar-perfil .chip').forEach(chip => {
        chip.addEventListener('click', function() {
            this.classList.toggle('selected');
            this.classList.toggle('bg-sena');
            this.classList.toggle('text-white');
            this.classList.toggle('border-sena');
            this.classList.toggle('border-sena-border');
        });
    });
    
    // ===== INICIAR APLICACIÓN =====
    if (isDetallesPage) {
        cargarPerfilDetalle();
    } else {
        cargarFiltrosLineas();
        cargarFiltrosTecnologias();
        cargarPerfiles();
    }
});