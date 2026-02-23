<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tecnologias Emergentes - SENA Observatorio Tecnologico</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: var(--font-sans);
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
    }

    /* ===== FOOTER ===== */
    .site-footer { border-top: 1px solid var(--border); background-color: var(--card); }
    .footer-inner { max-width: 1280px; margin: 0 auto; padding: 40px 16px; }
    .footer-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
  .footer-brand .brand-head { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
  .footer-brand .logo-box { width:48px; height:48px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:transparent; }
  .footer-brand .logo-box img { width:36px; height:auto; display:block; }
  .footer-brand .logo-text p:first-child { font-weight:700; font-size:16px; color: var(--foreground); margin:0; }
  .footer-brand .logo-text p:last-child { font-size:13px; color: var(--muted-foreground); margin:0; }
  .footer-brand p { font-size:14px; line-height:1.6; color: var(--muted-foreground); margin-top:8px; }
  /* Divider below the brand block to match reference */
  .footer-brand { padding-bottom:16px; }
    .footer-nav h4, .footer-contact h4 { font-size: 14px; font-weight: 600; color: var(--foreground); margin-bottom: 12px; }
    .footer-nav ul, .footer-contact ul { list-style: none; display: flex; flex-direction: column; gap: 8px; }
    .footer-nav a { font-size: 14px; color: var(--muted-foreground); transition: color 0.2s; }
    .footer-nav a:hover { color: var(--primary); }
    .footer-contact li { font-size: 14px; color: var(--muted-foreground); }
    .footer-bottom { margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); text-align: center;width: 100%; }
    .footer-bottom p { font-size: 12px; color: var(--muted-foreground);  }

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
  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="footer-inner">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand-head">
            <div class="logo-link">
              <div class="logo-box">
                  <img src="../../assets/img/logo-senlock.jpeg" alt="logo sena">
              </div>
              <div class="logo-text">
                <p>SENA</p>
                <p>Centro de Diseño e Innovación Tecnológica Industrial</p>
              </div>
            </div>
          </div>
          <p>Plataforma institucional para el analisis de necesidades empresariales de perfiles tecnologicos.</p>
        </div>
        <div class="footer-nav">
          <h4>Navegacion</h4>
          <ul>
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="busqueda.html">Perfiles</a></li>
            <li><a href="busqueda.html">Lineas Tecnologicas</a></li>
            <li><a href="tecnologias-emergentes.html">Tecnologias Emergentes</a></li>
            <li><a href="tecnologias-emergentes.html">Gestión Usuarios</a></li>
            <li><a href="../../view/creditos/creditos.php">Creditos</a></li>
          </ul>
        </div>
        <div class="footer-contact">
          <h4>Contacto</h4>
          <ul>
            <li>Dosquebradas, Risaralda, Colombia</li>
            <li>Línea nacional: 01 8000 910 270</li>
            <li>Servicio Nacional de Aprendizaje – SENA</li>
            <li>Centro de Diseño e Innovación Tecnológica Industrial (CDITI)</li>
            <li><a href="https://www.sena.edu.co">www.sena.edu.co</a></li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>SENLOCK - Todos los derechos reservados</p>
      </div>
    </div>
  </footer>
</body>
</html>
