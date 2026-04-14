<script src="../../assets/js/auth.js"></script>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

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

require_once __DIR__ . '/../helpers/permisos.php';

// Obtener rol actual para mostrar/ocultar elementos
$rolActual = obtenerRolActual();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Observatorio CTI - SENA</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="../../assets/img/logo-sena-verde-complementario-png-2022.png">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sena: '#39a900',
            'sena-strong': '#2d8a00',
            'sena-soft': '#f0fdf4',
            'sena-text': '#166534'
          }
        }
      }
    }
  </script>

  <style>
    /* Asegurar que el grupo funcione correctamente */
    .group:hover .group-hover\:block {
      display: block;
    }
    
    .group:hover .group-hover\:rotate-180 {
      transform: rotate(180deg);
    }

    /* Animación para el submenú */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-5px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-fadeIn {
      animation: fadeIn 0.15s ease-out;
    }

    /* Estilos para el menú fijo por click */
    .menu-fijo #submenu-tendencias {
      display: block !important;
    }

    .menu-fijo #svg-menu-tendencias {
      transform: rotate(180deg);
    }

    /* NUEVO: Estilos para el menú fijo de Programas */
    .menu-fijo #submenu-programas {
      display: block !important;
    }

    .menu-fijo #svg-menu-programas {
      transform: rotate(180deg);
    }

    /* SOLUCIÓN: Menú con separación justa */
    #menu-tendencias {
      position: relative;
    }

    /* Zona de seguridad que conecta el menú con el submenú - MÁS PEQUEÑA */
    .zona-seguridad {
      position: absolute;
      height: 15px; /* Reducido de 30px a 15px */
      width: 100%;
      top: 100%;
      left: 0;
      background: transparent;
      pointer-events: auto;
      z-index: 40;
    }

    /* El submenú ahora se posiciona con la separación justa */
    #submenu-tendencias {
      margin-top: 15px; /* Mismo valor reducido */
    }

    /* Mantener visible con hover */
    #menu-tendencias:hover #submenu-tendencias,
    #submenu-tendencias:hover,
    .menu-fijo #submenu-tendencias {
      display: block !important;
    }

    /* Asegurar que la zona de seguridad también mantiene el hover */
    #menu-tendencias:hover .zona-seguridad {
      display: block;
    }

    /* NUEVO: Estilos específicos para el menú de Programas (análogo a Tendencias) */
    #menu-programas {
      position: relative;
    }

    #submenu-programas {
      margin-top: 15px;
    }

    #menu-programas:hover #submenu-programas,
    #submenu-programas:hover,
    .menu-fijo #submenu-programas {
      display: block !important;
    }

    #menu-programas:hover .zona-seguridad {
      display: block;
    }

    /* ===== ESTILOS RESPONSIVE - SOLO PARA MÓVIL ===== */
    .hamburger-btn {
      display: none;
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 0;
    }

    .hamburger-btn svg {
      width: 28px;
      height: 28px;
      stroke: #374151;
      stroke-width: 1.5;
    }

    .mobile-overlay {
      display: none;
    }

    @media (max-width: 768px) {
      .hamburger-btn {
        display: block;
        margin-left: auto;
        margin-right: 1rem;
      }

      #main-nav {
        position: fixed;
        top: 64px;
        left: -280px;
        width: 280px;
        height: calc(100vh - 64px);
        background: white;
        flex-direction: column;
        align-items: stretch;
        padding: 1rem;
        transition: left 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 1000;
        overflow-y: auto;
        gap: 0.5rem !important;
        justify-self: start !important;
      }

      #main-nav.mobile-open {
        left: 0;
      }

      #main-nav > a,
      #main-nav .menu-container {
        width: 100%;
      }

      .mobile-overlay {
        display: none;
        position: fixed;
        top: 64px;
        left: 0;
        width: 100%;
        height: calc(100vh - 64px);
        background: rgba(0,0,0,0.5);
        z-index: 999;
      }

      .mobile-overlay.active {
        display: block;
      }

      /* Los submenús en móvil se muestran en bloque */
      .submenu {
        position: static !important;
        width: 100% !important;
        margin-top: 0.5rem !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
      }

      .zona-seguridad {
        display: none !important;
      }
    }
  </style>
</head>

