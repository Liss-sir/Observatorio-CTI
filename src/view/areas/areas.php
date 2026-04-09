<?php include __DIR__ . '../../../includes/header.php'; ?>

<main class="flex-1">
    <link rel="stylesheet" href="../../../assets/css/output.css">
    <link rel="stylesheet" href="../../assets/css/globals.css">
    <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
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

        <!-- BUSCADOR Y BOTÓN CREAR -->
        <div class="flex items-center gap-3 mb-6">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" id="buscador-areas" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar áreas...">
            </div>
            <button id="btn-crear-area" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Crear Área
            </button>
        </div>

        <!-- CONTADOR DE RESULTADOS -->
        <p class="text-sm text-gray-500 mb-4" id="contador-areas">Cargando áreas...</p>

        <!-- GRID AREAS (CONTENEDOR VACÍO) -->
        <!-- Es vital que tenga estas clases exactas para que el JS lo encuentre -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="contenedor-areas-grid">
            <!-- El contenido se generará automáticamente mediante JavaScript al cargar la página -->
            <!-- Si hay error, el JS mostrará un mensaje aquí -->
        </div>

        <div id="paginacion-areas" class="flex justify-center mt-6 gap-2"></div>
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
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

<!-- Incluir el script corregido (Asegúrate que la ruta sea correcta) -->
<script src="../../assets/js/areas/areas.js"></script>

<?php include __DIR__ . '../../../includes/footer.php'; ?>
