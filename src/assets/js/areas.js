document.addEventListener('DOMContentLoaded', function() {
    // ===== VARIABLES DE MODALES (adaptadas a áreas) =====
    const modalEditar = document.getElementById('modal-editar-area');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-area');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-area');
    const modalDeshabilitadoConfirmacion = document.getElementById('modal-deshabilitado-area');
    const modalHabilitar = document.getElementById('modal-habilitar-area');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-area');
    const modalCrear = document.getElementById('modal-crear-area');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-area');
    const modalDetalle = document.getElementById('modal-detalle-area');
    
    const cerrarModalBtns = document.querySelectorAll('.cerrar-modal-editar');
    const cerrarModalEditadoBtns = document.querySelectorAll('.cerrar-modal-editado');
    const cerrarModalDeshabilitarBtns = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const cerrarModalDeshabilitadoBtns = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const cerrarModalHabilitarBtns = document.querySelectorAll('.cerrar-modal-habilitar');
    const cerrarModalHabilitadoBtns = document.querySelectorAll('.cerrar-modal-habilitado');
    const cerrarModalCrearBtns = document.querySelectorAll('.cerrar-modal-crear');
    const cerrarModalCreadoBtns = document.querySelectorAll('.cerrar-modal-creado');
    const cerrarModalDetalleBtns = document.querySelectorAll('.cerrar-modal-detalle');
    
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnCrearArea = document.getElementById('btn-crear-area');

    // Variables para contadores y progreso
    let timeoutEditado = null;
    let intervalContadorEditado = null;
    let timeoutDeshabilitado = null;
    let intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let timeoutCreado = null;
    let intervalContadorCreado = null;
    
    // ===== FUNCIÓN PARA ABRIR MODAL =====
    function abrirModal(modal) {
        if (modal) {
            modal.classList.remove('hidden');
            modal.offsetHeight; // Forzar reflow
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
    
    // ===== FUNCIÓN PARA CERRAR MODAL EDITADO =====
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
    
    // ===== FUNCIÓN PARA CERRAR MODAL DESHABILITADO =====
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
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-habilitado');
        if (contador) contador.textContent = '3';
    }

    // ===== FUNCIÓN PARA CERRAR MODAL CREADO =====
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
    
    // ===== MOSTRAR MODAL EDITADO =====
    function mostrarModalEditado(nombreArea) {
        console.log('Mostrando modal editado para:', nombreArea);
        if (!modalEditadoConfirmacion) return;
        
        const nombreSpan = document.getElementById('nombre-area-editado');
        if (nombreSpan) nombreSpan.textContent = `"${nombreArea}"`;
        
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
        timeoutEditado = setTimeout(() => cerrarModalEditado(), 3000);
    }
    
    // ===== MOSTRAR MODAL DESHABILITADO =====
    function mostrarModalDeshabilitado(nombreArea) {
        console.log('Mostrando modal deshabilitado para:', nombreArea);
        if (!modalDeshabilitadoConfirmacion) return;
        
        const nombreSpan = document.getElementById('nombre-area-deshabilitado');
        if (nombreSpan) nombreSpan.textContent = `"${nombreArea}"`;
        
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
        timeoutDeshabilitado = setTimeout(() => cerrarModalDeshabilitado(), 3000);
    }

    // ===== MOSTRAR MODAL HABILITADO =====
    function mostrarModalHabilitado(nombreArea) {
        console.log('Mostrando modal habilitado para:', nombreArea);
        if (!modalHabilitadoConfirmacion) return;
        
        const nombreSpan = document.getElementById('nombre-area-habilitado');
        if (nombreSpan) nombreSpan.textContent = `"${nombreArea}"`;
        
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
        timeoutHabilitado = setTimeout(() => cerrarModalHabilitado(), 3000);
    }

    // ===== MOSTRAR MODAL CREADO =====
    function mostrarModalCreado(nombreArea) {
        console.log('Mostrando modal creado para:', nombreArea);
        if (!modalCreadoConfirmacion) return;
        
        const nombreSpan = document.getElementById('nombre-area-creado');
        if (nombreSpan) nombreSpan.textContent = `"${nombreArea}"`;
        
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
        timeoutCreado = setTimeout(() => cerrarModalCreado(), 3000);
    }
    
    // ===== EVENTO PARA ABRIR MODAL CREAR =====
    if (btnCrearArea) {
        btnCrearArea.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('form-nueva-area');
            if (form) form.reset();
            abrirModal(modalCrear);
        });
    }
    
    // ===== EVENTOS PARA BOTONES DE EDITAR (con clase .btn-editar-area) =====
    document.querySelectorAll('.btn-editar-area').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.border');
            const nombreArea = card.querySelector('h3').textContent;
            const descripcionArea = card.querySelector('p.text-sm').textContent; // Cambiado de .text-xs a .text-sm
            
            const switchEl = card.querySelector('.switch-sena');
            const estadoArea = switchEl.classList.contains('active') ? 'activo' : 'inactivo';
            
            const nombreInput = document.getElementById('nombre-area');
            const descripcionInput = document.getElementById('descripcion-area');
            const estadoSelect = document.getElementById('estado-area');
            
            if (nombreInput) nombreInput.value = nombreArea;
            if (descripcionInput) descripcionInput.value = descripcionArea;
            if (estadoSelect) estadoSelect.value = estadoArea;
            
            if (modalEditar) {
                modalEditar.setAttribute('data-card-id', card.getAttribute('data-id'));
            }
            
            abrirModal(modalEditar);
        });
    });
    
    // ===== EVENTOS PARA SWITCHES =====
    document.querySelectorAll('.switch-sena').forEach(switchEl => {
        switchEl.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.border');
            const nombreArea = card.querySelector('h3').textContent;
            
            if (this.classList.contains('active')) {
                // Deshabilitar
                const spanNombre = modalDeshabilitar.querySelector('span.font-medium');
                if (spanNombre) spanNombre.textContent = `"${nombreArea}"`;
                modalDeshabilitar.setAttribute('data-switch-id', card.getAttribute('data-id'));
                abrirModal(modalDeshabilitar);
            } else {
                // Habilitar
                const spanNombre = modalHabilitar.querySelector('span.font-semibold');
                if (spanNombre) spanNombre.textContent = `"${nombreArea}"`;
                modalHabilitar.setAttribute('data-switch-id', card.getAttribute('data-id'));
                modalHabilitar.setAttribute('data-nombre-area', nombreArea);
                abrirModal(modalHabilitar);
            }
        });
    });
    
    // ===== EVENTO PARA ABRIR MODAL DETALLE AL HACER CLICK EN LA TARJETA =====
    document.querySelectorAll('.border[data-id]').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.btn-editar-area') || e.target.closest('.switch-sena')) return;
            
            const nombreArea = this.querySelector('h3').textContent;
            const descripcionArea = this.querySelector('p.text-sm').textContent;
            const switchEl = this.querySelector('.switch-sena');
            const estadoArea = switchEl.classList.contains('active') ? 'Activo' : 'Inactivo';
            const colorEstado = switchEl.classList.contains('active') ? '#39A900' : '#9ca3af';
            
            document.getElementById('detalle-titulo').textContent = `Detalle de: ${nombreArea}`;
            document.getElementById('detalle-nombre').textContent = nombreArea;
            document.getElementById('detalle-descripcion').textContent = descripcionArea;
            document.getElementById('detalle-estado').textContent = estadoArea;
            document.getElementById('detalle-estado-indicador').style.backgroundColor = colorEstado;
            
            abrirModal(modalDetalle);
        });
    });
    
    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function(e) {
            e.preventDefault();
            
            const switchId = modalDeshabilitar.getAttribute('data-switch-id');
            const nombreArea = modalDeshabilitar.querySelector('span.font-medium').textContent.replace(/"/g, '');
            
            document.querySelectorAll('.border').forEach(card => {
                if (card.getAttribute('data-id') === switchId) {
                    const switchEl = card.querySelector('.switch-sena');
                    switchEl.classList.remove('active');
                    switchEl.setAttribute('title', 'Inactivo');
                }
            });
            
            cerrarModal(modalDeshabilitar);
            mostrarModalDeshabilitado(nombreArea);
        });
    }
    
    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', function(e) {
            e.preventDefault();
            
            const switchId = modalHabilitar.getAttribute('data-switch-id');
            const nombreArea = modalHabilitar.getAttribute('data-nombre-area');
            
            document.querySelectorAll('.border').forEach(card => {
                if (card.getAttribute('data-id') === switchId) {
                    const switchEl = card.querySelector('.switch-sena');
                    switchEl.classList.add('active');
                    switchEl.setAttribute('title', 'Activo');
                }
            });
            
            cerrarModal(modalHabilitar);
            mostrarModalHabilitado(nombreArea);
        });
    }
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE CREAR =====
    const formCrear = document.getElementById('form-nueva-area');
    if (formCrear) {
        formCrear.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const area = document.querySelector('input[name="area"]').value;
            const descripcion = document.querySelector('textarea[name="descripcion"]').value;
            
            console.log('Nueva área creada:', { area, descripcion });
            
            cerrarModal(modalCrear);
            mostrarModalCreado(area);
        });
    }
    
    // ===== CERRAR MODAL DE EDICIÓN =====
    cerrarModalBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalEditar));
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN EDITADO =====
    cerrarModalEditadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalEditado);
    });
    
    // ===== CERRAR MODAL DE DESHABILITAR =====
    cerrarModalDeshabilitarBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalDeshabilitar));
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN DESHABILITADO =====
    cerrarModalDeshabilitadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalDeshabilitado);
    });
    
    // ===== CERRAR MODAL DE HABILITAR =====
    cerrarModalHabilitarBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalHabilitar));
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN HABILITADO =====
    cerrarModalHabilitadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalHabilitado);
    });
    
    // ===== CERRAR MODAL DE CREAR =====
    cerrarModalCrearBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalCrear));
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN CREADO =====
    cerrarModalCreadoBtns.forEach(btn => {
        btn.addEventListener('click', cerrarModalCreado);
    });
    
    // ===== CERRAR MODAL DE DETALLE =====
    cerrarModalDetalleBtns.forEach(btn => {
        btn.addEventListener('click', () => cerrarModal(modalDetalle));
    });
    
    // ===== CERRAR MODALES HACIENDO CLICK EN OVERLAY =====
    [modalEditar, modalEditadoConfirmacion, modalDeshabilitar, modalDeshabilitadoConfirmacion, modalHabilitar, modalHabilitadoConfirmacion, modalCrear, modalCreadoConfirmacion, modalDetalle].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('fixed')) {
                    if (modal === modalEditadoConfirmacion) cerrarModalEditado();
                    else if (modal === modalDeshabilitadoConfirmacion) cerrarModalDeshabilitado();
                    else if (modal === modalHabilitadoConfirmacion) cerrarModalHabilitado();
                    else if (modal === modalCreadoConfirmacion) cerrarModalCreado();
                    else cerrarModal(modal);
                }
            });
        }
    });
    
    // ===== CERRAR CON TECLA ESC =====
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
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE EDICIÓN =====
    const formEditar = document.getElementById('form-editar-area');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombreArea = document.getElementById('nombre-area').value;
            console.log('Área actualizada:', nombreArea);
            
            cerrarModal(modalEditar);
            mostrarModalEditado(nombreArea);
        });
    }
});