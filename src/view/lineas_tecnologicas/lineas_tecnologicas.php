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
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">
  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- Page Header -->
      <div class="mb-5">
        <div>
          <h1 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main">Lineas Tecnologicas</h1>
          <p class="mt-2 text-sm text-sena-text-soft">Clasificacion de perfiles por areas tecnologicas del SENA</p>
        </div>
      </div>

      <!-- Search + Main Action -->
      <div class="mb-6">
        <div class="flex flex-wrap items-center gap-2.5">
          <div class="relative min-w-[260px] flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-sena-text-soft">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.3-4.3"></path>
            </svg>
            <input
              type="search"
              id="input-buscar-linea"
              placeholder="Buscar lineas tecnologicas..."
              class="h-10 w-full rounded-lg border border-sena-border bg-white pl-10 pr-3 text-sm text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:outline-none focus:ring-2 focus:ring-sena/15"
            >
          </div>
          <button type="button" id="btn-nueva-linea" class="flex h-10 items-center gap-2 rounded-lg bg-sena px-5 text-sm font-medium text-white whitespace-nowrap hover:opacity-90 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
            Nueva Linea Tecnológica
          </button>
        </div>
      </div>

      <!-- Cards Grid -->
      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        <!-- Card 1: Inteligencia Artificial -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">2 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Inteligencia Artificial</h3>
          <p class="text-sm text-sena-text-soft">2 vigentes &middot; 2 lineas</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Biotecnologia y Nanotecnologia</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 2: Blockchain -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">2 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Blockchain</h3>
          <p class="text-sm text-sena-text-soft">2 vigentes &middot; 1 linea</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 3: Internet de las Cosas (IoT) -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">1 perfil</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Internet de las Cosas (IoT)</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Electronica y Automatizacion</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 4: Computacion en la Nube -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">4 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Computacion en la Nube</h3>
          <p class="text-sm text-sena-text-soft">4 vigentes &middot; 2 lineas</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Servicios y Gestion</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 5: Ciberseguridad Avanzada -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">4 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Ciberseguridad Avanzada</h3>
          <p class="text-sm text-sena-text-soft">4 vigentes &middot; 1 linea</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 6: Big Data y Analitica -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">3 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Big Data y Analitica</h3>
          <p class="text-sm text-sena-text-soft">3 vigentes &middot; 2 lineas</p>
          <div class="flex flex-wrap gap-2">
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Tecnologias de la</span>
            <span class="text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5">Biotecnologia y Nanotecnologia</span>
          </div>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 7: Realidad Aumentada/Virtual -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">1 perfil</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Realidad Aumentada/Virtual</h3>
          <p class="text-sm text-sena-text-soft">0 vigentes &middot; 1 linea</p>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 8: Robotica Colaborativa -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">2 perfiles</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Robotica Colaborativa</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea</p>
          <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">
            Ver perfiles &rarr;
          </a>
        </div>

        <!-- Card 9: Manufactura Aditiva (Impresion 3D) -->
        <div class="bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
              <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
              </svg>
            </div>
            <span class="text-sm text-sena-text-soft">1 perfil</span>
          </div>
          <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug">Manufactura Aditiva (Impresion 3D)</h3>
          <p class="text-sm text-sena-text-soft">1 vigente &middot; 1 linea</p>
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