<?php
/**
 * ESTO ES NUEVO
 * Middleware de autenticación
 * Incluir al inicio de páginas protegidas
 */

function requerirAuth() {
    session_start();
    
    if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
        header('Location: ../../auth/login/login.php');
        exit;
    }
}

function requerirRol($rolPermitido) {
    requerirAuth();
    
    $rolActual = $_SESSION['rol_nombre'] ?? '';
    
    if (strtolower($rolActual) !== strtolower($rolPermitido)) {
        // Redirigir al dashboard correspondiente
        header('Location: ../../view/dashboard/dashboard.php');
        exit;
    }
}

// Helper para verificar si es admin
function esAdmin() {
    return ($_SESSION['rol_nombre'] ?? '') === 'ADMINISTRADOR';
}

// Helper para verificar si es empresa
function esEmpresa() {
    return ($_SESSION['rol_nombre'] ?? '') === 'EMPRESA';
}
?>