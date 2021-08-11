window.onload = () => {
  // Gestion api proposition de ville en fonction des lettres tapées
  const searchInput = document.getElementById('property_location')
  const cityList = document.getElementById('city-list-group')

  let cities
  let searchTerm = ''

  const fetchCities = async() => {
    cities = await fetch(
      `https://geo.api.gouv.fr/communes?nom=${searchTerm}&fields=departement,codesPostaux,centre&boost=population&limit=100`)
      .then(res => res.json())
      .catch(error => alert('Une erreur s\'est produite, veuillez réessayer plus tard'))
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
      cityItem.addEventListener('click', () => changeSearchInputValue(cityItem.textContent))
    }
  }

  searchInput.addEventListener('input', (e) => {
    searchTerm = e.target.value
    showCities()
  })

  function changeSearchInputValue(cityName) {
    searchInput.value = cityName
    cityList.innerHTML = ""
  }
}