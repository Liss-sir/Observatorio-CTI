document.addEventListener("DOMContentLoaded", () => {
  const STORAGE_KEY = "observatorio_lineas_tecnologicas_v1";

  const refs = {
    badgeArea: document.getElementById("detalle-badge-area"),
    badgeEstado: document.getElementById("detalle-badge-estado"),
    titulo: document.getElementById("detalle-titulo"),
    descripcion: document.getElementById("detalle-descripcion"),
    tecnologias: document.getElementById("detalle-tecnologias"),
    linea: document.getElementById("detalle-linea"),
    foco: document.getElementById("detalle-foco"),
    etapa: document.getElementById("detalle-etapa"),
    proyeccion: document.getElementById("detalle-proyeccion"),
    estadoCard: document.getElementById("detalle-card-estado"),
    estadoTitulo: document.getElementById("detalle-estado-titulo"),
    estadoTexto: document.getElementById("detalle-estado-texto"),
  };

  function getStorageLineas() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return parsed && typeof parsed === "object" ? parsed : {};
    } catch (error) {
      return {};
    }
  }

  function getLineaDesdeQuery() {
    const params = new URLSearchParams(window.location.search);
    return (params.get("linea") || "").trim();
  }

  function getFallbackRecord(nombre) {
    return {
      nombre: nombre || "Linea Tecnologica",
      active: true,
      area: "Sin area",
      linea: "Sin linea tecnologica",
      foco: "Sin foco de vigilancia",
      etapa: "Sin etapa registrada",
      proyeccion: "Sin proyeccion registrada",
      tecnologias: ["Sin tecnologias asociadas"],
      descripcion: "No se encontro informacion guardada para esta linea.",
    };
  }

  function renderTecnologias(items) {
    refs.tecnologias.innerHTML = "";

    const values = Array.isArray(items) && items.length > 0 ? items : ["Sin tecnologias asociadas"];

    values.forEach((value) => {
      const chip = document.createElement("span");
      chip.className =
        "inline-flex items-center rounded-md border border-sena/15 bg-sena-soft px-2.5 py-1 text-xs font-medium text-sena-text-main";
      chip.textContent = value;
      refs.tecnologias.appendChild(chip);
    });
  }

  function renderEstado(active) {
    const isActive = active !== false;

    refs.badgeEstado.textContent = isActive ? "Vigente" : "Inactiva";
    refs.estadoTitulo.textContent = isActive ? "Linea Vigente" : "Linea Inactiva";
    refs.estadoTexto.textContent = isActive
      ? "Esta linea se encuentra activa"
      : "Esta linea se encuentra deshabilitada";

    refs.badgeEstado.className = isActive
      ? "inline-flex items-center gap-1 rounded-full border border-sena/20 bg-sena-soft px-3 py-1 text-xs font-medium text-sena"
      : "inline-flex items-center gap-1 rounded-full border border-orange-300 bg-orange-50 px-3 py-1 text-xs font-medium text-orange-700";

    refs.estadoCard.className = isActive
      ? "rounded-xl border border-sena/20 bg-sena-soft px-4 py-3"
      : "rounded-xl border border-orange-300 bg-orange-50 px-4 py-3";
  }

  function render(record) {
    refs.badgeArea.textContent = record.area || "Sin area";
    refs.titulo.textContent = record.nombre || "Linea Tecnologica";
    refs.descripcion.textContent =
      record.descripcion || `Linea tecnologica ${record.nombre || ""} registrada en el observatorio.`;
    refs.linea.textContent = record.linea || "Sin linea tecnologica";
    refs.foco.textContent = record.foco || "Sin foco de vigilancia";
    refs.etapa.textContent = record.etapa || "Sin etapa registrada";
    refs.proyeccion.textContent = record.proyeccion || "Sin proyeccion registrada";

    renderTecnologias(record.tecnologias);
    renderEstado(record.active);
  }

  const lineas = getStorageLineas();
  const lineaParam = getLineaDesdeQuery();
  const record = lineas[lineaParam] || getFallbackRecord(lineaParam);
  render(record);
});
