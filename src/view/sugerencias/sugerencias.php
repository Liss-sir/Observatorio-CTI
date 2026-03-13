<?php
// Temporal: fijamos un ID de usuario por defecto ya que no hay login
$id_usuario_actual = 1; // ID de usuario fijo para pruebas

include __DIR__ . '../../../includes/header.php';
include __DIR__ . '/modal_crear_sugerencia.php';
include __DIR__ . '/modal_confirmar_crear_sugerencia.php';
include __DIR__ . '/modal_editar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_editar_sug.php';
include __DIR__ . '/modal_deshabilitar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_deshabilitar_sug.php';
include __DIR__ . '/modal_habilitar_sugerencia.php';
include __DIR__ . '/modal_confirmacion_habilitar_sug.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Proyección a Futuro - SENA Observatorio Tecnologico</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../../../assets/css/output.css">
  <link rel="stylesheet" href="../../assets/css/globals.css">
  <style>
    /* Estilos adicionales para las tarjetas */
    .tarjeta-tecnologia {
      transition: all 0.2s ease;
      cursor: pointer;
    }
    
    .tarjeta-tecnologia:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Estilos para el filtro bonito */
    .filtro-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin: 1rem 0 1.5rem 0;
    }

    .filtro-label {
      font-size: 0.875rem;
      font-weight: 500;
      color: #4b5563;
    }

    .filtro-botones {
      display: flex;
      gap: 0.5rem;
      background: #f3f4f6;
      padding: 0.25rem;
      border-radius: 0.75rem;
    }

    .filtro-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 1rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      border: none;
      background: transparent;
      color: #6b7280;
      cursor: pointer;
      transition: all 0.2s ease;
      line-height: 1.2;
    }

    .filtro-btn svg {
      width: 0.9rem;
      height: 0.9rem;
    }

    .contador-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 1.3rem;
      height: 1.3rem;
      padding: 0 0.3rem;
      background-color: #e5e7eb;
      color: #4b5563;
      border-radius: 9999px;
      font-size: 0.7rem;
      font-weight: 600;
      margin-left: 0.25rem;
      transition: all 0.2s ease;
    }

    .filtro-btn.active .contador-badge {
      background-color: #32a900;
      color: white;
    }

    .filtro-btn:hover {
      background: rgba(0, 0, 0, 0.05);
    }

    .filtro-btn.active {
      background: white;
      color: #32a900;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .filtro-btn.active svg {
      color: #32a900;
    }

    /* Estilos para el contador de resultados */
    .resultados-count {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .resultados-badge {
      padding: 0.25rem 0.75rem;
      border-radius: 2rem;
      font-size: 0.75rem;
      font-weight: 500;
      color: #4b5563;
    }

    /* Animación para el modal */
    @keyframes slideDownModal {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate-slideDownModal {
      animation: slideDownModal 0.3s ease-out;
    }

    @keyframes modalFadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }
    
    .animate-modalFadeIn {
      animation: modalFadeIn 0.2s ease-out;
    }

    /* Estilos para el switch */
    .switch-sena {
      width: 36px;
      height: 20px;
      background-color: #e5e7eb;
      border-radius: 9999px;
      position: relative;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .switch-sena.active {
      background-color: #39A900;
    }

    .switch-sena::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      background-color: white;
      border-radius: 50%;
      top: 2px;
      left: 2px;
      transition: transform 0.2s;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .switch-sena.active::after {
      transform: translateX(16px);
    }

    /* Loader */
    .loader {
      border: 3px solid #f3f3f3;
      border-radius: 50%;
      border-top: 3px solid #39A900;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .hidden {
      display: none;
    }

    /* Toast Container */
    #toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      pointer-events: none;
    }

    /* Toast Compact - Solo para validaciones */
    .toast-validation {
      pointer-events: auto;
      min-width: 300px;
      max-width: 340px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      animation: toastFadeIn 0.25s ease-out forwards;
      border-left: 4px solid;
      transform-origin: right;
    }

    @keyframes toastFadeIn {
      0% {
        opacity: 0;
        transform: translateX(30px);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes toastFadeOut {
      0% {
        opacity: 1;
        transform: translateX(0);
      }
      100% {
        opacity: 0;
        transform: translateX(30px);
      }
    }

    .toast-validation.exit {
      animation: toastFadeOut 0.2s ease-in forwards;
    }

    .toast-contenido {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      gap: 14px;
    }

    .toast-icono-wrapper {
      flex-shrink: 0;
      width: 36px;
      height: 36px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.04);
    }

    .toast-icono {
      width: 20px;
      height: 20px;
    }

    .toast-icono svg {
      width: 100%;
      height: 100%;
    }

    .toast-mensaje-wrapper {
      flex: 1;
    }

    .toast-titulo {
      font-size: 14px;
      font-weight: 600;
      color: #1f2937;
      margin-bottom: 2px;
    }

    .toast-mensaje {
      font-size: 13px;
      color: #6b7280;
      line-height: 1.4;
    }

    /* Solo mostramos warning y error para validaciones */
    .toast-validation.warning {
      border-left-color: #f59e0b;
      background: linear-gradient(135deg, #fffaf0 0%, #ffffff 100%);
    }
    .toast-validation.warning .toast-icono-wrapper {
      background: rgba(245, 158, 11, 0.1);
    }
    .toast-validation.warning .toast-icono svg {
      color: #f59e0b;
    }

    .toast-validation.error {
      border-left-color: #ef4444;
      background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
    }
    .toast-validation.error .toast-icono-wrapper {
      background: rgba(239, 68, 68, 0.1);
    }
    .toast-validation.error .toast-icono svg {
      color: #ef4444;
    }
  </style>
</head>
<body class="font-['Inter'] text-sena-text-main antialiased min-h-screen flex flex-col">

  <!-- Toast Container -->
  <div id="toast-container"></div>

  <!-- ===== MAIN CONTENT ===== -->
  <main class="flex-1">
    <div class="max-w-[80rem] mx-auto px-4 py-8 lg:px-8">

      <!-- SUGERENCIAS INNOVADORAS CONTENT -->
      <div class="flex items-center justify-between mb-6">
        <div class="section-heading">
          <h2 class="font-['Montserrat'] text-2xl font-bold text-sena-text-main"><P>Sugerencias Innovadoras</P></h2>
          <p class="text-sm text-sena-text-soft mt-1">Propuestas innovadoras para fortalecer el desarrollo tecnológico Risaraldense.</p>
        </div>

        <!-- action Buttons -->
        <div class="flex items-center gap-3">
          <a href="../../view/perfiles/mis_creaciones_perfiles.php" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg bg-white whitespace-nowrap h-10 hover:bg-sena-soft transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-sena-text-soft"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Mis creaciones
          </a>
          <button id="btn-nuevo-perfil" class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-sena rounded-lg whitespace-nowrap h-10 hover:opacity-90 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Nueva Sugerencia
          </button>
        </div>
      </div>

      <!-- FILTRO BONITO POR ESTADO CON CONTADORES -->
      <div class="filtro-container">
        <div class="filtro-botones">
          <button class="filtro-btn active" data-filtro="todos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="3" y1="9" x2="21" y2="9"></line>
              <line x1="3" y1="15" x2="21" y2="15"></line>
              <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
            Todos
            <span class="contador-badge" id="contador-todos">0</span>
          </button>
          <button class="filtro-btn" data-filtro="activos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 16v-4M12 8h.01"></path>
            </svg>
            Activos
            <span class="contador-badge" id="contador-activos">0</span>
          </button>
          <button class="filtro-btn" data-filtro="inactivos">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            Inactivos
            <span class="contador-badge" id="contador-inactivos">0</span>
          </button>
        </div>
      </div>

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex gap-8">
        <!-- Results -->
        <div class="flex-1 min-w-0">
          <!-- Contador de resultados actualizado -->
          <div class="resultados-count">
            <span class="resultados-badge" id="resultados-count">Cargando sugerencias...</span>
            <span class="text-xs text-sena-text-soft" id="filtro-activo">Mostrando todos</span>
          </div>

          <!-- Loader -->
          <div id="loader" class="loader"></div>

          <!-- Mensaje de error -->
          <div id="error-mensaje" class="hidden text-center py-8 text-red-600 bg-red-50 rounded-lg">
            Error al cargar las sugerencias. Por favor, intenta de nuevo.
          </div>

          <!-- Tarjetas container - Sin estilos de grid cuando está vacío -->
          <div id="tarjetas-container"></div>
        </div>
      </div>
    </div>
  </main>

  <!-- Script para la funcionalidad del filtro bonito y modales -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Variables globales
      const API_URL = '../../controllers/SugerenciasController.php';
      const idUsuarioActual = <?php echo $id_usuario_actual; ?>;
      
      let todasLasTarjetas = []; // Almacenar todas las sugerencias
      
      // Elementos del DOM
      const tarjetasContainer = document.getElementById('tarjetas-container');
      const loader = document.getElementById('loader');
      const errorMensaje = document.getElementById('error-mensaje');
      const resultadosCount = document.getElementById('resultados-count');
      const filtroActivo = document.getElementById('filtro-activo');
      const contadorTodos = document.getElementById('contador-todos');
      const contadorActivos = document.getElementById('contador-activos');
      const contadorInactivos = document.getElementById('contador-inactivos');

      // Funcionalidad del modal de crear
      const modalCrear = document.getElementById('modal-crear-sugerencia');
      const btnAbrirModal = document.getElementById('btn-nuevo-perfil');
      const btnCerrarModal = document.querySelectorAll('.cerrar-modal-crear');
      const formCrear = document.getElementById('form-nueva-proyeccion-futuro');
      const inputTitulo = document.getElementById('input-titulo-sugerencia');
      const inputContenido = document.querySelector('#modal-crear-sugerencia textarea[name="descripcion"]');

      // Funcionalidad del modal de éxito (crear)
      const modalExito = document.getElementById('modal-creado-confirmacion-proyeccion-futura');
      const btnCerrarExito = document.querySelectorAll('.cerrar-modal-creado');
      const nombreSugerenciaSpan = document.getElementById('nombre-sugerencia-creada');
      const contadorSegundos = document.getElementById('contador-segundos-creado');
      const progressBar = document.getElementById('progress-bar-creado');

      // Funcionalidad del modal de editar
      const modalEditar = document.getElementById('modal-editar-sugerencia');
      const formEditar = document.querySelector('#modal-editar-sugerencia form');
      const inputTituloEditar = document.querySelector('#modal-editar-sugerencia input[name="titulo"]');
      const textareaDescripcionEditar = document.querySelector('#modal-editar-sugerencia textarea[name="descripcion"]');
      const btnsEditar = document.querySelectorAll('.btn-editar-proyeccion');

      // Funcionalidad del modal de éxito (editar)
      const modalEditadoExito = document.getElementById('modal-editado-confirmacion');
      const btnCerrarEditadoExito = document.querySelectorAll('.cerrar-modal-editado');
      const nombreProyeccionEditado = document.getElementById('nombre-proyeccion-editado');
      const contadorSegundosEditado = document.getElementById('contador-segundos-editado');
      const progressBarEditado = document.getElementById('progress-bar-editado');

      // Funcionalidad del modal de deshabilitar
      const modalDeshabilitar = document.getElementById('modal-deshabilitar-sugerencia');
      const btnCerrarDeshabilitar = document.querySelectorAll('.cerrar-modal-deshabilitar');
      const btnConfirmarDeshabilitar = document.getElementById('btn-confirmar-deshabilitar');
      const nombreSugerenciaDeshabilitar = document.querySelector('#modal-deshabilitar-sugerencia .font-medium.text-sena-text-main');
      
      // Funcionalidad del modal de éxito (deshabilitar)
      const modalDeshabilitadoExito = document.getElementById('modal-deshabilitado-sugerencia');
      const btnCerrarDeshabilitadoExito = document.querySelectorAll('.cerrar-modal-deshabilitado');
      const nombreProyeccionDeshabilitado = document.getElementById('nombre-proyeccion-futuro-deshabilitado');
      const contadorSegundosDeshabilitado = document.getElementById('contador-segundos-deshabilitado');
      const progressBarDeshabilitado = document.getElementById('progress-bar-deshabilitado');

      // Funcionalidad del modal de habilitar
      const modalHabilitar = document.getElementById('modal-habilitar-sugerencia');
      const btnCerrarHabilitar = document.querySelectorAll('.cerrar-modal-habilitar');
      const btnConfirmarHabilitar = document.getElementById('btn-confirmar-habilitar');
      const nombreSugerenciaHabilitar = document.getElementById('nombre-suegerencia-habilitar');

      // Funcionalidad del modal de éxito (habilitar)
      const modalHabilitadoExito = document.getElementById('modal-habilitado-confirmacion-sugerencia');
      const btnCerrarHabilitadoExito = document.querySelectorAll('.cerrar-modal-habilitado-confirmacion');
      const nombreProyeccionHabilitado = document.getElementById('nombre-proyeccion-habilitado-exito');
      const contadorSegundosHabilitado = document.getElementById('contador-segundos');
      const progressBarHabilitado = document.getElementById('progress-bar-habilitado');

      let switches = [];
      let idDeshabilitando = null;
      let nombreDeshabilitando = null;
      let idHabilitando = null;
      let nombreHabilitando = null;
      let idEditando = null;
      
      let timeoutExito;
      let intervaloContador;
      let timeoutExitoEditado;
      let intervaloContadorEditado;
      let timeoutExitoDeshabilitado;
      let intervaloContadorDeshabilitado;
      let timeoutExitoHabilitado;
      let intervaloContadorHabilitado;

      // ==================== FUNCIÓN PARA MOSTRAR TOAST DE VALIDACIÓN ====================
      function mostrarToastValidacion(mensaje, tipo = 'warning') {
        const toastContainer = document.getElementById('toast-container');
        
        // Definir títulos según el tipo
        const titulo = tipo === 'warning' ? 'Campo requerido' : 'Error';
        
        // Crear elemento toast
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast-validation ${tipo}`;
        
        // Iconos según tipo
        const iconos = {
          warning: `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
          `,
          error: `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          `
        };

        toast.innerHTML = `
          <div class="toast-contenido">
            <div class="toast-icono-wrapper">
              <div class="toast-icono">
                ${iconos[tipo]}
              </div>
            </div>
            <div class="toast-mensaje-wrapper">
              <div class="toast-titulo">${titulo}</div>
              <div class="toast-mensaje">${mensaje}</div>
            </div>
          </div>
        `;

        toastContainer.appendChild(toast);

        // Auto cerrar después de 3 segundos
        setTimeout(() => {
          const toastElement = document.getElementById(toastId);
          if (toastElement) {
            toastElement.classList.add('exit');
            setTimeout(() => {
              if (toastElement.parentNode) {
                toastElement.remove();
              }
            }, 200);
          }
        }, 3000);
      }

      // ==================== FUNCIONES API ====================

      // Cargar sugerencias desde la base de datos
      async function cargarSugerencias() {
        try {
          loader.classList.remove('hidden');
          errorMensaje.classList.add('hidden');
          tarjetasContainer.innerHTML = '';

          const response = await fetch(`${API_URL}?accion=listarTodas`);
          const data = await response.json();

          // Verificar el formato de la respuesta
          if (data.status === 'success' || data.success === true) {
            // Si viene como data.data o directamente data
            const sugerencias = data.data || data;
            todasLasTarjetas = Array.isArray(sugerencias) ? sugerencias : [];
            renderizarTarjetas(todasLasTarjetas);
            actualizarContadores(todasLasTarjetas);
          } else {
            console.error('Error en la respuesta:', data);
            throw new Error('Error al cargar las sugerencias');
          }
        } catch (error) {
          console.error('Error:', error);
          errorMensaje.classList.remove('hidden');
          tarjetasContainer.innerHTML = '';
          mostrarToastValidacion('Error al cargar las sugerencias', 'error');
        } finally {
          loader.classList.add('hidden');
        }
      }

      // Renderizar tarjetas en el DOM
      function renderizarTarjetas(sugerencias) {
        if (!sugerencias || sugerencias.length === 0) {
          // Empty state independiente, sin estilos de grid
          tarjetasContainer.innerHTML = `
            <div class="w-full flex flex-col items-center justify-center py-20 px-4 bg-white border border-gray-200 rounded-xl">
              <div class="w-20 h-20 mb-5 bg-sena-soft rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sena" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                  <line x1="12" x2="12.01" y1="17" y2="17"/>
                </svg>
              </div>
              <h3 class="font-['Montserrat'] text-lg font-semibold text-sena-text-main mb-2">No hay sugerencias</h3>
              <p class="text-sm text-sena-text-soft text-center max-w-sm mb-6">
                Comienza creando tu primera sugerencia innovadora para fortalecer el desarrollo tecnológico de Risaralda.
              </p>
              <button id="btn-crear-desde-empty" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-sena rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M5 12h14"/>
                  <path d="M12 5v14"/>
                </svg>
                Crear primera sugerencia
              </button>
            </div>
          `;

          // Agregar evento al botón del empty state
          const btnCrearEmpty = document.getElementById('btn-crear-desde-empty');
          if (btnCrearEmpty && modalCrear) {
            btnCrearEmpty.addEventListener('click', function() {
              modalCrear.classList.remove('hidden');
              document.body.style.overflow = 'hidden';
            });
          }
          return;
        }

        // Si hay sugerencias, usamos el grid
        tarjetasContainer.className = 'grid grid-cols-1 md:grid-cols-3 gap-3';
        tarjetasContainer.innerHTML = sugerencias.map(s => crearTarjetaHTML(s)).join('');
        
        // Actualizar referencia a los switches después de renderizar
        switches = document.querySelectorAll('.switch-sena');
        asignarEventosASwitches();
        
        // Actualizar referencia a los botones de editar
        const nuevosBtnsEditar = document.querySelectorAll('.btn-editar-proyeccion');
        nuevosBtnsEditar.forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.stopPropagation();
            abrirModalEditar(this);
          });
        });
      }

      // Crear HTML de una tarjeta
      function crearTarjetaHTML(s) {
        // Validar que la fecha exista
        let fecha = 'Fecha no disponible';
        if (s.fecha_creacion) {
          try {
            fecha = new Date(s.fecha_creacion).toLocaleDateString('es-ES', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric'
            });
          } catch (e) {
            fecha = 'Fecha inválida';
          }
        }
        
        const estadoClass = s.estado == 1 ? 'active' : '';
        const estadoTitle = s.estado == 1 ? 'Activo' : 'Inactivo';
        
        // Limitar longitud del título y contenido
        const tituloCorto = s.titulo && s.titulo.length > 50 ? s.titulo.substring(0, 50) + '...' : (s.titulo || 'Sin título');
        const contenidoCorto = s.contenido && s.contenido.length > 80 ? s.contenido.substring(0, 80) + '...' : (s.contenido || 'Sin contenido');

        // Escapar caracteres especiales para atributos HTML
        const tituloEscapado = (s.titulo || '').replace(/"/g, '&quot;');
        const contenidoEscapado = (s.contenido || '').replace(/"/g, '&quot;');

        return `
          <div class="tarjeta-tecnologia border border-sena-border rounded-lg bg-white p-4 hover:border-sena/30 hover:shadow-sm transition-all cursor-pointer" 
               data-id="${s.id_sugerencia}" 
               data-nombre="${tituloEscapado}"
               data-descripcion="${contenidoEscapado}"
               data-estado="${s.estado == 1 ? 'activo' : 'inactivo'}"
               data-fecha="${fecha}">
            <div class="flex items-start justify-between mb-2">
              <div class="w-10 h-10 bg-sena-soft rounded-lg flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-sena">
                  <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                  <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                  <line x1="12" x2="12.01" y1="17" y2="17"/>
                </svg>
              </div>
              <div class="flex items-center gap-1">
                <button class="btn-editar-proyeccion p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors" 
                        title="Editar sugerencia" 
                        data-id="${s.id_sugerencia}" 
                        data-nombre="${tituloEscapado}" 
                        data-descripcion="${contenidoEscapado}">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/>
                  </svg>
                </button>
                <div class="switch-sena ${estadoClass}" title="${estadoTitle}" data-id="${s.id_sugerencia}" data-nombre="${tituloEscapado}"></div>
              </div>
            </div>
            <h3 class="font-['Montserrat'] text-sm font-semibold text-sena-text-main mb-1">${tituloCorto}</h3>
            <p class="text-xs text-sena-text-soft line-clamp-2">${contenidoCorto}</p>
            <hr class="my-3 mt-2 border-sena-border">
            <div class="flex items-center justify-end gap-2 mt-2 text-xs text-sena-text-soft">
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>${s.representante_legal || s.nombre_empresa || 'Usuario'}</span>
              </div>
              <div class="flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span>${fecha}</span>
              </div>
            </div>
          </div>
        `;
      }

      // Asignar eventos a los switches
      function asignarEventosASwitches() {
        switches.forEach(switchEl => {
          switchEl.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const id = this.dataset.id;
            const nombre = this.dataset.nombre;
            
            if (this.classList.contains('active')) {
              // Está activo -> deshabilitar
              abrirModalDeshabilitar(id, nombre);
            } else {
              // Está inactivo -> habilitar
              abrirModalHabilitar(id, nombre);
            }
          });
        });
      }

      // Actualizar contadores
      function actualizarContadores(sugerencias) {
        const activos = sugerencias.filter(s => s.estado == 1).length;
        const inactivos = sugerencias.filter(s => s.estado == 0).length;
        
        contadorTodos.textContent = sugerencias.length;
        contadorActivos.textContent = activos;
        contadorInactivos.textContent = inactivos;
        
        // Aplicar filtro actual
        const filtroActual = document.querySelector('.filtro-btn.active')?.dataset.filtro || 'todos';
        filtrarTarjetas(filtroActual);
      }

      // Filtrar tarjetas según estado
      function filtrarTarjetas(filtro) {
        const tarjetas = document.querySelectorAll('.tarjeta-tecnologia');
        let visibleCount = 0;
        
        tarjetas.forEach(tarjeta => {
          const estado = tarjeta.dataset.estado;
          let mostrar = false;
          
          if (filtro === 'todos') {
            mostrar = true;
          } else if (filtro === 'activos' && estado === 'activo') {
            mostrar = true;
          } else if (filtro === 'inactivos' && estado === 'inactivo') {
            mostrar = true;
          }
          
          if (mostrar) {
            tarjeta.style.display = 'block';
            visibleCount++;
          } else {
            tarjeta.style.display = 'none';
          }
        });
        
        resultadosCount.textContent = `${visibleCount} sugerencia${visibleCount !== 1 ? 's' : ''}`;
        const filtroTexto = filtro === 'todos' ? 'todos' : filtro === 'activos' ? 'activos' : 'inactivos';
        filtroActivo.textContent = `Mostrando ${filtroTexto}`;
      }

      // ==================== FUNCIONES CRUD ====================

      // Crear nueva sugerencia
      async function crearSugerencia(titulo, contenido) {
        try {
          const response = await fetch(`${API_URL}?accion=crear`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              id_usuario: idUsuarioActual,
              titulo: titulo,
              contenido: contenido,
              estado: 1
            })
          });

          const data = await response.json();
          
          if (data.success) {
            await cargarSugerencias(); // Recargar lista
            return { success: true, id: data.id_sugerencia };
          } else {
            return { success: false, error: data.error || 'Error al crear' };
          }
        } catch (error) {
          console.error('Error al crear:', error);
          return { success: false, error: 'Error de conexión' };
        }
      }

      // Actualizar sugerencia
      async function actualizarSugerencia(id, titulo, contenido) {
        try {
          const response = await fetch(`${API_URL}?accion=actualizar`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              id_sugerencia: id,
              titulo: titulo,
              contenido: contenido
            })
          });

          const data = await response.json();
          
          if (data.success) {
            await cargarSugerencias(); // Recargar lista
            return { success: true };
          } else {
            return { success: false, error: data.error || 'Error al actualizar' };
          }
        } catch (error) {
          console.error('Error al actualizar:', error);
          return { success: false, error: 'Error de conexión' };
        }
      }

      // Cambiar estado (activar/desactivar)
      async function cambiarEstado(id, accion) {
        try {
          const response = await fetch(`${API_URL}?accion=${accion}&id_sugerencia=${id}`, {
            method: 'POST'
          });

          const data = await response.json();
          
          if (data.success) {
            await cargarSugerencias(); // Recargar lista
            return { success: true };
          } else {
            return { success: false, error: data.error || `Error al ${accion}` };
          }
        } catch (error) {
          console.error('Error al cambiar estado:', error);
          return { success: false, error: 'Error de conexión' };
        }
      }

      // ==================== FUNCIONES DE MODALES ====================

      // Abrir modal de crear
      if (btnAbrirModal && modalCrear) {
        btnAbrirModal.addEventListener('click', function() {
          modalCrear.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
        });
      }

      // Abrir modal de editar
      function abrirModalEditar(btn) {
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const descripcion = btn.dataset.descripcion;
        
        idEditando = id;
        
        if (inputTituloEditar) {
          inputTituloEditar.value = nombre;
        }
        if (textareaDescripcionEditar) {
          textareaDescripcionEditar.value = descripcion;
        }
        
        if (formEditar) {
          formEditar.dataset.editandoId = id;
        }
        
        modalEditar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      // Abrir modal de deshabilitar
      function abrirModalDeshabilitar(id, nombre) {
        idDeshabilitando = id;
        nombreDeshabilitando = nombre;
        
        if (nombreSugerenciaDeshabilitar) {
          nombreSugerenciaDeshabilitar.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      // Abrir modal de habilitar
      function abrirModalHabilitar(id, nombre) {
        idHabilitando = id;
        nombreHabilitando = nombre;
        
        if (nombreSugerenciaHabilitar) {
          nombreSugerenciaHabilitar.textContent = `"${nombre}"`;
        }
        
        modalHabilitar.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      }

      // Cerrar modal de crear y editar
      btnCerrarModal.forEach(btn => {
        btn.addEventListener('click', function() {
          if (modalCrear && !modalCrear.classList.contains('hidden')) {
            modalCrear.classList.add('hidden');
          }
          if (modalEditar && !modalEditar.classList.contains('hidden')) {
            modalEditar.classList.add('hidden');
            idEditando = null;
          }
          document.body.style.overflow = '';
        });
      });

      // Cerrar modal de deshabilitar
      btnCerrarDeshabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
          modalDeshabilitar.classList.add('hidden');
          document.body.style.overflow = '';
          idDeshabilitando = null;
          nombreDeshabilitando = null;
        });
      });

      // Cerrar modal de habilitar
      btnCerrarHabilitar.forEach(btn => {
        btn.addEventListener('click', function() {
          modalHabilitar.classList.add('hidden');
          document.body.style.overflow = '';
          idHabilitando = null;
          nombreHabilitando = null;
        });
      });

      // Confirmar deshabilitar
      if (btnConfirmarDeshabilitar) {
        btnConfirmarDeshabilitar.addEventListener('click', async function() {
          if (idDeshabilitando && nombreDeshabilitando) {
            const resultado = await cambiarEstado(idDeshabilitando, 'desactivar');
            
            if (resultado.success) {
              modalDeshabilitar.classList.add('hidden');
              abrirModalDeshabilitadoExito(nombreDeshabilitando);
              // NO mostramos toast de éxito
            } else {
              mostrarToastValidacion('Ocurrió un error al deshabilitar la sugerencia: ' + resultado.error, 'error');
            }
            
            idDeshabilitando = null;
            nombreDeshabilitando = null;
          }
        });
      }

      // Confirmar habilitar
      if (btnConfirmarHabilitar) {
        btnConfirmarHabilitar.addEventListener('click', async function() {
          if (idHabilitando && nombreHabilitando) {
            const resultado = await cambiarEstado(idHabilitando, 'activar');
            
            if (resultado.success) {
              modalHabilitar.classList.add('hidden');
              abrirModalHabilitadoExito(nombreHabilitando);
              // NO mostramos toast de éxito
            } else {
              mostrarToastValidacion('Ocurrió un error al habilitar la sugerencia: ' + resultado.error, 'error');
            }
            
            idHabilitando = null;
            nombreHabilitando = null;
          }
        });
      }

      // Manejar envío del formulario de crear
      if (formCrear) {
        formCrear.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const titulo = inputTitulo ? inputTitulo.value.trim() : '';
          const contenido = inputContenido ? inputContenido.value.trim() : '';
          
          if (!titulo) {
            mostrarToastValidacion('Por favor ingresa un título para la sugerencia', 'warning');
            return;
          }
          
          if (!contenido) {
            mostrarToastValidacion('Por favor ingresa una descripción para la sugerencia', 'warning');
            return;
          }
          
          const resultado = await crearSugerencia(titulo, contenido);
          
          if (resultado.success) {
            modalCrear.classList.add('hidden');
            formCrear.reset();
            abrirModalExito(titulo);
            // NO mostramos toast de éxito
          } else {
            mostrarToastValidacion('Error al crear: ' + resultado.error, 'error');
          }
        });
      }

      // Manejar envío del formulario de editar
      if (formEditar) {
        formEditar.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const titulo = inputTituloEditar ? inputTituloEditar.value.trim() : '';
          const descripcion = textareaDescripcionEditar ? textareaDescripcionEditar.value.trim() : '';
          const id = this.dataset.editandoId;
          
          if (!titulo) {
            mostrarToastValidacion('Por favor ingresa un título para la sugerencia', 'warning');
            return;
          }
          
          if (!descripcion) {
            mostrarToastValidacion('Por favor ingresa una descripción para la sugerencia', 'warning');
            return;
          }
          
          const resultado = await actualizarSugerencia(id, titulo, descripcion);
          
          if (resultado.success) {
            modalEditar.classList.add('hidden');
            formEditar.reset();
            delete formEditar.dataset.editandoId;
            abrirModalEditadoExito(titulo);
            // NO mostramos toast de éxito
          } else {
            mostrarToastValidacion('Error al actualizar: ' + resultado.error, 'error');
          }
        });
      }

      // ==================== FUNCIONES DE MODALES DE ÉXITO ====================

      // Función para cerrar modal de éxito (crear)
      function cerrarModalExito() {
        if (modalExito) {
          modalExito.classList.add('hidden');
          document.body.style.overflow = '';
          
          if (timeoutExito) clearTimeout(timeoutExito);
          if (intervaloContador) clearInterval(intervaloContador);
          
          if (progressBar) progressBar.style.width = '0%';
          if (contadorSegundos) contadorSegundos.textContent = '3';
        }
      }

      // Función para abrir modal de éxito (crear)
      function abrirModalExito(titulo) {
        if (nombreSugerenciaSpan) {
          nombreSugerenciaSpan.textContent = `"${titulo}"`;
        }
        
        modalExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        contadorSegundos.textContent = segundos;
        progressBar.style.width = '0%';
        
        void progressBar.offsetWidth;
        
        setTimeout(() => {
          progressBar.style.width = '100%';
        }, 50);
        
        intervaloContador = setInterval(() => {
          segundos--;
          if (contadorSegundos) {
            contadorSegundos.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervaloContador);
          }
        }, 1000);
        
        timeoutExito = setTimeout(() => {
          cerrarModalExito();
        }, 3000);
      }

      // Función para cerrar modal de éxito (editar)
      function cerrarModalEditadoExito() {
        if (modalEditadoExito) {
          modalEditadoExito.classList.add('hidden');
          document.body.style.overflow = '';
          
          if (timeoutExitoEditado) clearTimeout(timeoutExitoEditado);
          if (intervaloContadorEditado) clearInterval(intervaloContadorEditado);
          
          if (progressBarEditado) progressBarEditado.style.width = '0%';
          if (contadorSegundosEditado) contadorSegundosEditado.textContent = '3';
        }
      }

      // Función para abrir modal de éxito (editar)
      function abrirModalEditadoExito(titulo) {
        if (!modalEditadoExito) {
          console.error('Modal de éxito editado no encontrado');
          return;
        }
        
        if (nombreProyeccionEditado) {
          nombreProyeccionEditado.textContent = `"${titulo}"`;
        }
        
        modalEditadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosEditado) contadorSegundosEditado.textContent = segundos;
        if (progressBarEditado) {
          progressBarEditado.style.width = '0%';
          void progressBarEditado.offsetWidth;
          setTimeout(() => {
            progressBarEditado.style.width = '100%';
          }, 50);
        }
        
        if (intervaloContadorEditado) clearInterval(intervaloContadorEditado);
        intervaloContadorEditado = setInterval(() => {
          segundos--;
          if (contadorSegundosEditado) {
            contadorSegundosEditado.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervaloContadorEditado);
          }
        }, 1000);
        
        if (timeoutExitoEditado) clearTimeout(timeoutExitoEditado);
        timeoutExitoEditado = setTimeout(() => {
          cerrarModalEditadoExito();
        }, 3000);
      }

      // Función para cerrar modal de éxito (deshabilitar)
      function cerrarModalDeshabilitadoExito() {
        if (modalDeshabilitadoExito) {
          modalDeshabilitadoExito.classList.add('hidden');
          document.body.style.overflow = '';
          
          if (timeoutExitoDeshabilitado) clearTimeout(timeoutExitoDeshabilitado);
          if (intervaloContadorDeshabilitado) clearInterval(intervaloContadorDeshabilitado);
          
          if (progressBarDeshabilitado) progressBarDeshabilitado.style.width = '0%';
          if (contadorSegundosDeshabilitado) contadorSegundosDeshabilitado.textContent = '3';
        }
      }

      // Función para abrir modal de éxito (deshabilitar)
      function abrirModalDeshabilitadoExito(nombre) {
        if (!modalDeshabilitadoExito) {
          console.error('Modal de éxito deshabilitado no encontrado');
          return;
        }
        
        if (nombreProyeccionDeshabilitado) {
          nombreProyeccionDeshabilitado.textContent = `"${nombre}"`;
        }
        
        modalDeshabilitadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosDeshabilitado) contadorSegundosDeshabilitado.textContent = segundos;
        if (progressBarDeshabilitado) {
          progressBarDeshabilitado.style.width = '0%';
          void progressBarDeshabilitado.offsetWidth;
          setTimeout(() => {
            progressBarDeshabilitado.style.width = '100%';
          }, 50);
        }
        
        if (intervaloContadorDeshabilitado) clearInterval(intervaloContadorDeshabilitado);
        intervaloContadorDeshabilitado = setInterval(() => {
          segundos--;
          if (contadorSegundosDeshabilitado) {
            contadorSegundosDeshabilitado.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervaloContadorDeshabilitado);
          }
        }, 1000);
        
        if (timeoutExitoDeshabilitado) clearTimeout(timeoutExitoDeshabilitado);
        timeoutExitoDeshabilitado = setTimeout(() => {
          cerrarModalDeshabilitadoExito();
        }, 3000);
      }

      // Función para cerrar modal de éxito (habilitar)
      function cerrarModalHabilitadoExito() {
        if (modalHabilitadoExito) {
          modalHabilitadoExito.classList.add('hidden');
          document.body.style.overflow = '';
          
          if (timeoutExitoHabilitado) clearTimeout(timeoutExitoHabilitado);
          if (intervaloContadorHabilitado) clearInterval(intervaloContadorHabilitado);
          
          if (progressBarHabilitado) progressBarHabilitado.style.width = '0%';
          if (contadorSegundosHabilitado) contadorSegundosHabilitado.textContent = '3';
        }
      }

      // Función para abrir modal de éxito (habilitar)
      function abrirModalHabilitadoExito(nombre) {
        if (!modalHabilitadoExito) {
          console.error('Modal de éxito habilitado no encontrado');
          return;
        }
        
        if (nombreProyeccionHabilitado) {
          nombreProyeccionHabilitado.textContent = `"${nombre}"`;
        }
        
        modalHabilitadoExito.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        let segundos = 3;
        if (contadorSegundosHabilitado) contadorSegundosHabilitado.textContent = segundos;
        if (progressBarHabilitado) {
          progressBarHabilitado.style.width = '0%';
          void progressBarHabilitado.offsetWidth;
          setTimeout(() => {
            progressBarHabilitado.style.width = '100%';
          }, 50);
        }
        
        if (intervaloContadorHabilitado) clearInterval(intervaloContadorHabilitado);
        intervaloContadorHabilitado = setInterval(() => {
          segundos--;
          if (contadorSegundosHabilitado) {
            contadorSegundosHabilitado.textContent = segundos;
          }
          if (segundos <= 0) {
            clearInterval(intervaloContadorHabilitado);
          }
        }, 1000);
        
        if (timeoutExitoHabilitado) clearTimeout(timeoutExitoHabilitado);
        timeoutExitoHabilitado = setTimeout(() => {
          cerrarModalHabilitadoExito();
        }, 3000);
      }

      // Cerrar modal de éxito (crear)
      btnCerrarExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalExito);
      });

      // Cerrar modal de éxito (editar)
      btnCerrarEditadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalEditadoExito);
      });

      // Cerrar modal de éxito (deshabilitar)
      btnCerrarDeshabilitadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalDeshabilitadoExito);
      });

      // Cerrar modal de éxito (habilitar)
      btnCerrarHabilitadoExito.forEach(btn => {
        btn.addEventListener('click', cerrarModalHabilitadoExito);
      });

      // Cerrar modales con tecla ESC
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (modalCrear && !modalCrear.classList.contains('hidden')) {
            modalCrear.classList.add('hidden');
            document.body.style.overflow = '';
          }
          if (modalEditar && !modalEditar.classList.contains('hidden')) {
            modalEditar.classList.add('hidden');
            document.body.style.overflow = '';
          }
          if (modalDeshabilitar && !modalDeshabilitar.classList.contains('hidden')) {
            modalDeshabilitar.classList.add('hidden');
            document.body.style.overflow = '';
          }
          if (modalHabilitar && !modalHabilitar.classList.contains('hidden')) {
            modalHabilitar.classList.add('hidden');
            document.body.style.overflow = '';
          }
          if (modalExito && !modalExito.classList.contains('hidden')) {
            cerrarModalExito();
          }
          if (modalEditadoExito && !modalEditadoExito.classList.contains('hidden')) {
            cerrarModalEditadoExito();
          }
          if (modalDeshabilitadoExito && !modalDeshabilitadoExito.classList.contains('hidden')) {
            cerrarModalDeshabilitadoExito();
          }
          if (modalHabilitadoExito && !modalHabilitadoExito.classList.contains('hidden')) {
            cerrarModalHabilitadoExito();
          }
        }
      });

      // Prevenir cierre al hacer clic dentro del modal
      [modalCrear, modalExito, modalEditar, modalEditadoExito, modalDeshabilitar, modalDeshabilitadoExito, modalHabilitar, modalHabilitadoExito].forEach(modal => {
        if (modal) {
          modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('fixed')) {
              if (modal === modalExito) {
                cerrarModalExito();
              } else if (modal === modalEditadoExito) {
                cerrarModalEditadoExito();
              } else if (modal === modalDeshabilitadoExito) {
                cerrarModalDeshabilitadoExito();
              } else if (modal === modalHabilitadoExito) {
                cerrarModalHabilitadoExito();
              } else {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
              }
            }
          });
        }
      });

      // Filtros
      const filtroBtns = document.querySelectorAll('.filtro-btn');
      filtroBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          filtroBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          const filtro = this.dataset.filtro;
          filtrarTarjetas(filtro);
        });
      });

      // Cargar sugerencias al iniciar
      cargarSugerencias();
    });
  </script>

</body>
</html>
<?php include __DIR__ . '../../../includes/footer.php'; ?>