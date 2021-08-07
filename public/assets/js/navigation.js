// Gestion menu burger et navigation
const burger = document.getElementById('burger')
const navigation = document.getElementById('navigation')
const dropdowns = document.querySelectorAll('.navigation-dropdown')
const closeAllMenu = () => {
    navigation.classList.remove('show')
    for(let drop of dropdowns) {
        drop.classList.remove('show')
    }
}
burger.addEventListener('click', (e) => {
    e.stopPropagation()
    navigation.classList.toggle('show')
    if (navigation.classList.contains('show')) {
        document.querySelector('.search-form-group.show').style.overflow = 'hidden'
        document.querySelector('.search-form-group').classList.remove('show')
    }
    for(let drop of dropdowns) {
        drop.classList.remove('show')
    }
})

for(let drop of dropdowns) {
    drop.addEventListener('click', function(e) {
        e.stopPropagation()
        for(let drop of dropdowns) {
            if (drop != this) drop.classList.remove('show')
        }
        this.classList.toggle('show')
    })
}
document.addEventListener('click', closeAllMenu)
window.addEventListener('resize', closeAllMenu)

// Ferme les messages alert
const alertClose = document.querySelectorAll("[data-dismiss=alert]");
for (let close of alertClose) {
  close.addEventListener("click", function (e) {
    e.preventDefault();
    this.parentNode.style.display = "none";
  });
}


// Gestion des favoris
const buttonFavorite = document.querySelectorAll('i.card-button-favorite')
const displayAllFavorite = document.getElementById('button-favorite-list')
for (const btn of buttonFavorite) {
    if (localStorage.getItem('favorites')) {
        let favorite = localStorage.getItem('favorites').split(',')
        favorite.indexOf(btn.dataset.id) > -1 && (btn.className = 'card-button-favorite fa fa-star')
        displayAllFavorite.href = '/offer/favorite/' + favorite.join(',')
    }
    btn.addEventListener('click', function(e) {
        if (localStorage.getItem('favorites')) {
            let favorite = localStorage.getItem('favorites').split(',')
            let index = favorite.indexOf(this.dataset.id)
            if (index > -1) {
                favorite.splice(index, 1)
                this.className = 'card-button-favorite fa fa-star-o'
                displayAllFavorite.href = '/offer/favorite/' + favorite.join(',')
            } else {
                favorite.push(this.dataset.id)
                this.className = 'card-button-favorite fa fa-star'
                displayAllFavorite.href = '/offer/favorite/' + favorite.join(',')
            }
            localStorage.setItem('favorites', favorite.join(','))
        } else {
            localStorage.setItem('favorites', this.dataset.id)
            this.className = 'card-button-favorite fa fa-star'
        }
    })
}