<?php
include __DIR__ . '../../../includes/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../../assets/css/output.css">
    <link rel="stylesheet" href="../../assets/css/globals.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-[82rem] mx-auto px-4 py-8 lg:px-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
                <p class="text-gray-500 text-sm">Administra los usuarios registrados en la plataforma</p>
            </div>
        </div>
        
        <!-- CARDS -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div id="card-todos"
                onclick="filtrarUsuarios('todos')"
                class="card-dashboard cursor-pointer p-6 rounded-xl shadow border border-gray rounded-xl p-6 flex flex-col items-start">
                <span id="total-usuarios" class="text-3xl font-bold">0</span>
                <span class="text-sm text-gray-700 tracking-wide">TOTAL USUARIOS</span>
            </div>
            <div id="card-activos"
                onclick="filtrarUsuarios('activos')"
                class="card-dashboard cursor-pointer p-6 rounded-xl shadow border border-gray rounded-xl p-6 flex flex-col items-start">
                <span id="usuarios-activos" class="text-3xl font-bold">0</span>
                <span class="text-sm text-gray-700 tracking-wide">ACTIVOS</span>
            </div>
            <div id="card-inactivos"
                onclick="filtrarUsuarios('inactivos')"
                class="card-dashboard cursor-pointer p-6 rounded-xl shadow border border-gray rounded-xl p-6 flex flex-col items-start">
                <span id="usuarios-inactivos" class="text-3xl font-bold">0</span>
                <span class="text-sm text-gray-700 tracking-wide">INACTIVOS</span>
            </div>
        </div>
        
        <!-- BUSCADOR -->
        <div class="flex-1 items-center gap-3 mb-6">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input
                    type="text"
                    id="buscador"
                    placeholder="Buscar usuario por nombre, email o empresa..."
                    class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm focus:border-emerald-500 focus:outline-none"
                />
            </div>
        </div>
        
        <!-- FILTRO -->
        <div class="flex items-center gap-2 mb-6">
            <select
                id="filtro-rol"
                onchange="filtrarPorRol(this.value)"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:border-sena focus:outline-none hover:border-sena"
            >
                <option value="todos">Todos los roles</option>
                <option value="1">Administrador</option>
                <option value="2">Empresa</option>
            </select>
        </div>
        
        <!-- TABLA -->
        <div class="rounded-lg border border-gray-200 bg-white overflow-hidden">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Usuario</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Representante legal</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Rol</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Estado</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios"></tbody>
            </table>
            <!-- MENSAJE SIN RESULTADOS -->
            <div id="mensaje-sin-resultados" class="hidden">
                <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl min-h-[400px]">
                    <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-sena-text-main mb-2">No se encontraron resultados</h3>
                    <p id="texto-sin-resultados" class="text-sm text-sena-text-soft text-center max-w-sm"></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'modal-deshabilitar-usuario.php'; ?>
    <?php include 'modal-habilitar-usuario.php'; ?>
    <?php include 'modal-habilitado-usuario.php'; ?>
    <?php include 'modal-deshabilitado-usuario.php'; ?>
    <?php include 'modal-detalle-usuario.php'; ?>
    
    <!-- JS -->
    <script src="../../assets/js/usuarios.js"></script>
    <?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>
