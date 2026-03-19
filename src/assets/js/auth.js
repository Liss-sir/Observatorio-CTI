/**
 * Módulo de autenticación global
 * Maneja la verificación de sesión y actualización del UI
 */

const Auth = {
    API_URL: "../../controllers/LogController.php",
    usuario: null,
    autenticado: false,

    /**
     * Verifica la sesión actual con el servidor
     */
    async verificarSesion() {
        try {
            const response = await fetch(`${this.API_URL}?accion=sesion`, {
                method: "GET",
                credentials: "same-origin" // Importante para enviar cookies de sesión
            });
            
            const data = await response.json();
            
            if (data.success && data.autenticado) {
                this.autenticado = true;
                this.usuario = data.usuario;
                this.actualizarUI();
                return true;
            } else {
                this.autenticado = false;
                this.usuario = null;
                this.actualizarUI();
                return false;
            }
        } catch (error) {
            console.error("Error verificando sesión:", error);
            this.autenticado = false;
            this.usuario = null;
            return false;
        }
    },

    /**
     * Cierra la sesión
     */
    async logout() {
        try {
            const response = await fetch(`${this.API_URL}?accion=logout`, {
                method: "POST",
                credentials: "same-origin"
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Limpiar sessionStorage
                sessionStorage.clear();
                
                // Redirigir al login o landing
                window.location.href = "../../auth/login/login.php";
            }
        } catch (error) {
            console.error("Error en logout:", error);
        }
    },

    /**
     * Actualiza el UI según el estado de autenticación
     */
    actualizarUI() {
        const navAuth = document.getElementById("nav-auth");
        const navGuest = document.getElementById("nav-guest");
        const userMenu = document.getElementById("user-menu");
        const userName = document.getElementById("user-name");
        const userRole = document.getElementById("user-role");

        if (this.autenticado && this.usuario) {
            // Ocultar botones de invitado (login/register)
            if (navGuest) navGuest.classList.add("hidden");
            
            // Mostrar menú de usuario
            if (navAuth) {
                navAuth.classList.remove("hidden");
                
                // Actualizar información del usuario
                if (userName) {
                    userName.textContent = this.usuario.nombre || this.usuario.correo;
                }
                if (userRole) {
                    userRole.textContent = this.usuario.rol_nombre || 'Usuario';
                }
            }

            // Ocultar/mostrar elementos según rol
            this.aplicarPermisosPorRol();
        } else {
            // Mostrar botones de invitado
            if (navGuest) navGuest.classList.remove("hidden");
            
            // Ocultar menú de usuario
            if (navAuth) navAuth.classList.add("hidden");
        }
    },

    /**
     * Aplica permisos visuales según el rol del usuario
     */
    aplicarPermisosPorRol() {
        if (!this.usuario) return;

        const rol = this.usuario.rol_nombre?.toLowerCase();

        // Elementos solo para administradores
        document.querySelectorAll('[data-rol="admin"]').forEach(el => {
            el.style.display = rol === 'administrador' ? '' : 'none';
        });

        // Elementos solo para empresas
        document.querySelectorAll('[data-rol="empresa"]').forEach(el => {
            el.style.display = rol === 'empresa' ? '' : 'none';
        });

        // Elementos para ambos (autenticados)
        document.querySelectorAll('[data-auth="true"]').forEach(el => {
            el.style.display = '';
        });

        // Elementos solo para invitados (ocultar si está autenticado)
        document.querySelectorAll('[data-auth="false"]').forEach(el => {
            el.style.display = 'none';
        });
    },

    /**
     * Verifica si el usuario tiene un rol específico
     */
    tieneRol(rol) {
        return this.autenticado && this.usuario?.rol_nombre?.toLowerCase() === rol.toLowerCase();
    },

    /**
     * Redirige si no está autenticado
     */
    requerirAuth() {
        if (!this.autenticado) {
            window.location.href = "../../auth/login/login.php";
            return false;
        }
        return true;
    },

    /**
     * Redirige si no tiene el rol requerido
     */
    requerirRol(rol) {
        if (!this.requerirAuth()) return false;
        
        if (!this.tieneRol(rol)) {
            // Redirigir a página no autorizada o dashboard
            window.location.href = "../../view/dashboard/dashboard.php";
            return false;
        }
        return true;
    }
};

// Inicializar al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    Auth.verificarSesion();
});