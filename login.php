<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
//much of the following block of session handling code is borrowed from the "PHP Sessions" lecture
if ((isset($_SESSION['username']))) {
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  header("Location: {$redirect}/props.php", true);
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
  <div id="login-form" class="home-form">
    <form>
      <h2>Login</h2>
      <label for="email">Email</label>
      <input type="email" name="demail" id="email"><br>
      <label for="password">Password</label>
      <input type="password" name="password" id="password"><br>
      <input type="button" name="login" class="home-submit" id="login" value="Login">
    </form>
  </div>
  <p id="registered-question">
    Not a registered user?
    <button id="registration-button">Sign Up</button>
  </p>
  <div id="registration-form" class="home-form">
    <!-- dynamic content from scripts.js -->
  </div>
  <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>