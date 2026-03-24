document.addEventListener("DOMContentLoaded", () => {
  

  const modalDeshabilitar = document.getElementById('modal-deshabilitar-usuario');
  const modalHabilitar = document.getElementById('modal-habilitar-usuario');

  const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
  const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');

  const modalDeshabilitado = document.getElementById('modal-deshabilitado-usuario');
  const modalHabilitado = document.getElementById('modal-habilitado-usuario');

  const modalDetalle = document.getElementById('modal-detalle-usuario')
  const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
  const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
  const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');

  let usuariosGlobal = [];
  let usuarioSeleccionado = null;
  let filtroActual = "todos";
  let timeoutDeshabilitado = null;
  let timeoutHabilitado = null;
  let intervalContadorHabilitado = null;
  let intervalContadorDeshabilitado = null;

  const cardActiva = document.getElementById("card-activos");

  cardActiva.classList.add("bg-sena-soft");
  cargarUsuarios();

  // =========================
  // CARGAR USUARIOS
  // =========================
  function cargarUsuarios(){

    fetch("../../controllers/UsuarioController.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: "accion=listar"
    })

    .then(res => res.json())
    .then(response => {

      usuariosGlobal = response.data || [];

      console.log("Usuarios:", usuariosGlobal);

      actualizarTarjetas();
      filtrarUsuarios(filtroActual);

    });

  }

  // =========================
  // RENDER TABLA
  // =========================
  function renderUsuarios(lista){

    const tabla = document.getElementById("tabla-usuarios");
    tabla.innerHTML = "";

    lista.forEach(usuario => {

      const activo = usuario.estado == 1;

      tabla.innerHTML += `
      <tr class="border-b border-gray-100 hover:bg-gray-50">

        <td class="px-4 py-3 font-medium text-gray-900">
          ${usuario.representante_legal}
        </td>

        <td class="px-4 py-3 text-gray-600">
          ${usuario.nombre_empresa ?? "Sistema"}
        </td>

        <td class="px-4 py-3 text-gray-600">
          ${usuario.rol_nombre}
        </td>

        <td class="px-4 py-3">
          <span class="px-2 py-1 text-xs font-medium rounded-full
          ${activo ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"}">
          ${activo ? "Activo" : "Inactivo"}
          </span>
        </td>

        <td class="px-4 py-3">
          <div class="flex items-center gap-3">

            <button 
              class="btn-ver-usuario text-green-500 hover:text-green-700"
              data-id="${usuario.id_usuario}"
            >
            👁
            </button>

            <div
              onclick="cambiarEstadoUsuario(${usuario.id_usuario}, '${usuario.representante_legal}', ${usuario.estado})"
              class="switch-sena ${activo ? "active" : ""} cursor-pointer"
              title="${activo ? "Activo" : "Inactivo"}"
            ></div>

          </div>
        </td>

      </tr>
      `;
    });

  }

  // =========================
  // FILTRAR USUARIOS
  // =========================
  function filtrarUsuarios(tipo){

    filtroActual = tipo;  

    let filtrados = [];

    if(tipo === "todos"){
      filtrados = usuariosGlobal;
    }

    if(tipo === "activos"){
      filtrados = usuariosGlobal.filter(u => u.estado == 1);
    }

    if(tipo === "inactivos"){
      filtrados = usuariosGlobal.filter(u => u.estado == 0);
    }

    activarTarjeta(tipo);
    renderUsuarios(filtrados);

  }

  // =========================
  // TARJETA SELECCIONADA
  // =========================
  function activarTarjeta(tipo){
    document.querySelectorAll(".card-dashboard").forEach(card => {
      card.classList.remove("bg-sena-soft","ring-1","ring-green-500");
    });

    let cardActiva;

    if(tipo === "todos"){
      cardActiva = document.getElementById("card-todos");
    }

    if(tipo === "activos"){
      cardActiva = document.getElementById("card-activos");
    }

    if(tipo === "inactivos"){
      cardActiva = document.getElementById("card-inactivos");
    }

    // agregar color solo a la seleccionada
    if(cardActiva){
      cardActiva.classList.add("bg-sena-soft","ring-1","ring-green-500");
    }

  }

  // =========================
  // TARJETAS
  // =========================
  function actualizarTarjetas(){

    const total = usuariosGlobal.length;
    const activos = usuariosGlobal.filter(u => u.estado == 1).length;
    const inactivos = usuariosGlobal.filter(u => u.estado == 0).length;

    document.getElementById("total-usuarios").innerText = total;
    document.getElementById("usuarios-activos").innerText = activos;
    document.getElementById("usuarios-inactivos").innerText = inactivos;

  }

  // =========================
  // CAMBIAR ESTADO
  // =========================
  window.cambiarEstadoUsuario = function(id, nombre, estado){

    usuarioSeleccionado = id;

    if(estado == 1){
      modalDeshabilitar.classList.remove("hidden");
    }else{
      modalHabilitar.classList.remove("hidden");
    }

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

    document.querySelectorAll(".cerrar-modal-detalle-usuario")
      .forEach(btn => {
        btn.addEventListener("click", () => {
          cerrarModal(document.getElementById("modal-detalle-usuario"));
      });
    });
    

  // =========================
  // CONFIRMAR DESHABILITAR
  // =========================
  if (btnConfirmarDeshabilitar) {
    btnConfirmarDeshabilitar.addEventListener("click", () => {
      fetch(`../../controllers/UsuarioController.php?accion=desactivar&id_usuario=${usuarioSeleccionado}`)
      .then(res => res.json())
      .then(() => {

        modalDeshabilitar.classList.add("hidden");
        cargarUsuarios();

        mostrarModalDeshabilitado("Usuario");

      });
    });
  }

  // =========================
  // CONFIRMAR HABILITAR
  // =========================
  if (btnConfirmarHabilitar) {
    btnConfirmarHabilitar.addEventListener("click", () => {
      fetch(`../../controllers/UsuarioController.php?accion=activar&id_usuario=${usuarioSeleccionado}`)
      .then(res => res.json())
      .then(() => {

        modalHabilitar.classList.add("hidden");
        cargarUsuarios();

        mostrarModalHabilitado("Usuario");

      });
    });
  }

  document.addEventListener("click", function(e){

    if(e.target.closest(".btn-ver-usuario")){

      const boton = e.target.closest(".btn-ver-usuario");
      const idUsuario = boton.dataset.id;

      const usuario = usuariosGlobal.find(u => u.id_usuario == idUsuario);

      if(!usuario) return;

      cargarDetalleUsuario(usuario);
      abrirModal(document.getElementById("modal-detalle-usuario"));

    }

  });

  function cargarDetalleUsuario(usuario){

    document.getElementById("detalle-nombre").textContent =
      usuario.representante_legal;

    document.getElementById("detalle-correo").textContent =
      usuario.correo ?? "Sin correo";

    document.getElementById("detalle-cargo").textContent =
      usuario.rol_nombre + " · " + (usuario.nombre_empresa ?? "");

    // iniciales avatar
    const iniciales = usuario.representante_legal
      .split(" ")
      .map(n => n[0])
      .join("")
      .substring(0,2)
      .toUpperCase();

    document.getElementById("detalle-avatar").textContent = iniciales;

  }

  // ===== FUNCIÓN PARA MOSTRAR MODAL DESHABILITADO =====
  function mostrarModalDeshabilitado(nombrePerfil) {        
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
        
        if (!modalHabilitado) {
            console.error('Modal habilitado no encontrado');
            return;
        }
        
        if (nombrePerfilHabilitadoExitoSpan) {
            nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalHabilitado);
        
        // Resetear contador
        let segundos = 3;
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
        }, 3000);
  }

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
        if (modalHabilitado) {
            modalHabilitado.classList.add('hidden');
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
  

  // =========================
  // EXPONER FILTRO AL HTML
  // =========================
  window.filtrarUsuarios = filtrarUsuarios;

});