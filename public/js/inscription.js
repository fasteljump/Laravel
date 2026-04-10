

// 1) Récupération des éléments
const form = document.querySelector("#inscription");
const messageBox = document.querySelector("#formMessage");

// Champs
const prenom = document.querySelector("#prenom");
const nom = document.querySelector("#nom");
const email = document.querySelector("#email_signup");
const role = document.querySelector("#role");
const password = document.querySelector("#password_signup");
const passwordConfirm = document.querySelector("#password_confirm");

// Zones d'erreurs (les <p> sous les champs)
const errorPrenom = document.querySelector("#error-prenom");
const errorNom = document.querySelector("#error-nom");
const errorEmail = document.querySelector("#error-email");
const errorRole = document.querySelector("#error-role");
const errorPassword = document.querySelector("#error-mdp");
const errorPasswordConfirm = document.querySelector("#error-mdpp");

// 2) Fonctions utilitaires (messages + erreurs)
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

// Email simple (format de base)
function isEmailValid(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

// 3) Validation au submit
form.addEventListener("submit", (event) => {
  event.preventDefault();
  hideMessage();

  let isValid = true;

  // Nettoyage complet (important pour éviter que ça reste rouge)
  clearFieldError(prenom.closest(".field"), errorPrenom);
  clearFieldError(nom.closest(".field"), errorNom);
  clearFieldError(email.closest(".field"), errorEmail);
  clearFieldError(role.closest(".field"), errorRole);
  clearFieldError(password.closest(".field"), errorPassword);
  clearFieldError(passwordConfirm.closest(".field"), errorPasswordConfirm);


  // Prénom : obligatoire, min 2 caractères
  if (prenom.value.trim().length < 2) {
    isValid = false;
    setFieldError(prenom.closest(".field"), errorPrenom, "Prénom obligatoire (min 2 caractères).");
  }

  // Nom : obligatoire, min 2 caractères
  if (nom.value.trim().length < 2) {
    isValid = false;
    setFieldError(nom.closest(".field"), errorNom, "Nom obligatoire (min 2 caractères).");
  }

  // Email : obligatoire + format
  if (!email.value.trim()) {
    isValid = false;
    setFieldError(email.closest(".field"), errorEmail, "Email obligatoire.");
  } else if (!isEmailValid(email.value.trim())) {
    isValid = false;
    setFieldError(email.closest(".field"), errorEmail, "Format d'email invalide (ex: nom@mail.com).");
  }

  // Rôle : obligatoire (option vide interdite)
  if (!role.value) {
    isValid = false;
    setFieldError(role.closest(".field"), errorRole, "Choisis un rôle.");
  }

  // Mot de passe : obligatoire, min 6
  if (password.value.trim().length < 6) {
    isValid = false;
    setFieldError(password.closest(".field"), errorPassword, "Mot de passe obligatoire (min 6 caractères).");
  }

  // Confirmation : doit matcher
  if (passwordConfirm.value.trim().length < 6) {
    isValid = false;
    setFieldError(passwordConfirm.closest(".field"), errorPasswordConfirm, "Confirmation obligatoire (min 6 caractères).");
  } else if (passwordConfirm.value !== password.value) {
    isValid = false;
    setFieldError(passwordConfirm.closest(".field"), errorPasswordConfirm, "Les mots de passe ne correspondent pas.");
  }

  // Si invalide : message global
  if (!isValid) {
    showMessage("error", "Veuillez corriger les erreurs.");
    return;
  }

  // Sinon : succès (démo) + redirection
  showMessage("success", "Profil créé (démo). Redirection...");

  setTimeout(() => {
    window.location.href = form.getAttribute("action");
  }, 500);
});

// 4) Bonus UX : enlever les erreurs dès que l'utilisateur corrige
prenom.addEventListener("input", () => clearFieldError(prenom.closest(".field"), errorPrenom));
nom.addEventListener("input", () => clearFieldError(nom.closest(".field"), errorNom));
email.addEventListener("input", () => clearFieldError(email.closest(".field"), errorEmail));
role.addEventListener("change", () => clearFieldError(role.closest(".field"), errorRole));
password.addEventListener("input", () => clearFieldError(password.closest(".field"), errorPassword));
passwordConfirm.addEventListener("input", () => clearFieldError(passwordConfirm.closest(".field"), errorPasswordConfirm));
