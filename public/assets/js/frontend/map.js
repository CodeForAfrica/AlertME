/**
 * Map Javascript File
 * -----------------------------------------------------------------------------
 */

// Resize the page
var footerHeight = $('footer').height() +
  parseInt($('footer').css('padding-top').replace('px', '')) +
  parseInt($('footer').css('padding-bottom').replace('px', ''));
if($('body').height() > (400 +  footerHeight)) {
  $('.home-map').css('margin-bottom', '-'+footerHeight+'px');
  $('.home-map').css('min-height', (400 + footerHeight)+'px');
  // Map loading
  $('.map-loading, .home-map .map-wrapper, .home-map .map-list').height($('.home-map').height() - footerHeight );
} else {
  $('.map-loading').height($('.home-map').height());
}

console.log($('body').height() +":" +(footerHeight + 500));

// On Document Ready
$( document ).ready(function() {

  // Create map
  L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  var map = L.mapbox.map('map', 'codeforafrica.ji193j10',{
    zoomAnimationThreshold: 10,
    maxZoom: 15,
    zoomControl: false,
    attributionControl: false
  }).setView([-28.4792625, 24.6727135], 5);
  map.scrollWheelZoom.disable();

  // Map controls
  $('#map-ctrl-zoom-in').click(function () {
    map.zoomIn();
  });
  $('#map-ctrl-zoom-out').click(function () {
    map.zoomOut();
  });

  // Overlapping markers
  var markers = new L.MarkerClusterGroup({
    showCoverageOnHover: false
  });
  var markers_arr = [];

  // Map events
  map.on('zoomend, moveend', function(e) {
    // Create shareable link
    var loc_bounds = map.getBounds();
    window.location.hash = "#!/bounds="+
    loc_bounds._southWest.lat+","+loc_bounds._southWest.lng+","+
    loc_bounds._northEast.lat+","+loc_bounds._northEast.lng;

    listMarkers();
  });



  /**
   * Load Markers
   * ---------------------------------------------------------------------------
   */
  function loadMarkers () {

    var bounds = map.getBounds();
    var bound = bounds._southWest.lat + "," + bounds._northEast.lat + "," +
      bounds._southWest.lat + "," + bounds._northEast.lng;

    $.ajax({
      type: "GET",
      url: '/api/v1/projectsgeojson?bounds='+bound
    }).done(function(response) {

      map.removeLayer(markers);

      markers = new L.MarkerClusterGroup({
        showCoverageOnHover: false
      });
      markers_arr = [];

      for (var i = 0; i < response.features.length; i++) {
        var datum = response.features[i];
        var loc = new L.LatLng(
          datum.geometry.coordinates[1],
          datum.geometry.coordinates[0]
        );
        var marker = new L.Marker(loc);
        marker.title = datum.properties.title;
        marker.bindPopup(marker.title);
        markers.addLayer(marker);

        markers_arr.push(marker);
      }

      map.addLayer(markers);

      $('.map-loading').fadeOut();
      listMarkers();

    });

  }
  loadMarkers();



  /**
   * List Markers in view
   * ---------------------------------------------------------------------------
   */
  function listMarkers () {
    // Construct an empty list to fill with onscreen markers.
    var inBounds = [],
    // Get the map bounds - the top-left and bottom-right locations.
      bounds = map.getBounds();

    $.each(markers_arr, function( index, marker ) {
      if (bounds.contains(marker.getLatLng())) {
        inBounds.push(marker.options.title);
      }
    });

    $('#marker-no').html(inBounds.length);
  }



  /**
   * Map link
   * ---------------------------------------------------------------------------
   */
  if(getUrlParameters("center", "", true) != false){
    var map_ctr = getUrlParameters("center", "", true).split(",");
    var map_zoom = getUrlParameters("zoom", "", true);
    map.setView([map_ctr[0], map_ctr[1]], map_zoom);
  }
  if(getUrlParameters("bounds", "", true) != false){
    var map_bounds = getUrlParameters("bounds", "", true).split(",");
    map.fitBounds([[map_bounds[0], map_bounds[1]],[map_bounds[2], map_bounds[3]]]);
  }



  /**
   * Subscription for Alerts
   * ---------------------------------------------------------------------------
   */

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
  $('#alertModal').on('shown.bs.modal', function () {
    map_alert.invalidateSize();
    map_alert.fitBounds(map.getBounds());
    // Reset
    $('.map-alert-email').removeClass('has-error');
    $('#alertModal .alert-danger').fadeOut();
    $('#alertModal .alert-danger .msg-error.email').fadeOut();
    $('#alertModal .alert-info').fadeOut();
    $('.create-alert-btn').removeClass('disabled');
    $('#alertModal .alert-success').fadeOut();
    $('#map-alert-email').attr('disabled', false);
  });
  $('.create-alert-btn').click(function () {
    if( $('#map-alert-email').val().trim() == '' ) {
      $('.map-alert-email').addClass('has-error');
      $('#alertModal .alert-danger').fadeIn();
      $('#alertModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    if(!isEmail($('#map-alert-email').val())) {
      $('.map-alert-email').addClass('has-error');
      $('#alertModal .alert-danger').fadeIn();
      $('#alertModal .alert-danger .msg-error.email').fadeIn();
      return;
    }
    $('#alertModal .alert-info').fadeIn();
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
      url: "/api/v1/alertregistration",
      data: data
    }).done(function(response) {

      if(response.status == 'OK') {
        $('#alertModal .alert-success').fadeIn();
      }
      if(response.status == 'OVER_LIMIT') {
        $('#alertModal .alert-danger').fadeIn();
        $('#alertModal .alert-danger .msg-error.limit').fadeIn();
      }

    }).fail(function() {
      // report error
      $('#alertModal .alert-danger').fadeIn();
      $('#alertModal .alert-danger .msg-error.reload').fadeIn();
    }).always(function() {
      $('#alertModal .alert-info').fadeOut();
    });
  });

});
