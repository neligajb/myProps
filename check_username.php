<?php
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["username"]))
{

  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die();
  }
  global $db_password;
  global $db_address;
  global $db_user;
  global $db_name;

  //connect to database
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }
//  else {
//    echo "Connection worked!<br>";
//  }


  //trim and lowercase username
  $username =  strtolower(trim($_POST["username"]));

  //sanitize username
  $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

  //check if username is in db
  if (!($stmt = $mysqli->prepare("SELECT userID FROM users WHERE email = ?"))) {
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

  function isEmail($n) {
    return preg_match('/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $n);
  }

  //if returned value is not null, username is not available
  if ($username_exists && isEmail($username)) {
    die('<img src="imgs/red-x.png" width="20" class="user-warn"/><span id="un-exists">account already exists</span>');
  }
  else if(!isEmail($username)) {
    die('<img src="imgs/red-x.png" width="20" class="user-warn"/><span id="un-exists">invalid email</span>');
  }
  else {
    die('<img src="imgs/green-check.png" width="20" class="user-warn"/>');
  }
}

