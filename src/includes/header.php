<?php
// Helper: determine current page (basename of path) and provide nav classes for active state.
// If a caller sets $activePage before including this file, that will be used instead.
$current = isset($activePage) ? $activePage : basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));

function nav_classes(string $target): string {
  global $current;

  $base = 'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200';

  $default = 'text-sena-text-muted hover:bg-sena-soft hover:text-sena';

  // Active style
  $active = 'bg-sena-soft text-sena-strong';

  return $base . ' ' . ($current === $target ? $active : $default);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tecnologias Emergentes - SENA Observatorio Tecnologico</title>

  <!-- 🌐 GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />

  <!-- 🎨 Tailwind CSS Compilado -->
  <link rel="stylesheet" href="../../../assets/css/output.css">

</head>

<body class="font-[Inter] antialiased bg-white text-sena-text-main">

  <!-- HEADER -->
  <header class="sticky top-0 z-50 border-b border-sena-border bg-white/95 backdrop-blur-sm gap-2 shadow-sm">
    <div class="max-w-7xl mx-auto grid grid-cols-[auto_1fr_auto] items-center h-16 px-4">

      <!-- LOGOS -->
      <a href="../../view/landing/landing.php" class="flex items-center">
        <div class="flex items-center gap-3 pr-4 mr-4">
          <div class="flex items-center gap-3">
            <img src="../../assets/img/logo-tecnnova.jpeg" alt="logo-tecnnova" class="h-16 w-auto block">
            <span class="block w-px h-7 bg-sena-border" aria-hidden="true"></span>
            <img src="../../assets/img/logo-sena-verde-complementario-png-2022.png" alt="logo-sena-verde" class="h-7 w-auto block">
          </div>
          <div>
            <p class="font-[Montserrat] text-sm font-bold leading-tight">
              SENA
            </p>
          </div>
        </div>
      </a>

      <!-- NAV -->
      <nav class="flex items-center gap-2 justify-self-center">
        <a href="../../view/landing/landing.php" class="<?= nav_classes('landing.php') ?>">Inicio</a>
        <a href="busqueda.html" class="<?= nav_classes('busqueda.html') ?>">Perfiles</a>
        <a href="busqueda.html" class="<?= nav_classes('busqueda.html') ?>">Lineas Tecnologicas</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Tecnologias Emergentes</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Gestión Perfiles</a>
        <a href="tecnologias-emergentes.html" class="<?= nav_classes('tecnologias-emergentes.html') ?>">Historial</a>
      </nav>

      <!-- BOTONES -->
      <div class="flex items-center gap-3">
        <a href="login.html"
           class="inline-flex items-center px-4 py-1.5 rounded-md text-sm font-medium btn-sena-outline transition-colors duration-200">
           Iniciar Sesion
        </a>

        <a href="login.html"
           class="inline-flex items-center px-4 py-1.5 rounded-md text-sm font-medium btn-sena transition-colors duration-200">
           Registrarse
        </a>
      </div>

    </div>
  </header>

</body>
</html>