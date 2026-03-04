document.addEventListener("DOMContentLoaded", function () {

    // ===== MODALES PARA DESHABILITAR =====
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    
    // ===== MODALES PARA HABILITAR =====
    const modalHabilitar = document.getElementById('modal-habilitar-perfil');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');

    // ===== TIMERS =====
    let timeoutDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let intervalContadorDeshabilitado = null;

    // Verificar que los modales existen
    console.log('Modal Deshabilitar:', modalDeshabilitar);
    console.log('Modal Habilitar:', modalHabilitar);
    console.log('Modal Deshabilitado:', modalDeshabilitado);
    console.log('Modal Habilitado:', modalHabilitadoConfirmacion);

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
    document.querySelectorAll('.cerrar-modal-deshabilitar, .cerrar-modal-habilitado-confirmacion').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            cerrarModal(modalDeshabilitar);
            cerrarModal(modalHabilitar);
            cerrarModal(modalDeshabilitado);
            cerrarModal(modalHabilitadoConfirmacion);
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
        let segundos = 3;
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
        }, 3000);
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
            // Remover eventos anteriores
            switchEl.removeEventListener('click', handleSwitchClick);
            // Agregar nuevo evento
            switchEl.addEventListener('click', handleSwitchClick);
        });
    }

    function handleSwitchClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const switchEl = this;
        
        // Obtener el nombre del perfil
        const cardPerfil = switchEl.closest('.border');
        let nombrePerfil = "Perfil";
        
        if (cardPerfil) {
            const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
            if (nombreElement) {
                nombrePerfil = nombreElement.textContent.trim();
            }
        }
        
        // Verificar el estado ACTUAL del switch
        const estaActivo = switchEl.classList.contains('active');
        
        console.log('Switch clickeado - Estado:', estaActivo ? 'ACTIVO' : 'INACTIVO', 'Perfil:', nombrePerfil);
        
        if (estaActivo) {
            // Switch ACTIVO → DESHABILITAR
            if (modalDeshabilitar) {
                // Actualizar el texto del perfil en el modal
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
            // Switch INACTIVO → HABILITAR
            if (modalHabilitar) {
                // Actualizar el texto del perfil en el modal de habilitar
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

    // Inicializar switches
    inicializarSwitches();

    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const nombrePerfil = this.getAttribute('data-nombre-perfil') || "Perfil";
            
            console.log('Confirmando deshabilitar - Perfil:', nombrePerfil);
            
            // Cambiar el estado del switch
            let switchEncontrado = false;
            document.querySelectorAll('.switch-sena').forEach(switchEl => {
                const cardPerfil = switchEl.closest('.border');
                if (cardPerfil) {
                    const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
                    if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                        // Cambiar a inactivo
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
            
            // Reinicializar iconos de Lucide
            lucide.createIcons();
            
            // Cerrar modal de confirmación
            cerrarModal(modalDeshabilitar);
            
            // Mostrar modal de éxito
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
            
            // Cambiar el estado del switch
            let switchEncontrado = false;
            document.querySelectorAll('.switch-sena').forEach(switchEl => {
                const cardPerfil = switchEl.closest('.border');
                if (cardPerfil) {
                    const nombreElement = cardPerfil.querySelector('.font-semibold.text-gray-800');
                    if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                        // Cambiar a activo
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
            
            // Reinicializar iconos de Lucide
            lucide.createIcons();
            
            // Cerrar modal de confirmación
            cerrarModal(modalHabilitar);
            
            // Mostrar modal de éxito
            mostrarModalHabilitado(nombrePerfil);
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