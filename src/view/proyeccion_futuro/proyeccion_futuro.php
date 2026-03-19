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
include __DIR__ . '/modal_detalles.pf.php';
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="1" 
                 data-nombre="Inteligencia Artificial"
                 data-descripcion="Se proyecta que para 2028, la IA generará el 20% de todo el contenido digital y automatizará el 30% de las tareas empresariales. Esta tecnología está transformando sectores como salud, finanzas, educación y manufactura, permitiendo análisis predictivo y automatización inteligente."
                 data-estado="activo"
                 data-fecha="2 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="2" 
                 data-nombre="Blockchain"
                 data-descripcion="Se espera que para 2027, el 30% de las transacciones financieras globales utilicen tecnología blockchain para mayor seguridad y trazabilidad. Esta tecnología descentralizada está revolucionando la cadena de suministro, contratos inteligentes y la identidad digital."
                 data-estado="activo"
                 data-fecha="3 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="3" 
                 data-nombre="Internet de las Cosas (IoT)"
                 data-descripcion="Proyección indica que para 2028 habrá más de 75 mil millones de dispositivos IoT conectados, transformando industrias y hogares. Esta red de dispositivos interconectados permite la recopilación y análisis de datos en tiempo real para optimizar procesos y mejorar la calidad de vida."
                 data-estado="inactivo"
                 data-fecha="4 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="4" 
                 data-nombre="Computación en la Nube"
                 data-descripcion="Se proyecta que para 2025, el 85% de las empresas migrarán completamente sus operaciones a entornos de nube híbrida. La computación en la nube ofrece escalabilidad, flexibilidad y reducción de costos, permitiendo a las empresas innovar más rápidamente."
                 data-estado="activo"
                 data-fecha="1 año">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="5" 
                 data-nombre="Ciberseguridad Avanzada"
                 data-descripcion="Para 2026, se estima que el gasto global en ciberseguridad alcanzará los $300 mil millones, impulsado por IA defensiva. Las organizaciones están adoptando enfoques de seguridad zero-trust y automatización de respuesta a incidentes."
                 data-estado="activo"
                 data-fecha="2 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="6" 
                 data-nombre="Big Data y Analítica"
                 data-descripcion="Se proyecta que para 2027, el mercado de big data alcanzará los $400 mil millones, con analítica predictiva en tiempo real. Las empresas utilizan big data para tomar decisiones basadas en datos y obtener ventajas competitivas."
                 data-estado="activo"
                 data-fecha="3 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="7" 
                 data-nombre="Realidad Aumentada/Virtual"
                 data-descripcion="Para 2029, se espera que 50 millones de personas trabajen en entornos de realidad mixta, revolucionando el teletrabajo. La AR/VR está transformando la educación, el entrenamiento profesional y el entretenimiento."
                 data-estado="inactivo"
                 data-fecha="5 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="8" 
                 data-nombre="Robótica Colaborativa"
                 data-descripcion="Se proyecta que para 2028, los robots colaborativos representarán el 40% de todas las instalaciones robóticas en industria. Los cobots trabajan junto a humanos, aumentando la productividad y seguridad en entornos de manufactura."
                 data-estado="activo"
                 data-fecha="2 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="9" 
                 data-nombre="Manufactura Aditiva (Impresión 3D)"
                 data-descripcion="Para 2028, se proyecta que el 60% de los productos de consumo masivo tendrán componentes fabricados con impresión 3D. La manufactura aditiva permite personalización masiva, reducción de desperdicios y producción bajo demanda."
                 data-estado="activo"
                 data-fecha="4 años">
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
            <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
                 data-id="10" 
                 data-nombre="Automatización de Procesos (RPA)"
                 data-descripcion="Para 2027, se espera que el 90% de las tareas administrativas repetitivas estén automatizadas mediante RPA con IA integrada. La automatización robótica de procesos libera a los empleados para tareas de mayor valor agregado."
                 data-estado="inactivo"
                 data-fecha="3 años">
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

  <!-- Script para la funcionalidad -->
  <script src="../../assets/js/proyeccion_futuro/proyeccion_futuro.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>