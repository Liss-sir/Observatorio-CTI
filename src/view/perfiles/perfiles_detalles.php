<?php
include __DIR__ . '../../../includes/header.php'; 
include __DIR__ . '/modal_editar_perfiles.php'; 
include __DIR__ . '/modal_deshabilitar_perfiles.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Desarrollador Full Stack Senior - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <!-- Agregar estilos adicionales para asegurar que el modal funcione -->
  <style>
    .hidden {
      display: none !important;
    }
    #modal-editar-perfil {
      transition: opacity 0.2s ease;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- Back Link -->
      <a href="sena-admin-perfiles.html" class="inline-flex items-center gap-1.5 text-sm text-sena-text-soft hover:text-sena-text-main transition-colors mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Volver a resultados
      </a>

      <!-- Profile Header -->
      <div class="mb-8">
        <div class="flex flex-wrap items-center gap-3 mb-3">
          <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-sena-soft text-sena-strong">Tecnologias de la Informacion y las Comunicaciones</span>
          <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            Vigente
          </span>
        </div>
        <div class="flex items-start justify-between gap-6 flex-wrap">
          <h1 class="text-2xl lg:text-3xl font-bold text-sena-text-main">Desarrollador Full Stack Senior</h1>
          <div class="flex items-center gap-3 flex-shrink-0">
            <button id="btn-editar-perfil" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-sena-border rounded-lg bg-white text-sena-text-main hover:bg-sena-soft transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
              Editar
            </button>
            <button id="btn-deshabilitar-perfil" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
              Desactivar
            </button>
          </div>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">

        <!-- Main Content Card -->
        <div class="border border-sena-border rounded-xl bg-white p-6 h-64">
          <h2 class="text-xs font-semibold uppercase tracking-wider text-sena-text-soft mb-3">Descripcion del Perfil</h2>
          <p class="text-sm leading-relaxed text-sena-text-main">
            Se requiere profesional con experiencia en desarrollo de aplicaciones web complejas utilizando frameworks modernos de frontend y backend. Debe tener conocimiento en arquitecturas de microservicios, bases de datos relacionales y no relacionales, y despliegue en entornos cloud.
          </p>

          <hr class="border-t border-sena-border my-6">

          <h2 class="text-xs font-semibold uppercase tracking-wider text-sena-text-soft mb-3">Tecnologias Emergentes Asociadas</h2>
          <div class="flex flex-wrap gap-2">
            <div class="flex items-center gap-2 px-3 py-2 border border-sena-border rounded-lg bg-sena-soft text-sm font-medium text-sena-text-main">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-sena"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
              Computacion en la Nube
            </div>
            <div class="flex items-center gap-2 px-3 py-2 border border-sena-border rounded-lg bg-sena-soft text-sm font-medium text-sena-text-main">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-sena"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
              Ciberseguridad Avanzada
            </div>
          </div>
        </div>

        <!-- Sidebar Info Cards -->
        <div class="flex flex-col gap-4">

          <!-- Empresa -->
          <div class="border border-sena-border rounded-xl bg-white p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
              Empresa
            </h3>
            <p class="text-sm font-medium text-sena-text-main">TechColombia S.A.S.</p>
          </div>

          <!-- Linea Tecnologica -->
          <div class="border border-sena-border rounded-xl bg-white p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
              Linea Tecnologica
            </h3>
            <p class="text-sm text-sena-text-main">Tecnologias de la Informacion y las Comunicaciones</p>
          </div>

          <!-- Fechas -->
          <div class="border border-sena-border rounded-xl bg-white p-5">
            <h3 class="flex items-center gap-2 text-sm font-semibold text-sena-text-main mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
              Fechas
            </h3>
            <div class="flex items-baseline gap-3">
              <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Creacion</span>
              <span class="text-sm font-medium text-sena-text-main">15 de noviembre de 2025</span>
            </div>
            <div class="flex items-baseline gap-3 mt-2">
              <span class="text-xs text-sena-text-soft min-w-[4.5rem]">Expiracion</span>
              <span class="text-sm font-medium text-sena-text-main">15 de mayo de 2026</span>
            </div>
          </div>

          <!-- Vigencia -->
          <div class="border border-sena/30 rounded-xl bg-sena/5 p-5 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-sena flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            <div>
              <p class="text-sm font-semibold text-sena">Perfil Vigente</p>
              <p class="text-xs text-sena-text-soft">Este perfil se encuentra activo</p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>


    <!-- JavaScript actualizado con ambos modales -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // ===== MODAL EDITAR =====
      const btnEditar = document.getElementById('btn-editar-perfil');
      const modalEditar = document.getElementById('modal-editar-perfil');
      const cerrarBtnsEditar = document.querySelectorAll('.cerrar-modal');
      
      // ===== MODAL DESHABILITAR =====
      const btnDeshabilitar = document.getElementById('btn-deshabilitar-perfil');
      const modalDeshabilitar = document.getElementById('modal-deshabilitar-perfil');
      const cerrarBtnsDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
      const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
      
      console.log('Botón editar encontrado:', btnEditar);
      console.log('Modal editar encontrado:', modalEditar);
      console.log('Botón deshabilitar encontrado:', btnDeshabilitar);
      console.log('Modal deshabilitar encontrado:', modalDeshabilitar);
      
      // ===== FUNCIONES PARA MODAL EDITAR =====
      function abrirModalEditar() {
        if (modalEditar) {
          modalEditar.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
          console.log('Modal editar abierto');
        }
      }
      
      function cerrarModalEditar() {
        if (modalEditar) {
          modalEditar.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
          console.log('Modal editar cerrado');
        }
      }
      
      // ===== FUNCIONES PARA MODAL DESHABILITAR =====
      function abrirModalDeshabilitar() {
        if (modalDeshabilitar) {
          modalDeshabilitar.classList.remove('hidden');
          document.body.classList.add('overflow-hidden');
          console.log('Modal deshabilitar abierto');
        }
      }
      
      function cerrarModalDeshabilitar() {
        if (modalDeshabilitar) {
          modalDeshabilitar.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
          console.log('Modal deshabilitar cerrado');
        }
      }
      
      // ===== EVENTOS MODAL EDITAR =====
      if (btnEditar) {
        btnEditar.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModalEditar();
        });
      }
      
      cerrarBtnsEditar.forEach(btn => {
        btn.addEventListener('click', cerrarModalEditar);
      });
      
      // ===== EVENTOS MODAL DESHABILITAR =====
      if (btnDeshabilitar) {
        btnDeshabilitar.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModalDeshabilitar();
        });
      }
      
      cerrarBtnsDeshabilitar.forEach(btn => {
        btn.addEventListener('click', cerrarModalDeshabilitar);
      });
      
      // Confirmar deshabilitar
      if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', function() {
          console.log('Perfil deshabilitado: Desarrollador Full Stack Senior');
          alert('Perfil deshabilitado exitosamente');
          cerrarModalDeshabilitar();
          
          // Aquí puedes agregar lógica adicional como:
          // - Cambiar el estado visual del perfil en la página
          // - Enviar petición AJAX al servidor
          // - Actualizar la etiqueta de "Vigente" a "Inactivo"
        });
      }
      
      // ===== EVENTOS COMUNES =====
      // Cerrar modales haciendo click en el overlay
      if (modalEditar) {
        modalEditar.addEventListener('click', function(e) {
          if (e.target === modalEditar || e.target.classList.contains('fixed')) {
            cerrarModalEditar();
          }
        });
      }
      
      if (modalDeshabilitar) {
        modalDeshabilitar.addEventListener('click', function(e) {
          if (e.target === modalDeshabilitar || e.target.classList.contains('fixed')) {
            cerrarModalDeshabilitar();
          }
        });
      }
      
      // Cerrar con tecla ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (modalEditar && !modalEditar.classList.contains('hidden')) {
            cerrarModalEditar();
          } else if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
            cerrarModalDeshabilitar();
          }
        }
      });
      
      // ===== MANEJAR FORMULARIO EDITAR =====
      const form = document.querySelector('#modal-editar-perfil form');
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          console.log('Formulario enviado');
          
          // Recoger datos del formulario
          var selectedChips = [];
          document.querySelectorAll('#modal-editar-perfil .chip.selected').forEach(function(chip) {
            selectedChips.push(chip.getAttribute('data-value'));
          });

          var data = {
            nombre: document.querySelector('input[name="nombre"]').value,
            descripcion: document.querySelector('textarea[name="descripcion"]').value,
            fechaCreacion: document.querySelector('input[name="fechaCreacion"]').value,
            fechaExpiracion: document.querySelector('input[name="fechaExpiracion"]').value,
            estado: document.querySelector('select[name="estado"]').value,
            lineaTecnologica: document.querySelector('select[name="lineaTecnologica"]').value,
            tecnologiasEmergentes: selectedChips,
            empresa: document.querySelector('select[name="empresa"]').value
          };

          console.log('Perfil actualizado:', data);
          alert('Perfil actualizado exitosamente');
          cerrarModalEditar();
        });
      }
      
      // ===== MANEJAR CHIPS =====
      document.querySelectorAll('#modal-editar-perfil .chip').forEach(chip => {
        chip.addEventListener('click', function() {
          this.classList.toggle('selected');
          this.classList.toggle('bg-sena');
          this.classList.toggle('text-white');
          this.classList.toggle('border-sena');
          this.classList.toggle('border-sena-border');
        });
      });
    });
  </script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>