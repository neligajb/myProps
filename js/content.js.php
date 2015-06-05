<?php header("Content-type: application/javascript"); ?>

<?php session_start(); ?>

$(document).ready(function() {
  var userID = <?php echo $_SESSION['userID'] ?>;

  var $addPropForm = $('#add-prop-form');
  var $listingData = $('#listing-data');

  getData();

  function getData() {
    $.post('content_data.php', {userID_p: userID}, function (data) {
      if (data == 'shit') {
        alert('shit');
      }
      else {
        $obj = jQuery.parseJSON(data);
        $('#welcome').append($obj.personalData.name);
        makeListings($obj);
      }
    });
  }


  function makeListings($obj) {
    if ($obj.listingData != null) {
      $listingData.append("<caption>My Favorite Properties</caption>" +
        "<thead><th>Address</th><th>City</th><th>State</th><th>Zip Code</th><th>Share</th><th></th></thead><tbody>");
      for (var prop in $obj.listingData) {
        if ($obj.listingData.hasOwnProperty(prop)) {
          var $sharedByName = $obj.listingData[prop]['sharedByName'];
          if ($sharedByName == null) {
            $sharedByName = '';
          }
          $listingData.append("<tr><td>" + $obj.listingData[prop]['address'] + "</td>" +
            "<td>" + $obj.listingData[prop]['city'] + "</td> <td>" + $obj.listingData[prop]['state'] + "</td>" +
            "<td>" + $obj.listingData[prop]['zip'] +
            "</td><td><button id='" + $obj.personalData.userID + "' class='myButton share-listing'>Share</button>" +
            "</td><td>" + $sharedByName + "</td></tr>");
        }
      }
      $listingData.append("</tbody>");
    }
  }


  $('#add-prop').on('click', function () {
    $addPropForm.append("<form id='dyn-prop-form' ><input type='text' id='new-address' maxlength='50' placeholder='Address'>" +
      "<input type='text' id='new-city' maxlength='50' placeholder='City'>" +
      "<input type='text' id='new-state' maxlength='2' placeholder='State'>" +
      "<input type='text' id='new-zip' maxlength='5' placeholder='Zip'>" +
      "<input type='button' name='submit-prop' class='myButton' id='submit-prop' value='Submit'></form>");
  });

  $addPropForm.on('click', '#submit-prop', function() {
    var addressP = $('#new-address').val();
    var cityP = $('#new-city').val();
    var stateP = $('#new-state').val();
    var zipP = $('#new-zip').val();

    if (addressP == '') {
      alert('Entries must have an address');
      return;
    }

    if(!(Math.floor(zipP) == zipP && $.isNumeric(zipP)) || zipP.length < 5) {
      alert('Please enter a 5 digit numerical zip code');
      return;
    }

    $.post('content_data.php', {userID1: $obj.personalData.userID, address1: addressP, city1: cityP, state1: stateP, zip1: zipP}, function(data) {
      alert(data);
      if (data == 'Listing Added') {
        $('#dyn-prop-form').remove();
        $listingData.empty();
        getData();
      }
    });
  });

  $listingData.on('click', '.share-listing', function() {
    $.post('content_data.php', {shareID: $('.share-listing').attr('id')}, function(data) {
      alert('Sharing Functionality coming soon!');
    });
  });

});