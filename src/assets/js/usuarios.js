document.addEventListener("DOMContentLoaded", () => {
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-usuario');
    const modalHabilitar = document.getElementById('modal-habilitar-usuario');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const modalDeshabilitado = document.getElementById('modal-deshabilitado-usuario');
    const modalHabilitado = document.getElementById('modal-habilitado-usuario');
    const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
    const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');
    
    let usuariosGlobal = [];
    let usuarioSeleccionado = null;
    let filtroActual = "todos";
    let filtroRolActual = "todos";
    let textoBusqueda = "";
    let timeoutDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let intervalContadorDeshabilitado = null;
    
    const cardActiva = document.getElementById("card-activos");
    if (cardActiva) {
        cardActiva.classList.add("bg-sena-soft");
    }
    
    // Buscador funcional
    const buscador = document.getElementById("buscador");
    if (buscador) {
        buscador.addEventListener("input", (e) => {
            textoBusqueda = e.target.value.toLowerCase().trim();
            aplicarFiltros();
        });
    }
    
    cargarUsuarios();
    
    // =========================
    // CARGAR USUARIOS
    // =========================
    function cargarUsuarios(){
        fetch("../../controllers/UsuarioController.php?accion=listar", { method: "GET" })
        .then(res => res.json())
        .then(response => {
            usuariosGlobal = response.data || [];
            console.log("Usuarios:", usuariosGlobal);
            actualizarTarjetas();
            aplicarFiltros();
        })
        .catch(error => {
            console.error("Error al cargar usuarios:", error);
        });
    }
    
    // =========================
    // RENDER TABLA
    // =========================
    function renderUsuarios(lista){
        const tabla = document.getElementById("tabla-usuarios");
        const mensajeSinResultados = document.getElementById("mensaje-sin-resultados");
        const textoSinResultados = document.getElementById("texto-sin-resultados");
        
        tabla.innerHTML = "";
        
        if (lista.length === 0) {
            tabla.parentElement.classList.add("hidden");
            mensajeSinResultados.classList.remove("hidden");
            
            let mensaje = "No hay usuarios registrados";
            
            if (textoBusqueda) {
                mensaje = `No hay usuarios que coincidan con "${textoBusqueda}"`;
            } else if (filtroActual === "activos") {
                mensaje = "No hay usuarios activos";
            } else if (filtroActual === "inactivos") {
                mensaje = "No hay usuarios inactivos";
            } else if (filtroRolActual !== "todos") {
                const select = document.getElementById("filtro-rol");
                const rolNombre = select?.options[select.selectedIndex]?.text || "este rol";
                mensaje = `No hay usuarios con el rol "${rolNombre}"`;
            }
            
            textoSinResultados.textContent = mensaje;
            return;
        }
        
        tabla.parentElement.classList.remove("hidden");
        mensajeSinResultados.classList.add("hidden");
        
        lista.forEach(usuario => {
            const activo = usuario.estado == 1;
            
            tabla.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">
                        ${usuario.nombre_empresa ?? "Sistema"}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        ${usuario.representante_legal}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        ${usuario.rol_nombre}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            ${activo ? "bg-sena-soft text-sena" : "bg-red-100 text-red-700"}">
                            ${activo ? "Activo" : "Inactivo"}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <button 
                                class="btn-ver-usuario text-green-500 hover:text-green-700"
                                data-id="${usuario.id_usuario}">
                                👁
                            </button>
                            <div
                                onclick="cambiarEstadoUsuario(${usuario.id_usuario}, '${usuario.representante_legal}', ${usuario.estado})"
                                class="switch-sena ${activo ? "active" : ""} cursor-pointer"
                                title="${activo ? "Activo" : "Inactivo"}">
                            </div>
                        </div>
                    </td>
                </tr>
            `;
        });
    }
    
    // =========================
    // APLICAR TODOS LOS FILTROS
    // =========================
    function aplicarFiltros() {
        let filtrados = usuariosGlobal;
        
        if (filtroActual === "activos") {
            filtrados = filtrados.filter(u => u.estado == 1);
        }
        if (filtroActual === "inactivos") {
            filtrados = filtrados.filter(u => u.estado == 0);
        }
        
        if (filtroRolActual !== "todos") {
            filtrados = filtrados.filter(u => u.id_rol == filtroRolActual);
        }
        
        if (textoBusqueda) {
            filtrados = filtrados.filter(u => 
                (u.representante_legal && u.representante_legal.toLowerCase().includes(textoBusqueda)) ||
                (u.correo && u.correo.toLowerCase().includes(textoBusqueda)) ||
                (u.nombre_empresa && u.nombre_empresa.toLowerCase().includes(textoBusqueda))
            );
        }
        
        activarTarjeta(filtroActual);
        renderUsuarios(filtrados);
    }
    
    // =========================
    // FILTRAR POR ESTADO
    // =========================
    function filtrarUsuarios(tipo){
        filtroActual = tipo;
        aplicarFiltros();
    }
    
    // =========================
    // FILTRAR POR ROL
    // =========================
    window.filtrarPorRol = function(rol) {
        filtroRolActual = rol;
        aplicarFiltros();
    }
    
    // =========================
    // TARJETA SELECCIONADA
    // =========================
    function activarTarjeta(tipo){
        document.querySelectorAll(".card-dashboard").forEach(card => {
            card.classList.remove("bg-sena-soft", "ring-1", "ring-green-500");
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
        
        if(cardActiva){
            cardActiva.classList.add("bg-sena-soft", "ring-1", "ring-green-500");
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
        
        // ✅ BUSCAR EL USUARIO EN EL ARRAY GLOBAL PARA OBTENER nombre_empresa
        const usuario = usuariosGlobal.find(u => u.id_usuario == id);
        const nombreEmpresa = usuario?.nombre_empresa ?? nombre;

        const nombreSpan = document.getElementById('nombre-usuario-deshabilitar');
        if (nombreSpan) {
            nombreSpan.textContent = nombreEmpresa;
        }
        
        if(estado == 1){
            // ✅ ACTUALIZAR NOMBRE EN MODAL DE CONFIRMACIÓN DESHABILITAR
            const spanDeshabilitar = document.getElementById('nombre-perfil-deshabilitar-confirmacion');
            if (spanDeshabilitar) {
                spanDeshabilitar.textContent = `"${nombreEmpresa}"`;
            }
            
            modalDeshabilitar.classList.remove("hidden");
        } else {
            // ✅ ACTUALIZAR NOMBRE EN MODAL DE CONFIRMACIÓN HABILITAR
            const spanHabilitar = document.getElementById('nombre-perfil-habilitar');
            if (spanHabilitar) {
                spanHabilitar.textContent = `"${nombreEmpresa}"`;
            }
            
            modalHabilitar.classList.remove("hidden");
        }
    }

    
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
                
                // ✅ BUSCAR EL USUARIO PARA OBTENER nombre_empresa
                const usuario = usuariosGlobal.find(u => u.id_usuario == usuarioSeleccionado);
                const nombreEmpresa = usuario?.nombre_empresa ?? "Usuario";
                
                // ✅ PASAR nombre_empresa a la función correcta
                mostrarModalDeshabilitado(nombreEmpresa);
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
                
                // ✅ BUSCAR EL USUARIO PARA OBTENER nombre_empresa
                const usuario = usuariosGlobal.find(u => u.id_usuario == usuarioSeleccionado);
                const nombreEmpresa = usuario?.nombre_empresa ?? "Usuario";
                
                // ✅ PASAR nombre_empresa a la función correcta
                mostrarModalHabilitado(nombreEmpresa);
            });
        });
    }
    
    // =========================
    // VER DETALLE USUARIO
    // =========================
    document.addEventListener("click", function(e){
        if(e.target.closest(".btn-ver-usuario")){
            const boton = e.target.closest(".btn-ver-usuario");
            const idUsuario = boton.dataset.id;
            
            fetch(`../../controllers/UsuarioController.php?accion=obtenerDetalle&id_usuario=${idUsuario}`, {
                method: "GET"
            })
            .then(res => res.json())
            .then(response => {
                if (response.data) {
                    cargarDetalleUsuario(response.data);
                    abrirModal(document.getElementById("modal-detalle-usuario"));
                }
            })
            .catch(error => {
                console.error("Error al cargar detalle:", error);
            });
        }
    });
    
    // =========================
    // CARGAR DETALLE USUARIO (CON TECNOLOGÍAS)
    // =========================
    function cargarDetalleUsuario(usuario){
        document.getElementById("detalle-nombre").textContent = usuario.representante_legal ?? "Sin nombre";
        document.getElementById("detalle-representante").textContent = usuario.representante_legal ?? "Sin nombre";
        document.getElementById("detalle-correo").textContent = usuario.correo ?? "Sin correo";
        document.getElementById("detalle-cargo").textContent = (usuario.rol_nombre ?? "") + " · " + (usuario.nombre_empresa ?? "");
        document.getElementById("detalle-razon-social").textContent = usuario.razon_social ?? "No especificada";
        document.getElementById("detalle-documento").textContent = usuario.tipo_documento ?? "Sin tipo";
        document.getElementById("detalle-numero-documento").textContent = usuario.numero_documento ?? "Sin número";
        document.getElementById("detalle-fecha-registro").textContent = usuario.fecha_registro ? formatearFecha(usuario.fecha_registro) : "Sin fecha";
        
        const iniciales = (usuario.representante_legal ?? "US").split(" ").map(n => n[0]).join("").substring(0, 2).toUpperCase();
        document.getElementById("detalle-avatar").textContent = iniciales;
        
        // TECNOLOGÍAS
        const contenedorTecnologias = document.getElementById("detalle-tecnologias");
        contenedorTecnologias.innerHTML = "";
        
        if (usuario.tecnologias && usuario.tecnologias.length > 0) {
            usuario.tecnologias.forEach(tec => {
                const span = document.createElement("span");
                span.className = "px-2.5 py-1 rounded-full bg-sena-soft text-sena text-xs font-medium";
                span.textContent = tec.nombre ?? tec.linea_tecnologica ?? "Tecnología";
                contenedorTecnologias.appendChild(span);
            });
        } else {
            contenedorTecnologias.innerHTML = `
                <span class="px-2.5 py-1 rounded-full bg-sena-soft text-sena text-xs">
                    Sin líneas tecnológicas
                </span>
            `;
        }
        
        // PERFILES
        const contenedorPerfiles = document.getElementById("detalle-perfiles");
        const totalPerfilesSpan = document.getElementById("detalle-total-perfiles");
        contenedorPerfiles.innerHTML = "";
        
        if (usuario.perfiles && usuario.perfiles.length > 0) {
            totalPerfilesSpan.textContent = usuario.perfiles.length;
            
            const perfilesAMostrar = usuario.perfiles.slice(0, 3);
            
            perfilesAMostrar.forEach((perfil, index) => {
                const perfilDiv = document.createElement("div");
                perfilDiv.className = "bg-gray-50 rounded p-2 flex justify-between items-center";
                
                const estadoClass = perfil.estado == 1 ? "bg-sena-soft text-sena" : "bg-red-100 text-red-700";
                const estadoTexto = perfil.estado == 1 ? "Vigente" : "Inactivo";
                
                perfilDiv.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-sena-text-main truncate">
                            ${perfil.nombre ?? "Sin nombre"}
                        </p>
                        <p class="text-xs text-sena-text-soft truncate">
                            ${perfil.linea_nombre ?? "Sin línea"}
                        </p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold ${estadoClass} flex-shrink-0 ml-2">
                        ${estadoTexto}
                    </span>
                `;
                
                contenedorPerfiles.appendChild(perfilDiv);
            });
            
            const btnVerMas = document.getElementById("btn-ver-mas-perfiles");
            if (usuario.perfiles.length > 3) {
                btnVerMas.classList.remove("hidden");
                btnVerMas.textContent = `Ver ${usuario.perfiles.length - 3} más`;
            } else {
                btnVerMas.classList.add("hidden");
            }
        } else {
            totalPerfilesSpan.textContent = "0";
            contenedorPerfiles.innerHTML = `
                <div class="text-center py-2 text-gray-500 text-xs bg-gray-50 rounded">
                    Sin perfiles creados
                </div>
            `;
            document.getElementById("btn-ver-mas-perfiles").classList.add("hidden");
        }
    }
    
    function formatearFecha(fechaSQL) {
        if (!fechaSQL) return "";
        const fecha = new Date(fechaSQL);
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const anio = fecha.getFullYear();
        return `${dia}/${mes}/${anio}`;
    }
    
    // ===== MODAL DESHABILITADO =====
    function mostrarModalDeshabilitado(nombrePerfil) {
        if (!modalDeshabilitado) {
            console.error('Modal deshabilitado no encontrado');
            return;
        }
        
        if (nombrePerfilDeshabilitadoSpan) {
            nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalDeshabilitado);
        
        let segundos = 3;
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) {
            contador.textContent = segundos;
        }
        
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

    // ===== MODAL HABILITADO =====
   function mostrarModalHabilitado(nombrePerfil) {
        if (!modalHabilitado) {
            console.error('Modal habilitado no encontrado');
            return;
        }
        
        if (nombrePerfilHabilitadoExitoSpan) {
            nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalHabilitado);
        
        let segundos = 3;
        const contador = document.getElementById('contador-segundos');
        if (contador) {
            contador.textContent = segundos;
        }
        
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
    
    function cerrarModalDeshabilitado() {
        if (modalDeshabilitado) modalDeshabilitado.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (timeoutDeshabilitado) { clearTimeout(timeoutDeshabilitado); timeoutDeshabilitado = null; }
        if (intervalContadorDeshabilitado) { clearInterval(intervalContadorDeshabilitado); intervalContadorDeshabilitado = null; }
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos-deshabilitado');
        if (contador) contador.textContent = '3';
    }
    
    function cerrarModalHabilitado() {
        if (modalHabilitado) modalHabilitado.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (timeoutHabilitado) { clearTimeout(timeoutHabilitado); timeoutHabilitado = null; }
        if (intervalContadorHabilitado) { clearInterval(intervalContadorHabilitado); intervalContadorHabilitado = null; }
        const progressBar = document.getElementById('progress-bar-habilitado');
        if (progressBar) progressBar.style.width = '0%';
        const contador = document.getElementById('contador-segundos');
        if (contador) contador.textContent = '3';
    }

    // =========================
    // EVENT LISTENERS PARA CERRAR MODALES
    // =========================

    // Modal detalle usuario (YA EXISTE)
    document.querySelectorAll(".cerrar-modal-detalle-usuario")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-detalle-usuario"));
            });
        });

    // ✅ AGREGAR: Modal confirmar deshabilitar
    document.querySelectorAll(".cerrar-modal-deshabilitar")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-deshabilitar-usuario"));
            });
        });

    // ✅ AGREGAR: Modal confirmar habilitar
    document.querySelectorAll(".cerrar-modal-habilitar")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-habilitar-usuario"));
            });
        });

    // ✅ AGREGAR: Modal deshabilitado éxito
    document.querySelectorAll(".cerrar-modal-deshabilitado")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-deshabilitado-usuario"));
            });
        });

    // ✅ AGREGAR: Modal habilitado éxito
    document.querySelectorAll(".cerrar-modal-habilitado-confirmacion")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-habilitado-usuario"));
            });
        });
    
    window.filtrarUsuarios = filtrarUsuarios;
});