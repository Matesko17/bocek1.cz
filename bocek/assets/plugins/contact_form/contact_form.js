import $ from 'jquery';

export function contact_form_ok() {
  $("#contact_form_loading").fadeOut();
  $(theme_vars.contact_form_vars.contact_form_element).children(".obsah").css("transition", "none").fadeOut("slow", function () {
    $("#contact_form_success_message").show();
  });
}

export function contact_form_error() {
  $("#contact_form_loading").fadeOut();
  $(theme_vars.contact_form_vars.contact_form_element).children(".obsah").css("transition", "none").fadeOut("slow", function () {
    $("#contact_form_error_message").show();
  });
}

export function contact_form_loading() {
  $(theme_vars.contact_form_vars.contact_form_element).children(".obsah").addClass("blur");
  $("#contact_form_loading").addClass("show");
}

export function contact_form_init() {
  $(document).ready(function () {
    $(theme_vars.contact_form_vars.contact_form_element).submit(function (event) {
      event.preventDefault();
      contact_form_loading();

      $.ajax({
        method: "POST",
        url: theme_vars.contact_form_vars.contact_form_folder + "ajax_formular.php",
        data: { jmeno: $("#input_jmeno").val(), email: $("#input_email").val(), telefon: $("#input_telefon").val(), zprava: $("#input_zprava").val() }
      })
        .done(function (message) {
          setTimeout(function () {
            if (message === "1") {
              contact_form_ok();
            } else {
              contact_form_error();
              console.error(message);
            }
          }, 1000);
        })
        .fail(function () {
          contact_form_error();
        });
    });
  });
}