<?php
include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_crear_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_crear_te.php';
include __DIR__ . '/modal_editar_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_editar_te.php';
include __DIR__ . '/modal_detalle_tecnologia_emergente.php'; 
include __DIR__ . '/modal_deshabilitar_te.php';
include __DIR__ . '/modal_confirmacion_dehabilitar_te.php';
include __DIR__ . '/modal_habilitar_tecnologia_emergente.php';
include __DIR__ . '/modal_confirmacion_habilitar_te.php';
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
  <link rel="stylesheet" href="../../assets/css/globals.css">
  

<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- TECNOLOGÍAS EMERGENTES CONTENT -->
      <div class="section-heading mb-6 animate-title-in">
        <h2 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Tecnologías Emergentes</h2>
        <p class="text-sm text-sena-text-soft mt-1">Transformación digital y nuevas dinámicas del sector productivo risaraldense.</p>
      </div>

      <!-- BARRA DE BÚSQUEDA Y BOTÓN CREAR -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1 animate-search-in">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar tecnologías...">
        </div>
        <button id="btn-crear-tendencia" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity animate-button-in">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear Tecnología Emergente
        </button>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong class="font-medium text-sena-text-main">12</strong> tecnologías emergentes encontradas</p>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Inteligencia Artificial -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="1" data-nombre="Inteligencia Artificial" data-descripcion="Computación cuántica accesible desde la nube para desarrolladores y empresas, permitiendo experimentación real en problemas de optimización y criptografía." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="1" data-nombre="Inteligencia Artificial" data-descripcion="Computación cuántica accesible desde la nube para desarrolladores y empresas, permitiendo experimentación real en problemas de optimización y criptografía.">
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
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="2" data-nombre="Blockchain" data-descripcion="Tecnología de registro distribuido que permite crear sistemas descentralizados para transacciones seguras y transparentes." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="2" data-nombre="Blockchain" data-descripcion="Tecnología de registro distribuido que permite crear sistemas descentralizados para transacciones seguras y transparentes.">
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
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="3" data-nombre="Internet de las Cosas (IoT)" data-descripcion="Red de dispositivos conectados que recopilan, comparten e intercambian datos en tiempo real para automatizar procesos." data-estado="inactivo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="3" data-nombre="Internet de las Cosas (IoT)" data-descripcion="Red de dispositivos conectados que recopilan, comparten e intercambian datos en tiempo real para automatizar procesos.">
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
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="4" data-nombre="Computación en la Nube" data-descripcion="Entrega de servicios informáticos a través de internet, permitiendo acceso flexible a recursos y aplicaciones bajo demanda." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="4" data-nombre="Computación en la Nube" data-descripcion="Entrega de servicios informáticos a través de internet, permitiendo acceso flexible a recursos y aplicaciones bajo demanda.">
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
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="5" data-nombre="Ciberseguridad Avanzada" data-descripcion="Protección integral contra ciberataques mediante tecnologías avanzadas y estrategias defensivas para sistemas críticos." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="5" data-nombre="Ciberseguridad Avanzada" data-descripcion="Protección integral contra ciberataques mediante tecnologías avanzadas y estrategias defensivas para sistemas críticos.">
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
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="6" data-nombre="Big Data y Analítica" data-descripcion="Procesamiento y análisis de grandes volúmenes de datos para obtener insights valiosos que impulsen decisiones estratégicas." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="6" data-nombre="Big Data y Analítica" data-descripcion="Procesamiento y análisis de grandes volúmenes de datos para obtener insights valiosos que impulsen decisiones estratégicas.">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Big Data y Analítica</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Procesamiento y análisis de grandes volúmenes de datos para obtener insights valiosos que impulsen decisiones estratégicas.</p>
            </div>

            <!-- Realidad Aumentada/Virtual -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="7" data-nombre="Realidad Aumentada/Virtual" data-descripcion="Tecnologías inmersivas que superponen información digital en el mundo real o crean entornos virtuales para experiencias interactivas." data-estado="inactivo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="7" data-nombre="Realidad Aumentada/Virtual" data-descripcion="Tecnologías inmersivas que superponen información digital en el mundo real o crean entornos virtuales para experiencias interactivas.">
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

            <!-- Robótica Colaborativa -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="8" data-nombre="Robótica Colaborativa" data-descripcion="Robots diseñados para trabajar junto a personas en entornos compartidos, mejorando productividad y seguridad laboral." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="8" data-nombre="Robótica Colaborativa" data-descripcion="Robots diseñados para trabajar junto a personas en entornos compartidos, mejorando productividad y seguridad laboral.">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Robótica Colaborativa</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Robots diseñados para trabajar junto a personas en entornos compartidos, mejorando productividad y seguridad laboral.</p>
            </div>

            <!-- Manufactura Aditiva (Impresión 3D) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="9" data-nombre="Manufactura Aditiva (Impresión 3D)" data-descripcion="Tecnología de fabricación que construye objetos capa por capa, revolucionando prototipos, personalización y producción." data-estado="activo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="9" data-nombre="Manufactura Aditiva (Impresión 3D)" data-descripcion="Tecnología de fabricación que construye objetos capa por capa, revolucionando prototipos, personalización y producción.">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena active" title="Activo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Manufactura Aditiva (Impresión 3D)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Tecnología de fabricación que construye objetos capa por capa, revolucionando prototipos, personalización y producción.</p>
            </div>

            <!-- Automatización de Procesos (RPA) -->
            <div class="border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer tarjeta-tecnologia" data-id="10" data-nombre="Automatización de Procesos (RPA)" data-descripcion="Automatización de tareas repetitivas mediante software que simula acciones humanas, aumentando eficiencia y reduciendo costos." data-estado="inactivo">
              <div class="flex items-start justify-between mb-2">
                <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-sena">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                  </svg>
                </div>
                <div class="flex items-center gap-1">
                  <button class="btn-editar-tendencia p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" title="Editar tendencia" data-id="10" data-nombre="Automatización de Procesos (RPA)" data-descripcion="Automatización de tareas repetitivas mediante software que simula acciones humanas, aumentando eficiencia y reduciendo costos.">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                      <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                    </svg>
                  </button>
                  <div class="switch-sena" title="Inactivo"></div>
                </div>
              </div>
              <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">Automatización de Procesos (RPA)</h3>
              <p class="text-xs text-sena-text-soft line-clamp-2">Automatización de tareas repetitivas mediante software que simula acciones humanas, aumentando eficiencia y reduciendo costos.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Script para controlar los modales -->
   <script src="../../assets/js/tecnologias_emergentes/tecnologias_emergentes.js"></script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>