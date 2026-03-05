document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll("main .grid > div.bg-white.rounded-xl");
  const btnMisLineas = document.getElementById("btn-mis-lineas");
  const btnNuevaLinea = document.getElementById("btn-nueva-linea");

  const tecnologiasDisponibles = [
    "Inteligencia Artificial",
    "Blockchain",
    "Internet de las Cosas (IoT)",
    "Computacion en la Nube",
    "Ciberseguridad Avanzada",
    "Big Data y Analitica",
    "Realidad Aumentada/Virtual",
    "Robotica Colaborativa",
    "Manufactura Aditiva (Impresion 3D)",
    "Automatizacion de Procesos (RPA)",
  ];

  const estadoLineas = {};
  let lineaEnEdicion = null;
  let lineaPendienteDeshabilitar = null;
  let lineaPendienteHabilitar = null;
  let mostrandoMisCreaciones = false;
  let successTimeout = null;
  let successInterval = null;

  const modals = crearModales();

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

  function renderChipsEditModal(selectedChips) {
    modals.chipsContainer.innerHTML = "";
    tecnologiasDisponibles.forEach((tecnologia) => {
      const chip = document.createElement("button");
      chip.type = "button";
      chip.dataset.value = tecnologia;
      chip.textContent = tecnologia;
      chip.className =
        "linea-chip rounded-full border px-3 py-1 text-xs transition-colors";

      const isSelected = selectedChips.includes(tecnologia);
      chip.classList.toggle("bg-sena", isSelected);
      chip.classList.toggle("text-white", isSelected);
      chip.classList.toggle("border-sena", isSelected);
      chip.classList.toggle("bg-white", !isSelected);
      chip.classList.toggle("text-sena-text-main", !isSelected);
      chip.classList.toggle("border-sena-border", !isSelected);

      chip.addEventListener("click", () => {
        const selected = chip.classList.contains("bg-sena");
        chip.classList.toggle("bg-sena", !selected);
        chip.classList.toggle("text-white", !selected);
        chip.classList.toggle("border-sena", !selected);
        chip.classList.toggle("bg-white", selected);
        chip.classList.toggle("text-sena-text-main", selected);
        chip.classList.toggle("border-sena-border", selected);
      });

      modals.chipsContainer.appendChild(chip);
    });
  }

  function renderChipsCreateModal() {
    modals.createChipsContainer.innerHTML = "";
    tecnologiasDisponibles.forEach((tecnologia) => {
      const chip = document.createElement("button");
      chip.type = "button";
      chip.dataset.value = tecnologia;
      chip.textContent = tecnologia;
      chip.className =
        "linea-chip-create rounded-full border border-sena-border bg-white text-sena-text-main px-3 py-1 text-xs transition-colors";

      chip.addEventListener("click", () => {
        const selected = chip.classList.contains("bg-sena");
        chip.classList.toggle("bg-sena", !selected);
        chip.classList.toggle("text-white", !selected);
        chip.classList.toggle("border-sena", !selected);
        chip.classList.toggle("bg-white", selected);
        chip.classList.toggle("text-sena-text-main", selected);
        chip.classList.toggle("border-sena-border", selected);
      });

      modals.createChipsContainer.appendChild(chip);
    });
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
      chips: tecnologiasDisponibles.slice(0, 5),
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
        chips: tecnologiasDisponibles.slice(0, 5),
      };
      lineaEnEdicion = { card, titleEl, oldName: nombreActual };

      modals.editInput.value = titleEl.textContent.trim();
      renderChipsEditModal(estadoActual.chips);
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
      renderChipsCreateModal();
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

    const chipsSeleccionados = Array.from(
      modals.chipsContainer.querySelectorAll(".linea-chip.bg-sena")
    ).map((chip) => chip.dataset.value);

    const oldState = estadoLineas[lineaEnEdicion.oldName] || { active: true, chips: [] };
    delete estadoLineas[lineaEnEdicion.oldName];
    estadoLineas[nuevoNombre] = {
      active: oldState.active,
      chips: chipsSeleccionados,
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
              <div class="mb-5">
                <p class="mb-2 text-sm font-medium text-sena-text-main">Tecnologias Emergentes Relacionadas</p>
                <div id="linea-edit-chips" class="flex flex-wrap gap-2"></div>
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
              <div class="mb-5">
                <p class="mb-2 text-sm font-medium text-sena-text-main">Tecnologias Emergentes Relacionadas</p>
                <div id="linea-create-chips" class="flex flex-wrap gap-2"></div>
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
      createChipsContainer: document.getElementById("linea-create-chips"),
      createClose: document.querySelectorAll(".linea-close-create"),

      editModal: document.getElementById("linea-modal-editar"),
      editForm: document.getElementById("linea-edit-form"),
      editInput: document.getElementById("linea-edit-input"),
      chipsContainer: document.getElementById("linea-edit-chips"),
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
