<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../../view/auth/login.php');
    exit;
}

// 🔥 DEFINIR ROL
$esAdmin = ($_SESSION['usuario']['id_rol']);
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
            <div class="w-16 h-16 bg-sena text-white rounded-2xl flex items-center justify-center text-2xl font-bold">
                <span id="headerInicial">A</span>
            </div>
            <div>
                <h1 class="text-2xl font-semibold" id="headerNombre">Cargando...</h1>
                <span id="headerRol" class="text-xs bg-sena-soft px-3 py-1 rounded-full"></span>
            </div>
        </div>

        <button class="btnAbrirPerfil border px-4 py-2 rounded-xl">
            Editar Perfil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- IZQUIERDA -->
        <div class="lg:col-span-2 space-y-6">

            <!-- INFO -->
            <div class="bg-white p-6 rounded-2xl">
                <p id="infoNombre"></p>
                <p id="infoCorreo"></p>
            </div>

            <!-- 🔥 RESUMEN SEGÚN ROL -->
            
            <?php if ($esAdmin): ?>
            <!-- ADMIN -->
            <div class="bg-white p-6 rounded-2xl">
                <h3>Resumen de la plataforma</h3>
                <div class="grid grid-cols-2 gap-4 mt-4">

                    <div>
                        <p>Perfiles</p>
                        <p id="statPerfiles">0</p>
                    </div>

                    <div>
                        <p>Empresas</p>
                        <p id="statEmpresas">0</p>
                    </div>

                    <div>
                        <p>Administradores</p>
                        <p id="statAdministradores">0</p>
                    </div>

                    <div>
                        <p>Usuarios</p>
                        <p id="statUsuarios">0</p>
                    </div>

                </div>
            </div>

            <?php else: ?>
            <!-- EMPRESA -->
            <div class="bg-white p-6 rounded-2xl">
                <h3>Resumen de tu empresa</h3>

                <div id="resumenContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <p>Perfiles</p>
                        <p id="statPerfiles">0</p>
                    </div>

                    <div>
                        <p>Programas</p>
                        <p id="statProgramas">0</p>
                    </div>

                    <div>
                        <p>Sugerencias</p>
                        <p id="statSugerencias">0</p>
                    </div>

                </div>
            </div>
            <?php endif; ?>

            <!-- PERFILES -->
            <div class="bg-white p-6 rounded-2xl">
                <h3>Perfiles recientes</h3>
                <div id="listaPerfiles"></div>
            </div>

        </div>

        <!-- DERECHA -->
        <div class="space-y-6">

            <!-- ESTADÍSTICAS -->
            <div class="bg-white p-6 rounded-2xl">
                <h3>Estadísticas</h3>
                <div id="listaEstadisticas"></div>
            </div>

            <div class="bg-white p-6 rounded-2xl">
                <p id="fechaRegistro"></p>
            </div>

        </div>

    </div>

</div>

<script src="../../assets/js/perfil.js"></script>
<?php include __DIR__ . '../../../includes/footer.php'; ?>
</body>
</html>