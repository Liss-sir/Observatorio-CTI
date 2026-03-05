document.addEventListener('DOMContentLoaded', function() {
    // ===== CONFIGURACIÓN =====
    // Cambiar a EtapaDesarrolloController
    const API_URL = '../../controllers/EtapaDesarrolloController.php';
    
    // ===== VARIABLES DE MODALES =====
    // Mantener los IDs originales del HTML (con "tendencia")
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
    
    // Variables para almacenar datos actuales (ahora serán etapas)
    let etapasActuales = [];
    let areasActuales = [];
    let etapaSeleccionada = null;

    // ===== FUNCIÓN PARA MOSTRAR TOAST DE VALIDACIÓN =====
    function mostrarToastValidacion(mensaje, tipo = 'warning') {
        const toastContainer = document.getElementById('toast-container');
        
        // Si no existe el contenedor, crearlo
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none';
            document.body.appendChild(container);
        }
        
        const container = document.getElementById('toast-container');
        
        // Definir títulos según el tipo
        const titulo = tipo === 'warning' ? 'Campo requerido' : 
               tipo === 'error' ? 'Error' : 
               tipo === 'info' ? 'Información' : 'Éxito';
        
        // Crear elemento toast
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast-validation ${tipo}`;
        
        // Iconos según tipo
        const iconos = {
            info: `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>`,
            warning: `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            `,
            error: `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `,
            success: `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            `
        };

        toast.innerHTML = `
            <div class="toast-contenido">
                <div class="toast-icono-wrapper">
                    <div class="toast-icono">
                        ${iconos[tipo] || iconos.warning}
                    </div>
                </div>
                <div class="toast-mensaje-wrapper">
                    <div class="toast-titulo">${titulo}</div>
                    <div class="toast-mensaje">${mensaje}</div>
                </div>
            </div>
        `;

        container.appendChild(toast);

        // Auto cerrar después de 3 segundos
        setTimeout(() => {
            const toastElement = document.getElementById(toastId);
            if (toastElement) {
                toastElement.classList.add('exit');
                setTimeout(() => {
                    if (toastElement.parentNode) {
                        toastElement.remove();
                    }
                }, 200);
            }
        }, 3000);
    }

    // ===== FUNCIONES DE API =====
    async function cargarEtapas() {
        try {
            // Usar listarTodas para obtener también las inactivas
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
                
                // Llenar el select de áreas en los modales (usando IDs del HTML)
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

    // Función para actualizar solo el switch visualmente
    function actualizarSwitchVisual(id, activar) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach(tarjeta => {
            if (tarjeta.getAttribute('data-id') == id) {
                const switchEl = tarjeta.querySelector('.switch-sena');
                if (switchEl) {
                    if (activar) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    } else {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                    }
                }
                
                // También actualizar la bolita de estado
                const estadoBolita = tarjeta.querySelector('.estado-bolita');
                if (estadoBolita) {
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${activar ? 'bg-sena' : 'bg-gray-300'}`;
                }
            }
        });
    }

    // Función para actualizar una tarjeta específica
    function actualizarTarjetaVisual(data) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        tarjetas.forEach(tarjeta => {
            if (tarjeta.getAttribute('data-id') == data.id_etapa) {
                // Actualizar título
                const tituloEl = tarjeta.querySelector('h3');
                if (tituloEl) tituloEl.textContent = data.nombre;
                
                // Actualizar descripción
                const descripcionEl = tarjeta.querySelector('p.text-xs');
                if (descripcionEl) descripcionEl.textContent = data.descripcion || 'Sin descripción';
                
                // Actualizar área (badge)
                const badgeArea = tarjeta.querySelector('.badge-area');
                if (badgeArea) {
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    badgeArea.textContent = area ? area.nombre_area : 'Área';
                }
                
                // Actualizar estado del switch
                const switchEl = tarjeta.querySelector('.switch-sena');
                if (switchEl) {
                    if (data.estado == 1) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    } else {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                    }
                }
                
                // Actualizar la bolita de estado
                const estadoBolita = tarjeta.querySelector('.estado-bolita');
                if (estadoBolita) {
                    estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${data.estado == 1 ? 'bg-sena' : 'bg-gray-300'}`;
                }
            }
        });
    }

    // Función para agregar una nueva tarjeta
    function agregarTarjetaVisual(etapa) {
        const contenedor = document.getElementById('contenedor-tendencias');
        const template = document.getElementById('template-tendencia');
        
        const card = template.content.cloneNode(true);
        const div = card.querySelector('.tarjeta-tecnologia');
        
        div.setAttribute('data-id', etapa.id_etapa);
        div.setAttribute('data-area', etapa.id_area);
        
        div.querySelector('h3').textContent = etapa.nombre;
        div.querySelector('p.text-xs').textContent = etapa.descripcion || 'Sin descripción';
        
        const badgeArea = div.querySelector('.badge-area');
        if (badgeArea) {
            badgeArea.textContent = etapa.nombre_area;
        }
        
        const switchEl = div.querySelector('.switch-sena');
        if (etapa.estado == 1) {
            switchEl.classList.add('active');
            switchEl.setAttribute('title', 'Activo');
        } else {
            switchEl.setAttribute('title', 'Inactivo');
        }
        
        // Actualizar la bolita de estado según el estado de la etapa
        const estadoBolita = div.querySelector('.estado-bolita');
        if (estadoBolita) {
            estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${etapa.estado == 1 ? 'bg-sena' : 'bg-gray-300'}`;
        }
        
        contenedor.appendChild(div);
        
        // Asignar eventos a la nueva tarjeta
        asignarEventosTarjeta(div);
    }

    // Función para asignar eventos a una tarjeta específica
    function asignarEventosTarjeta(tarjeta) {
        // Evento para botón editar
        const btnEditar = tarjeta.querySelector('.btn-editar-tendencia');
        if (btnEditar) {
            btnEditar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const idEtapa = tarjeta.getAttribute('data-id');
                etapaSeleccionada = etapasActuales.find(e => e.id_etapa == idEtapa);
                
                if (!etapaSeleccionada) return;
                
                // Llenar el select de áreas en el modal editar
                const areaSelect = document.getElementById('area-tendencia');
                if (areaSelect && areasActuales.length > 0) {
                    areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                    areasActuales.forEach(area => {
                        areaSelect.innerHTML += `<option value="${area.id_area}">${area.nombre_area}</option>`;
                    });
                }
                
                // Llenar el formulario con los datos
                const nombreInput = document.getElementById('nombre-tendencia');
                const descripcionInput = document.getElementById('descripcion-tendencia');
                const estadoSelect = document.getElementById('estado-tendencia');
                
                if (nombreInput) nombreInput.value = etapaSeleccionada.nombre;
                if (areaSelect) areaSelect.value = etapaSeleccionada.id_area;
                if (descripcionInput) descripcionInput.value = etapaSeleccionada.descripcion || '';
                if (estadoSelect) estadoSelect.value = etapaSeleccionada.estado == 1 ? 'activo' : 'inactivo';
                
                // Guardar ID en el modal
                if (modalEditar) {
                    modalEditar.setAttribute('data-id', idEtapa);
                }
                
                abrirModal(modalEditar);
            });
        }
        
        // Evento para switch
        const switchEl = tarjeta.querySelector('.switch-sena');
        if (switchEl) {
            switchEl.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const idEtapa = tarjeta.getAttribute('data-id');
                const nombreEtapa = tarjeta.querySelector('h3').textContent;
                const estaActivo = this.classList.contains('active');
                
                if (estaActivo) {
                    // Deshabilitar
                    const spanNombre = modalDeshabilitar.querySelector('span.font-medium');
                    if (spanNombre) {
                        spanNombre.textContent = `"${nombreEtapa}"`;
                    }
                    modalDeshabilitar.setAttribute('data-id', idEtapa);
                    abrirModal(modalDeshabilitar);
                } else {
                    // Habilitar
                    const spanNombre = modalHabilitar.querySelector('span.font-semibold');
                    if (spanNombre) {
                        spanNombre.textContent = `"${nombreEtapa}"`;
                    }
                    modalHabilitar.setAttribute('data-id', idEtapa);
                    modalHabilitar.setAttribute('data-nombre', nombreEtapa);
                    abrirModal(modalHabilitar);
                }
            });
        }
        
        // Evento para abrir detalle
        tarjeta.addEventListener('click', function(e) {
            if (e.target.closest('.btn-editar-tendencia') || e.target.closest('.switch-sena')) {
                return;
            }
            
            const idEtapa = this.getAttribute('data-id');
            const etapa = etapasActuales.find(e => e.id_etapa == idEtapa);
            
            if (!etapa) return;
            
            document.getElementById('detalle-titulo').textContent = `Detalle de: ${etapa.nombre}`;
            document.getElementById('detalle-nombre').textContent = etapa.nombre;
            document.getElementById('detalle-descripcion').textContent = etapa.descripcion || 'Sin descripción';
            
            const estadoTexto = etapa.estado == 1 ? 'Activo' : 'Inactivo';
            const colorEstado = etapa.estado == 1 ? '#39A900' : '#9ca3af';
            
            document.getElementById('detalle-estado').textContent = estadoTexto;
            document.getElementById('detalle-estado-indicador').style.backgroundColor = colorEstado;
            
            // Mostrar área
            const area = areasActuales.find(a => a.id_area == etapa.id_area);
            document.getElementById('detalle-area').textContent = area ? area.nombre_area : 'No especificada';
            
            abrirModal(modalDetalle);
        });
    }

    // Modifica crearEtapa para que agregue la nueva tarjeta sin recargar todo
    async function crearEtapa(data) {
        try {
            const response = await fetch(`${API_URL}?accion=crear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Obtener el área para mostrar en la tarjeta
                const area = areasActuales.find(a => a.id_area == data.id_area);
                
                // Crear la nueva etapa para el array local
                const nuevaEtapa = {
                    id_etapa: result.id_etapa,
                    id_area: data.id_area,
                    nombre: data.nombre,
                    descripcion: data.descripcion,
                    estado: data.estado,
                    nombre_area: area ? area.nombre_area : 'Área'
                };
                
                // Agregar al array local
                etapasActuales.push(nuevaEtapa);
                
                // Verificar si antes no había etapas (estaba mostrando empty state)
                const estabaVacio = etapasActuales.length === 1;
                
                if (estabaVacio) {
                    // Si estaba vacío, volver a renderizar todo para que desaparezca el empty state
                    renderizarEtapas(etapasActuales);
                } else {
                    // Si ya había etapas, solo agregar la nueva tarjeta
                    agregarTarjetaVisual(nuevaEtapa);
                }
                
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

    // Modifica actualizarEtapa para que no recargue todo
    async function actualizarEtapa(data) {
        try {
            const response = await fetch(`${API_URL}?accion=actualizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Actualizar los datos locales sin recargar todo
                const etapaIndex = etapasActuales.findIndex(e => e.id_etapa == data.id_etapa);
                if (etapaIndex !== -1) {
                    etapasActuales[etapaIndex] = {
                        ...etapasActuales[etapaIndex],
                        nombre: data.nombre,
                        id_area: data.id_area,
                        descripcion: data.descripcion,
                        estado: data.estado
                    };
                    
                    // Actualizar también el nombre del área
                    const area = areasActuales.find(a => a.id_area == data.id_area);
                    if (area) {
                        etapasActuales[etapaIndex].nombre_area = area.nombre_area;
                    }
                }
                
                // Actualizar la tarjeta visualmente
                actualizarTarjetaVisual(data);
                
                // Actualizar contador (aunque no cambie el número, por si acaso)
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

    // Modifica cambiarEstadoEtapa para que no recargue todo
    async function cambiarEstadoEtapa(id, accion) {
        try {
            const response = await fetch(`${API_URL}?accion=${accion}&id_etapa=${id}`, {
                method: 'POST'
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Actualizar el estado en el array local
                const etapaIndex = etapasActuales.findIndex(e => e.id_etapa == id);
                if (etapaIndex !== -1) {
                    etapasActuales[etapaIndex].estado = accion === 'activar' ? 1 : 0;
                }
                
                // Actualizar SOLO el switch y la bolita visualmente sin recargar todo
                actualizarSwitchVisual(id, accion === 'activar');
                
                // Actualizar contador (no cambia el número, pero por consistencia)
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
                headers: {
                    'Content-Type': 'application/json'
                },
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

    // ===== FUNCIONES DE RENDERIZADO =====
    function renderizarEtapas(etapas) {
        const contenedor = document.getElementById('contenedor-tendencias');
        const template = document.getElementById('template-tendencia');
        const terminoBusqueda = document.getElementById('buscador-tendencias').value.trim();
        
        // Limpiar completamente el contenedor
        contenedor.innerHTML = '';
        
        if (etapas.length === 0) {
            // Verificar si es porque no hay etapas en general o porque la búsqueda no dio resultados
            const hayBusquedaActiva = terminoBusqueda.length > 0;
            
            // Cambiar clase del contenedor para empty state
            contenedor.className = '';
            
            if (hayBusquedaActiva) {
                // Mensaje para cuando la búsqueda no tiene resultados
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </div>
                        <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm">
                            No hay etapas que coincidan con <span class="font-medium text-sena">"${terminoBusqueda}"</span>. Prueba con otras palabras clave.
                        </p>
                    </div>
                `;
            } else {
                // Mensaje para cuando no hay etapas creadas (empty state original)
                contenedor.innerHTML = `
                    <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
                        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 6.75h2.25c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 8.625 20.625V7.875c0-.621.504-1.125 1.125-1.125ZM16.5 3.75h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15.375 20.625V4.875c0-.621.504-1.125 1.125-1.125Z" />
                            </svg>
                        </div>
                        <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No hay tendencias actuales</h3>
                        <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">
                            Comienza creando tu primera tendencia actual para fortalecer el análisis tecnológico de Risaralda.
                        </p>
                        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/>
                                <path d="M12 5v14"/>
                            </svg>
                            Crear primera etapa
                        </button>
                    </div>
                `;
                
                // Asignar evento al botón del empty state
                const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
                if (btnCrearEmpty && modalCrear) {
                    btnCrearEmpty.addEventListener('click', function() {
                        const form = document.getElementById('form-nueva-tendencia-actual');
                        if (form) form.reset();
                        abrirModal(modalCrear);
                    });
                }
            }
            return;
        }
        
        // Restaurar clase de grid cuando hay etapas
        contenedor.className = 'grid grid-cols-1 md:grid-cols-3 gap-3';
        
        etapas.forEach(etapa => {
            const card = template.content.cloneNode(true);
            const div = card.querySelector('.tarjeta-tecnologia');
            
            div.setAttribute('data-id', etapa.id_etapa);
            div.setAttribute('data-area', etapa.id_area);
            
            div.querySelector('h3').textContent = etapa.nombre;
            div.querySelector('p.text-xs').textContent = etapa.descripcion || 'Sin descripción';
            
            const badgeArea = div.querySelector('.badge-area');
            if (badgeArea) {
                badgeArea.textContent = etapa.nombre_area;
            }
            
            const switchEl = div.querySelector('.switch-sena');
            if (etapa.estado == 1) {
                switchEl.classList.add('active');
                switchEl.setAttribute('title', 'Activo');
            } else {
                switchEl.setAttribute('title', 'Inactivo');
            }
            
            // Actualizar la bolita de estado según el estado de la etapa
            const estadoBolita = div.querySelector('.estado-bolita');
            if (estadoBolita) {
                estadoBolita.className = `estado-bolita w-2 h-2 rounded-full flex-shrink-0 ${etapa.estado == 1 ? 'bg-sena' : 'bg-gray-300'}`;
            }
            
            contenedor.appendChild(div);
        });
        
        // Asignar eventos individualmente a cada tarjeta
        document.querySelectorAll('.tarjeta-tecnologia').forEach(tarjeta => {
            asignarEventosTarjeta(tarjeta);
        });
    }

    function actualizarContador(total) {
        const contador = document.getElementById('total-tendencias');
        if (contador) {
            contador.textContent = total;
        }
    }

    // ===== FUNCIONES DE FILTRADO =====
    function filtrarEtapas() {
        const terminoBusqueda = document.getElementById('buscador-tendencias').value.toLowerCase();
        
        let filtradas = etapasActuales;
        
        if (terminoBusqueda) {
            filtradas = filtradas.filter(e => 
                e.nombre.toLowerCase().includes(terminoBusqueda) ||
                (e.descripcion && e.descripcion.toLowerCase().includes(terminoBusqueda)) ||
                (e.nombre_area && e.nombre_area.toLowerCase().includes(terminoBusqueda))
            );
        }
        
        renderizarEtapas(filtradas);
        actualizarContador(filtradas.length);
    }

    // ===== FUNCIONES DE MODALES =====
    function abrirModal(modal) {
        if (modal) {
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
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        const contador = document.getElementById('contador-segundos-editado');
        if (contador) {
            contador.textContent = '3';
        }
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
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) {
            contador.textContent = '3';
        }
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
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        const contador = document.getElementById('contador-segundos-habilitado');
        if (contador) {
            contador.textContent = '3';
        }
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
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        const contador = document.getElementById('contador-segundos-creado');
        if (contador) {
            contador.textContent = '3';
        }
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

    // ===== ASIGNAR EVENTOS A TARJETAS =====
    function asignarEventosTarjetas() {
        document.querySelectorAll('.tarjeta-tecnologia').forEach(tarjeta => {
            asignarEventosTarjeta(tarjeta);
        });
    }

    // ===== EVENTOS PRINCIPALES =====
    
    // Cargar datos iniciales
    cargarEtapas();
    cargarAreas();
    
    // Evento de búsqueda
    const buscador = document.getElementById('buscador-tendencias');
    if (buscador) {
        buscador.addEventListener('input', filtrarEtapas);
    }
    
    // Evento para abrir modal crear
    if (btnCrearTendencia) {
        btnCrearTendencia.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Llenar el select de áreas justo antes de abrir el modal
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
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE CREAR =====
    const formCrear = document.getElementById('form-nueva-tendencia-actual');
    if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const areaSelect = this.querySelector('select[name="area"]');
            const nombreInput = this.querySelector('input[name="nombre"]');
            const descripcionTextarea = this.querySelector('textarea[name="descripcion"]');
            
            const id_area = areaSelect.value;
            const nombre = nombreInput.value.trim();
            const descripcion = descripcionTextarea.value.trim();
            
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            
            if (!nombre) {
                mostrarToastValidacion('El nombre de la etapa es requerido', 'warning');
                return;
            }
            
            if (!descripcion) {
                mostrarToastValidacion('La descripción de la etapa es requerida', 'warning');
                return;
            }
            
            // Verificar si ya existe
            const existe = await verificarNombreExistente(nombre, id_area);
            if (existe) {
                mostrarToastValidacion('Ya existe una etapa con ese nombre en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await crearEtapa({
                id_area: parseInt(id_area),
                nombre: nombre,
                descripcion: descripcion,
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
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE EDICIÓN =====
    const formEditar = document.getElementById('form-editar-tendencia');
    if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalEditar.getAttribute('data-id');
            const nombre = document.getElementById('nombre-tendencia').value.trim();
            const id_area = document.getElementById('area-tendencia').value;
            const descripcion = document.getElementById('descripcion-tendencia').value.trim();
            const estado = document.getElementById('estado-tendencia').value === 'activo' ? 1 : 0;
            
            // Validaciones básicas
            if (!nombre) {
                mostrarToastValidacion('El nombre de la etapa es requerido', 'warning');
                return;
            }
            
            if (!id_area) {
                mostrarToastValidacion('Debe seleccionar un área', 'warning');
                return;
            }
            
            if (!descripcion) {
                mostrarToastValidacion('La descripción de la etapa es requerida', 'warning');
                return;
            }
            
            // ===== VALIDACIÓN DE CAMPOS SIN CAMBIOS =====
            // Obtener los datos originales de la etapa seleccionada
            const datosOriginales = etapaSeleccionada;
            
            // Comparar campos para ver si hubo cambios
            const nombreCambio = nombre !== datosOriginales.nombre;
            const areaCambio = parseInt(id_area) !== datosOriginales.id_area;
            const descripcionCambio = descripcion !== (datosOriginales.descripcion || '');
            const estadoCambio = estado !== datosOriginales.estado;
            
            // Si no hay ningún cambio
            if (!nombreCambio && !areaCambio && !descripcionCambio && !estadoCambio) {
                mostrarToastValidacion('No se ha realizado ningún cambio en la etapa', 'info');
                return;
            }
            
            // Verificar si ya existe (excluyendo esta etapa)
            const existe = await verificarNombreExistente(nombre, id_area, id_etapa);
            if (existe) {
                mostrarToastValidacion('Ya existe una etapa con ese nombre en el área seleccionada', 'info');
                return;
            }
            
            const resultado = await actualizarEtapa({
                id_etapa: parseInt(id_etapa),
                id_area: parseInt(id_area),
                nombre: nombre,
                descripcion: descripcion,
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
    
    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalDeshabilitar.getAttribute('data-id');
            const nombreEtapa = modalDeshabilitar.querySelector('span.font-medium').textContent.replace(/"/g, '');
            
            const resultado = await cambiarEstadoEtapa(id_etapa, 'desactivar');
            
            if (resultado.success) {
                cerrarModal(modalDeshabilitar);
                mostrarModalDeshabilitado(nombreEtapa);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al deshabilitar la etapa', 'error');
            }
        });
    }
    
    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const id_etapa = modalHabilitar.getAttribute('data-id');
            const nombreEtapa = modalHabilitar.getAttribute('data-nombre');
            
            const resultado = await cambiarEstadoEtapa(id_etapa, 'activar');
            
            if (resultado.success) {
                cerrarModal(modalHabilitar);
                mostrarModalHabilitado(nombreEtapa);
            } else {
                mostrarToastValidacion(resultado.error || 'Error al habilitar la etapa', 'error');
            }
        });
    }
    
    // ===== CERRAR MODALES =====
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
    
    // ===== CERRAR MODALES HACIENDO CLICK EN OVERLAY =====
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
    
    // ===== CERRAR CON TECLA ESC =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (modalEditar && !modalEditar.classList.contains('hidden')) {
                cerrarModal(modalEditar);
            } else if (modalEditadoConfirmacion && !modalEditadoConfirmacion.classList.contains('hidden')) {
                cerrarModalEditado();
            } else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
                cerrarModal(modalDeshabilitar);
            } else if (modalDeshabilitadoConfirmacion && !modalDeshabilitadoConfirmacion.classList.contains('hidden')) {
                cerrarModalDeshabilitado();
            } else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
                cerrarModal(modalHabilitar);
            } else if (modalHabilitadoConfirmacion && !modalHabilitadoConfirmacion.classList.contains('hidden')) {
                cerrarModalHabilitado();
            } else if (modalCrear && !modalCrear.classList.contains('hidden')) {
                cerrarModal(modalCrear);
            } else if (modalCreadoConfirmacion && !modalCreadoConfirmacion.classList.contains('hidden')) {
                cerrarModalCreado();
            } else if (modalDetalle && !modalDetalle.classList.contains('hidden')) {
                cerrarModal(modalDetalle);
            }
        }
    });
});
