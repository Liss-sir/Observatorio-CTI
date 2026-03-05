<?php include __DIR__ . '../../../includes/header.php'; ?>

<div class="p-6">
    <!-- TITULO -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Áreas</h1>
            <p class="text-gray-500 text-sm">
                Gestión de las áreas organizacionales del sector productivo.
            </p>
        </div>
        <button
            id="btn-crear-area"
            class="bg-[#39A900] hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Crear Área
        </button>
    </div>

    <!-- BUSCADOR -->
    <div class="mb-4">
        <div class="relative w-[420px]">
            <input
                type="text"
                placeholder="Buscar áreas..."
                class="w-full border rounded-lg py-2 px-4 pl-10 bg-gray-100">
            <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
        </div>
    </div>

    <p class="text-sm text-gray-500 mb-4">
        9 áreas encontradas
    </p>

    <!-- GRID AREAS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- AREA 1 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer hover:text-green-600"></i>
                    <i data-lucide="toggle-right" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <h3 class="font-semibold">Producción</h3>
            <p class="text-sm text-gray-500">
                Área encargada de la fabricación y transformación de materias primas en productos terminados.
            </p>
        </div>

        <!-- AREA 2 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer hover:text-green-600"></i>
                    <i data-lucide="toggle-right" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <h3 class="font-semibold">Logística</h3>
            <p class="text-sm text-gray-500">
                Gestión del transporte, almacenamiento y distribución de productos y materiales.
            </p>
        </div>

        <!-- AREA 3 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer hover:text-green-600"></i>
                    <i data-lucide="toggle-left" class="w-6 h-6 text-gray-400"></i>
                </div>
            </div>
            <h3 class="font-semibold">Recursos Humanos</h3>
            <p class="text-sm text-gray-500">
                Administración del talento humano, contratación, capacitación y bienestar laboral.
            </p>
        </div>

        <!-- AREA 4 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer hover:text-green-600"></i>
                    <i data-lucide="toggle-right" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <h3 class="font-semibold">Tecnología e Innovación</h3>
            <p class="text-sm text-gray-500">
                Desarrollo e implementación de soluciones tecnológicas para mejorar los procesos empresariales.
            </p>
        </div>

        <!-- AREA 5 -->
        <div class="border rounded-xl p-5 bg-white shadow-sm">
            <div class="flex justify-between items-start mb-3">
                <div class="bg-green-100 p-2 rounded-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="pencil" class="w-4 h-4 cursor-pointer hover:text-green-600"></i>
                    <i data-lucide="toggle-right" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <h3 class="font-semibold">Ventas y Comercialización</h3>
            <p class="text-sm text-gray-500">
                Estrategias de venta, distribución comercial y relaciones con clientes.
            </p>
        </div>
    </div>
</div>

<script src="/observatorio/Observatorio-CTI/src/assets/js/programas.js"></script>
<?php include __DIR__ . '../../../includes/footer.php'; ?>
<?php include __DIR__ . 'modalCrearArea.php'; ?>