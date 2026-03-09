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
    <link rel="stylesheet" href="../../../assets/css/output.css">
    <link rel="stylesheet" href="../../assets/css/globals.css">
</head>
<body class="bg-gray-100">

<div class="max-w-[82rem] mx-auto px-4 py-8 lg:px-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Programas de Formacion</h1>
            <p class="text-gray-500 text-sm">Programas de formacion disponibles en el SENA</p>
        </div>

        
    </div>

    <!-- Buscador -->
    <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="buscador" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar por nombre o codigo">
        </div>
        <button
            id="btn-abrir-crear-programa"
            class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nuevo Programa
        </button>
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
                    <div class="bg-sena-soft p-2 rounded-lg">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-green-600"></i>
                    </div>

                    <div class="flex gap-2 text-gray-400">
                        <button class="btn-editar-programa p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" 
                                data-codigo="<?= $programa['codigo'] ?>"
                                data-cupos="<?= $programa['cupos'] ?>"
                                data-nombre="<?= $programa['nombre'] ?>"
                                data-nivel="<?= $programa['nivel'] ?>"
                                data-modalidad="<?= $programa['modalidad'] ?>"
                                data-fechainicio="<?= $programa['fecha_inicio'] ?>"
                                data-fechafin="<?= $programa['fecha_fin'] ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                            </svg>
                        </button>

                        <!-- Contenedor para el switch que mantiene la clase y el evento -->
                        <div class="switch-sena active cursor-pointer" title="Activo">
                            <i data-lucide="toggle-right" class="w-4 h-4 text-green-600 hover:text-green-700"></i>
                        </div>
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
                        <span class="flex items-center"><?= $programa['cupos'] ?> cupos</span>
                        <span class="text-gray-300 mx-2">|</span>                        
                        <span class="flex items-center">Area: Teleinformatica y telecomunicaciones</span>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
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