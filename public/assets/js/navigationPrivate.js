// ####################################################################
// #                                                                  #
// #         Gestion burger et navigation menu espace privé           #
// #                                                                  #
// ####################################################################
const burger = document.getElementById('burger')
const navigation = document.getElementById('navigation')
const main = document.getElementById('private-area-main')

setTimeout(() => {
    document.querySelector('.logo-company-group').style.transition = 'color .1s .5s ease'
    document.querySelector('.show .logo-company-group').style.transition = 'color .1s .5s ease'
}, 500)

// if (window.innerWidth > 576) [navigation, main].forEach(elt => elt.classList.add('show'))
if (window.innerWidth < 576) main.classList.remove('show')

burger.addEventListener('click', (e) => {
    e.stopPropagation()
    navigation.classList.toggle('show')
    if (window.innerWidth > 576) main.classList.toggle('show')
})

window.addEventListener('resize', () => {
    if (window.innerWidth < 576) [navigation, main].forEach(elt => elt.classList.remove('show'))
})

document.addEventListener('click', () => {
    if (window.innerWidth < 576) [navigation, main].forEach(elt => elt.classList.remove('show'))
})