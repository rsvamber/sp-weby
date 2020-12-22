$(document).ready(function () {
  
  // kontrola, zda je heslo identicke
  $("#pw1").on("keyup", function () {
    if ($(this).val() != $("#pw2").val()) {
      $("#pw2").removeClass("valid").addClass("invalid");
      $(':button[type="submit"]').prop('disabled', true);
    } else {
      $("#pw2").removeClass("invalid").addClass("valid");
      $(':button[type="submit"]').prop('disabled', false);

    }
  });

  $("#pw2").on("keyup", function () {
    if ($("#pw1").val() != $(this).val()) {
      $(this).removeClass("valid").addClass("invalid");
      $(':button[type="submit"]').prop('disabled', true);

    } else {
      $(this).removeClass("invalid").addClass("valid");
      $(':button[type="submit"]').prop('disabled', false);

    }
  });
    // pro zvyrazneni aktivni polozky v navbaru
    $('li.active').removeClass('active');
    $('a[href="' + location.pathname + '"]').closest('li').addClass('active'); 

});