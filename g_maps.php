<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['addressP'])) {
  $address = urlencode($_POST['addressP']);

  $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $address . "&key=AIzaSyDOycp99xpWxtbR-Qno_yDxDJlFulMOGew";


  try {
    $curl = curl_init();

    if (FALSE === $curl)
      throw new Exception('failed to initialize');

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($curl);

    if (FALSE === $result)
      throw new Exception(curl_error($curl), curl_errno($curl));

    curl_close($curl);
    echo($result);
  }
  catch(Exception $e) {

    trigger_error(sprintf(
      'Curl failed with error #%d: %s',
      $e->getCode(), $e->getMessage()),
      E_USER_ERROR);
  }
}