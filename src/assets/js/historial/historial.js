document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("historial-search");
  const actionSelect = document.getElementById("historial-filter-accion");
  const moduleSelect = document.getElementById("historial-filter-modulo");
  const roleSelect = document.getElementById("historial-filter-rol");
  const resultsLabel = document.getElementById("historial-results");
  const listContainer = document.getElementById("historial-list");
  const emptyState = document.getElementById("historial-empty-state");

  const statCreaciones = document.getElementById("historial-stat-creaciones");
  const statEdiciones = document.getElementById("historial-stat-ediciones");
  const statDesactivaciones = document.getElementById("historial-stat-desactivaciones");

  if (!searchInput || !actionSelect || !moduleSelect || !roleSelect || !resultsLabel || !listContainer || !emptyState) {
    return;
  }

  const API_URL = "../../controllers/HistorialController.php";
  const ACTION_META = {
    INSERT: {
      label: "Creación",
      badgeClass: "bg-sena-soft text-sena-strong",
    },
    UPDATE: {
      label: "Edición",
      badgeClass: "bg-amber-100 text-amber-700",
    },
    HABILITAR: {
      label: "Habilitación",
      badgeClass: "bg-emerald-100 text-emerald-700",
    },
    DESHABILITAR: {
      label: "Deshabilitación",
      badgeClass: "bg-orange-100 text-orange-700",
    },
    DELETE: {
      label: "Eliminación",
      badgeClass: "bg-rose-100 text-rose-700",
    },
  };

  let allItems = [];

  function normalize(value) {
    return (value || "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .replace(/\s+/g, " ")
      .trim();
  }

  function escapeHtml(value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/\"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function getActionMeta(actionCode) {
    return ACTION_META[actionCode] || {
      label: actionCode || "Acción",
      badgeClass: "bg-slate-100 text-slate-700",
    };
  }

  function getRoleText(item) {
    const role = (item.usuario_cargo || "").trim();
    return role || "No disponible";
  }

  function formatModuleName(rawModule) {
    const value = String(rawModule || "").trim();
    if (!value) return "Módulo";
    return value
      .replace(/_/g, " ")
      .replace(/\s+/g, " ")
      .trim()
      .replace(/\b\w/g, (char) => char.toUpperCase());
  }

  function formatDayLabel(dateValue) {
    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return "Sin fecha";

    const formatter = new Intl.DateTimeFormat("es-CO", {
      weekday: "long",
      day: "2-digit",
      month: "long",
      year: "numeric",
    });

    const text = formatter.format(date);
    return text.charAt(0).toUpperCase() + text.slice(1);
  }

  function formatTime(dateValue) {
    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return "--:--";

    return date.toLocaleTimeString("es-CO", {
      hour: "2-digit",
      minute: "2-digit",
      hour12: false,
    });
  }

  function dayKey(dateValue) {
    const date = new Date(dateValue);
    if (Number.isNaN(date.getTime())) return "sin-fecha";

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  function itemView(item) {
    const actionCode = String(item.accion_ui || item.accion || "").toUpperCase();
    const actionMeta = getActionMeta(actionCode);

    const userName = (item.usuario_nombre || "Sistema").trim() || "Sistema";
    const roleText = getRoleText(item);
    const moduleText = formatModuleName(item.tabla_nombre);
    const detailText = (item.descripcion || "Sin detalle").trim();

    return {
      source: item,
      actionCode,
      actionLabel: actionMeta.label,
      actionBadgeClass: actionMeta.badgeClass,
      roleText,
      moduleText,
      userName,
      detailText,
      when: item.fecha,
      searchText: normalize([userName, roleText, actionMeta.label, moduleText, detailText].join(" ")),
      filterRole: normalize(roleText),
      filterModule: normalize(moduleText),
    };
  }

  function renderRows(rows) {
    listContainer.innerHTML = "";

    if (!rows.length) {
      return;
    }

    const grouped = new Map();
    rows.forEach((row) => {
      const key = dayKey(row.when);
      if (!grouped.has(key)) {
        grouped.set(key, []);
      }
      grouped.get(key).push(row);
    });

    const orderedKeys = Array.from(grouped.keys()).sort((a, b) => (a < b ? 1 : -1));

    orderedKeys.forEach((key) => {
      const dayRows = grouped.get(key) || [];
      const dayTitle = dayRows[0] ? formatDayLabel(dayRows[0].when) : "Sin fecha";

      const rowsHtml = dayRows
        .map((row) => {
          const time = formatTime(row.when);
          return `
            <tr class="border-b border-sena-border last:border-b-0">
              <td class="px-4 py-3 text-sm text-sena-text-soft">${escapeHtml(time)}</td>
              <td class="px-4 py-3">
                <p class="text-sm font-medium text-sena-text-main">${escapeHtml(row.userName)}</p>
              </td>
              <td class="px-4 py-3 text-sm text-sena-text-main">${escapeHtml(row.roleText)}</td>
              <td class="px-4 py-3">
                <span class="inline-block ${escapeHtml(row.actionBadgeClass)} text-xs font-medium px-2.5 py-1 rounded-md">${escapeHtml(row.actionLabel)}</span>
              </td>
              <td class="px-4 py-3 text-sm text-sena-text-main">${escapeHtml(row.moduleText)}</td>
              <td class="px-4 py-3 text-sm text-sena-text-main">${escapeHtml(row.detailText)}</td>
            </tr>
          `;
        })
        .join("");

      const dayHtml = `
        <div class="historial-day-section mb-4">
          <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-sena-text-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path>
              <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
            </svg>
            <span class="text-sm font-medium text-sena-text-main">${escapeHtml(dayTitle)}</span>
            <span class="historial-day-count bg-sena text-white text-xs font-medium px-2 py-0.5 rounded-full">${dayRows.length}</span>
          </div>
          <div class="bg-white rounded-xl border border-sena-border overflow-hidden">
            <table class="w-full">
              <thead>
                <tr class="border-b border-sena-border">
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Hora</th>
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Usuario</th>
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Rol</th>
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Acción</th>
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Módulo</th>
                  <th class="text-left px-4 py-3 text-xs font-medium text-sena-text-soft uppercase tracking-wide">Detalle</th>
                </tr>
              </thead>
              <tbody>${rowsHtml}</tbody>
            </table>
          </div>
        </div>
      `;

      listContainer.insertAdjacentHTML("beforeend", dayHtml);
    });
  }

  function setSelectOptions(select, options, placeholder) {
    const current = select.value;

    select.innerHTML = "";

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = placeholder;
    select.appendChild(defaultOption);

    options.forEach((opt) => {
      const option = document.createElement("option");
      option.value = opt.value;
      option.textContent = opt.label;
      select.appendChild(option);
    });

    if (current && options.some((opt) => opt.value === current)) {
      select.value = current;
    }
  }

  function populateFilterOptions(rows) {
    const actionEntries = [];
    Object.keys(ACTION_META).forEach((code) => {
      if (rows.some((row) => row.actionCode === code)) {
        actionEntries.push({ value: code, label: ACTION_META[code].label });
      }
    });

    const moduleMap = new Map();
    const roleMap = new Map();

    rows.forEach((row) => {
      if (row.filterModule && !moduleMap.has(row.filterModule)) {
        moduleMap.set(row.filterModule, row.moduleText);
      }
      if (row.filterRole && !roleMap.has(row.filterRole)) {
        roleMap.set(row.filterRole, row.roleText);
      }
    });

    const moduleEntries = Array.from(moduleMap.entries())
      .sort((a, b) => a[1].localeCompare(b[1], "es", { sensitivity: "base" }))
      .map(([value, label]) => ({ value, label }));

    const roleEntries = Array.from(roleMap.entries())
      .sort((a, b) => a[1].localeCompare(b[1], "es", { sensitivity: "base" }))
      .map(([value, label]) => ({ value, label }));

    setSelectOptions(actionSelect, actionEntries, "Todas las acciones...");
    setSelectOptions(moduleSelect, moduleEntries, "Todos los módulos...");
    setSelectOptions(roleSelect, roleEntries, "Todos los roles...");
  }

  function updateStats(rows) {
    const countByAction = rows.reduce(
      (acc, row) => {
        acc[row.actionCode] = (acc[row.actionCode] || 0) + 1;
        return acc;
      },
      { INSERT: 0, UPDATE: 0, DESHABILITAR: 0, DELETE: 0 }
    );

    if (statCreaciones) statCreaciones.textContent = String(countByAction.INSERT || 0);
    if (statEdiciones) statEdiciones.textContent = String(countByAction.UPDATE || 0);
    if (statDesactivaciones) {
      const totalDesactivaciones = (countByAction.DESHABILITAR || 0) + (countByAction.DELETE || 0);
      statDesactivaciones.textContent = String(totalDesactivaciones);
    }
  }

  function applyFilters() {
    const searchTerm = normalize(searchInput.value);
    const selectedAction = actionSelect.value;
    const selectedModule = moduleSelect.value;
    const selectedRole = roleSelect.value;

    const filteredRows = allItems.filter((row) => {
      const matchesSearch = !searchTerm || row.searchText.includes(searchTerm);
      const matchesAction = !selectedAction || row.actionCode === selectedAction;
      const matchesModule = !selectedModule || row.filterModule === selectedModule;
      const matchesRole = !selectedRole || row.filterRole === selectedRole;
      return matchesSearch && matchesAction && matchesModule && matchesRole;
    });

    renderRows(filteredRows);
    updateStats(filteredRows);

    resultsLabel.textContent = `Mostrando ${filteredRows.length} de ${allItems.length} registros`;
    emptyState.style.display = filteredRows.length ? "none" : "";
  }

  async function loadHistorial() {
    listContainer.innerHTML = "";
    resultsLabel.textContent = "Cargando historial...";
    emptyState.style.display = "none";

    try {
      const url = `${API_URL}?action=listar&page=1&limit=100`;
      const response = await fetch(url, {
        method: "GET",
        headers: { Accept: "application/json" },
      });

      if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
      }

      const payload = await response.json();
      const items = Array.isArray(payload.items) ? payload.items : [];

      allItems = items
        .map(itemView)
        .filter(
          (row) =>
            row.actionCode === "INSERT" ||
            row.actionCode === "UPDATE" ||
            row.actionCode === "HABILITAR" ||
            row.actionCode === "DESHABILITAR" ||
            row.actionCode === "DELETE"
        );

      populateFilterOptions(allItems);
      applyFilters();
    } catch (error) {
      allItems = [];
      populateFilterOptions(allItems);
      updateStats([]);
      resultsLabel.textContent = "Mostrando 0 de 0 registros";
      emptyState.style.display = "";
      emptyState.innerHTML = '<p class="text-sm text-rose-700">No fue posible cargar el historial en este momento.</p>';
      console.error("Error cargando historial:", error);
    }
  }

  searchInput.addEventListener("input", applyFilters);
  actionSelect.addEventListener("change", applyFilters);
  moduleSelect.addEventListener("change", applyFilters);
  roleSelect.addEventListener("change", applyFilters);

  loadHistorial();
});
