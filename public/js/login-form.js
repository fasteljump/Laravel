//recup élements
const form = document.querySelector("#loginForm");
const messageBox = document.querySelector("#loginMessage");

const email = document.querySelector("#email");
const password = document.querySelector("#password");

const errorEmail = document.querySelector("#error-email");
const errorPassword = document.querySelector("#error-password");

//affiche texte, supprime class et condition pour savoir quelle classe afficher
function showMessage(type, text) {
  messageBox.textContent = text;
  messageBox.classList.add("is-visible");
  messageBox.classList.remove("is-error", "is-success");

  if (type === "error") 
    {
        messageBox.classList.add("is-error");
    }
  if (type === "success") 
    {
        messageBox.classList.add("is-success");
    }
}
//vider les erreurs
function hideMessage() {
  messageBox.textContent = "";
  messageBox.classList.remove("is-visible", "is-error", "is-success");
}
//set les errreurs en fonction du parent de la const, de l'erreur et du texte en paramètre
function setFieldError(fieldElement, errorElement, text) {
  fieldElement.classList.add("is-invalid");
  errorElement.textContent = text;
}
//clear les erreurs 
function clearFieldError(fieldElement, errorElement) {
  fieldElement.classList.remove("is-invalid");
  errorElement.textContent = "";
}

// fonction pour vérifier un format de mail
function isEmailValid(value) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

// toujours la meme forme pour valider le submit : quand le form est envoyé (submit) lire la fonction avant de valider
form.addEventListener("submit", (event) => {
  event.preventDefault(); // on bloque la redirection automatique
  hideMessage();

  let isValid = true;

  // Nettoyage des erreurs quand on refresh
  clearFieldError(email.closest(".champs_mail"), errorEmail);
  clearFieldError(password.closest(".champs_password"), errorPassword);

  // email obligatoire + format correct
  if (!email.value.trim()) 
    {
    isValid = false;
    setFieldError(email.closest(".champs_mail"), errorEmail, "Email obligatoire.");
  } 
  else if (!isEmailValid(email.value.trim())) 
  {
    isValid = false;
    setFieldError(email.closest(".champs_mail"), errorEmail, "Format d'email invalide (ex: nom@mail.com).");
  }

  // Règle 2 : mot de passe obligatoire + min 6 caractères
  if (!password.value.trim()) 
    {
        isValid = false;
        setFieldError(password.closest(".champs_password"), errorPassword, "Mot de passe obligatoire.");
    } 
  else if (password.value.trim().length < 6) 
    {
        isValid = false;
        setFieldError(password.closest(".champs_password"), errorPassword, "Minimum 6 caractères.");
    }

  // Si invalide : message global
  if (!isValid) 
    {
        showMessage("error", "Veuillez corriger les erreurs.");
        return;
    }

  // Sinon : succès 
  showMessage("success", "Connexion réussie. Redirection...");

  // pause pour que l'utilisateur voie le message 
  setTimeout(() => 
    {
    
        window.location.href = form.getAttribute("action");
    }, 400);
});

// 4) Effacer les erreurs quand l'utilisateur corrige
email.addEventListener("input", () => 

    clearFieldError(email.closest(".champs_mail"), errorEmail)
);
password.addEventListener("input", () => 
    
    clearFieldError(password.closest(".champs_password"), errorPassword)
);
