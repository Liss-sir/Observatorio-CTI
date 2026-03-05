document.addEventListener("DOMContentLoaded", function () {

    // ===== MODALES PARA DESHABILITAR =====
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    
    // ===== MODALES PARA HABILITAR =====
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion'); // <-- CORREGIDO
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar'); // <-- CORREGIDO (era 'modal-habilitado-confirmacion')
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');

    // ===== MODALES PROGRAMA DE ACION ======
    const modalCrear = document.getElementById("modal-crear-programa");
    const btnCrearPrograma = document.getElementById('btn-crear-programa');
    const modalEditar = document.getElementById("modal-editar-programa");

    // ===== TIMERS =====
    let timeoutDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let intervalContadorDeshabilitado = null;

    // Verificar que los modales existen
    console.log('Modal Deshabilitar:', modalDeshabilitar);
    console.log('Modal Habilitar:', modalHabilitar);
    console.log('Modal Deshabilitado:', modalDeshabilitado);
    console.log('Modal Habilitado Confirmación:', modalHabilitadoConfirmacion);
    console.log('Modal Crear:', modalCrear);
    console.log('Botón Crear:', btnCrearPrograma);

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
            modal.removeAttribute('aria-hidden');  // Quita aria-hidden cuando está visible
            document.body.classList.add('overflow-hidden');
            
            // Mueve el foco al primer elemento interactivo dentro del modal
            const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusable) {
                focusable.focus();
            }
            console.log('Modal abierto:', modal.id);
        }
    }

    // ===== FUNCIÓN PARA CERRAR MODAL =====
    function cerrarModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true'); // Vuelve a poner aria-hidden al ocultarlo
            document.body.classList.remove('overflow-hidden');
            console.log('Modal cerrado:', modal.id);
        }
    }
    
    // ===== CERRAR MODALES CON BOTONES =====
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitado-confirmacion, .cerrar-modal-habilitar, .cerrar-modal-crear, .cerrar-modal-editar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            cerrarModal(modalDeshabilitar);
            cerrarModal(modalHabilitar);
            cerrarModal(modalDeshabilitado);
            cerrarModal(modalHabilitadoConfirmacion);
            cerrarModal(modalCrear);
            cerrarModal(modalEditar);
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

    // ===== MANEJAR CLICKS EN SWITCHES =====
    function inicializarSwitches() {
        document.querySelectorAll('.switch-sena').forEach(switchEl => {
            switchEl.removeEventListener('click', handleSwitchClick);
            switchEl.addEventListener('click', handleSwitchClick);
        });
    }

    function handleSwitchClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const switchEl = this;
        
        const cardPerfil = switchEl.closest('.border');
        let nombrePerfil = "Perfil";
        
        if (cardPerfil) {
            const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
            if (nombreElement) {
                nombrePerfil = nombreElement.textContent.trim();
            }
        }
        
        const estaActivo = switchEl.classList.contains('active');
        
        console.log('Switch clickeado - Estado:', estaActivo ? 'ACTIVO' : 'INACTIVO', 'Perfil:', nombrePerfil);
        
        if (estaActivo) {
            if (modalDeshabilitar) {
                const perfilSpan = modalDeshabilitar.querySelector('span.font-medium.text-sena-text-main, span.font-medium');
                if (perfilSpan) {
                    perfilSpan.textContent = `"${nombrePerfil}"`;
                }
                
                if (btnConfirmarDeshabilitar) {
                    btnConfirmarDeshabilitar.setAttribute('data-nombre-perfil', nombrePerfil);
                }
                abrirModal(modalDeshabilitar);
            }
        } else {
            if (modalHabilitar) {
                if (nombrePerfilHabilitarSpan) {
                    nombrePerfilHabilitarSpan.textContent = `"${nombrePerfil}"`;
                } else {
                    const perfilSpanHabilitar = modalHabilitar.querySelector('span.font-medium');
                    if (perfilSpanHabilitar) {
                        perfilSpanHabilitar.textContent = `"${nombrePerfil}"`;
                    }
                }
                
                if (btnConfirmarHabilitar) {
                    btnConfirmarHabilitar.setAttribute('data-nombre-perfil', nombrePerfil);
                }
                abrirModal(modalHabilitar);
            }
        }
    }

    inicializarSwitches();

    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const nombrePerfil = this.getAttribute('data-nombre-perfil') || "Perfil";
            
            console.log('Confirmando deshabilitar - Perfil:', nombrePerfil);
            
            let switchEncontrado = false;
            document.querySelectorAll('.switch-sena').forEach(switchEl => {
                const cardPerfil = switchEl.closest('.border');
                if (cardPerfil) {
                    const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
                    if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                        switchEl.classList.remove('active');
                        switchEl.setAttribute('title', 'Inactivo');
                        switchEl.setAttribute('data-lucide', 'toggle-left');
                        switchEl.classList.remove('text-green-600', 'hover:text-green-700');
                        switchEl.classList.add('text-gray-400', 'hover:text-gray-500');
                        console.log('Switch cambiado a gris para:', nombrePerfil);
                        switchEncontrado = true;
                    }
                }
            });
            
            if (!switchEncontrado) {
                console.log('No se encontró el switch para:', nombrePerfil);
            }
            
            lucide.createIcons();
            cerrarModal(modalDeshabilitar);
            mostrarModalDeshabilitado(nombrePerfil);
        });
    }
    
    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const nombrePerfil = this.getAttribute('data-nombre-perfil') || "Perfil";
            
            console.log('Confirmando habilitar - Perfil:', nombrePerfil);
            
            let switchEncontrado = false;
            document.querySelectorAll('.switch-sena').forEach(switchEl => {
                const cardPerfil = switchEl.closest('.border');
                if (cardPerfil) {
                    const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
                    if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                        switchEl.classList.add('active');
                        switchEl.setAttribute('title', 'Activo');
                        switchEl.setAttribute('data-lucide', 'toggle-right');
                        switchEl.classList.remove('text-gray-400', 'hover:text-gray-500');
                        switchEl.classList.add('text-green-600', 'hover:text-green-700');
                        console.log('Switch cambiado a verde para:', nombrePerfil);
                        switchEncontrado = true;
                    }
                }
            });
            
            if (!switchEncontrado) {
                console.log('No se encontró el switch para:', nombrePerfil);
            }
            
            lucide.createIcons();
            cerrarModal(modalHabilitar);
            mostrarModalHabilitado(nombrePerfil);
        });
    }

    // ===== MODAL EDITAR PROGRAMA =====
    document.querySelectorAll(".btn-editar-programa").forEach(btn => {
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
            
            abrirModal(modalEditar);
        });
    });

    // ===== MODAL CREAR PROGRAMA =====
    if (btnCrearPrograma) {
        btnCrearPrograma.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Click en botón crear programa');
            
            // Limpiar formulario
            if (modalCrear) {
                const inputs = modalCrear.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (input.type !== 'select-one') {
                        input.value = '';
                    } else {
                        input.selectedIndex = 0;
                    }
                });
                abrirModal(modalCrear);
            }
        });
    } else {
        console.error('ERROR: No se encontró el botón con ID "btn-crear-programa"');
    }

    // ===== GUARDAR NUEVO PROGRAMA =====
    const btnGuardarNuevo = document.getElementById('btn-guardar-nuevo-programa');
    if (btnGuardarNuevo) {
        btnGuardarNuevo.addEventListener('click', async () => {
            // Validar campos requeridos
            const campos = {
                area: document.getElementById("areaPrograma")?.value,
                codigo: document.getElementById("codigoNuevoPrograma")?.value,
                cupos: document.getElementById("cuposNuevoPrograma")?.value,
                nombre: document.getElementById("nombreNuevoPrograma")?.value,
                nivel: document.getElementById("nivelNuevoPrograma")?.value,
                modalidad: document.getElementById("modalidadNuevoPrograma")?.value,
                fechaInicio: document.getElementById("fechaInicioNuevoPrograma")?.value,
                fechaFin: document.getElementById("fechaFinNuevoPrograma")?.value
            };
            
            // Verificar campos vacíos
            for (let [key, value] of Object.entries(campos)) {
                if (!value) {
                    alert(`El campo ${key} es requerido`);
                    return;
                }
            }
            
            const datos = {
                id_area: campos.area,
                codigo_programa: campos.codigo,
                nombre_programa: campos.nombre,
                id_nivel: campos.nivel,
                modalidad: campos.modalidad,
                fecha_creacion: campos.fechaInicio,
                fecha_fin: campos.fechaFin,
                cupos: campos.cupos
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
                    cerrarModal(modalCrear);
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
    
    // ===== OBSERVER PARA NUEVOS ELEMENTOS =====
    const observer = new MutationObserver(function() {
        inicializarSwitches();
        lucide.createIcons();
    });
    
    const contenedor = document.getElementById('contenedorProgramas');
    if (contenedor) {
        observer.observe(contenedor, {
            childList: true,
            subtree: true
        });
    }

});