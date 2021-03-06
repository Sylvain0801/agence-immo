// ####################################################################
// #                                                                  #
// #                       Gestion des favoris                        #
// #                                                                  #
// ####################################################################
const redirectPath = document.querySelector('body').dataset.path
const buttonFavorite = document.querySelectorAll('i.card-button-favorite')
const displayAllFavorite = document.getElementById('button-favorite-list')
const favoritePath = `${redirectPath}/offer/favorite/`
if (localStorage.getItem('favorites')) {
    let favorite = localStorage.getItem('favorites').split(',')
    displayAllFavorite.href = favoritePath + encodeURIComponent(favorite.join(','))
}
for (const btn of buttonFavorite) {
    if (localStorage.getItem('favorites')) {
        favorite = localStorage.getItem('favorites').split(',')
        favorite.indexOf(btn.dataset.id) > -1 && (btn.className = 'card-button-favorite fa fa-star')
        displayAllFavorite.href = favoritePath + encodeURIComponent(favorite.join(','))
    }
    btn.addEventListener('click', function(e) {
        if (localStorage.getItem('favorites')) {
            let favorite = localStorage.getItem('favorites').split(',')
            let index = favorite.indexOf(this.dataset.id)
            if (index > -1) {
                favorite.splice(index, 1)
                this.className = 'card-button-favorite fa fa-star-o'
                displayAllFavorite.href = favoritePath + encodeURIComponent(favorite.join(','))
            } else {
                favorite.push(this.dataset.id)
                this.className = 'card-button-favorite fa fa-star'
                displayAllFavorite.href = favoritePath + encodeURIComponent(favorite.join(','))
            }
            localStorage.setItem('favorites', favorite.join(','))
        } else {
            localStorage.setItem('favorites', this.dataset.id)
            this.className = 'card-button-favorite fa fa-star'
            displayAllFavorite.href = favoritePath + this.dataset.id
        }
    })
}

// ####################################################################
// #                                                                  #
// #                       Gestion des modales                        #
// #                                                                  #
// ####################################################################

const modalButtons = document.querySelectorAll("[data-toggle=modal]");
for (let button of modalButtons) {
    button.addEventListener("click", function(e) {
    e.preventDefault();
    // On r??cup??re le data-target
    let target = this.dataset.target;

    // On renseigne les champs variables de la modale
      // Modale carousel image
      if (target === '#modal-property-img-view') {
        let id = this.dataset.id
        document.querySelector(`[data-bs-slide-to="${id}"]`).className = 'active';
        document.querySelector(`[data-bs-slide-to="${id}"]`).setAttribute('aria-current', 'true');

        document.querySelectorAll(`[data-carousel-item]`).forEach( item => item.className = 'carousel-item')
        document.querySelector(`[data-carousel-item="${id}"]`).classList.add('active');
      }
      // Modale confirmer la suppression
      if (target === '#modal-confirm-delete') {
        document.querySelector(target + ' span.span-item-id').textContent = this.dataset.id
        document.getElementById('delete-button').href = this.dataset.path
        }
      // Modale confirmer la suppression des documents
      if (target === '#modal-confirm-delete-document') {
        document.querySelector('[name=delete_document_token]').value = this.dataset.token
        document.getElementById('delete-button').setAttribute('formaction', this.dataset.path)
        }
      // Modale confirmer la suppression des rappels
      if (target === '#modal-confirm-delete-remind') {
        document.querySelector(target + ' span.span-item-id').textContent = this.dataset.message
        document.getElementById('delete-button').setAttribute('formaction', this.dataset.path)
      }
      // Modale gestion des locataires
      if (target === '#modal-confirm-manage-tenant') {
        document.getElementById('continue-button').href = this.dataset.path
      }
		
    // On r??cup??re la bonne modale
    let modal = document.querySelector(target);

    // On affiche la modale
    setTimeout(() => modal.classList.add("show"), 200);

    // On r??cup??re les boutons de fermeture
    const modalClose = modal.querySelectorAll("[data-dismiss=dialog]");

    for (let close of modalClose) {
            close.addEventListener("click", (e) => {
            e.preventDefault()
            modal.classList.remove("show");
        });
    }

    // On g??re la fermeture lors du clic sur la zone grise
    modal.addEventListener("click", function () {
        this.classList.remove("show");
    });
    // On ??vite la propagation du clic d'un enfant ?? son parent
    modal.children[0].addEventListener("click", function (e) {
    e.stopPropagation();
        });
    });
}

// ####################################################################
// #                                                                  #
// #                  Gestion des modales popup                       #
// #                                                                  #
// ####################################################################

const modalPopups = document.querySelectorAll("[data-modal=popup]");
for (let popup of modalPopups) {
   
    // On r??cup??re les boutons de fermeture
    const popupClose = popup.querySelectorAll("[data-dismiss=popup]");

    for (let close of popupClose) {
            close.addEventListener("click", (e) => {
            e.preventDefault()
            popup.classList.remove("show");
        });
    }

    // On g??re la fermeture lors du clic sur la zone grise
    popup.addEventListener("click", function () {
        this.classList.remove("show");
    });

    // On ??vite la propagation du clic d'un enfant ?? son parent
    popup.children[0].addEventListener("click", function (e) {
    e.stopPropagation();
    });
}

// ####################################################################
// #                                                                  #
// #                    Toggle r??ponses FAQ                           #
// #                                                                  #
// ####################################################################

const displayAnswer = document.querySelectorAll('[data-toggle=chevron-faq]')

for (const btn of displayAnswer) {
    btn.addEventListener('click', function(e) {
        e.stopPropagation()
        this.classList.toggle('show')
        this.parentNode.classList.toggle('show')
        this.parentNode.nextElementSibling.classList.toggle('show')
    })
}


// ####################################################################
// #                                                                  #
// #                   Fermeture message alert                        #
// #                                                                  #
// ####################################################################

const alertClose = document.querySelectorAll("[data-dismiss=alert]");
for (let close of alertClose) {
  close.addEventListener("click", function (e) {
    e.preventDefault();
    this.parentNode.style.display = "none";
  });
}