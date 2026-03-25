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
            console.log("Respuesta:", data);
            
            if (data.success && data.autenticado) {
                this.autenticado = true;
                this.usuario = data.usuario;
                console.log("✅ Autenticado:", this.usuario);
            } else {
                this.autenticado = false;
                this.usuario = null;
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
        const navUser = document.getElementById('nav-user');
        const navGuest = document.getElementById('nav-guest');

        if (this.autenticado && this.usuario) {
            if (navUser) navUser.classList.remove('hidden');
            if (navGuest) navGuest.classList.add('hidden');
            
            const userName = document.getElementById('user-name');
            const userRole = document.getElementById('user-role');
            
            if (userName) userName.textContent = this.usuario.nombre || 'Usuario';
            if (userRole) userRole.textContent = this.usuario.rol_nombre || 'Rol';
            
            this.aplicarPermisosPorAccion();
        } else {
            if (navUser) navUser.classList.add('hidden');
            if (navGuest) navGuest.classList.remove('hidden');
        }
        document.getElementById('app')?.classList.remove('opacity-0');
    },

    aplicarPermisosPorAccion() {
        if (!this.usuario) return;
        
        const rol = (this.usuario.rol_nombre || '').toUpperCase().trim();
        const permisos = this.permisosPorRol[rol] || [];
        
        console.log('🔐 Rol:', rol);
        console.log('📋 Permisos:', permisos);
        
        document.querySelectorAll('[data-permiso]').forEach(el => {
            const permiso = el.getAttribute('data-permiso');
            console.log('🔍 Verificando:', permiso);
            
            if (permisos.includes(permiso)) {
                console.log('✅ Concedido:', permiso);
                el.style.display = '';
                el.classList.remove('hidden');
            } else {
                console.log('⛔ Denegado:', permiso);
                el.style.display = 'none';
                el.classList.add('hidden');
            }
        });
    },

    tienePermiso(permiso) {
        if (!this.usuario) return false;
        const rol = (this.usuario.rol_nombre || '').toUpperCase().trim();
        const permisos = this.permisosPorRol[rol] || [];
        return permisos.includes(permiso);
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
            console.error("Error logout:", error);
        }
    },

    tieneRol(rol) {
        const rolUsuario = (this.usuario?.rol_nombre || '').toUpperCase();
        return this.autenticado && rolUsuario === rol.toUpperCase();
    }
};

document.addEventListener("DOMContentLoaded", () => {
    console.log("=== DOM CARGADO ===");
    Auth.verificarSesion();
});