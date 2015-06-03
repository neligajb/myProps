<?php
include 'dbInfo.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["userID_p"])) {

  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('Bad POST request.');
  }

  //connect to db

  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }


  //get user's listing data from db

  if (!($stmt = $mysqli->prepare("SELECT listingID, address, city, state, zip, sharedByID FROM test.listings WHERE ownerID = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $_POST["userID_p"])) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $row = array();
  $listingData = array();

  $outListingID = NULL;
  $outAddress = NULL;
  $outCity = NULL;
  $outState = NULL;
  $outZip = NULL;
  $outSharedByID = NULL;

  if (!$stmt->bind_result($outListingID, $outAddress, $outCity, $outState, $outZip, $outSharedByID)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  while ($stmt->fetch()) {
    $row['listingID'] = $outListingID;
    $row['address'] = $outAddress;
    $row['city'] = $outCity;
    $row['state'] = $outState;
    $row['zip'] = $outZip;
    $row['sharedByID'] = $outSharedByID;

    array_push($listingData, $row);
  }

  // get user's personal data from db

  $stmt = NULL;

  if (!($stmt = $mysqli->prepare("SELECT userID, email, name FROM test.users WHERE userID = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $_POST["userID_p"])) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $outUserID = NULL;
  $outEmail = NULL;
  $outName = NULL;

  if (!$stmt->bind_result($outUserID, $outEmail, $outName)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $personalData = array();

  while ($stmt->fetch()) {
    $personalData['userID'] = $outUserID;
    $personalData['email'] = $outEmail;
    $personalData['name'] = $outName;
  }

  mysqli_close($mysqli);

  $userData = array();
  $userData['personalData'] = $personalData;
  $userData['listingData'] = $listingData;

  if (!$jsonStr = json_encode($userData, JSON_FORCE_OBJECT)) {
    die('shit');
  }
  else {
    die($jsonStr);
  }
}