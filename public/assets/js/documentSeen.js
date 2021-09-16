// const redirectPath = document.querySelector('body').dataset.path
const buttonDocSeens = document.querySelectorAll('.button-document-seen')
const fetchMessageRead = async (item, id) => {
  await fetch(`${redirectPath}/private-area/document/seen/${id}`)
  .then(res => {
    if (res.ok) {
      item.classList.contains('fa-file-o') ? item.className = 'fa fa-file' : item.className = 'fa fa-file-o'
    } else {
      alert("Une erreur s'est produite, veuillez réessayer plus tard")
    }
  })
  .catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
  };
  
for (const btn of buttonDocSeens) {
  btn.addEventListener('click', function(e) {
    e.preventDefault()
    fetchMessageRead(this.children[0], this.dataset.id)
    }
  )
}