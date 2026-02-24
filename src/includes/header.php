<?php
// Helper: determine current page (basename of path) and provide nav classes for active state.
// If a caller sets $activePage before including this file, that will be used instead.
$current = isset($activePage) ? $activePage : basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
function nav_classes(string $target): string {
  global $current;
  $base = 'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200';
  $default = 'text-[#6b756e] hover:bg-[#e4f0e4] hover:text-[#1a2620]';
  // Active style: light green rounded background; use site's existing text color for the label
  $active = 'bg-[#e4f0e4] text-[#1a7a3a]';
  return $base . ' ' . ($current === $target ? $active : $default);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tecnologias Emergentes - SENA Observatorio Tecnologico</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-[Inter] antialiased">
  <!-- HEADER -->
  <header class="sticky top-0 z-50 border-b border-[#e2e5e2] bg-white/95 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto grid grid-cols-[auto_1fr_auto] items-center h-16 px-4">
      <a href="../../view/landing/landing.php" class="flex items-center">
        <div class="flex items-center gap-3 pr-4 mr-4">
          <div class="flex items-center gap-3">
            <img src="../../assets/img/logo-tecnnova.jpeg" alt="logo-tecnnova" class="h-16 w-auto block">
            <span class="block w-px h-7 bg-[#e2e5e2]" aria-hidden="true"></span>
            <img src="../../assets/img/logo-sena-verde-complementario-png-2022.png" alt="logo-sena-verde" class="h-7 w-auto block">
          </div>
          <div>
            <p class="font-[Montserrat] text-sm font-bold leading-tight text-[#1a2620]">SENA</p>
          </div>
        </div>
      </a>
      <nav class="flex items-center gap-2 justify-self-center">
        <a href="../../view/landing/landing.php" class="<?= nav_classes('landing.php') ?>">Inicio</a>
        <a href="busqueda.html" class="<?= nav_classes('busqueda.html') ?>">Perfiles</a>
        <a href="busqueda.html" class="<?= nav_classes('busqueda.html') ?>">Lineas Tecnologicas</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Tecnologias Emergentes</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Gestión Perfiles</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Historial</a>
      </nav>
      <div class="flex items-center gap-3">
        <a href="login.html" class="inline-flex items-center px-4 py-1.5 border border-[#00a300]/30 rounded-md text-sm font-medium text-[#1a2620] bg-transparent hover:bg-[#e4f0e4] transition-colors duration-200">Iniciar Sesion</a>
        <a href="login.html" class="inline-flex items-center px-4 py-1.5 border border-[#00a300]/30 rounded-md text-sm font-medium text-[#fff] bg-[#29a802] hover:bg-[#228b23] transition-colors duration-200">Registrarse</a>
      </div>
    </div>
  </header>
</body>
</html>