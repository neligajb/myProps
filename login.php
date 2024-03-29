<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('session.save_path', '../sessions');
session_start();
if ((isset($_SESSION['userID']))) {
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  header("Location: {$redirect}/content.php", true);
  die();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>MyProps :: login</title>
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <h1>Welcome to MyProps</h1>
  <div id="login-form" class="home-form">
    <form id="login-inner-form">
      <h2>Login</h2>
      <label for="email">Email</label>
      <input type="email" name="demail" id="email"><br>
      <label for="password">Password</label>
      <input type="password" name="password" id="password"><br>
      <input type="button" name="login" class="myButton" id="login" value="Login">
    </form>
  </div>
  <p id="registered-question">
    Not a registered user?
    <button id="registration-button" class="myButton">Sign Up</button>
  </p>
  <div id="registration-form" class="home-form">
    <!-- dynamic content from scripts.js -->
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/login.js"></script>
</body>
</html>