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
</head>
<body>

  <h3>Login</h3>
  <div id="registration-form">
    <form>
      <label for="username">Enter Username :  </label>
      <input type="text" id="username" maxlength="50" />
      <span id="user-result"></span>
    </form>
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
  </div>
<?php
  if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
  echo 'We don\'t have mysqli!!!';
  } else {
  echo 'Phew we have it!';
  } ?>
</body>
</html>