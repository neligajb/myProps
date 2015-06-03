<?php
session_start();

if (!isset($_SESSION['userID'])) {
  $_SESSION = array();
  session_destroy();
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  header("Location: {$redirect}/login.php", true);
  echo('died');
}
?>

<?php
echo $_SESSION['userID'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>MyProps :: Welcome</title>
  <link href="styles.css" rel="stylesheet">
</head>
  <body>

  <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
  <script type="text/javascript" src="js/content.js"></script>
  </body>
</html>
