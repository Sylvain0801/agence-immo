window.onload = () => {
  let password = document.getElementById("registration_form_plainPassword")
  let confirmPassword = document.getElementById("registration_form_confirmPassword");
  let agreeTerms = document.querySelector("#registration_form_agreeTerms");
  function validatePassword(){
    if(password.value != confirmPassword.value) {
      confirmPassword.setCustomValidity("Les mots de passe ne correspondent pas !");
    } else {
      password.setCustomValidity('');
      confirmPassword.setCustomValidity('');
    }
  }
  
  if(!agreeTerms.checked) agreeTerms.setCustomValidity("Vous devez accepter les conditions d'utilisation !")
  password.onchange = validatePassword;
  confirmPassword.onkeyup = validatePassword;
  agreeTerms.onclick = () => {
    if(!agreeTerms.checked) {
      agreeTerms.setCustomValidity("Vous devez accepter les conditions d'utilisation !")
    } else {
      agreeTerms.setCustomValidity('')
    }
  }
}