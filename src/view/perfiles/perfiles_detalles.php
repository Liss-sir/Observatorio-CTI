<?php
include __DIR__ . '../../../includes/header.php'; 
include __DIR__ . '/modal_editar_perfiles.php'; 
include __DIR__ . '/modal_deshabilitar_perfiles.php';
include __DIR__ . '/modal_habilitar_perfiles.php';
include __DIR__ . '/modal_habilitado_confirmacion.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_pefiles.php';
include __DIR__ . '/modal_editar_confirmacion.php';

// Obtener el ID del perfil de la URL
$id_perfil = $_GET['id'] ?? null;

// Obtener el ID del usuario de la sesión
$id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['id_usuario'] ?? $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;
$nombre_usuario = $_SESSION['nombre'] ?? $_SESSION['nombre_usuario'] ?? '';
$rol_usuario = $_SESSION['rol_nombre'] ?? $_SESSION['rol'] ?? $_SESSION['id_rol'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cargando perfil... - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <link rel="stylesheet" href="../../assets/css/perfilesOcupacionales/perfiles.css">
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
  <style>
    .hidden {
      display: none !important;
    }
    .skeleton {
      animation: pulse 1.5s ease-in-out infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    #modal-editar-perfil, #modal-deshabilitar-perfil, #modal-habilitar-perfil,
    #modal-habilitado-confirmacion, #modal-deshabilitado-perfil, #modal-editado-confirmacion {
      transition: opacity 0.2s ease;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- Back Link -->
      <a href="javascript:history.back()" class="inline-flex items-center gap-1.5 text-sm text-sena-text-soft hover:text-sena-text-main transition-colors mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Volver a resultados
      </a>

      <!-- Skeleton Loader -->
      <div id="skeleton-loader" class="skeleton">
        <div class="h-8 bg-gray-200 rounded w-1/3 mb-4"></div>
        <div class="h-32 bg-gray-200 rounded mb-4"></div>
        <div class="h-32 bg-gray-200 rounded"></div>
      </div>

      <!-- Profile Content (hidden initially) -->
      <div id="profile-content" class="hidden">
        <!-- Profile Header -->
        <div class="mb-8">
          <div class="flex flex-wrap items-center gap-3 mb-3">
            <span id="linea-badge" class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena-soft text-sena-strong">Cargando...</span>
            <span id="estado-badge" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
              <span id="estado-texto">Cargando...</span>
            </span>
          </div>
          <div class="flex items-start justify-between gap-6 flex-wrap">
            <h1 id="perfil-nombre" class="text-2xl lg:text-3xl font-bold text-sena-text-main">Cargando...</h1>
            <div class="flex items-center gap-3 flex-shrink-0">
              <button id="btn-editar-perfil" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena-border rounded-lg bg-white text-sena-text-main hover:bg-sena-soft transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                Editar
              </button>
              <button id="btn-cambiar-estado" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-lg bg-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/></svg>
                <span id="btn-estado-texto">Cargando...</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Content Grid -->
        <div class="grid gap-6 lg:grid-cols-[2fr_1fr] items-start">

          <!-- Columna Izquierda -->
          <div class="flex flex-col gap-6">
            <!-- Main Content Card - Descripción -->
            <div class="border border-sena-border rounded-xl bg-white p-6">
              <h2 class="text-xs font-semibold uppercase tracking-wider text-sena-text-soft mb-3">DESCRIPCION DEL PERFIL</h2>
              <p id="perfil-descripcion" class="text-sm leading-relaxed text-sena-text-main">Cargando...</p>
            </div>

            <!-- Cupos Card -->
            <div class="border border-sena-border rounded-xl bg-white p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Cupos disponibles
              </h3>
              <p id="cupos-valor" class="text-sm font-medium text-sena-text-main">Cargando...</p>
            </div>

            <!-- Nivel de Formación Card -->
            <div class="border border-sena-border rounded-xl bg-white p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                Nivel de Formación
              </h3>
              <p id="nivel-nombre" class="text-sm text-sena-text-main">Cargando...</p>
            </div>
          </div>

          <!-- Sidebar Info Cards - Columna Derecha -->
          <div class="flex flex-col gap-4">

            <!-- Empresa -->
            <div class="border border-sena-border rounded-xl bg-white p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                Empresa
              </h3>
              <p id="empresa-nombre" class="text-sm font-medium text-sena-text-main">Cargando...</p>
            </div>

            <!-- Linea Tecnologica -->
            <div class="border border-sena-border rounded-xl bg-white p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                Linea Tecnologica
              </h3>
              <p id="linea-nombre" class="text-sm text-sena-text-main">Cargando...</p>
            </div>

            <!-- Programa de Formación (comentado) -->
            <!-- <div class="border border-sena-border rounded-xl bg-white p-5">... </div> -->

            <!-- Fechas - Solo Creación -->
            <div class="border border-sena-border rounded-xl bg-white p-5">
              <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                Fecha de Creación
              </h3>
              <div class="flex items-baseline gap-3">
                <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Creacion</span>
                <span id="fecha-creacion" class="text-sm font-medium text-sena-text-main">Cargando...</span>
              </div>
            </div>

            <!-- Vigencia Card -->
            <div id="vigencia-card" class="border rounded-xl p-5 flex items-center gap-3">
              <!-- El color y contenido se actualizarán dinámicamente -->
            </div>

          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- Variables de configuración para JavaScript -->
  <script>
    window.SENA_CONFIG = {
      API_BASE_URL: '../../../controllers/PerfilOcupacionalController.php',
      PERFIL_ID: <?php echo json_encode($id_perfil); ?>,
      USUARIO_ID: <?php echo json_encode($id_usuario); ?>,
      USUARIO_NOMBRE: <?php echo json_encode($nombre_usuario); ?>,
      USUARIO_ROL: <?php echo json_encode($rol_usuario); ?>
    };
  </script>
  
  <script src="../../assets/js/perfiles/perfiles.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>