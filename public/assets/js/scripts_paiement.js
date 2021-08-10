window.onload = () => {
  // Récupère l'élement button
  let cardButton = document.getElementById("card-button");
  let modalButton = document.getElementById("display-modal-confirm-payment")

  // Modifie l'intention de paiement en fonction du bouton radio selectionné (annuel ou mensuel)
  paymentPeriods = document.querySelectorAll("[name=payment_periodicity]");
  for (const period of paymentPeriods) {
    period.addEventListener("click", function (e) {
      fetchPaymentPeriods(this.value);
    });
  }
	
  const fetchPaymentPeriods = async (price) => {
		intent = await fetch(`/payment/intention/${price}`)
		.then((res) => res.json())
		.catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
		cardButton.dataset.secret = intent.client_secret
  };

  // On instancie Stripe et on lui passe notre clé publique
  let stripe = Stripe(
    "pk_test_51IhWOpHIGsc4DWz6Z6M7I4MSWZti9X4hcSWm6mU0E9wIKvpFq5T2HLXgf698Edv8OMAgbaNJJQWsQBBbo84cto2500zn2xq8eO"
  );

  // Initialise les éléments du formulaire
  let elements = stripe.elements();

  // Définit la redirection en cas de succès du paiement
  let redirect = "/payment/success";

  // Récupère l'élément qui contiendra le nom du titulaire de la carte
  let cardholderName = document.getElementById("cardholder-name");

  // Crée les éléments de carte et les stocke dans la variable card
  let card = elements.create("card");

  card.mount("#card-element");

  card.addEventListener("change", function (event) {
    // On récupère l'élément qui permet d'afficher les erreurs de saisie
    let displayError = document.getElementById("card-errors");

    // Si il y a une erreur
    if (event.error) {
      // On l'affiche
      displayError.textContent = event.error.message;
			modalButton.removeAttribute('data-target')
    } else {
      // Sinon on l'efface
      displayError.textContent = "";
			modalButton.setAttribute('data-target', '#modal-confirm-payment')
    }
  });

  cardButton.addEventListener("click", () => {
    // Récupère l'attribut data-secret du bouton
    let clientSecret = cardButton.dataset.secret;

    // On envoie la promesse contenant le code de l'intention, l'objet "card" contenant les informations de carte et le nom du client
    stripe
      .handleCardPayment(clientSecret, card, {
        payment_method_data: {
          billing_details: { name: cardholderName.value },
        },
      })
      .then(function (result) {
        // Quand on reçoit une réponse
        if (result.error) {
          // Si on a une erreur, on l'affiche
          document.getElementById("errors").innerText = result.error.message;
        } else {
          // Sinon on redirige l'utilisateur
          document.location.href = redirect;
        }
      });
  });
};
