const Auth = {
    API_URL: "../../controllers/LogController.php",
    usuario: null,
    autenticado: false,
    listo: false,  // ✅ Flag para saber cuando Auth está listo
    callbacks: [], // ✅ Cola de callbacks
    permisosPorRol: {
        INVITADO: [
            'ver_inicio',
            'ver_perfiles',
            'ver_lineas_tecnologicas',
            'ver_programas'
        ],
        ADMINISTRADOR: [
            'crear_programa',           // ✅ SIN ESPACIOS
            'editar_programa',
            'desactivar_programa',
            'ver_programas',
            'ver_areas',
            'ver_menu_programas_admin',
            'crear_perfil',
            'editar_perfil',
            'desactivar_perfil',
            'ver_perfiles',
            'gestionar_usuarios',
            'ver_historial',
            'ver_tendencias',
            'crear_tendencia',          
            'editar_tendencia',
            'desactivar_tendencia',
            'ver_tecnologias_emergentes',
            'crear_tecnologia',
            'editar_tecnologia',
            'desactivar_tecnologia',  
            'ver_proyeccion_futuro',
            'crear_proyeccion',
            'editar_proyeccion',
            'desactivar_proyeccion',
            'ver_lineas_tecnologicas',
            'crear_linea',            
            'editar_linea',
            'desactivar_linea',
            'ver_sugerencias',
            'crear_sugerencia',
            'editar_sugerencia',
            'desactivar_sugerencia',
            'ver_estadisticas'
        ],
        EMPRESA: [
            'ver_inicio',
            'ver_perfiles',
            'crear_perfil',
            'editar_perfil',
            'desactivar_perfil',
            'ver_sugerencias',
            'crear_sugerencia',
            'ver_tendencias',
            'ver_tecnologias_emergentes',
            'ver_proyeccion_futuro',
            'ver_lineas_tecnologicas',
            'ver_programas',
            'ver_estadisticas'
        ]
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
                console.log("👤 Usuario no autenticado (Invitado)");
            }
            
            this.actualizarUI();
            this.aplicarPermisosMenu();
            
            // ✅ MARCAR COMO LISTO Y EJECUTAR CALLBACKS
            this.listo = true;
            this.callbacks.forEach(cb => { try { cb(); } catch(e) {} });
            this.callbacks = [];
            
            return this.autenticado;
        } catch (error) {
            console.error("Error:", error);
            this.autenticado = false;
            this.usuario = null;
            this.actualizarUI();
            this.aplicarPermisosMenu();
            
            // ✅ TAMBIÉN MARCAR COMO LISTO EN ERROR
            this.listo = true;
            this.callbacks.forEach(cb => { try { cb(); } catch(e) {} });
            this.callbacks = [];
            
            return false;
        }
    },

    // ✅ MÉTODO PARA ESPERAR A QUE AUTH ESTÉ LISTO
    whenReady(callback) {
        if (this.listo) {
            callback();
        } else {
            this.callbacks.push(callback);
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
        } else {
            if (navUser) navUser.classList.add('hidden');
            if (navGuest) navGuest.classList.remove('hidden');
        }
        document.getElementById('app')?.classList.remove('opacity-0');
    },

    aplicarPermisosMenu() {
        const rol = this.autenticado ? (this.usuario?.rol_nombre || 'INVITADO').toUpperCase().trim() : 'INVITADO';
        const permisos = this.permisosPorRol[rol] || [];
        console.log('🔐 Rol detectado:', rol);
        console.log('📋 Permisos disponibles:', permisos);
        
        const menuPermisos = {
            'menu-programas': 'ver_programas',
            'menu-tendencias': 'ver_tendencias',
            'nav-lineas': 'ver_lineas_tecnologicas',
            'nav-sugerencias': 'ver_sugerencias',
            'nav-gestion': 'gestionar_usuarios',
            'nav-historial': 'ver_historial',
            'nav-estadisticas': 'ver_estadisticas',
        };
        
        Object.entries(menuPermisos).forEach(([elementId, permiso]) => {
            const elemento = document.getElementById(elementId);
            if (elemento) {
                if (permisos.includes(permiso)) {
                    elemento.style.display = '';
                    elemento.classList.remove('hidden');
                } else {
                    elemento.style.display = 'none';
                    elemento.classList.add('hidden');
                }
            }
        });
        this.aplicarPermisosPorAccion(permisos);
    },

    aplicarPermisosPorAccion(permisosOverride = null) {
        const rol = this.autenticado ? (this.usuario?.rol_nombre || '').toUpperCase().trim() : 'INVITADO';
        const permisos = permisosOverride || this.permisosPorRol[rol] || [];
        document.querySelectorAll('[data-permiso]').forEach(el => {
            const permiso = el.getAttribute('data-permiso')?.trim();
            if (permiso && permisos.includes(permiso)) {
                el.style.display = '';
                el.classList.remove('hidden');
            } else {
                el.style.display = 'none';
                el.classList.add('hidden');
            }
        });
    },

    tienePermiso(permiso) {
        const rol = this.autenticado ? (this.usuario?.rol_nombre || 'INVITADO').toUpperCase().trim() : 'INVITADO';
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
        if (!this.autenticado) return rol.toUpperCase() === 'INVITADO';
        const rolUsuario = (this.usuario?.rol_nombre || '').toUpperCase();
        return rolUsuario === rol.toUpperCase();
    }
};

document.addEventListener("DOMContentLoaded", () => {
    console.log("=== DOM CARGADO ===");
    Auth.verificarSesion();
});