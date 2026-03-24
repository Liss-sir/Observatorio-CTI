<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al login si no hay sesión
    header('Location: ../../login.php');
    exit;
}

// Obtener el ID del usuario autenticado desde la sesión
$id_usuario_actual = $_SESSION['id_usuario'];

include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_crear_sugerencia.php';
include __DIR__ . '/modal_confirmar_crear_sugerencia.php';
include __DIR__ . '/modal_editar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_editar_sug.php';
include __DIR__ . '/modal_deshabilitar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_sug.php';
include __DIR__ . '/modal_habilitar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_habilitar_sug.php';
include __DIR__ . '/modal_ver_detalles_sugerencia.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Proyección a Futuro - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
  <link rel="stylesheet" href="../../assets/css/sugerencias/sugerencias.css">
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- Toast Container -->
  <div id="toast-container"></div>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- SUGERENCIAS INNOVADORAS CONTENT -->
      <div class="flex items-center justify-between mb-6">
        <div class="section-heading">
          <h2 class="text-2xl font-bold text-sena-text-main"><P>Sugerencias Innovadoras</P></h2>
          <p class="text-sm text-sena-text-soft mt-1">Propuestas innovadoras para fortalecer el desarrollo tecnológico Risaraldense.</p>
        </div>

        <!-- action Buttons -->
        <div class="flex items-center gap-3">
          <a href="../../view/perfiles/mis_creaciones_perfiles.php" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg bg-white whitespace-nowrap h-10 hover:bg-sena-soft transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Mis creaciones
          </a>
          <button id="btn-nuevo-perfil" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Nueva Sugerencia
          </button>
        </div>
      </div>

      <!-- FILTRO BONITO POR ESTADO CON CONTADORES -->
      <div class="filtro-container">
        <div class="filtro-botones">
          <button class="filtro-btn active" data-filtro="todos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="3" y1="9" x2="21" y2="9"></line>
              <line x1="3" y1="15" x2="21" y2="15"></line>
              <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            Todos
            <span class="contador-badge" id="contador-todos">0</span>
          </button>
          <button class="filtro-btn" data-filtro="activos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 16v-4M12 8h.01"></path>
            </svg>
            Activos
            <span class="contador-badge" id="contador-activos">0</span>
          </button>
          <button class="filtro-btn" data-filtro="inactivos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Inactivos
            <span class="contador-badge" id="contador-inactivos">0</span>
          </button>
        </div>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <!-- Contador de resultados actualizado -->
          <div class="resultados-count">
            <span class="resultados-badge" id="resultados-count">Cargando sugerencias...</span>
            <span class="text-xs text-sena-text-soft" id="filtro-activo">Mostrando todos</span>
          </div>

          <!-- Loader -->
          <div id="loader" class="loader"></div>

          <!-- Mensaje de error -->
          <div id="error-mensaje" class="hidden text-center py-8 text-red-600 bg-red-50 rounded-lg">
            Error al cargar las sugerencias. Por favor, intenta de nuevo.
          </div>

          <!-- Tarjetas container - Sin estilos de grid cuando está vacío -->
          <div id="tarjetas-container"></div>
          

        </div>
      </div>
    </div>

    <!-- Paginación -->
    <div id="paginacion-container"></div>
  </main>

  <!-- Script para pasar el ID del usuario autenticado a JavaScript -->
  <script>
    // Pasar el ID del usuario autenticado desde la sesión PHP a JavaScript
    window.idUsuarioActual = <?php echo json_encode($id_usuario_actual); ?>;
    console.log('Usuario autenticado ID:', window.idUsuarioActual);
  </script>
  
  <!-- Script principal -->
  <script src="../../assets/js/sugerencias/sugerencias.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>