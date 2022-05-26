// ! reservation_filter_course.js for reservation.php

/**
 * It loops through the select options and checks if the value of the select option is equal to the
 * value of the faculty. If it is, it shows the option, if not, it hides it
 * @param elem - The faculty select element.
 * @param select - The select element that you want to filter.
 */
function filter(elem, select) {
  /* Checking if the value of the faculty is empty. If it is, it disables the select element, if not, it
  enables it. */
  if (elem.value != "") {
    /* Enabling the select element. */
    select.disabled = false;
  } else {
    /* Disabling the select element. */
    select.disabled = true;
  }

  /* It loops through the select options and checks if the value of the select option is equal to the
    value of the faculty. If it is, it shows the option, if not, it hides it. */
  for (var i = 0; i < select.length; i++) {
    /* Checking if the value of the select option is equal to the value of the faculty. If it is, it
    shows the option, if not, it hides it. */
    if (select.options[i].getAttribute("match") == elem.value) {
      /* Showing the option. */
      select.options[i].style.display = "block";
    } else {
      /* Hiding the option. */
      select.options[i].style.display = "none";
    }
  }
}

function filter_on_edit() {
  /* A function that is called after 20 milliseconds. */
  setTimeout(function () {
    /* It loops through the select options and checks if the value of the select option is equal to the
    value of the faculty. If it is, it shows the option, if not, it hides it. */
    filter(
      /* Selecting the faculty select element. */
      document
        .querySelector("#Reservation_form_edit")
        .querySelector("#faculty_select"),
      /* Selecting the course select element. */
      document
        .querySelector("#Reservation_form_edit")
        .querySelector("#course_select")
    );
  }, 20);
}
