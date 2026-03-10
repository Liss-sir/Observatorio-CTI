document.addEventListener('DOMContentLoaded', function() {
      // Modales
      const modalCrear = document.getElementById('modal-crear-tecnologia-emergente');
      const modalConfirmacionCrear = document.getElementById('modal-creado-confirmacion-tecnologia-emergente');
      const modalEditar = document.getElementById('modal-editar-tecnologia-emergente');
      const modalConfirmacionEditar = document.getElementById('modal-editado-confirmacion-tecnologia-emergente');
      const modalDetalle = document.getElementById('modal-detalle-tecnologia-emergente');
      const modalDeshabilitar = document.getElementById('modal-deshabilitar-tecnologia-emergente');
      const modalConfirmacionDeshabilitar = document.getElementById('modal-deshabilitado-tecnologia-emergente');
      const modalHabilitar = document.getElementById('modal-habilitar-tendencia');
      const modalConfirmacionHabilitar = document.getElementById('modal-habilitado-confirmacion-tecnologia');
      
      // Elementos del modal de creación
      const btnAbrirModalCrear = document.getElementById('btn-crear-tendencia');
      const botonesCerrarCrear = document.querySelectorAll('.cerrar-modal-crear');
      const formCrear = document.getElementById('form-nueva-tecnologia-emergente');
      
      // Elementos del modal de edición
      const botonesEditar = document.querySelectorAll('.btn-editar-tendencia');
      const botonesCerrarEditar = document.querySelectorAll('.cerrar-modal-editar');
      const formEditar = document.getElementById('form-editar-tecnologia-emergente');
      const selectNombre = document.getElementById('nombre-tecnologia-emergente');
      const textareaDescripcion = document.getElementById('descripcion-tendencia');
      
      // Elementos del modal de confirmación de creación
      const botonesCerrarConfirmacionCrear = document.querySelectorAll('.cerrar-modal-creado');
      const nombreTecnologiaSpan = document.getElementById('nombre-tecnologia-creada');
      const contadorSegundosCrear = document.getElementById('contador-segundos-creado');
      const progressBarCrear = document.getElementById('progress-bar-creado');

      // Elementos del modal de confirmación de edición
      const botonesCerrarConfirmacionEditar = document.querySelectorAll('.cerrar-modal-editado');
      const nombreTecnologiaEditadaSpan = document.getElementById('nombre-tecnologia-editada');
      const contadorSegundosEditar = document.getElementById('contador-segundos-editado');
      const progressBarEditar = document.getElementById('progress-bar-editado');

      // Elementos del modal de detalle
      const tarjetasTecnologia = document.querySelectorAll('.tarjeta-tecnologia');
      const botonesCerrarDetalle = document.querySelectorAll('.cerrar-modal-detalle');
      const detalleTitulo = document.getElementById('detalle-titulo');
      const detalleNombre = document.getElementById('detalle-nombre-tecnologia');
      const detalleDescripcion = document.getElementById('detalle-descripcion-tecnologia');
      const detalleEstadoTexto = document.getElementById('detalle-estado-tecnologia');
      const detalleEstadoBadge = document.getElementById('detalle-estado-badge');
      const detalleEstadoIndicador = document.getElementById('detalle-estado-indicador');

      // Elementos del modal de deshabilitar
      const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
      const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
      const spanTecnologiaDeshabilitar = document.querySelector('#modal-deshabilitar-tecnologia-emergente .font-medium.text-sena-text-main');

      // Elementos del modal de confirmación de deshabilitar
      const botonesCerrarConfirmacionDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitado');
      const nombreTecnologiaDeshabilitadaSpan = document.getElementById('nombre-tecnologia-emergente-deshabilitada');
      const contadorSegundosDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
      const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

      // Elementos del modal de habilitar
      const botonesCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
      const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
      const spanTecnologiaHabilitar = document.getElementById('nombre-tecnologia-habilitar');

      // Elementos del modal de confirmación de habilitar
      const botonesCerrarConfirmacionHabilitar = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
      const nombreTecnologiaHabilitadaSpan = document.getElementById('nombre-tecnología-habilitado-exito');
      const contadorSegundosHabilitado = document.getElementById('contador-segundos');
      const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

      let timeoutId = null;
      let intervalId = null;
      let tecnologiaEditadaId = null;
      let tecnologiaDeshabilitarId = null;
      let tecnologiaDeshabilitarNombre = null;
      let tecnologiaHabilitarId = null;
      let tecnologiaHabilitarNombre = null;

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
        if (formCrear) {
          formCrear.reset();
        }
      }

      // ===== FUNCIONES PARA MODAL DE EDICIÓN =====
      function abrirModalEditar(id, nombre, descripcion) {
        tecnologiaEditadaId = id;
        
        if (selectNombre) {
          selectNombre.value = nombre;
        }
        if (textareaDescripcion) {
          textareaDescripcion.value = descripcion;
        }
        
        modalEditar.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalEditar.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalEditar() {
        modalEditar.classList.add('hidden');
        document.body.style.overflow = '';
        if (formEditar) {
          formEditar.reset();
        }
        tecnologiaEditadaId = null;
      }

      // ===== FUNCIONES PARA MODAL DE DETALLE =====
      function abrirModalDetalle(id, nombre, descripcion, estado) {
        if (detalleTitulo) {
          detalleTitulo.textContent = `Detalle: ${nombre}`;
        }
        if (detalleNombre) {
          detalleNombre.textContent = nombre;
        }
        if (detalleDescripcion) {
          detalleDescripcion.textContent = descripcion;
        }
        
        if (detalleEstadoTexto) {
          detalleEstadoTexto.textContent = estado === 'activo' ? 'Activo' : 'Inactivo';
        }
        
        if (detalleEstadoBadge) {
          if (estado === 'activo') {
            detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena';
            if (detalleEstadoIndicador) {
              detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-[#39A900] mr-2';
            }
          } else {
            detalleEstadoBadge.className = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600';
            if (detalleEstadoIndicador) {
              detalleEstadoIndicador.className = 'w-2 h-2 rounded-full bg-gray-400 mr-2';
            }
          }
        }
        
        modalDetalle.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalDetalle.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalDetalle() {
        modalDetalle.classList.add('hidden');
        document.body.style.overflow = '';
      }

      // ===== FUNCIONES PARA MODAL DE DESHABILITAR =====
      function abrirModalDeshabilitar(id, nombre) {
        tecnologiaDeshabilitarId = id;
        tecnologiaDeshabilitarNombre = nombre;
        
        if (spanTecnologiaDeshabilitar) {
          spanTecnologiaDeshabilitar.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitar.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalDeshabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalDeshabilitar() {
        modalDeshabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        tecnologiaDeshabilitarId = null;
        tecnologiaDeshabilitarNombre = null;
      }

      // ===== FUNCIONES PARA MODAL DE HABILITAR =====
      function abrirModalHabilitar(id, nombre) {
        tecnologiaHabilitarId = id;
        tecnologiaHabilitarNombre = nombre;
        
        if (spanTecnologiaHabilitar) {
          spanTecnologiaHabilitar.textContent = `"${nombre}"`;
        }
        
        modalHabilitar.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalHabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalHabilitar() {
        modalHabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        tecnologiaHabilitarId = null;
        tecnologiaHabilitarNombre = null;
      }

      // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE DESHABILITAR =====
      function abrirModalConfirmacionDeshabilitar(nombreTecnologia) {
        console.log('Abriendo modal de confirmación con nombre:', nombreTecnologia);
        
        const spanElement = document.getElementById('nombre-tecnologia-emergente-deshabilitada');
        
        if (spanElement) {
          const nombreParaMostrar = nombreTecnologia || 'Tecnología';
          spanElement.textContent = `"${nombreParaMostrar}"`;
          console.log('Span actualizado:', spanElement.textContent);
        } else {
          console.error('No se encontró el elemento nombre-tecnologia-emergente-deshabilitada');
        }
        
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
          progressBar.style.transition = 'none';
          progressBar.style.width = '0%';
          void progressBar.offsetWidth;
          progressBar.style.transition = 'width 3000ms linear';
          setTimeout(() => {
            progressBar.style.width = '100%';
          }, 50);
        }
        
        let segundos = 3;
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) {
          contador.textContent = segundos;
        }

        if (timeoutId) clearTimeout(timeoutId);
        if (intervalId) clearInterval(intervalId);

        intervalId = setInterval(() => {
          segundos--;
          if (contador) {
            contador.textContent = segundos;
          }
        }, 1000);

        timeoutId = setTimeout(() => {
          cerrarModalConfirmacionDeshabilitar();
        }, 3000);

        const modal = document.getElementById('modal-deshabilitado-tecnologia-emergente');
        if (modal) {
          modal.classList.remove('hidden');
          // Forzar reflow para que la animación se ejecute
          modal.offsetHeight;
          document.body.style.overflow = 'hidden';
        }
      }

      function cerrarModalConfirmacionDeshabilitar() {
        const modal = document.getElementById('modal-deshabilitado-tecnologia-emergente');
        if (modal) {
          modal.classList.add('hidden');
        }
        document.body.style.overflow = '';
        
        if (timeoutId) {
          clearTimeout(timeoutId);
          timeoutId = null;
        }
        if (intervalId) {
          clearInterval(intervalId);
          intervalId = null;
        }
        
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
          progressBar.style.width = '0%';
        }
      }

      // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE HABILITAR =====
      function abrirModalConfirmacionHabilitar(nombreTecnologia) {
        if (!modalConfirmacionHabilitar) return;
        
        console.log('Abriendo modal de confirmación de habilitar con nombre:', nombreTecnologia);
        
        if (nombreTecnologiaHabilitadaSpan) {
          const nombreParaMostrar = nombreTecnologia || 'Tecnología';
          nombreTecnologiaHabilitadaSpan.textContent = `"${nombreParaMostrar}"`;
        }
        
        if (progressBarHabilitado) {
          progressBarHabilitado.style.transition = 'none';
          progressBarHabilitado.style.width = '0%';
          void progressBarHabilitado.offsetWidth;
          progressBarHabilitado.style.transition = 'width 3000ms linear';
          setTimeout(() => {
            progressBarHabilitado.style.width = '100%';
          }, 50);
        }
        
        let segundos = 3;
        if (contadorSegundosHabilitado) {
          contadorSegundosHabilitado.textContent = segundos;
        }

        if (timeoutId) clearTimeout(timeoutId);
        if (intervalId) clearInterval(intervalId);

        intervalId = setInterval(() => {
          segundos--;
          if (contadorSegundosHabilitado) {
            contadorSegundosHabilitado.textContent = segundos;
          }
        }, 1000);

        timeoutId = setTimeout(() => {
          cerrarModalConfirmacionHabilitar();
        }, 3000);

        modalConfirmacionHabilitar.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalConfirmacionHabilitar.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalConfirmacionHabilitar() {
        if (!modalConfirmacionHabilitar) return;
        
        modalConfirmacionHabilitar.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutId) {
          clearTimeout(timeoutId);
          timeoutId = null;
        }
        if (intervalId) {
          clearInterval(intervalId);
          intervalId = null;
        }
        
        if (progressBarHabilitado) {
          progressBarHabilitado.style.width = '0%';
        }
      }

      // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE CREACIÓN =====
      function abrirModalConfirmacionCrear(nombreTecnologia) {
        if (nombreTecnologiaSpan) {
          nombreTecnologiaSpan.textContent = `"${nombreTecnologia}"`;
        }
        
        if (progressBarCrear) {
          progressBarCrear.style.transition = 'none';
          progressBarCrear.style.width = '0%';
          void progressBarCrear.offsetWidth;
          progressBarCrear.style.transition = 'width 3000ms linear';
          setTimeout(() => {
            progressBarCrear.style.width = '100%';
          }, 50);
        }
        
        let segundos = 3;
        if (contadorSegundosCrear) {
          contadorSegundosCrear.textContent = segundos;
        }

        if (timeoutId) clearTimeout(timeoutId);
        if (intervalId) clearInterval(intervalId);

        intervalId = setInterval(() => {
          segundos--;
          if (contadorSegundosCrear) {
            contadorSegundosCrear.textContent = segundos;
          }
        }, 1000);

        timeoutId = setTimeout(() => {
          cerrarModalConfirmacionCrear();
        }, 3000);

        modalConfirmacionCrear.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalConfirmacionCrear.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalConfirmacionCrear() {
        modalConfirmacionCrear.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutId) {
          clearTimeout(timeoutId);
          timeoutId = null;
        }
        if (intervalId) {
          clearInterval(intervalId);
          intervalId = null;
        }
        
        if (progressBarCrear) {
          progressBarCrear.style.width = '0%';
        }
      }

      // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE EDICIÓN =====
      function abrirModalConfirmacionEditar(nombreTecnologia) {
        if (!modalConfirmacionEditar) return;
        
        if (nombreTecnologiaEditadaSpan) {
          nombreTecnologiaEditadaSpan.textContent = `"${nombreTecnologia}"`;
        }
        
        if (progressBarEditar) {
          progressBarEditar.style.transition = 'none';
          progressBarEditar.style.width = '0%';
          void progressBarEditar.offsetWidth;
          progressBarEditar.style.transition = 'width 3000ms linear';
          setTimeout(() => {
            progressBarEditar.style.width = '100%';
          }, 50);
        }
        
        let segundos = 3;
        if (contadorSegundosEditar) {
          contadorSegundosEditar.textContent = segundos;
        }

        if (timeoutId) clearTimeout(timeoutId);
        if (intervalId) clearInterval(intervalId);

        intervalId = setInterval(() => {
          segundos--;
          if (contadorSegundosEditar) {
            contadorSegundosEditar.textContent = segundos;
          }
        }, 1000);

        timeoutId = setTimeout(() => {
          cerrarModalConfirmacionEditar();
        }, 3000);

        modalConfirmacionEditar.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modalConfirmacionEditar.offsetHeight;
        document.body.style.overflow = 'hidden';
      }

      function cerrarModalConfirmacionEditar() {
        if (!modalConfirmacionEditar) return;
        
        modalConfirmacionEditar.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutId) {
          clearTimeout(timeoutId);
          timeoutId = null;
        }
        if (intervalId) {
          clearInterval(intervalId);
          intervalId = null;
        }
        
        if (progressBarEditar) {
          progressBarEditar.style.width = '0%';
        }
      }

      // ===== EVENT LISTENERS =====

      // Event listeners para modal de creación
      if (btnAbrirModalCrear) {
        btnAbrirModalCrear.addEventListener('click', abrirModalCrear);
      }

      botonesCerrarCrear.forEach(boton => {
        boton.addEventListener('click', cerrarModalCrear);
      });

      if (modalCrear) {
        modalCrear.addEventListener('click', function(e) {
          if (e.target === modalCrear || e.target.classList.contains('bg-black')) {
            cerrarModalCrear();
          }
        });
      }

      // Event listeners para modal de edición
      botonesEditar.forEach(boton => {
        boton.addEventListener('click', function(e) {
          e.stopPropagation();
          const id = this.getAttribute('data-id');
          const nombre = this.getAttribute('data-nombre');
          const descripcion = this.getAttribute('data-descripcion');
          abrirModalEditar(id, nombre, descripcion);
        });
      });

      botonesCerrarEditar.forEach(boton => {
        boton.addEventListener('click', cerrarModalEditar);
      });

      if (modalEditar) {
        modalEditar.addEventListener('click', function(e) {
          if (e.target === modalEditar || e.target.classList.contains('bg-black')) {
            cerrarModalEditar();
          }
        });
      }

      // Event listeners para modal de detalle
      tarjetasTecnologia.forEach(tarjeta => {
        tarjeta.addEventListener('click', function(e) {
          if (!e.target.closest('.btn-editar-tendencia') && !e.target.closest('.switch-sena')) {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const descripcion = this.getAttribute('data-descripcion');
            const estado = this.getAttribute('data-estado');
            abrirModalDetalle(id, nombre, descripcion, estado);
          }
        });
      });

      botonesCerrarDetalle.forEach(boton => {
        boton.addEventListener('click', cerrarModalDetalle);
      });

      if (modalDetalle) {
        modalDetalle.addEventListener('click', function(e) {
          if (e.target === modalDetalle || e.target.classList.contains('bg-black')) {
            cerrarModalDetalle();
          }
        });
      }

      // Event listeners para modal de deshabilitar (switches activos)
      document.querySelectorAll('.switch-sena.active').forEach(switchBtn => {
        switchBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          const tarjeta = this.closest('.tarjeta-tecnologia');
          if (tarjeta) {
            const id = tarjeta.getAttribute('data-id');
            let nombre = tarjeta.getAttribute('data-nombre');
            const estadoActual = tarjeta.getAttribute('data-estado');
            
            if (!nombre || nombre === 'null' || nombre === '') {
              const h3 = tarjeta.querySelector('h3');
              if (h3) {
                nombre = h3.textContent.trim();
              }
            }
            
            console.log('Switch activo clickeado - Deshabilitar:', { id, nombre, estadoActual });
            
            if (estadoActual === 'activo') {
              if (nombre && nombre !== 'null' && nombre !== '') {
                abrirModalDeshabilitar(id, nombre);
              } else {
                console.error('No se pudo obtener el nombre de la tecnología');
                alert('Error: No se pudo identificar la tecnología');
              }
            }
          }
        });
      });

      // Event listeners para modal de habilitar (switches inactivos)
      document.querySelectorAll('.switch-sena:not(.active)').forEach(switchBtn => {
        switchBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          const tarjeta = this.closest('.tarjeta-tecnologia');
          if (tarjeta) {
            const id = tarjeta.getAttribute('data-id');
            let nombre = tarjeta.getAttribute('data-nombre');
            const estadoActual = tarjeta.getAttribute('data-estado');
            
            if (!nombre || nombre === 'null' || nombre === '') {
              const h3 = tarjeta.querySelector('h3');
              if (h3) {
                nombre = h3.textContent.trim();
              }
            }
            
            console.log('Switch inactivo clickeado - Habilitar:', { id, nombre, estadoActual });
            
            if (estadoActual === 'inactivo') {
              if (nombre && nombre !== 'null' && nombre !== '') {
                abrirModalHabilitar(id, nombre);
              } else {
                console.error('No se pudo obtener el nombre de la tecnología');
                alert('Error: No se pudo identificar la tecnología');
              }
            }
          }
        });
      });

      botonesCerrarDeshabilitar.forEach(boton => {
        boton.addEventListener('click', cerrarModalDeshabilitar);
      });

      if (modalDeshabilitar) {
        modalDeshabilitar.addEventListener('click', function(e) {
          if (e.target === modalDeshabilitar || e.target.classList.contains('bg-black')) {
            cerrarModalDeshabilitar();
          }
        });
      }

      botonesCerrarHabilitar.forEach(boton => {
        boton.addEventListener('click', cerrarModalHabilitar);
      });

      if (modalHabilitar) {
        modalHabilitar.addEventListener('click', function(e) {
          if (e.target === modalHabilitar || e.target.classList.contains('bg-black')) {
            cerrarModalHabilitar();
          }
        });
      }

      // Event listener para el botón confirmar deshabilitar
      if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function() {
          console.log('Botón confirmar deshabilitar clickeado:', { id: tecnologiaDeshabilitarId, nombre: tecnologiaDeshabilitarNombre });
          
          if (!tecnologiaDeshabilitarId || !tecnologiaDeshabilitarNombre) {
            console.error('Falta id o nombre:', { id: tecnologiaDeshabilitarId, nombre: tecnologiaDeshabilitarNombre });
            
            if (spanTecnologiaDeshabilitar) {
              const texto = spanTecnologiaDeshabilitar.textContent;
              tecnologiaDeshabilitarNombre = texto.replace(/^"|"$/g, '');
              console.log('Nombre recuperado del span:', tecnologiaDeshabilitarNombre);
            }
          }
          
          if (tecnologiaDeshabilitarId && tecnologiaDeshabilitarNombre) {
            console.log('Deshabilitando tecnología:', { id: tecnologiaDeshabilitarId, nombre: tecnologiaDeshabilitarNombre });
            
            cerrarModalDeshabilitar();
            abrirModalConfirmacionDeshabilitar(tecnologiaDeshabilitarNombre);
          } else {
            console.error('No se pudo recuperar el nombre de la tecnología');
            cerrarModalDeshabilitar();
          }
        });
      }

      // Event listener para el botón confirmar habilitar
      if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', function() {
          console.log('Botón confirmar habilitar clickeado:', { id: tecnologiaHabilitarId, nombre: tecnologiaHabilitarNombre });
          
          if (!tecnologiaHabilitarId || !tecnologiaHabilitarNombre) {
            console.error('Falta id o nombre:', { id: tecnologiaHabilitarId, nombre: tecnologiaHabilitarNombre });
            
            if (spanTecnologiaHabilitar) {
              const texto = spanTecnologiaHabilitar.textContent;
              tecnologiaHabilitarNombre = texto.replace(/^"|"$/g, '');
              console.log('Nombre recuperado del span:', tecnologiaHabilitarNombre);
            }
          }
          
          if (tecnologiaHabilitarId && tecnologiaHabilitarNombre) {
            console.log('Habilitando tecnología:', { id: tecnologiaHabilitarId, nombre: tecnologiaHabilitarNombre });
            
            cerrarModalHabilitar();
            abrirModalConfirmacionHabilitar(tecnologiaHabilitarNombre);
          } else {
            console.error('No se pudo recuperar el nombre de la tecnología');
            cerrarModalHabilitar();
          }
        });
      }

      // Event listeners para modal de confirmación de deshabilitar
      botonesCerrarConfirmacionDeshabilitar.forEach(boton => {
        boton.addEventListener('click', cerrarModalConfirmacionDeshabilitar);
      });

      if (modalConfirmacionDeshabilitar) {
        modalConfirmacionDeshabilitar.addEventListener('click', function(e) {
          if (e.target === modalConfirmacionDeshabilitar || e.target.classList.contains('bg-black')) {
            cerrarModalConfirmacionDeshabilitar();
          }
        });
      }

      // Event listeners para modal de confirmación de habilitar
      if (modalConfirmacionHabilitar) {
        botonesCerrarConfirmacionHabilitar.forEach(boton => {
          boton.addEventListener('click', cerrarModalConfirmacionHabilitar);
        });

        modalConfirmacionHabilitar.addEventListener('click', function(e) {
          if (e.target === modalConfirmacionHabilitar || e.target.classList.contains('bg-black')) {
            cerrarModalConfirmacionHabilitar();
          }
        });
      }

      // Event listeners para modal de confirmación de creación
      botonesCerrarConfirmacionCrear.forEach(boton => {
        boton.addEventListener('click', cerrarModalConfirmacionCrear);
      });

      if (modalConfirmacionCrear) {
        modalConfirmacionCrear.addEventListener('click', function(e) {
          if (e.target === modalConfirmacionCrear || e.target.classList.contains('bg-black')) {
            cerrarModalConfirmacionCrear();
          }
        });
      }

      // Event listeners para modal de confirmación de edición
      if (modalConfirmacionEditar) {
        botonesCerrarConfirmacionEditar.forEach(boton => {
          boton.addEventListener('click', cerrarModalConfirmacionEditar);
        });

        modalConfirmacionEditar.addEventListener('click', function(e) {
          if (e.target === modalConfirmacionEditar || e.target.classList.contains('bg-black')) {
            cerrarModalConfirmacionEditar();
          }
        });
      }

      // Manejar el envío del formulario de creación
      if (formCrear) {
        formCrear.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(formCrear);
          const area = formData.get('area');
          const descripcion = formData.get('descripcion');
          
          if (!area) {
            alert('Por favor selecciona un área');
            return;
          }

          console.log('Creando tecnología:', { area, descripcion });

          cerrarModalCrear();
          abrirModalConfirmacionCrear(area);
        });
      }

      // Manejar el envío del formulario de edición
      if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(formEditar);
          const nombre = formData.get('nombre');
          const descripcion = formData.get('descripcion');
          
          if (!nombre) {
            alert('Por favor selecciona un área');
            return;
          }

          console.log('Editando tecnología:', { id: tecnologiaEditadaId, nombre, descripcion });

          cerrarModalEditar();
          abrirModalConfirmacionEditar(nombre);
        });
      }

      // Manejar la tecla ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (modalConfirmacionCrear && !modalConfirmacionCrear.classList.contains('hidden')) {
            cerrarModalConfirmacionCrear();
          } else if (modalConfirmacionEditar && !modalConfirmacionEditar.classList.contains('hidden')) {
            cerrarModalConfirmacionEditar();
          } else if (modalConfirmacionDeshabilitar && !modalConfirmacionDeshabilitar.classList.contains('hidden')) {
            cerrarModalConfirmacionDeshabilitar();
          } else if (modalConfirmacionHabilitar && !modalConfirmacionHabilitar.classList.contains('hidden')) {
            cerrarModalConfirmacionHabilitar();
          } else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
            cerrarModalDeshabilitar();
          } else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
            cerrarModalHabilitar();
          } else if (modalDetalle && !modalDetalle.classList.contains('hidden')) {
            cerrarModalDetalle();
          } else if (modalEditar && !modalEditar.classList.contains('hidden')) {
            cerrarModalEditar();
          } else if (modalCrear && !modalCrear.classList.contains('hidden')) {
            cerrarModalCrear();
          }
        }
      });
    });
