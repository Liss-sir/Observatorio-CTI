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
    
    // =========================
    // ✅ VARIABLES DE PAGINACIÓN
    // =========================
    let paginaActual = 1;
    const elementosPorPagina = 10;
    let usuariosFiltrados = [];
    
    const cardActiva = document.getElementById("card-activos");
    if (cardActiva) {
        cardActiva.classList.add("bg-sena-soft");
    }
    
    // Buscador funcional
    const buscador = document.getElementById("buscador");
    if (buscador) {
        buscador.addEventListener("input", (e) => {
            textoBusqueda = e.target.value.toLowerCase().trim();
            paginaActual = 1;
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
    // RENDER TABLA (CON PAGINACIÓN)
    // =========================
    function renderUsuarios(lista){
        const tabla = document.getElementById("tabla-usuarios");
        // 👇 NUEVO: Asegurar que el contenedor padre permita scroll horizontal
        const padre = tabla.parentElement;
        if (padre && !padre.classList.contains('overflow-x-auto')) {
            padre.classList.add('overflow-x-auto', 'w-full');
        }
        // 👇 Forzar un ancho mínimo a la tabla para que el scroll aparezca en móviles
        tabla.style.minWidth = '600px';
        tabla.style.width = '100%';
        const mensajeSinResultados = document.getElementById("mensaje-sin-resultados");
        const textoSinResultados = document.getElementById("texto-sin-resultados");
        const paginacionContainer = document.getElementById("paginacion-container");
        
        tabla.innerHTML = "";
        usuariosFiltrados = lista;
        
        if (lista.length === 0) {
            tabla.parentElement.classList.add("hidden");
            if (mensajeSinResultados) {
                mensajeSinResultados.classList.remove("hidden");
            }
            if (paginacionContainer) {
                paginacionContainer.classList.add("hidden");
            }
            
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
            
            if (textoSinResultados) {
                textoSinResultados.textContent = mensaje;
            }
            if (padre) padre.classList.add('overflow-x-auto'); 
            return;
        }
        
        tabla.parentElement.classList.remove("hidden");
        if (mensajeSinResultados) {
            mensajeSinResultados.classList.add("hidden");
        }
        
        // =========================
        // ✅ PAGINACIÓN: Calcular página actual
        // =========================
        const totalPaginas = Math.ceil(lista.length / elementosPorPagina);
        
        if (paginaActual > totalPaginas) {
            paginaActual = totalPaginas;
        }
        
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const usuariosPagina = lista.slice(inicio, fin);
        
        usuariosPagina.forEach(usuario => {
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
                                <i data-lucide="eye" class="w-4 h-4"></i>
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
        
        // =========================
        // ✅ ACTUALIZAR PAGINACIÓN
        // =========================
        actualizarPaginacion(lista.length);
        lucide.createIcons();
    }
    
    // =========================
    // ✅ FUNCIÓN PARA ACTUALIZAR PAGINACIÓN
    // =========================
    function actualizarPaginacion(totalElementos) {
        const paginacionContainer = document.getElementById("paginacion-container");
        if (!paginacionContainer) return;
        
        const totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        
        if (totalPaginas <= 1) {
            paginacionContainer.classList.add("hidden");
            return;
        }
        
        paginacionContainer.classList.remove("hidden");
        
        let paginasHTML = '';
        
        // Determinar qué páginas mostrar
        let inicio = Math.max(1, paginaActual - 2);
        let fin = Math.min(totalPaginas, paginaActual + 2);
        
        // Ajustar si estamos al inicio
        if (paginaActual <= 3) {
            fin = Math.min(5, totalPaginas);
        }
        
        // Ajustar si estamos al final
        if (paginaActual >= totalPaginas - 2) {
            inicio = Math.max(totalPaginas - 4, 1);
        }
        
        // Primera página y elipsis al inicio
        if (inicio > 1) {
            paginasHTML += `
                <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="1">
                    1
                </button>
            `;
            if (inicio > 2) {
                paginasHTML += `<span class="px-2 text-sena-text-soft">...</span>`;
            }
        }
        
        // Páginas intermedias
        for (let i = inicio; i <= fin; i++) {
            const isActive = paginaActual === i;
            paginasHTML += `
                <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                    isActive 
                        ? 'bg-sena text-white shadow-md scale-100' 
                        : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                }" data-pagina="${i}">
                    ${i}
                </button>
            `;
        }
        
        // Última página y elipsis al final
        if (fin < totalPaginas) {
            if (fin < totalPaginas - 1) {
                paginasHTML += `<span class="px-2 text-sena-text-soft">...</span>`;
            }
            paginasHTML += `
                <button class="btn-pagina px-3 py-2 rounded-lg transition-all duration-200 border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30" data-pagina="${totalPaginas}">
                    ${totalPaginas}
                </button>
            `;
        }
        
        paginacionContainer.innerHTML = `
            <div class="flex flex-col items-center gap-3 mb-6">
                <div class="text-sm text-sena-text-soft">
                    Mostrando <span class="font-medium text-sena">${inicio + 1}</span> - 
                    <span class="font-medium text-sena">${Math.min(fin, totalElementos)}</span> de 
                    <span class="font-medium text-sena">${totalElementos}</span> usuarios
                </div>
                
                <div class="flex items-center gap-2 flex-wrap justify-center">
                    <!-- Primera página -->
                    <button class="btn-primera-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M18 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Anterior -->
                    <button class="btn-pagina-anterior px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === 1 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === 1 ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <!-- Botones de páginas -->
                    ${paginasHTML}
                    
                    <!-- Siguiente -->
                    <button class="btn-pagina-siguiente px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    
                    <!-- Última página -->
                    <button class="btn-ultima-pagina px-3 py-2 rounded-lg transition-all duration-200 ${
                        paginaActual === totalPaginas 
                            ? 'bg-gray-100 text-sena-text-soft cursor-not-allowed opacity-50' 
                            : 'border border-sena-border text-sena-text-main hover:bg-sena-soft hover:border-sena/30'
                    }" ${paginaActual === totalPaginas ? 'disabled' : ''}>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M6 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        // =========================
        // ✅ EVENT LISTENERS DE PAGINACIÓN
        // =========================
        document.querySelectorAll('.btn-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                paginaActual = parseInt(btn.dataset.pagina);
                renderUsuarios(usuariosFiltrados);
            });
        });
        
        document.querySelectorAll('.btn-pagina-anterior').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual > 1) {
                    paginaActual--;
                    renderUsuarios(usuariosFiltrados);
                }
            });
        });
        
        document.querySelectorAll('.btn-pagina-siguiente').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual < totalPaginas) {
                    paginaActual++;
                    renderUsuarios(usuariosFiltrados);
                }
            });
        });
        
        document.querySelectorAll('.btn-primera-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== 1) {
                    paginaActual = 1;
                    renderUsuarios(usuariosFiltrados);
                }
            });
        });
        
        document.querySelectorAll('.btn-ultima-pagina').forEach(btn => {
            btn.addEventListener('click', () => {
                if (paginaActual !== totalPaginas) {
                    paginaActual = totalPaginas;
                    renderUsuarios(usuariosFiltrados);
                }
            });
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
        paginaActual = 1;
        aplicarFiltros();
    }
    
    // =========================
    // FILTRAR POR ROL
    // =========================
    window.filtrarPorRol = function(rol) {
        filtroRolActual = rol;
        paginaActual = 1;
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
        
        const usuario = usuariosGlobal.find(u => u.id_usuario == id);
        const nombreEmpresa = usuario?.nombre_empresa ?? nombre;
        
        if(estado == 1){
            const spanDeshabilitar = document.getElementById('nombre-usuario-deshabilitar');
            if (spanDeshabilitar) {
                spanDeshabilitar.textContent = `"${nombreEmpresa}"`;
            }
            
            modalDeshabilitar.classList.remove("hidden");
        } else {
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
    
    document.querySelectorAll(".cerrar-modal-deshabilitar")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-deshabilitar-usuario"));
            });
        });
    
    document.querySelectorAll(".cerrar-modal-habilitar")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-habilitar-usuario"));
            });
        });
    
    document.querySelectorAll(".cerrar-modal-deshabilitado")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-deshabilitado-usuario"));
            });
        });
    
    document.querySelectorAll(".cerrar-modal-habilitado-confirmacion")
        .forEach(btn => {
            btn.addEventListener("click", () => {
                cerrarModal(document.getElementById("modal-habilitado-usuario"));
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
                
                const usuario = usuariosGlobal.find(u => u.id_usuario == usuarioSeleccionado);
                const nombreEmpresa = usuario?.nombre_empresa ?? "Usuario";
                
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
                
                const usuario = usuariosGlobal.find(u => u.id_usuario == usuarioSeleccionado);
                const nombreEmpresa = usuario?.nombre_empresa ?? "Usuario";
                
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

    function recortarTexto(texto, max = 25) {
        if (!texto) return '';
        if (texto.length <= max) return texto;

        const sub = texto.substring(0, max);
        return sub.substring(0, sub.lastIndexOf(' ')) + '...';
    }
    
    // =========================
    // CARGAR DETALLE USUARIO
    // =========================
    function cargarDetalleUsuario(usuario) {
        if (!usuario) {
            console.error("No se recibieron datos del usuario");
            return;
        }

        console.log("📦 Datos recibidos del backend:", usuario); // 🔍 CLAVE PARA DEBUG

        // Campos básicos
        document.getElementById("detalle-nombre").textContent = usuario.representante_legal ?? "Sin nombre";
        document.getElementById("detalle-representante").textContent = usuario.representante_legal ?? "Sin nombre";
        document.getElementById("detalle-correo").textContent = usuario.correo ?? "Sin correo";
        document.getElementById("detalle-cargo").textContent = `${usuario.rol_nombre ?? "Sin rol"} · ${usuario.nombre_empresa ?? "Sin empresa"}`;
        document.getElementById("detalle-razon-social").textContent = usuario.razon_social ?? "No especificada";
        document.getElementById("detalle-documento").textContent = usuario.tipo_documento ?? "Sin tipo";
        document.getElementById("detalle-numero-documento").textContent = usuario.numero_documento ?? "Sin número";
        document.getElementById("detalle-fecha-registro").textContent = usuario.fecha_registro ? formatearFecha(usuario.fecha_registro) : "Sin fecha";
        
        const iniciales = (usuario.nombre_empresa ?? "US").split(" ").map(n => n[0]).join("").substring(0, 2).toUpperCase();
        document.getElementById("detalle-avatar").textContent = iniciales || "US";

        // =========================
        // TECNOLOGÍAS
        // =========================
        const contTecnologias = document.getElementById("detalle-tecnologias");
        contTecnologias.innerHTML = "";
        
        const tecs = Array.isArray(usuario.tecnologias) ? usuario.tecnologias : [];
        console.log("🔍 Tecnologías recibidas:", tecs);

        if (tecs.length > 0) {
            tecs.forEach(tec => {
                const span = document.createElement("span");
                span.className = "px-2.5 py-1 rounded-full bg-sena-soft text-sena text-xs font-medium";
                const texto = tec.nombre ?? tec.linea_tecnologica ?? "Tecnología";
                span.textContent = recortarTexto(texto, 25);
                contTecnologias.appendChild(span);
            });
        } else {
            contTecnologias.innerHTML = `<span class="px-2.5 py-1 rounded-full bg-gray-200 text-gray-500 text-xs">Sin líneas tecnológicas</span>`;
        }

        // =========================
        // PERFILES
        // =========================
        const contPerfiles = document.getElementById("detalle-perfiles");
        const totalSpan = document.getElementById("detalle-total-perfiles");
        const btnVerMas = document.getElementById("btn-ver-mas-perfiles");
        
        contPerfiles.innerHTML = "";
        const perfs = Array.isArray(usuario.perfiles) ? usuario.perfiles : [];
        console.log("🔍 Perfiles recibidos:", perfs);

        totalSpan.textContent = perfs.length;

        if (perfs.length > 0) {
            perfs.slice(0, 3).forEach(perfil => {
                const div = document.createElement("div");
                div.className = "bg-gray-50 rounded p-2 flex justify-between items-center";
                
                const estadoClass = perfil.estado == 1 ? "bg-sena-soft text-sena" : "bg-red-100 text-red-700";
                const estadoTexto = perfil.estado == 1 ? "Vigente" : "Inactivo";

                div.innerHTML = `
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-sena-text-main truncate">${perfil.nombre ?? "Sin nombre"}</p>
                        <p class="text-xs text-sena-text-soft truncate">${perfil.linea_nombre ?? "Sin línea"}</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold ${estadoClass} flex-shrink-0 ml-2">${estadoTexto}</span>
                `;
                contPerfiles.appendChild(div);
            });

            if (btnVerMas) {
                if (perfs.length > 3) {
                    btnVerMas.classList.remove("hidden");
                    btnVerMas.textContent = `Ver ${perfs.length - 3} más`;

                    // 🔥 AQUÍ FALTA ESTO
                    btnVerMas.onclick = () => {
                        window.location.href = "../perfiles/perfiles.php";
                    };

                } else {
                    btnVerMas.classList.add("hidden");
                }
            }
        } else {
            contPerfiles.innerHTML = `<div class="text-center py-2 text-gray-500 text-xs bg-gray-50 rounded">Sin perfiles creados</div>`;
            if (btnVerMas) btnVerMas.classList.add("hidden");
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
    
    window.filtrarUsuarios = filtrarUsuarios;
});