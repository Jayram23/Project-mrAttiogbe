document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form.reservation");
  
    const fields = {
      name: {
        regex: /^[A-Za-z\s\-']+$/,
        error: "Le nom ne doit contenir que des lettres.",
      },
      email: {
        regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        error: "Adresse email invalide.",
      },
      phone: {
        regex: /^\+?[0-9\s\-]+$/,
        error: "Numéro de téléphone invalide.",
      },
      departure: {
        regex: /^[A-Za-z\s\-']+$/,
        error: "La ville de départ ne doit contenir que des lettres.",
      },
      arrival: {
        regex: /^[A-Za-z\s\-']+$/,
        error: "La ville d'arrivée ne doit contenir que des lettres.",
      },
      date: {
        validate: function (date) {
          const now = new Date();
          const inputDate = new Date(date);
          return inputDate >= now;
        },
        error: "La date de départ doit être future.",
      },
      tarif: {
        regex: /^\d+(\.\d{1,2})?$/,
        error: "Le montant du tarif doit être un nombre valide.",
      }
    };
  
    let errorMessages = [];
  
    function showMessage(message, isError = true) {
      const messageDiv = document.getElementById('form-messages');
      if (!messageDiv) return;
  
      const p = document.createElement('p');
      p.textContent = message;
      p.style.color = isError ? 'red' : 'green';
      p.style.margin = '5px 0';
      messageDiv.appendChild(p);
    }
  
    function clearMessages() {
      const messageDiv = document.getElementById('form-messages');
      if (messageDiv) messageDiv.innerHTML = '';
    }
  
    // ✏️ Empêcher la saisie de caractères non autorisés dans certains champs
    function restrictInput() {
      const letterOnlyFields = ['name', 'departure', 'arrival'];
      const numberOnlyFields = ['phone', 'tarif'];
  
      letterOnlyFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.addEventListener('keypress', function (e) {
            if (!/[a-zA-Z\s\-']/.test(e.key)) {
              e.preventDefault();
            }
          });
        }
      });
  
      numberOnlyFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key)) {
              e.preventDefault();
            }
          });
        }
      });
    }
  
    // 🔐 Validation globale à la soumission
    form.addEventListener('submit', function (e) {
      let hasErrors = false;
      clearMessages();
      errorMessages = [];
  
      Object.keys(fields).forEach((fieldName) => {
        const input = form[fieldName];
        let isValid = true;
  
        if (fieldName === "date") {
          isValid = fields.date.validate(input.value);
        } else if (fieldName === "tarif") {
          input.value = input.value.replace('€', '').trim();
          isValid = fields.tarif.regex.test(input.value);
        } else {
          isValid = fields[fieldName].regex.test(input.value.trim());
        }
  
        if (!isValid) {
          errorMessages.push(fields[fieldName].error);
          hasErrors = true;
        }
      });
  
      if (hasErrors) {
        e.preventDefault();
        errorMessages.forEach(msg => showMessage(msg, true));
        showMessage("Veuillez corriger les erreurs ci-dessus.", true);
      }
    });
  
    restrictInput();
  });
  