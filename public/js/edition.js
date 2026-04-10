

const form = document.querySelector("form"); 
const messageBox = document.querySelector("#formMessage");

// Champs principaux
const nomProjet = document.querySelector("#nom_projet");
const client = document.querySelector("#client");
const typeContrat = document.querySelector("#type_contrat");

// Champs contrat
const heuresIncluses = document.querySelector("#heures_incluses");
const tauxHoraire = document.querySelector("#taux_horaire");

// Bloc heures (pour afficher/cacher en régie)
const hoursBlock = document.querySelector(".contract-hours");

// Zones d'erreurs
const errorNom = document.querySelector("#error-nom_projet");
const errorClient = document.querySelector("#error-client");
const errorHeures = document.querySelector("#error-heures");
const errorTaux = document.querySelector("#error-taux");

// ===== 2) Fonctions UI (messages / erreurs) =====
function showMessage(type, text) {
  messageBox.textContent = text;
  messageBox.classList.add("is-visible");
  messageBox.classList.remove("is-error", "is-success");

  if (type === "error") messageBox.classList.add("is-error");
  if (type === "success") messageBox.classList.add("is-success");
}

function hideMessage() {
  messageBox.textContent = "";
  messageBox.classList.remove("is-visible", "is-error", "is-success");
}

function setFieldError(fieldElement, errorElement, text) {
  fieldElement.classList.add("is-invalid");
  errorElement.textContent = text;
}

function clearFieldError(fieldElement, errorElement) {
  fieldElement.classList.remove("is-invalid");
  errorElement.textContent = "";
}



function updateContractUI() {
  if (!hoursBlock) return; // si tu n'as pas ajouté .contract-hours, on ne fait rien

  const selected = typeContrat.value; // "forfait_heures" ou "regie"

  if (selected === "regie") {
    hoursBlock.classList.add("is-hidden");
    // Option : on vide les erreurs et valeurs pour éviter confusion
    clearFieldError(heuresIncluses.closest(".field"), errorHeures);
  } else {
    hoursBlock.classList.remove("is-hidden");
  }
}

typeContrat.addEventListener("change", updateContractUI);


form.addEventListener("submit", (event) => {
  event.preventDefault();
  hideMessage();

  let isValid = true;

  // Nettoyage des erreurs (important)
  clearFieldError(nomProjet.closest(".field"), errorNom);
  clearFieldError(client.closest(".field"), errorClient);
  clearFieldError(heuresIncluses.closest(".field"), errorHeures);
  clearFieldError(tauxHoraire.closest(".field"), errorTaux);

  // ----- Règles simples -----

  // Nom obligatoire (min 3)
  if (nomProjet.value.trim().length < 3) {
    isValid = false;
    setFieldError(nomProjet.closest(".field"), errorNom, "Nom obligatoire (min 3 caractères).");
  }

  // Client obligatoire
  if (!client.value) {
    isValid = false;
    setFieldError(client.closest(".field"), errorClient, "Choisis un client.");
  }

  // Heures incluses : seulement si forfait
  // (si régie, on ignore ce champ)
  if (typeContrat.value === "forfait_heures") {
    if (heuresIncluses.value !== "" && Number(heuresIncluses.value) < 0) {
      isValid = false;
      setFieldError(heuresIncluses.closest(".field"), errorHeures, "Heures incluses : valeur positive attendue.");
    }
  }

  // Taux horaire : doit être >= 0 si rempli
  if (tauxHoraire.value !== "" && Number(tauxHoraire.value) < 0) {
    isValid = false;
    setFieldError(tauxHoraire.closest(".field"), errorTaux, "Taux horaire : valeur positive attendue.");
  }

  // Si invalid : message global + stop
  if (!isValid) {
    showMessage("error", "Veuillez corriger les erreurs du formulaire.");
    return;
  }

  // Sinon succès (démo) + redirection
  showMessage("success", "Projet mis à jour (démo). Redirection...");

  setTimeout(() => {
    window.location.href = form.getAttribute("action");
  }, 500);
});


nomProjet.addEventListener("input", () => clearFieldError(nomProjet.closest(".field"), errorNom));
client.addEventListener("change", () => clearFieldError(client.closest(".field"), errorClient));
heuresIncluses.addEventListener("input", () => clearFieldError(heuresIncluses.closest(".field"), errorHeures));
tauxHoraire.addEventListener("input", () => clearFieldError(tauxHoraire.closest(".field"), errorTaux));


updateContractUI();
