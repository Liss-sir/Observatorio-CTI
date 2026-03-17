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
            card.className = 'bg-white border p-5 rounded-xl shadow programa-card';
            card.setAttribute('data-id', programa.id_programa);
            
            const nombreStr = String(programa.nombre_programa || '');
            const codigoStr = String(programa.codigo_programa || '');
            
            card.setAttribute('data-nombre', nombreStr.toLowerCase());
            card.setAttribute('data-codigo', codigoStr.toLowerCase());
            card.setAttribute('data-estado', programa.estado || 1);
            
            const estadoActivo = programa.estado == 1;
            
            card.innerHTML = `
                <div class="flex justify-between items-start mb-3">
                    <div class="bg-sena-soft p-2 rounded-lg">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-green-600"></i>
                    </div>
                    <div class="flex items-center gap-1">
                        <button class="btn-editar-programa p-1.5 rounded-lg"
                            data-id="${programa.id_programa}"
                            data-codigo="${programa.codigo_programa || ''}"
                            data-nombre="${programa.nombre_programa || ''}"
                            data-nivel="${programa.id_nivel || ''}"
                            data-modalidad="${programa.modalidad || ''}"
                            data-fechainicio="${programa.fecha_creacion || ''}"
                            data-fechafin="${programa.fecha_fin || ''}"
                            data-cupos="${programa.cupos || ''}"
                            data-area="${programa.id_area || ''}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4">
                                <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                            </svg>
                        </button>
                        <div class="switch-sena ${estadoActivo ? 'active' : ''} cursor-pointer" data-id="${programa.id_programa}"></div>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-800 mb-3">${programa.nombre_programa || 'Sin nombre'}</h3>
                <div class="text-xs text-gray-500">
                    <span>${programa.cupos || 'N/A'} cupos</span>
                    <span class="mx-2">|</span>
                    <span>Area: ${programa.area_nombre || 'N/A'}</span>
                </div>
            `;
            
            contenedor.appendChild(card);
        });
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
        
        inicializarSwitches();
        inicializarBotonesEditar();
    }

    // ===== FORMATO FECHA =====
    function formatoFecha(fecha) {
        if (!fecha) return 'N/A';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
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
            
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                document.getElementById("codigoPrograma").value = this.dataset.codigo || '';
                document.getElementById("cuposPrograma").value = this.dataset.cupos || '';
                document.getElementById("nombrePrograma").value = this.dataset.nombre || '';
                document.getElementById("nivelFormacion").value = this.dataset.nivel || '';
                document.getElementById("modalidadPrograma").value = this.dataset.modalidad || '';
                document.getElementById("fechaInicio").value = this.dataset.fechainicio || '';
                document.getElementById("fechaFin").value = this.dataset.fechafin || '';
                document.getElementById("idProgramaEditar").value = this.dataset.id || '';
                
                if (this.dataset.area) {
                    const selectArea = document.getElementById("areaProgramaEditar");
                    if (selectArea) selectArea.dataset.valor = this.dataset.area;
                }
                
                if (modalEditar) modalEditar.classList.remove("hidden");
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
                            if (sw) { sw.classList.remove('active'); sw.setAttribute('title', 'Inactivo'); }
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
                            if (sw) { sw.classList.add('active'); sw.setAttribute('title', 'Activo'); }
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
                cupos: document.getElementById("cuposNuevoPrograma")?.value
            };

            if (!datos.id_area || !datos.codigo_programa || !datos.nombre_programa || !datos.id_nivel) {
                alert('Complete los campos requeridos');
                return;
            }

            try {
                const response = await fetch(`${PRO_URL}p?accion=crear`, {
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
                codigo_programa: document.getElementById("codigoPrograma")?.value,
                nombre_programa: document.getElementById("nombrePrograma")?.value,
                id_nivel: document.getElementById("nivelFormacion")?.value,
                modalidad: document.getElementById("modalidadPrograma")?.value,
                fecha_creacion: document.getElementById("fechaInicio")?.value,
                fecha_fin: document.getElementById("fechaFin")?.value,
                cupos: document.getElementById("cuposPrograma")?.value
            };

            if (!datos.id_programa) {
                alert('ID no encontrado');
                return;
            }

            try {
                const response = await fetch(`${PRO_URL}?accion=actualizar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });
                const resultado = await response.json();
                if (resultado.success) {
                    alert(resultado.message);
                    if (modalEditar) modalEditar.classList.add("hidden");
                    cargarProgramas();
                } else {
                    alert(resultado.error);
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