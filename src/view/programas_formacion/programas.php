<?php
include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_deshabilitar_perfiles.php';
include __DIR__ . '/modal_habilitar_perfiles.php';           
include __DIR__ . '/modal_habilitado_confirmacion.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_pefiles.php';
include __DIR__ . '/modal_editar_programa.php';
include __DIR__ . '/modal_crear_programa.php';

// Simulación de datos (luego puedes traerlos de BD)
$programas = [
    [
        "codigo" => "PF-001",
        "nivel" => "Tecnologo",
        "modalidad" => "Presencial",
        "nombre" => "Desarrollo de Software",
        "cupos" => 30,
        "inicio" => "28 de feb de 2026",
        "fin" => "31 de ago de 2027"
    ],
    [
        "codigo" => "PF-002",
        "nivel" => "Tecnologo",
        "modalidad" => "Presencial",
        "nombre" => "Analisis y Desarrollo de Sistemas de Informacion",
        "cupos" => 25,
        "inicio" => "14 de abr de 2026",
        "fin" => "14 de oct de 2027"
    ],
    [
        "codigo" => "PF-003",
        "nivel" => "Tecnico",
        "modalidad" => "Presencial",
        "nombre" => "Mantenimiento de Equipos de Computo",
        "cupos" => 20,
        "inicio" => "31 de ene de 2026",
        "fin" => "31 de ene de 2027"
    ],
    [
        "codigo" => "PF-004",
        "nivel" => "Tecnico",
        "modalidad" => "Virtual",
        "nombre" => "Programacion de Software",
        "cupos" => 40,
        "inicio" => "30 de abr de 2026",
        "fin" => "30 de abr de 2027"
    ],
    [
        "codigo" => "PF-005",
        "nivel" => "Operario",
        "modalidad" => "Presencial",
        "nombre" => "Operacion de Maquinaria Industrial",
        "cupos" => 15,
        "inicio" => "14 de mar de 2026",
        "fin" => "14 de sept de 2026"
    ],
    [
        "codigo" => "PF-006",
        "nivel" => "Auxiliar",
        "modalidad" => "Virtual",
        "nombre" => "Soporte Tecnico en Redes",
        "cupos" => 35,
        "inicio" => "31 de may de 2026",
        "fin" => "31 de may de 2027"
    ],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Programas de Formación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-[82rem] mx-auto px-4 py-8 lg:px-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Programas de Formacion</h1>
            <p class="text-gray-500 text-sm">Programas de formacion disponibles en el SENA</p>
        </div>

        <button
            id="btn-abrir-crear-programa"
            class="bg-sena hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nuevo Programa
        </button>
    </div>

    <!-- Buscador -->
    <div class="mb-6 relative">
        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
        <input 
            type="text" 
            id="buscador"
            placeholder="Buscar por nombre o codigo..."
            class="w-full md:w-1/3 pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200"
        >
    </div>

    <!-- Cards -->
    <div id="contenedorProgramas" class="grid md:grid-cols-3 gap-6">

        <?php foreach($programas as $programa): ?>
            <div class="bg-white border p-5 rounded-xl shadow hover:shadow-md transition programa-card"
                data-nombre="<?= strtolower($programa['nombre']) ?>"
                data-codigo="<?= strtolower($programa['codigo']) ?>"
            >

                <!-- Top -->
                <div class="flex justify-between items-start mb-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-green-600"></i>
                    </div>

                    <div class="flex gap-2 text-gray-400">
                        <i 
                        data-lucide="edit"
                        class="w-4 h-4 hover:text-blue-500 cursor-pointer btn-editar-programa"

                        data-codigo="<?= $programa['codigo'] ?>"
                        data-cupos="<?= $programa['cupos'] ?>"
                        data-nombre="<?= $programa['nombre'] ?>"
                        data-nivel="<?= $programa['nivel'] ?>"
                        data-modalidad="<?= $programa['modalidad'] ?>"
                        data-fechainicio="<?= $programa['fecha_inicio'] ?>"
                        data-fechafin="<?= $programa['fecha_fin'] ?>"

                        ></i>

                        <i data-lucide="toggle-right" class="switch-sena active w-4 h-4 text-green-600 hover:text-green-700 cursor-pointer" title="Activo"></i>
                    </div>

                    
                </div>

                <!-- Etiquetas -->
                <div class="flex gap-2 my-2 flex-wrap">
                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700 flex items-center gap-1">
                        <i data-lucide="hash" class="w-3 h-3"></i>
                        <?= $programa['codigo'] ?>
                    </span>
                    
                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 flex items-center gap-1">
                        <i data-lucide="layers" class="w-3 h-3"></i>
                        <?= $programa['nivel'] ?>
                    </span>

                    <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 flex items-center gap-1">
                        <i data-lucide="radio" class="w-3 h-3"></i>
                        <?= $programa['modalidad'] ?>
                    </span>
                </div>

                <!-- Nombre -->
                <h3 class="font-semibold text-gray-800 mb-3">
                    <?= $programa['nombre'] ?>
                </h3>

                <!-- Info -->
                <div class="text-xs text-gray-500 flex flex-col gap-2">
                    
                    <div class="flex items-center gap-2 flex-wrap">
                        <i data-lucide="users" class="w-3 h-3"></i>
                        <span><?= $programa['cupos'] ?> cupos</span>

                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <?= $programa['inicio'] ?>
                        </span>
                        <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar-check" class="w-3 h-3"></i>
                            <?= $programa['fin'] ?>
                        </span>
                    </div>
                    <span>Area: Teleinformatica y telecomunicaciones</span>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>

<script>
    // Inicializar los iconos de Lucide
    lucide.createIcons();
</script>

<script src="../../assets/js/programas.js"></script>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>