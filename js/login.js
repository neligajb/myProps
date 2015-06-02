$("#registration-form").on('keyup', '#new-email', function() { //user types username in input field
  var username = $(this).val();     //get the string typed by user
  $.post('check_username.php', {'username':username}, function(data) {  //make ajax call to check_username.php
    $("#user-result").html(data); //dump the data received from PHP page
  });
});

$("#registration-button").on('click', function() {
  $("#registered-question").remove();
  $("#registration-form").append("<h2>Sign Up</h2><form><label for='new-email'>Email</label>" +
  "<input type='email' id='new-email' maxlength='50' required ><span id='user-result'></span><br>" +
  "<label for='name'>Name</label><input type='text' id='name' maxlength='50' required ><br>" +
  "<label for='new-password'>Password</label><input type='password' id='new-password' maxlength='20' required</form>" +
  "<br><input type='button' name='register' class='home-submit' id='register' value='Submit'></form>");
});

$("#registration-form").on('click', '#register', function() {
  var email = $("#new-email").val();
  var name = $("name").val();
  var password = $("#new-password").val();
  //check for blanks
  if (email == '' || name == '' || password == '') {
    $('input[id="new-email"], input[id="name"], input[id="new-password"]').css("border","2px #F7A8A8");
    $('input[id="new-email"], input[id="name"], input[id="new-password"]').css("box-shadow","0 0 3px red");
    alert("Please complete all fields.");
  }
});

$("#registration-form").on('focus', '#new-email', function() {
  $('input[id="new-email"]').css("border","");
  $('input[id="new-email"]').css("box-shadow","");
});

$("#registration-form").on('focus', '#name', function() {
  $('input[id="name"]').css("border","");
  $('input[id="name"]').css("box-shadow","");
});

$("#registration-form").on('focus', '#new-password', function() {
  $('input[id="new-password"]').css("border","");
  $('input[id="new-password"]').css("box-shadow","");
});