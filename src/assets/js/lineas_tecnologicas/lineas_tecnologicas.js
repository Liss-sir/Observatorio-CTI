
document.addEventListener("DOMContentLoaded", () => {
  const cardSelector = "div.tarjeta-tecnologia, div.bg-white.rounded-xl";
  const cardsGrid = document.getElementById("lineas-cards-grid") || document.querySelector("main .grid");
  let cards = cardsGrid ? Array.from(cardsGrid.querySelectorAll(`:scope > ${cardSelector}`)) : [];
  const inputBuscarLinea = document.getElementById("input-buscar-linea");
  const lineasEncontradasCount = document.getElementById("lineas-encontradas-count");
  const lineasEncontradasLabel = document.getElementById("lineas-encontradas-label");
  const btnNuevaLinea = document.getElementById("btn-nueva-linea");
  const emptyState = document.getElementById("linea-empty-state");
  const searchEmptyState = document.getElementById("linea-search-empty-state");
  const searchEmptyTerm = document.getElementById("linea-search-empty-term");
  const btnCrearDesdeEmpty = document.getElementById("btn-crear-desde-empty");
  const btnDetalleEditar = document.getElementById("btn-detalle-editar");
  const btnDetalleDeshabilitar = document.getElementById("btn-detalle-deshabilitar");
  const detalleTitulo = document.getElementById("detalle-linea-titulo");
  const detalleBadgeEstado = document.getElementById("detalle-badge-estado");
  const detalleEstadoTitulo = document.getElementById("detalle-estado-titulo");
  const detalleEstadoTexto = document.getElementById("detalle-estado-texto");
  const STORAGE_KEY = "observatorio_lineas_tecnologicas_v1";
  const isListadoView = Boolean(inputBuscarLinea && cardsGrid);

  const areasDisponibles = [];
  const programasFormacionDisponibles = [];
  const tendenciasEmergentesDisponibles = [];
  const etapasDisponibles = [];
  const proyeccionesDisponibles = [];
  let isCargandoLineas = false;

  const estadoLineas = {};
  let lineaEnEdicion = null;
  let lineaPendienteDeshabilitar = null;
  let lineaPendienteHabilitar = null;
  let successTimeout = null;
  let successInterval = null;
  let detalleActivo = true;

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

  const API_ROUTES = {
    lineas: "../../controllers/LineaTecController.php",
    areas: "../../controllers/AreaController.php",
    programas: "../../controllers/ProgramaFormacionController.php",
    etapas: "../../controllers/EtapaDesarrolloController.php",
    tendencias: "../../controllers/TendenciaEmergenteController.php",
    proyecciones: "../../controllers/ProyeccionFuturoController.php",
  };

  function buildApiUrl(base, params = {}) {
    const url = new URL(base, window.location.href);
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== null && String(value).trim() !== "") {
        url.searchParams.set(key, String(value));
      }
    });
    return url.toString();
  }

  // Acepta controladores que responden con success (bool) o status (string).
  function esRespuestaExitosa(payload) {
    if (!payload || typeof payload !== "object") {
      return false;
    }

    if (typeof payload.success === "boolean") {
      return payload.success;
    }

    if (typeof payload.status === "string") {
      return payload.status.toLowerCase() === "success";
    }

    return true;
  }

  // Wrapper central para fetch JSON con validacion de errores funcionales del API.
  async function fetchJson(base, params = {}, options = {}) {
    const response = await fetch(buildApiUrl(base, params), options);
    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }
    const payload = await response.json();

    if (!esRespuestaExitosa(payload)) {
      const mensaje = String(payload?.error || payload?.message || "Error en respuesta del backend").trim();
      throw new Error(mensaje || "Error en respuesta del backend");
    }

    return payload;
  }

  function extraerValorPorClaves(item, keys = []) {
    for (const key of keys) {
      if (Object.prototype.hasOwnProperty.call(item, key)) {
        const value = String(item[key] ?? "").trim();
        if (value) {
          return value;
        }
      }
    }
    return "";
  }

  function extraerIdDinamico(item) {
    const key = Object.keys(item || {}).find((k) => /^id(_|[A-Z])/.test(k) || k.toLowerCase().startsWith("id_"));
    if (!key) {
      return "";
    }
    return String(item[key] ?? "").trim();
  }

  function extraerLabelDinamico(item) {
    const posibles = Object.keys(item || {}).filter((k) => {
      const lower = k.toLowerCase();
      return lower === "nombre" || lower.startsWith("nombre_") || lower.includes("titulo") || lower.includes("descripcion");
    });

    for (const key of posibles) {
      const value = String(item[key] ?? "").trim();
      if (value) {
        return value;
      }
    }

    return "";
  }

  // Normaliza catálogos heterogéneos a un formato único: [{ value, label }].
  function toOptionList(payload, idKeys = [], labelKeys = []) {
    const source = Array.isArray(payload?.data) ? payload.data : [];
    return source
      .map((item) => {
        const value = (
          extraerValorPorClaves(item, [...idKeys, "id", "value"]) ||
          extraerIdDinamico(item)
        ).trim();

        const label = (
          extraerValorPorClaves(item, [...labelKeys, "text", "label", "nombre"]) ||
          extraerLabelDinamico(item)
        ).trim();

        if (!value || !label) {
          return null;
        }
        return { value, label };
      })
      .filter(Boolean);
  }

  function normalizarLineaDesdeBackend(row) {
    const idLinea = String(row?.id_linea ?? "").trim();
    const nombreLinea = String(row?.nombre_linea ?? "").trim();
    const nombrePrograma = String(row?.nombre_programa ?? "").trim();
    const nombreTendencia = String(row?.nombre_tendencia ?? "").trim();
    const nombreEtapa = String(row?.nombre_etapa ?? "").trim();
    const nombreArea = String(row?.nombre_area ?? "").trim();
    const anioProyeccion = String(row?.anio_proyeccion ?? row?.anio ?? "").trim();
    const descripcionProyeccion = String(
      row?.descripcion_proyeccion ?? row?.nombre_proyeccion ?? row?.nombre_proyeccion_futuro ?? row?.nombre ?? ""
    ).trim();

    const nombre =
      nombreLinea
      || nombrePrograma
      || nombreTendencia
      || (idLinea ? `Linea ${idLinea}` : "Linea Tecnologica");

    const proyeccionTexto = descripcionProyeccion && anioProyeccion
      ? `${descripcionProyeccion}: ${anioProyeccion}`
      : (descripcionProyeccion || anioProyeccion);

    return {
      idLinea,
      nombre,
      active: Number(row?.estado ?? 1) === 1,
      idArea: String(row?.id_area ?? ""),
      area: nombreArea,
      idPrograma: String(row?.id_programa ?? ""),
      programaFormacion: nombrePrograma,
      idEtapa: String(row?.id_etapa ?? ""),
      etapa: nombreEtapa,
      idTendencia: String(row?.id_tendencia ?? ""),
      tendencia: nombreTendencia,
      idProyeccion: String(row?.id_proyeccion ?? ""),
      proyeccion: anioProyeccion,
      proyecciones: proyeccionTexto ? [proyeccionTexto] : [],
      tecnologiasEmergentes: nombreTendencia ? [nombreTendencia] : [],
      fechaActualizacion: row?.fecha_creacion || null,
    };
  }

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

    values.forEach((entry) => {
      const optionConfig = typeof entry === "object" && entry !== null
        ? entry
        : { value: String(entry || ""), label: String(entry || "") };
      const optionValue = String(
        optionConfig.value || optionConfig.anio || optionConfig.anios || optionConfig.label || ""
      ).trim();
      const optionLabel = String(optionConfig.label || optionValue).trim();

      if (!optionValue || !optionLabel) {
        return;
      }

      const option = document.createElement("option");
      option.value = optionValue;
      option.textContent = optionLabel;
      select.appendChild(option);
    });
  }

  function getOptionValue(entry) {
    if (entry && typeof entry === "object") {
      return String(entry.value || entry.id || entry.label || "").trim();
    }
    return String(entry || "").trim();
  }

  function getOptionLabelByValue(options, value) {
    const objetivo = String(value || "").trim();
    if (!objetivo || !Array.isArray(options)) {
      return "";
    }

    const found = options.find((item) => String(item?.value || item?.id || "").trim() === objetivo);
    return String(found?.label || "").trim();
  }

  function normalizarNombreArea(area, idArea = "") {
    const desdeId = getOptionLabelByValue(areasDisponibles, idArea || area);
    if (desdeId) {
      return desdeId;
    }

    const areaTexto = String(area || "").trim();
    if (!areaTexto || /^\d+$/.test(areaTexto)) {
      return "";
    }
    return areaTexto;
  }
  function normalizarProyeccionTexto(proyeccion, idProyeccion = "") {
    const desdeId = getOptionLabelByValue(proyeccionesDisponibles, idProyeccion || proyeccion);
    if (desdeId) {
      return desdeId;
    }

    const texto = String(proyeccion || "").trim();
    if (!texto) {
      return "";
    }
    if (/^\d+$/.test(texto)) {
      return formatAnioProyeccion(texto);
    }
    return texto;
  }

  function formatAnioProyeccion(value) {
    const raw = String(value || "").trim();
    if (!raw) {
      return "";
    }

    if (/^\d+$/.test(raw)) {
      const numero = Number.parseInt(raw, 10);
      if (Number.isFinite(numero) && numero > 0) {
        return `${numero} ${numero === 1 ? "año" : "años"}`;
      }
    }

    return raw;
  }

  function reemplazarCatalogo(destino, opciones) {
    destino.length = 0;
    opciones.forEach((item) => destino.push(item));
  }

  function limpiarEstadoLineas() {
    Object.keys(estadoLineas).forEach((key) => delete estadoLineas[key]);
  }

  function extraerNombreLineaDesdeTextoSelect(texto) {
    const raw = String(texto || "").trim();
    if (!raw) {
      return "Linea Tecnologica";
    }

    const partes = raw.split(" - ").map((item) => item.trim()).filter(Boolean);
    if (partes.length >= 3) {
      const nombrePrograma = partes.slice(2).join(" - ").replace(/\s*\([^)]*\)\s*$/, "").trim();
      return nombrePrograma || raw;
    }

    if (partes.length >= 2) {
      return partes[1] || raw;
    }

    return raw;
  }

  async function cargarLineasDesdeBackendSelectFallback() {
    const payload = await fetchJson(API_ROUTES.lineas, { accion: "paraSelect" });
    const opciones = toOptionList(payload);

    cardsGrid.innerHTML = "";
    cards = [];
    limpiarEstadoLineas();

    opciones.forEach((opcion) => {
      const nombre = extraerNombreLineaDesdeTextoSelect(opcion.label);
      const state = {
        active: true,
        area: "",
        programaFormacion: opcion.label,
        tendencia: "",
        etapa: "",
        proyeccion: "",
        idLinea: opcion.value,
        idArea: "",
        idPrograma: "",
        idEtapa: "",
        idTendencia: "",
        idProyeccion: "",
      };

      estadoLineas[nombre] = state;
      const card = crearCardLinea(nombre, state);
      if (card) {
        guardarRegistroLinea(nombre, card);
      }
    });

    aplicarFiltroBusqueda();
    return opciones.length > 0;
  }

  function renderLoadingLineas() {
    if (!cardsGrid) {
      return;
    }

    isCargandoLineas = true;
    cardsGrid.innerHTML = `
      <div class="col-span-full text-center py-10 text-gray-500" id="lineas-loading-state">
        <svg class="w-8 h-8 mx-auto mb-2 animate-spin text-sena" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.93 4.93a10 10 0 1 1-1.42 1.42" />
        </svg>
        <p>Cargando lineas...</p>
      </div>
    `;
    cards = [];
    actualizarEmptyState();
  }

  function finalizarLoadingLineas() {
    isCargandoLineas = false;
    actualizarEmptyState();
  }

  async function cargarProgramasPorArea(idArea, targetSelect, selectedValue = "") {
    if (!targetSelect) {
      return;
    }

    if (!idArea) {
      fillSelect(targetSelect, [], "Seleccionar programa de formacion...");
      setSelectEnabled(targetSelect, false);
      return;
    }

    setSelectEnabled(targetSelect, true);

    try {
      const payload = await fetchJson(API_ROUTES.programas, {
        accion: "paraSelectPorArea",
        id_area: idArea,
      });

      const opciones = toOptionList(
        payload,
        ["id_programa", "idPrograma"],
        ["nombre_programa", "nombrePrograma", "text"]
      );
      fillSelect(targetSelect, opciones, "Seleccionar programa de formacion...");

      if (selectedValue) {
        targetSelect.value = String(selectedValue);
      }
    } catch (error) {
      fillSelect(targetSelect, [], "Seleccionar programa de formacion...");
      setSelectEnabled(targetSelect, false);
    }
  }

  async function cargarCatalogosDesdeBackend() {
    try {
      const [areasPayload, etapasPayload, tendenciasPayload, proyeccionesPayload] = await Promise.all([
        fetchJson(API_ROUTES.areas, { accion: "paraSelect" }),
        fetchJson(API_ROUTES.etapas, { accion: "paraSelect" }),
        fetchJson(API_ROUTES.tendencias, { accion: "paraSelect" }),
        fetchJson(API_ROUTES.proyecciones, { accion: "paraSelect" }),
      ]);

      reemplazarCatalogo(
        areasDisponibles,
        toOptionList(areasPayload, ["id_area", "idArea"], ["nombre_area", "nombreArea", "text"])
      );
      reemplazarCatalogo(
        etapasDisponibles,
        toOptionList(etapasPayload, ["id_etapa", "idEtapa"], ["nombre_etapa", "nombreEtapa", "nombre", "text"])
      );
      reemplazarCatalogo(
        tendenciasEmergentesDisponibles,
        toOptionList(tendenciasPayload, ["id_tendencia", "idTendencia"], ["nombre_tendencia", "nombreTendencia", "nombre", "text"])
      );
      reemplazarCatalogo(
        proyeccionesDisponibles,
        toOptionList(proyeccionesPayload, ["id_proyeccion", "idProyeccion"], ["nombre_proyeccion", "nombreProyeccion", "nombre", "anio", "text"])
      );

      fillSelect(modals.createArea, areasDisponibles, "Seleccionar area...");
      fillSelect(modals.createTendencia, tendenciasEmergentesDisponibles, "Seleccionar tendencia tecnologica emergente...");
      fillSelect(modals.createEtapa, etapasDisponibles, "Seleccionar etapa...");
      fillSelect(modals.createProyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");

      fillSelect(modals.editArea, areasDisponibles, "Seleccionar area...");
      fillSelect(modals.editTendencia, tendenciasEmergentesDisponibles, "Seleccionar tendencia tecnologica emergente...");
      fillSelect(modals.editEtapa, etapasDisponibles, "Seleccionar etapa...");
      fillSelect(modals.editProyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");

      setSelectEnabled(modals.createPrograma, false);
      setSelectEnabled(modals.editPrograma, false);
      fillSelect(modals.createPrograma, [], "Seleccionar programa de formacion...");
      fillSelect(modals.editPrograma, [], "Seleccionar programa de formacion...");
    } catch (error) {
      // Keep local fallback catalogs when API is unavailable.
    }
  }

  async function cargarLineasDesdeBackend() {
    if (!isListadoView || !cardsGrid) {
      return;
    }

    renderLoadingLineas();

    try {
      const payload = await fetchJson(API_ROUTES.lineas, { accion: "listarTodas" });
      if (!Array.isArray(payload?.data)) {
        throw new Error("Respuesta invalida en listado de lineas");
      }

      const rows = payload.data;

      cardsGrid.innerHTML = "";
      cards = [];
      limpiarEstadoLineas();

      if (rows.length === 0) {
        const cargadasDesdeSelect = await cargarLineasDesdeBackendSelectFallback();
        if (cargadasDesdeSelect) {
          return;
        }
        aplicarFiltroBusqueda();
        return;
      }

      rows.forEach((row) => {
        const linea = normalizarLineaDesdeBackend(row);
        const state = {
          active: linea.active,
          area: linea.area,
          programaFormacion: linea.programaFormacion,
          tendencia: linea.tendencia,
          etapa: linea.etapa,
          proyeccion: linea.proyecciones?.[0] || linea.proyeccion || normalizarProyeccionTexto("", linea.idProyeccion),
          idLinea: linea.idLinea,
          idArea: linea.idArea,
          idPrograma: linea.idPrograma,
          idEtapa: linea.idEtapa,
          idTendencia: linea.idTendencia,
          idProyeccion: linea.idProyeccion,
        };

        estadoLineas[linea.nombre] = state;
        const card = crearCardLinea(linea.nombre, state);
        if (card) {
          guardarRegistroLinea(linea.nombre, card);
        }
      });

      aplicarFiltroBusqueda();
    } catch (error) {
      try {
        const cargadasDesdeSelect = await cargarLineasDesdeBackendSelectFallback();
        if (!cargadasDesdeSelect) {
          cardsGrid.innerHTML = "";
          cards = [];
          limpiarEstadoLineas();
          aplicarFiltroBusqueda();
        }
      } catch (fallbackError) {
        cardsGrid.innerHTML = "";
        cards = [];
        limpiarEstadoLineas();
        aplicarFiltroBusqueda();
      }
    } finally {
      finalizarLoadingLineas();
    }
  }

  function agregarUnico(collection, value) {
    const text = String(value || "").trim();
    if (!text) {
      return;
    }

    const exists = collection.some((item) => normalizarTexto(item) === normalizarTexto(text));
    if (!exists) {
      collection.push(text);
    }
  }

  function agregarProyeccionUnica(value, label) {
    const optionValue = String(value || "").trim();
    const optionLabel = String(label || optionValue).trim();
    if (!optionValue || !optionLabel) {
      return;
    }

    const exists = proyeccionesDisponibles.some((item) => normalizarTexto(item.value) === normalizarTexto(optionValue));
    if (!exists) {
      proyeccionesDisponibles.push({ value: optionValue, label: optionLabel });
    }
  }

  function extraerProyeccionDesdeTexto(texto) {
    const raw = String(texto || "").trim();
    if (!raw) {
      return null;
    }

    const parts = raw.split(":");
    if (parts.length < 2) {
      return { value: raw, label: raw };
    }

    const value = parts.slice(1).join(":").trim();
    const label = raw;
    return value ? { value, label } : { value: raw, label: raw };
  }

  function obtenerProgramaFormacion(registro) {
    return String(
      (registro && (registro.programaFormacion || registro.programa_formacion || registro.programa)) || ""
    ).trim();
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

  function parseCardArea(card) {
    const chips = parseCardTecnologias(card);
    return chips.length > 0 ? chips[0] : "";
  }

  function hidratarCatalogosIniciales() {
    const data = getStorageLineas();

    Object.values(data).forEach((registro) => {
      if (!registro || typeof registro !== "object") {
        return;
      }

      agregarUnico(areasDisponibles, registro.area);
      agregarUnico(programasFormacionDisponibles, obtenerProgramaFormacion(registro));
      agregarUnico(tendenciasEmergentesDisponibles, registro.tendencia);
      agregarUnico(etapasDisponibles, registro.etapa);

      const proyeccionSimple = extraerProyeccionDesdeTexto(registro.proyeccion);
      if (proyeccionSimple) {
        agregarProyeccionUnica(proyeccionSimple.value, proyeccionSimple.label);
      }

      if (Array.isArray(registro.proyecciones)) {
        registro.proyecciones.forEach((item) => {
          if (typeof item === "string") {
            const parsed = extraerProyeccionDesdeTexto(item);
            if (parsed) {
              agregarProyeccionUnica(parsed.value, parsed.label);
            }
            return;
          }

          if (item && typeof item === "object") {
            const anio = String(item.anio || item.anios || "").trim();
            const nombre = String(item.nombre || item.titulo || "").trim();
            if (anio && nombre) {
              agregarProyeccionUnica(anio, `${nombre}: ${anio}`);
              return;
            }
            if (anio) {
              agregarProyeccionUnica(anio, anio);
            }
          }
        });
      }
    });

    cards.forEach((card) => {
      const chips = parseCardTecnologias(card);
      if (chips[0]) {
        agregarUnico(areasDisponibles, chips[0]);
      }
      if (chips[1]) {
        agregarUnico(tendenciasEmergentesDisponibles, chips[1]);
      }
    });
  }

  hidratarCatalogosIniciales();

  fillSelect(modals.createArea, areasDisponibles, "Seleccionar area...");
  fillSelect(modals.createPrograma, programasFormacionDisponibles, "Seleccionar programa de formacion...");
  fillSelect(modals.createTendencia, tendenciasEmergentesDisponibles, "Seleccionar tendencia tecnologica emergente...");
  fillSelect(modals.createEtapa, etapasDisponibles, "Seleccionar etapa...");
  fillSelect(modals.createProyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");

  fillSelect(modals.editArea, areasDisponibles, "Seleccionar area...");
  fillSelect(modals.editPrograma, programasFormacionDisponibles, "Seleccionar programa de formacion...");
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

  function mostrarToastValidacion(mensaje, tipo = "warning") {
    const toastContainer = document.getElementById("toast-container");

    if (!toastContainer) {
      const container = document.createElement("div");
      container.id = "toast-container";
      container.className = "fixed top-4 right-4 z-[99999] flex flex-col gap-3 pointer-events-none";
      document.body.appendChild(container);
    }

    const container = document.getElementById("toast-container");

    const titulo = tipo === "warning"
      ? "Campo requerido"
      : tipo === "error"
        ? "Error"
        : tipo === "info"
          ? "Información"
          : "Éxito";

    const toastId = `toast-${Date.now()}-${Math.random().toString(36).slice(2, 11)}`;
    const toast = document.createElement("div");
    toast.id = toastId;
    toast.className = `toast-validation ${tipo}`;

    const iconos = {
      info: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
      warning: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/></svg>`,
      error: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
      success: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
    };

    toast.innerHTML = `
      <div class="toast-contenido">
        <div class="toast-icono-wrapper">
          <div class="toast-icono">${iconos[tipo] || iconos.warning}</div>
        </div>
        <div class="toast-mensaje-wrapper">
          <div class="toast-titulo">${titulo}</div>
          <div class="toast-mensaje">${mensaje}</div>
        </div>
      </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
      const toastElement = document.getElementById(toastId);
      if (toastElement) {
        toastElement.classList.add("exit");
        setTimeout(() => {
          if (toastElement.parentNode) {
            toastElement.remove();
          }
        }, 200);
      }
    }, 3000);
  }

  function existeLineaConMismoNombre(nombre) {
    const objetivo = normalizarTexto(nombre);
    if (!objetivo) {
      return false;
    }

    return Object.keys(estadoLineas).some((nombreExistente) => normalizarTexto(nombreExistente) === objetivo);
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
    actualizarNoResultadosBusqueda(termino, visibles);
    actualizarEmptyState();
  }

  function actualizarNoResultadosBusqueda(terminoNormalizado, visibles) {
    if (!searchEmptyState) {
      return;
    }

    if (isCargandoLineas) {
      searchEmptyState.classList.add("hidden");
      return;
    }

    const terminoRaw = String(inputBuscarLinea?.value || "").trim();
    const hayBusquedaActiva = terminoNormalizado.length > 0;
    const totalCards = cards.length;
    const mostrar = hayBusquedaActiva && totalCards > 0 && visibles === 0;

    if (mostrar) {
      if (searchEmptyTerm) {
        searchEmptyTerm.textContent = terminoRaw;
      }
      searchEmptyState.classList.remove("hidden");
      return;
    }

    searchEmptyState.classList.add("hidden");
  }

  function actualizarEmptyState() {
    if (isCargandoLineas) {
      if (emptyState) {
        emptyState.classList.add("hidden");
      }
      if (searchEmptyState) {
        searchEmptyState.classList.add("hidden");
      }
      return;
    }

    if (searchEmptyState && !searchEmptyState.classList.contains("hidden")) {
      if (emptyState) {
        emptyState.classList.add("hidden");
      }
      return;
    }

    const totalCards = cardsGrid ? cardsGrid.querySelectorAll(`:scope > ${cardSelector}`).length : 0;
    if (emptyState) {
      if (totalCards === 0) {
        emptyState.classList.remove("hidden");
      } else {
        emptyState.classList.add("hidden");
      }
    }
  }

  function getDetalleDataForNombre(nombre) {
    const registro = findRegistroByNombre(getStorageLineas(), nombre);
    if (!registro) {
      return {
        area: "",
        programaFormacion: "",
        tendenciaActual: "",
        proyecciones: [],
        tecnologiasEmergentes: [],
      };
    }

    const proyecciones = Array.isArray(registro.proyecciones) && registro.proyecciones.length > 0
      ? registro.proyecciones
      : registro.proyeccion
        ? [registro.proyeccion]
        : [];

    const tecnologiasEmergentes = Array.isArray(registro.tecnologiasEmergentes) && registro.tecnologiasEmergentes.length > 0
      ? registro.tecnologiasEmergentes
      : Array.isArray(registro.tecnologias)
        ? registro.tecnologias
        : [];

    return {
      area: normalizarNombreArea(registro.area, registro.idArea),
      programaFormacion: obtenerProgramaFormacion(registro),
      tendenciaActual: String(registro.tendencia || "").trim(),
      proyecciones,
      tecnologiasEmergentes,
    };
  }

  function mapProyeccionesToText(proyecciones, fallbackAnios = "") {
    if (!Array.isArray(proyecciones)) {
      return [];
    }

    const mapped = proyecciones
      .map((item) => {
        if (typeof item === "string") {
          return normalizarProyeccionTexto(item);
        }

        if (item && typeof item === "object") {
          const anio = formatAnioProyeccion(item.anio || item.anios || fallbackAnios || "");
          const nombre = String(item.nombre || item.titulo || "").trim();

          if (nombre && anio) {
            return `${nombre}: ${anio}`;
          }
          return normalizarProyeccionTexto(nombre || anio);
        }

        return "";
      })
      .filter(Boolean);

    if (mapped.length > 0) {
      return mapped;
    }

    const anios = formatAnioProyeccion(fallbackAnios || "");
    if (!anios) {
      return [];
    }

    return [anios];
  }

  function actualizarLinkDetalle(card, nombre) {
    if (!card) {
      return;
    }

    const link = card.querySelector("a");
    if (!link) {
      return;
    }

    const idLinea = String(card?.dataset?.idLinea || "").trim();
    if (idLinea) {
      link.href = `detalles_lineas_tecnologicas.php?id_linea=${encodeURIComponent(idLinea)}&linea=${encodeURIComponent(nombre)}`;
      return;
    }
    link.href = `detalles_lineas_tecnologicas.php?linea=${encodeURIComponent(nombre)}`;
  }

  function construirRegistroLinea(nombre, card) {
    const state = estadoLineas[nombre] || {};
    const resumen = parseCardResumen(card);
    const detalle = getDetalleDataForNombre(nombre);
    const area = state.area || detalle.area || parseCardArea(card) || "";
    const programaFormacion = state.programaFormacion || detalle.programaFormacion || "";
    const tendencia = state.tendencia || detalle.tendenciaActual || "";
    const proyeccionesTexto = mapProyeccionesToText(detalle.proyecciones, state.proyeccion);
    const tecnologias = Array.isArray(detalle.tecnologiasEmergentes)
      ? detalle.tecnologiasEmergentes.filter(Boolean)
      : [tendencia].filter(Boolean);

    return {
      nombre,
      active: state.active !== false,
      idLinea: String(state.idLinea || card?.dataset?.idLinea || "").trim(),
      idArea: String(state.idArea || card?.dataset?.idArea || "").trim(),
      idPrograma: String(state.idPrograma || card?.dataset?.idPrograma || "").trim(),
      idEtapa: String(state.idEtapa || card?.dataset?.idEtapa || "").trim(),
      idTendencia: String(state.idTendencia || card?.dataset?.idTendencia || "").trim(),
      idProyeccion: String(state.idProyeccion || card?.dataset?.idProyeccion || "").trim(),
      area,
      programaFormacion,
      tendencia,
      etapa: state.etapa || "",
      proyeccion: normalizarProyeccionTexto(state.proyeccion, state.idProyeccion || card?.dataset?.idProyeccion || ""),
      proyecciones: proyeccionesTexto,
      tecnologiasEmergentes: tecnologias,
      perfiles: parseCardPerfiles(card),
      vigentes: resumen.vigentes,
      totalLineas: resumen.totalLineas,
      tecnologias: parseCardTecnologias(card),
      fechaActualizacion: new Date().toISOString(),
    };
  }

  function getDefaultStateForNombre(nombre) {
    const detalle = getDetalleDataForNombre(nombre);

    return {
      active: true,
      area: detalle.area || getOptionValue(areasDisponibles[0]) || "",
      programaFormacion: detalle.programaFormacion || getOptionValue(programasFormacionDisponibles[0]) || "",
      tendencia: detalle.tendenciaActual || getOptionValue(tendenciasEmergentesDisponibles[0]) || "",
      etapa: getOptionValue(etapasDisponibles[0]) || "",
      proyeccion: String(proyeccionesDisponibles[0]?.label || "").trim(),
    };
  }

  function getLineaFromQuery() {
    try {
      const params = new URLSearchParams(window.location.search);
      return (params.get("linea") || "").trim();
    } catch (error) {
      return "";
    }
  }

  function getLineaIdFromQuery() {
    try {
      const params = new URLSearchParams(window.location.search);
      return (params.get("id_linea") || "").trim();
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
    el.textContent = hasValue ? normalizarTextoVisible(value.trim()) : fallback;
  }

  function normalizarTextoVisible(value) {
    const text = String(value || "").trim();
    if (!text) {
      return "";
    }

    return text
      .replace(/\bMas\b/gi, "Más")
      .replace(/\b([Aa])nios\b/g, "$1ños")
      .replace(/\b([Aa])nio\b/g, "$1ño")
      .replace(/\b([Aa])nos\b/g, "$1ños")
      .replace(/\b([Aa])no\b/g, "$1ño");
  }

  function normalizeToList(value) {
    if (Array.isArray(value)) {
      return value.map((item) => String(item || "").trim()).filter(Boolean);
    }

    if (typeof value === "string") {
      return value
        .split(/[|,;•]/)
        .map((item) => item.trim())
        .filter(Boolean);
    }

    return [];
  }

  function renderListById(id, values, fallback = "No registrado") {
    const listEl = document.getElementById(id);
    if (!listEl) {
      return;
    }

    const normalized = Array.isArray(values) ? values.filter(Boolean) : [];
    const items = normalized.length > 0 ? normalized : [fallback];

    listEl.innerHTML = "";
    items.forEach((text) => {
      const item = document.createElement("li");
      item.textContent = normalizarTextoVisible(text);
      listEl.appendChild(item);
    });
  }

  function guardarRegistroDetalle(nombre) {
    if (!nombre) {
      return;
    }

    const state = estadoLineas[nombre] || getDefaultStateForNombre(nombre);
    const data = getStorageLineas();
    const existente = data[nombre] || {};
    const detalle = getDetalleDataForNombre(nombre);

    const areaFinal = typeof existente.area === "string" && existente.area.trim() !== ""
      ? existente.area
      : state.area || detalle.area || "";

    const programaFormacionFinal = obtenerProgramaFormacion(existente)
      || state.programaFormacion
      || detalle.programaFormacion
      || "";

    const tendenciaFinal = state.tendencia || detalle.tendenciaActual || "";

    const proyeccionesFinales = Array.isArray(existente.proyecciones) && existente.proyecciones.length > 0
      ? existente.proyecciones.filter(Boolean)
      : mapProyeccionesToText(detalle.proyecciones, state.proyeccion);

    const tecnologiasFinales = Array.isArray(existente.tecnologiasEmergentes) && existente.tecnologiasEmergentes.length > 0
      ? existente.tecnologiasEmergentes.filter(Boolean)
      : Array.isArray(detalle.tecnologiasEmergentes) && detalle.tecnologiasEmergentes.length > 0
        ? detalle.tecnologiasEmergentes.filter(Boolean)
        : [tendenciaFinal].filter(Boolean);

    const registro = {
      nombre,
      active: state.active !== false,
      idLinea: String(existente.idLinea || state.idLinea || "").trim(),
      idArea: String(existente.idArea || state.idArea || "").trim(),
      idPrograma: String(existente.idPrograma || state.idPrograma || "").trim(),
      idEtapa: String(existente.idEtapa || state.idEtapa || "").trim(),
      idTendencia: String(existente.idTendencia || state.idTendencia || "").trim(),
      idProyeccion: String(existente.idProyeccion || state.idProyeccion || "").trim(),
      area: areaFinal,
      programaFormacion: programaFormacionFinal,
      tendencia: tendenciaFinal,
      etapa: state.etapa || "",
      proyeccion: normalizarProyeccionTexto(state.proyeccion, state.idProyeccion || existente.idProyeccion || ""),
      proyecciones: proyeccionesFinales,
      tecnologiasEmergentes: tecnologiasFinales,
      perfiles: Number.isFinite(existente.perfiles) ? existente.perfiles : 0,
      vigentes: Number.isFinite(existente.vigentes) ? existente.vigentes : 0,
      totalLineas: Number.isFinite(existente.totalLineas) ? existente.totalLineas : 1,
      tecnologias: Array.isArray(existente.tecnologias) ? existente.tecnologias : [state.tendencia].filter(Boolean),
      fechaActualizacion: new Date().toISOString(),
    };

    data[nombre] = registro;
    setStorageLineas(data);
  }

  async function inicializarDetalleDesdeStorage() {
    if (!detalleTitulo) {
      return;
    }

    const idLineaQuery = getLineaIdFromQuery();
    if (idLineaQuery) {
      try {
        const fila = await obtenerLineaBackend(idLineaQuery);
        if (fila) {
          const normalizada = normalizarLineaDesdeBackend(fila);
          const nombreFinal = normalizada.nombre;

          detalleTitulo.textContent = nombreFinal;

          estadoLineas[nombreFinal] = {
            active: normalizada.active,
            area: normalizada.area,
            programaFormacion: normalizada.programaFormacion,
            tendencia: normalizada.tendencia,
            etapa: normalizada.etapa,
            proyeccion: normalizada.proyecciones?.[0] || normalizada.proyeccion || normalizarProyeccionTexto("", normalizada.idProyeccion),
            idLinea: normalizada.idLinea,
            idArea: normalizada.idArea,
            idPrograma: normalizada.idPrograma,
            idEtapa: normalizada.idEtapa,
            idTendencia: normalizada.idTendencia,
            idProyeccion: normalizada.idProyeccion,
          };

          setTextById("detalle-tendencia-texto", normalizada.tendencia);
          setTextById("detalle-area-texto", normalizada.area, "No registrada");
          setTextById("detalle-programa-formacion-texto", normalizada.programaFormacion, "No registrado");
          setTextById("detalle-fecha-actualizacion", formatFechaDetalle(normalizada.fechaActualizacion), "Sin registro");

          renderListById("detalle-proyeccion-list", normalizada.proyecciones, "No registrada");
          renderListById("detalle-tecnologias-list", normalizada.tecnologiasEmergentes, "No registradas");

          detalleActivo = normalizada.active;
          actualizarEstadoDetalleUI(detalleActivo);
          return;
        }
      } catch (error) {
        // Fallback to local storage rendering.
      }
    }

    const nombreDesdeQuery = getLineaFromQuery();
    const nombreBase = nombreDesdeQuery || (detalleTitulo.textContent || "").trim() || "Linea Tecnologica";
    const data = getStorageLineas();
    const registro = findRegistroByNombre(data, nombreBase);
    const baseState = registro || {
      nombre: nombreBase,
      ...getDefaultStateForNombre(nombreBase),
    };

    const nombreFinal = (baseState.nombre || nombreBase || "Linea Tecnologica").trim();
    const detalle = getDetalleDataForNombre(nombreFinal);
    const areaFinal = normalizarNombreArea(baseState.area || detalle.area || "", baseState.idArea || "");
    const programaFormacionFinal = obtenerProgramaFormacion(baseState) || detalle.programaFormacion || "";
    const tendenciaFinal = baseState.tendencia || detalle.tendenciaActual || "";

    const proyeccionesFinales = Array.isArray(baseState.proyecciones) && baseState.proyecciones.length > 0
      ? baseState.proyecciones.filter(Boolean)
      : mapProyeccionesToText(detalle.proyecciones, baseState.proyeccion);

    const tecnologiasFinales = Array.isArray(baseState.tecnologiasEmergentes) && baseState.tecnologiasEmergentes.length > 0
      ? baseState.tecnologiasEmergentes.filter(Boolean)
      : Array.isArray(baseState.tecnologias) && baseState.tecnologias.length > 0
        ? baseState.tecnologias.filter(Boolean)
        : Array.isArray(detalle.tecnologiasEmergentes)
          ? detalle.tecnologiasEmergentes.filter(Boolean)
          : [tendenciaFinal].filter(Boolean);

    detalleTitulo.textContent = nombreFinal;

    estadoLineas[nombreFinal] = {
      active: baseState.active !== false,
      area: areaFinal,
      programaFormacion: programaFormacionFinal,
      tendencia: tendenciaFinal,
      etapa: baseState.etapa || "",
      proyeccion: baseState.proyeccion || "",
    };

    if (nombreFinal !== nombreBase && estadoLineas[nombreBase]) {
      delete estadoLineas[nombreBase];
    }

    setTextById("detalle-tendencia-texto", tendenciaFinal);
    setTextById("detalle-area-texto", normalizarNombreArea(areaFinal, baseState.idArea || ""), "No registrada");
    setTextById("detalle-programa-formacion-texto", programaFormacionFinal, "No registrado");
    setTextById("detalle-fecha-actualizacion", formatFechaDetalle(baseState.fechaActualizacion), "Sin registro");

    renderListById("detalle-proyeccion-list", proyeccionesFinales, "No registrada");

    renderListById("detalle-tecnologias-list", tecnologiasFinales, "No registradas");

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
    const chips = [state.programaFormacion, state.tendencia].filter(Boolean).slice(0, 2);

    card.className = "tarjeta-tecnologia bg-white border border-sena-border rounded-xl p-6 flex flex-col gap-3";
    card.dataset.idLinea = String(state.idLinea || "");
    card.dataset.idArea = String(state.idArea || "");
    card.dataset.idPrograma = String(state.idPrograma || "");
    card.dataset.idEtapa = String(state.idEtapa || "");
    card.dataset.idTendencia = String(state.idTendencia || "");
    card.dataset.idProyeccion = String(state.idProyeccion || "");
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
      <div class="flex flex-wrap gap-2"></div>
      <a href="#" class="text-sm text-sena-strong font-medium mt-auto inline-flex items-center gap-1 hover:underline">Ver perfiles &rarr;</a>
    `;

    card.querySelector("h3").textContent = nombre;
    const chipsWrap = card.querySelector(".flex.flex-wrap.gap-2");
    chipsWrap.className = "flex flex-wrap gap-2 max-w-full";

    chips.forEach((chipText) => {
      const chip = document.createElement("span");
      chip.className = "text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5 truncate min-w-0";
      chip.textContent = chipText;
      chip.title = chipText;
      chipsWrap.appendChild(chip);
    });

    cardsGrid.appendChild(card);
    cards.push(card);
    inicializarCard(card, cards.length - 1);
    actualizarLinkDetalle(card, nombre);
    guardarRegistroLinea(nombre, card);
    actualizarEmptyState();
    return card;
  }

  function actualizarCardDesdeState(card, nombre, state) {
    if (!card) {
      return;
    }

    const titleEl = card.querySelector("h3");
    if (titleEl) {
      titleEl.textContent = nombre;
    }

    card.dataset.idLinea = String(state.idLinea || card.dataset.idLinea || "");
    card.dataset.idArea = String(state.idArea || card.dataset.idArea || "");
    card.dataset.idPrograma = String(state.idPrograma || card.dataset.idPrograma || "");
    card.dataset.idEtapa = String(state.idEtapa || card.dataset.idEtapa || "");
    card.dataset.idTendencia = String(state.idTendencia || card.dataset.idTendencia || "");
    card.dataset.idProyeccion = String(state.idProyeccion || card.dataset.idProyeccion || "");

    const chipsWrap = card.querySelector(".flex.flex-wrap.gap-2");
    if (chipsWrap) {
      chipsWrap.innerHTML = "";
      chipsWrap.className = "flex flex-wrap gap-2 max-w-full";
      [state.programaFormacion, state.tendencia].filter(Boolean).slice(0, 2).forEach((chipText) => {
        const chip = document.createElement("span");
        chip.className = "text-xs text-sena-strong bg-sena-soft rounded-full px-2.5 py-0.5 truncate min-w-0";
        chip.textContent = chipText;
        chip.title = chipText;
        chipsWrap.appendChild(chip);
      });
    }

    actualizarLinkDetalle(card, nombre);
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
      area: parseCardArea(card) || "",
      programaFormacion: "",
      tendencia: getOptionValue(tendenciasEmergentesDisponibles[0]) || "",
      etapa: getOptionValue(etapasDisponibles[0]) || "",
      proyeccion: getOptionValue(proyeccionesDisponibles[0]) || "",
      idLinea: String(card?.dataset?.idLinea || ""),
      idArea: String(card?.dataset?.idArea || ""),
      idPrograma: String(card?.dataset?.idPrograma || ""),
      idEtapa: String(card?.dataset?.idEtapa || ""),
      idTendencia: String(card?.dataset?.idTendencia || ""),
      idProyeccion: String(card?.dataset?.idProyeccion || ""),
    };

    lineaEnEdicion = { card, titleEl, oldName: nombreActual };

    modals.editInput.value = nombreActual;
    modals.editArea.value = estadoActual.idArea || estadoActual.area || "";
    modals.editTendencia.value = estadoActual.idTendencia || estadoActual.tendencia;
    modals.editEtapa.value = estadoActual.idEtapa || estadoActual.etapa;
    modals.editProyeccion.value = estadoActual.idProyeccion || estadoActual.proyeccion;
    cargarProgramasPorArea(modals.editArea.value, modals.editPrograma, estadoActual.idPrograma || estadoActual.programaFormacion || "");
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

  async function obtenerLineaBackend(idLinea) {
    if (!idLinea) {
      return null;
    }
    const payload = await fetchJson(API_ROUTES.lineas, {
      accion: "obtener",
      id_linea: idLinea,
    });
    return payload?.data || null;
  }

  async function crearLineaBackend(data) {
    return fetchJson(API_ROUTES.lineas, { accion: "crear" }, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
  }

  async function actualizarLineaBackend(data) {
    return fetchJson(API_ROUTES.lineas, { accion: "actualizar" }, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
  }

  async function cambiarEstadoLineaBackend(idLinea, activar) {
    if (!idLinea) {
      return { success: false };
    }
    const accion = activar ? "activar" : "desactivar";
    return fetchJson(API_ROUTES.lineas, {
      accion,
      id_linea: idLinea,
    });
  }

  function mostrarAlertaFinal(config) {
    const { title, message, subtitle = "", color = "#39A900", seconds = 3 } = config;

    const subtitleResolved = subtitle || (String(title).toLowerCase() === "error"
      ? "La operacion no se completo"
      : "Operacion realizada correctamente");

    modals.successTitle.textContent = title;
    if (modals.successSubtitle) {
      modals.successSubtitle.textContent = subtitleResolved;
    }
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
          area: "",
          programaFormacion: "",
          tendencia: getOptionValue(tendenciasEmergentesDisponibles[0]) || "",
          etapa: getOptionValue(etapasDisponibles[0]) || "",
          proyeccion: getOptionValue(proyeccionesDisponibles[0]) || "",
        };

        lineaEnEdicion = { card: null, titleEl: detalleTitulo, oldName: nombreActual, isDetalle: true };

        modals.editInput.value = nombreActual;
        modals.editArea.value = estadoActual.idArea || estadoActual.area || "";
        modals.editTendencia.value = estadoActual.idTendencia || estadoActual.tendencia;
        modals.editEtapa.value = estadoActual.idEtapa || estadoActual.etapa;
        modals.editProyeccion.value = estadoActual.idProyeccion || estadoActual.proyeccion;
        cargarProgramasPorArea(modals.editArea.value, modals.editPrograma, estadoActual.idPrograma || estadoActual.programaFormacion || "");
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

  if (isListadoView) {
    cards.forEach(inicializarCard);
    guardarTodasLasLineas();
  }
  inicializarDetalleDesdeStorage();
  inicializarAccionesDetalle();

  cargarCatalogosDesdeBackend();
  if (isListadoView) {
    cargarLineasDesdeBackend();
  }

  if (isListadoView && inputBuscarLinea) {
    inputBuscarLinea.addEventListener("input", aplicarFiltroBusqueda);
  }

  if (modals.createArea) {
    modals.createArea.addEventListener("change", () => {
      cargarProgramasPorArea(modals.createArea.value, modals.createPrograma);
    });
  }

  if (modals.editArea) {
    modals.editArea.addEventListener("change", () => {
      cargarProgramasPorArea(modals.editArea.value, modals.editPrograma);
    });
  }

  if (isListadoView) {
    aplicarFiltroBusqueda();
    actualizarEmptyState();
  }

  if (btnNuevaLinea) {
    btnNuevaLinea.addEventListener("click", () => {
      modals.createForm.reset();
      modals.createArea.value = "";
      modals.createPrograma.value = "";
      modals.createTendencia.value = "";
      modals.createEtapa.value = "";
      modals.createProyeccion.value = "";
      setSelectEnabled(modals.createPrograma, false);
      abrirModal(modals.createModal);
    });
  }

  if (btnCrearDesdeEmpty) {
    btnCrearDesdeEmpty.addEventListener("click", () => {
      modals.createForm.reset();
      modals.createArea.value = "";
      modals.createPrograma.value = "";
      modals.createTendencia.value = "";
      modals.createEtapa.value = "";
      modals.createProyeccion.value = "";
      setSelectEnabled(modals.createPrograma, false);
      abrirModal(modals.createModal);
    });
  }

  modals.createClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.createModal));
  });

  modals.createForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const nombreNuevaLinea = modals.createInput.value.trim() || "Nueva Linea";

    if (!modals.createInput.value.trim()) {
      mostrarToastValidacion("El nombre de la linea es requerido", "warning");
      return;
    }

    if (existeLineaConMismoNombre(nombreNuevaLinea)) {
      mostrarToastValidacion("Ya existe una linea con ese nombre", "info");
      return;
    }

    const payloadCrear = {
      nombre_linea: nombreNuevaLinea,
      id_area: modals.createArea.value,
      id_programa: modals.createPrograma.value,
      id_etapa: modals.createEtapa.value,
      id_tendencia: modals.createTendencia.value,
      id_proyeccion: modals.createProyeccion.value,
      estado: 1,
    };

    if (!payloadCrear.id_area || !payloadCrear.id_programa || !payloadCrear.id_etapa || !payloadCrear.id_tendencia || !payloadCrear.id_proyeccion) {
      mostrarAlertaFinal({
        title: "Datos incompletos",
        message: "Debes completar Area, Programa, Etapa, Tendencia y Proyeccion.",
        color: "#e65100",
        seconds: 3,
      });
      return;
    }

    try {
      const creado = await crearLineaBackend(payloadCrear);
      const idCreado = String(creado?.id_linea || "").trim();

      // Sincroniza siempre desde backend tras crear para evitar desajustes visuales.
      if (isListadoView) {
        await cargarLineasDesdeBackend();
      }

      cerrarModal(modals.createModal);
      mostrarAlertaFinal({
        title: "Linea Creada",
        message: idCreado
          ? `La linea \"${nombreNuevaLinea}\" fue creada correctamente (ID ${idCreado}).`
          : `La linea \"${nombreNuevaLinea}\" fue creada correctamente.`,
        color: "#39A900",
        seconds: 3,
      });
      return;
    } catch (error) {
      const backendMsg = String(error?.message || "").trim();
      mostrarAlertaFinal({
        title: "Error",
        message: backendMsg || "No fue posible crear la linea en el backend.",
        color: "#e65100",
        seconds: 3,
      });
      return;
    }
  });

  modals.disableClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.disableModal));
  });

  modals.disableConfirm.addEventListener("click", async () => {
    if (!lineaPendienteDeshabilitar) {
      return;
    }

    const card = lineaPendienteDeshabilitar.switchBtn
      ? lineaPendienteDeshabilitar.switchBtn.closest("div.tarjeta-tecnologia, div.bg-white.rounded-xl")
      : null;
    const idLinea = String(
      card?.dataset?.idLinea
      || estadoLineas[lineaPendienteDeshabilitar.nombre]?.idLinea
      || ""
    ).trim();

    if (idLinea) {
      try {
        const resp = await cambiarEstadoLineaBackend(idLinea, false);
        if (resp?.success === false) {
          throw new Error(resp?.error || "No fue posible desactivar");
        }
      } catch (error) {
        mostrarAlertaFinal({
          title: "Error",
          message: "No fue posible deshabilitar la linea en el backend.",
          color: "#e65100",
          seconds: 3,
        });
        return;
      }
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

  modals.enableConfirm.addEventListener("click", async () => {
    if (!lineaPendienteHabilitar) {
      return;
    }

    const card = lineaPendienteHabilitar.switchBtn
      ? lineaPendienteHabilitar.switchBtn.closest("div.tarjeta-tecnologia, div.bg-white.rounded-xl")
      : null;
    const idLinea = String(
      card?.dataset?.idLinea
      || estadoLineas[lineaPendienteHabilitar.nombre]?.idLinea
      || ""
    ).trim();

    if (idLinea) {
      try {
        const resp = await cambiarEstadoLineaBackend(idLinea, true);
        if (resp?.success === false) {
          throw new Error(resp?.error || "No fue posible activar");
        }
      } catch (error) {
        mostrarAlertaFinal({
          title: "Error",
          message: "No fue posible habilitar la linea en el backend.",
          color: "#e65100",
          seconds: 3,
        });
        return;
      }
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

  modals.editForm.addEventListener("submit", async (event) => {
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
      area: "",
      programaFormacion: "",
      tendencia: getOptionValue(tendenciasEmergentesDisponibles[0]) || "",
      etapa: getOptionValue(etapasDisponibles[0]) || "",
      proyeccion: getOptionValue(proyeccionesDisponibles[0]) || "",
    };

    const idLineaEditar = String(
      oldState.idLinea
      || lineaEnEdicion?.card?.dataset?.idLinea
      || ""
    ).trim();

    delete estadoLineas[lineaEnEdicion.oldName];
    estadoLineas[nuevoNombre] = {
      active: oldState.active,
      area: getOptionLabelByValue(areasDisponibles, modals.editArea.value),
      programaFormacion: modals.editPrograma.value,
      tendencia: modals.editTendencia.value,
      etapa: modals.editEtapa.value,
      proyeccion: getOptionLabelByValue(proyeccionesDisponibles, modals.editProyeccion.value),
      idLinea: idLineaEditar,
      idArea: modals.editArea.value,
      idPrograma: modals.editPrograma.value,
      idEtapa: modals.editEtapa.value,
      idTendencia: modals.editTendencia.value,
      idProyeccion: modals.editProyeccion.value,
    };

    let nombreFinalEdicion = nuevoNombre;

    if (idLineaEditar) {
      try {
        const payloadActualizar = {
          id_linea: idLineaEditar,
          nombre_linea: nuevoNombre,
          id_area: modals.editArea.value,
          id_programa: modals.editPrograma.value,
          id_etapa: modals.editEtapa.value,
          id_tendencia: modals.editTendencia.value,
          id_proyeccion: modals.editProyeccion.value,
          estado: oldState.active ? 1 : 0,
        };

        await actualizarLineaBackend(payloadActualizar);
        const filaActualizada = await obtenerLineaBackend(idLineaEditar);

        if (filaActualizada) {
          const normalizada = normalizarLineaDesdeBackend(filaActualizada);
          const nombreBackend = normalizada.nombre || nuevoNombre;

          delete estadoLineas[nuevoNombre];
          estadoLineas[nombreBackend] = {
            active: normalizada.active,
            area: normalizada.area,
            programaFormacion: normalizada.programaFormacion,
            tendencia: normalizada.tendencia,
            etapa: normalizada.etapa,
            proyeccion: normalizada.proyecciones?.[0] || normalizada.proyeccion || normalizarProyeccionTexto("", normalizada.idProyeccion),
            idLinea: normalizada.idLinea,
            idArea: normalizada.idArea,
            idPrograma: normalizada.idPrograma,
            idEtapa: normalizada.idEtapa,
            idTendencia: normalizada.idTendencia,
            idProyeccion: normalizada.idProyeccion,
          };

          if (lineaEnEdicion.card) {
            lineaEnEdicion.card.dataset.idLinea = normalizada.idLinea;
            lineaEnEdicion.card.dataset.idArea = normalizada.idArea;
            lineaEnEdicion.card.dataset.idPrograma = normalizada.idPrograma;
            lineaEnEdicion.card.dataset.idEtapa = normalizada.idEtapa;
            lineaEnEdicion.card.dataset.idTendencia = normalizada.idTendencia;
            lineaEnEdicion.card.dataset.idProyeccion = normalizada.idProyeccion;
            actualizarCardDesdeState(lineaEnEdicion.card, nombreBackend, estadoLineas[nombreBackend]);
          }

          lineaEnEdicion.titleEl.textContent = nombreBackend;
          nombreFinalEdicion = nombreBackend;
        }
      } catch (error) {
        mostrarAlertaFinal({
          title: "Error",
          message: "No fue posible actualizar la linea en el backend.",
          color: "#e65100",
          seconds: 3,
        });
        return;
      }
    }

    lineaEnEdicion.titleEl.textContent = nombreFinalEdicion;
    if (lineaEnEdicion.card && estadoLineas[nombreFinalEdicion]) {
      actualizarCardDesdeState(lineaEnEdicion.card, nombreFinalEdicion, estadoLineas[nombreFinalEdicion]);
    } else {
      actualizarLinkDetalle(lineaEnEdicion.card, nombreFinalEdicion);
    }

    if (lineaEnEdicion.isDetalle) {
      if (estadoLineas[nombreFinalEdicion]) {
        estadoLineas[nombreFinalEdicion].active = detalleActivo;
      }
      guardarRegistroDetalle(nombreFinalEdicion);
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
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Area</label>
                  <select id="linea-edit-area" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar area...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Programa de Formacion</label>
                  <select id="linea-edit-programa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20">
                    <option value="">Seleccionar programa de formacion...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-edit-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar etapa...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Tendencias Tecnologicas Emergentes</label>
                  <select id="linea-edit-tendencia" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar tendencia tecnologica emergente...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-edit-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar proyeccion...</option>
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
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Area</label>
                  <select id="linea-create-area" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar area...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Programa de Formacion</label>
                  <select id="linea-create-programa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20">
                    <option value="">Seleccionar programa de formacion...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-create-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar etapa...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Tendencias Tecnologicas Emergentes</label>
                  <select id="linea-create-tendencia" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar tendencia tecnologica emergente...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-create-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar proyeccion...</option>
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
                <p id="linea-success-subtitle" class="text-xs text-sena-text-soft">Operacion realizada correctamente</p>
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
      createArea: document.getElementById("linea-create-area"),
      createPrograma: document.getElementById("linea-create-programa"),
      createTendencia: document.getElementById("linea-create-tendencia"),
      createEtapa: document.getElementById("linea-create-etapa"),
      createProyeccion: document.getElementById("linea-create-proyeccion"),
      createClose: document.querySelectorAll(".linea-close-create"),

      editModal: document.getElementById("linea-modal-editar"),
      editForm: document.getElementById("linea-edit-form"),
      editInput: document.getElementById("linea-edit-input"),
      editArea: document.getElementById("linea-edit-area"),
      editPrograma: document.getElementById("linea-edit-programa"),
      editTendencia: document.getElementById("linea-edit-tendencia"),
      editEtapa: document.getElementById("linea-edit-etapa"),
      editProyeccion: document.getElementById("linea-edit-proyeccion"),
      editClose: document.querySelectorAll(".linea-close-edit"),

      successModal: document.getElementById("linea-modal-success"),
      successTitle: document.getElementById("linea-success-title"),
      successSubtitle: document.getElementById("linea-success-subtitle"),
      successIconWrap: document.getElementById("linea-success-icon-wrap"),
      successIcon: document.getElementById("linea-success-icon"),
      successCounter: document.getElementById("linea-success-counter"),
      successProgress: document.getElementById("linea-success-progress"),
      successText: document.getElementById("linea-success-text"),
      successClose: document.querySelectorAll(".linea-close-success"),
    };
  }
});
