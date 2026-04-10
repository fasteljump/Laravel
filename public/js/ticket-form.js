

// récupère les éléments 
const form = document.querySelector("#ticketForm");
const messageBox = document.querySelector("#formMessage");

// champs formulaire
const project = document.querySelector("#project");
const title = document.querySelector("#title");
const desc = document.querySelector("#desc");
const temps = document.querySelector("#spent");
const estimate = document.querySelector("#estimate")


// Zones d'erreurs 
const errorProject = document.querySelector("#error-project");
const errorTitle = document.querySelector("#error-title");
const errorDesc = document.querySelector("#error-desc");
const errorTemps = document.querySelector("#error-spent");
const errorestimate =document.querySelector("#error-spent2")

// afficher message en haut 
function showMessage(type, text) {
  messageBox.textContent = text;
  messageBox.classList.add("is-visible");
  messageBox.classList.remove("is-error", "is-success");
  

  if (type === "error") messageBox.classList.add("is-error");
  if (type === "success") messageBox.classList.add("is-success");
}
// cacher le message global 
function hideMessage() {
  messageBox.textContent = "";
  messageBox.classList.remove("is-visible", "is-error", "is-success");
}

// afficher erreur d'un champ 
function setFieldError(fieldElement, errorElement, text) {
  fieldElement.classList.add("is-invalid");
  errorElement.textContent = text;
}

// Enlever erreur d'un champ
function clearFieldError(fieldElement, errorElement) {
  fieldElement.classList.remove("is-invalid");
  errorElement.textContent = "";
}


// Ecoute du submit
form.addEventListener("submit", (event) => {
  // éviter l'envoi automatique 
  event.preventDefault();

  hideMessage();

  // booleen false au début et true si erreur pour afficher message en haut 
  let isValid = true;

  // Nettoyage erreurs au début 
  clearFieldError(project.closest(".field"), errorProject);
  clearFieldError(title.closest(".field"), errorTitle);
  clearFieldError(desc.closest(".field"), errorDesc);
  clearFieldError(temps.closest(".field"), errorTemps);

  // Règles simples 
  if (!project.value) {
    isValid = false;
    setFieldError(project.closest(".field"), errorProject, "Choisis un projet.");
  }

  if (title.value.trim().length < 5) {
    isValid = false;
    setFieldError(title.closest(".field"), errorTitle, "Titre obligatoire (min 5 caractères).");
  }

  if (desc.value.trim().length < 20) {
    isValid = false;
    setFieldError(desc.closest(".field"), errorDesc, "Description trop courte (min 20 caractères).");
  }

  // Temps passé. Number pour convertir string vers decimal 
  if (temps.value !== "" && Number(temps.value) < 0) {
    isValid = false;
    setFieldError(temps.closest(".field"), errorTemps, "Le temps passé doit être positif et non négatif !");
  }

  if(estimate.value =="")
  {
    isValid = false;
    setFieldError(estimate.closest(".field"), errorestimate, "Le champs ne peut pas etre vide !");
  }

  if(Number(estimate.value)<0)
  {
    isValid = false;
    setFieldError(estimate.closest(".field"), errorestimate, " Le temps passé doit être positif et non négatif !");
  }
  // Si invalid afficher message en haut
  if (!isValid) {
    showMessage("error", "Veuillez corriger les erreurs du formulaire.");
    return;
  }

  // sinon afficher succes
  showMessage("success", "Ticket créé avec succès.");

  form.reset();

});

title.addEventListener("input", () => 
  
  clearFieldError(title.closest(".field"), errorTitle)
);
desc.addEventListener("input", () => 

  clearFieldError(desc.closest(".field"), errorDesc)
);
project.addEventListener("change", () => 
  
  clearFieldError(project.closest(".field"), errorProject)
);
temps.addEventListener("input", () => 
  
  clearFieldError(temps.closest(".field"), errorTemps)
);


 