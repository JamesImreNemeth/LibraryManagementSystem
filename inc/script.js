// Carousel Section
const buttons = document.querySelectorAll("[data-carousel-button]") // Gets all the buttons with data-carousel

buttons.forEach(button => {
  button.addEventListener("click", () => { // loops through all the buttons and adds a click event
    const offset = button.dataset.carouselButton === "next" ? 1 : -1 // If the img = next, add one, if not then add -1
    const slides = button
      .closest("[data-carousel]")
      .querySelector("[data-slides]") // Gets all the slides from the HTML

    const activeSlide = slides.querySelector("[data-active]") //Finds the active slide with the data-active attribute
    let newIndex = [...slides.children].indexOf(activeSlide) + offset // finds the new index of the active slide depending if it was prev or next
    if (newIndex < 0) newIndex = slides.children.length - 1 // If we back, then go to the last image
    if (newIndex >= slides.children.length) newIndex = 0 // If we got past the last slide, go back to the start

    slides.children[newIndex].dataset.active = true // The next image that is selected will become active
    delete activeSlide.dataset.active // The old image will delete its active status attribute
  })
})

function validateForm() {
    // Get form inputs
    var firstName = document.forms["registrationForm"]["firstName"].value;
    var lastName = document.forms["registrationForm"]["lastName"].value;
    var email = document.forms["registrationForm"]["email"].value;
    var password = document.forms["registrationForm"]["password"].value;
    var retypePassword = document.forms["registrationForm"]["retypePassword"].value;

    // Validate first name and last name (letters only)
    var nameRegex = /^[a-zA-Z]+$/;
    if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
        alert("First name and last name must contain only letters.");
        return false;
    }

    // Validate email format
    var emailRegex = /\S+@\S+\.\S+/;
    if (!emailRegex.test(email)) {
        alert("Invalid email format.");
        return false;
    }

    // Validate password (at least 6 characters and contains 1 number)
    var passwordRegex = /^(?=.*\d).{6,}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must be at least 6 characters long and contain at least 1 number.");
        return false;
    }

    // Validate password match
    if (password !== retypePassword) {
        alert("Passwords do not match.");
        return false;
    }

    return true;
}

function openPage(){
  var search = document.getElementById("search-bar").value;

  if (search === "home"){
    window.open("index.php");
  } 
  if (search === "register"){
    window.open("registration.php");
  }
  if (search === "login"){
    window.open("login.php");
  }
  if (search === "browse"){
    window.open("browse.php");
  }
  if (search === "borrow"){
    window.open("borrow_form.php");
  }
  if (search === "return"){
    window.open("return_form.php");
  }
}
