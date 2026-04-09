<?php
include __DIR__ . '../../../includes/header.php'; 
include __DIR__ . '/modal_crear_perfiles.php';
include __DIR__ . '/modal_editar_perfiles.php'; 
include __DIR__ . '/modal_deshabilitar_perfiles.php';
include __DIR__ . '/modal_habilitar_perfiles.php';           
include __DIR__ . '/modal_habilitado_confirmacion.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_pefiles.php';
include __DIR__ . '/modal_editar_confirmacion.php';
include __DIR__ . '/modal_creado_confirmacion.php';

// Obtener el ID del usuario de la sesión - PRUEBA CON DIFERENTES NOMBRES
$id_usuario = $_SESSION['usuario_id'] ?? 
              $_SESSION['id_usuario'] ?? 
              $_SESSION['user_id'] ?? 
              $_SESSION['id'] ?? 
              $_SESSION['idusuario'] ?? 
              null;

$nombre_usuario = $_SESSION['nombre'] ?? $_SESSION['nombre_usuario'] ?? $_SESSION['username'] ?? '';
$rol_usuario = $_SESSION['rol'] ?? $_SESSION['id_rol'] ?? $_SESSION['rol_usuario'] ?? '';

// Si aún es null, intenta obtenerlo de otra manera (ej: desde una cookie o función de autenticación)
if (!$id_usuario) {
    // Aquí puedes poner una función que obtenga el usuario logueado de tu sistema
    // Por ejemplo: $id_usuario = obtenerUsuarioLogueado();
}

// Debug
echo "<!-- DEBUG SESSION: ";
var_dump(array_keys($_SESSION)); // Muestra solo las claves
echo " -->";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SENA - Administrador Perfiles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/style_toast_alert.css">
  <link rel="stylesheet" href="../../assets/css/perfilesOcupacionales/perfiles.css">
  <!-- <link rel="stylesheet" href="../../assets/css/globals.css"> -->
</head>

<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- Search Bar + Action Buttons -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" id="search-perfiles" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar perfiles...">
        </div>
        <a href="javascript:void(0)" id="btn-mis-creaciones" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg bg-white whitespace-nowrap h-10 hover:bg-sena-soft transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Mis creaciones
        </a>
        <button data-permiso="crear_perfil" id="btn-nuevo-perfil" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Nuevo Perfil
        </button>
      </div>

      <!-- Content Layout -->
      <div class="flex gap-8">

        <!-- Sidebar Filters -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
          <div class="sticky top-24 border border-sena-border rounded-xl bg-white p-5">
            <h3 class="text-sm font-semibold text-sena-text-main mb-4">Filtros</h3>

            <!-- Estado -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Estado</span>
              <div class="relative">
                <select id="filtro-estado" class="w-full h-10 pl-3 pr-8 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main appearance-none focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none cursor-pointer">
                  <option value="todos">Todos</option>
                  <option value="activos">Activos</option>
                  <option value="inactivos">Inactivos</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </div>
            </div>

            <!-- Linea Tecnologica - Se cargará dinámicamente -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Linea Tecnologica</span>
              <div id="filtros-lineas" class="flex flex-col gap-2.5">
                <!-- Las líneas tecnológicas se cargarán aquí dinámicamente -->
                <div class="text-sm text-sena-text-soft">Cargando líneas...</div>
              </div>
            </div>

            <!-- Tecnologia Emergente - Se cargará dinámicamente -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Tecnologia Emergente</span>
              <div id="filtros-tecnologias" class="flex flex-col gap-2.5">
                <!-- Las tecnologías emergentes se cargarán aquí dinámicamente -->
                <div class="text-sm text-sena-text-soft">Cargando tecnologías...</div>
              </div>
            </div>

            <!-- Tendencia Actual - Se cargará dinámicamente -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Tendencia Actual</span>
              <div id="filtros-tendencias" class="flex flex-col gap-2.5">
                <div class="text-sm text-sena-text-soft">Cargando tendencias...</div>
              </div>
            </div>

            <!-- Proyección a Futuro - Se cargará dinámicamente -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Proyección a Futuro</span>
              <div id="filtros-proyecciones" class="flex flex-col gap-2.5">
                <div class="text-sm text-sena-text-soft">Cargando proyecciones...</div>
              </div>
            </div>

            <!-- Botón aplicar filtros -->
            <button id="btn-aplicar-filtros" class="w-full mt-4 px-4 py-2 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity">
              Aplicar filtros
            </button>
            <button id="btn-limpiar-filtros" class="w-full mt-2 px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-colors">
              Limpiar filtros
            </button>
          </div>
        </aside>

        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4">
            <strong id="contador-perfiles" class="font-medium text-sena-text-main">0</strong> 
            perfiles encontrados
          </p>

          <!-- Contenedor de tarjetas de perfiles (se llena dinámicamente) -->
          <div id="perfiles-container" class="flex flex-col gap-3">
            <!-- Skeletons de carga -->
            <div class="skeleton-card border border-sena-border rounded-lg bg-white p-4">
              <div class="h-5 bg-gray-200 rounded w-1/3 mb-2"></div>
              <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            </div>
            <div class="skeleton-card border border-sena-border rounded-lg bg-white p-4">
              <div class="h-5 bg-gray-200 rounded w-1/3 mb-2"></div>
              <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            </div>
            <div class="skeleton-card border border-sena-border rounded-lg bg-white p-4">
              <div class="h-5 bg-gray-200 rounded w-1/3 mb-2"></div>
              <div class="h-4 bg-gray-200 rounded w-1/2"></div>
            </div>
          </div>

          <!-- Paginación -->
          <div id="paginacion" class="flex justify-center items-center gap-2 mt-6">
            <!-- Se generará dinámicamente -->
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- Variables de configuración para JavaScript -->
  <script>
    window.SENA_CONFIG = {
      API_BASE_URL: '../../../controllers/PerfilOcupacionalController.php',
      USUARIO_ID: <?php echo json_encode($id_usuario); ?>,
      USUARIO_NOMBRE: <?php echo json_encode($nombre_usuario); ?>,
      USUARIO_ROL: <?php echo json_encode($rol_usuario); ?>
    };
  </script>
  
  <script src="../../assets/js/perfiles/perfiles.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>