document.addEventListener("DOMContentLoaded", function () {

    // ========== ELEMENTOS DEL DOM ==========
    // Modales
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');
    const modalCrear = document.getElementById("modal-crear-programa");
    const modalEditar = document.getElementById("modal-editar-programa");

    // Botones de confirmación
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnGuardarNuevo = document.getElementById('btn-guardar-nuevo-programa');
    const btnGuardarEdicion = document.getElementById('btn-guardar-edicion');
    const btnCrearPrograma = document.getElementById('btn-abrir-crear-programa');

    // Elementos de texto en modales
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');

    // Búsqueda
    const buscador = document.getElementById("buscador");
    const contenedorProgramas = document.getElementById("contenedorProgramas");

    const API = "../../controllers/";

    // ========== VARIABLES PARA TIMERS ==========
    let timeoutDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorDeshabilitado = null;
    let intervalContadorHabilitado = null;

    // ========== FUNCIONES AUXILIARES ==========
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

    // Cerrar modal haciendo clic en el fondo oscuro
    document.querySelectorAll('.fixed.inset-0.bg-black').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                const modal = this.closest('.fixed.inset-0.z-50');
                if (modal) cerrarModal(modal);
            }
        });
    });

    // ========== FUNCIONES PARA MODALES DE CONFIRMACIÓN (DESHABILITAR/HABILITAR) ==========
    function cerrarModalDeshabilitado() {
        if (modalDeshabilitado) {
            modalDeshabilitado.classList.add('hidden');
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
        if (contador) contador.textContent = '6';
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
        const contador = document.getElementById('contador-segundos');
        if (contador) contador.textContent = '6';
    }

    function mostrarModalDeshabilitado(nombrePerfil) {
        if (!modalDeshabilitado) return;
        if (nombrePerfilDeshabilitadoSpan) {
            nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        }
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

        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }

        if (timeoutDeshabilitado) clearTimeout(timeoutDeshabilitado);
        timeoutDeshabilitado = setTimeout(cerrarModalDeshabilitado, 6000);
    }

    function mostrarModalHabilitado(nombrePerfil) {
        if (!modalHabilitadoConfirmacion) return;
        if (nombrePerfilHabilitadoExitoSpan) {
            nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
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

        const progressBar = document.getElementById('progress-bar-habilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => progressBar.style.width = '100%', 50);
        }

        if (timeoutHabilitado) clearTimeout(timeoutHabilitado);
        timeoutHabilitado = setTimeout(cerrarModalHabilitado, 6000);
    }

    // ========== BÚSQUEDA EN TIEMPO REAL ==========
    if (buscador && contenedorProgramas) {
        buscador.addEventListener("keyup", function () {
            const texto = this.value.toLowerCase();
            const cards = contenedorProgramas.querySelectorAll(".programa-card");
            cards.forEach(card => {
                const nombre = card.dataset.nombre ? card.dataset.nombre.toLowerCase() : '';
                const codigo = card.dataset.codigo ? card.dataset.codigo.toLowerCase() : '';
                if (nombre.includes(texto) || codigo.includes(texto)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    // ========== MANEJADOR DE SWITCHES (TOGGLE) ==========
    function manejadorSwitch(e) {
        e.preventDefault();
        e.stopPropagation();

        const card = this.closest('.programa-card, .border');
        if (!card) return;

        const nombrePrograma = card.querySelector('.font-semibold')?.textContent || 'Programa';
        const id = card.getAttribute('data-id');
        if (!id) return;

        if (this.classList.contains('active')) {
            // Quiere desactivar
            const spanNombre = modalDeshabilitar?.querySelector('span.font-medium');
            if (spanNombre) spanNombre.textContent = `"${nombrePrograma}"`;
            if (modalDeshabilitar) modalDeshabilitar.setAttribute('data-switch-id', id);
            abrirModal(modalDeshabilitar);
        } else {
            // Quiere activar
            const spanNombre = modalHabilitar?.querySelector('span.font-semibold');
            if (spanNombre) spanNombre.textContent = `"${nombrePrograma}"`;
            if (modalHabilitar) {
                modalHabilitar.setAttribute('data-switch-id', id);
                modalHabilitar.setAttribute('data-nombre-programa', nombrePrograma);
            }
            abrirModal(modalHabilitar);
        }
    }

    function inicializarSwitches() {
        document.querySelectorAll('.switch-sena').forEach(switchEl => {
            // Eliminar eventos previos para evitar duplicados
            switchEl.removeEventListener('click', manejadorSwitch);
            switchEl.addEventListener('click', manejadorSwitch);
        });
    }

    // Inicializar switches al cargar la página
    inicializarSwitches();

    // Observar cambios en el contenedor para inicializar nuevos switches (ej. después de recargar vía AJAX)
    if (contenedorProgramas) {
        const observer = new MutationObserver(function(mutations) {
            inicializarSwitches();
        });
        observer.observe(contenedorProgramas, {
            childList: true,
            subtree: true
        });
    }

    // ========== CONFIRMAR DESHABILITAR ==========
    if (btnConfirmarDeshabilitar && modalDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function(e) {
            e.preventDefault();

            const switchId = modalDeshabilitar.getAttribute('data-switch-id');
            const nombrePrograma = modalDeshabilitar.querySelector('span.font-medium')?.textContent || 'Programa';

            // Cambiar visualmente el switch a inactivo
            if (switchId) {
                const card = document.querySelector(`[data-id="${switchId}"]`);
                if (card) {
                    const switchEl = card.querySelector('.switch-sena');
                    if (switchEl) {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                    }
                }
            }

            // Llamar al servidor para desactivar
            console.log("ID programa:", switchId);
            try {
                const response = await fetch(`${API}ProgramaFormacionController.php?accion=desactivar&id_programa=${switchId}`, {
                    method: 'GET' // o POST según el controlador (usa GET en el switch final)
                });
                const resultado = await response.json();
                if (!resultado.success) {
                    alert(resultado.error || 'Error al desactivar el programa');
                    // Revertir cambio visual si falla
                    if (switchId) {
                        const card = document.querySelector(`[data-id="${switchId}"]`);
                        if (card) {
                            const switchEl = card.querySelector('.switch-sena');
                            if (switchEl) {
                                switchEl.classList.add('active');
                                switchEl.setAttribute('title', 'Activo');
                            }
                        }
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión al desactivar');
            }

            cerrarModal(modalDeshabilitar);
            mostrarModalDeshabilitado(nombrePrograma);
        });
    }

    // ========== CONFIRMAR HABILITAR ==========
    if (btnConfirmarHabilitar && modalHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function(e) {
            e.preventDefault();

            const switchId = modalHabilitar.getAttribute('data-switch-id');
            const nombrePrograma = modalHabilitar.getAttribute('data-nombre-programa') || 'Programa';

            // Cambiar visualmente el switch a activo
            if (switchId) {
                const card = document.querySelector(`[data-id="${switchId}"]`);
                if (card) {
                    const switchEl = card.querySelector('.switch-sena');
                    if (switchEl) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    }
                }
            }

            // Llamar al servidor para activar
            try {
                const response = await fetch(`${API}ProgramaFormacionController.php?accion=activar&id_programa=${switchId}`, {
                    method: 'GET'
                });
                const resultado = await response.json();
                if (!resultado.success) {
                    alert(resultado.error || 'Error al activar el programa');
                    // Revertir cambio visual si falla
                    if (switchId) {
                        const card = document.querySelector(`[data-id="${switchId}"]`);
                        if (card) {
                            const switchEl = card.querySelector('.switch-sena');
                            if (switchEl) {
                                switchEl.classList.remove('active');
                                switchEl.setAttribute('title', 'Inactivo');
                            }
                        }
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión al activar');
            }

            cerrarModal(modalHabilitar);
            mostrarModalHabilitado(nombrePrograma);
        });
    }


    // abrir modal
    if (btnCrearPrograma && modalCrear) {
        btnCrearPrograma.addEventListener("click", () => {
            modalCrear.classList.remove("hidden");
        });
    }

    // cerrar modal
    document.querySelectorAll(".cerrar-modal-crear").forEach(btn => {
        btn.addEventListener("click", () => {
            modalCrear.classList.add("hidden");
        });
    });

    // ========== CREAR NUEVO PROGRAMA ==========
    if (btnGuardarNuevo && modalCrear) {
        btnGuardarNuevo.addEventListener('click', async function(e) {
            e.preventDefault();

            const areaSeleccionada = document.getElementById("areaPrograma").value;
            const codigo = document.getElementById("codigoNuevoPrograma").value;
            const nombre = document.getElementById("nombreNuevoPrograma").value;
            const nivel = document.getElementById("nivelNuevoPrograma").value;
            const fechaInicio = document.getElementById("fechaInicioNuevoPrograma").value;
            const fechaFin = document.getElementById("fechaFinNuevoPrograma").value;
            const cupos = document.getElementById("cuposNuevoPrograma").value;
            const descripcion = document.getElementById("descripcionNuevoPrograma").value;

            // Recoger datos del formulario
            const datos = {
                id_area: document.getElementById("areaPrograma")?.value,
                codigo_programa: document.getElementById("codigoNuevoPrograma")?.value,
                nombre_programa: document.getElementById("nombreNuevoPrograma")?.value,
                id_nivel: document.getElementById("nivelNuevoPrograma")?.value,
                modalidad: document.getElementById("modalidadNuevoPrograma")?.value,
                fecha_creacion: document.getElementById("fechaInicioNuevoPrograma")?.value,
                fecha_fin: document.getElementById("fechaFinNuevoPrograma")?.value,
                cupos: document.getElementById("cuposNuevoPrograma")?.value,
                descripcion: document.getElementById("descripcionNuevoPrograma")?.value || ''
            };

            // Validación básica
            for (let key in datos) {
                if (!datos[key] && key !== 'descripcion') {
                    alert(`El campo ${key} es obligatorio`);
                    return;
                }
            }

            try {
                const response = await fetch(`${API}ProgramaFormacionController.php?accion=crear`, {
                    method: "POST",
                    headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_area: areaSeleccionada,
                    codigo_programa: codigo,
                    nombre_programa: nombre,
                    id_nivel: nivel,
                    fecha_creacion: fechaInicio,
                    fecha_fin: fechaFin,
                    cupos: cupos,
                    descripcion: descripcion
                })
                });

                const resultado = await response.json();

                if (resultado.success) {
                    alert(resultado.message);
                    cerrarModal(modalCrear);
                    location.reload();
                } else {
                    alert(resultado.error || 'Error al crear el programa');
                }

            } catch (error) {
                console.error(error);
                alert("Error de conexión al crear el programa");
            }
        });
    }

    // ========== EDITAR PROGRAMA ==========
    // Abrir modal de edición y cargar datos
    document.querySelectorAll(".btn-editar-programa").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Obtener datos desde atributos data-
            const id = this.dataset.id;
            const codigo = this.dataset.codigo;
            const cupos = this.dataset.cupos;
            const nombre = this.dataset.nombre;
            const nivel = this.dataset.nivel;
            const modalidad = this.dataset.modalidad;
            const fechaInicio = this.dataset.fechainicio;
            const fechaFin = this.dataset.fechafin;
            const descripcion = this.dataset.descripcion || '';
            const area = this.dataset.area;

            // Llenar campos del modal de edición
            if (document.getElementById("idProgramaEditar")) {
                document.getElementById("idProgramaEditar").value = id || '';
            }
            if (document.getElementById("areaProgramaEditar")) {
                document.getElementById("areaProgramaEditar").value = area || '';
            }
            if (document.getElementById("codigoProgramaEditar")) {
                document.getElementById("codigoProgramaEditar").value = codigo || '';
            }
            if (document.getElementById("cuposProgramaEditar")) {
                document.getElementById("cuposProgramaEditar").value = cupos || '';
            }
            if (document.getElementById("nombreProgramaEditar")) {
                document.getElementById("nombreProgramaEditar").value = nombre || '';
            }
            if (document.getElementById("nivelFormacionEditar")) {
                document.getElementById("nivelFormacionEditar").value = nivel || '';
            }
            if (document.getElementById("modalidadProgramaEditar")) {
                document.getElementById("modalidadProgramaEditar").value = modalidad || '';
            }
            if (document.getElementById("fechaInicioEditar")) {
                document.getElementById("fechaInicioEditar").value = fechaInicio || '';
            }
            if (document.getElementById("fechaFinEditar")) {
                document.getElementById("fechaFinEditar").value = fechaFin || '';
            }
            if (document.getElementById("descripcionProgramaEditar")) {
                document.getElementById("descripcionProgramaEditar").value = descripcion;
            }

            // Abrir modal
            if (modalEditar) modalEditar.classList.remove("hidden");
        });
    });

    // Guardar cambios de edición
    if (btnGuardarEdicion && modalEditar) {
        btnGuardarEdicion.addEventListener('click', async function(e) {
            e.preventDefault();

            const datos = {
                id_programa: document.getElementById("idProgramaEditar")?.value,
                id_area: document.getElementById("areaProgramaEditar")?.value,
                codigo_programa: document.getElementById("codigoProgramaEditar")?.value,
                nombre_programa: document.getElementById("nombreProgramaEditar")?.value,
                id_nivel: document.getElementById("nivelFormacionEditar")?.value,
                modalidad: document.getElementById("modalidadProgramaEditar")?.value,
                fecha_creacion: document.getElementById("fechaInicioEditar")?.value,
                fecha_fin: document.getElementById("fechaFinEditar")?.value,
                cupos: document.getElementById("cuposProgramaEditar")?.value,
                descripcion: document.getElementById("descripcionProgramaEditar")?.value || ''
            };

            // Validar campos obligatorios
            for (let key in datos) {
                if (!datos[key] && key !== 'descripcion') {
                    alert(`El campo ${key} es obligatorio`);
                    return;
                }
            }

            try {
                const response = await fetch(`${API}ProgramaFormacionController.php?accion=actualizar`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(datos)
                });

                const resultado = await response.json();
                if (resultado.success) {
                    alert(resultado.message);
                    cerrarModal(modalEditar);
                    location.reload(); // O actualizar la card
                } else {
                    alert(resultado.error || 'Error al actualizar');
                }
            } catch (error) {
                console.error(error);
                alert("Error de conexión al actualizar");
            }
        });
    }

    // ========== CERRAR MODALES CON BOTONES ==========
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitado-confirmacion, .cerrar-modal-habilitar, .cerrar-modal-crear, .cerrar-modal-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Cerrar el modal correspondiente (el más cercano)
            const modal = this.closest('.fixed.inset-0.z-50');
            if (modal) cerrarModal(modal);
        });
    });

    // Cerrar modales de confirmación automáticos con el botón "Cerrar"
    document.querySelectorAll('#modal-deshabilitado-perfil .cerrar-modal, #modal-habilitado-confirmacion .cerrar-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            cerrarModalDeshabilitado();
            cerrarModalHabilitado();
        });
    });

});