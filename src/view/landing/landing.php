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

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-slate-50 text-slate-900">

  <!-- HERO -->
  <section class="w-full bg-gradient-to-b from-green-50 via-slate-50 to-slate-50 px-20 pt-16 pb-16">
      <div class="mx-auto max-w-3xl text-center">

        <div class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-100/60 px-4 py-1.5 text-xs font-bold text-green-800">
          Plataforma Institucional SENA
        </div>

        <h1 class="mt-6 text-4xl font-extrabold leading-tight text-slate-900 md:text-5xl">
          Observatorio de <br class="hidden md:block" />
          Perfiles <span class="text-green-700">Tecnológicos</span>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-sm font-semibold text-slate-500 md:text-base">
          Análisis de necesidades empresariales clasificadas por líneas tecnológicas y<br class="hidden md:block" />
          tecnologías emergentes del sector productivo risaraldense.
        </p>

        <!-- Search -->
        <div class="mx-auto mt-8 max-w-xl">
          <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white p-2 shadow-sm">
            <div class="relative flex-1">
              <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
              <input
                class="w-full rounded-lg py-2 pl-10 pr-3 text-sm font-semibold text-slate-700 outline-none placeholder:text-slate-400"
                placeholder="Buscar perfiles tecnológicos..."
              />
            </div>
            <button class="rounded-lg bg-green-700 px-6 py-2 text-sm font-bold text-white hover:bg-green-800">
              Buscar
            </button>
          </div>
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
          <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-green-700">
            <i data-lucide="trending-up" class="h-4 w-4"></i>
            Más consultados
          </div>
          <h2 class="mt-1 text-xl font-extrabold text-slate-900">Perfiles Destacados</h2>
        </div>

        <a class="inline-flex items-center gap-2 text-sm font-bold text-green-700 hover:text-green-800" href="#">
          Ver todos <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
      </div>

      <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-extrabold text-green-800">Tecnologías de</span>
            <span class="rounded-full bg-green-700/10 px-2 py-0.5 text-[10px] font-extrabold text-green-700">Vigente</span>
          </div>

          <h3 class="mt-3 text-sm font-extrabold text-slate-900 transition-colors duration-200 group-hover:text-green-700">Desarrollador Full Stack Senior</h3>
          <p class="mt-1 text-xs font-semibold text-slate-500">
            Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Computación en la Nube</span>
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Ciberseguridad Avanzada</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">
            <div class="text-xs font-bold text-slate-500">TechColombia S.A.S.</div>
            <i data-lucide="eye" class="h-4 w-4 text-slate-400 transition-colors duration-200 group-hover:text-green-700"></i>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-extrabold text-green-800">Tecnologías de</span>
            <span class="rounded-full bg-green-700/10 px-2 py-0.5 text-[10px] font-extrabold text-green-700">Vigente</span>
          </div>

          <h3 class="mt-3 text-sm font-extrabold text-slate-900 transition-colors duration-200 group-hover:text-green-700">Especialista en Inteligencia Artificial</h3>
          <p class="mt-1 text-xs font-semibold text-slate-500">
            Perfil orientado a profesionales con experiencia en machine learning, deep…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Inteligencia Artificial</span>
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Big Data y Analítica</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">
            <div class="text-xs font-bold text-slate-500">InnoSoft Ltda.</div>
            <i data-lucide="eye" class="h-4 w-4 text-slate-400 transition-colors duration-200 group-hover:text-green-700"></i>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-extrabold text-green-800">Tecnologías de</span>
            <span class="rounded-full bg-green-700/10 px-2 py-0.5 text-[10px] font-extrabold text-green-700">Vigente</span>
          </div>

          <h3 class="mt-3 text-sm font-extrabold text-slate-900 transition-colors duration-200 group-hover:text-green-700">Analista de Ciberseguridad</h3>
          <p class="mt-1 text-xs font-semibold text-slate-500">
            Se busca profesional para identificar vulnerabilidades, gestionar incidentes de…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Ciberseguridad Avanzada</span>
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Blockchain</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">
            <div class="text-xs font-bold text-slate-500">DataVision Corp.</div>
            <i data-lucide="eye" class="h-4 w-4 text-slate-400 transition-colors duration-200 group-hover:text-green-700"></i>
          </div>
        </article>

        <!-- Card 4 -->
        <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-lg">
          <div class="flex items-center justify-between">
            <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[10px] font-extrabold text-green-800">Tecnologías de</span>
            <span class="rounded-full bg-green-700/10 px-2 py-0.5 text-[10px] font-extrabold text-green-700">Vigente</span>
          </div>

          <h3 class="mt-3 text-sm font-extrabold text-slate-900 transition-colors duration-200 group-hover:text-green-700">Ingeniero de Datos</h3>
          <p class="mt-1 text-xs font-semibold text-slate-500">
            Profesional especializado en la creación y mantenimiento de pipelines de datos,…
          </p>

          <div class="mt-3 flex flex-wrap gap-1.5">
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Big Data y Analítica</span>
            <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-600">Computación en la Nube</span>
          </div>

          <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">
            <div class="text-xs font-bold text-slate-500">CloudNet Colombia</div>
            <i data-lucide="eye" class="h-4 w-4 text-slate-400 transition-colors duration-200 group-hover:text-green-700"></i>
          </div>
        </article>
      </div>
    </section>

    <!-- Líneas Tecnológicas -->
    <section class="mt-12 w-full">
        <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-green-700">
            <i data-lucide="layers" class="h-4 w-4"></i>
            Clasificación
        </div>

        <h2 class="mt-1 text-xl font-extrabold text-slate-900">Líneas Tecnológicas</h2>

        <!-- GRID: 4 cols en desktop, estira todos los items -->
        <div class="mt-5 grid w-full grid-cols-1 gap-4 md:grid-cols-4">
            <!-- TIC (ocupa 2 columnas) -->
            <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
            <div class="flex h-full items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-700/10 text-sm font-extrabold text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
                TI
                </div>
                <div class="min-w-0">
                <div class="truncate text-sm font-extrabold text-slate-900">Tecnologías de la Información</div>
                <div class="text-xs font-bold text-slate-500">3 perfiles registrados</div>
                </div>
            </div>
            </article>

            <!-- BIO -->
            <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
            <div class="flex h-full items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-700/10 text-sm font-extrabold text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
                BIO
                </div>
                <div class="min-w-0">
                <div class="truncate text-sm font-extrabold text-slate-900">Biotecnología y Nanotecnología</div>
                <div class="text-xs font-bold text-slate-500">1 perfil registrado</div>
                </div>
            </div>
            </article>

            <!-- DIS -->
            <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
            <div class="flex h-full items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-700/10 text-sm font-extrabold text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
                DIS
                </div>
                <div class="min-w-0">
                <div class="truncate text-sm font-extrabold text-slate-900">Diseño e Innovación</div>
                <div class="text-xs font-bold text-slate-500">1 perfil registrado</div>
                </div>
            </div>
            </article>

            <!-- PRO -->
            <article class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
            <div class="flex h-full items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-green-700/10 text-sm font-extrabold text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
                PRO
                </div>
                <div class="min-w-0">
                <div class="truncate text-sm font-extrabold text-slate-900">Producción y Transformación</div>
                <div class="text-xs font-bold text-slate-500">0 perfiles registrados</div>
                </div>
            </div>
            </article>
        </div>
    </section>

    <!-- Tecnologías Emergentes -->
    <section class="mt-12">
      <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-wider text-green-700">
        <i data-lucide="cpu" class="h-4 w-4"></i>
        Innovación
      </div>
      <h2 class="mt-1 text-xl font-extrabold text-slate-900">Tecnologías Emergentes</h2>

      <div class="mt-5 grid grid-cols-2 gap-4 md:grid-cols-5">
        <!-- item -->
        <div class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="mt-3 text-xs font-extrabold text-slate-900">Inteligencia Artificial</div>
          <div class="mt-1 text-[11px] font-bold text-slate-500">2 perfiles</div>
        </div>

        <div class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="mt-3 text-xs font-extrabold text-slate-900">Blockchain</div>
          <div class="mt-1 text-[11px] font-bold text-slate-500">2 perfiles</div>
        </div>

        <div class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="mt-3 text-xs font-extrabold text-slate-900">Internet de las Cosas (IoT)</div>
          <div class="mt-1 text-[11px] font-bold text-slate-500">1 perfil</div>
        </div>

        <div class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="mt-3 text-xs font-extrabold text-slate-900">Computación en la Nube</div>
          <div class="mt-1 text-[11px] font-bold text-slate-500">3 perfiles</div>
        </div>

        <div class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-5 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:border-green-300 hover:shadow-md">
          <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700 transition-colors duration-200 group-hover:bg-green-700 group-hover:text-white">
            <i data-lucide="sparkles" class="h-4 w-4"></i>
          </div>
          <div class="mt-3 text-xs font-extrabold text-slate-900">Ciberseguridad Avanzada</div>
          <div class="mt-1 text-[11px] font-bold text-slate-500">3 perfiles</div>
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