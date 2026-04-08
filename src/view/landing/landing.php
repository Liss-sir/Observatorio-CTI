<?php
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
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap"
    rel="stylesheet"
  />

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
        Análisis de necesidades empresariales clasificadas por líneas tecnológicas y
        <br class="hidden md:block" />
        tecnologías emergentes del sector productivo risaraldense.
      </p>

    </div>
  </section>

  <!-- CONTENIDO -->
  <main class="w-full px-20 pb-20">

    <!-- PERFILES RECIENTES -->
    <section class="mt-10">
      <div class="flex items-end justify-between">
        <div>
          <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
            <i data-lucide="clock" class="h-4 w-4"></i>
            Recientes
          </div>
          <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">
            Perfiles Recientes
          </h2>
        </div>
      </div>

      <div
        id="perfiles-recientes"
        class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
      >
        <div class="col-span-full py-8 text-center text-sm font-semibold text-sena-text-muted">
          Cargando perfiles...
        </div>
      </div>
    </section>

    <!-- LÍNEAS TECNOLÓGICAS -->
    <section class="mt-12 w-full">
      <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
        <i data-lucide="layers" class="h-4 w-4"></i>
        Clasificación
      </div>

      <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">
        Líneas Tecnológicas
      </h2>

      <div
        id="lineas-tecnologicas"
        class="mt-5 grid w-full grid-cols-1 gap-4 md:grid-cols-4"
      >
        <div class="col-span-full py-8 text-center text-sm font-semibold text-sena-text-muted">
          Cargando líneas tecnológicas...
        </div>
      </div>
    </section>

    <!-- PROGRAMAS DE FORMACIÓN -->
    <section class="mt-12">
      <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-sena-strong">
        <i data-lucide="book-open" class="h-4 w-4"></i>
        Formación
      </div>

      <h2 class="font-[Montserrat] mt-1 text-xl font-bold text-sena-text-main">
        Programas de Formación
      </h2>

      <div
        id="programas-formacion"
        class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-5"
      >
        <div class="col-span-full py-8 text-center text-sm font-semibold text-sena-text-muted">
          Cargando programas...
        </div>
      </div>
    </section>

  </main>

  <script src="../../assets/js/landing/landing.js"></script>
  <script>
    if (window.lucide) {
      lucide.createIcons();
    }
  </script>

</body>
</html>

<?php include __DIR__ . '../../../includes/footer.php'; ?>