window.onload = () => {
  
  // Gestion input file customisé
  const customInputFile = document.getElementById('offer_add_edit_form_images')
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
      for (let i = 0; i < this.files.length; i++) {
          var reader = new FileReader();
          reader.onload = function (e) {
            let span = document.createElement('span')
            span.className = 'input-file-item'
            span.style.backgroundImage = `url(${e.target.result})`
            inputFileContainer.insertAdjacentElement('afterbegin', span)
          }
          reader.readAsDataURL(e.target.files[i]) 
        }
      }
    }
  )

  // Gestion en version edit 
  const deleteFileItems = document.querySelectorAll('.delete-current-file-item')
  const fetchRemoveImage = async (offer, img) => {
    await fetch(`/private-area/offer/delete/image/${offer}/${img}`)
    .then(
      inputFileContainer.dataset.countCurrentFiles = parseInt(inputFileContainer.dataset.countCurrentFiles) + 1,
      )
      .catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
    };
    
    for (const item of deleteFileItems) {
      item.addEventListener('click', function(e) {
        e.preventDefault()
        fetchRemoveImage(this.dataset.offerId, this.dataset.imageId)
        this.parentNode.remove()
      }
    )
  }
}