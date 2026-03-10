
document.addEventListener("DOMContentLoaded", () => {
  let cards = Array.from(document.querySelectorAll("main .grid > div.bg-white.rounded-xl"));
  const cardsGrid = document.querySelector("main .grid");
  const inputBuscarLinea = document.getElementById("input-buscar-linea");
  const lineasEncontradasCount = document.getElementById("lineas-encontradas-count");
  const lineasEncontradasLabel = document.getElementById("lineas-encontradas-label");
  const btnNuevaLinea = document.getElementById("btn-nueva-linea");
  const btnDetalleEditar = document.getElementById("btn-detalle-editar");
  const btnDetalleDeshabilitar = document.getElementById("btn-detalle-deshabilitar");
  const detalleTitulo = document.getElementById("detalle-linea-titulo");
  const detalleBadgeEstado = document.getElementById("detalle-badge-estado");
  const detalleEstadoTitulo = document.getElementById("detalle-estado-titulo");
  const detalleEstadoTexto = document.getElementById("detalle-estado-texto");
  const STORAGE_KEY = "observatorio_lineas_tecnologicas_v1";

  const tendenciasEmergentesDisponibles = [
    "Manufactura aditiva",
    "Avances en técnicas de mecanizado",
    "Sistemas de soldadura",
    "Sistemas de corte automatizado",
    "Digitalización del mantenimiento",
    "IA y ciberseguridad",
    "Construcción modular y sostenible",
    "IoT y robótica",
    "Desarrollo de sistemas de control y automatización",
  ];

  const etapasDisponibles = [
    "Investigación inicial",
    "Desarrollo tecnológico",
    "Implementación piloto",
    "Adopción industrial",
  ];
  const proyeccionesDisponibles = ["5 años", "10 años", "Más de 10 años"];

  const estadosInicialesPorNombre = {
    "Computacion en la Nube": {
      active: true,
      tendencia: "Digitalización del mantenimiento",
      etapa: "Implementación piloto",
      proyeccion: "5 años",
    },
    "Ciberseguridad Avanzada": {
      active: true,
      tendencia: "IA y ciberseguridad",
      etapa: "Desarrollo tecnológico",
      proyeccion: "5 años",
    },
    "Big Data y Analitica": {
      active: true,
      tendencia: "IA y ciberseguridad",
      etapa: "Investigación inicial",
      proyeccion: "10 años",
    },
    "Inteligencia Artificial": {
      active: true,
      tendencia: "IA y ciberseguridad",
      etapa: "Desarrollo tecnológico",
      proyeccion: "5 años",
    },
    Blockchain: {
      active: true,
      tendencia: "IA y ciberseguridad",
      etapa: "Investigación inicial",
      proyeccion: "Más de 10 años",
    },
    "Internet de las Cosas (IoT)": {
      active: true,
      tendencia: "IoT y robótica",
      etapa: "Implementación piloto",
      proyeccion: "5 años",
    },
    "Manufactura Aditiva (Impresion 3D)": {
      active: true,
      tendencia: "Manufactura aditiva",
      etapa: "Implementación piloto",
      proyeccion: "10 años",
    },
    "Realidad Aumentada/Virtual": {
      active: true,
      tendencia: "IA y ciberseguridad",
      etapa: "Investigación inicial",
      proyeccion: "Más de 10 años",
    },
    "Robotica Colaborativa": {
      active: true,
      tendencia: "IoT y robótica",
      etapa: "Desarrollo tecnológico",
      proyeccion: "10 años",
    },
  };

  const estadoLineas = {};
  let lineaEnEdicion = null;
  let lineaPendienteDeshabilitar = null;
  let lineaPendienteHabilitar = null;
  let successTimeout = null;
  let successInterval = null;
  let detalleActivo = true;

  const modals = crearModales();
  aplicarEstiloFlechaSelect();

  function aplicarEstiloFlechaSelect() {
    if (!document.getElementById("linea-tec-select-arrow-style")) {
      const style = document.createElement("style");
      style.id = "linea-tec-select-arrow-style";
      style.textContent = `
        .linea-tec-select {
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          padding-right: 2.75rem !important;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5.5 7.5L10 12l4.5-4.5' stroke='%236B7280' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 0.85rem center;
          background-size: 0.9rem;
        }

        .linea-tec-select::-ms-expand {
          display: none;
        }
      `;
      document.head.appendChild(style);
    }

    [modals.createModal, modals.editModal].forEach((modal) => {
      modal.querySelectorAll("select").forEach((select) => {
        select.classList.add("linea-tec-select");
      });
    });
  }

  function setSelectEnabled(select, enabled) {
    select.disabled = !enabled;
    select.classList.toggle("bg-gray-100", !enabled);
    select.classList.toggle("cursor-not-allowed", !enabled);
  }

  function resetSelect(select, placeholder) {
    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.value = "";
  }

  function fillSelect(select, values, placeholder) {
    resetSelect(select, placeholder);
    values.forEach((value) => {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = value;
      select.appendChild(option);
    });
  }

  fillSelect(modals.createTendencia, tendenciasEmergentesDisponibles, "Seleccionar tendencia tecnologica emergente...");
  fillSelect(modals.createEtapa, etapasDisponibles, "Seleccionar etapa...");
  fillSelect(modals.createProyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");

  fillSelect(modals.editTendencia, tendenciasEmergentesDisponibles, "Seleccionar tendencia tecnologica emergente...");
  fillSelect(modals.editEtapa, etapasDisponibles, "Seleccionar etapa...");
  fillSelect(modals.editProyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");

  function abrirModal(modal) {
    modal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
  }

  function cerrarModal(modal) {
    modal.classList.add("hidden");
    if (!document.querySelector(".linea-tec-modal:not(.hidden)")) {
      document.body.classList.remove("overflow-hidden");
    }
  }

  function normalizarTexto(value) {
    return value
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .trim();
  }

  function aplicarFiltroBusqueda() {
    const termino = normalizarTexto(inputBuscarLinea?.value || "");
    let visibles = 0;

    cards.forEach((card) => {
      const titleEl = card.querySelector("h3");
      const searchableText = normalizarTexto(titleEl?.textContent || "");
      const visible = termino === "" || searchableText.includes(termino);
      card.style.display = visible ? "flex" : "none";
      if (visible) {
        visibles += 1;
      }
    });

    if (lineasEncontradasCount) {
      lineasEncontradasCount.textContent = String(visibles);
    }
    if (lineasEncontradasLabel) {
      lineasEncontradasLabel.textContent = visibles === 1 ? "linea encontrada" : "lineas encontradas";
    }
  }

  function getStorageLineas() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return parsed && typeof parsed === "object" ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function setStorageLineas(value) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(value));
    } catch (error) {
      // Ignore storage failures to avoid blocking UI behavior.
    }
  }

  function parseCardResumen(card) {
    const resumen = card.querySelector("h3 + p");
    const text = (resumen?.textContent || "").replace(/\s+/g, " ").trim();
    const match = text.match(/(\d+)\s+vigentes?\s*[·\-]\s*(\d+)\s+lineas?/i);

    if (!match) {
      return { vigentes: 0, totalLineas: 0 };
    }

    return {
      vigentes: Number.parseInt(match[1], 10) || 0,
      totalLineas: Number.parseInt(match[2], 10) || 0,
    };
  }

  function parseCardPerfiles(card) {
    const counter = card.querySelector(".linea-tec-controls span") || card.querySelector(".flex.justify-between.items-start span");
    const text = (counter?.textContent || "").trim();
    const match = text.match(/(\d+)/);
    return match ? Number.parseInt(match[1], 10) || 0 : 0;
  }

  function parseCardTecnologias(card) {
    return Array.from(card.querySelectorAll(".flex.flex-wrap.gap-2 span"))
      .map((chip) => chip.textContent.trim())
      .filter(Boolean);
  }

  function actualizarLinkDetalle(card, nombre) {
    if (!card) {
      return;
    }

    const link = card.querySelector("a");
    if (!link) {
      return;
    }

    link.href = `detalles_lineas_tecnologicas.php?linea=${encodeURIComponent(nombre)}`;
  }

  function construirRegistroLinea(nombre, card) {
    const state = estadoLineas[nombre] || {};
    const resumen = parseCardResumen(card);

    return {
      nombre,
      active: state.active !== false,
      tendencia: state.tendencia || "",
      etapa: state.etapa || "",
      proyeccion: state.proyeccion || "",
      perfiles: parseCardPerfiles(card),
      vigentes: resumen.vigentes,
      totalLineas: resumen.totalLineas,
      tecnologias: parseCardTecnologias(card),
      descripcion: `Linea tecnologica ${nombre} registrada en el Observatorio CTI.`,
      fechaActualizacion: new Date().toISOString(),
    };
  }

  function getDefaultStateForNombre(nombre) {
    return (
      estadosInicialesPorNombre[nombre] || {
        active: true,
        tendencia: tendenciasEmergentesDisponibles[0] || "",
        etapa: etapasDisponibles[1] || etapasDisponibles[0] || "",
        proyeccion: proyeccionesDisponibles[0] || "",
      }
    );
  }

  function getLineaFromQuery() {
    try {
      const params = new URLSearchParams(window.location.search);
      return (params.get("linea") || "").trim();
    } catch (error) {
      return "";
    }
  }

  function findRegistroByNombre(data, nombreBuscado) {
    if (!nombreBuscado || !data || typeof data !== "object") {
      return null;
    }

    if (data[nombreBuscado]) {
      return data[nombreBuscado];
    }

    const normalizadoObjetivo = normalizarTexto(nombreBuscado);
    const key = Object.keys(data).find((k) => normalizarTexto(k) === normalizadoObjetivo);
    return key ? data[key] : null;
  }

  function formatFechaDetalle(value) {
    if (!value) {
      return "Sin registro";
    }

    const fecha = new Date(value);
    if (Number.isNaN(fecha.getTime())) {
      return "Sin registro";
    }

    return fecha.toLocaleDateString("es-CO", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
  }

  function setTextById(id, value, fallback = "No registrado") {
    const el = document.getElementById(id);
    if (!el) {
      return;
    }

    const hasValue = typeof value === "string" && value.trim() !== "";
    el.textContent = hasValue ? value.trim() : fallback;
  }

  function crearDescripcionDetalle(registro) {
    const partes = [];

    if (registro.tendencia) {
      partes.push(`La tendencia tecnologica emergente asociada es ${registro.tendencia}.`);
    }
    if (registro.etapa) {
      partes.push(`Actualmente se ubica en la etapa ${registro.etapa}.`);
    }
    if (registro.proyeccion) {
      partes.push(`La proyeccion estimada es ${registro.proyeccion}.`);
    }

    return partes.join(" ") || `Linea tecnologica ${registro.nombre || ""} registrada en el Observatorio CTI.`.trim();
  }

  function guardarRegistroDetalle(nombre) {
    if (!nombre) {
      return;
    }

    const state = estadoLineas[nombre] || getDefaultStateForNombre(nombre);
    const data = getStorageLineas();
    const existente = data[nombre] || {};

    const registro = {
      nombre,
      active: state.active !== false,
      tendencia: state.tendencia || "",
      etapa: state.etapa || "",
      proyeccion: state.proyeccion || "",
      perfiles: Number.isFinite(existente.perfiles) ? existente.perfiles : 0,
      vigentes: Number.isFinite(existente.vigentes) ? existente.vigentes : 0,
      totalLineas: Number.isFinite(existente.totalLineas) ? existente.totalLineas : 1,
      tecnologias: Array.isArray(existente.tecnologias) ? existente.tecnologias : [state.tendencia].filter(Boolean),
      descripcion: crearDescripcionDetalle({ nombre, ...state }),
      fechaActualizacion: new Date().toISOString(),
    };

    data[nombre] = registro;
    setStorageLineas(data);
  }

  function inicializarDetalleDesdeStorage() {
    if (!detalleTitulo) {
      return;
    }

    const nombreDesdeQuery = getLineaFromQuery();
    const nombreBase = nombreDesdeQuery || (detalleTitulo.textContent || "").trim() || "Linea Tecnologica";
    const data = getStorageLineas();
    const registro = findRegistroByNombre(data, nombreBase);
    const baseState = registro || {
      nombre: nombreBase,
      ...getDefaultStateForNombre(nombreBase),
      descripcion: "",
    };

    const nombreFinal = (baseState.nombre || nombreBase || "Linea Tecnologica").trim();
    detalleTitulo.textContent = nombreFinal;

    estadoLineas[nombreFinal] = {
      active: baseState.active !== false,
      tendencia: baseState.tendencia || "",
      etapa: baseState.etapa || "",
      proyeccion: baseState.proyeccion || "",
    };

    if (nombreFinal !== nombreBase && estadoLineas[nombreBase]) {
      delete estadoLineas[nombreBase];
    }

    setTextById("detalle-tendencia-badge", baseState.tendencia, "Tendencia tecnologica emergente");
    setTextById("detalle-nombre-linea-texto", nombreFinal, "No registrada");
    setTextById("detalle-tendencia-texto", baseState.tendencia);
    setTextById("detalle-etapa-texto", baseState.etapa);
    setTextById("detalle-proyeccion-texto", baseState.proyeccion);
    setTextById("detalle-fecha-actualizacion", formatFechaDetalle(baseState.fechaActualizacion), "Sin registro");
    setTextById("detalle-tendencia-tag-texto", baseState.tendencia, "Tendencia emergente");
    setTextById("detalle-etapa-tag-texto", baseState.etapa, "Etapa");
    setTextById("detalle-proyeccion-tag-texto", baseState.proyeccion, "Proyeccion");

    const descripcion = baseState.descripcion || crearDescripcionDetalle({ nombre: nombreFinal, ...baseState });
    setTextById("detalle-descripcion", descripcion, "Informacion de contexto de la linea tecnologica.");

    detalleActivo = baseState.active !== false;
    guardarRegistroDetalle(nombreFinal);
  }

  function guardarRegistroLinea(nombre, card) {
    if (!nombre || !card) {
      return;
    }

    const data = getStorageLineas();
    data[nombre] = construirRegistroLinea(nombre, card);
    setStorageLineas(data);
  }

  function eliminarRegistroLinea(nombre) {
    if (!nombre) {
      return;
    }

    const data = getStorageLineas();
    if (data[nombre]) {
      delete data[nombre];
      setStorageLineas(data);
    }
  }

  function guardarTodasLasLineas() {
    cards.forEach((card) => {
      const titleEl = card.querySelector("h3");
      const nombre = titleEl?.textContent.trim();
      if (nombre) {
        actualizarLinkDetalle(card, nombre);
        guardarRegistroLinea(nombre, card);
      }
    });
  }

  function crearCardLinea(nombre, state) {
    if (!cardsGrid) {
      return null;
    }

    const card = document.createElement("div");
    const estadoActivo = state.active !== false;
    const estadoTexto = estadoActivo ? "0 vigentes" : "0 vigentes";
    const chips = [state.tendencia, state.etapa].filter(Boolean).slice(0, 2);

    card.className = "tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3";
    card.innerHTML = `
      <div class="flex justify-between items-start">
        <div class="w-11 h-11 bg-sena-soft rounded-xl flex items-center justify-center">
          <svg class="w-5 h-5 text-sena-strong" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z" />
          </svg>
        </div>
        <span class="text-sm text-sena-text-soft">0 perfiles</span>
      </div>
      <h3 class="font-['Montserrat'] text-base font-semibold text-sena-text-main leading-snug"></h3>
      <p class="text-sm text-sena-text-soft">${estadoTexto} &middot; 1 linea</p>
      <div class="flex flex-wrap gap-2"></div>
      <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">Ver perfiles &rarr;</a>
    `;

    card.querySelector("h3").textContent = nombre;
    const chipsWrap = card.querySelector(".flex.flex-wrap.gap-2");

    chips.forEach((chipText) => {
      const chip = document.createElement("span");
      chip.className = "text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5";
      chip.textContent = chipText;
      chipsWrap.appendChild(chip);
    });

    cardsGrid.appendChild(card);
    cards.push(card);
    inicializarCard(card, cards.length - 1);
    actualizarLinkDetalle(card, nombre);
    return card;
  }

  function setSwitchState(switchBtn, isActive) {
    switchBtn.dataset.active = isActive ? "true" : "false";
    switchBtn.setAttribute("aria-pressed", isActive ? "true" : "false");
    switchBtn.classList.toggle("bg-sena", isActive);
    switchBtn.classList.toggle("bg-gray-300", !isActive);

    const thumb = switchBtn.querySelector("span");
    if (thumb) {
      // Support both legacy absolute thumb and inline flex thumb switches.
      if (thumb.classList.contains("absolute")) {
        const travel = Math.max(switchBtn.clientWidth - thumb.clientWidth - 4, 0);
        thumb.style.transform = isActive ? `translateX(${travel}px)` : "translateX(0)";
      } else {
        thumb.style.transform = "";
        switchBtn.classList.toggle("justify-end", isActive);
        switchBtn.classList.toggle("justify-start", !isActive);
      }
    }
  }

  function abrirModalEdicionLinea(card, titleEl) {
    const nombreActual = titleEl.textContent.trim();
    const estadoActual = estadoLineas[nombreActual] || {
      active: true,
      tendencia: tendenciasEmergentesDisponibles[0],
      etapa: etapasDisponibles[1],
      proyeccion: proyeccionesDisponibles[0],
    };

    lineaEnEdicion = { card, titleEl, oldName: nombreActual };

    modals.editInput.value = nombreActual;
    modals.editTendencia.value = estadoActual.tendencia;
    modals.editEtapa.value = estadoActual.etapa;
    modals.editProyeccion.value = estadoActual.proyeccion;
    abrirModal(modals.editModal);
  }

  function manejarToggleLinea(switchBtn, titleEl) {
    const isActive = switchBtn.dataset.active === "true";

    if (isActive) {
      lineaPendienteDeshabilitar = { switchBtn, nombre: titleEl.textContent.trim() };
      modals.disableText.textContent = `Estas seguro de deshabilitar "${lineaPendienteDeshabilitar.nombre}"? El registro no se eliminara, solo se marcara como inactivo.`;
      abrirModal(modals.disableModal);
      return;
    }

    lineaPendienteHabilitar = { switchBtn, nombre: titleEl.textContent.trim() };
    modals.enableText.textContent = `Estas seguro de que deseas habilitar la linea "${lineaPendienteHabilitar.nombre}"?`;
    abrirModal(modals.enableModal);
  }

  function cerrarAlertaFinal() {
    if (successTimeout) {
      clearTimeout(successTimeout);
      successTimeout = null;
    }

    if (successInterval) {
      clearInterval(successInterval);
      successInterval = null;
    }

    modals.successProgress.style.width = "0%";
    modals.successCounter.textContent = "3";
    cerrarModal(modals.successModal);
  }

  function mostrarAlertaFinal(config) {
    const { title, message, color = "#39A900", seconds = 3 } = config;

    modals.successTitle.textContent = title;
    modals.successText.textContent = message;
    modals.successText.style.color = color;
    modals.successIconWrap.style.backgroundColor = `${color}1A`;
    modals.successIcon.style.color = color;
    modals.successProgress.style.backgroundColor = color;

    abrirModal(modals.successModal);

    if (successTimeout) {
      clearTimeout(successTimeout);
    }
    if (successInterval) {
      clearInterval(successInterval);
    }

    let remaining = seconds;
    modals.successCounter.textContent = String(remaining);
    modals.successProgress.style.width = "0%";

    setTimeout(() => {
      modals.successProgress.style.width = "100%";
    }, 30);

    successInterval = setInterval(() => {
      remaining -= 1;
      modals.successCounter.textContent = String(Math.max(remaining, 0));
      if (remaining <= 0) {
        clearInterval(successInterval);
        successInterval = null;
      }
    }, 1000);

    successTimeout = setTimeout(() => {
      cerrarAlertaFinal();
    }, seconds * 1000);
  }

  function actualizarEstadoDetalleUI(isActive) {
    if (!btnDetalleDeshabilitar) {
      return;
    }

    detalleActivo = isActive;

    if (isActive) {
      btnDetalleDeshabilitar.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
        Desactivar
      `;
      btnDetalleDeshabilitar.className =
        "inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#e65100] rounded-lg bg-white text-[#e65100] hover:bg-[#e65100]/5 transition-colors";

      if (detalleBadgeEstado) {
        detalleBadgeEstado.className =
          "inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-sena/10 text-sena";
        detalleBadgeEstado.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4" />
          </svg>
          Vigente
        `;
      }

      if (detalleEstadoTitulo) {
        detalleEstadoTitulo.textContent = "Linea Vigente";
        detalleEstadoTitulo.className = "text-sm font-semibold text-sena";
      }

      if (detalleEstadoTexto) {
        detalleEstadoTexto.textContent = "Esta linea se encuentra activa";
        detalleEstadoTexto.className = "text-xs text-sena-text-soft";
      }
      return;
    }

    btnDetalleDeshabilitar.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
      Habilitar
    `;
    btnDetalleDeshabilitar.className =
      "inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border border-[#39A900] rounded-lg bg-white text-[#39A900] hover:bg-[#39A900]/5 transition-colors";

    if (detalleBadgeEstado) {
      detalleBadgeEstado.className =
        "inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-medium bg-[#FFF4E5] text-[#A05A00]";
      detalleBadgeEstado.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
          <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 9-6 6m0-6 6 6" />
        </svg>
        Deshabilitado
      `;
    }

    if (detalleEstadoTitulo) {
      detalleEstadoTitulo.textContent = "Linea Deshabilitada";
      detalleEstadoTitulo.className = "text-sm font-semibold text-[#A05A00]";
    }

    if (detalleEstadoTexto) {
      detalleEstadoTexto.textContent = "Esta linea se encuentra inactiva";
      detalleEstadoTexto.className = "text-xs text-sena-text-soft";
    }
  }

  function inicializarAccionesDetalle() {
    if (!btnDetalleEditar && !btnDetalleDeshabilitar) {
      return;
    }

    actualizarEstadoDetalleUI(detalleActivo);

    if (btnDetalleEditar) {
      btnDetalleEditar.addEventListener("click", () => {
        const nombreActual = (detalleTitulo?.textContent || "Linea Tecnologica").trim();
        const estadoActual = estadoLineas[nombreActual] || {
          active: detalleActivo,
          tendencia: tendenciasEmergentesDisponibles[0],
          etapa: etapasDisponibles[1],
          proyeccion: proyeccionesDisponibles[0],
        };

        lineaEnEdicion = { card: null, titleEl: detalleTitulo, oldName: nombreActual, isDetalle: true };

        modals.editInput.value = nombreActual;
        modals.editTendencia.value = estadoActual.tendencia;
        modals.editEtapa.value = estadoActual.etapa;
        modals.editProyeccion.value = estadoActual.proyeccion;
        abrirModal(modals.editModal);
      });
    }

    if (btnDetalleDeshabilitar) {
      btnDetalleDeshabilitar.addEventListener("click", () => {
        const nombreActual = (detalleTitulo?.textContent || "Linea Tecnologica").trim();

        if (detalleActivo) {
          lineaPendienteDeshabilitar = { switchBtn: null, nombre: nombreActual, isDetalle: true };
          modals.disableText.textContent = `Estas seguro de deshabilitar "${nombreActual}"? El registro no se eliminara, solo se marcara como inactivo.`;
          abrirModal(modals.disableModal);
          return;
        }

        lineaPendienteHabilitar = { switchBtn: null, nombre: nombreActual, isDetalle: true };
        modals.enableText.textContent = `Estas seguro de que deseas habilitar la linea "${nombreActual}"?`;
        abrirModal(modals.enableModal);
      });
    }
  }

  function inicializarCard(card, index) {
    if (card.querySelector(".linea-tec-controls")) {
      return;
    }

    const cardTopRow = card.querySelector(".flex.justify-between.items-start");
    const titleEl = card.querySelector("h3");
    const hasInlineActions =
      cardTopRow?.querySelector('button[aria-label="Editar linea"]') &&
      cardTopRow?.querySelector('button[aria-label="Cambiar estado"]');

    if (!cardTopRow || !titleEl) {
      return;
    }

    const nombreTecnologia = titleEl.textContent.trim() || `Linea ${index + 1}`;

    const estadoInicial = getDefaultStateForNombre(nombreTecnologia);
    estadoLineas[nombreTecnologia] = { ...estadoInicial };

    // If actions are already rendered in HTML, don't inject a second controls block.
    if (hasInlineActions) {
      const editarBtnInline = cardTopRow.querySelector('button[aria-label="Editar linea"]');
      const switchBtnInline = cardTopRow.querySelector('button[aria-label="Cambiar estado"]');

      if (switchBtnInline) {
        const isActive = estadoLineas[nombreTecnologia]?.active !== false;
        setSwitchState(switchBtnInline, isActive);
      }

      if (editarBtnInline && !editarBtnInline.dataset.bound) {
        editarBtnInline.addEventListener("click", () => abrirModalEdicionLinea(card, titleEl));
        editarBtnInline.dataset.bound = "true";
      }

      if (switchBtnInline && !switchBtnInline.dataset.bound) {
        switchBtnInline.addEventListener("click", () => manejarToggleLinea(switchBtnInline, titleEl));
        switchBtnInline.dataset.bound = "true";
      }

      actualizarLinkDetalle(card, nombreTecnologia);
      return;
    }

    const perfilCounter = cardTopRow.querySelector(":scope > span");

    if (!perfilCounter) {
      actualizarLinkDetalle(card, nombreTecnologia);
      return;
    }

    cardTopRow.classList.remove("items-start");
    cardTopRow.classList.add("items-center");

    const editarBtn = document.createElement("button");
    editarBtn.type = "button";
    editarBtn.className =
      "p-1.5 rounded-lg text-sena-text-soft hover:bg-sena-soft hover:text-sena transition-colors";
    editarBtn.setAttribute("aria-label", `Editar ${nombreTecnologia}`);
    editarBtn.title = "Editar";
    editarBtn.innerHTML =
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>';

    const switchEstadoBtn = document.createElement("button");
    switchEstadoBtn.type = "button";
    switchEstadoBtn.className =
      "linea-tec-switch relative w-9 h-5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-sena/20";
    switchEstadoBtn.setAttribute("aria-label", `Cambiar estado de ${nombreTecnologia}`);

    const switchThumb = document.createElement("span");
    switchThumb.className =
      "absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform";
    switchEstadoBtn.appendChild(switchThumb);
    setSwitchState(switchEstadoBtn, true);

    editarBtn.addEventListener("click", () => abrirModalEdicionLinea(card, titleEl));
    switchEstadoBtn.addEventListener("click", () => manejarToggleLinea(switchEstadoBtn, titleEl));

    const controlsContainer = document.createElement("div");
    controlsContainer.className = "linea-tec-controls flex items-center gap-1";
    controlsContainer.appendChild(editarBtn);
    controlsContainer.appendChild(switchEstadoBtn);
    controlsContainer.appendChild(perfilCounter);
    cardTopRow.appendChild(controlsContainer);

    actualizarLinkDetalle(card, nombreTecnologia);
  }

  cards.forEach(inicializarCard);
  guardarTodasLasLineas();
  inicializarDetalleDesdeStorage();
  inicializarAccionesDetalle();

  if (inputBuscarLinea) {
    inputBuscarLinea.addEventListener("input", aplicarFiltroBusqueda);
  }

  aplicarFiltroBusqueda();

  if (btnNuevaLinea) {
    btnNuevaLinea.addEventListener("click", () => {
      modals.createForm.reset();
      modals.createTendencia.value = "";
      modals.createEtapa.value = "";
      modals.createProyeccion.value = "";
      abrirModal(modals.createModal);
    });
  }

  modals.createClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.createModal));
  });

  modals.createForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const nombreNuevaLinea = modals.createInput.value.trim() || "Nueva Linea";
    const nuevaState = {
      active: true,
      tendencia: modals.createTendencia.value,
      etapa: modals.createEtapa.value,
      proyeccion: modals.createProyeccion.value,
    };

    estadoLineas[nombreNuevaLinea] = nuevaState;
    const nuevaCard = crearCardLinea(nombreNuevaLinea, nuevaState);
    if (nuevaCard) {
      guardarRegistroLinea(nombreNuevaLinea, nuevaCard);
      aplicarFiltroBusqueda();
    }

    cerrarModal(modals.createModal);
    mostrarAlertaFinal({
      title: "Linea Creada",
      message: `La linea \"${nombreNuevaLinea}\" fue creada correctamente.`,
      color: "#39A900",
      seconds: 3,
    });
  });

  modals.disableClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.disableModal));
  });

  modals.disableConfirm.addEventListener("click", () => {
    if (!lineaPendienteDeshabilitar) {
      return;
    }

    if (lineaPendienteDeshabilitar.switchBtn) {
      setSwitchState(lineaPendienteDeshabilitar.switchBtn, false);
    }

    if (lineaPendienteDeshabilitar.isDetalle) {
      actualizarEstadoDetalleUI(false);
      guardarRegistroDetalle(lineaPendienteDeshabilitar.nombre);
    }

    if (estadoLineas[lineaPendienteDeshabilitar.nombre]) {
      estadoLineas[lineaPendienteDeshabilitar.nombre].active = false;
    }

    const card = lineaPendienteDeshabilitar.switchBtn
      ? lineaPendienteDeshabilitar.switchBtn.closest("div.bg-white.rounded-xl")
      : null;
    if (card) {
      guardarRegistroLinea(lineaPendienteDeshabilitar.nombre, card);
    }

    cerrarModal(modals.disableModal);
    mostrarAlertaFinal({
      title: "Linea Deshabilitada",
      message: `La linea \"${lineaPendienteDeshabilitar.nombre}\" fue deshabilitada correctamente.`,
      color: "#39A900",
      seconds: 3,
    });
    lineaPendienteDeshabilitar = null;
  });

  modals.enableClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.enableModal));
  });

  modals.enableConfirm.addEventListener("click", () => {
    if (!lineaPendienteHabilitar) {
      return;
    }

    if (lineaPendienteHabilitar.switchBtn) {
      setSwitchState(lineaPendienteHabilitar.switchBtn, true);
    }

    if (lineaPendienteHabilitar.isDetalle) {
      actualizarEstadoDetalleUI(true);
      guardarRegistroDetalle(lineaPendienteHabilitar.nombre);
    }

    if (estadoLineas[lineaPendienteHabilitar.nombre]) {
      estadoLineas[lineaPendienteHabilitar.nombre].active = true;
    }

    const card = lineaPendienteHabilitar.switchBtn
      ? lineaPendienteHabilitar.switchBtn.closest("div.bg-white.rounded-xl")
      : null;
    if (card) {
      guardarRegistroLinea(lineaPendienteHabilitar.nombre, card);
    }

    cerrarModal(modals.enableModal);
    mostrarAlertaFinal({
      title: "Linea Habilitada",
      message: `La linea \"${lineaPendienteHabilitar.nombre}\" fue habilitada correctamente.`,
      color: "#39A900",
      seconds: 2,
    });
    lineaPendienteHabilitar = null;
  });

  modals.editClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.editModal));
  });

  modals.editForm.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!lineaEnEdicion) {
      return;
    }

    const nuevoNombre = modals.editInput.value.trim();
    if (!nuevoNombre) {
      return;
    }

    const oldState = estadoLineas[lineaEnEdicion.oldName] || {
      active: true,
      tendencia: tendenciasEmergentesDisponibles[0],
      etapa: etapasDisponibles[1],
      proyeccion: proyeccionesDisponibles[0],
    };
    delete estadoLineas[lineaEnEdicion.oldName];
    estadoLineas[nuevoNombre] = {
      active: oldState.active,
      tendencia: modals.editTendencia.value,
      etapa: modals.editEtapa.value,
      proyeccion: modals.editProyeccion.value,
    };

    lineaEnEdicion.titleEl.textContent = nuevoNombre;
    actualizarLinkDetalle(lineaEnEdicion.card, nuevoNombre);

    if (lineaEnEdicion.isDetalle) {
      estadoLineas[nuevoNombre].active = detalleActivo;
      guardarRegistroDetalle(nuevoNombre);
      inicializarDetalleDesdeStorage();
    }

    eliminarRegistroLinea(lineaEnEdicion.oldName);
    guardarRegistroLinea(nuevoNombre, lineaEnEdicion.card);
    cerrarModal(modals.editModal);
    mostrarAlertaFinal({
      title: "Linea Editada",
      message: `La linea \"${nuevoNombre}\" fue actualizada correctamente.`,
      color: "#00304D",
      seconds: 3,
    });
    lineaEnEdicion = null;
  });

  modals.successClose.forEach((btn) => {
    btn.addEventListener("click", () => {
      cerrarAlertaFinal();
    });
  });

  [modals.createModal, modals.disableModal, modals.enableModal, modals.editModal, modals.successModal].forEach((modal) => {
    modal.addEventListener("click", (event) => {
      if (event.target === modal) {
        if (modal === modals.successModal) {
          cerrarAlertaFinal();
          return;
        }

        cerrarModal(modal);
      }
    });
  });

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
      return;
    }

    if (!modals.successModal.classList.contains("hidden")) {
      cerrarAlertaFinal();
      return;
    }

    if (!modals.editModal.classList.contains("hidden")) {
      cerrarModal(modals.editModal);
      return;
    }

    if (!modals.createModal.classList.contains("hidden")) {
      cerrarModal(modals.createModal);
      return;
    }

    if (!modals.enableModal.classList.contains("hidden")) {
      cerrarModal(modals.enableModal);
      return;
    }

    if (!modals.disableModal.classList.contains("hidden")) {
      cerrarModal(modals.disableModal);
    }
  });

  function crearModales() {
    const wrapper = document.createElement("div");
    wrapper.innerHTML = `
      <div id="linea-modal-deshabilitar" class="linea-tec-modal hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
          <div class="w-full max-w-md rounded-xl border border-sena-border bg-white shadow-xl overflow-hidden">
            <div class="border-b border-sena-border px-6 py-4 flex items-center justify-between">
              <h3 class="text-lg font-semibold text-sena-text-main">Deshabilitar Linea Tecnologica</h3>
              <button type="button" class="linea-close-disable text-sena-text-soft hover:text-sena-text-main">&times;</button>
            </div>
            <div class="px-6 py-4">
              <p id="linea-disable-text" class="text-sm text-sena-text-soft leading-relaxed"></p>
            </div>
            <div class="border-t border-sena-border px-6 py-4 flex justify-end gap-3">
              <button type="button" class="linea-close-disable rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">Cancelar</button>
              <button type="button" id="linea-confirm-disable" class="rounded-lg bg-[#e09a3f] px-4 py-2 text-sm font-medium text-white hover:opacity-90">Desactivar</button>
            </div>
          </div>
        </div>
      </div>

      <div id="linea-modal-editar" class="linea-tec-modal hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
          <div class="w-full max-w-2xl rounded-xl border border-sena-border bg-white shadow-2xl overflow-hidden">
            <div class="border-b border-sena-border px-6 py-4 flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-sena-text-main">Editar Linea Tecnologica</h3>
                <p class="mt-1 text-sm text-sena-text-soft">Modifica el nombre de la linea tecnologica.</p>
              </div>
              <button type="button" class="linea-close-edit text-sena-text-soft hover:text-sena-text-main">&times;</button>
            </div>
            <form id="linea-edit-form" class="px-6 py-4">
              <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre</label>
                <input id="linea-edit-input" type="text" class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20" required>
              </div>

              <div class="flex flex-col gap-4 mb-5">
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-edit-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar etapa...</option>
                    ${etapasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Tendencias Tecnologicas Emergentes</label>
                  <select id="linea-edit-tendencia" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar tendencia tecnologica emergente...</option>
                    ${tendenciasEmergentesDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-edit-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar proyeccion...</option>
                    ${proyeccionesDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
              </div>

              <div class="border-t border-sena-border pt-4 flex justify-end gap-3">
                <button type="button" class="linea-close-edit rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">Cancelar</button>
                <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90">Guardar Cambios</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id="linea-modal-crear" class="linea-tec-modal hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
          <div class="w-full max-w-2xl rounded-xl border border-sena-border bg-white shadow-2xl overflow-hidden">
            <div class="border-b border-sena-border px-6 py-4 flex items-center justify-between">
              <div>
                <h3 class="text-lg font-semibold text-sena-text-main">Nueva Linea Tecnologica</h3>
                <p class="mt-1 text-sm text-sena-text-soft">Ingresa el nombre de la nueva linea tecnologica.</p>
              </div>
              <button type="button" class="linea-close-create text-sena-text-soft hover:text-sena-text-main">&times;</button>
            </div>
            <form id="linea-create-form" class="px-6 py-4">
              <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-sena-text-main">Nombre</label>
                <input id="linea-create-input" type="text" class="w-full rounded-lg border border-sena-border px-3 py-2 text-sm text-sena-text-main focus:border-sena focus:ring-2 focus:ring-sena/20" required>
              </div>

              <div class="flex flex-col gap-4 mb-5">
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-create-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar etapa...</option>
                    ${etapasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Tendencias Tecnologicas Emergentes</label>
                  <select id="linea-create-tendencia" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar tendencia tecnologica emergente...</option>
                    ${tendenciasEmergentesDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-create-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar proyeccion...</option>
                    ${proyeccionesDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
              </div>

              <div class="border-t border-sena-border pt-4 flex justify-end gap-3">
                <button type="button" class="linea-close-create rounded-lg border border-sena-border px-4 py-2 text-sm font-medium text-sena-text-main hover:bg-sena-soft">Cancelar</button>
                <button type="submit" class="rounded-lg bg-sena px-4 py-2 text-sm font-medium text-white hover:opacity-90">Crear Linea</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div id="linea-modal-habilitar" class="linea-tec-modal hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
          <div class="w-full max-w-md rounded-xl border border-sena-border bg-white shadow-2xl overflow-hidden">
            <div class="border-b border-sena-border px-6 py-5 flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M12 16v-4"></path>
                  <path d="M12 8h.01"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-sena-text-main">Habilitar linea?</h3>
                <p class="text-sm text-sena-text-soft">Esta accion activara la linea nuevamente</p>
              </div>
            </div>
            <div class="px-6 py-5">
              <p id="linea-enable-text" class="text-sm text-sena-text-soft leading-relaxed"></p>
              <p class="text-xs text-sena-text-soft mt-3 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                <span class="font-medium text-yellow-800">Informacion:</span> La linea volvera a estar visible y disponible para su uso.
              </p>
            </div>
            <div class="border-t border-sena-border px-6 py-4 flex justify-end gap-3">
              <button type="button" class="linea-close-enable px-4 py-2 text-sm font-medium text-sena-text-main border border-sena-border rounded-lg hover:bg-sena-soft transition-colors">Cancelar</button>
              <button type="button" id="linea-confirm-enable" class="px-4 py-2 text-sm font-medium text-white bg-[#39A900] rounded-lg hover:bg-green-700 transition-colors">Si, habilitar linea</button>
            </div>
          </div>
        </div>
      </div>

      <div id="linea-modal-success" class="linea-tec-modal hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
          <div class="w-full max-w-md rounded-xl border border-sena-border bg-white shadow-2xl overflow-hidden">
            <div class="border-b border-sena-border px-6 py-4 flex items-center gap-3">
              <div id="linea-success-icon-wrap" class="w-10 h-10 rounded-full flex items-center justify-center">
                <svg id="linea-success-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
              <div>
                <h3 id="linea-success-title" class="text-lg font-semibold text-sena-text-main">Accion completada</h3>
                <p class="text-xs text-sena-text-soft">Operacion realizada correctamente</p>
              </div>
              <button type="button" class="linea-close-success ml-auto text-sena-text-soft hover:text-sena-text-main">&times;</button>
            </div>
            <div class="px-6 py-4">
              <p id="linea-success-text" class="text-sm text-sena-text-soft"></p>
              <div class="mt-3 flex items-center gap-2 text-xs text-sena-text-soft">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Esta ventana se cerrara automaticamente en <span id="linea-success-counter">3</span> segundos</span>
              </div>
            </div>
            <div class="h-1.5 bg-gray-100 w-full overflow-hidden">
              <div id="linea-success-progress" class="h-full transition-all duration-[3000ms] ease-linear" style="width:0%"></div>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.appendChild(wrapper);

    return {
      disableModal: document.getElementById("linea-modal-deshabilitar"),
      disableText: document.getElementById("linea-disable-text"),
      disableConfirm: document.getElementById("linea-confirm-disable"),
      disableClose: document.querySelectorAll(".linea-close-disable"),

      enableModal: document.getElementById("linea-modal-habilitar"),
      enableText: document.getElementById("linea-enable-text"),
      enableConfirm: document.getElementById("linea-confirm-enable"),
      enableClose: document.querySelectorAll(".linea-close-enable"),

      createModal: document.getElementById("linea-modal-crear"),
      createForm: document.getElementById("linea-create-form"),
      createInput: document.getElementById("linea-create-input"),
      createTendencia: document.getElementById("linea-create-tendencia"),
      createEtapa: document.getElementById("linea-create-etapa"),
      createProyeccion: document.getElementById("linea-create-proyeccion"),
      createClose: document.querySelectorAll(".linea-close-create"),

      editModal: document.getElementById("linea-modal-editar"),
      editForm: document.getElementById("linea-edit-form"),
      editInput: document.getElementById("linea-edit-input"),
      editTendencia: document.getElementById("linea-edit-tendencia"),
      editEtapa: document.getElementById("linea-edit-etapa"),
      editProyeccion: document.getElementById("linea-edit-proyeccion"),
      editClose: document.querySelectorAll(".linea-close-edit"),

      successModal: document.getElementById("linea-modal-success"),
      successTitle: document.getElementById("linea-success-title"),
      successIconWrap: document.getElementById("linea-success-icon-wrap"),
      successIcon: document.getElementById("linea-success-icon"),
      successCounter: document.getElementById("linea-success-counter"),
      successProgress: document.getElementById("linea-success-progress"),
      successText: document.getElementById("linea-success-text"),
      successClose: document.querySelectorAll(".linea-close-success"),
    };
  }
});
