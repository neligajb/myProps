$("#username").keyup(function () { //user types username in input field
  alert('pressed a key');
  var username = $(this).val();     //get the string typed by user
  $.post('check_username.php', {'username':username}, function(data) {  //make ajax call to check_username.php
    $("#user-result").html(data); //dump the data received from PHP page
  });
});