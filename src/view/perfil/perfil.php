<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../../view/auth/login.php');
    exit;
}

$esAdmin = ($_SESSION['usuario']['id_rol'] == 1);
?>

<?php include './modal_editar_perfil.php'; ?>
<?php include __DIR__ . '../../../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../../../assets/css/output.css">
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

            <div class="flex flex-col gap-1"> 
                <h1 class="text-2xl font-semibold text-gray-800" id="headerNombre">Cargando...</h1>
                <span id="headerRol" class="text-xs bg-sena-soft text-green-700 px-3 py-1 rounded-full w-fit"></span>
            </div>
        </div>

        <button class="btnAbrirPerfil border border-sena-strong px-4 py-2 rounded-xl hover:bg-sena-soft hover:text-sena transition flex items-center gap-2 mb-2">
            <i data-lucide="edit" class="w-5 h-5 text-green-600"></i>
            Editar Perfil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- IZQUIERDA -->
        <div class="lg:col-span-2 space-y-6">

            <!-- INFO -->
            <div class="bg-white p-6 rounded-2xl shadow border">
                
                <div class="flex items-center gap-2 mb-4">
                    <h3 class="text-base font-light text-gray-500">Información</h3>
                </div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="user" class="w-5 h-5 text-green-600"></i>
                    <p id="infoEmpresa" class="font-medium text-gray-800">Empresa</p>
                </div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="mail" class="w-5 h-5 text-green-600"></i>
                    <p id="infoNombre" class="font-medium text-gray-800"></i>
                </div>
                
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="mail" class="w-5 h-5 text-green-600"></i>
                    <p id="infoCorreo" class="font-medium text-gray-800"></i>
                </div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="user" class="w-5 h-5 text-green-600"></i>
                    <p id="infoDoc" class="font-medium text-gray-800">CC - 12345678</p>
                </div>

                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="user" class="w-5 h-5 text-green-600"></i>
                    <p id="infoRazon" class="font-medium text-gray-800"></p>
                </div>
            </div>

            <!-- RESUMEN -->
            <div class="bg-white p-6 rounded-2xl shadow border">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    <?= $esAdmin ? 'Resumen de la plataforma' : 'Resumen de tu empresa' ?>
                </h3>

                <div id="resumenContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">

                    <!-- PERFILES -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="file-text" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Perfiles</p>
                            <p id="statPerfiles" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <?php if ($esAdmin): ?>

                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="building" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Empresas</p>
                            <p id="statEmpresas" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="shield" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Administradores</p>
                            <p id="statAdministradores" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Usuarios</p>
                            <p id="statUsuarios" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <?php else: ?>

                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="book-open" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Programas</p>
                            <p id="statProgramas" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="lightbulb" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sugerencias</p>
                            <p id="statSugerencias" class="text-2xl font-semibold">0</p>
                        </div>
                    </div>

                    <?php endif; ?>

                </div>
            </div>

            <!-- PERFILES -->
            <div class="bg-white p-6 rounded-2xl shadow border">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Perfiles recientes</h3>
                <div id="listaPerfiles" class="space-y-2"></div>
            </div>

        </div>

        <!-- DERECHA -->
        <div class="space-y-6">

            <!-- ESTADÍSTICAS -->
            <div class="bg-white p-6 rounded-2xl shadow border">
                
                <!-- 🔥 ICONO -->
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                    <h3 class="text-lg font-semibold text-gray-700">Estadísticas</h3>
                </div>

                <div id="listaEstadisticas" class="space-y-2 text-sm text-gray-600"></div>
            </div>

            <!-- ACTIVIDAD -->
            <div class="bg-white p-6 rounded-2xl shadow border text-sm text-gray-500">
                
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="clock" class="w-4 h-4 text-green-600"></i>
                    <span class="font-medium text-gray-600">Actividad</span>
                </div>

                <p id="fechaRegistro"></p>
            </div>

        </div>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

<script src="../../assets/js/perfil.js"></script>
<?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>