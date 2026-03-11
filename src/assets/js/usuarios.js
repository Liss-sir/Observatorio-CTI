document.addEventListener("DOMContentLoaded", () => {

  cargarUsuarios();
});

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
            <div
            class="switch-sena ${activo ? "active" : ""} cursor-pointer"
            title="${activo ? "Activo" : "Inactivo"}"
            ></div>
        </td>
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
            <button class="text-gray-400 hover:text-gray-700">
                👁
            </button>
            </div>
        </td>
        </tr>
    `;
    });

  });

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