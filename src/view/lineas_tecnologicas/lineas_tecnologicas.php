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
        <button type="button" id="btn-nueva-linea" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
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

        <!-- Card 1: Computacion en la Nube -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Computacion en la Nube</h3>
          <p class="text-sm text-sena-text-soft">4 vigentes &middot; 2 lineas &middot; 4 perfiles</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Servicios y Gestion</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 2: Ciberseguridad Avanzada -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Ciberseguridad Avanzada</h3>
          <p class="text-sm text-sena-text-soft">4 vigentes &middot; 1 linea &middot; 4 perfiles</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 3: Big Data y Analitica -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Big Data y Analitica</h3>
          <p class="text-sm text-sena-text-soft">3 vigentes &middot; 2 lineas &middot; 3 perfiles</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Biotecnologia y Nanotecnologia</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 4: Inteligencia Artificial -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Inteligencia Artificial</h3>
          <p class="text-sm text-sena-text-soft">2 vigentes &middot; 2 lineas &middot; 2 perfiles</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Biotecnologia y Nanotecnologia</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 5: Blockchain -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Blockchain</h3>
          <p class="text-sm text-sena-text-soft">2 vigentes &middot; 1 linea &middot; 2 perfiles</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 6: Internet de las Cosas (IoT) -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Internet de las Cosas (IoT)</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea &middot; 1 perfil</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Electronica y Automatizacion</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 7: Manufactura Aditiva (Impresion 3D) -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Manufactura Aditiva (Impresion 3D)</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea &middot; 1 perfil</p>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 8: Realidad Aumentada/Virtual -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Realidad Aumentada/Virtual</h3>
          <p class="text-sm text-sena-text-soft">0 vigentes &middot; 1 linea &middot; 1 perfil</p>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 9: Robotica Colaborativa -->
        <div class="tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <div class="flex items-center gap-1 shrink-0">
              <button type="button" aria-label="Editar linea" class="p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                </svg>
              </button>
              <button type="button" aria-label="Cambiar estado" class="linea-tec-switch relative inline-flex w-9 h-5 items-center rounded-full bg-sena transition-colors">
                <span class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform"></span>
              </button>
            </div>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Robotica Colaborativa</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea &middot; 2 perfiles</p>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

      </div>
    </div>
  </main>

  <script src="../../assets/js/lineas_tecnologicas.js"></script>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>