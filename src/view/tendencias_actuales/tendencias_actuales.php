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
  
  <!-- Estilos para el switch personalizado (solo lo necesario) -->
  <style>
    .switch-sena {
      position: relative;
      width: 36px;
      height: 20px;
      background-color: #9ca3af;
      border-radius: 9999px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: inline-block;
    }
    .switch-sena::after {
      content: '';
      position: absolute;
      top: 2px;
      left: 2px;
      width: 16px;
      height: 16px;
      background-color: white;
      border-radius: 9999px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
      transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
    }
    .switch-sena.active {
      background-color: #39A900;
    }
    .switch-sena.active::after {
      transform: translateX(16px);
    }
    .switch-sena:active::after {
      width: 20px;
    }
    .switch-sena.active:active::after {
      transform: translateX(12px);
    }
    
    /* Animación para el modal de confirmación */
    @keyframes modalFadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
    .animate-modalFadeIn {
      animation: modalFadeIn 0.3s ease-out;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

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
          <input type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar tendencias...">
        </div>
        <button id="btn-crear-tendencia" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear Tendencia Actual
        </button>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong class="font-medium text-sena-text-main">12</strong> tendencias encontradas</p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Inteligencia Artificial -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="1">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Inteligencia Artificial</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Computación cuántica accesible desde la nube para desarrolladores y empresas, permitiendo experimentación real en problemas de optimización y criptografía.</p>
            </div>

            <!-- Blockchain -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="2">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Blockchain</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Tecnología de registro distribuido que permite crear sistemas descentralizados para transacciones seguras y transparentes.</p>
            </div>

            <!-- Internet de las Cosas (IoT) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="3">
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
                  <div class="switch-sena" title="Inactivo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Internet de las Cosas (IoT)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Red de dispositivos conectados que recopilan, comparten e intercambian datos en tiempo real para automatizar procesos.</p>
            </div>

            <!-- Computacion en la Nube -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="4">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Computacion en la Nube</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Entrega de servicios informáticos a través de internet, permitiendo acceso flexible a recursos y aplicaciones bajo demanda.</p>
            </div>

            <!-- Ciberseguridad Avanzada -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="5">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Ciberseguridad Avanzada</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Protección integral contra ciberataques mediante tecnologías avanzadas y estrategias defensivas para sistemas críticos.</p>
            </div>

            <!-- Big Data y Analitica -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="6">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Big Data y Analitica</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Procesamiento y análisis de grandes volúmenes de datos para obtener insights valiosos que impulsen decisiones estratégicas.</p>
            </div>

            <!-- Realidad Aumentada/Virtual -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="7">
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
                  <div class="switch-sena" title="Inactivo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Realidad Aumentada/Virtual</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Tecnologías inmersivas que superponen información digital en el mundo real o crean entornos virtuales para experiencias interactivas.</p>
            </div>

            <!-- Robotica Colaborativa -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="8">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Robotica Colaborativa</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Robots diseñados para trabajar junto a personas en entornos compartidos, mejorando productividad y seguridad laboral.</p>
            </div>

            <!-- Manufactura Aditiva (Impresion 3D) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="9">
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
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Manufactura Aditiva (Impresion 3D)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Tecnología de fabricación que construye objetos capa por capa, revolucionando prototipos, personalizacion y producción.</p>
            </div>

            <!-- Automatizacion de Procesos (RPA) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" data-id="10">
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
                  <div class="switch-sena" title="Inactivo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Automatizacion de Procesos (RPA)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Automatización de tareas repetitivas mediante software que simula acciones humanas, aumentando eficiencia y reduciendo costos.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Script unificado para modales -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // ===== VARIABLES DE MODALES =====
    const modalEditar = document.getElementById('modal-editar-tendencia');
    const modalEditadoConfirmacion = document.getElementById('modal-editado-confirmacion');
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-tendencias-act');
    const modalDeshabilitadoConfirmacion = document.getElementById('modal-deshabilitado-tendencia-act');
    const modalHabilitar = document.getElementById('modal-habilitar-tendencia');
    const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion-tendencia_act');
    const modalCrear = document.getElementById('modal-crear-tendencia-actual');
    const modalCreadoConfirmacion = document.getElementById('modal-creado-confirmacion-tendencia-actual');
    const modalDetalle = document.getElementById('modal-detalle-tendencia');
    
    const cerrarModalBtns = document.querySelectorAll('.cerrar-modal-editar');
    const cerrarModalEditadoBtns = document.querySelectorAll('.cerrar-modal-editado');
    const cerrarModalDeshabilitarBtns = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const cerrarModalDeshabilitadoBtns = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const cerrarModalHabilitarBtns = document.querySelectorAll('.cerrar-modal-habilitar');
    const cerrarModalHabilitadoBtns = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const cerrarModalCrearBtns = document.querySelectorAll('.cerrar-modal-crear');
    const cerrarModalCreadoBtns = document.querySelectorAll('.cerrar-modal-creado');
    const cerrarModalDetalleBtns = document.querySelectorAll('.cerrar-modal-detalle');
    
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const btnCrearTendencia = document.getElementById('btn-crear-tendencia');

    // Variables para el contador y progreso
    let timeoutEditado = null;
    let intervalContadorEditado = null;
    let timeoutDeshabilitado = null;
    let intervalContadorDeshabilitado = null;
    let timeoutHabilitado = null;
    let intervalContadorHabilitado = null;
    let timeoutCreado = null;
    let intervalContadorCreado = null;
    
    // ===== FUNCIÓN PARA ABRIR MODAL =====
    function abrirModal(modal) {
      if (modal) {
        modal.classList.remove('hidden');
        // Forzar reflow para que la animación se ejecute
        modal.offsetHeight;
        document.body.classList.add('overflow-hidden');
      }
    }
    
    // ===== FUNCIÓN PARA CERRAR MODAL =====
    function cerrarModal(modal) {
      if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    }
    
    // ===== FUNCIÓN PARA CERRAR MODAL EDITADO =====
    function cerrarModalEditado() {
      if (modalEditadoConfirmacion) {
        modalEditadoConfirmacion.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
      if (timeoutEditado) {
        clearTimeout(timeoutEditado);
        timeoutEditado = null;
      }
      if (intervalContadorEditado) {
        clearInterval(intervalContadorEditado);
        intervalContadorEditado = null;
      }
      const progressBar = document.getElementById('progress-bar-editado');
      if (progressBar) {
        progressBar.style.width = '0%';
      }
      const contador = document.getElementById('contador-segundos-editado');
      if (contador) {
        contador.textContent = '3';
      }
    }
    
    // ===== FUNCIÓN PARA CERRAR MODAL DESHABILITADO =====
    function cerrarModalDeshabilitado() {
      if (modalDeshabilitadoConfirmacion) {
        modalDeshabilitadoConfirmacion.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
      if (timeoutDeshabilitado) {
        clearTimeout(timeoutDeshabilitado);
        timeoutDeshabilitado = null;
      }
      if (intervalContadorDeshabilitado) {
        clearInterval(intervalContadorDeshabilitado);
        intervalContadorDeshabilitado = null;
      }
      const progressBar = document.getElementById('progress-bar-deshabilitado');
      if (progressBar) {
        progressBar.style.width = '0%';
      }
      const contador = document.getElementById('contador-segundos-deshabilitado');
      if (contador) {
        contador.textContent = '3';
      }
    }

    // ===== FUNCIÓN PARA CERRAR MODAL HABILITADO =====
    function cerrarModalHabilitado() {
      if (modalHabilitadoConfirmacion) {
        modalHabilitadoConfirmacion.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
      if (timeoutHabilitado) {
        clearTimeout(timeoutHabilitado);
        timeoutHabilitado = null;
      }
      if (intervalContadorHabilitado) {
        clearInterval(intervalContadorHabilitado);
        intervalContadorHabilitado = null;
      }
      const progressBar = document.getElementById('progress-bar-habilitado');
      if (progressBar) {
        progressBar.style.width = '0%';
      }
      const contador = document.getElementById('contador-segundos-habilitado');
      if (contador) {
        contador.textContent = '3';
      }
    }

    // ===== FUNCIÓN PARA CERRAR MODAL CREADO =====
    function cerrarModalCreado() {
      if (modalCreadoConfirmacion) {
        modalCreadoConfirmacion.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
      if (timeoutCreado) {
        clearTimeout(timeoutCreado);
        timeoutCreado = null;
      }
      if (intervalContadorCreado) {
        clearInterval(intervalContadorCreado);
        intervalContadorCreado = null;
      }
      const progressBar = document.getElementById('progress-bar-creado');
      if (progressBar) {
        progressBar.style.width = '0%';
      }
      const contador = document.getElementById('contador-segundos-creado');
      if (contador) {
        contador.textContent = '3';
      }
    }
    
    // ===== FUNCIÓN PARA MOSTRAR MODAL EDITADO =====
    function mostrarModalEditado(nombreTendencia) {
      console.log('Mostrando modal editado para:', nombreTendencia);
      
      if (!modalEditadoConfirmacion) {
        console.error('Modal editado no encontrado');
        return;
      }
      
      const nombrePerfilEditadoSpan = document.getElementById('nombre-perfil-editado');
      if (nombrePerfilEditadoSpan) {
        nombrePerfilEditadoSpan.textContent = `"${nombreTendencia}"`;
      }
      
      abrirModal(modalEditadoConfirmacion);
      
      // Resetear contador
      let segundos = 3;
      const contador = document.getElementById('contador-segundos-editado');
      if (contador) {
        contador.textContent = segundos;
      }
      
      // Iniciar contador regresivo
      if (intervalContadorEditado) {
        clearInterval(intervalContadorEditado);
      }
      
      intervalContadorEditado = setInterval(() => {
        segundos--;
        if (contador) {
          contador.textContent = segundos;
        }
        if (segundos <= 0) {
          clearInterval(intervalContadorEditado);
          intervalContadorEditado = null;
        }
      }, 1000);
      
      // Animar barra de progreso
      const progressBar = document.getElementById('progress-bar-editado');
      if (progressBar) {
        progressBar.style.width = '0%';
        setTimeout(() => {
          progressBar.style.width = '100%';
        }, 50);
      }
      
      if (timeoutEditado) {
        clearTimeout(timeoutEditado);
      }
      
      timeoutEditado = setTimeout(() => {
        cerrarModalEditado();
      }, 3000);
    }
    
    // ===== FUNCIÓN PARA MOSTRAR MODAL DESHABILITADO =====
    function mostrarModalDeshabilitado(nombreTendencia) {
      console.log('Mostrando modal deshabilitado para:', nombreTendencia);
      
      if (!modalDeshabilitadoConfirmacion) {
        console.error('Modal deshabilitado no encontrado');
        return;
      }
      
      // Actualizar el nombre en el modal
      const nombreTendenciaSpan = document.getElementById('nombre-tendencia-act-deshabilitado');
      if (nombreTendenciaSpan) {
        nombreTendenciaSpan.textContent = `"${nombreTendencia}"`;
      }
      
      abrirModal(modalDeshabilitadoConfirmacion);
      
      // Resetear contador (3 segundos)
      let segundos = 3;
      const contador = document.getElementById('contador-segundos-deshabilitado');
      if (contador) {
        contador.textContent = segundos;
      }
      
      // Iniciar contador regresivo
      if (intervalContadorDeshabilitado) {
        clearInterval(intervalContadorDeshabilitado);
      }
      
      intervalContadorDeshabilitado = setInterval(() => {
        segundos--;
        if (contador) {
          contador.textContent = segundos;
        }
        if (segundos <= 0) {
          clearInterval(intervalContadorDeshabilitado);
          intervalContadorDeshabilitado = null;
        }
      }, 1000);
      
      // Animar barra de progreso (3 segundos)
      const progressBar = document.getElementById('progress-bar-deshabilitado');
      if (progressBar) {
        progressBar.style.width = '0%';
        setTimeout(() => {
          progressBar.style.width = '100%';
        }, 50);
      }
      
      if (timeoutDeshabilitado) {
        clearTimeout(timeoutDeshabilitado);
      }
      
      timeoutDeshabilitado = setTimeout(() => {
        cerrarModalDeshabilitado();
      }, 3000);
    }

    // ===== FUNCIÓN PARA MOSTRAR MODAL HABILITADO =====
    function mostrarModalHabilitado(nombreTendencia) {
      console.log('Mostrando modal habilitado para:', nombreTendencia);

      if (!modalHabilitadoConfirmacion) {
        console.error('Modal habilitado no encontrado');
        return;
      }

      const nombreSpan = document.getElementById('nombre-tendencia-act-habilitado-exito');
      if (nombreSpan) {
        nombreSpan.textContent = `"${nombreTendencia}"`;
      }

      abrirModal(modalHabilitadoConfirmacion);

      let segundos = 3;
      const contador = document.getElementById('contador-segundos-habilitado');
      if (contador) {
        contador.textContent = segundos;
      }

      if (intervalContadorHabilitado) {
        clearInterval(intervalContadorHabilitado);
      }

      intervalContadorHabilitado = setInterval(() => {
        segundos--;
        if (contador) contador.textContent = segundos;
        if (segundos <= 0) {
          clearInterval(intervalContadorHabilitado);
          intervalContadorHabilitado = null;
        }
      }, 1000);

      const progressBar = document.getElementById('progress-bar-habilitado');
      if (progressBar) {
        progressBar.style.width = '0%';
        setTimeout(() => {
          progressBar.style.width = '100%';
        }, 50);
      }

      if (timeoutHabilitado) {
        clearTimeout(timeoutHabilitado);
      }

      timeoutHabilitado = setTimeout(() => {
        cerrarModalHabilitado();
      }, 3000);
    }

    // ===== FUNCIÓN PARA MOSTRAR MODAL CREADO =====
    function mostrarModalCreado(nombreTendencia) {
      console.log('Mostrando modal creado para:', nombreTendencia);

      if (!modalCreadoConfirmacion) {
        console.error('Modal creado no encontrado');
        return;
      }

      const nombreSpan = document.getElementById('nombre-tendencia-creada');
      if (nombreSpan) {
        nombreSpan.textContent = `"${nombreTendencia}"`;
      }

      abrirModal(modalCreadoConfirmacion);

      let segundos = 3;
      const contador = document.getElementById('contador-segundos-creado');
      if (contador) {
        contador.textContent = segundos;
      }

      if (intervalContadorCreado) {
        clearInterval(intervalContadorCreado);
      }

      intervalContadorCreado = setInterval(() => {
        segundos--;
        if (contador) contador.textContent = segundos;
        if (segundos <= 0) {
          clearInterval(intervalContadorCreado);
          intervalContadorCreado = null;
        }
      }, 1000);

      const progressBar = document.getElementById('progress-bar-creado');
      if (progressBar) {
        progressBar.style.width = '0%';
        setTimeout(() => {
          progressBar.style.width = '100%';
        }, 50);
      }

      if (timeoutCreado) {
        clearTimeout(timeoutCreado);
      }

      timeoutCreado = setTimeout(() => {
        cerrarModalCreado();
      }, 3000);
    }
    
    // ===== EVENTO PARA ABRIR MODAL CREAR =====
    if (btnCrearTendencia) {
      btnCrearTendencia.addEventListener('click', function(e) {
        e.preventDefault();
        // Limpiar el formulario
        const form = document.getElementById('form-nueva-tendencia-actual');
        if (form) {
          form.reset();
        }
        abrirModal(modalCrear);
      });
    }
    
    // ===== EVENTOS PARA BOTONES DE EDITAR =====
    document.querySelectorAll('.btn-editar-tendencia').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Obtener datos de la tarjeta
        const card = this.closest('.border');
        const nombreTendencia = card.querySelector('h3').textContent;
        const descripcionTendencia = card.querySelector('p.text-xs').textContent;
        
        // Obtener el estado del switch
        const switchEl = card.querySelector('.switch-sena');
        const estadoTendencia = switchEl.classList.contains('active') ? 'activo' : 'inactivo';
        
        // Llenar el formulario con los datos
        const nombreInput = document.getElementById('nombre-tendencia');
        const descripcionInput = document.getElementById('descripcion-tendencia');
        const estadoSelect = document.getElementById('estado-tendencia');
        
        if (nombreInput) nombreInput.value = nombreTendencia;
        if (descripcionInput) descripcionInput.value = descripcionTendencia;
        if (estadoSelect) estadoSelect.value = estadoTendencia;
        
        // Fechas (ejemplo)
        const today = new Date();
        const fechaCreacion = new Date(today);
        fechaCreacion.setMonth(today.getMonth() - 3);
        const fechaCreacionInput = document.getElementById('fecha-creacion');
        if (fechaCreacionInput) {
          fechaCreacionInput.value = fechaCreacion.toISOString().split('T')[0];
        }
        
        const fechaExpiracion = new Date(today);
        fechaExpiracion.setMonth(today.getMonth() + 6);
        const fechaExpiracionInput = document.getElementById('fecha-expiracion');
        if (fechaExpiracionInput) {
          fechaExpiracionInput.value = fechaExpiracion.toISOString().split('T')[0];
        }
        
        // Guardar referencia a la tarjeta actual
        if (modalEditar) {
          modalEditar.setAttribute('data-card-id', card.getAttribute('data-id'));
        }
        
        // Abrir modal de edición
        abrirModal(modalEditar);
      });
    });
    
    // ===== EVENTOS PARA SWITCHES =====
    document.querySelectorAll('.switch-sena').forEach(switchEl => {
      switchEl.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const card = this.closest('.border');
        const nombreTendencia = card.querySelector('h3').textContent;
        
        // Si el switch está activo (verde), mostrar modal de deshabilitar
        if (this.classList.contains('active')) {
          // Actualizar el nombre en el modal de deshabilitar
          const spanNombre = modalDeshabilitar.querySelector('span.font-medium');
          if (spanNombre) {
            spanNombre.textContent = `"${nombreTendencia}"`;
          }
          
          // Guardar referencia al switch
          modalDeshabilitar.setAttribute('data-switch-id', card.getAttribute('data-id'));
          
          // Abrir modal de deshabilitar
          abrirModal(modalDeshabilitar);
        } else {
          // Si está inactivo (gris), mostrar modal de habilitar
          const spanNombre = modalHabilitar.querySelector('span.font-semibold');
          if (spanNombre) {
            spanNombre.textContent = `"${nombreTendencia}"`;
          }
          
          // Guardar referencia al switch
          modalHabilitar.setAttribute('data-switch-id', card.getAttribute('data-id'));
          modalHabilitar.setAttribute('data-nombre-tendencia', nombreTendencia);
          
          // Abrir modal de habilitar
          abrirModal(modalHabilitar);
        }
      });
    });
    
    // ===== EVENTO PARA ABRIR MODAL DETALLE AL HACER CLICK EN LA TARJETA =====
    document.querySelectorAll('.border[data-id]').forEach(card => {
      card.addEventListener('click', function(e) {
        // Evitar que se active si se hizo clic en el botón de editar o en el switch
        if (e.target.closest('.btn-editar-tendencia') || e.target.closest('.switch-sena')) {
          return;
        }
        
        // Obtener datos de la tarjeta
        const nombreTendencia = this.querySelector('h3').textContent;
        const descripcionTendencia = this.querySelector('p.text-xs').textContent;
        const switchEl = this.querySelector('.switch-sena');
        const estadoTendencia = switchEl.classList.contains('active') ? 'Activo' : 'Inactivo';
        const colorEstado = switchEl.classList.contains('active') ? '#39A900' : '#9ca3af';
        
        // Obtener el ID de la tarjeta
        const cardId = this.getAttribute('data-id');
        
        // Actualizar el modal con los datos
        document.getElementById('detalle-titulo').textContent = `Detalle de: ${nombreTendencia}`;
        document.getElementById('detalle-nombre').textContent = nombreTendencia;
        document.getElementById('detalle-descripcion').textContent = descripcionTendencia;
        document.getElementById('detalle-estado').textContent = estadoTendencia;
        document.getElementById('detalle-estado-indicador').style.backgroundColor = colorEstado;
        
        // Abrir modal
        abrirModal(modalDetalle);
      });
    });
    
    // ===== CONFIRMAR DESHABILITAR =====
    if (btnConfirmarDeshabilitar) {
      btnConfirmarDeshabilitar.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Obtener el ID del switch guardado
        const switchId = modalDeshabilitar.getAttribute('data-switch-id');
        const nombreTendencia = modalDeshabilitar.querySelector('span.font-medium').textContent.replace(/"/g, '');
        
        // Buscar el switch correspondiente y desactivarlo
        document.querySelectorAll('.border').forEach(card => {
          if (card.getAttribute('data-id') === switchId) {
            const switchEl = card.querySelector('.switch-sena');
            switchEl.classList.remove('active');
            switchEl.setAttribute('title', 'Inactivo');
          }
        });
        
        // Cerrar modal de deshabilitar
        cerrarModal(modalDeshabilitar);
        
        // Mostrar modal de confirmación de deshabilitado
        mostrarModalDeshabilitado(nombreTendencia);
      });
    }
    
    // ===== CONFIRMAR HABILITAR =====
    if (btnConfirmarHabilitar) {
      btnConfirmarHabilitar.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Obtener el ID del switch guardado
        const switchId = modalHabilitar.getAttribute('data-switch-id');
        const nombreTendencia = modalHabilitar.getAttribute('data-nombre-tendencia');
        
        // Buscar el switch correspondiente y activarlo
        document.querySelectorAll('.border').forEach(card => {
          if (card.getAttribute('data-id') === switchId) {
            const switchEl = card.querySelector('.switch-sena');
            switchEl.classList.add('active');
            switchEl.setAttribute('title', 'Activo');
          }
        });
        
        // Cerrar modal de habilitar
        cerrarModal(modalHabilitar);
        
        // Mostrar modal de éxito de habilitación
        mostrarModalHabilitado(nombreTendencia);
      });
    }
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE CREAR =====
    const formCrear = document.getElementById('form-nueva-tendencia-actual');
    if (formCrear) {
      formCrear.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Recopilar datos del formulario
        const area = document.querySelector('input[name="area"]').value;
        const descripcion = document.querySelector('textarea[name="descripcion"]').value;
        
        console.log('Nueva tendencia creada:', { area, descripcion });
        
        // Cerrar modal de creación
        cerrarModal(modalCrear);
        
        // Mostrar modal de éxito de creación
        mostrarModalCreado(area);
      });
    }
    
    // ===== CERRAR MODAL DE EDICIÓN =====
    cerrarModalBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModal(modalEditar);
      });
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN EDITADO =====
    cerrarModalEditadoBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModalEditado();
      });
    });
    
    // ===== CERRAR MODAL DE DESHABILITAR =====
    cerrarModalDeshabilitarBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModal(modalDeshabilitar);
      });
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN DESHABILITADO =====
    cerrarModalDeshabilitadoBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModalDeshabilitado();
      });
    });
    
    // ===== CERRAR MODAL DE HABILITAR =====
    cerrarModalHabilitarBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModal(modalHabilitar);
      });
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN HABILITADO =====
    cerrarModalHabilitadoBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModalHabilitado();
      });
    });
    
    // ===== CERRAR MODAL DE CREAR =====
    cerrarModalCrearBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModal(modalCrear);
      });
    });
    
    // ===== CERRAR MODAL DE CONFIRMACIÓN CREADO =====
    cerrarModalCreadoBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModalCreado();
      });
    });
    
    // ===== CERRAR MODAL DE DETALLE =====
    cerrarModalDetalleBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        cerrarModal(modalDetalle);
      });
    });
    
    // ===== CERRAR MODALES HACIENDO CLICK EN OVERLAY =====
    [modalEditar, modalEditadoConfirmacion, modalDeshabilitar, modalDeshabilitadoConfirmacion, modalHabilitar, modalHabilitadoConfirmacion, modalCrear, modalCreadoConfirmacion, modalDetalle].forEach(modal => {
      if (modal) {
        modal.addEventListener('click', function(e) {
          if (e.target === modal || e.target.classList.contains('fixed')) {
            if (modal === modalEditadoConfirmacion) {
              cerrarModalEditado();
            } else if (modal === modalDeshabilitadoConfirmacion) {
              cerrarModalDeshabilitado();
            } else if (modal === modalHabilitadoConfirmacion) {
              cerrarModalHabilitado();
            } else if (modal === modalCreadoConfirmacion) {
              cerrarModalCreado();
            } else {
              cerrarModal(modal);
            }
          }
        });
      }
    });
    
    // ===== CERRAR CON TECLA ESC =====
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (modalEditar && !modalEditar.classList.contains('hidden')) {
          cerrarModal(modalEditar);
        } else if (modalEditadoConfirmacion && !modalEditadoConfirmacion.classList.contains('hidden')) {
          cerrarModalEditado();
        } else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
          cerrarModal(modalDeshabilitar);
        } else if (modalDeshabilitadoConfirmacion && !modalDeshabilitadoConfirmacion.classList.contains('hidden')) {
          cerrarModalDeshabilitado();
        } else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
          cerrarModal(modalHabilitar);
        } else if (modalHabilitadoConfirmacion && !modalHabilitadoConfirmacion.classList.contains('hidden')) {
          cerrarModalHabilitado();
        } else if (modalCrear && !modalCrear.classList.contains('hidden')) {
          cerrarModal(modalCrear);
        } else if (modalCreadoConfirmacion && !modalCreadoConfirmacion.classList.contains('hidden')) {
          cerrarModalCreado();
        } else if (modalDetalle && !modalDetalle.classList.contains('hidden')) {
          cerrarModal(modalDetalle);
        }
      }
    });
    
    // ===== MANEJAR ENVÍO DEL FORMULARIO DE EDICIÓN =====
    const formEditar = document.getElementById('form-editar-tendencia');
    if (formEditar) {
      formEditar.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Recopilar datos del formulario
        const nombreTendencia = document.getElementById('nombre-tendencia').value;
        
        console.log('Tendencia actualizada:', nombreTendencia);
        
        // Cerrar modal de edición
        cerrarModal(modalEditar);
        
        // Mostrar modal de éxito
        mostrarModalEditado(nombreTendencia);
      });
    }
  });
</script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>