window.onload = () => {
  const inputRadius = document.querySelector('#search_form_radius')
  document.querySelector('i.fa.fa-plus-circle').addEventListener('click', () => { if(inputRadius.value < 150) inputRadius.value = parseInt(inputRadius.value) + 5} )
  document.querySelector('i.fa.fa-minus-circle').addEventListener('click', () => { if(inputRadius.value > 5) inputRadius.value = parseInt(inputRadius.value) - 5} )
}