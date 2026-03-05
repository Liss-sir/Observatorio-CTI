document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll("main .grid > div.bg-white.rounded-xl");
  const btnMisLineas = document.getElementById("btn-mis-lineas");
  const btnNuevaLinea = document.getElementById("btn-nueva-linea");

  const focosDisponibles = [
    "Manufactura Aditiva",
    "Avances en tecnicas de mecanizado",
    "Sistemas de Soldadura",
    "Sistemas de Corte automatizado",
    "Digitalizacion del mantenimiento",
    "IA y Ciberseguridad",
    "Construccion Modular y Sostenible",
    "IoT y Robotica",
    "Desarrollo de sistemas de control y automatizacion",
  ];

  const areasDisponibles = [
    "Industria y Manufactura",
    "TIC e Infraestructura Digital",
    "Construccion e Infraestructura",
    "Automatizacion y Energia",
    "Automotriz",
    "Moda y Diseno",
    "Multimedia y Audiovisual",
  ];

  const lineasPorArea = {
    "Industria y Manufactura": [
      "Mantenimiento Mecanico Industrial",
      "Sistema de Manufactura con CNC",
      "Soldadura",
      "Electromecanica",
      "Maderas",
    ],
    "TIC e Infraestructura Digital": [
      "Informatica, diseno y desarrollo de software",
      "Infraestructura TIC",
      "Telecomunicaciones",
    ],
    "Construccion e Infraestructura": [
      "Obras civiles",
      "Construccion",
      "Dibujo arquitectonico",
      "Maquinaria Pesada",
      "Redes de gas",
    ],
    "Automatizacion y Energia": [
      "Mecatronica",
      "Electronica",
      "Instrumentacion y Control de Procesos",
      "Refrigeracion y aire acondicionado",
      "Sistemas de Control",
      "Electricidad Industrial",
      "Sistemas electricos domiciliarios",
    ],
    Automotriz: [
      "Autotronica",
      "Mantenimiento mecanico de automotores",
      "Mecanica de motos",
    ],
    "Moda y Diseno": ["Confecciones", "Diseno de modas", "Marroquineria", "Calzado"],
    "Multimedia y Audiovisual": ["Multimedia", "Audiovisuales"],
  };

  const focosPorLinea = {
    "Mantenimiento Mecanico Industrial": [
      "Digitalizacion del mantenimiento",
      "Avances en tecnicas de mecanizado",
    ],
    "Sistema de Manufactura con CNC": [
      "Avances en tecnicas de mecanizado",
      "Manufactura Aditiva",
    ],
    Soldadura: ["Sistemas de Soldadura", "Sistemas de Corte automatizado"],
    Electromecanica: ["IoT y Robotica", "Desarrollo de sistemas de control y automatizacion"],
    "Informatica, diseno y desarrollo de software": ["IA y Ciberseguridad", "IoT y Robotica"],
    "Infraestructura TIC": ["IA y Ciberseguridad", "Digitalizacion del mantenimiento"],
    Telecomunicaciones: ["IA y Ciberseguridad", "IoT y Robotica"],
    "Obras civiles": ["Construccion Modular y Sostenible"],
    Construccion: ["Construccion Modular y Sostenible", "IoT y Robotica"],
    "Dibujo arquitectonico": ["Construccion Modular y Sostenible"],
    "Maquinaria Pesada": ["Digitalizacion del mantenimiento", "IoT y Robotica"],
    "Redes de gas": ["Desarrollo de sistemas de control y automatizacion"],
    Maderas: ["Manufactura Aditiva"],
    Mecatronica: ["IoT y Robotica", "Desarrollo de sistemas de control y automatizacion"],
    Electronica: ["IoT y Robotica", "Desarrollo de sistemas de control y automatizacion"],
    "Instrumentacion y Control de Procesos": ["Desarrollo de sistemas de control y automatizacion"],
    "Refrigeracion y aire acondicionado": ["Digitalizacion del mantenimiento"],
    "Sistemas de Control": ["Desarrollo de sistemas de control y automatizacion"],
    "Electricidad Industrial": ["Desarrollo de sistemas de control y automatizacion"],
    "Sistemas electricos domiciliarios": ["IoT y Robotica"],
    Autotronica: ["IA y Ciberseguridad", "IoT y Robotica"],
    "Mantenimiento mecanico de automotores": ["Digitalizacion del mantenimiento", "IoT y Robotica"],
    "Mecanica de motos": ["Digitalizacion del mantenimiento"],
    Confecciones: ["Manufactura Aditiva"],
    "Diseno de modas": ["Manufactura Aditiva"],
    Marroquineria: ["Manufactura Aditiva"],
    Calzado: ["Manufactura Aditiva"],
    Multimedia: ["IA y Ciberseguridad"],
    Audiovisuales: ["IA y Ciberseguridad"],
  };

  const etapasDisponibles = [
    "Investigacion inicial",
    "Desarrollo tecnologico",
    "Implementacion piloto",
    "Adopcion industrial",
  ];
  const proyeccionesDisponibles = ["5 años", "10 años", "Más de 10 años"];

  const estadoLineas = {};
  let lineaEnEdicion = null;
  let lineaPendienteDeshabilitar = null;
  let lineaPendienteHabilitar = null;
  let mostrandoMisCreaciones = false;
  let successTimeout = null;
  let successInterval = null;

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

  function aplicarCadenaFiltros(form) {
    const { area, linea, foco, etapa, proyeccion } = form;

    fillSelect(area, areasDisponibles, "Seleccionar area...");
    fillSelect(etapa, etapasDisponibles, "Seleccionar etapa...");
    fillSelect(proyeccion, proyeccionesDisponibles, "Seleccionar proyeccion...");
    resetSelect(linea, "Seleccionar linea tecnologica...");
    resetSelect(foco, "Seleccionar foco de vigilancia...");

    setSelectEnabled(linea, false);
    setSelectEnabled(foco, false);
    setSelectEnabled(etapa, false);
    setSelectEnabled(proyeccion, false);

    area.addEventListener("change", () => {
      const lineas = lineasPorArea[area.value] || [];
      fillSelect(linea, lineas, "Seleccionar linea tecnologica...");

      setSelectEnabled(linea, lineas.length > 0);
      resetSelect(foco, "Seleccionar foco de vigilancia...");
      setSelectEnabled(foco, false);
      setSelectEnabled(etapa, false);
      setSelectEnabled(proyeccion, false);
      etapa.value = "";
      proyeccion.value = "";
    });

    linea.addEventListener("change", () => {
      const focos = focosPorLinea[linea.value] || focosDisponibles;
      fillSelect(foco, focos, "Seleccionar foco de vigilancia...");

      const canUse = foco.options.length > 1;
      setSelectEnabled(foco, canUse);
      setSelectEnabled(etapa, false);
      setSelectEnabled(proyeccion, false);
      etapa.value = "";
      proyeccion.value = "";
    });

    foco.addEventListener("change", () => {
      const hasFoco = foco.value.trim() !== "";
      setSelectEnabled(etapa, hasFoco);
      setSelectEnabled(proyeccion, hasFoco);

      if (!hasFoco) {
        etapa.value = "";
        proyeccion.value = "";
      }
    });
  }

  aplicarCadenaFiltros({
    area: modals.createArea,
    linea: modals.createLinea,
    foco: modals.createFoco,
    etapa: modals.createEtapa,
    proyeccion: modals.createProyeccion,
  });

  aplicarCadenaFiltros({
    area: modals.editArea,
    linea: modals.editLinea,
    foco: modals.editFoco,
    etapa: modals.editEtapa,
    proyeccion: modals.editProyeccion,
  });

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

  function setMisCreacionesButtonState(isActive) {
    if (!btnMisLineas) {
      return;
    }

    btnMisLineas.classList.toggle("bg-sena", isActive);
    btnMisLineas.classList.toggle("text-white", isActive);
    btnMisLineas.classList.toggle("border-sena", isActive);
    btnMisLineas.classList.toggle("hover:bg-sena", isActive);
    btnMisLineas.classList.toggle("text-sena-text-main", !isActive);
    btnMisLineas.classList.toggle("border-sena-border", !isActive);
    btnMisLineas.classList.toggle("bg-white", !isActive);
    btnMisLineas.classList.toggle("hover:bg-sena-soft", !isActive);

    const icon = btnMisLineas.querySelector("svg");
    if (icon) {
      icon.classList.toggle("text-white", isActive);
      icon.classList.toggle("text-sena-text-soft", !isActive);
    }
  }

  function aplicarFiltroMisCreaciones() {
    cards.forEach((card, index) => {
      card.style.display = !mostrandoMisCreaciones || index < 3 ? "flex" : "none";
    });
  }

  function setSwitchState(switchBtn, isActive) {
    switchBtn.dataset.active = isActive ? "true" : "false";
    switchBtn.setAttribute("aria-pressed", isActive ? "true" : "false");
    switchBtn.classList.toggle("bg-sena", isActive);
    switchBtn.classList.toggle("bg-gray-300", !isActive);

    const thumb = switchBtn.querySelector("span");
    if (thumb) {
      thumb.style.transform = isActive ? "translateX(12px)" : "translateX(0)";
    }
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

  function inicializarCard(card, index) {
    if (card.querySelector(".linea-tec-controls")) {
      return;
    }

    const cardTopRow = card.querySelector(".flex.justify-between.items-start");
    const perfilCounter = cardTopRow?.querySelector("span");
    const titleEl = card.querySelector("h3");

    if (!cardTopRow || !perfilCounter || !titleEl) {
      return;
    }

    const nombreTecnologia = titleEl.textContent.trim() || `Linea ${index + 1}`;

    estadoLineas[nombreTecnologia] = {
      active: true,
      area: areasDisponibles[0],
      linea: (lineasPorArea[areasDisponibles[0]] || [""])[0],
      foco: focosDisponibles[0],
      etapa: etapasDisponibles[1],
      proyeccion: proyeccionesDisponibles[0],
    };

    cardTopRow.classList.remove("items-start");
    cardTopRow.classList.add("items-center");

    const editarBtn = document.createElement("button");
    editarBtn.type = "button";
    editarBtn.className =
      "w-8 h-8 rounded-md flex items-center justify-center text-sena-text-soft hover:bg-sena-soft hover:text-sena-text-main transition-colors";
    editarBtn.setAttribute("aria-label", `Editar ${nombreTecnologia}`);
    editarBtn.title = "Editar";
    editarBtn.innerHTML =
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>';

    const switchEstadoBtn = document.createElement("button");
    switchEstadoBtn.type = "button";
    switchEstadoBtn.className =
      "linea-tec-switch relative w-8 h-5 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-sena/20";
    switchEstadoBtn.setAttribute("aria-label", `Cambiar estado de ${nombreTecnologia}`);

    const switchThumb = document.createElement("span");
    switchThumb.className =
      "absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform";
    switchEstadoBtn.appendChild(switchThumb);
    setSwitchState(switchEstadoBtn, true);

    editarBtn.addEventListener("click", () => {
      const nombreActual = titleEl.textContent.trim();
      const estadoActual = estadoLineas[nombreActual] || {
        active: true,
        area: areasDisponibles[0],
        linea: (lineasPorArea[areasDisponibles[0]] || [""])[0],
        foco: focosDisponibles[0],
        etapa: etapasDisponibles[1],
        proyeccion: proyeccionesDisponibles[0],
      };
      lineaEnEdicion = { card, titleEl, oldName: nombreActual };

      modals.editInput.value = titleEl.textContent.trim();
      modals.editArea.value = estadoActual.area;
      modals.editArea.dispatchEvent(new Event("change"));
      modals.editLinea.value = estadoActual.linea;
      modals.editLinea.dispatchEvent(new Event("change"));
      modals.editFoco.value = estadoActual.foco;
      modals.editFoco.dispatchEvent(new Event("change"));
      modals.editEtapa.value = estadoActual.etapa;
      modals.editProyeccion.value = estadoActual.proyeccion;
      abrirModal(modals.editModal);
    });

    switchEstadoBtn.addEventListener("click", () => {
      const isActive = switchEstadoBtn.dataset.active === "true";

      if (isActive) {
        lineaPendienteDeshabilitar = { switchBtn: switchEstadoBtn, nombre: titleEl.textContent.trim() };
        modals.disableText.textContent = `Estas seguro de deshabilitar \"${lineaPendienteDeshabilitar.nombre}\"? El registro no se eliminara, solo se marcara como inactivo.`;
        abrirModal(modals.disableModal);
        return;
      }

      lineaPendienteHabilitar = { switchBtn: switchEstadoBtn, nombre: titleEl.textContent.trim() };
      modals.enableText.textContent = `Estas seguro de que deseas habilitar la linea \"${lineaPendienteHabilitar.nombre}\"?`;
      abrirModal(modals.enableModal);
    });

    const controlsContainer = document.createElement("div");
    controlsContainer.className = "linea-tec-controls flex items-center gap-2";
    controlsContainer.appendChild(editarBtn);
    controlsContainer.appendChild(switchEstadoBtn);
    controlsContainer.appendChild(perfilCounter);
    cardTopRow.appendChild(controlsContainer);
  }

  cards.forEach(inicializarCard);

  if (btnMisLineas) {
    btnMisLineas.addEventListener("click", () => {
      mostrandoMisCreaciones = !mostrandoMisCreaciones;
      setMisCreacionesButtonState(mostrandoMisCreaciones);
      aplicarFiltroMisCreaciones();
    });
  }

  if (btnNuevaLinea) {
    btnNuevaLinea.addEventListener("click", () => {
      modals.createForm.reset();
      modals.createArea.value = "";
      modals.createLinea.value = "";
      modals.createFoco.value = "";
      modals.createEtapa.value = "";
      modals.createProyeccion.value = "";
      modals.createArea.dispatchEvent(new Event("change"));
      abrirModal(modals.createModal);
    });
  }

  modals.createClose.forEach((btn) => {
    btn.addEventListener("click", () => cerrarModal(modals.createModal));
  });

  modals.createForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const nombreNuevaLinea = modals.createInput.value.trim() || "Nueva Linea";
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

    setSwitchState(lineaPendienteDeshabilitar.switchBtn, false);

    if (estadoLineas[lineaPendienteDeshabilitar.nombre]) {
      estadoLineas[lineaPendienteDeshabilitar.nombre].active = false;
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

    setSwitchState(lineaPendienteHabilitar.switchBtn, true);

    if (estadoLineas[lineaPendienteHabilitar.nombre]) {
      estadoLineas[lineaPendienteHabilitar.nombre].active = true;
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
      area: areasDisponibles[0],
      linea: (lineasPorArea[areasDisponibles[0]] || [""])[0],
      foco: focosDisponibles[0],
      etapa: etapasDisponibles[1],
      proyeccion: proyeccionesDisponibles[0],
    };
    delete estadoLineas[lineaEnEdicion.oldName];
    estadoLineas[nuevoNombre] = {
      active: oldState.active,
      area: modals.editArea.value,
      linea: modals.editLinea.value,
      foco: modals.editFoco.value,
      etapa: modals.editEtapa.value,
      proyeccion: modals.editProyeccion.value,
    };

    lineaEnEdicion.titleEl.textContent = nuevoNombre;
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
                    ${areasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Linea tecnologica</label>
                  <select id="linea-edit-linea" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar linea tecnologica...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Foco de vigilancia</label>
                  <select id="linea-edit-foco" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar foco de vigilancia...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-edit-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar etapa...</option>
                    ${etapasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-edit-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
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
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Area</label>
                  <select id="linea-create-area" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" required>
                    <option value="">Seleccionar area...</option>
                    ${areasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Linea tecnologica</label>
                  <select id="linea-create-linea" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar linea tecnologica...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Foco de vigilancia</label>
                  <select id="linea-create-foco" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar foco de vigilancia...</option>
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Etapa de Desarrollo o tendencia Actual</label>
                  <select id="linea-create-etapa" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
                    <option value="">Seleccionar etapa...</option>
                    ${etapasDisponibles.map((o) => `<option value="${o}">${o}</option>`).join("")}
                  </select>
                </div>
                <div>
                  <label class="mb-1 block text-sm font-medium text-sena-text-main">Proyeccion a Futuro</label>
                  <select id="linea-create-proyeccion" class="w-full rounded-lg border border-sena-border pl-3 pr-10 py-2 text-sm text-sena-text-main bg-white focus:border-sena focus:ring-2 focus:ring-sena/20" disabled required>
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
      createArea: document.getElementById("linea-create-area"),
      createLinea: document.getElementById("linea-create-linea"),
      createFoco: document.getElementById("linea-create-foco"),
      createEtapa: document.getElementById("linea-create-etapa"),
      createProyeccion: document.getElementById("linea-create-proyeccion"),
      createClose: document.querySelectorAll(".linea-close-create"),

      editModal: document.getElementById("linea-modal-editar"),
      editForm: document.getElementById("linea-edit-form"),
      editInput: document.getElementById("linea-edit-input"),
      editArea: document.getElementById("linea-edit-area"),
      editLinea: document.getElementById("linea-edit-linea"),
      editFoco: document.getElementById("linea-edit-foco"),
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
