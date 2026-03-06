<?php
// filepath: c:\wamp64\www\Observatorio-CTI\src\view\lineas_tecnologicas\detalles_lineas_tecnologicas.php

/**
 * Include a PHP file and extract only the first occurrence of a given HTML tag.
 * This allows reusing legacy includes that output full HTML documents.
 */
function extract_tag_from_include(string $includePath, string $tagName): string
{
  ob_start();
  include $includePath;
  $html = ob_get_clean();

  $pattern = sprintf('/<%1$s\\b[^>]*>.*?<\\/%1$s>/is', preg_quote($tagName, '/'));

  if (preg_match($pattern, $html, $matches) === 1) {
    return $matches[0];
  }

  return '';
}

$headerFragment = extract_tag_from_include(__DIR__ . '/../../includes/header.php', 'header');
$footerFragment = extract_tag_from_include(__DIR__ . '/../../includes/footer.php', 'footer');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle de Perfil - SENA Observatorio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Inter'] text-[hsl(150,10%,15%)] antialiased bg-[hsl(0,0%,99%)] min-h-screen">

  <?= $headerFragment ?>

  <div class="w-full max-w-6xl mx-auto px-6 lg:px-10 py-10">

    <!-- ============ BACK LINK ============ -->
    <a href="#" class="group inline-flex items-center gap-2 text-sm text-[hsl(150,5%,45%)] hover:text-[hsl(105,100%,33%)] transition-colors mb-8">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform group-hover:-translate-x-0.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
      </svg>
      Volver a resultados
    </a>

    <!-- ============ BADGES ============ -->
    <div class="flex flex-wrap items-center gap-3 mb-4">
      <span class="text-[13px] font-medium text-[hsl(105,100%,33%)] bg-[hsl(105,40%,92%)] rounded-full px-4 py-1.5 tracking-wide">
        Tecnologias de la Informacion y las Comunicaciones
      </span>
      <span id="detalle-badge-estado" class="inline-flex items-center gap-1.5 text-[13px] font-medium text-[hsl(105,100%,33%)] bg-[hsl(105,40%,92%)] rounded-full px-4 py-1.5">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
        </svg>
        Vigente
      </span>
    </div>

    <!-- ============ TITLE + ACTIONS ROW ============ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mb-10">
      <h1 id="detalle-linea-titulo" class="font-['Montserrat'] text-3xl font-bold text-[hsl(150,10%,15%)] tracking-tight">
        Desarrollador Full Stack Senior
      </h1>
      <div class="flex items-center gap-3 flex-shrink-0">
        <!-- Editar -->
        <button id="btn-detalle-editar" type="button" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-[hsl(150,10%,15%)] border border-[hsl(100,10%,90%)] rounded-lg bg-white whitespace-nowrap h-10 hover:bg-[hsl(105,40%,92%)] transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-[hsl(150,5%,45%)]">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
          </svg>
          Editar
        </button>
        <!-- Desactivar -->
        <button id="btn-detalle-deshabilitar" type="button" class="px-6 py-2.5 text-sm font-semibold text-white bg-[#E1A14A] rounded-xl whitespace-nowrap hover:bg-[#cf903a] transition-colors">
          Desactivar
        </button>
      </div>
    </div>

    <!-- ============ TWO-COLUMN LAYOUT ============ -->
    <div class="flex flex-col lg:flex-row gap-6 items-start">

      <!-- ======== LEFT COLUMN ======== -->
      <div class="flex-1 min-w-0 border border-[hsl(100,10%,90%)] rounded-2xl bg-white p-8 lg:p-10">

        <!-- Description -->
        <div class="mb-8">
          <h4 class="text-[11px] font-bold text-[hsl(150,5%,45%)] uppercase tracking-[0.15em] mb-5">
            Descripcion del Perfil
          </h4>
          <p class="text-[15px] text-[hsl(150,10%,15%)] leading-[1.75] max-w-prose">
            Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas utilizando frameworks
            modernos de frontend y backend. Debe tener conocimiento en arquitecturas de microservicios, bases de datos
            relacionales y no relacionales, y despliegue en entornos cloud.
          </p>
        </div>

        <!-- Divider -->
        <hr class="border-t border-gray-100 mb-8" />

        <!-- Tecnologias Emergentes -->
        <div>
          <h4 class="text-[11px] font-bold text-[hsl(150,5%,45%)] uppercase tracking-[0.15em] mb-5">
            Tecnologias Emergentes Asociadas
          </h4>
          <div class="flex flex-wrap gap-3">
            <span class="inline-flex items-center gap-2 text-[14px] font-medium text-[hsl(150,10%,15%)] bg-[hsl(105,25%,95%)] border border-[hsl(100,10%,90%)] rounded-xl px-4 py-2.5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] text-[hsl(105,100%,33%)] flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
              Computacion en la Nube
            </span>
            <span class="inline-flex items-center gap-2 text-[14px] font-medium text-[hsl(150,10%,15%)] bg-[hsl(105,25%,95%)] border border-[hsl(100,10%,90%)] rounded-xl px-4 py-2.5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] text-[hsl(105,100%,33%)] flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
              Ciberseguridad Avanzada
            </span>
          </div>
        </div>
      </div>

      <!-- ======== RIGHT COLUMN ======== -->
      <div class="w-full lg:w-[380px] flex-shrink-0 flex flex-col gap-4">

        <!-- Empresa -->
        <div class="border border-[hsl(100,10%,90%)] rounded-2xl bg-white px-6 py-5">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-[hsl(105,40%,92%)] rounded-xl flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] text-[hsl(105,100%,33%)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M3.75 3v18m4.5-18v18m4.5-18v18m4.5-18v18M6 6.75h.008M6 9.75h.008M6 12.75h.008M6 15.75h.008M9.75 6.75h.008M9.75 9.75h.008M9.75 12.75h.008M9.75 15.75h.008M13.5 6.75h.008M13.5 9.75h.008M13.5 12.75h.008M13.5 15.75h.008M17.25 6.75h.008M17.25 9.75h.008M17.25 12.75h.008M17.25 15.75h.008" />
              </svg>
            </div>
            <h4 class="text-[15px] font-bold text-[hsl(150,10%,15%)]">Empresa</h4>
          </div>
          <p class="text-[15px] text-[hsl(150,10%,15%)] pl-12">TechColombia S.A.S.</p>
        </div>

        <!-- Linea Tecnologica -->
        <div class="border border-[hsl(100,10%,90%)] rounded-2xl bg-white px-6 py-5">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-[hsl(105,40%,92%)] rounded-xl flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] text-[hsl(105,100%,33%)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L12 12.75 6.429 9.75m11.142 0 4.179 2.25-9.75 5.25-9.75-5.25 4.179-2.25" />
              </svg>
            </div>
            <h4 class="text-[15px] font-bold text-[hsl(150,10%,15%)]">Linea Tecnologica</h4>
          </div>
          <p class="text-[15px] text-[hsl(150,10%,15%)] pl-12">Tecnologias de la Informacion y las Comunicaciones</p>
        </div>

        <!-- Fechas -->
        <div class="border border-[hsl(100,10%,90%)] rounded-2xl bg-white px-6 py-5">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 bg-[hsl(105,40%,92%)] rounded-xl flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-[18px] h-[18px] text-[hsl(105,100%,33%)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
              </svg>
            </div>
            <h4 class="text-[15px] font-bold text-[hsl(150,10%,15%)]">Fechas</h4>
          </div>
          <div class="pl-12 flex flex-col gap-3">
            <div class="flex items-baseline">
              <span class="text-[13px] text-[hsl(150,5%,45%)] w-24">Creacion</span>
              <span class="text-[15px] font-semibold text-[hsl(150,10%,15%)]">15 de noviembre de 2025</span>
            </div>
            <div class="flex items-baseline">
              <span class="text-[13px] text-[hsl(150,5%,45%)] w-24">Expiracion</span>
              <span class="text-[15px] font-semibold text-[hsl(150,10%,15%)]">15 de mayo de 2026</span>
            </div>
          </div>
        </div>

        <!-- Perfil Vigente -->
        <div class="border border-[hsl(100,10%,90%)] rounded-2xl bg-[hsl(105,25%,95%)] px-6 py-5">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[hsl(105,100%,33%)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
            </div>
            <div>
              <h4 id="detalle-estado-titulo" class="text-[15px] font-bold text-[hsl(105,100%,33%)] leading-tight">Perfil Vigente</h4>
              <p id="detalle-estado-texto" class="text-[13px] text-[hsl(150,5%,45%)] mt-0.5">Este perfil se encuentra activo</p>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>

  <script src="../../assets/js/lineas_tecnologicas.js"></script>

  <?= $footerFragment ?>

</body>
</html>