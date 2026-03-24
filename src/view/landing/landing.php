<?php
// landing.php

include __DIR__ . '../../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Observatorio de Perfiles Tecnológicos</title>

  <!-- Styles -->
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/landing/landing.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  
  <!-- Lucide -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="font-[Inter] bg-white text-sena-text-main">

  <!-- HERO -->
  <section class="w-full bg-gradient-to-b from-sena-soft via-white to-white px-20 pt-16 pb-16">
    <div class="mx-auto max-w-3xl text-center">

      <div class="inline-flex items-center gap-2 rounded-full border border-sena-border bg-sena-soft px-4 py-1.5 text-xs font-bold text-sena-strong">
        Plataforma Institucional SENA
      </div>

      <h1 class="font-[Montserrat] mt-6 text-4xl font-bold leading-tight text-sena-text-main md:text-5xl">
        Observatorio de <br class="hidden md:block" />
        Perfiles <span class="text-sena">Tecnológicos</span>
      </h1>

      <p class="mx-auto mt-4 max-w-2xl text-sm font-semibold text-sena-text-muted md:text-base">
        Análisis de necesidades empresariales clasificadas por líneas tecnológicas y<br class="hidden md:block" />
        tecnologías emergentes del sector productivo risaraldense.
      </p>

      <!-- Search -->
      <!-- <div class="mx-auto mt-8 max-w-xl">
        <div class="flex items-center gap-2 rounded-xl border border-sena-border bg-white p-2 shadow-sm">
          <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-sena-text-muted"></i>
            <input
              class="w-full rounded-lg py-2 pl-10 pr-3 text-sm font-semibold text-sena-text-main outline-none placeholder:text-sena-text-muted"
              placeholder="Buscar perfiles tecnológicos..."
            />
          </div>
          <button class="btn-sena px-6 py-2 text-sm font-bold">
            Buscar
          </button>
        </div>
      </div> -->

    </div>
  </section>

  <!-- CONTENT -->
  <main class="w-full px-20 pb-20">

    <!-- Perfiles Recientes -->
    <section class="mt-10">
      <div class="flex items-end justify-between">
        <div>
          <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
            <i data-lucide="clock" class="h-4 w-4"></i>
            Recientes
          </div>
          <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">Perfiles Recientes</h2>
        </div>

        <a class="inline-flex items-center gap-2 text-sm font-bold text-sena-strong" href="#">
          Ver todos <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
      </div>

      <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">
              Tecnologías de
            </span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">
              Vigente
            </span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Desarrollador Full Stack Senior
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Computación en la Nube</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Ciberseguridad Avanzada</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              TechColombia S.A.S.
            </div>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">Vigente</span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Especialista en Inteligencia Artificial
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Perfil orientado a profesionales con experiencia en machine learning, deep…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Inteligencia Artificial</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Big Data y Analítica</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              InnoSoft Ltda.
            </div>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">Vigente</span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Analista de Ciberseguridad
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Se busca profesional para identificar vulnerabilidades, gestionar incidentes de…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Ciberseguridad Avanzada</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Blockchain</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              DataVision Corp.
            </div>
          </div>
        </article>

        <!-- Card 4 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">Vigente</span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Ingeniero de Datos
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Profesional especializado en la creación y mantenimiento de pipelines de datos,…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Big Data y Analítica</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Computación en la Nube</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              CloudNet Colombia
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Líneas Tecnológicas -->
    <section class="mt-12 w-full">
      <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
        <i data-lucide="layers" class="h-4 w-4"></i>
        Clasificación
      </div>

      <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">
        Líneas Tecnológicas
      </h2>

      <div class="mt-5 grid w-full grid-cols-1 gap-4 md:grid-cols-4">
        <!-- TIC -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              TIC
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Tecnologías de la Información y las Comunicaciones
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                6 perfiles registrados
              </div>
            </div>
          </div>
        </article>

        <!-- BIO -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              BIO
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Biotecnología y Nanotecnología
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                1 perfil registrado
              </div>
            </div>
          </div>
        </article>

        <!-- DIS -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              DIS
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Diseño e Innovación
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                1 perfil registrado
              </div>
            </div>
          </div>
        </article>

        <!-- PRO -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              PRO
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Producción y Transformación
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                0 perfiles registrados
              </div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Programas de Formación -->
    <section class="mt-12">
      <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
        <i data-lucide="book-open" class="h-4 w-4"></i>
        Formación
      </div>

      <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">
        Programas de Formación
      </h2>

      <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-5">

        <!-- Programa 1 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              DS
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Desarrollo de Software
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                25 cupos disponibles
              </div>
            </div>

          </div>
        </article>

        <!-- Programa 2 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              AD
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Análisis de Datos
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                18 cupos disponibles
              </div>
            </div>
          </div>
        </article>

        <!-- Programa 3 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              CS
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Ciberseguridad
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                12 cupos disponibles
              </div>
            </div>
          </div>
        </article>

        <!-- Programa 4 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              IA
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Inteligencia Artificial
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                20 cupos disponibles
              </div>
            </div>
          </div>
        </article>

        <!-- Programa 5 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="flex items-center gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200">
              RT
            </div>

            <div>
              <div class="font-[Montserrat] text-sm font-bold text-sena-text-main">
                Redes y Telecomunicaciones
              </div>
              <div class="text-xs font-bold text-sena-text-muted">
                15 cupos disponibles
              </div>
            </div>
          </div>
        </article>

      </div>
    </section>

  </main>

  <script>
    lucide.createIcons();
  </script>

</body>
</html>

<?php include __DIR__ . '../../../includes/footer.php'; ?>