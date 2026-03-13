document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("historial-search");
  const actionSelect = document.getElementById("historial-filter-accion");
  const moduleSelect = document.getElementById("historial-filter-modulo");
  const roleSelect = document.getElementById("historial-filter-rol");
  const resultsLabel = document.getElementById("historial-results");

  const daySections = Array.from(document.querySelectorAll(".historial-day-section"));
  if (!searchInput || !actionSelect || !moduleSelect || !roleSelect || !resultsLabel || daySections.length === 0) {
    return;
  }

  const sectionModels = daySections
    .map((section) => ({
      section,
      rows: Array.from(section.querySelectorAll("tbody tr")),
      badge: section.querySelector(".historial-day-count"),
    }))
    .filter((model) => model.rows.length > 0);

  const allRows = sectionModels.flatMap((model) => model.rows);
  const totalRows = allRows.length;

  function normalize(value) {
    return (value || "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .replace(/\s+/g, " ")
      .trim();
  }

  function getCellText(cell) {
    return (cell?.textContent || "").replace(/\s+/g, " ").trim();
  }

  function hydrateRowData(row) {
    const cells = row.querySelectorAll("td");
    const roleText = getCellText(cells[2]);
    const actionText = getCellText(cells[3]);
    const moduleText = getCellText(cells[4]);
    const detailText = getCellText(cells[5]);
    const userText = getCellText(cells[1]);

    row.dataset.filterRole = normalize(roleText);
    row.dataset.filterAction = normalize(actionText);
    row.dataset.filterModule = normalize(moduleText);
    row.dataset.searchText = normalize([userText, roleText, actionText, moduleText, detailText].join(" "));
  }

  allRows.forEach(hydrateRowData);

  function populateSelect(select, valuesMap, placeholder) {
    const current = select.value;
    const options = Array.from(valuesMap.entries()).sort((a, b) => a[1].localeCompare(b[1], "es", { sensitivity: "base" }));

    select.innerHTML = "";

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = placeholder;
    select.appendChild(defaultOption);

    options.forEach(([value, label]) => {
      const option = document.createElement("option");
      option.value = value;
      option.textContent = label;
      select.appendChild(option);
    });

    if (current && valuesMap.has(current)) {
      select.value = current;
    }
  }

  const actionsMap = new Map();
  const modulesMap = new Map();
  const rolesMap = new Map();

  allRows.forEach((row) => {
    const cells = row.querySelectorAll("td");
    const actionLabel = getCellText(cells[3]);
    const moduleLabel = getCellText(cells[4]);
    const roleLabel = getCellText(cells[2]);

    const actionValue = normalize(actionLabel);
    const moduleValue = normalize(moduleLabel);
    const roleValue = normalize(roleLabel);

    if (actionValue && !actionsMap.has(actionValue)) {
      actionsMap.set(actionValue, actionLabel);
    }
    if (moduleValue && !modulesMap.has(moduleValue)) {
      modulesMap.set(moduleValue, moduleLabel);
    }
    if (roleValue && !rolesMap.has(roleValue)) {
      rolesMap.set(roleValue, roleLabel);
    }
  });

  populateSelect(actionSelect, actionsMap, "Todas las acciones...");
  populateSelect(moduleSelect, modulesMap, "Todos los modulos...");
  populateSelect(roleSelect, rolesMap, "Todos los roles...");

  function applyFilters() {
    const searchTerm = normalize(searchInput.value);
    const actionValue = actionSelect.value;
    const moduleValue = moduleSelect.value;
    const roleValue = roleSelect.value;

    let visibleRows = 0;

    sectionModels.forEach((model) => {
      let visibleInSection = 0;

      model.rows.forEach((row) => {
        const matchesSearch = !searchTerm || row.dataset.searchText.includes(searchTerm);
        const matchesAction = !actionValue || row.dataset.filterAction === actionValue;
        const matchesModule = !moduleValue || row.dataset.filterModule === moduleValue;
        const matchesRole = !roleValue || row.dataset.filterRole === roleValue;

        const visible = matchesSearch && matchesAction && matchesModule && matchesRole;
        row.style.display = visible ? "" : "none";

        if (visible) {
          visibleInSection += 1;
          visibleRows += 1;
        }
      });

      model.section.style.display = visibleInSection > 0 ? "" : "none";
      if (model.badge) {
        model.badge.textContent = String(visibleInSection);
      }
    });

    resultsLabel.textContent = `Mostrando ${visibleRows} de ${totalRows} registros`;
  }

  searchInput.addEventListener("input", applyFilters);
  actionSelect.addEventListener("change", applyFilters);
  moduleSelect.addEventListener("change", applyFilters);
  roleSelect.addEventListener("change", applyFilters);

  applyFilters();
});
