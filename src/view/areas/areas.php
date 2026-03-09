<?php include __DIR__ . '../../../includes/header.php';?>

<main class="flex-1">
<link rel="stylesheet" href="../../../assets/css/output.css">
<link rel="stylesheet" href="../../assets/css/globals.css">
<script src="https://unpkg.com/lucide@latest"></script>
<div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">
    <!-- TITULO -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Áreas</h1>
            <p class="text-gray-500 text-sm">
                Gestión de las áreas de los programas de formación.
            </p>
        </div>
    </div>

    <!-- BUSCADOR -->
    <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar tendencias...">
        </div>
        <button id="btn-crear-area" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Crear Área
        </button>
    </div>

    <p class="text-sm text-gray-500 mb-4">9 áreas encontradas</p>

    <!-- GRID AREAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- AREA 1 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="1">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Producción</h3>
            <p class="text-sm text-gray-500">
                Área encargada de la fabricación y transformación de materias primas en productos terminados.
            </p>
        </div>

        <!-- AREA 2 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="2">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Logística</h3>
            <p class="text-sm text-gray-500">
                Gestión del transporte, almacenamiento y distribución de productos y materiales.
            </p>
        </div>

        <!-- AREA 3 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="3">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Recursos Humanos</h3>
            <p class="text-sm text-gray-500">
                Administración del talento humano, contratación, capacitación y bienestar laboral.
            </p>
        </div>

        <!-- AREA 4 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="4">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Tecnología e Innovación</h3>
            <p class="text-sm text-gray-500">
                Desarrollo e implementación de soluciones tecnológicas para mejorar los procesos empresariales.
            </p>
        </div>

        <!-- AREA 5 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm" data-id="5">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-sena-soft p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button class="btn-editar-area p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar área">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                        </svg>
                    </button>
                    <div class="switch-sena active" title="Activo"></div>
                </div>
            </div>
            <h3 class="font-semibold">Ventas y Comercialización</h3>
            <p class="text-sm text-gray-500">
                Estrategias de venta, distribución comercial y relaciones con clientes.
            </p>
        </div>
    </div>
</div>
<!-- MODALES -->
<?php include 'modal_crear_area.php'; ?>
<?php include 'modal_editar_area.php'; ?>
<?php include 'modal_detalle_area.php'; ?>
<?php include 'modal_deshabilitar_area.php'; ?>
<?php include 'modal_habilitar_area.php'; ?>
<?php include 'modal_creado_area.php'; ?>
<?php include 'modal_editado_area.php'; ?>
<?php include 'modal_deshabilitado_area.php'; ?>
<?php include 'modal_habilitado_area.php'; ?>
</main>

<script>
    lucide.createIcons();
</script>

<!-- Incluir el nuevo script de áreas -->
<script src="/observatorio/Observatorio-CTI/src/assets/js/areas.js"></script>

<?php include __DIR__ . '../../../includes/footer.php'; ?>