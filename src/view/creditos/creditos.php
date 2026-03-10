<?php
// creditos.php
// Este archivo genera la misma salida HTML que antes, pero usando sintaxis PHP
// para imprimir todo el contenido. Usamos nowdoc para evitar el parseo de variables.

include __DIR__ . '../../../includes/header.php'; // ruta relativa al archivo actual

echo 
<<<'HTML'
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Créditos SENNOVA</title>
  <meta name="description" content="Equipo del Sistema de Investigación, Desarrollo Tecnológico e Innovación - SENA" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../assets/css/creditos/creditos.css">
  <link rel="stylesheet" href="../../../assets/css/output.css">
</head>
<body class="font-['Inter'] text-sena-text-main antialiased">

  <!-- Hero -->
  <header class="bg-gradient-to-b from-sena-soft to-white text-center px-6 py-20 md:py-24">
    <div class="inline-flex items-center border border-sena-border bg-sena-soft rounded-full px-5 py-2 text-sm font-medium text-sena animate">
      Plataforma Institucional SENA
    </div>
    <h1 class="font-['Plus_Jakarta_Sans'] text-4xl md:text-5xl lg:text-6xl font-extrabold mt-8 leading-[1.15] animate delay-1">
      Créditos Observatorio de <br>Perfiles <span class="text-sena">Tecnologicos</span>
    </h1>
    <p class="mt-4 text-sena-text-soft max-w-[540px] mx-auto text-base animate delay-2">
      Conoce al equipo detrás del Sistema de Investigación, Desarrollo Tecnológico e Innovación.
    </p>
  </header>

  <!-- Content -->
  <main class="max-w-[64rem] mx-auto px-6 py-16">
    <div class="flex flex-col gap-20">

<!-- Subdirectora -->
<section>
  <div class="flex items-center gap-2 mb-2 text-xs font-bold tracking-wider uppercase text-sena">
    <svg class="w-4 h-4 stroke-current stroke-2 fill-none text-sena" viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
    Dirección
  </div>

  <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Equipo Directivo</h2>
  
  <!-- Flexbox con cards de ancho fijo -->
  <div class="flex flex-wrap justify-center gap-6 mt-8">
    <!-- Card Subdirectora con ancho fijo -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 w-[280px] p-8 text-center min-h-[260px] animate delay-1">
      <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/subdirectora_creador.jpg"/>
      <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Sandra Yulieth García González</div>
      <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Subdirectora</span>
      <div class="mt-2 text-xs text-sena-text-soft">Subdirección de Centro</div>
      <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:sandra@sena.edu.co">
        <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
        sgarciag@sena.edu.co
      </a>
    </div>

    <!-- Card Juan Carlos con ancho fijo -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 w-[280px] p-8 text-center min-h-[260px] animate delay-2">
      <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/sennova_creador.jpeg"/>
      <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">MSc. Ing. Juan Carlos Garcia Buitrago</div>
      <span class="inline-flex justify-center items-center mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Coordinador Sennova</span>
      <div class="mt-2 text-xs text-sena-text-soft">Lider Sistema de Innovación y Competitividad-CDITI</div>
      <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:jcgarcia@sena.edu.co">
        <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
        jcgarcia@sena.edu.co 
      </a>
    </div>
  </div>
</section>

<!-- Instructores -->
<section>
  <div class="flex items-center gap-2 mb-2 text-xs font-bold tracking-wider uppercase text-sena">
    <svg class="w-4 h-4 stroke-current stroke-2 fill-none text-sena" viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
    Líderes
  </div>
  <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Instructores</h2>
  <!-- Flexbox con cards de ancho fijo -->
  <div class="flex flex-wrap justify-center gap-6 mt-8">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 w-[280px] p-8 text-center min-h-[260px] animate delay-1">
      <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/yuely_creador.jpeg" alt="Foto de Yuely" />
      <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Ing. Yuely Adriana Arce Arias</div>
      <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Instructora Area de Teleinformatica</span>
      <div class="mt-2 text-xs text-sena-text-soft">Ingeniera en Sistemas</div>
      <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:yarcea@sena.edu.co">
        <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
        yarcea@sena.edu.co
      </a>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 w-[280px] p-8 text-center min-h-[260px] animate delay-2">
      <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/sebas_creador.jpeg" alt="Foto de Sebastián" />
      <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Ing. Sebastian Duque Vera</div>
      <span class="inline-flex justify-center items-center mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Instructor Area de Teleinformatica</span>
      <div class="mt-2 text-xs text-sena-text-soft">Ingeniero en Sistemas</div>
      <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:sebas@sena.edu.co">
        <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
        sebas@sena.edu.co
      </a>
    </div>
  </div>
