<?php

function obtenerPermisosPorRol() {
    return [
        'INVITADO' => [
            'ver_inicio',
            'ver_perfiles',
            'ver_lineas_tecnologicas',
            'ver_programas'
        ],
        'ADMINISTRADOR' => [
            //programas
            'crear_programa',
            'editar_programa',
            'desactivar_programa',
            'ver_programas',
            'ver_areas',
            'ver_menu_programas_admin',
            //perfiles
            'crear_perfil',
            'editar_perfil',
            'desactivar_perfil',
            'ver_perfiles',
            //header
            'gestionar_usuarios',
            'ver_historial',
            //tendencias
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
            //linea tecnologica
            'ver_lineas_tecnologicas',
            'crear_linea',
            'editar_linea',
            'desactivar_linea',
            //sugerencias
            'ver_sugerencias',
            'crear_sugerencia',
            'editar_sugerencia',
            'desactivar_sugerencia',
            //estadisticas
            'ver_estadisticas'
        ],
        'EMPRESA' => [
            // Solo puede ver y gestionar perfiles
            'ver_inicio',
            'ver_perfiles',
            'crear_perfil',
            'editar_perfil',
            'desactivar_perfil',
            // Solo puede ver y crear sugerencias (no editar ni desactivar las de otros)
            'ver_sugerencias',
            'crear_sugerencia',
            'ver_lineas_tecnologicas',
            'ver_programas',
            'ver_estadisticas'
        ]
    ];
}

function obtenerRolActual() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['rol_nombre']) || empty($_SESSION['rol_nombre'])) {
        return 'INVITADO';
    }
    
    return $_SESSION['rol_nombre'];
}

function tienePermiso($permiso) {
    $rol = obtenerRolActual();
    $permisos = obtenerPermisosPorRol();
    
    return in_array($permiso, $permisos[$rol] ?? []);
}

function verificarPermiso($permiso) {
    if (!tienePermiso($permiso)) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (obtenerRolActual() === 'INVITADO') {
            header('Location: /auth/login/login.php?error=requiere_autenticacion');
            exit;
        }
        
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => 'No autorizado'
        ]);
        exit;
    }
}

function estaAutenticado() {
    return obtenerRolActual() !== 'INVITADO';
}
?>
