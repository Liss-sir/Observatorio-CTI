<?php
// landing.php

include __DIR__ . '../../../includes/header.php';

echo <<<'HTML'
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Observatorio de Perfiles Tecnológicos</title>

  <!-- Tailwind and Style-->
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
      <div class="mx-auto mt-8 max-w-xl">
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
      </div>

    </div>
  </section>

  <!-- CONTENT -->
  <main class="w-full px-20 pb-20">

    <!-- Perfiles Destacados -->
    <section class="mt-10">
      <div class="flex items-end justify-between">
        <div>
          <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
            <i data-lucide="trending-up" class="h-4 w-4"></i>
            Más consultados
          </div>
          <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">Perfiles Destacados</h2>
        </div>

        <a class="inline-flex items-center gap-2 text-sm font-bold text-sena-strong" href="#">
        <a class="inline-flex items-center gap-2 text-sm font-bold text-sena-strong" href="#">
          Ver todos <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
      </div>

      <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">
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
          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Computación en la Nube</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Ciberseguridad Avanzada</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              TechColombia S.A.S.
            </div>
            <!-- <i data-lucide="eye" class="h-4 w-4 text-sena-text-muted transition-colors duration-200"></i> -->
          </div>
        </article>

        <!-- Card 2 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">Vigente</span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Especialista en Inteligencia Artificial
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Perfil orientado a profesionales con experiencia en machine learning, deep…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Inteligencia Artificial</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Big Data y Analítica</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              InnoSoft Ltda.
            </div>
            <!-- <i data-lucide="eye" class="h-4 w-4 text-sena-text-muted transition-colors duration-200"></i> -->
          </div>
        </article>

        <!-- Card 3 -->
        <article class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2.5 py-0.5 text-[10px] font-bold text-sena-strong">Tecnologías de</span>
            <span class="rounded-full bg-sena-soft px-2 py-0.5 text-[10px] font-bold text-sena-strong">Vigente</span>
          </div>

          <h3 class="font-[Montserrat] mt-3 text-sm font-bold text-sena-text-main transition-colors duration-200">
            Analista de Ciberseguridad
          </h3>

          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
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
            <!-- <i data-lucide="eye" class="h-4 w-4 text-sena-text-muted transition-colors duration-200"></i> -->
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
          <p class="mt-1 text-xs font-semibold text-sena-text-muted transition-colors duration-200">
            Profesional especializado en la creación y mantenimiento de pipelines de datos,…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Big Data y Analítica</span>
            <span class="rounded-md bg-sena-soft px-2 py-1 text-[10px] font-bold text-sena-strong">Computación en la Nube</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-sena-border pt-3">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
            <div class="text-xs font-bold text-sena-text-muted transition-colors duration-200">
              CloudNet Colombia
            </div>
            <!-- <i data-lucide="eye" class="h-4 w-4 text-sena-text-muted transition-colors duration-200"></i> -->
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

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
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

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
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

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
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

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-sena-soft text-sm font-bold text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
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

    <!-- Tecnologías Emergentes -->
    <section class="mt-12">
      <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
        <i data-lucide="cpu" class="h-4 w-4"></i>
        Innovación
      </div>
      <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">Tecnologías Emergentes</h2>

      <div class="mt-5 grid grid-cols-2 gap-4 md:grid-cols-5">
        <!-- item 1 -->
        <div class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sena-soft text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="font-[Montserrat] mt-3 text-xs font-bold text-sena-text-main">Inteligencia Artificial</div>
          <div class="mt-1 text-[11px] font-bold text-sena-text-muted">2 perfiles</div>
        </div>

        <!-- item 2 -->
        <div class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sena-soft text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
            <i data-lucide="link" class="h-4 w-4"></i>
          </div>
          <div class="font-[Montserrat] mt-3 text-xs font-bold text-sena-text-main">Blockchain</div>
          <div class="mt-1 text-[11px] font-bold text-sena-text-muted">2 perfiles</div>
        </div>

        <!-- item 3 -->
        <div class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sena-soft text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
            <i data-lucide="wifi" class="h-4 w-4"></i>
          </div>
          <div class="font-[Montserrat] mt-3 text-xs font-bold text-sena-text-main">Internet de las Cosas (IoT)</div>
          <div class="mt-1 text-[11px] font-bold text-sena-text-muted">1 perfil</div>
        </div>

        <!-- item 4 -->
        <div class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sena-soft text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
            <i data-lucide="cloud" class="h-4 w-4"></i>
          </div>
          <div class="font-[Montserrat] mt-3 text-xs font-bold text-sena-text-main">Computación en la Nube</div>
          <div class="mt-1 text-[11px] font-bold text-sena-text-muted">3 perfiles</div>
        </div>

        <!-- item 5 -->
        <div class="tarjeta-tecnologia group cursor-pointer rounded-xl border border-sena-border bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-sena-strong hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-sena-soft text-sena-strong transition-colors duration-200 group-hover:bg-sena-strong">
            <i data-lucide="shield" class="h-4 w-4"></i>
          </div>
          <div class="font-[Montserrat] mt-3 text-xs font-bold text-sena-text-main">Ciberseguridad Avanzada</div>
          <div class="mt-1 text-[11px] font-bold text-sena-text-muted">3 perfiles</div>
        </div>
      </div>
    </section>

  </main>

  <script>
    lucide.createIcons();
  </script>

</body>
</html>
HTML;

include __DIR__ . '../../../includes/footer.php';