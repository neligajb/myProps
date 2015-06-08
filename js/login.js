$(document).ready(function() {
  var $registrationForm = $("#registration-form");

  $registrationForm.on('keyup', '#new-email', function () { //user types username in input field
    var username = $(this).val();     //get the string typed by user
    $.post('check_username.php', {'username': username}, function (data) {  //make ajax call to check_username.php
      $("#user-result").html(data); //dump the data received from PHP page
    });
  });

  $("#registration-button").on('click', function () {
    $("#registered-question").remove();
    $("#registration-form").append("<h2>Sign Up</h2><form><label for='new-email'>Email</label>" +
    "<input type='email' id='new-email' maxlength='50' required ><span id='user-result'></span><br>" +
    "<label for='new-name'>Name</label><input type='text' id='new-name' maxlength='50' required ><br>" +
    "<label for='new-password'>Password</label><input type='password' id='new-password' maxlength='20' required >" +
    "<br><input type='button' name='register' class='myButton' id='register' value='Submit'></form>");
  });


  $registrationForm.on('focus', '#new-email', function () {
    $('input[id="new-email"]').css({"border": "", "box-shadow": ""});
  });

  $registrationForm.on('focus', '#new-name', function () {
    $('input[id="new-name"]').css({"border": "", "box-shadow": ""});
  });

  $registrationForm.on('focus', '#new-password', function () {
    $('input[id="new-password"]').css({"border": "", "box-shadow": ""});
  });


  $registrationForm.on('click', '#register', function () {
    var emailP = $("#new-email").val();
    var nameP = $("#new-name").val();
    var passwordP = $("#new-password").val();

    //check for blanks
    if (emailP == '' || nameP == '' || passwordP == '') {
      $('input[id="new-email"], input[id="new-name"], input[id="new-password"]').css({"border": "2px #F7A8A8", "box-shadow": "0 0 3px red"});
      alert("Please complete all fields.");
      return;
    }

    if (!isEmail(emailP)) {
      alert("Please enter a valid email.");
      return;
    }

    if (!isValidName(nameP)) {
      alert("Please enter a valid name.");
      return;
    }

    if (passwordP.length < 6) {
      alert("Password must be 6 - 20 characters.");
      return;
    }

    $.post('register.php', {username1: emailP, name1: nameP, password1: passwordP}, function (data) {
      if (data == 'un-exists') {
        $('input[id="new-name"]').val('');
        $('input[id="new-password"]').val('');
      }
      else {
        $registrationForm.append('<p id="login-response"></p>');
        if (data == 'User Added.') {
          window.location.replace("http://web.engr.oregonstate.edu/~neliganj/cs290-finalProject/content.php");
        }
        $("#login-response").html(data); //dump the data received from PHP page
      }
    });
  });

  function isEmail(eml) {
    var regex = /^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(eml);
  }

  function isValidName(n) {
    var regex = /^[a-zA-Z][a-zA-Z \-'\.]+$/;
    return regex.test(n);
  }

  //scripts for existing user login

  var $login = $('#login');
  var $email = $("#email");
  var $password = $("#password");

  $login.on('click', function() {
    var emailP = $email.val();
    var passwordP = $password.val();

    if (emailP == '' || passwordP == '') {
      $email.css({"border": "2px #F7A8A8", "box-shadow": "0 0 3px red"});
      $password.css({"border": "2px #F7A8A8", "box-shadow": "0 0 3px red"});
      alert("Please complete all fields.");
      return;
    }

    $.post('check_existing_username.php', {username1: emailP, password1: passwordP}, function (data) {
      if (data == 'login') {
        window.location.replace("http://web.engr.oregonstate.edu/~neliganj/cs290-finalProject/content.php");
      }
      $('#login-inner-form').append('<span id="login-response1"></span>');
      $("#login-response1").html(data); //dump the data received from PHP page
    });

  });

  $email.on('focus', function () {
    $email.css({"border": "", "box-shadow": ""});
  });

  $password.on('focus', function () {
    $password.css({"border": "", "box-shadow": ""});
  });


});