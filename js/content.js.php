<?php header("Content-type: application/javascript"); ?>

<?php session_start(); ?>

$(document).ready(function() {
  var userID = <?php echo $_SESSION['userID'] ?>;

  $.post('content_data.php', {userID_p: userID}, function (data) {
    if (data == 'shit') {
      alert('shit');
    }
    else {
      var obj = jQuery.parseJSON(data);
      $('#welcome').append(obj.personalData.name);
    }
  });



});