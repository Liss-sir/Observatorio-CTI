<?php
// filepath: c:\wamp64\www\Observatorio-CTI\src\view\tendencias\tecnologias_emergentes.php
include __DIR__ . '../../../includes/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lineas Tecnologicas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">
  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- Page Header -->
      <div class="mb-6">
        <h1 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Lineas Tecnologicas</h1>
        <p class="mt-2 text-sm text-sena-text-soft">Clasificacion de perfiles por areas tecnologicas del SENA</p>
      </div>

      <!-- Search + Actions -->
      <div class="mb-4 flex items-center gap-3 w-full">
        <div class="relative flex-1 min-w-0">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input id="input-buscar-linea" type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar lineas...">
        </div>
        <button data-permiso="crear_linea" type="button" id="btn-nueva-linea" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
          Nueva Linea Tecnológica
        </button>
      </div>

      <p class="text-sm text-sena-text-soft mb-6">
        <strong id="lineas-encontradas-count" class="font-medium text-sena-text-main">0</strong>
        <span id="lineas-encontradas-label">lineas encontradas</span>
      </p>

      <!-- Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      </div>

      <!-- Empty State -->
      <div id="linea-empty-state" class="hidden w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
        <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="3"></circle>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
          </svg>
        </div>
        <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No hay líneas tecnológicas</h3>
        <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">Comienza creando tu primera línea tecnológica.</p>
        <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Crear primera línea
        </button>
      </div>
    </div>
  </main>
              </svg>
        </div>
      </div>
    </div>
  </main>

  <script src="../../assets/js/lineas_tecnologicas/lineas_tecnologicas.js"></script>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>