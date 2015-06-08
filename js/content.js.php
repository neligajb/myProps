<?php header("Content-type: application/javascript"); ?>

<?php session_start(); ?>

$(document).ready(function() {
  var userID = <?php echo $_SESSION['userID'] ?>;

  var $addPropForm = $('#add-prop-form');
  var $listingData = $('#listing-data');
  var $welcome = $('#welcome');

  getData();

  function getData() {
    $.post('content_data.php', {userID_p: userID}, function (data) {
      if (data == 'could not encode JSON') {
        alert('could not encode JSON');
      }
      else {
        $obj = jQuery.parseJSON(data);
        $welcome.empty();
        $welcome.append("Hi, " + $obj.personalData.name);
        makeListings($obj);
      }
    });
  }


  function makeListings($obj) {
    if ($obj.listingData != null) {
      $listingData.append("<caption>My Favorite Properties</caption>" +
        "<thead><th>Address</th><th>City</th><th>State</th><th>Zip Code</th><th></th><th></th><th></th><th></th></thead><tbody>");
      for (var prop in $obj.listingData) {
        if ($obj.listingData.hasOwnProperty(prop)) {
          var $fullAddress = $obj.listingData[prop]['address'] + ", " + $obj.listingData[prop]['city'] + ", " +
            $obj.listingData[prop]['state'];
          var $sharedByName = $obj.listingData[prop]['sharedByName'];
          if ($sharedByName == null) {
            $sharedByName = '';
          }
          $listingData.append("<tr><td>" + $obj.listingData[prop]['address'] + "</td>" +
            "<td>" + $obj.listingData[prop]['city'] + "</td> <td>" + $obj.listingData[prop]['state'] + "</td>" +
            "<td>" + $obj.listingData[prop]['zip'] + "</td>" +
            "<td><button id='" + $fullAddress + "' class='myButton map-listing' data-toggle='modal' data-target='#myModal'>Map</button>" +
            "<td id='listing-" + $obj.listingData[prop]['listingID'] + "'><button value='" + $obj.listingData[prop]['listingID'] + "' id='" + $obj.personalData.userID + "' class='myButton share-listing'>Share</button>" +
            "</td><td>" + $sharedByName + "</td>" +
            "<td><button id='" + $obj.listingData[prop]['listingID'] + "' class='myButton delete-listing'><img src='imgs/red-x.png' width='25'></button></td></tr>");
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
    var $sharingUserID = $(this).attr('id');
    var $sharedListingID = $(this).attr('value');

    $.post('content_data.php', {getUsers: 'true'}, function(data) {
      $emailList = jQuery.parseJSON(data);
      var $arr = $.map($emailList, function (el) {
        return el;
      });

      var listingTD_ID = "#listing-" + $sharedListingID;

      $(listingTD_ID).append("<form id='share-form'><br><input type='text' id='share-with' placeholder='Enter email address'>" +
      "<input type='button' name='submit-share' class='myButton' id='submit-share' value='&#10144'></form>");


      $("#share-with").autocomplete({
        source: $arr
      });
    });

    var listingTD_ID = "#listing-" + $sharedListingID;

    $(listingTD_ID).on('click', '#submit-share', {param1: $sharingUserID, param2: $sharedListingID}, function(e) {
      var $toBeSharedWithEmail = $('#share-with').val();

      $.post('content_data.php', {sharingUserID: e.data.param1, sharedListingID: e.data.param2, toBeSharedWithEmail: $toBeSharedWithEmail}, function(data) {
        if (data == 'Listing Shared') {
          alert(data);
        }
      });

      $('#share-form').remove();
    });


  });

  $listingData.on('click', '.delete-listing', function() {
    var $listingID = $(this).attr('id');
    $.post('content_data.php', {deleteID: $listingID}, function(data) {
      alert(data);
    });
    $listingData.empty();
    getData();
  });

  var $myModalLabel = $('#myModalLabel');

  $listingData.on('click', '.map-listing', function() {
    var $address = $(this).attr('id');
    $.post('g_maps.php', {addressP: $address}, function(data) {
      $addressObject = jQuery.parseJSON(data);
      if (!($addressObject.status == 'OK')) {
        alert('Could not find the address');
      }
      else {
        var lat = $addressObject.results[0].geometry.location.lat;
        var long = $addressObject.results[0].geometry.location.lng;
        //alert(lat + " " + long);
      }
      $('#map-canvas').empty();
      $myModalLabel.empty();
      $myModalLabel.append($address);
      googleMap(lat, long, $address);

    })
  });

  function googleMap (lat, long, $address) {
    var mapCanvas = document.getElementById('map-canvas');
    var position = new google.maps.LatLng(lat, long) ;
    var mapOptions = {
      center: position,
      zoom: 16,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var marker = new google.maps.Marker({
      position: position,
      title: $address
    });

    var map = new google.maps.Map(mapCanvas, mapOptions);
    marker.setMap(map);

    var infowindow = new google.maps.InfoWindow({
      content: $address
    });

    google.maps.event.addListener(marker, 'click', function() {
      infowindow.open(map, marker);
    });
  }

});