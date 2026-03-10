document.addEventListener("DOMContentLoaded", function () {

    // ===== MODALES PARA DESHABILITAR =====
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    
    // ===== MODALES PARA HABILITAR =====
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');   
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');

    // ===== MODALES PROGRAMA DE FORMACION ======
    const btnCrearPrograma = document.getElementById("btn-abrir-crear-programa");
    const modalCrear = document.getElementById("modal-crear-programa");

    // ===== TIMERS =====
    let timeoutDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let intervalContadorDeshabilitado = null;

    // Verificar que los modales existen
    console.log('Modal Deshabilitar:', modalDeshabilitar);
    console.log('Modal Habilitar:', modalHabilitar);
    console.log('Modal Deshabilitado:', modalDeshabilitado);
    console.log('Modal Habilitado:', btnConfirmarHabilitar);

    const buscador = document.getElementById("buscador");
    const cards = document.querySelectorAll(".programa-card");

    if (buscador) {
        buscador.addEventListener("keyup", function () {
            const texto = this.value.toLowerCase();
            cards.forEach(card => {
                const nombre = card.dataset.nombre;
                const codigo = card.dataset.codigo;
                if (nombre.includes(texto) || codigo.includes(texto)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    // ===== FUNCIÓN PARA ABRIR MODAL =====
    function abrirModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    // ===== FUNCIÓN PARA CERRAR MODAL =====
    function cerrarModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }
    
    // ===== CERRAR MODALES CON BOTONES =====
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitado-confirmacion,.cerrar-modal-habilitar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            cerrarModal(modalDeshabilitar);
            cerrarModal(modalHabilitar);
            cerrarModal(modalDeshabilitado);
            cerrarModal(btnConfirmarHabilitar);
        });
    });

    // ===== FUNCIÓN PARA CERRAR MODAL DESHABILITADO =====
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
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) {
            contador.textContent = '3';
        }
    }

    // ===== FUNCIÓN PARA CERRAR MODAL HABILITADO =====
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
        const contador = document.getElementById('contador-segundos');
        if (contador) {
            contador.textContent = '3';
        }
    }

    // ===== FUNCIÓN PARA MOSTRAR MODAL DESHABILITADO =====
    function mostrarModalDeshabilitado(nombrePerfil) {
        console.log('Mostrando modal deshabilitado para:', nombrePerfil);
        
        if (!modalDeshabilitado) {
            console.error('Modal deshabilitado no encontrado');
            return;
        }
        
        if (nombrePerfilDeshabilitadoSpan) {
            nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalDeshabilitado);
        
        // Resetear contador
        let segundos = 6;
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) {
            contador.textContent = segundos;
        }
        
        // Iniciar contador regresivo
        if (intervalContadorDeshabilitado) {
            clearInterval(intervalContadorDeshabilitado);
        }
        
        intervalContadorDeshabilitado = setInterval(() => {
            segundos--;
            if (contador) {
                contador.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervalContadorDeshabilitado);
                intervalContadorDeshabilitado = null;
            }
        }, 1000);
        
        // Animar barra de progreso
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 50);
        }
        
        if (timeoutDeshabilitado) {
            clearTimeout(timeoutDeshabilitado);
        }
        
        timeoutDeshabilitado = setTimeout(() => {
            cerrarModalDeshabilitado();
        }, 6000);
    }

    // ===== FUNCIÓN PARA MOSTRAR MODAL HABILITADO =====
    function mostrarModalHabilitado(nombrePerfil) {
        console.log('Mostrando modal habilitado para:', nombrePerfil);
        
        if (!modalHabilitadoConfirmacion) {
            console.error('Modal habilitado no encontrado');
            return;
        }
        
        if (nombrePerfilHabilitadoExitoSpan) {
            nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalHabilitadoConfirmacion);
        
        // Resetear contador
        let segundos = 6;
        const contador = document.getElementById('contador-segundos');
        if (contador) {
            contador.textContent = segundos;
        }
        
        // Iniciar contador regresivo
        if (intervalContadorHabilitado) {
            clearInterval(intervalContadorHabilitado);
        }
        
        intervalContadorHabilitado = setInterval(() => {
            segundos--;
            if (contador) {
                contador.textContent = segundos;
            }
            if (segundos <= 0) {
                clearInterval(intervalContadorHabilitado);
                intervalContadorHabilitado = null;
            }
        }, 1000);
        
        // Animar barra de progreso
        const progressBar = document.getElementById('progress-bar-habilitado');
        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 50);
        }
        
        if (timeoutHabilitado) {
            clearTimeout(timeoutHabilitado);
        }
        
        timeoutHabilitado = setTimeout(() => {
            cerrarModalHabilitado();
        }, 6000);
    }

    // ===== EVENTOS PARA SWITCHES =====
    document.querySelectorAll('.switch-sena').forEach(switchEl => {
        switchEl.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const card = this.closest('.border');
            const nombrePrograma = card.querySelector('.font-semibold').textContent;

            // GUARDAR EL ID DEL CARD
            const id = card.getAttribute('data-id');

            if (this.classList.contains('active')) {

                const spanNombre = modalDeshabilitar.querySelector('span.font-medium');
                if (spanNombre) spanNombre.textContent = `"${nombrePrograma}"`;

                modalDeshabilitar.setAttribute('data-switch-id', id);
                abrirModal(modalDeshabilitar);

            } else {

                const spanNombre = modalHabilitar.querySelector('span.font-semibold');
                if (spanNombre) spanNombre.textContent = `"${nombrePrograma}"`;

                modalHabilitar.setAttribute('data-switch-id', id);
                modalHabilitar.setAttribute('data-nombre-programa', nombrePrograma);

                abrirModal(modalHabilitar);
            }
        });
    });

    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function(e) {
        e.preventDefault();

        const switchId = modalDeshabilitar.getAttribute('data-switch-id');

        document.querySelectorAll('.border').forEach(card => {
            if (card.getAttribute('data-id') === switchId) {

                const switchEl = card.querySelector('.switch-sena');

                if (switchEl) {
                    switchEl.classList.remove('active');
                    switchEl.setAttribute('title', 'Inactivo');
                }
            }
        });

        cerrarModal(modalDeshabilitar);
    });
    }

    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', function(e) {
            e.preventDefault();

            const switchId = modalHabilitar.getAttribute('data-switch-id');

            document.querySelectorAll('.border').forEach(card => {
                if (card.getAttribute('data-id') === switchId) {

                    const switchEl = card.querySelector('.switch-sena');

                    if (switchEl) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                    }
                }
            });

            cerrarModal(modalHabilitar);
        });
    }

    //modal editar programa de formacion
    document.querySelectorAll(".btn-editar-programa").forEach(btn => {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Obtener los datos del programa desde los atributos data-
            const codigo = this.dataset.codigo;
            const cupos = this.dataset.cupos;
            const nombre = this.dataset.nombre;
            const nivel = this.dataset.nivel;
            const modalidad = this.dataset.modalidad;
            const fechaInicio = this.dataset.fechainicio;
            const fechaFin = this.dataset.fechafin;
            
            // Llenar el formulario del modal
            document.getElementById("codigoPrograma").value = codigo || '';
            document.getElementById("cuposPrograma").value = cupos || '';
            document.getElementById("nombrePrograma").value = nombre || '';
            document.getElementById("nivelFormacion").value = nivel || '';
            document.getElementById("modalidadPrograma").value = modalidad || '';
            document.getElementById("fechaInicio").value = fechaInicio || '';
            document.getElementById("fechaFin").value = fechaFin || '';
            
            // Abrir el modal
            document.getElementById("modal-editar-programa").classList.remove("hidden");
        });
    });

    //funcion para cerrar el modal
    function cerrarModalEditarPrograma() {
    document
        .getElementById("modal-editar-programa")
        .classList.add("hidden");
    }

    document.querySelectorAll(".cerrar-modal-editar").forEach(btn => {
        btn.addEventListener("click", cerrarModalEditarPrograma);
    });

    /* CERRAR MODAL */
    document.querySelectorAll(".cerrar-modal-crear").forEach(btn=>{
        btn.addEventListener("click",()=>{
            modalCrear.classList.add("hidden");
        });
    });

    // CREAR PROGRAMA DE FORMACION
    if (btnCrearPrograma) {
        btnCrearPrograma.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (modalCrear) {
                modalCrear.classList.remove('hidden');
            }
        });
    }

    // Cerrar modal crear
    document.querySelectorAll('.cerrar-modal-crear').forEach(btn => {
        btn.addEventListener('click', () => {
            if (modalCrear) {
                modalCrear.classList.add('hidden');
            }
        });
    });

    // Guardar nuevo programa
    const btnGuardarNuevo = document.getElementById('btn-guardar-nuevo-programa');
    if (btnGuardarNuevo) {
        btnGuardarNuevo.addEventListener('click', async () => {
            const datos = {
                id_area: document.getElementById("areaPrograma").value,
                codigo_programa: document.getElementById("codigoNuevoPrograma").value,
                nombre_programa: document.getElementById("nombreNuevoPrograma").value,
                id_nivel: document.getElementById("nivelNuevoPrograma").value,
                modalidad: document.getElementById("modalidadNuevoPrograma").value,
                fecha_creacion: document.getElementById("fechaInicioNuevoPrograma").value,
                fecha_fin: document.getElementById("fechaFinNuevoPrograma").value,
                cupos: document.getElementById("cuposNuevoPrograma").value // Agregué cupos que faltaba
            };

            try {
                const response = await fetch("/src/controllers/ProgramaFormacionController.php?accion=crear", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datos)
                });

                const resultado = await response.json();
                if (resultado.success) {
                    alert(resultado.message);
                    modalCrear.classList.add("hidden");
                    location.reload();
                } else {
                    alert(resultado.error);
                }
            } catch (error) {
                console.error(error);
                alert("Error al crear el programa");
            }
        });
    }
    
    // Re-inicializar switches cuando se cambie el DOM (por si se agregan nuevos)
    const observer = new MutationObserver(function() {
        inicializarSwitches();
    });
    
    observer.observe(document.getElementById('contenedorProgramas'), {
        childList: true,
        subtree: true
    });

});