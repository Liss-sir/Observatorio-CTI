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
  <title>Detalle de Linea Tecnologica - SENA Observatorio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <?= $headerFragment ?>

  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

    <!-- ============ BACK LINK ============ -->
    <a href="lineas_tecnologicas.php" class="inline-flex items-center gap-1.5 text-sm text-sena-text-soft hover:text-sena-text-main transition-colors mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
      Volver a resultados
    </a>

    <div class="mb-8">
      <!-- ============ BADGES ============ -->
      <div class="flex flex-wrap items-center gap-3 mb-3">
        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena-soft text-sena-strong">
          Lineas Tecnologicas
        </span>
        <span id="detalle-badge-estado" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4" />
          </svg>
          Vigente
        </span>
      </div>

      <!-- ============ TITLE + ACTIONS ROW ============ -->
      <div class="flex items-start justify-between gap-6 flex-wrap">
        <h1 id="detalle-linea-titulo" class="text-2xl lg:text-3xl font-bold text-sena-text-main">
          Linea Tecnologica
        </h1>
        <div class="flex items-center gap-3 flex-shrink-0">
        <!-- Editar -->
        <button id="btn-detalle-editar" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena-border rounded-lg bg-white text-sena-text-main hover:bg-sena-soft transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
          Editar
        </button>
        <!-- Desactivar -->
        <button id="btn-detalle-deshabilitar" type="button" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
          Desactivar
        </button>
        </div>
      </div>
    </div>

    <!-- ============ TWO-COLUMN LAYOUT ============ -->
    <div class="grid gap-6 lg:grid-cols-[2fr_1fr] items-start">

      <!-- ======== LEFT COLUMN ======== -->
      <div class="flex-1 min-w-0 border border-[hsl(100,10%,90%)] rounded-2xl bg-white p-8 lg:p-10">

        <!-- Description -->
        <div class="mb-0">
          <h4 class="text-[11px] font-bold text-[hsl(150,5%,45%)] uppercase tracking-[0.15em] mb-4">
            Descripcion de la Linea Tecnológica
          </h4>
          <p id="detalle-descripcion" class="text-[15px] text-[hsl(150,10%,15%)] leading-[1.75] max-w-prose">
            Informacion de contexto de la linea tecnológica.
          </p>
        </div>
      </div>

      <!-- ======== RIGHT COLUMN ======== -->
      <div class="flex flex-col gap-4">

      <!-- Tendencia Tecnologica Emergente -->
        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L12 12.75 6.429 9.75m11.142 0 4.179 2.25-9.75 5.25-9.75-5.25 4.179-2.25" /></svg>
            Tendencia Tecnologica Emergente
          </h3>
          <p id="detalle-tendencia-texto" class="text-sm font-medium text-sena-text-main">No registrada</p>
        </div>

        <!-- Planeacion -->
        <div class="border border-sena-border rounded-xl bg-white p-5">
          <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-sena"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            Planeacion
          </h3>
          <div class="space-y-2">
            <div class="flex items-baseline gap-3">
              <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Etapa</span>
              <span id="detalle-etapa-texto" class="text-sm font-medium text-sena-text-main">No registrada</span>
            </div>
            <div class="flex items-baseline gap-3">
              <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Proyeccion</span>
              <span id="detalle-proyeccion-texto" class="text-sm font-medium text-sena-text-main">No registrada</span>
            </div>
            <div class="flex items-baseline gap-3">
              <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Actualizado</span>
              <span id="detalle-fecha-actualizacion" class="text-sm font-medium text-sena-text-main">Sin registro</span>
            </div>
          </div>
        </div>

        <!-- Linea Vigente -->
        <div class="border border-sena/30 rounded-xl bg-sena/5 p-5 flex items-center gap-3">
          <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sena flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4"/></svg>
            <div>
              <h4 id="detalle-estado-titulo" class="text-sm font-semibold text-sena">Linea Vigente</h4>
              <p id="detalle-estado-texto" class="text-xs text-sena-text-soft">Esta linea se encuentra activa</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    </div>
  </main>

  <script src="../../assets/js/lineas_tecnologicas.js"></script>

  <?= $footerFragment ?>

</body>
</html>