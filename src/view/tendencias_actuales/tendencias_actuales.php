<?php
include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_editar_tendencias_act.php';
include __DIR__ . '/modal_confirmacion_editar_tendencias_act.php';
include __DIR__ . '/modal_dehabilitar_tendencia_act.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_tendencia_act.php';
include __DIR__ . '/modal_habilitar_tendencia_act.php';
include __DIR__ . '/modal_confirmacion_habilitar_ta.php';
include __DIR__ . '/modal_crear_tendencia_actual.php';
include __DIR__ . '/modal_confirmacion_crear_tendencia_actual.php';
include __DIR__ . '/modal_detalle_tendencia_act.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tendencias Actuales - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

<div id="toast-container" class="fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none"></div>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- TENDENCIAS ACTUALES CONTENT -->
      <div class="section-heading mb-6">
        <h2 class="text-2xl font-bold text-sena-text-main">Tendencias Actuales</h2>
        <p class="text-sm text-sena-text-soft mt-1">Innovaciones tecnológicas emergentes que influyen en los perfiles y necesidades del sector productivo risaraldense.</p>
      </div>

      <!-- BARRA DE BÚSQUEDA Y BOTÓN CREAR -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="buscador-tendencias" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar tendencias...">
        </div>
        <button data-permiso="crear_tendencia" id="btn-crear-tendencia" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear Tendencia Actual
        </button>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong id="total-tendencias" class="font-medium text-sena-text-main">0</strong> tendencias encontradas</p>

          <div id="contenedor-tendencias" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Las tarjetas se cargarán dinámicamente -->
          </div>
        </div>
      </div>
    </div>

      <!-- Contenedor para la paginación -->
    <div id="paginacion-container" class="hidden"></div>
    
  </main>

  <!-- Script de funcionalidad -->
  <script src="../../assets/js/tendencias_actuales/tendencias_actuales.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>