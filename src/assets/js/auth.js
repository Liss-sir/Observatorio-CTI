/**
 * ESTO ES NUEVO
 * Módulo de autenticación global
 */

const Auth = {
    API_URL: "../../controllers/LogController.php",
    usuario: null,
    autenticado: false,
    permisosPorRol: {
        ADMINISTRADOR: [
            'crear_programa',
            'editar_programa',
            'desactivar_programa',
            'ver_menu_programas_admin',
            'ver_areas',
            'gestionar_usuarios',
            'ver_historial',
            'crear_perfil',
            'crear_tendencia',
            'editar_tendencia',
            'desactivar_tendencia',
            'crear_tecnologia',
            'editar_tecnologia',
            'desactivar_tecnologia',
            'crear_proyeccion',
            'editar_proyeccion',
            'desactivar_proyeccion',
            'crear_linea',
            'editar_linea',
            'desactivar_linea'
        ],
        EMPRESA: []
    },

    async verificarSesion() {
        try {
            console.log("=== INICIANDO VERIFICACIÓN ===");
            const response = await fetch(`${this.API_URL}?accion=sesion`, {
                method: "GET",
                credentials: "same-origin"
            });
            const data = await response.json();
            console.log("Respuesta del servidor:", data);
            
            if (data.success && data.autenticado) {
                this.autenticado = true;
                this.usuario = data.usuario;
                console.log("✅ Usuario autenticado:", this.usuario);
            } else {
                this.autenticado = false;
                this.usuario = null;
                console.log("❌ No autenticado");
            }
            
            this.actualizarUI();
            return this.autenticado;
            
        } catch (error) {
            console.error("Error:", error);
            this.actualizarUI();
            return false;
        }
    },

    actualizarUI() {
        console.log("=== ACTUALIZANDO UI ===");
        
        const navGuest = document.getElementById("nav-guest");
        const navUser = document.getElementById("nav-user");
        
        if (this.autenticado && this.usuario) {
            if (navGuest) navGuest.style.display = "none";
            if (navUser) navUser.classList.remove("hidden");
            
            this.actualizarInfoUsuario();
            this.aplicarPermisosPorRol();
        } else {
            if (navGuest) navGuest.style.display = "flex";
            if (navUser) navUser.classList.add("hidden");
            this.ocultarTodoPorRol();
        }
    },

    actualizarInfoUsuario() {
        if (!this.usuario) return;
        
        const nombre = this.usuario.nombre || this.usuario.correo?.split('@')[0] || 'Usuario';
        const rol = this.usuario.rol_nombre || 'Usuario';
        
        const userName = document.getElementById("user-name");
        const userRole = document.getElementById("user-role");
        
        if (userName) userName.textContent = nombre;
        if (userRole) userRole.textContent = rol;
        
        console.log("Info actualizada:", { nombre, rol });
    },

    aplicarPermisosPorRol() {
        if (!this.usuario) {
            console.log("No hay usuario");
            return;
        }

        // USAR MAYÚSCULAS como vienen de la BD
        const rolUsuario = (this.usuario.rol_nombre || '').toUpperCase().trim();
        console.log("Rol del usuario:", rolUsuario);

        const elementos = document.querySelectorAll('[data-rol]');
        console.log(`Encontrados ${elementos.length} elementos con data-rol`);

        elementos.forEach((el, index) => {
            const rolRequerido = (el.getAttribute('data-rol') || '').toUpperCase().trim();
            const texto = el.textContent?.trim() || `Elemento ${index}`;
            
            console.log(`[${index}] "${texto}" | Requiere: "${rolRequerido}" | Usuario: "${rolUsuario}"`);
            
            if (rolUsuario === rolRequerido) {
                // MOSTRAR
                el.classList.remove('hidden');
                el.style.display = '';
                console.log(`   ✅ MOSTRADO`);
            } else {
                // OCULTAR
                el.classList.add('hidden');
                el.style.display = 'none';
                console.log(`   ❌ OCULTADO`);
            }
        });
    },

    ocultarTodoPorRol() {
        document.querySelectorAll('[data-rol]').forEach(el => {
            el.classList.add('hidden');
            el.style.display = 'none';
        });
    },

    async logout() {
        try {
            const response = await fetch(`${this.API_URL}?accion=logout`, {
                method: "POST",
                credentials: "same-origin"
            });
            const data = await response.json();
            if (data.success) {
                sessionStorage.clear();
                window.location.href = "../../auth/login/login.php";
            }
        } catch (error) {
            console.error("Error en logout:", error);
            alert("Error al cerrar sesión");
        }
    },

    tieneRol(rol) {
        const rolUsuario = (this.usuario?.rol_nombre || '').toUpperCase();
        return this.autenticado && rolUsuario === rol.toUpperCase();
    }
};

// Inicializar
document.addEventListener("DOMContentLoaded", () => {
    console.log("=== DOM CARGADO ===");
    Auth.verificarSesion();
});