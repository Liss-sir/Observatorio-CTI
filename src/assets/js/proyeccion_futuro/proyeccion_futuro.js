document.addEventListener('DOMContentLoaded', function() {
    // Elementos del modal de creación
    const modalCrear = document.getElementById('modal-crear-proyeccion-futuro');
    const btnAbrir = document.getElementById('btn-crear-proyeccion');
    const botonesCerrarCrear = document.querySelectorAll('.cerrar-modal-crear');
    const formCrear = document.getElementById('form-nueva-proyeccion-futuro');
    const btnSubmitCrear = document.getElementById('btn-submit-proyeccion');
    const btnTextCrear = btnSubmitCrear ? btnSubmitCrear.querySelector('.btn-text') : null;
    const btnLoadingCrear = btnSubmitCrear ? btnSubmitCrear.querySelector('.btn-loading') : null;

    // Elementos del modal de edición
    const modalEditar = document.getElementById('modal-editar-proyeccion-futuro');
    const botonesEditar = document.querySelectorAll('.btn-editar-proyeccion');
    const formEditar = document.getElementById('form-editar-proyeccion-futuro');

    // Elementos del modal de deshabilitar (confirmación)
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-proyeccion-futura');
    const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombreProyeccionDeshabilitarSpan = modalDeshabilitar ? modalDeshabilitar.querySelector('.font-medium.text-sena-text-main') : null;

    // Elementos del modal de confirmación de deshabilitado (éxito)
    const modalConfirmacionDeshabilitado = document.getElementById('modal-deshabilitado-proyeccion-futuro');
    const botonesCerrarConfirmacionDeshabilitado = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const nombreProyeccionDeshabilitadoSpan = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
    const contadorSpanDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
    const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

    // Elementos del modal de habilitar (confirmación)
    const modalHabilitar = document.getElementById('modal-habilitar-tendencia');
    const botonesCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const nombreProyeccionHabilitarSpan = document.getElementById('nombre-proyeccion-habilitar');

    // Elementos del modal de confirmación de habilitado (éxito)
    const modalConfirmacionHabilitado = document.getElementById('modal-habilitado-confirmacion-proyeccion_futura');
    const botonesCerrarConfirmacionHabilitado = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const nombreProyeccionHabilitadoSpan = document.getElementById('nombre-proyeccion-habilitado-exito');
    const contadorSpanHabilitado = document.getElementById('contador-segundos');
    const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

    // Elementos del modal de confirmación de creación
    const modalConfirmacionCrear = document.getElementById('modal-creado-confirmacion-proyeccion-futura');
    const botonesCerrarConfirmacionCrear = document.querySelectorAll('.cerrar-modal-creado');
    const nombreProyeccionSpan = document.getElementById('nombre-proyeccion-creada');
    const contadorSpanCrear = document.getElementById('contador-segundos-creado');
    const progressBarCrear = document.getElementById('progress-bar-creado');

    // Elementos del modal de confirmación de edición
    const modalConfirmacionEditar = document.getElementById('modal-editado-confirmacion');
    const botonesCerrarConfirmacionEditar = document.querySelectorAll('.cerrar-modal-editado');
    const nombreProyeccionEditadoSpan = document.getElementById('nombre-proyeccion-editado');
    const contadorSpanEditar = document.getElementById('contador-segundos-editado');
    const progressBarEditar = document.getElementById('progress-bar-editado');
    
    // Elementos del modal de detalles
    const modalDetalle = document.getElementById('modal-detalle-proyeccion-futuro');
    const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
    const botonesCerrarDetalle = document.querySelectorAll('.cerrar-modal-detalle');
    
    // Elementos del modal de detalles para actualizar contenido
    const detalleTitulo = document.getElementById('detalle-titulo');
    const detalleNombre = document.getElementById('detalle-nombre-proyeccion_futuro');
    const detalleDescripcion = document.getElementById('detalle-descripcion-proyeccion_futuro');
    const detalleEstado = document.getElementById('detalle-estado-proyeccion_futuro');
    const detalleEstadoIndicador = document.getElementById('detalle-estado-indicador');
    const detalleEstadoBadge = document.getElementById('detalle-estado-badge');
    
    // Switches (para deshabilitar/habilitar)
    const switches = document.querySelectorAll('.switch-sena');
    
    // Timeouts e intervals
    let timeoutIdCrear = null;
    let intervalIdCrear = null;
    let timeoutIdEditar = null;
    let intervalIdEditar = null;
    let timeoutIdDeshabilitado = null;
    let intervalIdDeshabilitado = null;
    let timeoutIdHabilitado = null;
    let intervalIdHabilitado = null;

    // Variables para guardar el ID y nombre de la proyección a deshabilitar/habilitar
    let currentDeshabilitarId = null;
    let currentDeshabilitarNombre = null;
    let currentHabilitarId = null;
    let currentHabilitarNombre = null;

    // ===== FUNCIONES PARA MODAL DE CREACIÓN =====
    function abrirModalCrear() {
      modalCrear.classList.remove('hidden');
      // Forzar reflow para que la animación se ejecute
      modalCrear.offsetHeight;
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalCrear() {
      modalCrear.classList.add('hidden');
      document.body.style.overflow = '';
      
      // Resetear el botón si estaba en estado de carga
      if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
        btnSubmitCrear.disabled = false;
        btnTextCrear.classList.remove('hidden');
        btnLoadingCrear.classList.add('hidden');
      }
    }

    // ===== FUNCIONES PARA MODAL DE EDICIÓN =====
    function abrirModalEditar(id) {
      console.log('Editando proyección con ID:', id);
      
      const datosDemo = {
        1: { area: 'Inteligencia Artificial', descripcion: 'Se proyecta que para 2028, la IA generará el 20% de todo el contenido digital y automatizará el 30% de las tareas empresariales.', fecha: '2' },
        2: { area: 'Blockchain', descripcion: 'Se espera que para 2027, el 30% de las transacciones financieras globales utilicen tecnología blockchain.', fecha: '3' },
        3: { area: 'Internet de las Cosas (IoT)', descripcion: 'Proyección indica que para 2028 habrá más de 75 mil millones de dispositivos IoT conectados.', fecha: '4' },
        4: { area: 'Computación en la Nube', descripcion: 'Se proyecta que para 2025, el 85% de las empresas migrarán a entornos de nube híbrida.', fecha: '1' },
        5: { area: 'Ciberseguridad Avanzada', descripcion: 'Para 2026, el gasto global en ciberseguridad alcanzará los $300 mil millones.', fecha: '2' },
        6: { area: 'Big Data y Analítica', descripcion: 'El mercado de big data alcanzará los $400 mil millones para 2027.', fecha: '3' },
        7: { area: 'Realidad Aumentada/Virtual', descripcion: '50 millones de personas trabajarán en entornos de realidad mixta para 2029.', fecha: '5' },
        8: { area: 'Robótica Colaborativa', descripcion: 'Los robots colaborativos representarán el 40% de instalaciones robóticas para 2028.', fecha: '2' },
        9: { area: 'Manufactura Aditiva (Impresión 3D)', descripcion: '60% de productos tendrán componentes impresos en 3D para 2028.', fecha: '4' },
        10: { area: 'Automatización de Procesos (RPA)', descripcion: '90% de tareas repetitivas estarán automatizadas para 2027.', fecha: '3' }
      };
      
      const datos = datosDemo[id] || { area: '', descripcion: '', fecha: '' };
      
      if (formEditar) {
        const selectArea = formEditar.querySelector('select[name="area"]');
        const textareaDesc = formEditar.querySelector('textarea[name="descripcion"]');
        const selectFecha = formEditar.querySelector('select[name="fecha"]');
        
        if (selectArea) selectArea.value = datos.area;
        if (textareaDesc) textareaDesc.value = datos.descripcion;
        if (selectFecha) selectFecha.value = datos.fecha;
      }
      
      modalEditar.classList.remove('hidden');
      // Forzar reflow para que la animación se ejecute
      modalEditar.offsetHeight;
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalEditar() {
      modalEditar.classList.add('hidden');
      document.body.style.overflow = '';
    }

    // ===== FUNCIONES PARA MODAL DE DESHABILITAR (CONFIRMACIÓN) =====
    function abrirModalDeshabilitar(id, nombre) {
      currentDeshabilitarId = id;
      currentDeshabilitarNombre = nombre;
      
      // Actualizar el nombre en el modal
      if (nombreProyeccionDeshabilitarSpan) {
        nombreProyeccionDeshabilitarSpan.textContent = `"${nombre}"`;
      }
      
      modalDeshabilitar.classList.remove('hidden');
      // Forzar reflow para que la animación se ejecute
      modalDeshabilitar.offsetHeight;
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalDeshabilitar() {
      modalDeshabilitar.classList.add('hidden');
      document.body.style.overflow = '';
      currentDeshabilitarId = null;
      currentDeshabilitarNombre = null;
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE DESHABILITADO (ÉXITO) =====
    function cerrarModalConfirmacionDeshabilitado() {
      if (modalConfirmacionDeshabilitado) {
        modalConfirmacionDeshabilitado.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdDeshabilitado) {
          clearTimeout(timeoutIdDeshabilitado);
          timeoutIdDeshabilitado = null;
        }
        if (intervalIdDeshabilitado) {
          clearInterval(intervalIdDeshabilitado);
          intervalIdDeshabilitado = null;
        }
        
        if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
        if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = '3';
      }
    }

    function mostrarModalConfirmacionDeshabilitado(nombreProyeccion) {
      if (nombreProyeccionDeshabilitadoSpan) {
        nombreProyeccionDeshabilitadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionDeshabilitado.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = segundos;
      if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '100%';
      }, 50);
      
      intervalIdDeshabilitado = setInterval(() => {
        segundos--;
        if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdDeshabilitado);
          intervalIdDeshabilitado = null;
        }
      }, 1000);
      
      timeoutIdDeshabilitado = setTimeout(() => {
        cerrarModalConfirmacionDeshabilitado();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE HABILITAR (CONFIRMACIÓN) =====
    function abrirModalHabilitar(id, nombre) {
      currentHabilitarId = id;
      currentHabilitarNombre = nombre;
      
      // Actualizar el nombre en el modal
      if (nombreProyeccionHabilitarSpan) {
        nombreProyeccionHabilitarSpan.textContent = `"${nombre}"`;
      }
      
      modalHabilitar.classList.remove('hidden');
      // Forzar reflow para que la animación se ejecute
      modalHabilitar.offsetHeight;
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalHabilitar() {
      modalHabilitar.classList.add('hidden');
      document.body.style.overflow = '';
      currentHabilitarId = null;
      currentHabilitarNombre = null;
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE HABILITADO (ÉXITO) =====
    function cerrarModalConfirmacionHabilitado() {
      if (modalConfirmacionHabilitado) {
        modalConfirmacionHabilitado.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdHabilitado) {
          clearTimeout(timeoutIdHabilitado);
          timeoutIdHabilitado = null;
        }
        if (intervalIdHabilitado) {
          clearInterval(intervalIdHabilitado);
          intervalIdHabilitado = null;
        }
        
        if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
        if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = '3';
      }
    }

    function mostrarModalConfirmacionHabilitado(nombreProyeccion) {
      if (nombreProyeccionHabilitadoSpan) {
        nombreProyeccionHabilitadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionHabilitado.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = segundos;
      if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarHabilitado) progressBarHabilitado.style.width = '100%';
      }, 50);
      
      intervalIdHabilitado = setInterval(() => {
        segundos--;
        if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdHabilitado);
          intervalIdHabilitado = null;
        }
      }, 1000);
      
      timeoutIdHabilitado = setTimeout(() => {
        cerrarModalConfirmacionHabilitado();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE CREACIÓN =====
    function cerrarModalConfirmacionCrear() {
      if (modalConfirmacionCrear) {
        modalConfirmacionCrear.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdCrear) {
          clearTimeout(timeoutIdCrear);
          timeoutIdCrear = null;
        }
        if (intervalIdCrear) {
          clearInterval(intervalIdCrear);
          intervalIdCrear = null;
        }
        
        if (progressBarCrear) progressBarCrear.style.width = '0%';
        if (contadorSpanCrear) contadorSpanCrear.textContent = '3';
      }
    }

    function mostrarModalConfirmacionCrear(nombreProyeccion) {
      if (nombreProyeccionSpan) {
        nombreProyeccionSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionCrear.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanCrear) contadorSpanCrear.textContent = segundos;
      if (progressBarCrear) progressBarCrear.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarCrear) progressBarCrear.style.width = '100%';
      }, 50);
      
      intervalIdCrear = setInterval(() => {
        segundos--;
        if (contadorSpanCrear) contadorSpanCrear.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdCrear);
          intervalIdCrear = null;
        }
      }, 1000);
      
      timeoutIdCrear = setTimeout(() => {
        cerrarModalConfirmacionCrear();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE EDICIÓN =====
    function cerrarModalConfirmacionEditar() {
      if (modalConfirmacionEditar) {
        modalConfirmacionEditar.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdEditar) {
          clearTimeout(timeoutIdEditar);
          timeoutIdEditar = null;
        }
        if (intervalIdEditar) {
          clearInterval(intervalIdEditar);
          intervalIdEditar = null;
        }
        
        if (progressBarEditar) progressBarEditar.style.width = '0%';
        if (contadorSpanEditar) contadorSpanEditar.textContent = '3';
      }
    }

    function mostrarModalConfirmacionEditar(nombreProyeccion) {
      if (nombreProyeccionEditadoSpan) {
        nombreProyeccionEditadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionEditar.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanEditar) contadorSpanEditar.textContent = segundos;
      if (progressBarEditar) progressBarEditar.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarEditar) progressBarEditar.style.width = '100%';
      }, 50);
      
      intervalIdEditar = setInterval(() => {
        segundos--;
        if (contadorSpanEditar) contadorSpanEditar.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdEditar);
          intervalIdEditar = null;
        }
      }, 1000);
      
      timeoutIdEditar = setTimeout(() => {
        cerrarModalConfirmacionEditar();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE DETALLES =====
    function abrirModalDetalle(id, nombre, descripcion, estado, fecha) {
        // Actualizar el título
        if (detalleTitulo) {
            detalleTitulo.textContent = `Detalle de ${nombre}`;
        }
        
        // Actualizar nombre del área
        if (detalleNombre) {
            detalleNombre.textContent = nombre;
        }
        
        // Actualizar descripción
        if (detalleDescripcion) {
            detalleDescripcion.textContent = descripcion;
        }
        
        // Actualizar estado y estilo del badge
        if (detalleEstado) {
            const estadoTexto = estado === 'activo' ? 'Activo' : 'Inactivo';
            detalleEstado.textContent = estadoTexto;
            
            if (detalleEstadoIndicador) {
                if (estado === 'activo') {
                    detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-[#39A900] mr-2';
                    if (detalleEstadoBadge) {
                        detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena';
                    }
                } else {
                    detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-sena-text-soft mr-2';
                    if (detalleEstadoBadge) {
                        detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-sena-text-soft';
                    }
                }
            }
        }
        
        // Mostrar el modal
        modalDetalle.classList.remove('hidden');
        // Forzar reflow para la animación
        modalDetalle.offsetHeight;
        document.body.style.overflow = 'hidden';
    }

    function cerrarModalDetalle() {
        modalDetalle.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ===== EVENT LISTENERS =====

    // Abrir modal de creación
    if (btnAbrir) {
      btnAbrir.addEventListener('click', abrirModalCrear);
    }

    // Abrir modal de edición
    botonesEditar.forEach(boton => {
      boton.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.getAttribute('data-id');
        abrirModalEditar(id);
      });
    });

    // Evento para las tarjetas (abrir modal de detalles)
    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('click', function(e) {
            // Prevenir que se active si se hace clic en botones específicos
            if (e.target.closest('.btn-editar-proyeccion') || e.target.closest('.switch-sena')) {
                return;
            }
            
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const descripcion = this.getAttribute('data-descripcion');
            const estado = this.getAttribute('data-estado') || 'activo'; // Por defecto activo si no se especifica
            const fecha = this.getAttribute('data-fecha') || 'No especificada';
            
            abrirModalDetalle(id, nombre, descripcion, estado, fecha);
        });
    });

    // Evento para los switches (abrir modal de deshabilitar o habilitar)
    switches.forEach(sw => {
      sw.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Obtener el estado actual (activo/inactivo)
        const isActive = this.classList.contains('active');
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        
        if (isActive) {
          // Si está activo, abrir modal para deshabilitar
          abrirModalDeshabilitar(id, nombre);
        } else {
          // Si está inactivo, abrir modal para habilitar
          abrirModalHabilitar(id, nombre);
        }
      });
    });

    // Confirmar deshabilitar
    if (btnConfirmarDeshabilitar) {
      btnConfirmarDeshabilitar.addEventListener('click', function() {
        console.log('Deshabilitando proyección:', currentDeshabilitarId, currentDeshabilitarNombre);
        
        // Simular deshabilitación
        setTimeout(() => {
          // Cambiar el estado del switch correspondiente
          if (currentDeshabilitarId) {
            const switchElement = document.querySelector(`.switch-sena[data-id="${currentDeshabilitarId}"]`);
            if (switchElement) {
              switchElement.classList.remove('active');
              switchElement.setAttribute('title', 'Inactivo');
            }
          }
          
          // Guardar nombre antes de cerrar
          const nombreProyeccion = currentDeshabilitarNombre;
          
          // Cerrar modal de deshabilitar
          cerrarModalDeshabilitar();
          
          // Mostrar modal de confirmación de deshabilitado (ÉXITO)
          mostrarModalConfirmacionDeshabilitado(nombreProyeccion);
        }, 300);
      });
    }

    // Confirmar habilitar
    if (btnConfirmarHabilitar) {
      btnConfirmarHabilitar.addEventListener('click', function() {
        console.log('Habilitando proyección:', currentHabilitarId, currentHabilitarNombre);
        
        // Simular habilitación
        setTimeout(() => {
          // Cambiar el estado del switch correspondiente
          if (currentHabilitarId) {
            const switchElement = document.querySelector(`.switch-sena[data-id="${currentHabilitarId}"]`);
            if (switchElement) {
              switchElement.classList.add('active');
              switchElement.setAttribute('title', 'Activo');
            }
          }
          
          // Guardar nombre antes de cerrar
          const nombreProyeccion = currentHabilitarNombre;
          
          // Cerrar modal de habilitar
          cerrarModalHabilitar();
          
          // Mostrar modal de confirmación de habilitado (ÉXITO)
          mostrarModalConfirmacionHabilitado(nombreProyeccion);
        }, 300);
      });
    }

    // Cerrar modales con los botones de cerrar
    botonesCerrarCrear.forEach(boton => {
      boton.addEventListener('click', function(e) {
        if (modalCrear && !modalCrear.classList.contains('hidden')) {
          cerrarModalCrear();
        }
        if (modalEditar && !modalEditar.classList.contains('hidden')) {
          cerrarModalEditar();
        }
      });
    });

    // Cerrar modal de deshabilitar
    botonesCerrarDeshabilitar.forEach(boton => {
      boton.addEventListener('click', cerrarModalDeshabilitar);
    });

    // Cerrar modal de habilitar
    botonesCerrarHabilitar.forEach(boton => {
      boton.addEventListener('click', cerrarModalHabilitar);
    });

    // Cerrar modal de confirmación de deshabilitado
    botonesCerrarConfirmacionDeshabilitado.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionDeshabilitado);
    });

    // Cerrar modal de confirmación de habilitado
    botonesCerrarConfirmacionHabilitado.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionHabilitado);
    });

    // Cerrar modal de confirmación de creación
    botonesCerrarConfirmacionCrear.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionCrear);
    });

    // Cerrar modal de confirmación de edición
    botonesCerrarConfirmacionEditar.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionEditar);
    });

    // Cerrar modal de detalles con los botones de cerrar
    botonesCerrarDetalle.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.stopPropagation();
            cerrarModalDetalle();
        });
    });

    // Cerrar modales al hacer clic en el overlay
    if (modalCrear) {
      modalCrear.addEventListener('click', function(e) {
        if (e.target === modalCrear || e.target.classList.contains('bg-black')) {
          cerrarModalCrear();
        }
      });
    }

    if (modalEditar) {
      modalEditar.addEventListener('click', function(e) {
        if (e.target === modalEditar || e.target.classList.contains('bg-black')) {
          cerrarModalEditar();
        }
      });
    }

    if (modalDeshabilitar) {
      modalDeshabilitar.addEventListener('click', function(e) {
        if (e.target === modalDeshabilitar || e.target.classList.contains('bg-black')) {
          cerrarModalDeshabilitar();
        }
      });
    }

    if (modalHabilitar) {
      modalHabilitar.addEventListener('click', function(e) {
        if (e.target === modalHabilitar || e.target.classList.contains('bg-black')) {
          cerrarModalHabilitar();
        }
      });
    }

    if (modalConfirmacionDeshabilitado) {
      modalConfirmacionDeshabilitado.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionDeshabilitado || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionDeshabilitado();
        }
      });
    }

    if (modalConfirmacionHabilitado) {
      modalConfirmacionHabilitado.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionHabilitado || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionHabilitado();
        }
      });
    }

    if (modalConfirmacionCrear) {
      modalConfirmacionCrear.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionCrear || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionCrear();
        }
      });
    }

    if (modalConfirmacionEditar) {
      modalConfirmacionEditar.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionEditar || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionEditar();
        }
      });
    }

    if (modalDetalle) {
        modalDetalle.addEventListener('click', function(e) {
            if (e.target === modalDetalle || e.target.classList.contains('bg-black') || e.target.classList.contains('fixed')) {
                cerrarModalDetalle();
            }
        });
    }

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (modalCrear && !modalCrear.classList.contains('hidden')) {
          cerrarModalCrear();
        }
        if (modalEditar && !modalEditar.classList.contains('hidden')) {
          cerrarModalEditar();
        }
        if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
          cerrarModalDeshabilitar();
        }
        if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
          cerrarModalHabilitar();
        }
        if (modalConfirmacionDeshabilitado && !modalConfirmacionDeshabilitado.classList.contains('hidden')) {
          cerrarModalConfirmacionDeshabilitado();
        }
        if (modalConfirmacionHabilitado && !modalConfirmacionHabilitado.classList.contains('hidden')) {
          cerrarModalConfirmacionHabilitado();
        }
        if (modalConfirmacionCrear && !modalConfirmacionCrear.classList.contains('hidden')) {
          cerrarModalConfirmacionCrear();
        }
        if (modalConfirmacionEditar && !modalConfirmacionEditar.classList.contains('hidden')) {
          cerrarModalConfirmacionEditar();
        }
        if (modalDetalle && !modalDetalle.classList.contains('hidden')) {
          cerrarModalDetalle();
        }
      }
    });

    // Manejar envío del formulario de creación
    if (formCrear) {
      formCrear.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
          btnSubmitCrear.disabled = true;
          btnTextCrear.classList.add('hidden');
          btnLoadingCrear.classList.remove('hidden');
        }
        
        const formData = new FormData(formCrear);
        const datos = Object.fromEntries(formData.entries());
        const nombreProyeccion = datos.area || 'Nueva Proyección';
        
        setTimeout(() => {
          cerrarModalCrear();
          formCrear.reset();
          mostrarModalConfirmacionCrear(nombreProyeccion);
          
          if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
            btnSubmitCrear.disabled = false;
            btnTextCrear.classList.remove('hidden');
            btnLoadingCrear.classList.add('hidden');
          }
        }, 300);
      });
    }

    // Manejar envío del formulario de edición
    if (formEditar) {
      formEditar.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(formEditar);
        const datos = Object.fromEntries(formData.entries());
        const nombreProyeccion = datos.area || 'Proyección';
        
        setTimeout(() => {
          cerrarModalEditar();
          mostrarModalConfirmacionEditar(nombreProyeccion);
        }, 300);
      });
    }
});
