<?php
include __DIR__ . '../../../includes/header.php'; 
include __DIR__ . '/modal_crear_perfiles.php';
include __DIR__ . '/modal_editar_perfiles.php'; 
include __DIR__ . '/modal_deshabilitar_perfiles.php';
include __DIR__ . '/modal_habilitar_perfiles.php';           
include __DIR__ . '/modal_habilitado_confirmacion.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_pefiles.php';
include __DIR__ . '/modal_editar_confirmacion.php';
include __DIR__ . '/modal_creado_confirmacion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SENA - Administrador Perfiles</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  
  <!-- Estilos para el switch personalizado -->
  <style>
    /* Switch personalizado */
    .switch-sena {
      position: relative;
      width: 36px;
      height: 20px;
      background-color: #9ca3af; /* gris por defecto */
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
      background-color: #39A900; /* Verde SENA */
    }

    .switch-sena.active::after {
      transform: translateX(16px);
    }

    /* Animación al hacer clic */
    .switch-sena:active::after {
      width: 20px;
    }

    .switch-sena.active:active::after {
      transform: translateX(12px);
    }

    /* Estilo para deshabilitado */
    .switch-sena.inactive {
      background-color: #9ca3af; /* gris */
      cursor: not-allowed;
      opacity: 0.7;
    }

    .switch-sena.inactive::after {
      transform: translateX(0);
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">


      <!-- Search Bar + Action Buttons -->
      <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <input type="text" class="w-full h-10 pl-10 pr-3 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main placeholder:text-sena-text-soft focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none transition-all" placeholder="Buscar perfiles...">
        </div>
        <a href="../../view/perfiles/mis_creaciones_perfiles.php" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg bg-white whitespace-nowrap h-10 hover:bg-sena-soft transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Mis creaciones
        </a>
        <button id="btn-nuevo-perfil" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Nuevo Perfil
        </button>
      </div>

      <!-- Content Layout -->
      <div class="flex gap-8">

        <!-- Sidebar Filters -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
          <div class="sticky top-24 border border-sena-border rounded-xl bg-white p-5">
            <h3 class="text-sm font-semibold text-sena-text-main mb-4">Filtros</h3>

            <!-- Estado -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Estado</span>
              <div class="relative">
                <select class="w-full h-10 pl-3 pr-8 text-sm border border-sena-border rounded-lg bg-white text-sena-text-main appearance-none focus:border-sena focus:ring-2 focus:ring-sena/15 outline-none cursor-pointer">
                  <option value="todos">Todos</option>
                  <option value="vigente">Vigente</option>
                  <option value="expirado">Expirado</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-sena-text-soft pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </div>
            </div>

            <!-- Linea Tecnologica -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Linea Tecnologica</span>
              <div class="flex flex-col gap-2.5">
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Tecnologias de la Informacion y las Comunicaciones</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Biotecnologia y Nanotecnologia</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Diseno e Innovacion</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Produccion y Transformacion</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Materiales e Industria</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Electronica y Automatizacion</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Servicios y Gestion Empresarial</span>
                </label>
              </div>
            </div>

            <!-- Tecnologia Emergente -->
            <div class="mb-6">
              <span class="block mb-3 text-[0.6875rem] font-semibold uppercase tracking-wider text-sena-text-soft">Tecnologia Emergente</span>
              <div class="flex flex-col gap-2.5">
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Inteligencia Artificial</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Blockchain</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Internet de las Cosas (IoT)</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Computacion en la Nube</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Ciberseguridad Avanzada</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Big Data y Analitica</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Realidad Aumentada/Virtual</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Robotica Colaborativa</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Manufactura Aditiva (Impresion 3D)</span>
                </label>
                <label class="flex items-start gap-2.5 cursor-pointer">
                  <input type="checkbox" class="w-4 h-4 border-2 border-sena-border rounded appearance-none bg-white checked:bg-sena checked:border-sena checked:after:content-[''] checked:after:absolute checked:after:left-[0.1875rem] checked:after:top-0 checked:after:w-[0.3125rem] checked:after:h-[0.5625rem] checked:after:border-white checked:after:border-b-2 checked:after:border-r-2 checked:after:rotate-45 relative flex-shrink-0 mt-0.5">
                  <span class="text-sm text-sena-text-main">Automatizacion de Procesos (RPA)</span>
                </label>
              </div>
            </div>
          </div>
        </aside>

        <!-- Results -->
        <div class="flex-1 min-w-0">
          <p class="text-sm text-sena-text-soft mb-4"><strong class="font-medium text-sena-text-main">12</strong> perfiles encontrados</p>

          <div class="flex flex-col gap-3">

            <!-- Profile Cards -->
            <!-- Profile Card 1: Desarrollador Full Stack Senior -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Desarrollador Full Stack Senior</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Tecnologias de la Informacion y las Comunicaciones
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    TechColombia S.A.S.
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="1">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="../../view/perfiles/perfiles_detalles.php" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 2: Especialista en Inteligencia Artificial -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Especialista en Inteligencia Artificial</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Tecnologias de la Informacion y las Comunicaciones
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    InnoSoft Ltda.
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="2">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 3: Analista de Datos Senior -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Analista de Datos Senior</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Big Data y Analitica
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    DataLab S.A.
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="3">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 4: Ingeniero DevOps -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Ingeniero DevOps</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Computacion en la Nube
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    CloudOps Inc.
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="4">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 5: Diseñador UX/UI -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Diseñador UX/UI</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Expirado</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Diseno e Innovacion
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    StudioUX
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="5">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH INACTIVO (GRIS) -->
                <div class="switch-sena" title="Inactivo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 6: Especialista en Ciberseguridad -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Especialista en Ciberseguridad</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Ciberseguridad Avanzada
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    SecureNet
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="6">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 7: Ingeniero de Robotica -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Ingeniero de Robotica</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Robotica Colaborativa
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    RoboTech
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="7">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 8: Gerente de Producto Tecnológico -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Gerente de Producto Tecnológico</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Servicios y Gestion Empresarial
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    Prodtiva
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="8">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 9: Investigador en Biotecnología -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Investigador en Biotecnología</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Biotecnologia y Nanotecnologia
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    BioNova
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="9">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 10: Ingeniero de Automatización -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Ingeniero de Automatización</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Expirado</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Electronica y Automatizacion
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    AutoPlus
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="10">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH INACTIVO (GRIS) -->
                <div class="switch-sena" title="Inactivo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 11: Especialista en Blockchain -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Especialista en Blockchain</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Blockchain
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    BlockForge
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="11">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

            <!-- Profile Card 12: Arquitecto Cloud -->
            <div class="border border-sena-border rounded-lg bg-white p-4 flex items-center gap-4 hover:border-sena/30 hover:shadow-sm transition-all">
              <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <span class="text-sm font-semibold text-sena-text-main truncate group-hover:text-sena">Arquitecto Cloud</span>
                  <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.625rem] font-medium bg-sena/10 text-sena flex-shrink-0">Vigente</span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-sena-text-soft">
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                    Computacion en la Nube
                  </span>
                  <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    NimbusCloud
                  </span>
                </div>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                <button class="btn-editar-perfil flex items-center justify-center w-8 h-8 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors" title="Editar" data-perfil-id="12">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                </button>
                
                <!-- SWITCH ACTIVO (VERDE) -->
                <div class="switch-sena active" title="Activo"></div>
                
                <a href="#" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-sena rounded-lg whitespace-nowrap hover:bg-sena/5 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                  Ver detalle
                </a>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- JavaScript unificado para modales y switches -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
      // ===== MODAL CREAR PERFIL =====
      const btnNuevoPerfil = document.getElementById('btn-nuevo-perfil');
      const modalCrear = document.getElementById('modal-crear-perfil');
      
      // ===== MODAL EDITAR PERFIL =====
      const btnEditar = document.querySelectorAll('.btn-editar-perfil');
      const modalEditar = document.getElementById('modal-editar-perfil');
      
      // ===== MODALES PARA DESHABILITAR =====
      const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil'); // Modal de confirmación
      const modalDeshabilitado = document.getElementById('modal-deshabilitado-perfil'); // Modal de éxito
      const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
      const nombrePerfilDeshabilitadoSpan = document.getElementById('nombre-perfil-deshabilitado');
      
      // ===== MODALES PARA HABILITAR =====
      const modalHabilitar = document.getElementById('modal-habilitar-perfil');
      const modalHabilitadoConfirmacion = document.getElementById('modal-habilitado-confirmacion');
      const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
      const nombrePerfilHabilitarSpan = document.getElementById('nombre-perfil-habilitar');
      const nombrePerfilHabilitadoExitoSpan = document.getElementById('nombre-perfil-habilitado-exito');
      
      // ===== BOTONES DE CERRAR =====
      const cerrarBtnsCrear = document.querySelectorAll('.cerrar-modal-crear');
      const cerrarBtnsEditar = document.querySelectorAll('.cerrar-modal');
      const cerrarBtnsDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
      const cerrarBtnsHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
      const cerrarBtnsHabilitadoConfirmacion = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
      const cerrarBtnsDeshabilitado = document.querySelectorAll('.cerrar-modal-deshabilitado');

      // ===== MODAL EDITAR CONFIRMACIÓN =====
      const modalEditadoConfirmacion = document.getElementById('modal-editado-confirmacion');
      const nombrePerfilEditadoSpan = document.getElementById('nombre-perfil-editado');
      const cerrarBtnsEditado = document.querySelectorAll('.cerrar-modal-editado');

      // ===== MODAL CREADO CONFIRMACIÓN =====
      const modalCreadoConfirmacion = document.getElementById('modal-creado-confirmacion');
      const nombrePerfilCreadoSpan = document.getElementById('nombre-perfil-creado');
      const cerrarBtnsCreado = document.querySelectorAll('.cerrar-modal-creado');
      
      // ===== TIMERS =====
      let timeoutDeshabilitado = null;
      let timeoutHabilitado = null;
      let timeoutEditado = null;
      let timeoutCreado = null;
      let intervalContadorHabilitado = null;
      let intervalContadorDeshabilitado = null;
      let intervalContadorEditado = null;
      let intervalContadorCreado = null;
      
      // ===== FUNCIÓN PARA ABRIR MODAL =====
      function abrirModal(modal) {
        if (modal) {
          modal.classList.remove('hidden');
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
      
      // ===== FUNCIÓN PARA CERRAR MODAL DESHABILITADO =====
      function cerrarModalDeshabilitado() {
        if (modalDeshabilitado) {
          modalDeshabilitado.classList.add('hidden');
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
        const contador = document.getElementById('contador-segundos');
        if (contador) {
          contador.textContent = '3';
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
      
      // ===== FUNCIÓN PARA MOSTRAR MODAL DESHABILITADO =====
      function mostrarModalDeshabilitado(nombrePerfil) {
        console.log('Mostrando modal deshabilitado para:', nombrePerfil);
        
        if (!modalDeshabilitado) {
          console.error('Modal deshabilitado no encontrado');
          return;
        }
        
        // Actualizar nombre del perfil
        if (nombrePerfilDeshabilitadoSpan) {
          nombrePerfilDeshabilitadoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        // Abrir modal
        abrirModal(modalDeshabilitado);
        
        // Resetear contador
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
        
        // Animar barra de progreso
        const progressBar = document.getElementById('progress-bar-deshabilitado');
        if (progressBar) {
          progressBar.style.width = '0%';
          setTimeout(() => {
            progressBar.style.width = '100%';
          }, 50);
        }
        
        // Programar cierre automático
        if (timeoutDeshabilitado) {
          clearTimeout(timeoutDeshabilitado);
        }
        
        timeoutDeshabilitado = setTimeout(() => {
          cerrarModalDeshabilitado();
        }, 3000);
      }

      // ===== FUNCIÓN PARA MOSTRAR MODAL EDITADO =====
      function mostrarModalEditado(nombrePerfil) {
        console.log('Mostrando modal editado para:', nombrePerfil);
        
        if (!modalEditadoConfirmacion) {
          console.error('Modal editado no encontrado');
          return;
        }
        
        if (nombrePerfilEditadoSpan) {
          nombrePerfilEditadoSpan.textContent = `"${nombrePerfil}"`;
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
      
      // ===== FUNCIÓN PARA MOSTRAR MODAL HABILITADO =====
      function mostrarModalHabilitado(nombrePerfil) {
        console.log('Mostrando modal habilitado para:', nombrePerfil);
        
        if (!modalHabilitadoConfirmacion) {
          console.error('Modal habilitado no encontrado');
          return;
        }
        
        if (nombrePerfilHabilitadoExitoSpan) {
          nombrePerfilHabilitadoExitoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalHabilitadoConfirmacion);
        
        // Resetear contador
        let segundos = 3;
        const contador = document.getElementById('contador-segundos');
        if (contador) {
          contador.textContent = segundos;
        }
        
        // Iniciar contador regresivo
        if (intervalContadorHabilitado) {
          clearInterval(intervalContadorHabilitado);
        }
        
        intervalContadorHabilitado = setInterval(() => {
          segundos--;
          if (contador) {
            contador.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervalContadorHabilitado);
            intervalContadorHabilitado = null;
          }
        }, 1000);
        
        // Animar barra de progreso
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
      function mostrarModalCreado(nombrePerfil) {
        console.log('Mostrando modal creado para:', nombrePerfil);
        
        if (!modalCreadoConfirmacion) {
          console.error('Modal creado no encontrado');
          return;
        }
        
        if (nombrePerfilCreadoSpan) {
          nombrePerfilCreadoSpan.textContent = `"${nombrePerfil}"`;
        }
        
        abrirModal(modalCreadoConfirmacion);
        
        // Resetear contador
        let segundos = 3;
        const contador = document.getElementById('contador-segundos-creado');
        if (contador) {
          contador.textContent = segundos;
        }
        
        // Iniciar contador regresivo
        if (intervalContadorCreado) {
          clearInterval(intervalContadorCreado);
        }
        
        intervalContadorCreado = setInterval(() => {
          segundos--;
          if (contador) {
            contador.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervalContadorCreado);
            intervalContadorCreado = null;
          }
        }, 1000);
        
        // Animar barra de progreso
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
      
      // ===== EVENTOS MODAL CREAR =====
      if (btnNuevoPerfil) {
        btnNuevoPerfil.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModal(modalCrear);
          
          // Establecer fecha de creación por defecto: hoy
          var today = new Date().toISOString().split('T')[0];
          var fechaCreacion = document.querySelector('#modal-crear-perfil input[name="fechaCreacion"]');
          if (fechaCreacion) fechaCreacion.value = today;
        });
      }
      
      cerrarBtnsCrear.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModal(modalCrear);
        });
      });
      
      // ===== EVENTOS MODAL EDITAR =====
      btnEditar.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModal(modalEditar);
          
          // Aquí puedes cargar los datos del perfil según el ID
          const perfilId = this.getAttribute('data-perfil-id');
          console.log('Editar perfil ID:', perfilId);
        });
      });
      
      cerrarBtnsEditar.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModal(modalEditar);
        });
      });
      
      // ===== MANEJAR CLICKS EN SWITCHES =====
      // IMPORTANTE: Remover cualquier evento anterior
      document.querySelectorAll('.switch-sena').forEach(switchEl => {
        // Clonar y reemplazar para eliminar eventos anteriores
        const newSwitch = switchEl.cloneNode(true);
        switchEl.parentNode.replaceChild(newSwitch, switchEl);
      });
      
      // Aplicar nuevo manejador a todos los switches
      document.querySelectorAll('.switch-sena').forEach(switchEl => {
        switchEl.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          // Obtener el nombre del perfil
          const cardPerfil = this.closest('.border');
          let nombrePerfil = "Perfil";
          
          if (cardPerfil) {
            const nombreElement = cardPerfil.querySelector('.text-sm.font-semibold');
            if (nombreElement) {
              nombrePerfil = nombreElement.textContent.trim();
            }
          }
          
          // Verificar el estado ACTUAL del switch
          const estaActivo = this.classList.contains('active');
          
          console.log('Switch clickeado - Estado:', estaActivo ? 'ACTIVO' : 'INACTIVO', 'Perfil:', nombrePerfil);
          
          if (estaActivo) {
            // Switch ACTIVO (verde) → DESHABILITAR
            if (btnConfirmarDeshabilitar) {
              btnConfirmarDeshabilitar.setAttribute('data-nombre-perfil', nombrePerfil);
            }
            abrirModal(modalDeshabilitar);
          } else {
            // Switch INACTIVO (gris) → HABILITAR
            if (nombrePerfilHabilitarSpan) {
              nombrePerfilHabilitarSpan.textContent = `"${nombrePerfil}"`;
            }
            if (btnConfirmarHabilitar) {
              btnConfirmarHabilitar.setAttribute('data-nombre-perfil', nombrePerfil);
            }
            abrirModal(modalHabilitar);
          }
        });
      });
      
      // ===== CERRAR MODALES =====
      cerrarBtnsDeshabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModal(modalDeshabilitar);
        });
      });
      
      cerrarBtnsHabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModal(modalHabilitar);
        });
      });
      
      cerrarBtnsHabilitadoConfirmacion.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModalHabilitado();
        });
      });
      
      cerrarBtnsDeshabilitado.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModalDeshabilitado();
        });
      });
      
      // Cerrar modal editado manualmente
      cerrarBtnsEditado.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModalEditado();
        });
      });
      
      // Cerrar modal creado manualmente
      cerrarBtnsCreado.forEach(btn => {
        btn.addEventListener('click', function() {
          cerrarModalCreado();
        });
      });
      
      // ===== CONFIRMAR DESHABILITAR =====
      if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function(e) {
          e.preventDefault();
          
          const nombrePerfil = this.getAttribute('data-nombre-perfil') || "Perfil";
          
          console.log('Confirmando deshabilitar - Perfil:', nombrePerfil);
          
          // Buscar TODOS los switches y encontrar el que corresponde al perfil
          document.querySelectorAll('.switch-sena').forEach(switchEl => {
            const cardPerfil = switchEl.closest('.border');
            if (cardPerfil) {
              const nombreElement = cardPerfil.querySelector('.text-sm.font-semibold');
              if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                // Este es el switch del perfil que estamos deshabilitando
                switchEl.classList.remove('active');
                switchEl.setAttribute('title', 'Inactivo');
                console.log('Switch cambiado a gris para:', nombrePerfil);
              }
            }
          });
          
          // Cerrar modal de confirmación
          cerrarModal(modalDeshabilitar);
          
          // Mostrar modal de éxito
          mostrarModalDeshabilitado(nombrePerfil);
        });
      }
      
      // ===== CONFIRMAR HABILITAR =====
      if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', function(e) {
          e.preventDefault();
          
          const nombrePerfil = this.getAttribute('data-nombre-perfil') || "Perfil";
          
          console.log('Confirmando habilitar - Perfil:', nombrePerfil);
          
          // Buscar TODOS los switches y encontrar el que corresponde al perfil
          document.querySelectorAll('.switch-sena').forEach(switchEl => {
            const cardPerfil = switchEl.closest('.border');
            if (cardPerfil) {
              const nombreElement = cardPerfil.querySelector('.text-sm.font-semibold');
              if (nombreElement && nombreElement.textContent.trim() === nombrePerfil) {
                // Este es el switch del perfil que estamos habilitando
                switchEl.classList.add('active');
                switchEl.setAttribute('title', 'Activo');
                console.log('Switch cambiado a verde para:', nombrePerfil);
              }
            }
          });
          
          // Cerrar modal de confirmación
          cerrarModal(modalHabilitar);
          
          // Mostrar modal de éxito
          mostrarModalHabilitado(nombrePerfil);
        });
      }
      
      // ===== CERRAR MODALES HACIENDO CLICK EN OVERLAY =====
      [modalCrear, modalEditar, modalDeshabilitar, modalDeshabilitado, modalHabilitar, modalHabilitadoConfirmacion, modalEditadoConfirmacion, modalCreadoConfirmacion].forEach(modal => {
        if (modal) {
          modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('fixed')) {
              if (modal === modalDeshabilitado) {
                cerrarModalDeshabilitado();
              } else if (modal === modalHabilitadoConfirmacion) {
                cerrarModalHabilitado();
              } else if (modal === modalEditadoConfirmacion) {
                cerrarModalEditado();
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
          if (modalCrear && !modalCrear.classList.contains('hidden')) {
            cerrarModal(modalCrear);
          } else if (modalEditar && !modalEditar.classList.contains('hidden')) {
            cerrarModal(modalEditar);
          } else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
            cerrarModal(modalDeshabilitar);
          } else if (modalDeshabilitado && !modalDeshabilitado.classList.contains('hidden')) {
            cerrarModalDeshabilitado();
          } else if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
            cerrarModal(modalHabilitar);
          } else if (modalHabilitadoConfirmacion && !modalHabilitadoConfirmacion.classList.contains('hidden')) {
            cerrarModalHabilitado();
          } else if (modalEditadoConfirmacion && !modalEditadoConfirmacion.classList.contains('hidden')) {
            cerrarModalEditado();
          } else if (modalCreadoConfirmacion && !modalCreadoConfirmacion.classList.contains('hidden')) {
            cerrarModalCreado();
          }
        }
      });
      
      // ===== MANEJAR FORMULARIO CREAR PERFIL =====
      const formCrear = document.querySelector('#modal-crear-perfil form');
      if (formCrear) {
        formCrear.addEventListener('submit', function(e) {
          e.preventDefault();
          
          var selectedChips = [];
          document.querySelectorAll('#modal-crear-perfil .chip-crear.selected').forEach(function(chip) {
            selectedChips.push(chip.getAttribute('data-value'));
          });

          // Obtener el nombre del perfil
          const nombrePerfil = document.querySelector('#modal-crear-perfil input[name="nombre"]').value || "Perfil";

          var data = {
            nombre: nombrePerfil,
            descripcion: document.querySelector('#modal-crear-perfil textarea[name="descripcion"]').value,
            fechaCreacion: document.querySelector('#modal-crear-perfil input[name="fechaCreacion"]').value,
            fechaExpiracion: document.querySelector('#modal-crear-perfil input[name="fechaExpiracion"]').value,
            estado: document.querySelector('#modal-crear-perfil select[name="estado"]').value,
            lineaTecnologica: document.querySelector('#modal-crear-perfil select[name="lineaTecnologica"]').value,
            tecnologiasEmergentes: selectedChips,
            empresa: document.querySelector('#modal-crear-perfil select[name="empresa"]').value
          };

          console.log('Perfil creado:', data);
          
          // Cerrar modal de creación
          cerrarModal(modalCrear);
          
          // Mostrar modal de éxito de creación
          mostrarModalCreado(nombrePerfil);
        });
      }
      
      // ===== MANEJAR FORMULARIO EDITAR PERFIL =====
      const formEditar = document.querySelector('#modal-editar-perfil form');
      if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
          e.preventDefault();
          
          var selectedChips = [];
          document.querySelectorAll('#modal-editar-perfil .chip.selected').forEach(function(chip) {
            selectedChips.push(chip.getAttribute('data-value'));
          });

          // Obtener el nombre del perfil
          const nombrePerfil = document.querySelector('#modal-editar-perfil input[name="nombre"]').value || "Perfil";

          var data = {
            nombre: nombrePerfil,
            descripcion: document.querySelector('#modal-editar-perfil textarea[name="descripcion"]').value,
            fechaCreacion: document.querySelector('#modal-editar-perfil input[name="fechaCreacion"]').value,
            fechaExpiracion: document.querySelector('#modal-editar-perfil input[name="fechaExpiracion"]').value,
            estado: document.querySelector('#modal-editar-perfil select[name="estado"]').value,
            lineaTecnologica: document.querySelector('#modal-editar-perfil select[name="lineaTecnologica"]').value,
            tecnologiasEmergentes: selectedChips,
            empresa: document.querySelector('#modal-editar-perfil select[name="empresa"]').value
          };

          console.log('Perfil actualizado:', data);
          
          // Cerrar modal de edición
          cerrarModal(modalEditar);
          
          // Mostrar modal de éxito de edición
          mostrarModalEditado(nombrePerfil);
        });
      }
      
      // ===== MANEJAR CHIPS =====
      // Chips del modal crear
      document.querySelectorAll('#modal-crear-perfil .chip-crear').forEach(chip => {
        chip.addEventListener('click', function() {
          this.classList.toggle('selected');
          this.classList.toggle('bg-sena');
          this.classList.toggle('text-white');
          this.classList.toggle('border-sena');
          this.classList.toggle('border-sena-border');
        });
      });
      
      // Chips del modal editar
      document.querySelectorAll('#modal-editar-perfil .chip').forEach(chip => {
        chip.addEventListener('click', function() {
          this.classList.toggle('selected');
          this.classList.toggle('bg-sena');
          this.classList.toggle('text-white');
          this.classList.toggle('border-sena');
          this.classList.toggle('border-sena-border');
        });
      });
      
      // ===== FUNCIONALIDAD SWITCH ORIGINAL (AHORA COMENTADA PARA EVITAR CONFLICTOS) =====
      // La funcionalidad de switches ahora está manejada arriba
      /*
      function initSwitches() {
        document.querySelectorAll('.switch-sena:not(.inactive)').forEach(switchEl => {
          switchEl.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle active class
            this.classList.toggle('active');
            
            // Actualizar atributo title
            if (this.classList.contains('active')) {
              this.setAttribute('title', 'Activo');
            } else {
              this.setAttribute('title', 'Inactivo');
            }
            
            console.log('Switch toggled:', this.classList.contains('active') ? 'ON' : 'OFF');
          });
        });
      }

      // Ejecutar inicialización de switches
      initSwitches();
      */
    });
  </script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>