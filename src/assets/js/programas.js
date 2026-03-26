document.addEventListener("DOMContentLoaded", function () {
    // ===== REFERENCIAS A MODALES =====
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');
    
    const btnCrearPrograma = document.getElementById("btn-abrir-crear-programa");
    const modalCrear = document.getElementById("modal-crear-programa");
    const modalEditar = document.getElementById("modal-editar-programa");

    const AREA_URL = '../../controllers/AreaController.php';
    const PRO_URL = '../../controllers/ProgramaFormacionController.php';

    // ===== TIMERS =====
    let timeoutDeshabilitado = null, timeoutHabilitado = null;
    let intervalContadorHabilitado = null, intervalContadorDeshabilitado = null;

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
                renderizarProgramas(result.data);
            }
        } catch (error) {
            console.error('Error programas:', error);
            contenedor.innerHTML = '<div class="col-span-3 text-center py-10 text-red-500"><p>Error</p></div>';
        }
    }

    // ===== OBTENER NOMBRE NIVEL =====
    function getNombreNivel(idNivel) {
        const niveles = {
            '1': 'Técnico',
            '2': 'Tecnólogo',
            '3': 'Especialización',
            '4': 'Curso'
        };
        return niveles[idNivel] || 'N/A';
    }

   // ===== OBTENER COLOR NIVEL =====
    function getColorNivel(idNivel) {
        // Verde un poco más intenso que bg-sena-soft para diferenciar
        return 'bg-green-100 text-green-800';
    }

    // ===== RENDERIZAR PROGRAMAS =====
    function renderizarProgramas(programas) {
        const contenedor = document.getElementById('contenedorProgramas');
        if (!contenedor) return;
        
        contenedor.innerHTML = '';
        
        if (!programas || programas.length === 0) {
            contenedor.innerHTML = '<div class="col-span-3 text-center py-10"><p>No hay programas</p></div>';
            return;
        }
        
        programas.forEach(programa => {
            const card = document.createElement('div');
            card.className = 'bg-white border border-gray-200 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow programa-card';
            card.setAttribute('data-id', programa.id_programa);
            
            const nombreStr = String(programa.nombre_programa || '');
            const codigoStr = String(programa.codigo_programa || '');
            
            card.setAttribute('data-nombre', nombreStr.toLowerCase());
            card.setAttribute('data-codigo', codigoStr.toLowerCase());
            card.setAttribute('data-estado', programa.estado || 1);
            
            const estadoActivo = programa.estado == 1;
            const nombreNivel = getNombreNivel(programa.id_nivel);
            const colorNivel = getColorNivel(programa.id_nivel);
            
            // Usar nombre_area (como viene de la BD) o un valor por defecto
            const nombreArea = programa.nombre_area || 'N/A';

            let switchHTML = '';

            if (Auth.tienePermiso('desactivar_programa')) {
                switchHTML = `
                    <div class="switch-sena ${estadoActivo ? 'active' : ''}" data-id="${programa.id_programa}"></div>
                `;
            }
            
            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div class="flex items-center gap-2">
                        <button data-permiso="editar_programa" class="btn-editar-programa p-2 hover:bg-gray-100 rounded-lg transition-colors"
                            data-id="${programa.id_programa}"
                            data-codigo="${programa.codigo_programa || ''}"
                            data-nombre="${programa.nombre_programa || ''}"
                            data-nivel="${programa.id_nivel || ''}"
                            data-modalidad="${programa.modalidad || ''}"
                            data-fechainicio="${programa.fecha_creacion || ''}"
                            data-fechafin="${programa.fecha_fin || ''}"
                            data-cupos="${programa.cupos_formacion || ''}"
                            data-area="${programa.id_area || ''}">
                            <i data-lucide="pencil" class="w-4 h-4 text-gray-500"></i>
                        </button>
                        ${switchHTML}
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        # ${programa.codigo_programa || 'N/A'}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium ${colorNivel}">
                        <i data-lucide="layers" class="w-3 h-3"></i>
                        ${nombreNivel}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-sena-soft text-green-700">
                        <i data-lucide="monitor" class="w-3 h-3"></i>
                        ${programa.modalidad || 'N/A'}
                    </span>
                </div>
                
                <h3 class="font-semibold text-gray-800 text-base mb-4 leading-tight">${programa.nombre_programa || 'Sin nombre'}</h3>
                
                <div class="space-y-2 text-sm text-gray-500">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            ${programa.cupos_formacion || 'N/A'} cupos
                        </span>
                        <span class="text-gray-300">|</span>
                        <!-- CAMBIO: usar nombre_area en lugar de area_nombre -->
                        <span>Área: ${nombreArea}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
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
            `;
            
            contenedor.appendChild(card);
        });
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        inicializarSwitches();
        inicializarBotonesEditar();
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
                const nombrePrograma = card.querySelector('h3').textContent;
                const estadoActual = this.classList.contains('active');

                if (estadoActual) {
                    modalDeshabilitar?.setAttribute('data-switch-id', idPrograma);
                    modalDeshabilitar?.setAttribute('data-nombre-programa', nombrePrograma);
                    if (modalDeshabilitar) modalDeshabilitar.classList.remove('hidden');
                } else {
                    modalHabilitar?.setAttribute('data-switch-id', idPrograma);
                    modalHabilitar?.setAttribute('data-nombre-programa', nombrePrograma);
                    if (modalHabilitar) modalHabilitar.classList.remove('hidden');
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
                
                // Debug: ver datos del botón
                console.log('Datos del botón:', {
                    modalidad: boton.dataset.modalidad,
                    nivel: boton.dataset.nivel,
                    area: boton.dataset.area
                });
                
                const idInput = document.getElementById("idProgramaEditar");
                const codigoInput = document.getElementById("codigoPrograma");
                const nombreInput = document.getElementById("nombrePrograma");
                const cuposInput = document.getElementById("cuposPrograma");
                const nivelSelect = document.getElementById("nivelFormacion");
                const modalidadSelect = document.getElementById("modalidadPrograma");
                const fechaInicioInput = document.getElementById("fechaInicio");
                const fechaFinInput = document.getElementById("fechaFin");
                const areaSelect = document.getElementById("areaProgramaEditar");
                
                if (idInput) idInput.value = boton.dataset.id || '';
                if (codigoInput) codigoInput.value = boton.dataset.codigo || '';
                if (nombreInput) nombreInput.value = boton.dataset.nombre || '';
                if (cuposInput) cuposInput.value = boton.dataset.cupos || '';
                if (nivelSelect) nivelSelect.value = boton.dataset.nivel || '';
                if (fechaInicioInput) fechaInicioInput.value = boton.dataset.fechainicio || '';
                if (fechaFinInput) fechaFinInput.value = boton.dataset.fechafin || '';
                
                // Manejar modalidad (convertir a mayúsculas si es necesario)
                if (modalidadSelect && boton.dataset.modalidad) {
                    const modalidadValor = boton.dataset.modalidad.toUpperCase();
                    console.log('Asignando modalidad:', modalidadValor);
                    modalidadSelect.value = modalidadValor;
                    
                    // Si no coincide, intentar con el valor original
                    if (modalidadSelect.value !== modalidadValor) {
                        modalidadSelect.value = boton.dataset.modalidad;
                        console.log('Intentando con valor original:', boton.dataset.modalidad);
                    }
                }
                
                // Cargar áreas y seleccionar la correspondiente
                if (areaSelect) {
                    areaSelect.innerHTML = '<option value="">Cargando...</option>';
                    try {
                        const areas = await cargarAreas();
                        areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
                        areas.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.text;
                            areaSelect.appendChild(option);
                        });
                        if (boton.dataset.area) {
                            areaSelect.value = boton.dataset.area;
                        }
                    } catch (error) {
                        console.error('Error cargando áreas:', error);
                        areaSelect.innerHTML = '<option value="">Error al cargar</option>';
                    }
                }
                
                // Debug: ver valores asignados
                console.log('Valores asignados:', {
                    modalidad: modalidadSelect?.value,
                    nivel: nivelSelect?.value,
                    area: areaSelect?.value
                });
                
                const modal = document.getElementById("modal-editar-programa");
                if (modal) modal.classList.remove("hidden");
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
                cargarProgramas();
                return;
            }
            
            timeoutBusqueda = setTimeout(async () => {
                try {
                    const response = await fetch(`${PRO_URL}?accion=buscar&q=${encodeURIComponent(texto)}`);
                    const result = await response.json();
                    if (result.success && result.data) renderizarProgramas(result.data);
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
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitado-confirmacion, .cerrar-modal-habilitar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            cerrarModal(modalDeshabilitar);
            cerrarModal(modalHabilitar);
            cerrarModal(modalDeshabilitado);
            cerrarModal(modalHabilitadoConfirmacion);
        });
    });

    document.querySelectorAll(".cerrar-modal-editar").forEach(btn => {
        btn.addEventListener("click", () => { if (modalEditar) modalEditar.classList.add("hidden"); });
    });

    document.querySelectorAll(".cerrar-modal-crear").forEach(btn => {
        btn.addEventListener("click", () => { if (modalCrear) modalCrear.classList.add("hidden"); });
    });

    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function(e) {
            e.preventDefault();
            const switchId = modalDeshabilitar?.getAttribute('data-switch-id');
            if (!switchId) return;
            
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
                    mostrarModalDeshabilitado(modalDeshabilitar.getAttribute('data-nombre-programa') || 'Programa');
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
                    mostrarModalHabilitado(modalHabilitar.getAttribute('data-nombre-programa') || 'Programa');
                } else {
                    alert(result.error || 'Error');
                }
                
            } catch (error) {
                console.error(error);
                alert('Error de conexion');
            }
        });
    }
    // ===== GUARDAR NUEVO PROGRAMA =====
    const btnGuardarNuevo = document.getElementById('btn-guardar-nuevo-programa');
    if (btnGuardarNuevo) {
        btnGuardarNuevo.addEventListener('click', async () => {
            const datos = {
                id_area: document.getElementById("areaPrograma")?.value,
                codigo_programa: document.getElementById("codigoNuevoPrograma")?.value,
                nombre_programa: document.getElementById("nombreNuevoPrograma")?.value,
                id_nivel: document.getElementById("nivelNuevoPrograma")?.value,
                modalidad: document.getElementById("modalidadNuevoPrograma")?.value,
                fecha_creacion: document.getElementById("fechaInicioNuevoPrograma")?.value,
                fecha_fin: document.getElementById("fechaFinNuevoPrograma")?.value,
                cupos_formacion: document.getElementById("cuposNuevoPrograma")?.value,
                descripcion: document.getElementById("descripcionNuevoPrograma")?.value
            };

            if (!datos.id_area || !datos.codigo_programa || !datos.nombre_programa || !datos.id_nivel) {
                alert('Complete los campos requeridos');
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
                    alert(resultado.message);
                    if (modalCrear) modalCrear.classList.add("hidden");
                    cargarProgramas();
                } else {
                    alert(resultado.error);
                }
            } catch (error) {
                console.error(error);
                alert("Error al crear");
            }
        });
    }

    // ===== GUARDAR EDITADO =====
    const btnGuardarEditar = document.getElementById('btn-guardar-programa-editado');
    if (btnGuardarEditar) {
        btnGuardarEditar.addEventListener('click', async () => {
            const datos = {
                id_programa: document.getElementById("idProgramaEditar")?.value,
                id_area: document.getElementById("areaProgramaEditar")?.value, 
                codigo_programa: document.getElementById("codigoPrograma")?.value,
                nombre_programa: document.getElementById("nombrePrograma")?.value,
                id_nivel: document.getElementById("nivelFormacion")?.value,
                modalidad: document.getElementById("modalidadPrograma")?.value,
                fecha_creacion: document.getElementById("fechaInicio")?.value,
                fecha_fin: document.getElementById("fechaFin")?.value,
                cupos_formacion: document.getElementById("cuposPrograma")?.value,
                descripcion: document.getElementById("descripcionProgramaEditar")?.value
            };

            // Debug: ver qué datos se están enviando
            console.log('Datos a enviar:', datos);

            if (!datos.id_programa) {
                alert('ID no encontrado');
                return;
            }

            if (!datos.id_area) {
                alert('El área es requerida');
                return;
            }

            try {
                const response = await fetch(`${PRO_URL}?accion=actualizar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });
                const resultado = await response.json();
                
                // Debug: ver respuesta del servidor
                console.log('Respuesta del servidor:', resultado);
                
                if (resultado.success) {
                    alert(resultado.message);
                    if (modalEditar) modalEditar.classList.add("hidden");
                    cargarProgramas();
                } else {
                    alert(resultado.error || 'Error al actualizar');
                }
            } catch (error) {
                console.error(error);
                alert("Error al actualizar");
            }
        });
    }

    // ===== ABRIR MODAL CREAR =====
    if (btnCrearPrograma) {
        btnCrearPrograma.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (modalCrear) modalCrear.classList.remove('hidden');
        });
    }

    // ===== MODAL DESHABILITADO =====
    function mostrarModalDeshabilitado(nombrePerfil) {
        if (!modalDeshabilitado) return;
        if (nombrePerfilDeshabilitadoSpan) nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        abrirModal(modalDeshabilitado);
        
        let segundos = 6;
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
        timeoutDeshabilitado = setTimeout(() => { cerrarModalDeshabilitado(); }, 6000);
    }

    // ===== MODAL HABILITADO =====
    function mostrarModalHabilitado(nombrePerfil) {
        if (!modalHabilitadoConfirmacion) return;
        if (nombrePerfilHabilitadoExitoSpan) nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        abrirModal(modalHabilitadoConfirmacion);
        
        let segundos = 6;
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
        timeoutHabilitado = setTimeout(() => { cerrarModalHabilitado(); }, 6000);
    }

    // ===== CERRAR MODAL DESHABILITADO =====
    function cerrarModalDeshabilitado() {
        if (modalDeshabilitado) { modalDeshabilitado.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        if (timeoutDeshabilitado) { clearTimeout(timeoutDeshabilitado); timeoutDeshabilitado = null; }
        if (intervalContadorDeshabilitado) { clearInterval(intervalContadorDeshabilitado); intervalContadorDeshabilitado = null; }
        const pb = document.getElementById('progress-bar-deshabilitado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos-deshabilitado'); if (c) c.textContent = '6';
    }

    // ===== CERRAR MODAL HABILITADO =====
    function cerrarModalHabilitado() {
        if (modalHabilitadoConfirmacion) { modalHabilitadoConfirmacion.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        if (timeoutHabilitado) { clearTimeout(timeoutHabilitado); timeoutHabilitado = null; }
        if (intervalContadorHabilitado) { clearInterval(intervalContadorHabilitado); intervalContadorHabilitado = null; }
        const pb = document.getElementById('progress-bar-habilitado'); if (pb) pb.style.width = '0%';
        const c = document.getElementById('contador-segundos'); if (c) c.textContent = '6';
    }

    // ===== INICIALIZAR =====
    console.log('programas.js cargado correctamente');
    llenarSelectAreas('areaPrograma');
    llenarSelectAreas('areaProgramaEditar');
    cargarProgramas();
});