<body id="body" class="opacity-0 font-[Inter] antialiased bg-white text-gray-800 transition-opacity duration-200">

  <!-- HEADER -->
  <header class="sticky top-0 z-50 border-b border-gray-200 bg-white/95 backdrop-blur-sm shadow-sm">
    <div class="max-w-8xl mx-auto grid grid-cols-[auto_1fr_auto] items-center h-16 px-4 w-full">

      <!-- LOGOS -->
      <a href="../../view/landing/landing.php" class="flex items-center gap-3">
        <img src="../../assets/img/logo-tecnnova.png" alt="Tecnnnova" class="h-12 w-auto">
        <span class="w-px h-6 bg-gray-300"></span>
        <img src="../../assets/img/logo-sena-verde-complementario-png-2022.png" alt="SENA" class="h-6 w-auto">
      </a>

      <!-- Botón Hamburguesa (solo visible en móvil) -->
      <button class="hamburger-btn" id="hamburgerBtn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Overlay para cerrar menú -->
      <div class="mobile-overlay" id="mobileOverlay"></div>

      <!-- NAVEGACIÓN -->
      <nav class="flex items-center gap-1 justify-self-center whitespace-nowrap" id="main-nav">
        
        <!-- PÚBLICO: Inicio (todos pueden ver) -->
        <a href="../../view/landing/landing.php" class="<?= nav_classes('landing.php') ?>">
          Inicio
        </a>

        <!-- PÚBLICO: Perfiles (todos pueden ver) -->
        <a href="../../view/perfiles/perfiles.php" class="<?= nav_classes('perfiles.php') ?>">
          Perfiles
        </a>

        <?php if (tienePermiso('ver_programas')): ?>
        <!-- PROTEGIDO: Menú Programas Formación -->
          <div class="relative group menu-container <?= (in_array($current, ['programas.php','areas.php'])) ? 'bg-sena-soft text-sena-strong rounded-md' : '' ?>" id="menu-programas" data-permiso="ver_programas">
              <div class="flex items-center rounded-md hover:bg-sena-soft hover:text-sena transition">
                  <button id="btn-menu-programas-text" type="button" class="px-3 py-2 rounded-l-md text-sm font-medium text-gray-600 hover:text-sena">
                      Programas Formación
                  </button>
                  <button id="btn-menu-programas" type="button" class="btn-toggle px-2 py-2 rounded-r-md text-gray-400 hover:text-green-700" data-permiso="ver_menu_programas_admin">
                      <svg class="w-3 h-3 chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                      </svg>
                  </button>
              </div>
              <div class="zona-seguridad hidden group-hover:block"></div>
              <div id="submenu-programas" class="submenu hidden absolute left-0 mt-2 w-60 bg-white rounded-lg shadow-xl border border-gray-100 z-50 animate-fadeIn">
                  <a href="../../view/programas_formacion/programas.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena">
                      <span class="w-8 h-8 bg-sena-soft rounded-lg flex items-center justify-center text-green-600">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                          </svg>
                      </span>
                      <div>
                          <p class="font-medium">Programas Formación</p>
                          <p class="text-xs text-gray-500">Explorar programas</p>
                      </div>
                  </a>
                  <?php if (tienePermiso('ver_areas')): ?>
                  <div class="border-t border-gray-100 mx-3"></div>
                  <a href="../../view/areas/areas.php" data-permiso="ver_areas" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena">
                      <span class="w-8 h-8 bg-sena-soft rounded-lg flex items-center justify-center text-green-600">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                          </svg>
                      </span>
                      <div>
                          <p class="font-medium">Áreas</p>
                          <p class="text-xs text-gray-500">Areas de conocimiento</p>
                      </div>
                  </a>
                  <?php endif; ?>
              </div>
          </div>
        <?php endif; ?>

        <?php if (tienePermiso('ver_tendencias')): ?>
        <!-- PROTEGIDO: Menú Tendencias Actuales -->
        <div class="relative group menu-container <?= (in_array($current, ['tendencias_actuales.php','tecnologias_emergentes.php','proyeccion_futuro.php'])) ? 'bg-sena-soft text-sena rounded-md' : '' ?>" id="menu-tendencias">
          <div class="flex items-center rounded-md hover:bg-sena-soft transition">
            <button id="btn-menu-tendencias-text" type="button" class="px-3 py-2 rounded-l-md text-sm font-medium text-gray-600 hover:text-sena">
              Tendencias Actuales
            </button>
            <button  id="btn-menu-tendencias" type="button" class="btn-toggle px-2 py-2 rounded-r-md text-gray-400 hover:text-green-700">
              <svg class="w-3 h-3 chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
          </div>
          <div class="zona-seguridad hidden group-hover:block"></div>
          <div id="submenu-tendencias" class="submenu hidden absolute left-0 mt-2 w-60 bg-white rounded-lg shadow-xl border border-gray-100 z-50 animate-fadeIn">
            <a href="../../view/tendencias_actuales/tendencias_actuales.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena">
              <span class="w-8 h-8 bg-sena-soft rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </span>
              <div>
                <p class="font-medium">Tendencias Actuales</p>
                <p class="text-xs text-gray-500">Explorar tendencias</p>
              </div>
            </a>
            <?php if (tienePermiso('ver_tecnologias_emergentes')): ?>
            <div class="border-t border-gray-100 mx-3"></div>
            <a href="../../view/tecnologias_emergentes/tecnologias_emergentes.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena ">
              <span class="w-8 h-8 bg-sena-soft rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </span>
              <div>
                <p class="font-medium">Tecnologías Emergentes</p>
                <p class="text-xs text-gray-500">Innovación y vanguardia</p>
              </div>
            </a>
            <?php endif; ?>
            <?php if (tienePermiso('ver_proyeccion_futuro')): ?>
            <div class="border-t border-gray-100 mx-3"></div>
            <a href="../../view/proyeccion_futuro/proyeccion_futuro.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena rounded-lg">
              <span class="w-8 h-8 bg-sena-soft rounded-lg flex items-center justify-center text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </span>
              <div>
                <p class="font-medium">Proyección a Futuro</p>
                <p class="text-xs text-gray-500">Tendencias 2026-2036</p>
              </div>
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if (tienePermiso('ver_lineas_tecnologicas')): ?>
        <!-- PROTEGIDO: Líneas Tecnológicas -->
        <a href="../../view/lineas_tecnologicas/lineas_tecnologicas.php" class="<?= nav_classes('lineas_tecnologicas.php') ?>">
          Líneas Tecnológicas
        </a>
        <?php endif; ?>
        
        <?php if (tienePermiso('ver_sugerencias')): ?>
        <!-- PROTEGIDO: Sugerencias -->
        <a href="../../view/sugerencias/sugerencias.php" class="<?= nav_classes('sugerencias.php') ?>">
          Sugerencias
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('gestionar_usuarios')): ?>
        <!-- ADMIN: Gestión Perfiles -->
        <a href="../../view/gestion_usuarios/gestion_usuarios.php" 
           class="<?= nav_classes('gestion_usuarios.php') ?>" 
           data-permiso="gestionar_usuarios">
          Gestión Usuarios
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('ver_historial')): ?>
        <!-- ADMIN: Historial -->
        <a href="../../view/historial/historial.php" 
           class="<?= nav_classes('historial.php') ?>" 
           data-permiso="ver_historial">
          Historial
        </a>
        <?php endif; ?>

        <?php if (tienePermiso('ver_estadisticas')): ?>
        <a href="../../view/estadisticas/estadisticas.php" class="<?= nav_classes('estadisticas.php') ?>">
          Estadisticas
        </a>
        <?php endif; ?>

      </nav>

      <!-- BOTONES / MENÚ USUARIO -->
      <div class="flex items-center gap-3">
        
        <?php if (!estaAutenticado()): ?>
        <!-- INVITADO: Login/Register -->
        <div id="nav-guest" class="flex items-center gap-2">
          <a href="../../auth/login/login.php" class="px-4 py-2 text-sm font-medium text-green-700 border border-green-600 rounded-md hover:bg-green-50 transition">
            Iniciar Sesión
          </a>
          <a href="../../auth/login/register.php" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition">
            Registrarse
          </a>
        </div>
        <?php else: ?>
        <!-- USUARIO AUTENTICADO -->
        <div id="nav-user" class="flex items-center gap-3">
          

          <!-- Menú desplegable usuario -->
          <div class="relative">
            <button id="user-menu-btn" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-sena-soft transition">
              <div class="w-8 h-8 bg-sena-soft rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-sena-strong" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="text-left flex-shrink-0 min-w-0">
                <p id="user-name" class="text-sm font-medium text-gray-900 leading-none"><?= htmlspecialchars($_SESSION['nombre_empresa'] ?? 'Usuario') ?></p>
                <p id="user-role" class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($_SESSION['rol_nombre'] ?? 'Rol') ?></p>
              </div>
              <svg id="user-chevron" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Dropdown -->
            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-100 z-50">
              <div class="py-1">
                <a href="../../view/perfil/perfil.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-green-700">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Mi Perfil
                </a>
                
                <button class="btnAbrirPerfil w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-green-700">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                  Editar Perfil
                </button>

              <?php if (tienePermiso('crear_admin')): ?>
                  <a href="../../auth/login/register.php?admin=1"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-green-700">
                    
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3M9 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a7 7 0 00-7 7h7"/>
                    </svg>

                    Crear Admin
                  </a>
              <?php endif; ?>

                <div class="border-t border-gray-100 my-1"></div>

                <button onclick="Auth.logout()" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Cerrar Sesión
                </button>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>

    </div>
  </header>
