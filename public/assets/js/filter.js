// ####################################################################
// #                                                                  #
// #                Gestion filtres biens et annonces                 #
// #                                                                  #
// ####################################################################

window.onload = () => {

  // Affiche/masque le menu de filtre
  const mainButtonFilter = document.getElementById('main-button-filter')
  const filterListGroup = document.getElementById('filter-list-group')
  mainButtonFilter.addEventListener('click', (e) => {
    e.stopPropagation()
    filterListGroup.classList.toggle('show')
  })

  // Affiche/masque les sous menus
  const checkboxFirstLevel = document.querySelectorAll("[data-header] + label")
  for (const checkbox of checkboxFirstLevel) {
    checkbox.addEventListener('click', function(e) {
      e.stopPropagation()
      this.parentNode.nextElementSibling.classList.toggle('show')
    })
  }
}