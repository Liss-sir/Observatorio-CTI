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
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }
    a { text-decoration: none; color: inherit; }

    /* ===== HEADER ===== */
    .site-header {
      position: sticky; top: 0; z-index: 50;
      border-bottom: 1px solid var(--border);
      background-color: rgba(255,255,255,0.95);
      backdrop-filter: blur(8px);
    }
    .header-inner {
      max-width: 1280px; margin: 0 auto;
      display: grid; grid-template-columns: auto 1fr auto; align-items: center;
      height: 64px; padding: 0 16px;
    }
    /* Left column: logos */
    .logo-link { display: flex; align-items: center; }
    .logo-group {
      display: flex; align-items: center; gap: 12px;
      padding-right: 16px; margin-right: 16px; 
    }
    .logo-images { display:flex; align-items:center; gap:12px; }
    .logo-images img { height: 28px; width: auto; display:block; }
    /* Make the first logo slightly larger (Tecnova) */
    .logo-images img:first-child { height: 36px; }
    /* vertical divider between the two logos */
    .logo-mid-divider {
      display: block; width: 1px; height: 28px; background: var(--border);
    }
    .logo-text p:first-child { font-family: var(--font-heading); font-size: 14px; font-weight: 700; line-height: 1.2; color: var(--foreground); }
    .logo-text p:last-child { font-size: 12px; line-height: 1.2; color: var(--muted-foreground); }
    /* Center column: nav */
    .nav-desktop { display: flex; align-items: center; gap: 12px; justify-self: center; }
    .nav-desktop a {
      padding: 8px 12px; border-radius: 6px; font-size: 14px; font-weight: 500;
      color: var(--muted-foreground); transition: background-color 0.2s, color 0.2s;
    }
    .nav-desktop a:hover { background-color: var(--accent); color: var(--foreground); }
    .btn-login {
      display: inline-flex; align-items: center; padding: 6px 16px;
      border: 1px solid rgba(0,163,0,0.3); border-radius: 6px;
      font-size: 14px; font-weight: 500; color: var(--foreground); background: transparent;
      cursor: pointer; transition: background-color 0.2s;
    }
    .btn-login:hover { background-color: var(--accent); }

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
  </style>
</head>
<body>
  <!-- HEADER -->
  <header class="site-header">
    <div class="header-inner">
      <a href="inicio.html" class="logo-link">
        <div class="logo-group">
          <div class="logo-images">
            <img src="../../assets/img/logo-tecnnova.jpeg" alt="logo-tecnnova">
            <span class="logo-mid-divider" aria-hidden="true"></span>
            <img src="../../assets/img/logo-sena-verde-complementario-png-2022.png" alt="logo-sena-verde">
          </div>
          <div class="logo-text">
            <p>SENA</p>
          </div>
        </div>
      </a>
      <nav class="nav-desktop">
        <a href="../../view/landing/landing.php">Inicio</a>
        <a href="busqueda.html">Perfiles</a>
        <a href="busqueda.html">Lineas Tecnologicas</a>
        <a href="tecnologias-emergentes.html">Tecnologias Emergentes</a>
        <a href="tecnologias-emergentes.html">Gestión Perfiles</a>
      </nav>
      <div class="header-actions" style="display:flex;align-items:center;gap:12px;">
        <a href="login.php" class="btn-login">Iniciar Sesion</a>
      </div>
    </div>
  </header>
