<?php header("Content-type: application/javascript"); ?>

<?php session_start(); ?>

$(document).ready(function() {
  var userID = <?php echo $_SESSION['userID'] ?>;
  var userDataString = null;

  $.post('content_data.php', {userID_p: userID}, function (data) {
    userDataString = data;
  });

  var obj = jQuery.parseJSON(userDataString);
  alert (obj.personalData.name);

});