// ####################################################################
// #                                                                  #
// #                Active/déactive les annonces                      #
// #                                                                  #
// ####################################################################
const redirectPath = document.querySelector('body').dataset.path
const switchButtons = document.querySelectorAll('.toggle-switch-button input[type=checkbox]')
const fetchActiveOffer = async (id) => {
  await fetch(`${redirectPath}/private-area/offer/active/${id}`)
  .then(res => {return !res.ok ? alert("Une erreur s'est produite, veuillez réessayer plus tard") : res})
  .catch((error) => alert("Une erreur s'est produite, veuillez réessayer plus tard"));
  };
for (const btn of switchButtons) {
  btn.addEventListener('click', function(e) {
    fetchActiveOffer(this.dataset.id);
  })
}
