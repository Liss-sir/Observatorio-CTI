<?php
session_start();

$usuario = $_SESSION['usuario'] ?? null;

include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_deshabilitar_perfiles.php';
include __DIR__ . '/modal_habilitar_perfiles.php';
include __DIR__ . '/modal_habilitado_confirmacion.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_perfiles.php';
include __DIR__ . '/modal_editar_programa.php';
include __DIR__ . '/modal_crear_programa.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Programas de Formación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../../../assets/css/output.css">
    <link rel="stylesheet" href="../../assets/css/globals.css">
</head>
<body class="opacity-0" id="app">
<body class="bg-gray-100">
<div class="max-w-[82rem] mx-auto px-4 py-8 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Programas de Formación</h1>
            <p class="text-gray-500 text-sm">Programas de formación disponibles en el SENA</p>
        </div>
    </div>
    
    <!-- Buscador -->
    <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="buscador" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar por nombre o código">
        </div>
        <button data-permiso="crear_programa" id="btn-abrir-crear-programa" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nuevo Programa
        </button>
    </div>
    
    <!-- Cards - Se carga dinámicamente desde JS -->
    <div id="contenedorProgramas" class="grid md:grid-cols-3 gap-6">
        <div class="col-span-3 text-center py-10 text-gray-500">
            <i data-lucide="loader" class="w-8 h-8 mx-auto mb-2 animate-spin"></i>
            <p>Cargando programas...</p>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>

<script src="../../assets/js/programas.js"></script>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>