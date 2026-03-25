<?php

function obtenerPermisosPorRol() {
    return [
        'ADMINISTRADOR' => [
            //programas
            'crear_programa',
            'editar_programa',
            'desactivar_programa',
            'ver_areas',
            'ver_menu_programas_admin',
            //perfiles
            'crear_perfil',
            //header
            'gestionar_usuarios',
            'ver_historial',
            //tendencias
            'crear_tendencia',
            'editar_tendencia',
            'desactivar_tendencia',
            //tecnologias emergentes
            'crear_tecnologia',
            'editar_tecnologia',
            'desactivar_tecnologia',
            //proyeccion futuro
            'crear_proyeccion',
            'editar_proyeccion',
            'desactivar_proyeccion',
            //linea tecnologica
            'crear_linea',
            'editar_linea',
            'desactivar_linea'
        ],
        'EMPRESA' => [
            // sin permisos
        ]
    ];
}

function tienePermiso($permiso) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $rol = $_SESSION['rol_nombre'] ?? '';

    $permisos = obtenerPermisosPorRol();

    return in_array($permiso, $permisos[$rol] ?? []);
}