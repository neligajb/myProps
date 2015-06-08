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

  if (!($stmt = $mysqli->prepare("SELECT listingID, address, city, state, zip, sharedByID, sharedByName FROM test.listings WHERE ownerID = ?"))) {
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
  $outSharedByName = NULL;

  if (!$stmt->bind_result($outListingID, $outAddress, $outCity, $outState, $outZip, $outSharedByID, $outSharedByName)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  while ($stmt->fetch()) {
    $row['listingID'] = $outListingID;
    $row['address'] = $outAddress;
    $row['city'] = $outCity;
    $row['state'] = $outState;
    $row['zip'] = $outZip;
    $row['sharedByID'] = $outSharedByID;
    $row['sharedByName'] = $outSharedByName;

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

  //close db connection and build JSON array

  mysqli_close($mysqli);

  $userData = array();
  $userData['personalData'] = $personalData;
  $userData['listingData'] = $listingData;

  if (!$jsonStr = json_encode($userData, JSON_FORCE_OBJECT)) {
    die('could not encode JSON');
  }
  else {
    die($jsonStr);
  }
}


//add listing

else if (isset($_POST["address1"])) {

  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('Bad POST request.');
  }


  //connect to db

  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }

  //sanitize entries
  $address = filter_var($_POST["address1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $city = filter_var($_POST["city1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $state = filter_var($_POST["state1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $zip = filter_var($_POST["zip1"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
  $userID = (int) $_POST["userID1"];

  if ($address == '') {
    mysqli_close($mysqli);
    die('No address');
  }

  //add a new entry

  if (!($stmt = $mysqli->prepare("INSERT INTO test.listings (address, city, state, zip, ownerID) VALUES (?,?,?,?,?)"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("ssssi", $address, $city, $state, $zip, $userID)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  //add listing
  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  mysqli_close($mysqli);

  die('Listing Added');
}


//delete listing

else if (isset($_POST["deleteID"])) {
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }

  //sanitize entry
  $deleteID = filter_var($_POST["deleteID"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);

  if (!($stmt = $mysqli->prepare("DELETE FROM test.listings WHERE listingID = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("i", $deleteID)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  //delete listing
  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  mysqli_close($mysqli);

  die('Listing Deleted');
}


//get list of user emails

else if (isset($_POST["getUsers"]) && $_POST["getUsers"] == 'true') {
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }

  if (!($stmt = $mysqli->prepare("SELECT email FROM test.users"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }


  //get emails list
  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $email = NULL;

  if (!$stmt->bind_result($email)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $emailsList = array();

  while ($stmt->fetch()) {
    array_push($emailsList, $email);
  }

  mysqli_close($mysqli);

  if (!$jsonStr = json_encode($emailsList, JSON_FORCE_OBJECT)) {
    die('could not encode JSON');
  }
  else {
    die($jsonStr);
  }
}

//add shared listing

else if (isset($_POST["sharingUserID"]) && isset($_POST["sharedListingID"]) && isset($_POST["toBeSharedWithEmail"])) {
  $mysqli = new mysqli($db_address, $db_user, $db_password, $db_name);
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    die();
  }

  $sharedListingID = $_POST["sharedListingID"];
  $sharingUserID = $_POST["sharingUserID"];
  $toBeSharedWithEmail = $_POST["toBeSharedWithEmail"];


  //get listing details

  if (!($stmt = $mysqli->prepare("SELECT address, city, state, zip FROM test.listings WHERE listingID = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("i", $sharedListingID)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $outAddress = NULL;
  $outCity = NULL;
  $outState = NULL;
  $outZip = NULL;

  if (!$stmt->bind_result($outAddress, $outCity, $outState, $outZip)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();


  //get sharing user's name

  $stmt = NULL;

  if (!($stmt = $mysqli->prepare("SELECT name FROM test.users WHERE userID = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $sharingUserID)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $outSharedByName = NULL;

  if (!$stmt->bind_result($outSharedByName)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();


  //get userID of user that listing is being shared with

  $stmt = NULL;

  if (!($stmt = $mysqli->prepare("SELECT userID FROM test.users WHERE email = ?"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("s", $toBeSharedWithEmail)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  $outUserID_toBeSharedWith = NULL;

  if (!$stmt->bind_result($outUserID_toBeSharedWith)) {
    echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();

  if ($outUserID_toBeSharedWith == NULL || $outUserID_toBeSharedWith == '') {
    die("User not found.");
  }


  //add listing to table, with ownerID set to the user to be shared with

  $stmt = NULL;

  if (!($stmt = $mysqli->prepare("INSERT INTO test.listings (address, city, state, zip, ownerID, sharedByID, sharedByName) VALUES (?,?,?,?,?,?,?)"))) {
    echo "Prepared statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  if (!$stmt->bind_param("ssssiis", $outAddress, $outCity, $outState, $outZip, $outUserID_toBeSharedWith, $sharingUserID, $outSharedByName)) {
    echo "Binding output params failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  //add listing
  if (!$stmt->execute()) {
    echo "Execute statement failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }

  mysqli_close($mysqli);

  die('Listing Shared');
}