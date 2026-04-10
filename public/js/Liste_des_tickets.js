
function normalizeText(str) {
    // Met en minuscule + enlève les accents
    return (str || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .trim();
  }
  
  function parseDateToTs(dateStr) {
    // Si la date n'est pas lisible, on renvoie 0 
    const ts = Date.parse(dateStr);
    return Number.isNaN(ts) ? 0 : ts;
  }
  
  // Récupération des éléments 
  const filterForm = document.querySelector(".filtre-bar");
  const table = document.querySelector(".table-wrap table");
  const tbody = table?.querySelector("tbody");
  
  if (!filterForm || !table || !tbody) {
    // Sécurité : si la structure HTML change, on évite les erreurs JS
    console.warn("tickets-list.js : structure HTML non trouvée (filtre/table/tbody).");
  } else {
    // Filtres
    const statusRadios = Array.from(filterForm.querySelectorAll('input[name="statut"]'));
    const sortSelect = filterForm.querySelector('select[name="tri"]');
    const prioritySelect = filterForm.querySelector('select[name="priority"]');
    const searchInput = filterForm.querySelector('#q');
  
    // afficher le nombre de résultats dans le titre
    const titleEl = document.querySelector(".card-title");
  
    //Données des lignes 
    const rows = Array.from(tbody.querySelectorAll("tr"));
  
    // Ligne 
    const noResultsRow = document.createElement("tr");
    noResultsRow.className = "no-results";
    noResultsRow.innerHTML = `<td colspan="7" style="padding:14px; color:#6b7280;">Aucun ticket ne correspond à vos filtres.</td>`;
    noResultsRow.hidden = true;
    tbody.appendChild(noResultsRow);
  
    // On transforme chaque <tr> en objet facile à filtrer/trier
    const data = rows.map((row, index) => {
      const cells = row.querySelectorAll("td");
  
      const id = cells[0]?.textContent.trim() || "";
      const subject = cells[1]?.textContent.trim() || "";
      const assignee = cells[2]?.textContent.trim() || "";
  
      // Statut : on récupère la classe "status-xxx"
      const statusSpan = row.querySelector(".status");
      let statusKey = "";
      if (statusSpan) {
        for (const cls of statusSpan.classList) {
          if (cls.startsWith("status-")) {
            statusKey = cls.replace("status-", ""); // open / progress / resolved / closed
            break;
          }
        }
      }
  
      // Priorité : on récupère la classe "prio-xxx"
      const prioSpan = row.querySelector(".prio");
      let prioKey = "";
      if (prioSpan) {
        for (const cls of prioSpan.classList) {
          if (cls.startsWith("prio-")) {
            prioKey = cls.replace("prio-", ""); // high / med / low (attention : "med" dans ton HTML)
            break;
          }
        }
      }
  
      const createdText = cells[5]?.textContent.trim() || "";
      const updatedText = cells[6]?.textContent.trim() || "";
  
      return {
        row,
        index, // pour garder un tri stable
        id,
        subject,
        assignee,
        statusKey,
        prioKey,
        createdTs: parseDateToTs(createdText),
        updatedTs: parseDateToTs(updatedText),
        // Texte “fusionné” pour la recherche
        searchBlob: normalizeText(`${id} ${subject} ${assignee}`)
      };
    });
  
    //mettre en surbrillance la pill active
    function updateActivePills() {
      const pills = Array.from(filterForm.querySelectorAll(".pill"));
      pills.forEach((pill) => {
        const input = pill.querySelector('input[type="radio"]');
        pill.classList.toggle("is-active", !!input?.checked);
      });
    }
  
    //Application des filtres + tri
    function applyFilters() {
      const selectedStatus = filterForm.querySelector('input[name="statut"]:checked')?.value || "all";
      const selectedPriority = prioritySelect?.value || "all";
      const selectedSort = sortSelect?.value || "latest";
      const q = normalizeText(searchInput?.value || "");
  
      // 1) Trier d’abord 
      const sorted = [...data].sort((a, b) => {
        const diff = selectedSort === "oldest"
          ? a.updatedTs - b.updatedTs
          : b.updatedTs - a.updatedTs;
  
        // Tri stable si égal, on garde l’ordre initial
        return diff !== 0 ? diff : a.index - b.index;
      });
  
      
      sorted.forEach(item => tbody.appendChild(item.row));
      
  
      //Filtrer / cacher
      let visibleCount = 0;
  
      sorted.forEach((item) => {
        let ok = true;
  
        // Filtre statut
        if (selectedStatus !== "all") {
          ok = ok && item.statusKey === selectedStatus;
        }
  
        // Filtre priorité
        if (selectedPriority !== "all") {

          const prioWanted = selectedPriority === "medium" ? "med" : selectedPriority;
          ok = ok && item.prioKey === prioWanted;
        }
  
        // Recherche
        if (q) {
          ok = ok && item.searchBlob.includes(q);
        }
  
        item.row.hidden = !ok;
        if (ok) visibleCount += 1;
      });
  
      // 3) Afficher / cacher "Aucun résultat"
      noResultsRow.hidden = visibleCount !== 0;
  
      // 4) Mettre à jour le titre 
      if (titleEl) {
        titleEl.textContent = `Tickets (${visibleCount})`;
      }
    }
  
    //Événements 
    // Empêche le "submit" du form sinon ça recharge
    filterForm.addEventListener("submit", (e) => {
      e.preventDefault();
      applyFilters();
    });
  
    // Statut (radios)
    statusRadios.forEach((radio) => {
      radio.addEventListener("change", () => {
        updateActivePills();
        applyFilters();
      });
    });
  
    // Priorité
    prioritySelect?.addEventListener("change", applyFilters);
  
    // Tri
    sortSelect?.addEventListener("change", applyFilters);
  
    // Recherche en live (à chaque frappe)
    searchInput?.addEventListener("input", applyFilters);
  
    // Bonus UX : touche ESC pour vider la recherche
    searchInput?.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        searchInput.value = "";
        applyFilters();
      }
    });
  
    // Init au chargement
    updateActivePills();
    applyFilters();
  }
  