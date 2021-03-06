window.onload = () => {
  // Gestion bouton + et - rayon
  const inputRadius = document.querySelector('#search_form_radius')
  document.querySelector('i.fa.fa-plus-circle').addEventListener('click', () => { 
    if (inputRadius.value === "") {
      inputRadius.value = 5
    } else if (inputRadius.value < 150) {
      inputRadius.value = parseInt(inputRadius.value) + 5
    } 
  })
  document.querySelector('i.fa.fa-minus-circle').addEventListener('click', () => { if(inputRadius.value > 5) inputRadius.value = parseInt(inputRadius.value) - 5} )
  
  // Affiche/masque le formulaire en version mobile
  const searchButton = document.getElementById('search-button')
  const formGroup = document.querySelector('.search-form-group')
  if (window.innerWidth < 768) { 
    searchButton.classList.add('show') 
    formGroup.classList.remove('show')
  } else if (window.innerWidth > 768) {
    searchButton.classList.remove('show')
    formGroup.classList.add('show')
  }
  let currentWidth = window.innerWidth
  window.addEventListener('resize', () => {
    if (window.innerWidth !== currentWidth) {
      if (window.innerWidth < 768) { 
        searchButton.classList.add('show') 
        formGroup.classList.remove('show')
      } else if (window.innerWidth > 768) {
        searchButton.classList.remove('show')
        formGroup.classList.add('show')
      }
      currentWidth = window.innerWidth
    }
  })
  searchButton.addEventListener('click', function(e) {
    e.stopPropagation()
    if(formGroup.classList.contains('show')) {
      document.querySelector('.search-form-group.show').style.overflow = 'hidden'
      formGroup.classList.remove('show')
    } else {
      formGroup.classList.add('show')
      closeAllMenu()
    }
  })

  // Gestion checkbox vente & location
  const groupSell = [checkboxSell, inputSellMin, inputSellMax] = [
    document.querySelector('#search_form_sell + label'),
    document.getElementById('search_form_sell_price_min'),
    document.getElementById('search_form_sell_price_max')
  ]
  const groupRent = [checkboxRent, inputRentMin, inputRentMax] = [
    document.querySelector('#search_form_rent + label'),
    document.getElementById('search_form_rent_price_min'),
    document.getElementById('search_form_rent_price_max')
  ]
  function disabledSellOrRent(checkbox, group) {
    if (checkbox.previousElementSibling.checked) {
      group.forEach(elt => {
        elt.setAttribute('disabled', 'disabled')
        elt.style.filter = 'brightness(.8)'
      })
      group[0].previousElementSibling.setAttribute('disabled', 'disabled')
    } else if (!checkbox.previousElementSibling.checked){
      group.forEach(elt => {
        elt.removeAttribute('disabled')
        elt.style.filter = 'none'
      })
      group[0].previousElementSibling.removeAttribute('disabled')
    }
  }

  if (checkboxSell.previousElementSibling.checked) {
      disabledSellOrRent(checkboxSell, groupRent)
  }
  if (checkboxRent.previousElementSibling.checked) {
      disabledSellOrRent(checkboxRent, groupSell)
  }

  checkboxSell.addEventListener('click', function(e) {
    e.stopPropagation()
    setTimeout(() => disabledSellOrRent(this, groupRent), 200)
  })

  checkboxRent.addEventListener('click', function(e) {
    e.stopPropagation()
    setTimeout( () => disabledSellOrRent(this, groupSell), 200)
  })


  // Gestion api proposition de ville en fonction des lettres tap??es
  const searchInput = document.getElementById('search_form_city')
  const cityList = document.getElementById('city-list-group')
  const cityCoord = document.getElementById('search_form_coord')

  let cities
  let searchTerm = ''

  const fetchCities = async() => {
    cities = await fetch(
      `https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=departement,codesPostaux,centre&boost=population&limit=100`)
      .then(res => res.json())
      .catch(error => alert('Une erreur s\'est produite, veuillez r??essayer plus tard'))
  }
  
  const showCities = async() => {
    await fetchCities()
    cityList.innerHTML = (
      cities
        .map(city => (
          city.centre &&
          `<div class="city-list-item" data-coord=${city.centre.coordinates.join(',')}>${city.codesPostaux[0]}&nbsp;${city.nom}</div>`
        )).join('')
    )
    for (const cityItem of cityList.children) {
      cityItem.addEventListener('click', () => changeSearchInputValue(cityItem.textContent, cityItem.dataset.coord))
    }
  }

  searchInput.addEventListener('input', (e) => {
    searchTerm = e.target.value
    document.querySelector('.search-form-group.show').style.overflow = 'visible'
    showCities()
  })

  function changeSearchInputValue(cityName, coord) {
    searchInput.value = cityName
    cityCoord.value = coord
    cityList.innerHTML = ""
    document.querySelector('.search-form-group.show').style.overflow = 'hidden'
  }
  
  document.addEventListener('click', function() {
    cityList.innerHTML = ""
    document.querySelector('.search-form-group.show').style.overflow = 'hidden'
  })
}