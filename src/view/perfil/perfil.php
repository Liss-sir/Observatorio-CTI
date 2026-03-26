<?php
// ✅ Solo validar sesión, NO pasar datos a la vista
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../../view/auth/login.php');
    exit;
}
?>
<?php include './modal_editar_perfil.php'; ?>
<?php include __DIR__ . '../../../includes/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../../../assets/css/output.css">
    <link rel="stylesheet" href="../../assets/css/globals.css">
    <title>Perfil</title>
</head>
<body class="bg-gray-100">
<div class="max-w-7xl mx-auto px-6 py-10">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-10">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-sena text-white rounded-2xl flex items-center justify-center text-2xl font-bold shadow">
                <span id="headerInicial">A</span>
            </div>
            <div>
                <h1 class="text-2xl font-semibold" id="headerNombre">Cargando...</h1>
                <div class="flex gap-2 mt-2">
                    <span class="bg-sena-soft text-sena-strong text-xs px-3 py-1 rounded-full font-medium" id="headerRol">...</span>
                </div>
            </div>
        </div>
        <button class="btnAbrirPerfil flex items-center gap-2 border border-sena px-4 py-2 rounded-xl hover:bg-sena-soft hover:text-sena-strong shadow-sm">
            <i data-lucide="pencil" class="w-4 h-4 text-sena"></i>
            Editar Perfil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- IZQUIERDA -->
        <div class="lg:col-span-2 space-y-6">
            <!-- INFO -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs text-gray-400 uppercase mb-4">Información</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                        <span id="infoNombre">Cargando...</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                        <span id="infoCorreo">Cargando...</span>
                    </div>
                </div>
            </div>

            <!-- RESUMEN DE LA PLATAFORMA (4 tarjetas) -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-xs text-gray-400 uppercase mb-4">Resumen de la plataforma</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Perfiles -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="file-text" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Perfiles</p>
                            <p class="text-2xl font-semibold" id="statPerfiles">0</p>
                            <p class="text-xs text-gray-400">registrados</p>
                        </div>
                    </div>
                    <!-- Empresas -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="building" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Empresas</p>
                            <p class="text-2xl font-semibold" id="statEmpresas">0</p>
                            <p class="text-xs text-gray-400">registradas</p>
                        </div>
                    </div>
                    <!-- Administradores (AGREGADO) -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="shield" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Administradores</p>
                            <p class="text-2xl font-semibold" id="statAdministradores">0</p>
                            <p class="text-xs text-gray-400">activos</p>
                        </div>
                    </div>
                    <!-- Usuarios (AGREGADO) -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Usuarios</p>
                            <p class="text-2xl font-semibold" id="statUsuarios">0</p>
                            <p class="text-xs text-gray-400">totales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PERFILES RECIENTES -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs text-gray-400 uppercase mb-4">Perfiles Recientes</h2>
                <div id="listaPerfiles">
                    <p class="text-sm text-gray-400">Cargando...</p>
                </div>
            </div>
        </div>

        <!-- DERECHA -->
        <div class="space-y-6">
            <!-- ESTADÍSTICAS -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                    Estadísticas
                </h3>
                <div class="space-y-2 text-sm" id="listaEstadisticas">
                    <div class="flex justify-between"><span>Cargando...</span></div>
                </div>
            </div>

            <!-- ACTIVIDAD -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                    Actividad
                </h3>
                <p class="text-sm text-gray-500" id="fechaRegistro">Miembro desde...</p>
            </div>

            <!-- CUENTA -->
            <div class="bg-gray-50 border rounded-2xl p-6">
                <div class="flex items-center gap-3">
                    <i data-lucide="shield" class="w-5 h-5 text-green-700"></i>
                    <div>
                        <h4 class="font-semibold text-sm" id="tipoCuenta">Cuenta</h4>
                        <p class="text-xs text-gray-500" id="descripcionCuenta">...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script>
<script src="../../assets/js/perfil.js"></script>
<?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>