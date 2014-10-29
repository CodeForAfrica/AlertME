/**
 * Subscription for Alerts on Map
 * ---------------------------------------------------------------------------
 */

// On Document Ready
$( document ).ready(function() {

  // Create Alert Map
  var map_alert = L.mapbox.map('map-alert', 'codeforafrica.ji193j10',{
    zoomAnimationThreshold: 10,
    maxZoom: 20,
    zoomControl: false,
    attributionControl: false
  }).setView([-28.4792625, 24.6727135], 5);

  // Disable drag and zoom handlers.
  map_alert.dragging.disable();
  map_alert.touchZoom.disable();
  map_alert.doubleClickZoom.disable();
  map_alert.scrollWheelZoom.disable();

  // Disable tap handler, if present.
  if (map_alert.tap) map_alert.tap.disable();

  // Modal Controls
  $('#subscriptionModal').on('shown.bs.modal', function () {
    map_alert.invalidateSize();
    map_alert.fitBounds(map.getBounds());
    // Reset
    $('.map-alert-email').removeClass('has-error');
    $('#subscriptionModal .alert-danger').fadeOut();
    $('#subscriptionModal .alert-danger .msg-error.email').fadeOut();
    $('#subscriptionModal .alert-info').fadeOut();
    $('.create-alert-btn').removeClass('disabled');
    $('#subscriptionModal .alert-success').fadeOut();
    $('#map-alert-email').attr('disabled', false);
  });
  $('.create-alert-btn').click(function () {
    if( $('#map-alert-email').val().trim() == '' ) {
      $('.map-alert-email').addClass('has-error');
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    if(!isEmail($('#map-alert-email').val())) {
      $('.map-alert-email').addClass('has-error');
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    $('#subscriptionModal .alert-info').fadeIn();
    $('.create-alert-btn').addClass('disabled');
    $('#map-alert-email').attr('disabled', true);
    var bounds = map.getBounds();
    var bound = bounds._southWest.lat + "," + bounds._southWest.lng + "," +
      bounds._northEast.lat + "," + bounds._northEast.lng;
    var data = {
      email: $('#map-alert-email').val().trim(),
      bounds: bound,
      _token: csrf_token
    };
    $.ajax({
      type: "POST",
      url: "/api/v1/subscriptions",
      data: data
    }).done(function(response) {

      if(response.status == 'OK') {
        $('#subscriptionModal .alert-success').fadeIn();
      }
      if(response.status == 'OVER_LIMIT') {
        $('#subscriptionModal .alert-danger').fadeIn();
        $('#subscriptionModal .alert-danger .msg-error.limit').fadeIn();
      }

    }).fail(function() {
      // report error
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.reload').fadeIn();
    }).always(function() {
      $('#subscriptionModal .alert-info').fadeOut();
    });
  });

});
