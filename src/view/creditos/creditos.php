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
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #ffffff;
      --fg: #212121;
      --primary: hsl(120, 55%, 32%);
      --primary-fg: #ffffff;
      --secondary-bg: hsl(120, 40%, 96%);
      --secondary-fg: hsl(120, 55%, 25%);
      --muted: hsl(0, 0%, 45%);
      --border: hsl(0, 0%, 92%);
      --card-shadow: 0 1px 3px hsl(0 0% 0% / 0.05);
      --card-shadow-hover: 0 8px 30px hsl(0 0% 0% / 0.08);
      --hero-gradient: linear-gradient(180deg, hsl(120, 40%, 96%) 0%, #fff 100%);
      --font-heading: 'Plus Jakarta Sans', sans-serif;
      --font-body: 'Inter', sans-serif;
      --radius: 1rem;
    }

    body {
      font-family: var(--font-body);
      background: var(--bg);
      color: var(--fg);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
    }

    /* Hero */
    .hero {
      background: var(--hero-gradient);
      text-align: center;
      padding: 5rem 1.5rem 6rem;
    }
    .hero .badge {
      display: inline-flex;
      align-items: center;
      border: 1px solid hsl(120, 55%, 32%, 0.2);
      background-color: #e4f0e4;
      border-radius: 9999px;
      padding: 0.5rem 1.25rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--primary);
    }
    .hero h1 {
      font-family: var(--font-heading);
      font-size: clamp(2rem, 5vw, 3.5rem);
      font-weight: 800;
      margin-top: 2rem;
      line-height: 1.15;
    }
    .hero h1 span { color: var(--primary); }
    .hero p {
      margin-top: 1rem;
      color: var(--muted);
      max-width: 540px;
      margin-left: auto;
      margin-right: auto;
      font-size: 1rem;
    }

    /* Sections */
    .container {
      max-width: 64rem;
      margin: 0 auto;
      padding: 4rem 1.5rem;
    }
    .sections { display: flex; flex-direction: column; gap: 5rem; }

    .section-label {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 0.5rem;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--primary);
    }
    .section-label svg { width: 16px; height: 16px; fill: none; stroke: var(--primary); stroke-width: 2; }

    .section-title {
      font-family: var(--font-heading);
      font-size: 1.75rem;
      font-weight: 700;
    }

    /* Cards grid */
    .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; max-width: 540px; margin: 2rem auto 0; }
    .grid-3 { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.25rem; margin-top: 2rem; }
    /* Allow placing a specific card at a desired column */
    .grid-3 .col-2 { grid-column: 2; }
    /* Responsive: on narrow viewports let natural flow occur */
    @media (max-width: 620px) {
      .grid-3 .col-2 { grid-column: auto; }
    }
    .grid-1 { max-width: 320px; margin: 2rem auto 0; }

    /* Card */
    .card {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--card-shadow);
      padding: 2rem 1.5rem;
      text-align: center;
      transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .card:hover {
      box-shadow: var(--card-shadow-hover);
      transform: translateY(-4px);
    }

    .card .avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--border);
      margin: 0 auto 1rem;
      transition: border-color 0.3s ease, transform 0.4s ease;
    }
    .card:hover .avatar {
      border-color: hsl(120, 55%, 32%, 0.4);
      transform: scale(1.05);
    }
    .card.featured .avatar {
      width: 96px;
      height: 96px;
      border-color: hsl(120, 55%, 32%, 0.2);
    }

    .card .name {
      font-family: var(--font-heading);
      font-size: 1rem;
      font-weight: 700;
    }

    .card .role-badge {
      display: inline-block;
      margin-top: 0.375rem;
      /* make the capsule width fit the text */
      padding: 0.35rem 1rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      background: var(--secondary-bg);
      color: var(--secondary-fg);
      white-space: nowrap;
    }
    /* Featured cards should use the same light capsule style as regular cards */
    .card.featured .role-badge {
      background: var(--secondary-bg);
      color: var(--secondary-fg);
    }

    .card .area {
      margin-top: 0.5rem;
      font-size: 0.75rem;
      color: var(--muted);
    }

    .card .email {
      margin-top: 0.75rem;
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      font-size: 0.75rem;
      color: var(--muted);
      text-decoration: none;
      transition: color 0.2s;
    }
    .card .email:hover { color: var(--primary); }
    .card .email svg { width: 12px; height: 12px; fill: none; stroke: currentColor; stroke-width: 2; }

    /* Footer */
    footer {
      border-top: 1px solid var(--border);
      background: hsl(0, 0%, 98%);
      padding: 2rem 1.5rem;
    }
    .footer-inner {
      max-width: 64rem;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
    }
    .footer-inner .brand {
      font-family: var(--font-heading);
      font-weight: 700;
      font-size: 0.875rem;
    }
    .footer-inner .copy {
      font-size: 0.75rem;
      color: var(--muted);
    }

    /* Animations */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate {
      animation: fadeUp 0.5s ease forwards;
      opacity: 0;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.15s; }
    .delay-3 { animation-delay: 0.2s; }
    .delay-4 { animation-delay: 0.25s; }
    .delay-5 { animation-delay: 0.3s; }
    .delay-6 { animation-delay: 0.35s; }
  </style>
