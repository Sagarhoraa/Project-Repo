const form = document.querySelector('form');
const username = document.getElementById('username');
const password = document.getElementById('password');
const errorMessage = document.getElementById('error-message');


form.addEventListener("submit", (e) => {
  const errors = [];

  if(username.value.trim() === ""){
    errors.push("Username is required");
  }
  if(password.value.length <4){
    errors.push("Password must be greater than 4 characters")
  }
  if(errors.length >0){
    e.preventDefault();
    errorMessage.hidden = false;
    errorMessage.innerHTML = errors.join(', ');
    
  }
})
username.addEventListener("input", () => {
  errorMessage.textContent = "";
  errorMessage.hidden = true;
});

password.addEventListener("input", () => {
  errorMessage.textContent = "";
  errorMessage.hidden = true;
});
