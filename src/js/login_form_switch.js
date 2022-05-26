// ! login_form_switch.js for login.php

/* Getting the element with the id of 'card' and storing it in a variable called 'card'. */
var card = document.getElementById("card");

/**
 * When the user clicks on the card, rotate the card 180 degrees.
 */
function openNext() {
  card.style.transform = "rotateY(-180deg)";
}

/**
 * When the user clicks the button with the id of 'prev', the card will rotate back to its original
 * position.
 */
function openPrev() {
  card.style.transform = "rotateY(0deg)";
}