<script src="../../assets/js/perfil.js"></script>

<?php include '../../view/perfil/modal_editar_perfil.php'; ?>
  <!-- Script para menús desplegables -->
  <script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== MENÚ HAMBURGUESA =====
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const mobileMenu = document.getElementById('main-nav');
  const mobileOverlay = document.getElementById('mobileOverlay');

  function toggleMobileMenu() {
    mobileMenu.classList.toggle('mobile-open');
    mobileOverlay.classList.toggle('active');
    document.body.style.overflow = mobileMenu.classList.contains('mobile-open') ? 'hidden' : '';
  }

  function closeMobileMenu() {
    mobileMenu.classList.remove('mobile-open');
    mobileOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (hamburgerBtn) {
    hamburgerBtn.addEventListener('click', toggleMobileMenu);
  }

  if (mobileOverlay) {
    mobileOverlay.addEventListener('click', closeMobileMenu);
  }

  // Cerrar menú al hacer click en un enlace (solo en móvil)
  document.querySelectorAll('#main-nav a').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
        closeMobileMenu();
      }
    });
  });

  // Cerrar menú al redimensionar a desktop
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      closeMobileMenu();
    }
  });

  function inicializarMenu(menuId) {
    const menu = document.getElementById(menuId);
    if (!menu) return;

    const btn = menu.querySelector('.btn-toggle');
    const btnText = menu.querySelector('[id$="-text"]');
    const submenu = menu.querySelector('.submenu');
    const zona = menu.querySelector('.zona-seguridad');

    if (!btn || !submenu) return;

    let activoPorClick = false;
    let activoPorHover = false;
    let timeout = null;

    function actualizar() {
      if (activoPorClick || activoPorHover) {
        menu.classList.add('menu-fijo');
      } else {
        menu.classList.remove('menu-fijo');
      }
    }

    function activarHover() {
      clearTimeout(timeout);
      activoPorHover = true;
      actualizar();
    }

    function desactivarHover() {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        if (!activoPorClick && !menu.matches(':hover')) {
          activoPorHover = false;
          actualizar();
        }
      }, 100);
    }

    // CLICK - en el botón de la flecha
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      // En móvil, simplemente mostrar/ocultar el submenú
      if (window.innerWidth <= 768) {
        submenu.classList.toggle('hidden');
        const chevron = btn.querySelector('.chevron');
        if (chevron) {
          chevron.style.transform = submenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
        return;
      }

      activoPorClick = !activoPorClick;

      if (activoPorClick) {
        activoPorHover = false;
      }

      actualizar();
    });

    // CLICK - en el texto del botón (abre el menú sin navegar)
    if (btnText) {
      btnText.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        // En móvil, mostrar/ocultar submenú
        if (window.innerWidth <= 768) {
          submenu.classList.toggle('hidden');
          const chevron = btn.querySelector('.chevron');
          if (chevron) {
            chevron.style.transform = submenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
          }
          return;
        }

        activoPorClick = !activoPorClick;

        if (activoPorClick) {
          activoPorHover = false;
        }

        actualizar();
      });
    }

    // HOVER (solo para desktop)
    if (window.innerWidth > 768) {
      [menu, submenu, zona].forEach(el => {
        if (!el) return;

        el.addEventListener('mouseenter', activarHover);
        el.addEventListener('mouseleave', desactivarHover);
      });
    }

    // CLICK EN OPCIÓN → cerrar
    submenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        activoPorClick = false;
        activoPorHover = false;
        actualizar();
        if (window.innerWidth <= 768) {
          submenu.classList.add('hidden');
          const chevron = btn.querySelector('.chevron');
          if (chevron) {
            chevron.style.transform = 'rotate(0deg)';
          }
          closeMobileMenu();
        }
      });
    });

    // CLICK FUERA 
    document.addEventListener('click', function (e) {
      if (window.innerWidth > 768) {
        if (
          menu &&
          submenu &&
          !menu.contains(e.target) &&
          !submenu.contains(e.target)
        ) {
          activoPorClick = false;
          actualizar();
        }
      }
    });
  }

  function inicializarUserDropdown() {
    const btnUser = document.getElementById('user-menu-btn');
    const dropdownUser = document.getElementById('user-dropdown');

    if (!btnUser || !dropdownUser) return;

    let abierto = false;

    btnUser.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      abierto = !abierto;

      if (abierto) {
        dropdownUser.classList.remove('hidden');
      } else {
        dropdownUser.classList.add('hidden');
      }
    });

    // Cerrar al hacer click fuera
    document.addEventListener('click', function (e) {
      if (
        !btnUser.contains(e.target) &&
        !dropdownUser.contains(e.target)
      ) {
        dropdownUser.classList.add('hidden');
        abierto = false;
      }
    });
  }

  // Inicializar ambos menús
  inicializarMenu('menu-tendencias');
  inicializarMenu('menu-programas');
  inicializarUserDropdown();

});
</script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('body').classList.remove('opacity-0');
  });
</script>

</body>
</html>