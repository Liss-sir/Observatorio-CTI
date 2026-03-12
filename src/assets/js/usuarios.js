document.addEventListener("DOMContentLoaded", () => {
  cargarUsuarios();
});

const modalDeshabilitar = document.getElementById('modal-deshabilitar-usuario');
const modalHabilitar = document.getElementById('modal-habilitar-usuario');

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

    console.log("datos:", response.data);

    const tabla = document.getElementById("tabla-usuarios");
    tabla.innerHTML = "";

    const usuarios = response.data || [];

    usuarios.forEach(usuario => {
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
            ${usuario.estado == 1 
              ? "bg-green-100 text-green-700"
              : "bg-red-100 text-red-700"}">
            ${usuario.estado == 1 ? "Activo" : "Inactivo"}
            </span>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
            <button class="text-gray-400 hover:text-gray-700">
                👁
            </button>
            <div
              class="switch-sena ${activo ? "active" : ""} cursor-pointer"
              title="${activo ? "Activo" : "Inactivo"}"
            ></div>
            </div>
        </td>
        </tr>
    `;
    });

  });

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

function cambiarEstadoUsuario(id, nombre, estadoActual){

  if(estadoActual == 1){

    fetch(`../../controllers/UsuarioController.php?accion=desactivar&id_usuario=${id}`)
    .then(res => res.json())
    .then(() => {

      mostrarModalDeshabilitado(nombre);
      cargarUsuarios();

    });

  }else{

    fetch(`../../controllers/UsuarioController.php?accion=activar&id_usuario=${id}`)
    .then(res => res.json())
    .then(() => {

      mostrarModalHabilitado(nombre);
      cargarUsuarios();

    });

  }

}