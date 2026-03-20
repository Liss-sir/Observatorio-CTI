<?php
include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_crear_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_crear_te.php';
include __DIR__ . '/modal_editar_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_editar_te.php';
include __DIR__ . '/modal_detalle_tecnologia_emergente.php'; 
include __DIR__ . '/modal_deshabilitar_te.php';
include __DIR__ . '/modal_confirmacion_dehabilitar_te.php';
include __DIR__ . '/modal_habilitar_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_habilitar_te.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tecnologías Emergentes - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">

  <style>
    /* Estilos para los toasts */
    .toast-validation {
      min-width: 320px;
      max-width: 400px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      pointer-events: auto;
      animation: slideInToast 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      overflow: hidden;
      border-left: 4px solid;
    }

    .toast-validation.warning { border-left-color: #FFB020; }
    .toast-validation.error { border-left-color: #EF4444; }
    .toast-validation.success { border-left-color: #39A900; }
    .toast-validation.info { border-left-color: #3B82F6; }

    .toast-contenido {
      display: flex;
      align-items: center;
      padding: 16px;
      gap: 12px;
    }

    .toast-icono-wrapper { flex-shrink: 0; }
    .toast-icono {
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .toast-validation.warning .toast-icono { color: #FFB020; }
    .toast-validation.error .toast-icono { color: #EF4444; }
    .toast-validation.success .toast-icono { color: #39A900; }
    .toast-validation.info .toast-icono { color: #3B82F6; }

    .toast-icono svg {
      width: 20px;
      height: 20px;
      stroke-width: 2.5;
    }

    .toast-mensaje-wrapper {
      flex: 1;
      min-width: 0;
    }

    .toast-titulo {
      font-size: 14px;
      font-weight: 600;
      color: #1E293B;
      line-height: 1.4;
      margin-bottom: 2px;
    }

    .toast-mensaje {
      font-size: 13px;
      color: #64748B;
      line-height: 1.4;
      word-wrap: break-word;
    }

    .toast-validation.exit {
      animation: slideOutToast 0.2s ease forwards;
    }

    @keyframes slideInToast {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutToast {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }

    /* Forzar que el toast esté por encima de todo */
    #toast-container {
      position: fixed !important;
      top: 20px !important;
      right: 20px !important;
      z-index: 999999 !important;
    }

    .estado-bolita {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 9999px;
      flex-shrink: 0;
    }

    .estado-bolita.bg-sena {
      background-color: #39A900;
    }

    .estado-bolita.bg-gray-300 {
      background-color: #D1D5DB;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- Toast container para alertas -->
  <div id="toast-container" class="fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none"></div>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- TECNOLOGÍAS EMERGENTES CONTENT -->
      <div class="section-heading mb-6 animate-title-in">
        <h2 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Tecnologías Emergentes</h2>
        <p class="text-sm text-sena-text-soft mt-1">Transformación digital y nuevas dinámicas del sector productivo risaraldense.</p>
      </div>

      <!-- BARRA DE BÚSQUEDA Y BOTÓN CREAR -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1 animate-search-in">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="buscador-tecnologias" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar tecnologías...">
        </div>
        <button id="btn-crear-tendencia" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear Tecnología Emergente
        </button>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong id="total-tecnologias" class="font-medium text-sena-text-main">0</strong> tecnologías emergentes encontradas</p>

          <div id="contenedor-tecnologias" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Las tarjetas se cargarán dinámicamente con JavaScript -->
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Script para controlar los modales -->
  <script src="../../assets/js/tecnologias_emergentes/tecnologias_emergentes.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>