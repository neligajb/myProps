<?php
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

function isEmail($n) {
  return preg_match('/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $n);
}

function validName($n) {
  return preg_match("/^[a-zA-Z][a-zA-Z -@'@.]+$/", $n);
}

if (isset($_POST["username1"]) && isset($_POST["name1"]) && isset($_POST["password1"])) {

  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('No POST request.');
  }

  if (!isEmail($_POST["username1"]) || strlen($_POST["username1"]) > 50) {
    die('Invalid Email');
  }

  if (!validName($_POST["name1"]) || strlen($_POST["name1"]) > 50) {
    die('Invalid name.');
  }

  if (strlen($_POST["password1"]) < 6 || strlen($_POST["password1"]) > 20) {
    die('Invalid password.');
  }

  //connect to database
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }

  //trim and lowercase username
  $username =  strtolower(trim($_POST["username1"]));

  //sanitize entries
  $username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $password = filter_var($_POST["password1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $name = filter_var($_POST["name1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

  //check if username is in db
  if (!($stmt = $mysqli->prepare("SELECT userID FROM test.users WHERE email = ?"))) {
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

  if ($username_exists) {
    mysqli_close($mysqli);
    die('There is already a user with this email address.');
  }
  //add a new entry
  else {
    if (!($stmt = $mysqli->prepare("INSERT INTO test.users (email, password, name) VALUES (?,?,?)"))) {
      echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    if (!$stmt->bind_param("sss", $username, $password, $name)) {
      echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //add user
    if (!$stmt->execute()) {
      echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    //get the the userID
    if (!($stmt = $mysqli->prepare("SELECT userID FROM test.users WHERE email = ?"))) {
      echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    if (!$stmt->bind_param("s", $username)) {
      echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
      echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $userID = NULL;

    if (!$stmt->bind_result($userID)) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->fetch();

    mysqli_close($mysqli);
    die('User Added.');
    //header("Location: /cs290-finalProject/content?userID=" . $userID);
  }
}
else {
  die('Bad login info.');
}