<?php
// middleware/auth.php

function requerirAuth() {
    session_start();
    
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
        header('Location: ' . rutaAbsoluta('auth/login/login.php'));
        exit;
    }
}

function requerirRol($rolPermitido) {
    requerirAuth();
    
    $rolUsuario = $_SESSION['rol_nombre'] ?? '';
    
    if (strtolower($rolUsuario) !== strtolower($rolPermitido)) {
        header('Location: ' . rutaAbsoluta('view/dashboard/dashboard.php'));
        exit;
    }
}

function rutaAbsoluta($rutaRelativa) {
    // Ajusta según tu estructura
    $base = '/tu-proyecto/'; // Cambia esto
    return $base . $rutaRelativa;
}

function usuarioActual() {
    return $_SESSION['usuario'] ?? null;
}

function esAdmin() {
    return ($_SESSION['rol_nombre'] ?? '') === 'administrador';
}

function esEmpresa() {
    return ($_SESSION['rol_nombre'] ?? '') === 'empresa';
}
?>