
// récupère les éléments 
const form = document.querySelector("#mot_passe");
const messageBox = document.querySelector("#formMessage");

// champs formulaire
const reset = document.querySelector("#email_reset");


// Zones d'erreurs 
const errorReset = document.querySelector("#error-reset");


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

function hideMessage() 
{
    messageBox.textContent = "";
    messageBox.classList.remove("is-visible", "is-error", "is-success");
}
  //set les errreurs en fonction du parent de la const, de l'erreur et du texte en paramètre
  function setFieldError(fieldElement, errorElement, text) 
  {
    fieldElement.classList.add("is-invalid");
    errorElement.textContent = text;
  }
  //clear les erreurs 
  function clearFieldError(fieldElement, errorElement) 
  {
    fieldElement.classList.remove("is-invalid");
    errorElement.textContent = "";
  }
  
  // fonction pour vérifier un format de mail
  function isEmailValid(value) 
  {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  form.addEventListener("submit", (event) => {
    event.preventDefault(); // on bloque la redirection automatique
    hideMessage();
  
    let isValid = true;
  
    // Nettoyage des erreurs quand on refresh
    clearFieldError(reset.closest(".field"), errorReset);
  
    console.log("printf", !reset.value.trim(), reset.value.trim());
    // email obligatoire + format correct
    if (!reset.value.trim()) 
      {
      isValid = false;
      console.log(reset.closest(".field"));
      setFieldError(reset.closest(".field"), errorReset, "Email obligatoire !");
    } 
    else if (!isEmailValid(reset.value.trim())) 
    {
      isValid = false;
      setFieldError(reset.closest(".field"), errorReset, "Format d'email invalide (ex: nom@mail.com).");
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
  
  // Effacer les erreurs quand l'utilisateur corrige
  reset.addEventListener("input", () => 
  
      clearFieldError(reset.closest(".field"), errorReset)
  );
 