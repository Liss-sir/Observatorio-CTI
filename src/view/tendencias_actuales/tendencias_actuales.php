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
        <h2 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Tendencias Actuales</h2>
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
  </main>

  <!-- Template para tarjeta de tendencia -->
  <template id="template-tendencia">
    <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer">
      <div class="flex items-start justify-between mb-2">
        <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 6.75h2.25c.621 0 1.125.504 1.125 1.125v12.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 8.625 20.625V7.875c0-.621.504-1.125 1.125-1.125ZM16.5 3.75h2.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 15.375 20.625V4.875c0-.621.504-1.125 1.125-1.125Z" />
          </svg>
        </div>
        <div class="flex items-center gap-1">
          <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
              <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
            </svg>
          </button>
          <div class="switch-sena" title="Activo"></div>
        </div>
      </div>
      <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1"></h3>
      <p class="text-xs text-sena-text-soft line-clamp-2"></p>
    <div class="mt-2 text-xs text-sena-text-soft">
      <hr class="my-1 border-sena-border mb-2">
      <div class="flex items-center gap-2">
        <span class="estado-bolita w-2 h-2 rounded-full flex-shrink-0"></span>
        <span class="badge-area"></span>
      </div>
    </div>
    </div>
  </template>

  
  <!-- Script de funcionalidad -->
  <script src="../../assets/js/tendencias_actuales/tendencias_actuales.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>