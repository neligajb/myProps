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
  echo('die');
  die();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>MyProps :: Welcome</title>
  <link rel="stylesheet" href="styles.css" >
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/content.js"></script>
</head>
  <body id="userID" user="<?php echo $_SESSION['userID'] ?>">
    <div class="container-fluid">
      <header>
        <form action="logout.php" method="post"><input type="submit" value="Logout" class="myButton"></form>
        <h1 class="title">MyProps</h1>
      </header>
      <h2 id="welcome"></h2>
      <form>
        <input type="button" name="add-prop" class="myButton" id="add-prop" value="Add Property">
      </form>
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body" id="map-canvas">
              ...
            </div>
          </div>
        </div>
      </div>
      <div id="add-prop-form">
        <!-- dynamic form -->
      </div>
      <table class="table table-hover" id="listing-data">
        <!-- dynamic content -->
      </table>
    </div>
  </body>

</html>
