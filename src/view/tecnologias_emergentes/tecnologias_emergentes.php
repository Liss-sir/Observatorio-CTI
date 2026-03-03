<?php
include __DIR__ . '../../../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tecnologias Emergentes - SENA Observatorio Tecnologico</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --background: #fcfcfc;
      --foreground: #1a2620;
      --card: #ffffff;
      --primary: #00a300;
      --primary-foreground: #ffffff;
      --secondary: #f0f5f0;
      --muted: #f3f4f3;
      --muted-foreground: #6b756e;
      --accent: #e4f0e4;
      --accent-foreground: #1a6b1a;
      --border: #e2e5e2;
      --radius: 0.5rem;
      --font-sans: 'Inter', system-ui, sans-serif;
      --font-heading: 'Montserrat', system-ui, sans-serif;
    }

    body {
      font-family: var(--font-sans);
      background-color: var(--background);
      color: var(--foreground);
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }
    a { text-decoration: none; color: inherit; }


    /* ===== TABS ===== */
    .page-content {
      max-width: 1280px; margin: 0 auto; padding: 32px 16px;
    }
    .tab-nav {
      display: flex; align-items: center; gap: 4px;
      border: 1px solid var(--border); background-color: var(--card);
      border-radius: 8px; padding: 4px; margin-bottom: 24px;
    }
    .tab-nav a {
      display: flex; align-items: center; gap: 8px;
      padding: 8px 16px; border: none; border-radius: 6px;
      font-size: 14px; font-weight: 500; font-family: var(--font-sans);
      cursor: pointer; background: transparent;
      color: var(--muted-foreground); transition: all 0.2s;
    }
    .tab-nav a.active {
      background-color: var(--primary); color: var(--primary-foreground);
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .tab-nav a svg { width: 16px; height: 16px; }

    /* ===== GRID CARDS ===== */
    .grid-3 {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
    }
    .card {
      border: 1px solid var(--border); background-color: var(--card);
      border-radius: var(--radius); transition: border-color 0.2s, box-shadow 0.2s;
    }
    .card:hover { border-color: rgba(0,163,0,0.3); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .card-body { padding: 20px; }
    .card-top-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .card-icon {
      width: 40px; height: 40px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .card-icon.accent-bg { background-color: var(--accent); }
    .card-icon svg { width: 20px; height: 20px; color: var(--primary); }
    .card-count { font-size: 12px; color: var(--muted-foreground); }
    .card h3 { font-family: var(--font-heading); font-size: 14px; font-weight: 600; color: var(--foreground); margin-bottom: 8px; }
    .card .card-sub { font-size: 12px; color: var(--muted-foreground); margin-bottom: 12px; }
    .badge-secondary {
      display: inline-flex; padding: 2px 8px; background-color: var(--accent);
      border-radius: 4px; font-size: 10px; color: var(--accent-foreground);
    }
    .tags { display: flex; flex-wrap: wrap; gap: 4px; }
    .card-link {
      display: inline-flex; align-items: center; gap: 4px; margin-top: 12px;
      font-size: 12px; font-weight: 500; color: var(--primary);
    }
    .card-link svg { width: 12px; height: 12px; }

    .section-heading { margin-bottom: 24px; }
    .section-heading h2 { font-family: var(--font-heading); font-size: 20px; font-weight: 700; color: var(--foreground); }
    .section-heading p { font-size: 14px; color: var(--muted-foreground); margin-top: 4px; }

    /* ===== FOOTER ===== */
    .site-footer { border-top: 1px solid var(--border); background-color: var(--card); }
    .footer-inner { max-width: 1280px; margin: 0 auto; padding: 40px 16px; }
    .footer-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
    .footer-brand p { font-size: 14px; line-height: 1.6; color: var(--muted-foreground); }
    .footer-nav h4, .footer-contact h4 { font-size: 14px; font-weight: 600; color: var(--foreground); margin-bottom: 12px; }
    .footer-nav ul, .footer-contact ul { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .footer-nav a { font-size: 14px; color: var(--muted-foreground); transition: color 0.2s; }
    .footer-nav a:hover { color: var(--primary); }
    .footer-contact li { font-size: 14px; color: var(--muted-foreground); }
    .footer-bottom { margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); text-align: center; }
    .footer-bottom p { font-size: 12px; color: var(--muted-foreground); }

    @media (max-width: 1024px) {
      .grid-3 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
      .nav-desktop, .header-actions { display: none; }
      .grid-3 { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr; }
      .logo-text { display: none; }
      .tab-nav { flex-wrap: wrap; }
    }
  </style>
</head>
<body>

  <main>
    <div class="page-content">
      

      <!-- TECNOLOGIAS EMERGENTES CONTENT -->
      <div class="section-heading">
        <h2>Tecnologias Emergentes</h2>
        <p>Tecnologias de vanguardia asociadas a los perfiles registrados</p>
      </div>

      <div class="grid-3">
        <!-- Inteligencia Artificial -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">2 perfiles</span>
            </div>
            <h3>Inteligencia Artificial</h3>
            <p class="card-sub">2 vigentes &middot; 2 lineas</p>
            <div class="tags">
              <span class="badge-secondary">Tecnologias de la</span>
              <span class="badge-secondary">Biotecnologia y Nanotecnologia</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Blockchain -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">2 perfiles</span>
            </div>
            <h3>Blockchain</h3>
            <p class="card-sub">2 vigentes &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Tecnologias de la</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Internet de las Cosas (IoT) -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">1 perfil</span>
            </div>
            <h3>Internet de las Cosas (IoT)</h3>
            <p class="card-sub">1 vigente &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Electronica y Automatizacion</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Computacion en la Nube -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">4 perfiles</span>
            </div>
            <h3>Computacion en la Nube</h3>
            <p class="card-sub">4 vigentes &middot; 2 lineas</p>
            <div class="tags">
              <span class="badge-secondary">Tecnologias de la</span>
              <span class="badge-secondary">Servicios y Gestion</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Ciberseguridad Avanzada -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">4 perfiles</span>
            </div>
            <h3>Ciberseguridad Avanzada</h3>
            <p class="card-sub">4 vigentes &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Tecnologias de la</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Big Data y Analitica -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">3 perfiles</span>
            </div>
            <h3>Big Data y Analitica</h3>
            <p class="card-sub">3 vigentes &middot; 2 lineas</p>
            <div class="tags">
              <span class="badge-secondary">Tecnologias de la</span>
              <span class="badge-secondary">Biotecnologia y Nanotecnologia</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Realidad Aumentada/Virtual -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">1 perfil</span>
            </div>
            <h3>Realidad Aumentada/Virtual</h3>
            <p class="card-sub">0 vigentes &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Diseno e Innovacion</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Robotica Colaborativa -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">2 perfiles</span>
            </div>
            <h3>Robotica Colaborativa</h3>
            <p class="card-sub">1 vigente &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Electronica y Automatizacion</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Manufactura Aditiva (Impresion 3D) -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">1 perfil</span>
            </div>
            <h3>Manufactura Aditiva (Impresion 3D)</h3>
            <p class="card-sub">1 vigente &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Materiales e Industria</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>

        <!-- Automatizacion de Procesos (RPA) -->
        <div class="card">
          <div class="card-body">
            <div class="card-top-row">
              <div class="card-icon accent-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2" stroke-linecap="round"/></svg>
              </div>
              <span class="card-count">2 perfiles</span>
            </div>
            <h3>Automatizacion de Procesos (RPA)</h3>
            <p class="card-sub">2 vigentes &middot; 1 linea</p>
            <div class="tags">
              <span class="badge-secondary">Servicios y Gestion</span>
            </div>
            <a href="busqueda.html" class="card-link">Ver perfiles <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M5 12h14m0 0-7-7m7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>
