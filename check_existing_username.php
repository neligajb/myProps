<?php
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

function isEmail($n) {
  return preg_match('/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/', $n);
}


if (isset($_POST["username1"]) && isset($_POST["password1"])) {
  global $db_password;
  global $db_address;
  global $db_user;
  global $db_name;

  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('Bad POST request.');
  }

  //check if valid email
  if (!isEmail($_POST["username1"]) || strlen($_POST["username1"]) > 50) {
    die('Invalid Email');
  }

  //check if valid password
  if (strlen($_POST["password1"]) < 6 || strlen($_POST["password1"]) > 20) {
    die('Invalid password');
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

  if (!$username_exists) {
    mysqli_close($mysqli);
    die('No account associated with this email address.');
  }
  else { //check password
    $stmt = NULL;
    $original_userID = $username_exists;
    if (!($stmt = $mysqli->prepare("SELECT userID FROM users WHERE userID = ? AND password = ?"))) {
      echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    if (!$stmt->bind_param("is", $original_userID, $password)) {
      echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!$stmt->execute()) {
      echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $matches = array();
    $match = NULL;

    if (!$stmt->bind_result($match)) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    while ($stmt->fetch()) {
      array_push($matches, $match);
    }

    mysqli_close($mysqli);


    if (count($matches) < 1) {
      //echo ($original_userID . ' ' . $password . ' ' . $match . ' ');
      die('Invalid password');
    }
    else {
      ini_set('session.save_path', '../sessions');
      session_start();
      $_SESSION['userID'] = $original_userID;
      echo('login');
    }
  }
}

else {
  die('Bad POST request.');
}