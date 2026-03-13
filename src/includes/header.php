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

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />

  <!-- Tailwind CSS Compilado -->
  <link rel="stylesheet" href="../../../assets/css/output.css">

  <!-- Estilos adicionales -->
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
  </style>
</head>

<body class="font-[Inter] antialiased bg-white text-sena-text-main">

  <!-- HEADER -->
  <header class="sticky top-0 z-50 border-b border-sena-border bg-white/95 backdrop-blur-sm gap-2 shadow-sm">
    <div class="max-w-8xl mx-auto grid grid-cols-[auto_1fr_auto] items-center h-16 px-4 w-full">

      <!-- LOGOS -->
      <a href="../../view/landing/landing.php" class="flex items-center justify-self-start">
        <div class="flex items-center gap-3 pr-4 mr-4">
          <div class="flex items-center gap-3">
            <img src="../../assets/img/logo-tecnnova.png" alt="logo-tecnnova" class="h-16 w-auto block">
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

      <!-- NAV con menú desplegable -->
      <nav class="flex items-center gap-2 justify-self-center whitespace-nowrap">
        <a href="../../view/landing/landing.php" class="<?= nav_classes('landing.php') ?>">Inicio</a>
        <a href="../../view/perfiles/perfiles.php" class="<?= nav_classes('perfiles.php') ?>">Perfiles</a>

        <!-- NUEVO: Inicio del submenú de Programas (idéntico al de Tendencias) -->
        <!-- Menú desplegable Programas Formación -->
        <div class="relative group" id="menu-programas">
          
          <!-- Contenedor con hover unificado -->
          <div class="flex items-center rounded-md hover:bg-sena-soft transition-colors duration-200">

            <a href="../../view/programas_formacion/programas.php"
               class="px-3 py-2 rounded-l-md text-sm font-medium text-sena-text-muted hover:text-sena transition-colors duration-200">
              Programas Formación
            </a>

            <button id="btn-menu-programas"
                    type="button"
                    class="px-1 py-2 rounded-r-md text-sena-text-muted hover:text-sena transition-colors duration-200">
              <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180"
                   id="svg-menu-programas"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7">
                </path>
              </svg>
            </button>

          </div>

          <!-- ZONA DE SEGURIDAD: Puente invisible que conecta el menú con el submenú -->
          <div class="zona-seguridad hidden group-hover:block"></div>
          
          <!-- Submenú - CON SEPARACIÓN JUSTA pero navegable -->
          <div class="absolute w-64 hidden bg-white rounded-xl overflow-hidden shadow-xl z-50 border border-gray-100 animate-fadeIn"
               id="submenu-programas"
               style="left: -2rem;">
            
            <!-- Flechita superior decorativa -->
            <div class="absolute -top-2 left-4 w-4 h-4 bg-white transform rotate-45 border-t border-l border-gray-100"></div>
            
            <div class="relative bg-white">
              
              <!-- Divisor -->
              <div class="border-t border-gray-100 mx-4"></div>
              
              <!-- Área  -->
              <a href="../../view/areas/areas.php"
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena transition-all duration-200 group/item">
                
                <span class="w-8 h-8 bg-sena-soft/50 rounded-lg flex items-center justify-center text-sena group-hover/item:bg-sena group-hover/item:text-white transition-colors duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </span>

                <div>
                  <p class="font-medium">Área</p>
                  <p class="text-xs text-gray-500">Gestionar disciplinas técnicas</p>
                </div>

              </a>

              <!-- Puedes añadir más áreas aquí, copiando el mismo patrón y recordando añadir los divisores necesarios -->

            </div>
          </div>

        </div>
        <!-- NUEVO: Fin del submenú de Programas -->
        
        <!-- Menú desplegable Tendencias Actuales -->
        <div class="relative group" id="menu-tendencias">
          
          <!-- Contenedor con hover unificado -->
          <div class="flex items-center rounded-md hover:bg-sena-soft transition-colors duration-200">

            <a href="../../view/tendencias_actuales/tendencias_actuales.php"
               class="px-3 py-2 rounded-l-md text-sm font-medium text-sena-text-muted hover:text-sena transition-colors duration-200">
              Tendencias Actuales
            </a>

            <button id="btn-menu-tendencias"
                    type="button"
                    class="px-1 py-2 rounded-r-md text-sena-text-muted hover:text-sena transition-colors duration-200">
              <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180"
                   id="svg-menu-tendencias"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7">
                </path>
              </svg>
            </button>

          </div>

          <!-- ZONA DE SEGURIDAD: Puente invisible que conecta el menú con el submenú -->
          <div class="zona-seguridad hidden group-hover:block"></div>
          
          <!-- Submenú - CON SEPARACIÓN JUSTA pero navegable -->
          <div class="absolute w-64 hidden bg-white rounded-xl overflow-hidden shadow-xl z-50 border border-gray-100 animate-fadeIn"
               id="submenu-tendencias"
               style="left: -2rem;">
            
            <!-- Flechita superior decorativa -->
            <div class="absolute -top-2 left-4 w-4 h-4 bg-white transform rotate-45 border-t border-l border-gray-100"></div>
            
            <div class="relative bg-white">
              
              <!-- Tecnologías Emergentes -->
              <a href="../../view/tecnologias_emergentes/tecnologias_emergentes.php"
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena transition-all duration-200 group/item">
                
                <span class="w-8 h-8 bg-sena-soft/50 rounded-lg flex items-center justify-center text-sena group-hover/item:bg-sena group-hover/item:text-white transition-colors duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                  </svg>
                </span>

                <div>
                  <p class="font-medium">Tecnologías Emergentes</p>
                  <p class="text-xs text-gray-500">Innovación y vanguardia</p>
                </div>

              </a>
              
              <!-- Divisor -->
              <div class="border-t border-gray-100 mx-4"></div>
              
              <!-- Proyección a Futuro -->
              <a href="../proyeccion_futuro/proyeccion_futuro.php"
                 class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-sena-soft hover:text-sena transition-all duration-200 group/item">
                
                <span class="w-8 h-8 bg-sena-soft/50 rounded-lg flex items-center justify-center text-sena group-hover/item:bg-sena group-hover/item:text-white transition-colors duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                  </svg>
                </span>

                <div>
                  <p class="font-medium">Proyección a Futuro</p>
                  <p class="text-xs text-gray-500">Tendencias 2026-2036</p>
                </div>

              </a>

            </div>
          </div>

        </div>

        <a href="../../view/lineas_tecnologicas/lineas_tecnologicas.php"
               class="px-3 py-2 rounded-l-md text-sm font-medium text-sena-text-muted hover:text-sena transition-colors duration-200">
              Lineas Tecnologicas
          </a>

        <a href="../../view/gestion_usuarios/gestion_usuarios.php" class="<?= nav_classes('gestion_usuarios.php') ?>">Gestión Perfiles</a>
        <a href="sugerencias.html" class="<?= nav_classes('sugerencias.html') ?>">Sugerencias</a>
        <a href="../../view/historial/historial.php" class="<?= nav_classes('historial.php') ?>">Historial</a>
      </nav>

      <!-- BOTONES -->
      <div class="flex items-center gap-3 justify-self-end">
        <a href="../../auth/login/login.php"
           class="inline-flex items-center px-4 py-1.5 rounded-md text-sm font-medium border border-sena text-sena hover:bg-sena-soft transition-colors duration-200">
           Iniciar Sesion
        </a>

        <a href="../../auth/login/register.php"
           class="inline-flex items-center px-4 py-1.5 rounded-md text-sm font-medium bg-sena text-white hover:bg-sena-strong transition-colors duration-200">
           Registrarse
        </a>
      </div>

    </div>
  </header>

  <!-- Script para manejar menú fijo con click + hover -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- Lógica para Tendencias (ya existente) ---
      const btnMenuTendencias = document.getElementById('btn-menu-tendencias');
      const menuTendencias = document.getElementById('menu-tendencias');
      const submenuTendencias = document.getElementById('submenu-tendencias');
      
      // NUEVO: Obtener elementos para Programas
      const btnMenuProgramas = document.getElementById('btn-menu-programas');
      const menuProgramas = document.getElementById('menu-programas');
      const submenuProgramas = document.getElementById('submenu-programas');
      
      const zonaSeguridad = document.querySelector('.zona-seguridad'); // Nota: Esto solo selecciona el primero. Para que sea más preciso, deberías usar IDs o clases específicas. Lo dejamos así para que no se complique, pero funcionará si el HTML es consistente.
      
      let clickActivoTendencias = false;
      let hoverActivoTendencias = false;
      
      // NUEVO: Estados para Programas
      let clickActivoProgramas = false;
      let hoverActivoProgramas = false;
      
      let timeoutId = null;

      // Función para actualizar visibilidad del menú de Tendencias
      function actualizarMenuTendencias() {
        if (clickActivoTendencias || hoverActivoTendencias) {
          menuTendencias.classList.add('menu-fijo');
        } else {
          menuTendencias.classList.remove('menu-fijo');
        }
      }

      // NUEVO: Función para actualizar visibilidad del menú de Programas
      function actualizarMenuProgramas() {
        if (clickActivoProgramas || hoverActivoProgramas) {
          menuProgramas.classList.add('menu-fijo');
        } else {
          menuProgramas.classList.remove('menu-fijo');
        }
      }

      // Función para activar hover (genérica, requiere el menú específico)
      function activarHover(menu, setHover, actualizarFunc) {
        if (timeoutId) {
          clearTimeout(timeoutId);
          timeoutId = null;
        }
        // Desactivar el click del otro menú si es necesario? Mejor mantenerlos independientes.
        // if (!clickActivo) {
          setHover(true);
          actualizarFunc();
        // }
      }

      // Función para desactivar hover con delay (requiere elementos específicos)
      function desactivarHover(menu, submenu, zona, setHover, actualizarFunc, clickActivo) {
        if (timeoutId) {
          clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
          // Comprueba si el mouse está sobre ALGÚN elemento relevante de ESTE menú
          const estaSobreEsteMenu = (menu && menu.matches(':hover')) || 
                                      (submenu && submenu.matches(':hover')) || 
                                      (zona && zona.matches(':hover'));
          
          if (!clickActivo && !estaSobreEsteMenu) {
            setHover(false);
            actualizarFunc();
          }
          timeoutId = null;
        }, 100); // Reducido a 100ms para respuesta más rápida
      }

      // --- Eventos para Tendencias (adaptados) ---
      if (btnMenuTendencias && menuTendencias && submenuTendencias) {
        const zonaSeguridadTendencias = menuTendencias.querySelector('.zona-seguridad');

        btnMenuTendencias.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          clickActivoTendencias = !clickActivoTendencias;
          
          if (clickActivoTendencias) {
            hoverActivoTendencias = false;
            if (timeoutId) {
              clearTimeout(timeoutId);
              timeoutId = null;
            }
          }
          
          actualizarMenuTendencias();
        });

        [menuTendencias, submenuTendencias, zonaSeguridadTendencias].forEach(element => {
          if (element) {
            element.addEventListener('mouseenter', () => activarHover(menuTendencias, (val) => hoverActivoTendencias = val, actualizarMenuTendencias));
            element.addEventListener('mouseleave', () => desactivarHover(menuTendencias, submenuTendencias, zonaSeguridadTendencias, (val) => hoverActivoTendencias = val, actualizarMenuTendencias, clickActivoTendencias));
          }
        });

        // Cerrar menú fijo al hacer click en enlaces
        submenuTendencias.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', function() {
            clickActivoTendencias = false;
            hoverActivoTendencias = false;
            actualizarMenuTendencias();
          });
        });
      }

      // --- NUEVO: Eventos para Programas (duplicado y adaptado) ---
      if (btnMenuProgramas && menuProgramas && submenuProgramas) {
        const zonaSeguridadProgramas = menuProgramas.querySelector('.zona-seguridad');

        btnMenuProgramas.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          clickActivoProgramas = !clickActivoProgramas;
          
          if (clickActivoProgramas) {
            hoverActivoProgramas = false;
            if (timeoutId) {
              clearTimeout(timeoutId);
              timeoutId = null;
            }
          }
          
          actualizarMenuProgramas();
        });

        [menuProgramas, submenuProgramas, zonaSeguridadProgramas].forEach(element => {
          if (element) {
            element.addEventListener('mouseenter', () => activarHover(menuProgramas, (val) => hoverActivoProgramas = val, actualizarMenuProgramas));
            element.addEventListener('mouseleave', () => desactivarHover(menuProgramas, submenuProgramas, zonaSeguridadProgramas, (val) => hoverActivoProgramas = val, actualizarMenuProgramas, clickActivoProgramas));
          }
        });

        // Cerrar menú fijo al hacer click en enlaces
        submenuProgramas.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', function() {
            clickActivoProgramas = false;
            hoverActivoProgramas = false;
            actualizarMenuProgramas();
          });
        });
      }

      // Cerrar menús fijos al hacer click fuera (mejorado para ambos)
      document.addEventListener('click', function(e) {
        // Para Tendencias
        if (menuTendencias && !menuTendencias.contains(e.target) && 
            !submenuTendencias.contains(e.target) && 
            clickActivoTendencias) {
          clickActivoTendencias = false;
          actualizarMenuTendencias();
        }
        
        // Para Programas
        if (menuProgramas && !menuProgramas.contains(e.target) && 
            !submenuProgramas.contains(e.target) && 
            clickActivoProgramas) {
          clickActivoProgramas = false;
          actualizarMenuProgramas();
        }
      });

    });
  </script>

</body>
</html>