</section>
      <!-- Equipo -->
      <section>
        <div class="flex items-center gap-2 mb-2 text-xs font-bold tracking-wider uppercase text-sena">
          <svg class="w-4 h-4 stroke-current stroke-2 fill-none text-sena" viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
          Colaboradores
        </div>
        <h2 class="font-['Plus_Jakarta_Sans'] text-3xl font-bold">Equipo de Trabajo</h2>
        <!-- Grid con ancho máximo de 650px para 3 columnas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 max-w-[650px] mx-auto mt-8">
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-1">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/juanes_creador.jpeg" alt="Foto de Camila Rodríguez" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Juan Esteban Soto Cardona</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Desarrollador Front-end</span>
            <div class="mt-2 text-xs text-sena-text-soft">Ingeniero de Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:camila.rodriguez@sena.edu.co">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              sotojuanes15@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-2">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/lissi_creador.jpeg" alt="Foto de Lisseth Soto Monsalve" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Lisseth Soto Monsalve</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Lider de Proyecto</span>
            <div class="mt-2 text-xs text-sena-text-soft">Analisis y Desarrollo de Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:andres.lisisotomonsalve@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              lisisotomonsalve@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-3">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/kev_creador.jpeg" alt="Foto de Kevin Andres Duarte Hurtado" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Kevin Andres Duarte Hurtado</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Lider Front-end</span>
            <div class="mt-2 text-xs text-sena-text-soft">Ingeniero de Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:hh.andress78@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              hh.andress78@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-4">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/samuel_creador.jpeg" alt="Foto de David Torres" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Samuel Monsalve Gomez</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Lider Back-End</span>
            <div class="mt-2 text-xs text-sena-text-soft">Ingeniero de Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:monsalvegomezsamuel2@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              monsalvegomezsamuel2@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-5">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/juanjo_creador.jpeg" alt="Foto de Juan José Candamil Pérez" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Juan José Candamil Pérez</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Especialista en Bases de Datos</span>
            <div class="mt-2 text-xs text-sena-text-soft">Ingeniero en Sistemas</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:yomacjc@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              yomacjc@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-6">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/julian_creador.jpeg" alt="Foto de Carlos Moreno" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Julian Osorio González</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Gestor de Documentación</span>
            <div class="mt-2 text-xs text-sena-text-soft">Ingeniero en Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:julianchin1226@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              julianchin1226@gmail.com
            </a>
          </div>
          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 p-8 text-center animate delay-6 md:col-start-2">
            <img class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 mx-auto mb-4 hover:border-sena-strong transition-all duration-300 hover:scale-105" src="../../assets/img/creadores_img/isaac_creador.jpeg" alt="Foto de Isaac Echeverry Garcia" />
            <div class="font-['Plus_Jakarta_Sans'] font-bold text-base">Isaac Echeverry Garcia</div>
            <span class="inline-block mt-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-sena-soft text-sena whitespace-nowrap">Desarrollador Back-End</span>
            <div class="mt-2 text-xs text-sena-text-soft">Analisis y Desarrollo de Software</div>
            <a class="mt-3 inline-flex items-center gap-1.5 text-xs text-sena-text-soft hover:text-sena transition-colors" href="mailto:isaacecheverry53@gmail.com">
              <svg class="w-3 h-3 stroke-current stroke-2 fill-none" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              isaacecheverry53@gmail.com
            </a>
          </div>
        </div> 
      </section>

    </div>
  </main>


</body>
</html>
HTML;

include __DIR__ . '../../../includes/footer.php'; // ruta relativa al archivo actual
?>