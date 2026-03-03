<?php
include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_crear_proyeccion_futuro.php';
include __DIR__ . '/modal_confirmacion_crear_pf.php';
include __DIR__ . '/modal_editar_proyeccion_futuro.php';
include __DIR__ . '/modal_confirmacion_editar_pf.php';
include __DIR__ . '/modal_deshabilitar_proyeccion_futura.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_pf.php';
include __DIR__ . '/modal_habilitar_proyeccion_futura.php';
include __DIR__ . '/modal_confirmacion_habilitar_pf.php';
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

    /* Clase hidden para el modal */
    .hidden {
      display: none !important;
    }

    /* Estilos para los select personalizados */
    .select-container {
      position: relative;
      width: 100%;
    }

    .custom-select {
      width: 100%;
      padding: 0.5rem 2.5rem 0.5rem 0.75rem;
      font-size: 0.875rem;
      color: #1e293b;
      background-color: white;
      border: 1px solid #e2e8f0;
      border-radius: 0.5rem;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      cursor: pointer;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 1.25rem;
    }

    .custom-select:focus {
      outline: none;
      border-color: #39A900;
      box-shadow: 0 0 0 2px rgba(57, 169, 0, 0.1);
    }

    .custom-select:hover {
      border-color: #94a3b8;
    }

    .custom-select::-ms-expand {
      display: none;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- PROYECCIÓN A FUTURO CONTENT -->
      <div class="section-heading mb-6">
        <h2 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main"><P>Proyección a Futuro</P></h2>
        <p class="text-sm text-sena-text-soft mt-1">Innovaciones emergentes que impactan el sector productivo risaraldense.</p>
      </div>

      <!-- BARRA DE BÚSQUEDA Y BOTÓN CREAR -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar proyecciones a futuro...">
        </div>
        <button id="btn-crear-proyeccion" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear Proyección a Futuro
        </button>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong class="font-medium text-sena-text-main">12</strong> proyecciones a futuro encontradas</p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Inteligencia Artificial -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="1" data-nombre="Inteligencia Artificial">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="1" data-nombre="Inteligencia Artificial"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Inteligencia Artificial</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Se proyecta que para 2028, la IA generará el 20% de todo el contenido digital y automatizará el 30% de las tareas empresariales.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">2 años</span>
              </div>
            </div>

            <!-- Blockchain -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="2" data-nombre="Blockchain">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="2" data-nombre="Blockchain"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Blockchain</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Se espera que para 2027, el 30% de las transacciones financieras globales utilicen tecnología blockchain para mayor seguridad y trazabilidad.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">3 años</span>
              </div>
            </div>

            <!-- Internet de las Cosas (IoT) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="3" data-nombre="Internet de las Cosas (IoT)">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena" title="Inactivo" data-id="3" data-nombre="Internet de las Cosas (IoT)"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Internet de las Cosas (IoT)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Proyección indica que para 2028 habrá más de 75 mil millones de dispositivos IoT conectados, transformando industrias y hogares.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">4 años</span>
              </div>
            </div>

            <!-- Computacion en la Nube -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="4" data-nombre="Computación en la Nube">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="4" data-nombre="Computación en la Nube"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Computacion en la Nube</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Se proyecta que para 2025, el 85% de las empresas migrarán completamente sus operaciones a entornos de nube híbrida.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">1 año</span>
              </div>
            </div>

            <!-- Ciberseguridad Avanzada -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="5" data-nombre="Ciberseguridad Avanzada">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="5" data-nombre="Ciberseguridad Avanzada"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Ciberseguridad Avanzada</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Para 2026, se estima que el gasto global en ciberseguridad alcanzará los $300 mil millones, impulsado por IA defensiva.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">2 años</span>
              </div>
            </div>

            <!-- Big Data y Analitica -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="6" data-nombre="Big Data y Analítica">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="6">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="6" data-nombre="Big Data y Analítica"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Big Data y Analitica</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Se proyecta que para 2027, el mercado de big data alcanzará los $400 mil millones, con analítica predictiva en tiempo real.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">3 años</span>
              </div>
            </div>

            <!-- Realidad Aumentada/Virtual -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="7" data-nombre="Realidad Aumentada/Virtual">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="7">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena" title="Inactivo" data-id="7" data-nombre="Realidad Aumentada/Virtual"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Realidad Aumentada/Virtual</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Para 2029, se espera que 50 millones de personas trabajen en entornos de realidad mixta, revolucionando el teletrabajo.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">5 años</span>
              </div>
            </div>

            <!-- Robotica Colaborativa -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="8" data-nombre="Robótica Colaborativa">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="8">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="8" data-nombre="Robótica Colaborativa"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Robotica Colaborativa</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Se proyecta que para 2028, los robots colaborativos representarán el 40% de todas las instalaciones robóticas en industria.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">2 años</span>
              </div>
            </div>

            <!-- Manufactura Aditiva (Impresion 3D) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="9" data-nombre="Manufactura Aditiva (Impresión 3D)">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="9">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo" data-id="9" data-nombre="Manufactura Aditiva (Impresión 3D)"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Manufactura Aditiva (Impresion 3D)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Para 2028, se proyecta que el 60% de los productos de consumo masivo tendrán componentes fabricados con impresión 3D.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">4 años</span>
              </div>
            </div>

            <!-- Automatizacion de Procesos (RPA) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all" data-id="10" data-nombre="Automatización de Procesos (RPA)">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none"/>
                    <polyline points="12 6 12 12 16 14" stroke="currentColor" fill="none"/>
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar proyección" data-id="10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena" title="Inactivo" data-id="10" data-nombre="Automatización de Procesos (RPA)"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Automatizacion de Procesos (RPA)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Para 2027, se espera que el 90% de las tareas administrativas repetitivas estén automatizadas mediante RPA con IA integrada.</p>
              <hr class="my-3 mt-2 border-sena-border">
              <div class="flex items-center justify-end gap-1 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-sena-text-soft">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span class="text-xs text-sena-text-soft">3 años</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Script para controlar los modales -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elementos del modal de creación
    const modalCrear = document.getElementById('modal-crear-proyeccion-futuro');
    const btnAbrir = document.getElementById('btn-crear-proyeccion');
    const botonesCerrarCrear = document.querySelectorAll('.cerrar-modal-crear');
    const formCrear = document.getElementById('form-nueva-proyeccion-futuro');
    const btnSubmitCrear = document.getElementById('btn-submit-proyeccion');
    const btnTextCrear = btnSubmitCrear ? btnSubmitCrear.querySelector('.btn-text') : null;
    const btnLoadingCrear = btnSubmitCrear ? btnSubmitCrear.querySelector('.btn-loading') : null;

    // Elementos del modal de edición
    const modalEditar = document.getElementById('modal-editar-proyeccion-futuro');
    const botonesEditar = document.querySelectorAll('.btn-editar-proyeccion');
    const formEditar = document.getElementById('form-editar-proyeccion-futuro');

    // Elementos del modal de deshabilitar (confirmación)
    const modalDeshabilitar = document.getElementById('modal-deshabilitar-proyeccion-futura');
    const botonesCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
    const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
    const nombreProyeccionDeshabilitarSpan = modalDeshabilitar ? modalDeshabilitar.querySelector('.font-medium.text-sena-text-main') : null;

    // Elementos del modal de confirmación de deshabilitado (éxito)
    const modalConfirmacionDeshabilitado = document.getElementById('modal-deshabilitado-proyeccion-futuro');
    const botonesCerrarConfirmacionDeshabilitado = document.querySelectorAll('.cerrar-modal-deshabilitado');
    const nombreProyeccionDeshabilitadoSpan = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
    const contadorSpanDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
    const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

    // Elementos del modal de habilitar (confirmación)
    const modalHabilitar = document.getElementById('modal-habilitar-tendencia');
    const botonesCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
    const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
    const nombreProyeccionHabilitarSpan = document.getElementById('nombre-proyeccion-habilitar');

    // Elementos del modal de confirmación de habilitado (éxito)
    const modalConfirmacionHabilitado = document.getElementById('modal-habilitado-confirmacion-proyeccion_futura');
    const botonesCerrarConfirmacionHabilitado = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
    const nombreProyeccionHabilitadoSpan = document.getElementById('nombre-proyeccion-habilitado-exito');
    const contadorSpanHabilitado = document.getElementById('contador-segundos');
    const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

    // Elementos del modal de confirmación de creación
    const modalConfirmacionCrear = document.getElementById('modal-creado-confirmacion-proyeccion-futura');
    const botonesCerrarConfirmacionCrear = document.querySelectorAll('.cerrar-modal-creado');
    const nombreProyeccionSpan = document.getElementById('nombre-proyeccion-creada');
    const contadorSpanCrear = document.getElementById('contador-segundos-creado');
    const progressBarCrear = document.getElementById('progress-bar-creado');

    // Elementos del modal de confirmación de edición
    const modalConfirmacionEditar = document.getElementById('modal-editado-confirmacion');
    const botonesCerrarConfirmacionEditar = document.querySelectorAll('.cerrar-modal-editado');
    const nombreProyeccionEditadoSpan = document.getElementById('nombre-proyeccion-editado');
    const contadorSpanEditar = document.getElementById('contador-segundos-editado');
    const progressBarEditar = document.getElementById('progress-bar-editado');
    
    // Switches (para deshabilitar/habilitar)
    const switches = document.querySelectorAll('.switch-sena');
    
    // Timeouts e intervals
    let timeoutIdCrear = null;
    let intervalIdCrear = null;
    let timeoutIdEditar = null;
    let intervalIdEditar = null;
    let timeoutIdDeshabilitado = null;
    let intervalIdDeshabilitado = null;
    let timeoutIdHabilitado = null;
    let intervalIdHabilitado = null;

    // Variables para guardar el ID y nombre de la proyección a deshabilitar/habilitar
    let currentDeshabilitarId = null;
    let currentDeshabilitarNombre = null;
    let currentHabilitarId = null;
    let currentHabilitarNombre = null;

    // ===== FUNCIONES PARA MODAL DE CREACIÓN =====
    function abrirModalCrear() {
      modalCrear.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalCrear() {
      modalCrear.classList.add('hidden');
      document.body.style.overflow = '';
      
      // Resetear el botón si estaba en estado de carga
      if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
        btnSubmitCrear.disabled = false;
        btnTextCrear.classList.remove('hidden');
        btnLoadingCrear.classList.add('hidden');
      }
    }

    // ===== FUNCIONES PARA MODAL DE EDICIÓN =====
    function abrirModalEditar(id) {
      console.log('Editando proyección con ID:', id);
      
      const datosDemo = {
        1: { area: 'Inteligencia Artificial', descripcion: 'Se proyecta que para 2028, la IA generará el 20% de todo el contenido digital y automatizará el 30% de las tareas empresariales.', fecha: '2' },
        2: { area: 'Blockchain', descripcion: 'Se espera que para 2027, el 30% de las transacciones financieras globales utilicen tecnología blockchain.', fecha: '3' },
        3: { area: 'Internet de las Cosas (IoT)', descripcion: 'Proyección indica que para 2028 habrá más de 75 mil millones de dispositivos IoT conectados.', fecha: '4' },
        4: { area: 'Computación en la Nube', descripcion: 'Se proyecta que para 2025, el 85% de las empresas migrarán a entornos de nube híbrida.', fecha: '1' },
        5: { area: 'Ciberseguridad Avanzada', descripcion: 'Para 2026, el gasto global en ciberseguridad alcanzará los $300 mil millones.', fecha: '2' },
        6: { area: 'Big Data y Analítica', descripcion: 'El mercado de big data alcanzará los $400 mil millones para 2027.', fecha: '3' },
        7: { area: 'Realidad Aumentada/Virtual', descripcion: '50 millones de personas trabajarán en entornos de realidad mixta para 2029.', fecha: '5' },
        8: { area: 'Robótica Colaborativa', descripcion: 'Los robots colaborativos representarán el 40% de instalaciones robóticas para 2028.', fecha: '2' },
        9: { area: 'Manufactura Aditiva (Impresión 3D)', descripcion: '60% de productos tendrán componentes impresos en 3D para 2028.', fecha: '4' },
        10: { area: 'Automatización de Procesos (RPA)', descripcion: '90% de tareas repetitivas estarán automatizadas para 2027.', fecha: '3' }
      };
      
      const datos = datosDemo[id] || { area: '', descripcion: '', fecha: '' };
      
      if (formEditar) {
        const selectArea = formEditar.querySelector('select[name="area"]');
        const textareaDesc = formEditar.querySelector('textarea[name="descripcion"]');
        const selectFecha = formEditar.querySelector('select[name="fecha"]');
        
        if (selectArea) selectArea.value = datos.area;
        if (textareaDesc) textareaDesc.value = datos.descripcion;
        if (selectFecha) selectFecha.value = datos.fecha;
      }
      
      modalEditar.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalEditar() {
      modalEditar.classList.add('hidden');
      document.body.style.overflow = '';
    }

    // ===== FUNCIONES PARA MODAL DE DESHABILITAR (CONFIRMACIÓN) =====
    function abrirModalDeshabilitar(id, nombre) {
      currentDeshabilitarId = id;
      currentDeshabilitarNombre = nombre;
      
      // Actualizar el nombre en el modal
      if (nombreProyeccionDeshabilitarSpan) {
        nombreProyeccionDeshabilitarSpan.textContent = `"${nombre}"`;
      }
      
      modalDeshabilitar.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalDeshabilitar() {
      modalDeshabilitar.classList.add('hidden');
      document.body.style.overflow = '';
      currentDeshabilitarId = null;
      currentDeshabilitarNombre = null;
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE DESHABILITADO (ÉXITO) =====
    function cerrarModalConfirmacionDeshabilitado() {
      if (modalConfirmacionDeshabilitado) {
        modalConfirmacionDeshabilitado.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdDeshabilitado) {
          clearTimeout(timeoutIdDeshabilitado);
          timeoutIdDeshabilitado = null;
        }
        if (intervalIdDeshabilitado) {
          clearInterval(intervalIdDeshabilitado);
          intervalIdDeshabilitado = null;
        }
        
        if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
        if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = '3';
      }
    }

    function mostrarModalConfirmacionDeshabilitado(nombreProyeccion) {
      if (nombreProyeccionDeshabilitadoSpan) {
        nombreProyeccionDeshabilitadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionDeshabilitado.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = segundos;
      if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '100%';
      }, 50);
      
      intervalIdDeshabilitado = setInterval(() => {
        segundos--;
        if (contadorSpanDeshabilitado) contadorSpanDeshabilitado.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdDeshabilitado);
          intervalIdDeshabilitado = null;
        }
      }, 1000);
      
      timeoutIdDeshabilitado = setTimeout(() => {
        cerrarModalConfirmacionDeshabilitado();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE HABILITAR (CONFIRMACIÓN) =====
    function abrirModalHabilitar(id, nombre) {
      currentHabilitarId = id;
      currentHabilitarNombre = nombre;
      
      // Actualizar el nombre en el modal
      if (nombreProyeccionHabilitarSpan) {
        nombreProyeccionHabilitarSpan.textContent = `"${nombre}"`;
      }
      
      modalHabilitar.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function cerrarModalHabilitar() {
      modalHabilitar.classList.add('hidden');
      document.body.style.overflow = '';
      currentHabilitarId = null;
      currentHabilitarNombre = null;
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE HABILITADO (ÉXITO) =====
    function cerrarModalConfirmacionHabilitado() {
      if (modalConfirmacionHabilitado) {
        modalConfirmacionHabilitado.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdHabilitado) {
          clearTimeout(timeoutIdHabilitado);
          timeoutIdHabilitado = null;
        }
        if (intervalIdHabilitado) {
          clearInterval(intervalIdHabilitado);
          intervalIdHabilitado = null;
        }
        
        if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
        if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = '3';
      }
    }

    function mostrarModalConfirmacionHabilitado(nombreProyeccion) {
      if (nombreProyeccionHabilitadoSpan) {
        nombreProyeccionHabilitadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionHabilitado.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = segundos;
      if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarHabilitado) progressBarHabilitado.style.width = '100%';
      }, 50);
      
      intervalIdHabilitado = setInterval(() => {
        segundos--;
        if (contadorSpanHabilitado) contadorSpanHabilitado.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdHabilitado);
          intervalIdHabilitado = null;
        }
      }, 1000);
      
      timeoutIdHabilitado = setTimeout(() => {
        cerrarModalConfirmacionHabilitado();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE CREACIÓN =====
    function cerrarModalConfirmacionCrear() {
      if (modalConfirmacionCrear) {
        modalConfirmacionCrear.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdCrear) {
          clearTimeout(timeoutIdCrear);
          timeoutIdCrear = null;
        }
        if (intervalIdCrear) {
          clearInterval(intervalIdCrear);
          intervalIdCrear = null;
        }
        
        if (progressBarCrear) progressBarCrear.style.width = '0%';
        if (contadorSpanCrear) contadorSpanCrear.textContent = '3';
      }
    }

    function mostrarModalConfirmacionCrear(nombreProyeccion) {
      if (nombreProyeccionSpan) {
        nombreProyeccionSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionCrear.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanCrear) contadorSpanCrear.textContent = segundos;
      if (progressBarCrear) progressBarCrear.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarCrear) progressBarCrear.style.width = '100%';
      }, 50);
      
      intervalIdCrear = setInterval(() => {
        segundos--;
        if (contadorSpanCrear) contadorSpanCrear.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdCrear);
          intervalIdCrear = null;
        }
      }, 1000);
      
      timeoutIdCrear = setTimeout(() => {
        cerrarModalConfirmacionCrear();
      }, 3000);
    }

    // ===== FUNCIONES PARA MODAL DE CONFIRMACIÓN DE EDICIÓN =====
    function cerrarModalConfirmacionEditar() {
      if (modalConfirmacionEditar) {
        modalConfirmacionEditar.classList.add('hidden');
        document.body.style.overflow = '';
        
        if (timeoutIdEditar) {
          clearTimeout(timeoutIdEditar);
          timeoutIdEditar = null;
        }
        if (intervalIdEditar) {
          clearInterval(intervalIdEditar);
          intervalIdEditar = null;
        }
        
        if (progressBarEditar) progressBarEditar.style.width = '0%';
        if (contadorSpanEditar) contadorSpanEditar.textContent = '3';
      }
    }

    function mostrarModalConfirmacionEditar(nombreProyeccion) {
      if (nombreProyeccionEditadoSpan) {
        nombreProyeccionEditadoSpan.textContent = `"${nombreProyeccion}"`;
      }
      
      modalConfirmacionEditar.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
      
      let segundos = 3;
      if (contadorSpanEditar) contadorSpanEditar.textContent = segundos;
      if (progressBarEditar) progressBarEditar.style.width = '0%';
      
      setTimeout(() => {
        if (progressBarEditar) progressBarEditar.style.width = '100%';
      }, 50);
      
      intervalIdEditar = setInterval(() => {
        segundos--;
        if (contadorSpanEditar) contadorSpanEditar.textContent = segundos;
        
        if (segundos <= 0) {
          clearInterval(intervalIdEditar);
          intervalIdEditar = null;
        }
      }, 1000);
      
      timeoutIdEditar = setTimeout(() => {
        cerrarModalConfirmacionEditar();
      }, 3000);
    }

    // ===== EVENT LISTENERS =====

    // Abrir modal de creación
    if (btnAbrir) {
      btnAbrir.addEventListener('click', abrirModalCrear);
    }

    // Abrir modal de edición
    botonesEditar.forEach(boton => {
      boton.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.getAttribute('data-id');
        abrirModalEditar(id);
      });
    });

    // Evento para los switches (abrir modal de deshabilitar o habilitar)
    switches.forEach(sw => {
      sw.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Obtener el estado actual (activo/inactivo)
        const isActive = this.classList.contains('active');
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        
        if (isActive) {
          // Si está activo, abrir modal para deshabilitar
          abrirModalDeshabilitar(id, nombre);
        } else {
          // Si está inactivo, abrir modal para habilitar
          abrirModalHabilitar(id, nombre);
        }
      });
    });

    // Confirmar deshabilitar
    if (btnConfirmarDeshabilitar) {
      btnConfirmarDeshabilitar.addEventListener('click', function() {
        console.log('Deshabilitando proyección:', currentDeshabilitarId, currentDeshabilitarNombre);
        
        // Simular deshabilitación
        setTimeout(() => {
          // Cambiar el estado del switch correspondiente
          if (currentDeshabilitarId) {
            const switchElement = document.querySelector(`.switch-sena[data-id="${currentDeshabilitarId}"]`);
            if (switchElement) {
              switchElement.classList.remove('active');
              switchElement.setAttribute('title', 'Inactivo');
            }
          }
          
          // Guardar nombre antes de cerrar
          const nombreProyeccion = currentDeshabilitarNombre;
          
          // Cerrar modal de deshabilitar
          cerrarModalDeshabilitar();
          
          // Mostrar modal de confirmación de deshabilitado (ÉXITO)
          mostrarModalConfirmacionDeshabilitado(nombreProyeccion);
        }, 300);
      });
    }

    // Confirmar habilitar
    if (btnConfirmarHabilitar) {
      btnConfirmarHabilitar.addEventListener('click', function() {
        console.log('Habilitando proyección:', currentHabilitarId, currentHabilitarNombre);
        
        // Simular habilitación
        setTimeout(() => {
          // Cambiar el estado del switch correspondiente
          if (currentHabilitarId) {
            const switchElement = document.querySelector(`.switch-sena[data-id="${currentHabilitarId}"]`);
            if (switchElement) {
              switchElement.classList.add('active');
              switchElement.setAttribute('title', 'Activo');
            }
          }
          
          // Guardar nombre antes de cerrar
          const nombreProyeccion = currentHabilitarNombre;
          
          // Cerrar modal de habilitar
          cerrarModalHabilitar();
          
          // Mostrar modal de confirmación de habilitado (ÉXITO)
          mostrarModalConfirmacionHabilitado(nombreProyeccion);
        }, 300);
      });
    }

    // Cerrar modales con los botones de cerrar
    botonesCerrarCrear.forEach(boton => {
      boton.addEventListener('click', function(e) {
        if (modalCrear && !modalCrear.classList.contains('hidden')) {
          cerrarModalCrear();
        }
        if (modalEditar && !modalEditar.classList.contains('hidden')) {
          cerrarModalEditar();
        }
      });
    });

    // Cerrar modal de deshabilitar
    botonesCerrarDeshabilitar.forEach(boton => {
      boton.addEventListener('click', cerrarModalDeshabilitar);
    });

    // Cerrar modal de habilitar
    botonesCerrarHabilitar.forEach(boton => {
      boton.addEventListener('click', cerrarModalHabilitar);
    });

    // Cerrar modal de confirmación de deshabilitado
    botonesCerrarConfirmacionDeshabilitado.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionDeshabilitado);
    });

    // Cerrar modal de confirmación de habilitado
    botonesCerrarConfirmacionHabilitado.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionHabilitado);
    });

    // Cerrar modal de confirmación de creación
    botonesCerrarConfirmacionCrear.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionCrear);
    });

    // Cerrar modal de confirmación de edición
    botonesCerrarConfirmacionEditar.forEach(boton => {
      boton.addEventListener('click', cerrarModalConfirmacionEditar);
    });

    // Cerrar modales al hacer clic en el overlay
    if (modalCrear) {
      modalCrear.addEventListener('click', function(e) {
        if (e.target === modalCrear || e.target.classList.contains('bg-black')) {
          cerrarModalCrear();
        }
      });
    }

    if (modalEditar) {
      modalEditar.addEventListener('click', function(e) {
        if (e.target === modalEditar || e.target.classList.contains('bg-black')) {
          cerrarModalEditar();
        }
      });
    }

    if (modalDeshabilitar) {
      modalDeshabilitar.addEventListener('click', function(e) {
        if (e.target === modalDeshabilitar || e.target.classList.contains('bg-black')) {
          cerrarModalDeshabilitar();
        }
      });
    }

    if (modalHabilitar) {
      modalHabilitar.addEventListener('click', function(e) {
        if (e.target === modalHabilitar || e.target.classList.contains('bg-black')) {
          cerrarModalHabilitar();
        }
      });
    }

    if (modalConfirmacionDeshabilitado) {
      modalConfirmacionDeshabilitado.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionDeshabilitado || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionDeshabilitado();
        }
      });
    }

    if (modalConfirmacionHabilitado) {
      modalConfirmacionHabilitado.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionHabilitado || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionHabilitado();
        }
      });
    }

    if (modalConfirmacionCrear) {
      modalConfirmacionCrear.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionCrear || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionCrear();
        }
      });
    }

    if (modalConfirmacionEditar) {
      modalConfirmacionEditar.addEventListener('click', function(e) {
        if (e.target === modalConfirmacionEditar || e.target.classList.contains('bg-black')) {
          cerrarModalConfirmacionEditar();
        }
      });
    }

    // Cerrar modales con tecla ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        if (modalCrear && !modalCrear.classList.contains('hidden')) {
          cerrarModalCrear();
        }
        if (modalEditar && !modalEditar.classList.contains('hidden')) {
          cerrarModalEditar();
        }
        if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
          cerrarModalDeshabilitar();
        }
        if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
          cerrarModalHabilitar();
        }
        if (modalConfirmacionDeshabilitado && !modalConfirmacionDeshabilitado.classList.contains('hidden')) {
          cerrarModalConfirmacionDeshabilitado();
        }
        if (modalConfirmacionHabilitado && !modalConfirmacionHabilitado.classList.contains('hidden')) {
          cerrarModalConfirmacionHabilitado();
        }
        if (modalConfirmacionCrear && !modalConfirmacionCrear.classList.contains('hidden')) {
          cerrarModalConfirmacionCrear();
        }
        if (modalConfirmacionEditar && !modalConfirmacionEditar.classList.contains('hidden')) {
          cerrarModalConfirmacionEditar();
        }
      }
    });

    // Manejar envío del formulario de creación
    if (formCrear) {
      formCrear.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
          btnSubmitCrear.disabled = true;
          btnTextCrear.classList.add('hidden');
          btnLoadingCrear.classList.remove('hidden');
        }
        
        const formData = new FormData(formCrear);
        const datos = Object.fromEntries(formData.entries());
        const nombreProyeccion = datos.area || 'Nueva Proyección';
        
        setTimeout(() => {
          cerrarModalCrear();
          formCrear.reset();
          mostrarModalConfirmacionCrear(nombreProyeccion);
          
          if (btnSubmitCrear && btnTextCrear && btnLoadingCrear) {
            btnSubmitCrear.disabled = false;
            btnTextCrear.classList.remove('hidden');
            btnLoadingCrear.classList.add('hidden');
          }
        }, 300);
      });
    }

    // Manejar envío del formulario de edición
    if (formEditar) {
      formEditar.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(formEditar);
        const datos = Object.fromEntries(formData.entries());
        const nombreProyeccion = datos.area || 'Proyección';
        
        setTimeout(() => {
          cerrarModalEditar();
          mostrarModalConfirmacionEditar(nombreProyeccion);
        }, 300);
      });
    }
  });
  </script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>