</head>
<body>

  <!-- Hero -->
  <header class="hero">
    <div class="badge animate">Plataforma Institucional SENA</div>
    <h1 class="animate delay-1">Créditos Observatorio de <br>Perfiles <span>Tecnologicos</span></h1>
    <p class="animate delay-2">Conoce al equipo detrás del Sistema de Investigación, Desarrollo Tecnológico e Innovación.</p>
  </header>

  <!-- Content -->
  <main class="container">
    <div class="sections">

      <!-- Subdirectora -->
      <section>
        <div class="section-label">
          <svg viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
          Dirección
        </div>
        <h2 class="section-title">Subdirectora</h2>
        <div class="grid-1">
          <div class="card featured animate delay-1">
            <img class="avatar" src="../../assets/img/creadores_img/subdirectora_creador.jpg" alt="Foto de Sandra" />
            <div class="name">Sandra Yulieth García González</div>
            <span class="role-badge">Subdirectora</span>
            <div class="area">Subdirección de Centro</div>
            <a class="email" href="mailto:sandra@sena.edu.co">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              sandra@sena.edu.co
            </a>
          </div>
        </div>
      </section>

      <!-- Instructores -->
      <section>
        <div class="section-label">
          <svg viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
          Líderes
        </div>
        <h2 class="section-title">Instructores</h2>
        <div class="grid-2" style="display:grid; grid-template-columns:repeat(3,minmax(240px,1fr)); gap:1.25rem; max-width:900px; margin:2rem auto 0; align-items:stretch;">
          <div class="card animate delay-1" style="min-height:260px;">
            <img class="avatar" src="../../assets/img/creadores_img/yuely_creador.jpeg" alt="Foto de Yuely" />
            <div class="name">Yuely Adriana Arce Arias</div>
            <span class="role-badge">Instructora</span>
            <div class="area">Ingeniera en Sistemas</div>
            <a class="email" href="mailto:yarcea@sena.edu.co">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              yarcea@sena.edu.co
            </a>
          </div>
          <div class="card animate delay-2" style="min-height:260px;">
            <img class="avatar" src="../../assets/img/creadores_img/sebas_creador.jpeg" alt="Foto de Sebastián" />
            <div class="name">Jhoan Sebastian Duque Vera</div>
            <span class="role-badge" style="display:inline-flex; justify-content:center; align-items:center; margin:0.375rem auto; padding:0.35rem 1rem; white-space:nowrap;">Instructor</span>
            <div class="area">Ingeniero en Sistemas</div>
            <a class="email" href="mailto:sebas@sena.edu.co">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              sebas@sena.edu.co
            </a>
          </div>
           <div class="card animate delay-2" style="min-height:260px;">
            <img class="avatar" src="../../assets/sebas.jpg" alt="Foto de Sebastián" />
            <div class="name">Juan Carlos Garcia Buitrago</div>
            <span class="role-badge" style="display:inline-flex; justify-content:center; align-items:center; margin:0.375rem auto; padding:0.35rem 1rem; white-space:nowrap;">Coordinado Sennova</span>
            <div class="area">Ingeniero en Sistemas</div>
            <a class="email" href="mailto:sebas@sena.edu.co">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              sebas@sena.edu.co
            </a>
          </div>
        </div>
      </section>

      <!-- Equipo -->
      <section>
        <div class="section-label">
          <svg viewBox="0 0 24 24"><path d="M12 3l1.5 4.5H18l-3.5 2.7 1.3 4.3L12 12l-3.8 2.5 1.3-4.3L6 7.5h4.5z"/></svg>
          Colaboradores
        </div>
        <h2 class="section-title">Equipo de Trabajo</h2>
        <div class="grid-3">
          <div class="card animate delay-1">
            <img class="avatar" src="../../assets/img/creadores_img/juanes_creador.jpeg" alt="Foto de Camila Rodríguez" />
            <div class="name">Juan Esteban Soto Cardona</div>
            <span class="role-badge">Desarrollador Front-end</span>
            <div class="area">Ingeniero de Software</div>
            <a class="email" href="mailto:camila.rodriguez@sena.edu.co">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              sotojuanes15@gmail.com
            </a>
          </div>
          <div class="card animate delay-2">
            <img class="avatar" src="../../assets/member2.jpg" alt="Foto de Lisseth Soto Monsalve" />
            <div class="name">Lisseth Soto Monsalve</div>
            <span class="role-badge">Lider de Proyecto</span>
            <div class="area">Analisis y Desarrollo de Software</div>
            <a class="email" href="mailto:andres.lisisotomonsalve@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              lisisotomonsalve@gmail.com
            </a>
          </div>
          <div class="card animate delay-3">
            <img class="avatar" src="../../assets/img/creadores_img/kev_creador.jpeg" alt="Foto de Kevin Andres Duarte Hurtado" />
            <div class="name">Kevin Andres Duarte Hurtado</div>
            <span class="role-badge">Lider Front-end</span>
            <div class="area">Ingeniero de Software</div>
            <a class="email" href="mailto:hh.andress78@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              hh.andress78@gmail.com
            </a>
          </div>
          <div class="card animate delay-4">
            <img class="avatar" src="../../assets/img/creadores_img/samuel_creador.jpeg" alt="Foto de David Torres" />
            <div class="name">Samuel Monsalve Gomez</div>
            <span class="role-badge">Lider Back-End</span>
            <div class="area">Ingeniero de Software</div>
            <a class="email" href="mailto:monsalvegomezsamuel2@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              monsalvegomezsamuel2@gmail.com
            </a>
          </div>
          <div class="card animate delay-5">
            <img class="avatar" src="../../assets/img/creadores_img/juanjo_creador.jpeg" alt="Foto de Juan José Candamil Pérez" />
            <div class="name">Juan José Candamil Pérez</div>
            <span class="role-badge">Especialista en Bases de Datos</span>
            <div class="area">Ingeniero en Sistemas</div>
            <a class="email" href="mailto:yomacjc@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              yomacjc@gmail.com
            </a>
          </div>
          <div class="card animate delay-6">
            <img class="avatar" src="../../assets/img/creadores_img/julian_creador.jpeg" alt="Foto de Carlos Moreno" />
            <div class="name">Julian Osorio González</div>
            <span class="role-badge">Gestor de Documentación</span>
            <div class="area">Ingeniero en Software</div>
            <a class="email" href="mailto:julianchin1226@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
              julianchin1226@gmail.com
            </a>
          </div>
          <div class="card animate delay-6 col-2">
            <img class="avatar" src="../../assets/img/creadores_img/isaac_creador.jpeg" alt="Foto de Isaac Echeverry Garcia" />
            <div class="name">Isaac Echeverry Garcia</div>
            <span class="role-badge">Desarrollador Back-End</span>
            <div class="area">Analisis y Desarrollo de Software</div>
            <a class="email" href="mailto:isaacecheverry53@gmail.com">
              <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
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

