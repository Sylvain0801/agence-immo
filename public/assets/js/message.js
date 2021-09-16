window.onload = () => {
  const redirectPath = document.querySelector('body').dataset.path
  const buttonMsgReads = document.querySelectorAll('.button-msg-read')
  const fetchMessageRead = async (item, id) => {
    await fetch(`${redirectPath}/private-area/message/read/${id}`)
    .then(res => {
      if (res.ok) {
        item.classList.contains('fa-envelope-open-o') ? item.className = 'fa fa-envelope' : item.className = 'fa fa-envelope-open-o'
      } else {
        alert("Une erreur s'est produite, veuillez réessayer plus tard")
      }
    })
    .catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
    };
    
  for (const btn of buttonMsgReads) {
    btn.addEventListener('click', function(e) {
      e.preventDefault()
      fetchMessageRead(this.children[0], this.dataset.id)
      }
    )
  }
}