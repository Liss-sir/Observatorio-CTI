<?php
$usuario = [
    'nombre' => 'Administrador SENA',
    'correo' => 'admin@sena.edu.co',
    'rol' => 'admin'
];

$empresa = [
    'nombre' => 'TechColombia S.A.S',
    'nit' => '900.123.456-7',
    'telefono' => '+57 301 234 5678'
];

$estadisticas = [
    'total' => 2,
    'vigentes' => 2,
    'expirados' => 0
];

$perfiles = [
    ['nombre' => 'Desarrollador Full Stack Senior', 'estado' => 'vigente'],
    ['nombre' => 'Especialista en Blockchain', 'estado' => 'vigente']
];

$estadisticas = [
    'programas' => 12,
    'areas' => 6,
    'lineas' => 7,
    'tecnologias' => 10,
    'tendencias' => 4,
    'proyeccion' => 3,
    'sugerencias' => 5
];
?>
<?php include 'modal_editar_perfil.php'; ?>
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
                <?= strtoupper($usuario['nombre'][0]) ?>
            </div>

            <div>
                <h1 class="text-2xl font-semibold"><?= $usuario['nombre'] ?></h1>

                <div class="flex gap-2 mt-2">
                    <?php if($usuario['rol'] === 'empresa'): ?>
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Empresa</span>
                        <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">
                            <?= $empresa['nombre'] ?>
                        </span>
                    <?php else: ?>
                        <span class="bg-sena-soft text-sena-strong text-xs px-3 py-1 rounded-full font-medium">Administrador</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <button class="btnAbrirPerfil flex items-center gap-2 border border-sena px-4 py-2 rounded-xl hover:bg-sena-soft hover:text-sena-strong shadow-sm">
            <i data-lucide="pencil" class="w-4 h-4 text-sena  "></i>
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
                        <?= $usuario['nombre'] ?>
                    </div>

                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                        <?= $usuario['correo'] ?>
                    </div>
                </div>
            </div>

            <!-- RESUMEN DE LA PLATAFORMA -->
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
                            <p class="text-2xl font-semibold"><?= $estadisticas['programas'] ?></p>
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
                            <p class="text-2xl font-semibold"><?= $estadisticas['areas'] ?></p>
                            <p class="text-xs text-gray-400">registradas</p>
                        </div>
                    </div>

                    <!-- Administradores -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="shield" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Administradores</p>
                            <p class="text-2xl font-semibold"><?= $estadisticas['tendencias'] ?></p>
                            <p class="text-xs text-gray-400">activos</p>
                        </div>
                    </div>

                    <!-- Usuarios -->
                    <div class="border rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-sena-soft p-2 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Usuarios</p>
                            <p class="text-2xl font-semibold"><?= $estadisticas['sugerencias'] ?></p>
                            <p class="text-xs text-gray-400">totales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EMPRESA -->
            <?php if($usuario['rol'] === 'empresa'): ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs text-gray-400 uppercase mb-4">Empresa</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3">
                        <i data-lucide="building" class="w-4 h-4 text-gray-400"></i>
                        <?= $empresa['nombre'] ?>
                    </div>

                    <div class="flex items-center gap-3">
                        <i data-lucide="file-text" class="w-4 h-4 text-gray-400"></i>
                        <?= $empresa['nit'] ?>
                    </div>

                    <div class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        <?= $empresa['telefono'] ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- PERFILES -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs text-gray-400 uppercase mb-4">Perfiles</h2>

                <?php foreach($perfiles as $p): ?>
                <div class="flex justify-between items-center border-b py-3 last:border-none">
                    <div class="flex items-center gap-3">
                        <i data-lucide="briefcase" class="w-4 h-4 text-gray-400"></i>
                        <span><?= $p['nombre'] ?></span>
                    </div>

                    <span class="estado px-3 py-1 text-xs rounded-full bg-sena-soft text-green-700 font-medium">
                        <?= ucfirst($p['estado']) ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- DERECHA -->
        <div class="space-y-6">

            <!-- ESTADISTICAS -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                    Estadísticas
                </h3>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Programas de formación</span><span><?= $estadisticas['programas'] ?></span></div>
                    <div class="flex justify-between"><span>Áreas</span><span><?= $estadisticas['areas'] ?></span></div>
                    <div class="flex justify-between"><span>Líneas Tec.</span><span><?= $estadisticas['lineas'] ?></span></div>
                    <div class="flex justify-between"><span>Tec. Emergentes</span><span><?= $estadisticas['tecnologias'] ?></span></div>
                    <div class="flex justify-between"><span>Tendencias actuales</span><span><?= $estadisticas['tendencias'] ?></span></div>
                    <div class="flex justify-between"><span>Proyección a futuro</span><span><?= $estadisticas['proyeccion'] ?></span></div>
                    <div class="flex justify-between"><span>Sugerencias</span><span><?= $estadisticas['sugerencias'] ?></span></div>
                </div>
            </div>

            <!-- ACTIVIDAD -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="font-semibold mb-2 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                    Actividad
                </h3>
                <p class="text-sm text-gray-500">Miembro desde febrero 2026</p>
            </div>

            <!-- CUENTA ADMIN -->
            <div class="bg-gray-50 border rounded-2xl p-6">
                <div class="flex items-center gap-3">
                    <i data-lucide="shield" class="w-5 h-5 text-green-700"></i>
                    <div>
                        <h4 class="font-semibold text-sm">Cuenta Administrador</h4>
                        <p class="text-xs text-gray-500">Acceso completo a la plataforma</p>
                    </div>
                </div>
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