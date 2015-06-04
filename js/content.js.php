<?php header("Content-type: application/javascript"); ?>

<?php session_start(); ?>

$(document).ready(function() {
  var userID = <?php echo $_SESSION['userID'] ?>;

  var $addPropForm = $('#add-prop-form');

  $.post('content_data.php', {userID_p: userID}, function (data) {
    if (data == 'shit') {
      alert('shit');
    }
    else {
      $obj = jQuery.parseJSON(data);
      $('#welcome').append($obj.personalData.name);
    }
  });

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

    $.post('content_data.php', {userID1: $obj.personalData.userID, address1: addressP, city1: cityP, state1: stateP, zip1: zipP}, function(data) {
      alert(data);
      if (data == 'Listing Added') {
        $('#dyn-prop-form').remove();
      }
    });
  });

});