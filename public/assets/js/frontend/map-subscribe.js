/**
 * Subscription for Alerts on Map
 * ---------------------------------------------------------------------------
 */

var map_subcribe_rectangle;
var map_image_link;

// On Document Ready
$( document ).ready(function() {

  // Create Alert Map
  map_subcribe_rectangle = L.rectangle(pahali.map.get('map').getBounds(), {color: "#2ECC71", weight: 1});
  map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/' +
    'geojson(' + encodeURI(JSON.stringify(map_subcribe_rectangle.toGeoJSON())) + ')/' +
    'auto/600x200.png?' +
    'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  $('#map-alert').attr('src', map_image_link);

  // Modal Controls
  function alertsReset() {
    $('.map-alert-email').removeClass('has-error');
    $('#map-alert-email').attr('disabled', false);

    $('#subscriptionModal .alert-info').fadeOut();
    $('#subscriptionModal .alert-success').fadeOut();
    $('#subscriptionModal .alert-warning').fadeOut();
    $('#subscriptionModal .alert-danger').fadeOut();
    
    $('.create-alert-btn').removeClass('disabled');
  }
  $('#subscriptionModal').on('show.bs.modal', function () {
    if (pahali.map.get('map').getZoom() < 11) {
      $('#modal-subscribe-error').modal('show')
      return false;
    };
  });
  $('#subscriptionModal').on('shown.bs.modal', function () {
    map_subcribe_rectangle = L.rectangle(map.getBounds(), {color: "#2ECC71", weight: 1});
    var map_image_link = 'https://api.tiles.mapbox.com/v4/codeforafrica.ji193j10/' +
      'geojson(' + encodeURI(JSON.stringify(map_subcribe_rectangle.toGeoJSON())) + ')/' +
      'auto/600x200.png?' +
      'access_token=pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
    $('#map-alert').attr('src', map_image_link);

    // Reset
    alertsReset();
  });

  // Modal View Controls
  $('.create-alert-btn').click(function () {
    alertsReset();
    // Check e-mail
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
    // Subscription start
    $('#subscriptionModal .alert-info').fadeIn();
    $('.create-alert-btn').addClass('disabled');
    $('#map-alert-email').attr('disabled', true);
    var bounds = map.getBounds();
    var bound = bounds._southWest.lat + "," + bounds._southWest.lng + "," +
      bounds._northEast.lat + "," + bounds._northEast.lng;
    var center = map.getCenter().lng + "," + map.getCenter().lat;
    var data = {
      type: 'map',
      email: $('#map-alert-email').val().trim(),
      bounds: bound,
      center: center,
      zoom: map.getZoom(),
      geojson: JSON.stringify(map_subcribe_rectangle.toGeoJSON()),
      _token: pahali.csrf_token 
    };
    $.ajax({
      type: "POST",
      url: "/api/v1/subscriptions",
      data: data
    }).done(function(response) {

      if(response.status == 'OK') {
        $('#subscriptionModal .alert-success').fadeIn();
      };
      if(response.status == 'OVER_LIMIT') {
        $('#subscriptionModal .alert-danger').fadeIn();
        $('#subscriptionModal .alert-danger .msg-error.limit').fadeIn();
      };
      if(response.error) {
        var validator = response.validator;
        if (validator.email) {
          $('#subscriptionModal .alert-danger').fadeIn();
          $('#subscriptionModal .alert-danger .msg-error.email').fadeIn();
          $('#map-alert-email').attr('disabled', false);
          $('.map-alert-email').addClass('has-error');
          $('.create-alert-btn').removeClass('disabled');
        };
        if (validator.bounds) {
          $('#subscriptionModal .alert-danger').fadeIn();
          $('#subscriptionModal .alert-danger .msg-error.reload').fadeIn();
        };
        if (validator.confirm_token) {
          $('#subscriptionModal .alert-warning').fadeIn();
          $('#subscriptionModal .alert-warning .msg-error.duplicate').fadeIn();
        };
      };

    }).fail(function() {
      // report error
      $('#subscriptionModal .alert-danger').fadeIn();
      $('#subscriptionModal .alert-danger .msg-error.reload').fadeIn();
    }).always(function() {
      $('#subscriptionModal .alert-info').fadeOut();
    });
  });

});
