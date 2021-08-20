window.onload = () => {
  // Gestion input file customisé
  const customInputFile = document.getElementById('document_add_edit_form_document')
  const inputFileContainer = document.getElementById('input-file-container')
  const maxSizeAllowed = document.getElementById('max-size-exceeded')
  const maxFilesAllowed = document.getElementById('max-files-exceeded')
  const buttonDownloadFiles = document.querySelector('.input-file-button')

  buttonDownloadFiles.addEventListener('click', () => {
    maxSizeAllowed.classList.remove('show')
    maxFilesAllowed.classList.remove('show')
    maxSizeAllowed.style.display = 'flex'
    maxFilesAllowed.style.display = 'flex'
  })

  customInputFile.addEventListener('change', function(e) {
    inputFileContainer.innerHTML = ""
    if (this.files) {
      inputFileContainer.innerHTML += `<span class="clear-input-file-content"><i class="fa fa-close"></i></span>`
      const clearButton = document.querySelector('.clear-input-file-content')
      clearButton.addEventListener('click', function(e) {
        e.preventDefault()
        inputFileContainer.innerHTML = ""
        customInputFile.value = "" 
      })
      for (let i = 0; i < this.files.length; i++) {
        if (this.files[i].size >= 2000000) {
          inputFileContainer.innerHTML = ""
          customInputFile.value = ""
          maxSizeAllowed.classList.add('show')
          break 
        } 
        if (this.files.length > parseInt(inputFileContainer.dataset.countCurrentFiles)){
          inputFileContainer.innerHTML = ""
          customInputFile.value = ""
          maxFilesAllowed.classList.add('show')
          break 
        }
      }
      let span = document.createElement('span')
      span.className = 'input-file-item'
      span.textContent = this.files[0].name
      inputFileContainer.insertAdjacentElement('afterbegin', span)
      }
    }
  )

  // Gestion en version edit 
  const deleteFileItems = document.querySelectorAll('.delete-current-file-item')
  const fetchRemoveImage = async (item, offer, img) => {
    await fetch(`/private-area/offer/delete/image/${offer}/${img}`)
    .then(res => {
      if (res.ok) {
        inputFileContainer.dataset.countCurrentFiles = parseInt(inputFileContainer.dataset.countCurrentFiles) + 1,
        item.parentNode.remove()
      } else {
        alert("Une erreur s'est produite, veuillez réessayer plus tard")
      }
    }
      )
    .catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
    };
    
    for (const item of deleteFileItems) {
      item.addEventListener('click', function(e) {
        e.preventDefault()
        fetchRemoveImage(this, this.dataset.offerId, this.dataset.imageId)
      }
    )
  }
}