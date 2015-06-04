<?php
include 'content_data.php';
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['userID'])) {
  $_SESSION = array();
  session_destroy();
  $filePath = explode('/', $_SERVER['PHP_SELF'], -1);
  $filePath = implode('/', $filePath);
  $redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
  header("Location: {$redirect}/login.php", true);
  die();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>MyProps :: Welcome</title>
  <link href="styles.css" rel="stylesheet">
</head>
  <body>
  <header>
    <form action="logout.php" method="post"><input type="submit" value="Logout"></form>
    <h1 class="title">MyProps</h1>
  </header>
    <h2 id="welcome">Welcome </h2>
    <table id="listing-data">
      <!-- dynamic content -->
    </table>
  </body>
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/content.js.php"></script>
</html>
