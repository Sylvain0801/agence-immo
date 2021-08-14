// ####################################################################
// #                                                                  #
// #          Gestion burger et navigation menu principal             #
// #                                                                  #
// ####################################################################

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