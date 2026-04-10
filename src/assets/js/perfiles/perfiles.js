// perfiles.js - Versión con filtros completamente funcionales

document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN API =====
    const API_BASE_URL = '../../controllers/PerfilOcupacionalController.php';
    
    // ===== CONSTANTE DE VIGENCIA (12 MESES) =====
    const MESES_VIGENCIA = 12;
    
    // ===== DEBUG: Mostrar información del usuario para verificar rol =====
    if (window.SENA_CONFIG) {
        console.log('🔐 Información del Usuario Logueado:');
        console.log('  ID Usuario:', window.SENA_CONFIG.USUARIO_ID);
        console.log('  Nombre:', window.SENA_CONFIG.USUARIO_NOMBRE);
        console.log('  Rol:', window.SENA_CONFIG.USUARIO_ROL);
        console.log('  Rol (lowercase):', String(window.SENA_CONFIG.USUARIO_ROL).toLowerCase());
    }
    
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
    
    // ===== VARIABLES PARA TIMEOUTS E INTERVALS DE CONTADORES =====
    let timeoutCreado = null;
    let intervalContadorCreado = null;
    let timeoutEditado = null;
    let intervalContadorEditado = null;
    let timeoutDeshabilitado = null;
    let intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    
    // Variables para countdown del modal de creación
    let timeoutCountdownCrear = null;
    let intervalCountdownCrear = null;
    
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
    
    // ===== VARIABLES PARA BUSCADOR =====
    let terminoBusqueda = '';
    const searchInput = document.getElementById('search-perfiles');
    
    // Variable para almacenar todos los perfiles (para búsqueda local)
    let todosLosPerfiles = [];
    
    // ===== VARIABLES PARA PAGINACIÓN =====
    let paginaActual = 1;
    const elementosPorPagina = 9;
    let perfilesFiltrados = [];
    
    // ===== FUNCIÓN PARA VERIFICAR SI UN PERFIL ESTÁ VENCIDO (12 MESES) =====
    function isPerfilVencido(fechaCreacion) {
        if (!fechaCreacion) return false;
        
        const fechaCreacionDate = new Date(fechaCreacion);
        const fechaActual = new Date();
        
        // Calcular la diferencia en meses
        let mesesDiferencia = (fechaActual.getFullYear() - fechaCreacionDate.getFullYear()) * 12;
        mesesDiferencia += fechaActual.getMonth() - fechaCreacionDate.getMonth();
        
        // Si la fecha de creación tiene más de 12 meses, está vencido
        return mesesDiferencia >= MESES_VIGENCIA;
    }
    
    // ===== FUNCIÓN PARA OBTENER DIAS RESTANTES DE VIGENCIA =====
    function getDiasRestantesVigencia(fechaCreacion) {
        if (!fechaCreacion) return 0;
        
        const fechaCreacionDate = new Date(fechaCreacion);
        const fechaVencimiento = new Date(fechaCreacionDate);
        fechaVencimiento.setMonth(fechaVencimiento.getMonth() + MESES_VIGENCIA);
        
        const fechaActual = new Date();
        const diferenciaMs = fechaVencimiento - fechaActual;
        const diferenciaDias = Math.ceil(diferenciaMs / (1000 * 60 * 60 * 24));
        
        return diferenciaDias > 0 ? diferenciaDias : 0;
    }
    
    // ===== FUNCIÓN PARA ACTUALIZAR EL ESTADO DE PERFILES VENCIDOS (SOLO VISUALMENTE) =====
    // NOTA: Esto solo cambia la visualización, no modifica la base de datos
    function actualizarEstadoVisualPerfilesVencidos(perfiles) {
        const perfilesActualizados = perfiles.map(perfil => {
            // Si el perfil está activo pero ya pasaron 12 meses, marcarlo como expirado visualmente
            if (perfil.estado == 1 && isPerfilVencido(perfil.fecha_creacion)) {
                return { ...perfil, estado: 0, _vencido_automaticamente: true };
            }
            return perfil;
        });
        return perfilesActualizados;
    }
    
    // ===== FUNCIÓN PARA MOSTRAR ALERTA DE PERFIL PRÓXIMO A VENCER =====
    function mostrarAlertaProximoVencer(perfil) {
        if (perfil.estado == 1 && perfil.fecha_creacion) {
            const diasRestantes = getDiasRestantesVigencia(perfil.fecha_creacion);
            
            // Si quedan menos de 30 días para vencer, mostrar advertencia
            if (diasRestantes > 0 && diasRestantes <= 30) {
                let mensaje = '';
                let tipo = 'warning';
                
                if (diasRestantes <= 7) {
                    mensaje = `⚠️ El perfil "${perfil.nombre}" vencerá en ${diasRestantes} días. ¡Renueva o actualiza la información!`;
                    tipo = 'error';
                } else if (diasRestantes <= 15) {
                    mensaje = `⚠️ El perfil "${perfil.nombre}" vencerá en ${diasRestantes} días. Considera actualizarlo pronto.`;
                    tipo = 'warning';
                } else {
                    mensaje = `ℹ️ El perfil "${perfil.nombre}" vencerá en ${diasRestantes} días.`;
                    tipo = 'info';
                }
                
                mostrarToastValidacion(mensaje, tipo);
            }
        }
    }
    
    // ===== FUNCIÓN PARA AGREGAR INDICADOR DE PRÓXIMO VENCIMIENTO EN LA TARJETA =====
    function getIndicadorVigencia(fechaCreacion, estado) {
        if (estado != 1 || !fechaCreacion) return '';
        
        const diasRestantes = getDiasRestantesVigencia(fechaCreacion);
        
        if (diasRestantes <= 0) {
            return `<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-red-100 text-red-600 flex-shrink-0 ml-2">
                        ⚠️ Vencido
                    </span>`;
        } else if (diasRestantes <= 30) {
            return `<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-yellow-100 text-yellow-700 flex-shrink-0 ml-2">
                        ⏰ ${diasRestantes} días
                    </span>`;
        }
        
        return '';
    }
    
    // ===== FUNCIÓN PARA AGREGAR INDICADOR DE VIGENCIA EN DETALLE =====
    function agregarIndicadorVigenciaDetalle(perfil) {
        const vigenciaCard = document.getElementById('vigencia-card');
        if (!vigenciaCard) return;
        
        const isActive = perfil.estado == 1;
        const diasRestantes = getDiasRestantesVigencia(perfil.fecha_creacion);
        const estaVencido = isPerfilVencido(perfil.fecha_creacion);
        
        if (isActive && !estaVencido && diasRestantes <= 30) {
            vigenciaCard.className = 'border border-yellow-300 rounded-xl bg-yellow-50 p-5 flex items-center gap-3';
            vigenciaCard.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-yellow-600 flex-shrink-0">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4"/>
                    <path d="M12 16h.01"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-700">Próximo a vencer</p>
                    <p class="text-xs text-yellow-600">Este perfil expirará en ${diasRestantes} días (vigencia 12 meses)</p>
                </div>
            `;
        } else if (isActive && estaVencido) {
            vigenciaCard.className = 'border border-red-200 rounded-xl bg-red-50 p-5 flex items-center gap-3';
            vigenciaCard.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-red-500 flex-shrink-0">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-600">Perfil Expirado</p>
                    <p class="text-xs text-red-500">Este perfil superó los ${MESES_VIGENCIA} meses de vigencia</p>
                </div>
            `;
        } else if (isActive && !estaVencido) {
            // Perfil vigente con más de 30 días
            vigenciaCard.className = 'border border-green-300 rounded-xl bg-green-50 p-5 flex items-center gap-3';
            vigenciaCard.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-600 flex-shrink-0">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <path d="m9 11 3 3L22 4"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-green-700">Perfil Vigente</p>
                    <p class="text-xs text-green-600">Este perfil se encuentra activo</p>
                </div>
            `;
        }
    }
    
    // ===== FUNCIÓN PARA MANEJAR ERRORES DE FETCH =====
    async function fetchJSON(url, options = {}) {
        try {
            console.log('Fetching:', url);
            const response = await fetch(url, options);
            
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
            }
            
            const text = await response.text();
            console.log('Respuesta recibida (primeros 200 chars):', text.substring(0, 200));
            
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Respuesta no es JSON válido:', text);
                throw new Error('El servidor no devolvió una respuesta JSON válida');
            }
        } catch (error) {
            console.error('Error en fetchJSON:', error);
            throw error;
        }
    }
    
    // ===== FUNCIÓN PARA MOSTRAR TOAST =====
    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        let toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(toastContainer);
        }
        
        const titulo = tipo === 'warning' ? 'Aviso' : 
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
    
    // ===== FUNCIONES PARA CERRAR MODALES DE CONFIRMACIÓN CON CONTADORES =====
    function cerrarModalCreado() {
        if (modalCreadoConfirmacion) {
            modalCreadoConfirmacion.classList.add('hidden');
            document.body.style.overflow = '';
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

    function cerrarModalEditado() {
        if (modalEditadoConfirmacion) {
            modalEditadoConfirmacion.classList.add('hidden');
            document.body.style.overflow = '';
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
        if (modalDeshabilitado) {
            modalDeshabilitado.classList.add('hidden');
            document.body.style.overflow = '';
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
            document.body.style.overflow = '';
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

    // ===== FUNCIONES DE COUNTDOWN PARA EL MODAL DE CREACIÓN =====
    function iniciarCountdownCrear(segundos = 5) {
        if (intervalCountdownCrear) {
            clearInterval(intervalCountdownCrear);
        }
        
        if (timeoutCountdownCrear) {
            clearTimeout(timeoutCountdownCrear);
        }
        
        timeoutCountdownCrear = setTimeout(() => {
            detenerCountdownCrear();
        }, segundos * 1000);
    }
    
    function detenerCountdownCrear() {
        if (intervalCountdownCrear) {
            clearInterval(intervalCountdownCrear);
            intervalCountdownCrear = null;
        }
        if (timeoutCountdownCrear) {
            clearTimeout(timeoutCountdownCrear);
            timeoutCountdownCrear = null;
        }
    }
    
    // ===== FUNCIONES PARA MOSTRAR MODALES CON CONTADORES =====
    function mostrarModalCreado(nombrePerfil) {
        if (!modalCreadoConfirmacion) return;

        const nombreSpan = document.getElementById('nombre-perfil-creado');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombrePerfil}"`;
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

    function mostrarModalEditado(nombrePerfil) {
        if (!modalEditadoConfirmacion) return;

        const nombreSpan = document.getElementById('nombre-perfil-editado');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombrePerfil}"`;
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

    function mostrarModalDeshabilitado(nombrePerfil) {
        if (!modalDeshabilitado) return;

        const nombreSpan = document.getElementById('nombre-perfil-deshabilitado');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombrePerfil}"`;
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

    function mostrarModalHabilitado(nombrePerfil) {
        if (!modalHabilitadoConfirmacion) return;

        const nombreSpan = document.getElementById('nombre-perfil-habilitado-exito');
        if (nombreSpan) {
            nombreSpan.textContent = `"${nombrePerfil}"`;
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
                
                terminoBusqueda = '';
                if (searchInput) searchInput.value = '';
                
                const data = await fetchJSON(`${API_BASE_URL}?accion=listarTodas`);
                
                if (data.status === 'success') {
                    // Aplicar verificación de vencimiento visual
                    todosLosPerfiles = actualizarEstadoVisualPerfilesVencidos(data.data);
                    renderizarPerfiles(todosLosPerfiles);
                    actualizarContador(todosLosPerfiles.length);
                    modoMisCreaciones = false;
                    if (btnMisCreaciones) {
                        btnMisCreaciones.classList.remove('bg-sena', 'text-white', 'border-sena', 'hover:opacity-90');
                        btnMisCreaciones.classList.add('text-sena-text-main', 'border-sena-border', 'bg-white', 'hover:bg-sena-soft');
                        // Restaurar texto original
                        btnMisCreaciones.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Mis creaciones`;
                    }
                    
                    // Verificar perfiles próximos a vencer y mostrar alertas
                    todosLosPerfiles.forEach(perfil => {
                        if (perfil.estado == 1 && !isPerfilVencido(perfil.fecha_creacion)) {
                            mostrarAlertaProximoVencer(perfil);
                        }
                    });
                } else {
                    mostrarToastValidacion('Error al cargar perfiles', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
            } finally {
                mostrarCargando(false);
            }
        }
    }

    async function cargarMisCreaciones() {
        if (!isDetallesPage) {
            try {
                mostrarCargando(true);
                
                terminoBusqueda = '';
                if (searchInput) searchInput.value = '';
                
                const userId = window.SENA_CONFIG?.USUARIO_ID;
                if (!userId) {
                    mostrarToastValidacion('No se pudo identificar el usuario', 'warning');
                    return;
                }
                
                const data = await fetchJSON(`${API_BASE_URL}?accion=obtenerPorUsuario&id_usuario=${userId}`);
                
                if (data.success) {
                    // Aplicar verificación de vencimiento visual
                    todosLosPerfiles = actualizarEstadoVisualPerfilesVencidos(data.data);
                    renderizarPerfiles(todosLosPerfiles);
                    actualizarContador(todosLosPerfiles.length);
                    modoMisCreaciones = true;
                    if (btnMisCreaciones) {
                        btnMisCreaciones.classList.add('bg-sena', 'text-white', 'border-sena', 'hover:opacity-90');
                        btnMisCreaciones.classList.remove('text-sena-text-main', 'border-sena-border', 'bg-white', 'hover:bg-sena-soft');
                        // Cambiar texto a "Ver todos"
                        btnMisCreaciones.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-white"><path d="M4 4v16h16"/><path d="m9 15 3-3 3 3"/><path d="M12 12V4"/></svg> Ver todos`;
                    }
                } else {
                    mostrarToastValidacion('Error al cargar tus creaciones', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
            } finally {
                mostrarCargando(false);
            }
        }
    }
    
    function actualizarPaginacion(totalElementos) {
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        const paginacionContainer = document.getElementById('paginacion');
        
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
            <div class="flex flex-col items-center gap-3">
                ${totalPaginas > 1 ? `
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${((paginaActual - 1) * elementosPorPagina) + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(paginaActual * elementosPorPagina, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> perfiles
                </div>
                ` : `
                <div class="text-sm text-sena-text-soft">
                    Total: <span class="font-medium text-sena">${totalElementos}</span> perfiles
                </div>
                `}
                
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    ${totalPaginas > 1 ? `
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
        
        if (totalPaginas > 1) {
            // Eventos para botones de paginación
            document.querySelectorAll('.btn-pagina').forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                newBtn.addEventListener('click', handlePaginaClick);
            });
            
            const btnPrimeraPage = document.querySelector('.btn-primera-pagina');
            if (btnPrimeraPage) {
                const newBtn = btnPrimeraPage.cloneNode(true);
                btnPrimeraPage.parentNode.replaceChild(newBtn, btnPrimeraPage);
                newBtn.addEventListener('click', handleFirstPageClick);
            }
            
            const btnPaginaAnterior = document.querySelector('.btn-pagina-anterior');
            if (btnPaginaAnterior) {
                const newBtn = btnPaginaAnterior.cloneNode(true);
                btnPaginaAnterior.parentNode.replaceChild(newBtn, btnPaginaAnterior);
                newBtn.addEventListener('click', handlePreviousPageClick);
            }
            
            const btnPaginaSiguiente = document.querySelector('.btn-pagina-siguiente');
            if (btnPaginaSiguiente) {
                const newBtn = btnPaginaSiguiente.cloneNode(true);
                btnPaginaSiguiente.parentNode.replaceChild(newBtn, btnPaginaSiguiente);
                newBtn.addEventListener('click', handleNextPageClick);
            }
            
            const btnUltimaPagina = document.querySelector('.btn-ultima-pagina');
            if (btnUltimaPagina) {
                const newBtn = btnUltimaPagina.cloneNode(true);
                btnUltimaPagina.parentNode.replaceChild(newBtn, btnUltimaPagina);
                newBtn.addEventListener('click', handleLastPageClick);
            }
        }
    }
    
    function handlePaginaClick(e) {
        e.preventDefault();
        const pagina = parseInt(this.getAttribute('data-pagina'));
        paginaActual = pagina;
        renderizarPaginaActual();
        actualizarPaginacion(perfilesFiltrados.length);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function handleFirstPageClick(e) {
        e.preventDefault();
        if (paginaActual !== 1) {
            paginaActual = 1;
            renderizarPaginaActual();
            actualizarPaginacion(perfilesFiltrados.length);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    function handlePreviousPageClick(e) {
        e.preventDefault();
        if (paginaActual > 1) {
            paginaActual--;
            renderizarPaginaActual();
            actualizarPaginacion(perfilesFiltrados.length);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    function handleNextPageClick(e) {
        e.preventDefault();
        const totalPaginas = Math.ceil(perfilesFiltrados.length / elementosPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            renderizarPaginaActual();
            actualizarPaginacion(perfilesFiltrados.length);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    function handleLastPageClick(e) {
        e.preventDefault();
        const totalPaginas = Math.ceil(perfilesFiltrados.length / elementosPorPagina);
        if (paginaActual !== totalPaginas) {
            paginaActual = totalPaginas;
            renderizarPaginaActual();
            actualizarPaginacion(perfilesFiltrados.length);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    async function cargarPerfilDetalle() {
        if (isDetallesPage && perfilIdFromUrl) {
            try {
                mostrarSkeleton(true);
                
                const result = await fetchJSON(`${API_BASE_URL}?accion=obtener&id_perfil=${perfilIdFromUrl}`);
                
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
                mostrarToastValidacion(error.message || 'Error al cargar los detalles del perfil', 'error');
            }
        }
    }
    
    function renderizarPerfiles(perfiles) {
        const container = document.querySelector('#perfiles-container, .flex.flex-col.gap-3');
        if (!container) return;
        
        // Guardar perfiles filtrados y resetear página
        perfilesFiltrados = perfiles;
        paginaActual = 1;
        
        // Renderizar página actual
        renderizarPaginaActual();
        
        // Actualizar paginación
        actualizarPaginacion(perfiles.length);
        
        inicializarEventosPerfiles();
    }
    
    function renderizarPaginaActual() {
        const container = document.querySelector('#perfiles-container, .flex.flex-col.gap-3');
        if (!container) return;
        
        if (perfilesFiltrados.length === 0) {
            // Detectar si hay filtros activos
            const hayFiltrosActivos = 
                document.querySelectorAll('.filtro-linea:checked, .filtro-tecnologia:checked, .filtro-tendencia:checked, .filtro-proyeccion:checked').length > 0 ||
                (document.getElementById('filtro-estado') && document.getElementById('filtro-estado').value !== 'todos');
            
            const hayBusqueda = terminoBusqueda && terminoBusqueda.trim() !== '';
            
            let mensajeVacio = '';
            
            if (hayBusqueda && hayFiltrosActivos) {
                // Sin resultados por búsqueda Y filtros
                mensajeVacio = `
                    <div class="text-center py-12 border border-sena-border rounded-xl">
                        <div class="mx-auto w-16 h-16 rounded-full bg-sena/10 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-sena" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft mb-4">Tu búsqueda "<strong>${escapeHtml(terminoBusqueda)}</strong>" no coincide con ningún perfil en los filtros seleccionados.</p>
                        <p class="text-xs text-sena-text-soft">Intenta:</p>
                        <ul class="text-xs text-sena-text-soft mt-2 space-y-1">
                            <li>✓ Cambiar los términos de búsqueda</li>
                            <li>✓ Ajustar los filtros aplicados</li>
                            <li>✓ Limpiar los filtros para ver todos los perfiles</li>
                        </ul>
                    </div>
                `;
            } else if (hayBusqueda) {
                // Sin resultados por búsqueda
                mensajeVacio = `
                    <div class="text-center py-12 border border-sena-border rounded-xl">
                        <div class="mx-auto w-16 h-16 rounded-full bg-sena/10 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-sena" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft mb-4">No hay perfiles que coincidan con "<strong>${escapeHtml(terminoBusqueda)}</strong>".</p>
                        <p class="text-xs text-sena-text-soft">Sugerencias:</p>
                        <ul class="text-xs text-sena-text-soft mt-2 space-y-1">
                            <li>✓ Verifica la ortografía del término buscado</li>
                            <li>✓ Intenta con palabras clave más generales</li>
                            <li>✓ Usa diferentes términos de búsqueda</li>
                        </ul>
                    </div>
                `;
            } else if (hayFiltrosActivos) {
                // Sin resultados por filtros
                mensajeVacio = `
                    <div class="text-center py-12 border border-sena-border rounded-xl">
                        <div class="mx-auto w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.5 4H16.5a2.25 2.25 0 012.25 2.25v11.5A2.25 2.25 0 0116.5 20h-9a2.25 2.25 0 01-2.25-2.25V6.25A2.25 2.25 0 017.5 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-sena-text-main mb-2">Sin perfiles disponibles</h3>
                        <p class="text-sm text-sena-text-soft mb-4">Los filtros seleccionados no tienen coincidencias.</p>
                        <p class="text-xs text-sena-text-soft">Opciones:</p>
                        <ul class="text-xs text-sena-text-soft mt-2 space-y-1">
                            <li>✓ Ajusta los filtros aplicados</li>
                            <li>✓ Prueba con diferentes opciones de filtrado</li>
                            <li>✓ Limpia los filtros para ver todos los perfiles</li>
                        </ul>
                    </div>
                `;
            } else {
                // Sin perfiles en general - personalizado para "Mis creaciones"
                if (modoMisCreaciones) {
                    mensajeVacio = `
                        <div class="text-center py-12 border border-sena-border rounded-xl">
                            <div class="mx-auto w-16 h-16 rounded-full bg-sena/10 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-sena" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.5 4H16.5a2.25 2.25 0 012.25 2.25v11.5A2.25 2.25 0 0116.5 20h-9a2.25 2.25 0 01-2.25-2.25V6.25A2.25 2.25 0 017.5 4z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-sena-text-main mb-2">Aún no has creado perfiles</h3>
                            <p class="text-sm text-sena-text-soft mb-4">Tu lista de creaciones está vacía. Comienza creando tu primer perfil ocupacional.</p>
                            <p class="text-xs text-sena-text-soft">Los perfiles te permiten:</p>
                            <ul class="text-xs text-sena-text-soft mt-2 space-y-1">
                                <li>✓ Definir requisitos y competencias</li>
                                <li>✓ Organizar información ocupacional</li>
                                <li>✓ Compartir con otros usuarios</li>
                            </ul>
                        </div>
                    `;
                } else {
                    mensajeVacio = `
                        <div class="text-center py-12 border border-sena-border rounded-xl bg-gradient-to-br from-blue-50 to-white">
                            <div class="mx-auto w-16 h-16 rounded-full bg-sena/10 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-sena" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-sena-text-main mb-2">No hay perfiles creados</h3>
                            <p class="text-sm text-sena-text-soft mb-4">Comienza creando tu primer perfil ocupacional.</p>
                            <p class="text-xs text-sena-text-soft">Los perfiles te permiten definir los requisitos y competencias necesarias.</p>
                        </div>
                    `;
                }
            }
            
            container.innerHTML = mensajeVacio;
            return;
        }
        
        // Obtener ID del usuario actual y su rol
        const usuarioActualId = window.SENA_CONFIG?.USUARIO_ID;
        const rolActual = window.SENA_CONFIG?.USUARIO_ROL;
        // Verificar si es administrador - comparar con diferentes formatos posibles
        const esAdmin = rolActual === 'ADMINISTRADOR' || 
                       rolActual === 'administrador' ||
                       rolActual === 1 || 
                       rolActual === '1' ||
                       String(rolActual).toLowerCase() === 'administrador';
        
        // Calcular índices para la página actual
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const perfilesEnPagina = perfilesFiltrados.slice(inicio, fin);
        
        container.innerHTML = perfilesEnPagina.map(perfil => {
            // Verificar si el usuario actual es el creador o es administrador
            const puedeEditar = esAdmin || parseInt(perfil.id_usuario) === parseInt(usuarioActualId);
            
            // Verificar si el perfil está vencido visualmente
            const estaVencidoVisual = perfil._vencido_automaticamente === true;
            
            // Obtener indicador de vigencia
            const indicadorVigencia = getIndicadorVigencia(perfil.fecha_creacion, perfil.estado);
            
            // DEBUG
            console.log(`📋 Perfil: ${perfil.nombre}`);
            console.log(`   ID Creador: ${perfil.id_usuario}, ID Usuario Actual: ${usuarioActualId}`);
            console.log(`   Es Admin: ${esAdmin}, Puede Editar: ${puedeEditar}`);
            console.log(`   Estado BD: ${perfil.estado}, Vencido Visual: ${estaVencidoVisual}`);
            
            // Determinar estado visual para mostrar
            const estadoVisual = estaVencidoVisual ? 0 : perfil.estado;
            const estadoTextoVisual = estadoVisual == 1 ? 'Vigente' : 'Expirado';
            const estadoClaseVisual = estadoVisual == 1 ? 'bg-sena/10 text-sena' : 'bg-red-100 text-red-600';
            
            // Generar botones de acciones solo si tiene permiso Y el perfil NO está vencido visualmente
            const botonesAcciones = (puedeEditar && !estaVencidoVisual) ? `
                <div class="flex items-center gap-1 flex-shrink-0">
                    <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="${perfil.id_perfil}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena ${estadoVisual == 1 ? 'active' : ''}" title="${estadoVisual == 1 ? 'Activo' : 'Inactivo'}"></div>
                </div>
            ` : '';
            
            return `
            <div class="tarjeta-perfil border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all ${estaVencidoVisual ? 'opacity-75 bg-gray-50' : ''}" data-perfil-id="${perfil.id_perfil}">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-sm font-semibold text-sena-text-main truncate">${escapeHtml(perfil.nombre)}</span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium ${estadoClaseVisual} flex-shrink-0">
                            ${estadoTextoVisual}
                        </span>
                        ${indicadorVigencia}
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
                        ${perfil.fecha_creacion ? `<span class="flex items-center gap-1 text-sena-text-soft/70">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            ${new Date(perfil.fecha_creacion).toLocaleDateString()}
                        </span>` : ''}
                    </div>
                    ${estaVencidoVisual ? `
                    <div class="mt-2 text-xs text-red-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-3 h-3">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Perfil vencido (superó los ${MESES_VIGENCIA} meses de vigencia)
                    </div>
                    ` : ''}
                </div>
                ${botonesAcciones}
                <a href="../../view/perfiles/perfiles_detalles.php?id=${perfil.id_perfil}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    Ver detalle
                </a>
            </div>
        `}).join('');
        
        inicializarEventosPerfiles();
    }
    
    function renderizarDetallePerfil(perfil) {
        // Verificar si el perfil está vencido visualmente
        const estaVencidoVisual = isPerfilVencido(perfil.fecha_creacion);
        const isActive = perfil.estado == 1 && !estaVencidoVisual;
        
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
        
        // Verificar permisos de edición
        const usuarioActualId = window.SENA_CONFIG?.USUARIO_ID;
        const rolActual = window.SENA_CONFIG?.USUARIO_ROL;
        // Verificar si es administrador - comparar con diferentes formatos posibles
        const esAdmin = rolActual === 'ADMINISTRADOR' || 
                       rolActual === 'administrador' ||
                       rolActual === 1 || 
                       rolActual === '1' ||
                       String(rolActual).toLowerCase() === 'administrador';
        const puedeEditar = (esAdmin || parseInt(perfil.id_usuario) === parseInt(usuarioActualId)) && !estaVencidoVisual;
        
        // Ocultar/mostrar botones según permisos
        const btnEditarDetalles = document.getElementById('btn-editar-perfil');
        const btnCambiarEstadoDetalles = document.getElementById('btn-cambiar-estado');
        
        if (btnEditarDetalles) {
            btnEditarDetalles.classList.toggle('hidden', !puedeEditar);
        }
        if (btnCambiarEstadoDetalles) {
            btnCambiarEstadoDetalles.classList.toggle('hidden', !puedeEditar);
        }
        
        const btnEstadoTexto = document.getElementById('btn-estado-texto');
        const btnCambiarEstadoElem = document.getElementById('btn-cambiar-estado');
        if (btnEstadoTexto && btnCambiarEstadoElem && puedeEditar) {
            if (isActive) {
                btnEstadoTexto.textContent = 'Desactivar';
                btnCambiarEstadoElem.className = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors';
            } else {
                btnEstadoTexto.textContent = 'Activar';
                btnCambiarEstadoElem.className = 'inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena rounded-lg bg-white text-sena hover:bg-sena/5 transition-colors';
            }
        } else if (btnCambiarEstadoElem && !puedeEditar) {
            btnCambiarEstadoElem.classList.add('hidden');
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
        
        // Agregar información de vigencia
        const vigenciaInfo = document.getElementById('vigencia-info');
        if (vigenciaInfo && perfil.fecha_creacion) {
            const diasRestantes = getDiasRestantesVigencia(perfil.fecha_creacion);
            const fechaVencimiento = new Date(perfil.fecha_creacion);
            fechaVencimiento.setMonth(fechaVencimiento.getMonth() + MESES_VIGENCIA);
            
            if (estaVencidoVisual) {
                vigenciaInfo.innerHTML = `
                    <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-sm text-red-600">
                            <strong>⚠️ Perfil vencido</strong><br>
                            Este perfil superó los ${MESES_VIGENCIA} meses de vigencia desde su creación (${formatearFecha(perfil.fecha_creacion)}).
                        </p>
                    </div>
                `;
            } else if (diasRestantes <= 30) {
                vigenciaInfo.innerHTML = `
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-sm text-yellow-700">
                            <strong>⏰ Próximo a vencer</strong><br>
                            Este perfil expirará en <strong>${diasRestantes} días</strong> (${formatearFecha(fechaVencimiento)}).
                            ${diasRestantes <= 7 ? '¡Renueva o actualiza la información pronto!' : ''}
                        </p>
                    </div>
                `;
            } else {
                vigenciaInfo.innerHTML = `
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-sm text-green-700">
                            <strong>✅ Perfil vigente</strong><br>
                            Vigencia: ${MESES_VIGENCIA} meses desde creación (${formatearFecha(perfil.fecha_creacion)}).
                            Vence el ${formatearFecha(fechaVencimiento)}.
                        </p>
                    </div>
                `;
            }
        }
        
        agregarIndicadorVigenciaDetalle(perfil);
    }
    
    async function crearPerfil(data) {
        try {
            const result = await fetchJSON(`${API_BASE_URL}?accion=crear`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            if (result.success) {
                cerrarModal(modalCrear);
                mostrarModalCreado(data.nombre);
                iniciarCountdownCrear(5);
                setTimeout(() => {
                    cerrarModal(modalCreadoConfirmacion);
                    detenerCountdownCrear();
                }, 5000);
                
                // Mostrar información sobre la vigencia
                mostrarToastValidacion(`El perfil tiene una vigencia de ${MESES_VIGENCIA} meses a partir de hoy`, 'info');
                
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
            mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
            return false;
        }
    }
    
    async function actualizarPerfil(id, data) {
        try {
            data.id_perfil = id;
            const result = await fetchJSON(`${API_BASE_URL}?accion=actualizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
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
            mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
            return false;
        }
    }
    
    async function obtenerPerfil(id) {
        try {
            const result = await fetchJSON(`${API_BASE_URL}?accion=obtener&id_perfil=${id}`);
            
            if (result.success) {
                return result.data;
            } else {
                mostrarToastValidacion(result.error || 'Error al obtener perfil', 'error');
                return null;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
            return null;
        }
    }
    
    async function cambiarEstadoPerfil(id, accion) {
        try {
            const result = await fetchJSON(`${API_BASE_URL}?accion=${accion}&id_perfil=${id}`, { method: 'POST' });
            
            if (result.success) {
                return true;
            } else {
                mostrarToastValidacion(result.error || `Error al ${accion} perfil`, 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarToastValidacion(error.message || 'Error de conexión al servidor', 'error');
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
    
    // NOTA: Las funciones mostrarModalHabilitado, mostrarModalEditado y mostrarModalCreado 
    // con los contadores ya están definidas arriba con su lógica completa
    
    // ===== FUNCIONES PARA CARGAR DATOS EN EL MODAL CREAR =====
    
    async function cargarLineasTecnologicas() {
        const selectLinea = document.getElementById('linea_tecnologica');
        if (!selectLinea) return;
        
        try {
            selectLinea.innerHTML = '<option value="" disabled selected>Cargando líneas...</option>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarLineasTecnologicas`);
            
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
            mostrarToastValidacion('Error al cargar líneas tecnológicas', 'error');
        }
    }
    
    async function cargarNivelesFormacion() {
        const selectNivel = document.getElementById('nivel_formacion');
        if (!selectNivel) return;
        
        try {
            selectNivel.innerHTML = '<option value="" disabled selected>Cargando niveles...</option>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarNivelesFormacion`);
            
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
            mostrarToastValidacion('Error al cargar niveles de formación', 'error');
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
            
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarProgramasPorLinea&id_linea=${id_linea}`);
            
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
                
                // Agregar opción "Ninguno cumple"
                const optionNinguno = document.createElement('option');
                optionNinguno.value = 'ninguno';
                optionNinguno.textContent = '─────────────────────────────────';
                optionNinguno.disabled = true;
                selectPrograma.appendChild(optionNinguno);
                
                const optionNoCumple = document.createElement('option');
                optionNoCumple.value = 'no-cumple';
                optionNoCumple.textContent = 'Ninguno de estos cumple con lo que busco';
                selectPrograma.appendChild(optionNoCumple);
            } else {
                selectPrograma.innerHTML = '<option value="" disabled selected>No hay programas para esta línea</option>';
                selectPrograma.disabled = true;
            }
        } catch (error) {
            console.error('Error cargando programas:', error);
            selectPrograma.innerHTML = '<option value="" disabled selected>Error al cargar programas</option>';
            selectPrograma.disabled = true;
            mostrarToastValidacion('Error al cargar programas de formación', 'error');
        }
    }
    
    async function cargarTecnologiasEmergentes() {
        const container = document.getElementById('tecnologias-emergentes');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando tecnologías...</div>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarTecnologiasEmergentes`);
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(tecnologia => {
                    const chip = document.createElement('div');
                    chip.className = 'chip-crear inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border border-sena-border bg-white text-sena-text-main cursor-pointer transition-all hover:border-sena hover:bg-sena/5';
                    chip.setAttribute('data-value', tecnologia.id_tendencia);
                    chip.textContent = tecnologia.nombre;
                    
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
            mostrarToastValidacion('Error al cargar tecnologías emergentes', 'error');
        }
    }
    
    // ===== FUNCIONES PARA CARGAR DATOS EN EL MODAL EDITAR =====
    
    async function cargarNivelesFormacionEditar() {
        const selectNivel = document.getElementById('editar_nivel_formacion');
        if (!selectNivel) return;
        
        try {
            selectNivel.innerHTML = '<option value="" disabled selected>Cargando niveles...</option>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarNivelesFormacion`);
            
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
            mostrarToastValidacion('Error al cargar niveles de formación', 'error');
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
        
        datosOriginalesEditar = {
            nombre: perfil.nombre || '',
            descripcion: perfil.descripcion || '',
            id_nivel: perfil.id_nivel || '',
            cupos: perfil.cupos || 1
        };
    }
    
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
    
    // ===== FUNCIÓN AUXILIAR PARA AGREGAR LISTENERS A FILTROS =====
    function agregarListenersAFiltros() {
        document.querySelectorAll('.filtro-linea, .filtro-tecnologia, .filtro-tendencia, .filtro-proyeccion').forEach(checkbox => {
            // Remover listeners anteriores (si existen) y agregar nuevos
            checkbox.removeEventListener('change', manejarCambioFiltro);
            checkbox.addEventListener('change', manejarCambioFiltro);
        });
    }
    
    function manejarCambioFiltro() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            aplicarFiltros();
        }, 300);
    }
    
    async function cargarFiltrosLineas() {
        const container = document.getElementById('filtros-lineas');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando líneas...</div>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarLineasTecnologicas`);
            
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
            agregarListenersAFiltros();
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
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarTecnologiasEmergentes`);
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(tecnologia => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2.5 cursor-pointer';
                    label.innerHTML = `
                        <input type="checkbox" class="filtro-tecnologia w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5" data-id="${tecnologia.id_tendencia}">
                        <span class="text-sm text-sena-text-main">${escapeHtml(tecnologia.nombre)}</span>
                    `;
                    container.appendChild(label);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay tecnologías disponibles</div>';
            }
            agregarListenersAFiltros();
        } catch (error) {
            console.error('Error cargando filtros de tecnologías:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar tecnologías</div>';
        }
    }
    
    async function cargarFiltrosTendencias() {
        const container = document.getElementById('filtros-tendencias');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando tendencias...</div>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarTendenciasActuales`);
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(tendencia => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2.5 cursor-pointer';
                    label.innerHTML = `
                        <input type="checkbox" class="filtro-tendencia w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5" data-id="${tendencia.id_tendencia}">
                        <span class="text-sm text-sena-text-main">${escapeHtml(tendencia.nombre)}</span>
                    `;
                    container.appendChild(label);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay tendencias disponibles</div>';
            }
            agregarListenersAFiltros();
        } catch (error) {
            console.error('Error cargando filtros de tendencias:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar tendencias</div>';
        }
    }
    
    async function cargarFiltrosProyecciones() {
        const container = document.getElementById('filtros-proyecciones');
        if (!container) return;
        
        try {
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Cargando proyecciones...</div>';
            const data = await fetchJSON(`${API_BASE_URL}?accion=listarProyeccionesFuturo`);
            
            if (data.success === true && data.data && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach(proyeccion => {
                    const label = document.createElement('label');
                    label.className = 'flex items-start gap-2.5 cursor-pointer';
                    label.innerHTML = `
                        <input type="checkbox" class="filtro-proyeccion w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5" data-id="${proyeccion.id_proyeccion}">
                        <span class="text-sm text-sena-text-main">${escapeHtml(proyeccion.nombre)} ${proyeccion.anio ? `(${proyeccion.anio})` : ''}</span>
                    `;
                    container.appendChild(label);
                });
            } else {
                container.innerHTML = '<div class="text-sm text-sena-text-soft">No hay proyecciones disponibles</div>';
            }
            agregarListenersAFiltros();
        } catch (error) {
            console.error('Error cargando filtros de proyecciones:', error);
            container.innerHTML = '<div class="text-sm text-sena-text-soft">Error al cargar proyecciones</div>';
        }
    }
    
    // ===== FUNCIONES DE FILTROS CORREGIDAS =====
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
        
        const tendenciasSeleccionadas = [];
        document.querySelectorAll('.filtro-tendencia:checked').forEach(chk => {
            tendenciasSeleccionadas.push(chk.getAttribute('data-id'));
        });
        
        const proyeccionesSeleccionadas = [];
        document.querySelectorAll('.filtro-proyeccion:checked').forEach(chk => {
            proyeccionesSeleccionadas.push(chk.getAttribute('data-id'));
        });
        
        // Construir objeto de filtros
        const filtros = {};
        
        // Estado
        if (estado !== 'todos') {
            filtros.estado = estado === 'activos' ? 1 : 0;
        }
        
        // Líneas tecnológicas
        if (lineasSeleccionadas.length > 0) {
            filtros.id_linea = lineasSeleccionadas;
        }
        
        // Tecnologías emergentes
        if (tecnologiasSeleccionadas.length > 0) {
            filtros.id_tecnologia = tecnologiasSeleccionadas;
        }
        
        // Tendencias actuales
        if (tendenciasSeleccionadas.length > 0) {
            filtros.id_tendencia = tendenciasSeleccionadas;
        }
        
        // Proyecciones a futuro
        if (proyeccionesSeleccionadas.length > 0) {
            filtros.id_proyeccion = proyeccionesSeleccionadas;
        }
        
        // Término de búsqueda
        if (terminoBusqueda) {
            filtros.termino = terminoBusqueda;
        }
        
        console.log('Filtros enviados al servidor:', filtros);
        
        try {
            mostrarCargando(true);
            
            const data = await fetchJSON(`${API_BASE_URL}?accion=buscarAvanzado`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filtros)
            });
            
            if (data.success) {
                // Aplicar verificación de vencimiento visual a los resultados
                const perfilesConVigencia = actualizarEstadoVisualPerfilesVencidos(data.data);
                renderizarPerfiles(perfilesConVigencia);
                actualizarContador(perfilesConVigencia.length);
                
            } else {
                mostrarToastValidacion(data.error || 'Error al aplicar filtros', 'error');
            }
        } catch (error) {
            console.error('Error en aplicarFiltros:', error);
            mostrarToastValidacion(error.message || 'Error al comunicarse con el servidor.', 'error');
        } finally {
            mostrarCargando(false);
        }
    }
    
    function limpiarFiltros(mostrarMensaje = true, recargar = true) {
        document.querySelectorAll('.filtro-linea').forEach(chk => {
            chk.checked = false;
        });
        
        document.querySelectorAll('.filtro-tecnologia').forEach(chk => {
            chk.checked = false;
        });
        
        document.querySelectorAll('.filtro-tendencia').forEach(chk => {
            chk.checked = false;
        });
        
        document.querySelectorAll('.filtro-proyeccion').forEach(chk => {
            chk.checked = false;
        });
        
        if (filtroEstado) filtroEstado.value = 'todos';
        
        // Limpiar buscador
        terminoBusqueda = '';
        if (searchInput) searchInput.value = '';
        
        // Solo recargar si se solicita explícitamente
        if (recargar) {
            if (modoMisCreaciones) {
                cargarMisCreaciones();
            } else {
                cargarPerfiles();
            }
        }
        
        if (mostrarMensaje) {
            mostrarToastValidacion('Filtros limpiados', 'success');
        }
    }
    
    // ===== EVENTO DEL BUSCADOR EN TIEMPO REAL =====
    let debounceTimer;
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            terminoBusqueda = e.target.value.trim();
            
            clearTimeout(debounceTimer);
            
            debounceTimer = setTimeout(() => {
                // Si hay filtros seleccionados, aplicar todos juntos
                const hayFiltrosSeleccionados = 
                    document.querySelectorAll('.filtro-linea:checked, .filtro-tecnologia:checked, .filtro-tendencia:checked, .filtro-proyeccion:checked').length > 0 ||
                    (filtroEstado && filtroEstado.value !== 'todos');
                
                if (hayFiltrosSeleccionados || terminoBusqueda !== '') {
                    aplicarFiltros();
                } else if (terminoBusqueda === '') {
                    // Si no hay término de búsqueda y no hay filtros, mostrar todos
                    if (modoMisCreaciones) {
                        cargarMisCreaciones();
                    } else {
                        cargarPerfiles();
                    }
                }
            }, 300);
        });
    }
    
    // Escuchar cambios en select de estado
    if (filtroEstado) {
        filtroEstado.addEventListener('change', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                aplicarFiltros();
            }, 300);
        });
    }
    
    // ===== EVENTO DEL BOTÓN MIS CREACIONES (CORREGIDO) =====
    if (btnMisCreaciones) {
        // Eliminar eventos anteriores para evitar duplicados
        const nuevoBtn = btnMisCreaciones.cloneNode(true);
        btnMisCreaciones.parentNode.replaceChild(nuevoBtn, btnMisCreaciones);
        
        nuevoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Limpiar buscador
            terminoBusqueda = '';
            if (searchInput) searchInput.value = '';
            
            // Limpiar filtros visualmente (sin recargar)
            document.querySelectorAll('.filtro-linea, .filtro-tecnologia, .filtro-tendencia, .filtro-proyeccion').forEach(chk => {
                chk.checked = false;
            });
            if (filtroEstado) filtroEstado.value = 'todos';
            
            // Cambiar modo y cargar datos correspondientes
            if (modoMisCreaciones) {
                modoMisCreaciones = false;
                cargarPerfiles();
                // Cambiar estilo del botón
                this.classList.remove('bg-sena', 'text-white', 'border-sena', 'hover:opacity-90');
                this.classList.add('text-sena-text-main', 'border-sena-border', 'bg-white', 'hover:bg-sena-soft');
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Mis creaciones`;
            } else {
                modoMisCreaciones = true;
                cargarMisCreaciones();
                // Cambiar estilo del botón
                this.classList.add('bg-sena', 'text-white', 'border-sena', 'hover:opacity-90');
                this.classList.remove('text-sena-text-main', 'border-sena-border', 'bg-white', 'hover:bg-sena-soft');
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-white"><path d="M4 4v16h16"/><path d="m9 15 3-3 3 3"/><path d="M12 12V4"/></svg> Ver todos`;
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
        
        // Verificar si el perfil está vencido antes de permitir edición
        const perfil = await obtenerPerfil(perfilId);
        if (perfil && isPerfilVencido(perfil.fecha_creacion)) {
            mostrarToastValidacion('No se puede editar un perfil vencido. La vigencia es de 12 meses.', 'error');
            return;
        }
        
        currentPerfilId = perfilId;
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
        
        // Verificar si el perfil está vencido (solo para activación)
        const perfil = await obtenerPerfil(perfilId);
        
        if (!estaActivo && perfil && isPerfilVencido(perfil.fecha_creacion)) {
            mostrarToastValidacion(`No se puede activar un perfil vencido. La vigencia es de ${MESES_VIGENCIA} meses.`, 'error');
            return;
        }
        
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
            
            const nombre = document.querySelector('#modal-crear-perfil input[name="nombre"]')?.value || '';
            const descripcion = document.querySelector('#modal-crear-perfil textarea[name="descripcion"]')?.value || '';
            const cupos = document.querySelector('#modal-crear-perfil input[name="cupos"]')?.value || '';
            const id_linea = document.querySelector('#modal-crear-perfil select[name="id_linea"]')?.value || '';
            const id_programa = document.querySelector('#modal-crear-perfil select[name="id_programa"]')?.value || '';
            const id_nivel = document.querySelector('#modal-crear-perfil select[name="id_nivel"]')?.value || '';
            
            if (!validarNombre(nombre)) return;
            if (!validarDescripcion(descripcion)) return;
            if (!validarCupos(cupos)) return;
            if (!validarLineaTecnologica(id_linea)) return;
            if (!validarProgramaFormacion(id_programa)) return;
            if (!validarNivelFormacion(id_nivel)) return;
            
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
            
            if (!hayCambiosEnEditar()) {
                mostrarToastValidacion('No se ha realizado ningún cambio en el perfil', 'info');
                return;
            }
            
            const nombre = document.querySelector('#modal-editar-perfil input[name="nombre"]')?.value || '';
            const descripcion = document.querySelector('#modal-editar-perfil textarea[name="descripcion"]')?.value || '';
            const cupos = document.querySelector('#modal-editar-perfil input[name="cupos"]')?.value || '';
            const id_nivel = document.querySelector('#modal-editar-perfil select[name="id_nivel"]')?.value || '';
            
            if (!validarNombre(nombre)) return;
            if (!validarDescripcion(descripcion)) return;
            if (!validarCupos(cupos)) return;
            if (!validarNivelFormacion(id_nivel)) return;
            
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
    
    // ===== EVENTO DEL SELECT DE PROGRAMA =====
    const selectPrograma = document.getElementById('programa_formacion');
    if (selectPrograma) {
        selectPrograma.addEventListener('change', function(e) {
            if (e.target.value === 'no-cumple') {
                e.preventDefault();
                e.target.value = ''; // Reset el select
                abrirModal(document.getElementById('modal-ninguno-cumple'));
            }
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
                // Verificar si el perfil está vencido
                if (isPerfilVencido(currentPerfil.fecha_creacion)) {
                    mostrarToastValidacion('No se puede editar un perfil vencido. La vigencia es de 12 meses.', 'error');
                    return;
                }
                
                // Verificar permisos nuevamente por seguridad
                const usuarioActualId = window.SENA_CONFIG?.USUARIO_ID;
                const rolActual = window.SENA_CONFIG?.USUARIO_ROL;
                const esAdmin = rolActual === 'ADMINISTRADOR' || 
                               rolActual === 'administrador' ||
                               rolActual === 1 || 
                               rolActual === '1' ||
                               String(rolActual).toLowerCase() === 'administrador';
                const puedeEditar = esAdmin || parseInt(currentPerfil.id_usuario) === parseInt(usuarioActualId);
                
                if (!puedeEditar) {
                    mostrarToastValidacion('No tienes permiso para editar este perfil', 'error');
                    return;
                }
                
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
                // Verificar si el perfil está vencido
                if (isPerfilVencido(currentPerfil.fecha_creacion)) {
                    mostrarToastValidacion('Este perfil ya está vencido (12 meses de vigencia cumplidos)', 'warning');
                    return;
                }
                
                // Verificar permisos nuevamente por seguridad
                const usuarioActualId = window.SENA_CONFIG?.USUARIO_ID;
                const rolActual = window.SENA_CONFIG?.USUARIO_ROL;
                const esAdmin = rolActual === 'ADMINISTRADOR' || 
                               rolActual === 'administrador' ||
                               rolActual === 1 || 
                               rolActual === '1' ||
                               String(rolActual).toLowerCase() === 'administrador';
                const puedeEditar = esAdmin || parseInt(currentPerfil.id_usuario) === parseInt(usuarioActualId);
                
                if (!puedeEditar) {
                    mostrarToastValidacion('No tienes permiso para cambiar el estado de este perfil', 'error');
                    return;
                }
                
                abrirModal(modalDeshabilitar);
            }
        });
    }
    
    if (btnCambiarEstadoDetalles) {
        btnCambiarEstadoDetalles.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPerfil) {
                // Verificar permisos nuevamente por seguridad
                const usuarioActualId = window.SENA_CONFIG?.USUARIO_ID;
                const rolActual = window.SENA_CONFIG?.USUARIO_ROL;
                const esAdmin = rolActual === 'ADMINISTRADOR' || 
                               rolActual === 'administrador' ||
                               rolActual === 1 || 
                               rolActual === '1' ||
                               String(rolActual).toLowerCase() === 'administrador';
                const puedeEditar = esAdmin || parseInt(currentPerfil.id_usuario) === parseInt(usuarioActualId);
                
                if (!puedeEditar) {
                    mostrarToastValidacion('No tienes permiso para cambiar el estado de este perfil', 'error');
                    return;
                }
                
                if (currentPerfil.estado == 1) {
                    // Verificar si el perfil está vencido antes de permitir desactivación manual
                    if (isPerfilVencido(currentPerfil.fecha_creacion)) {
                        mostrarToastValidacion('Este perfil ya está vencido (12 meses de vigencia cumplidos)', 'warning');
                        return;
                    }
                    abrirModal(modalDeshabilitar);
                } else {
                    // Verificar si el perfil está vencido antes de permitir activación
                    if (isPerfilVencido(currentPerfil.fecha_creacion)) {
                        mostrarToastValidacion(`No se puede activar un perfil vencido. La vigencia es de ${MESES_VIGENCIA} meses.`, 'error');
                        return;
                    }
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
                // Verificar nuevamente si el perfil está vencido
                const perfilActual = await obtenerPerfil(id);
                if (perfilActual && isPerfilVencido(perfilActual.fecha_creacion)) {
                    mostrarToastValidacion(`No se puede activar un perfil vencido. La vigencia es de ${MESES_VIGENCIA} meses.`, 'error');
                    cerrarModal(modalHabilitar);
                    return;
                }
                
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
    
    // ===== EVENTO PARA EL MODAL DE NINGUNO CUMPLE =====
    const modalNinguno = document.getElementById('modal-ninguno-cumple');
    const btnIrSugerencias = document.getElementById('btn-ir-sugerencias');
    
    if (btnIrSugerencias) {
        btnIrSugerencias.addEventListener('click', function() {
            if (modalNinguno) cerrarModal(modalNinguno);
            // Redirigir a la página de sugerencias
            const sugerenciasUrl = window.location.pathname.replace('/perfiles/perfiles.php', '/sugerencias/sugerencias.php');
            window.location.href = sugerenciasUrl;
        });
    }
    
    // Eventos de cerrar modal para Ninguno cumple
    document.querySelectorAll('.cerrar-modal-ninguno').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (modalNinguno) cerrarModal(modalNinguno);
        });
    });
    
    // Cerrar modal de Ninguno cumple con overlay
    if (modalNinguno) {
        modalNinguno.addEventListener('click', function(e) {
            if (e.target === modalNinguno) {
                cerrarModal(modalNinguno);
            }
        });
    }
    
    // ===== BOTONES DE CERRAR MODALES =====
    document.querySelectorAll('.cerrar-modal-crear, .cerrar-modal, .cerrar-modal-deshabilitar, .cerrar-modal-habilitar, .cerrar-modal-habilitado-confirmacion, .cerrar-modal-deshabilitado, .cerrar-modal-editado, .cerrar-modal-creado').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let modal = this.closest('[id*="modal-"]');
            if (modal) {
                // Detener countdown según el modal
                if (modal.id === 'modal-creado-confirmacion') {
                    cerrarModalCreado();
                } else if (modal.id === 'modal-editado-confirmacion') {
                    cerrarModalEditado();
                } else if (modal.id === 'modal-deshabilitado-perfil') {
                    cerrarModalDeshabilitado();
                } else if (modal.id === 'modal-habilitado-confirmacion') {
                    cerrarModalHabilitado();
                } else {
                    cerrarModal(modal);
                }
            }
        });
    });
    
    // Cerrar modales con overlay
    const modales = [modalCrear, modalEditar, modalDeshabilitar, modalDeshabilitado, modalHabilitar, modalHabilitadoConfirmacion, modalEditadoConfirmacion, modalCreadoConfirmacion, modalNinguno];
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
        cargarFiltrosTendencias();
        cargarFiltrosProyecciones();
        cargarPerfiles();
    }
});