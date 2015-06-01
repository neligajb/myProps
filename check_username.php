<?php
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["username"]))
{

  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die();
  }

  //connect to database
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }
  else {
    echo "Connection worked!<br>";
  }


  //received username value from registration page
  $username =  $_POST["username"];

  //trim and lowercase username
  $username =  strtolower(trim($_POST["username"]));

  //sanitize username
  $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

  //check if username is in db
  if (!($stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $username)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $username_exists = NULL;

  if (!$stmt->bind_result($username_exists)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();

  mysqli_close($mysqli);

  //if returned value is not null, username is not available
  if($username_exists) {
    die('<img src="imgs/red-x.png" />');
  }else{
    die('<img src="imgs/green-check.png" />');
  }
}

