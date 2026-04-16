<?php
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
        header('Location: ../../view/landing/landing.php');
        exit;
    }
}

function esAdmin() {
    return ($_SESSION['rol_nombre'] ?? '') === 'ADMINISTRADOR';
}

function esEmpresa() {
    return ($_SESSION['rol_nombre'] ?? '') === 'EMPRESA';
}
?>