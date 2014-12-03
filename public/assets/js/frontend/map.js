/**
 * Map Javascript File
 * -----------------------------------------------------------------------------
 */

// Resize the page
function resizeMap () {
  var footerHeight = $('footer').height() +
  parseInt($('footer').css('padding-top').replace('px', '')) +
  parseInt($('footer').css('padding-bottom').replace('px', ''));
  if($('body').height() > (500 +  footerHeight)) {
    // Map loading
    $('.home-map, .map-loading, .home-map .map-wrapper, .home-map .map-list').height($('body').height() - footerHeight );
  } else {
    $('.home-map, .map-loading, .home-map .map-wrapper, .home-map .map-list').height(500);
  }
}
resizeMap();
$(window).resize(function(){
  resizeMap();
});

// console.log($('body').height() +":" +(footerHeight + 500));


// Markers
var markers;
var markers_arr = [];

var map;


// On Document Ready
$( document ).ready(function() {

  // Create map
  L.mapbox.accessToken = 'pk.eyJ1IjoiY29kZWZvcmFmcmljYSIsImEiOiJVLXZVVUtnIn0.JjVvqHKBGQTNpuDMJtZ8Qg';
  map = L.mapbox.map('map', 'codeforafrica.ji193j10',{
    zoomAnimationThreshold: 10,
    maxZoom: 15,
    zoomControl: false,
    attributionControl: false
  }).setView([-28.4792625, 24.6727135], 5);
  map.scrollWheelZoom.disable();

  pahali.map.set({'map': map});

  pahali.map.center();

  // Map controls
  $('#map-ctrl-zoom-in').click(function () {
    map.zoomIn();
  });
  $('#map-ctrl-zoom-out').click(function () {
    map.zoomOut();
  });

  // Overlapping markers
  markers = new L.MarkerClusterGroup({
    showCoverageOnHover: false
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
      url: '/api/v1/projects?map=1'
    }).done(function(response) {

      projects_categories = response.projects_categories;
      pahali.map.set({'categories': projects_categories});

      map.removeLayer(markers);

      markers = new L.MarkerClusterGroup({
        showCoverageOnHover: false
      });
      markers_arr = [];

      for (var i = 0; i < response.projects.length; i++) {
        var project = response.projects[i];

        var loc = new L.LatLng(
          project.geo_lat,
          project.geo_lng
        );
        if (project.geo_type == "address" && project.lat && project.lng &&
          project.geo_address.trim() != "" && project.geo_address != null &&
          project.geo_status == 1) {
          loc = new L.LatLng(
            project.lat,
            project.lng
          );
        } else if (project.geo_type == "lat_lng") {
          loc = new L.LatLng(
            project.geo_lat,
            project.geo_lng
          );
        } else {
          continue;
        }

        if (project.title == '' || project.title == null) {
          project.title = '[ No Title ]';
        };

        var marker = new L.Marker(loc);

        var marker_html = '<h6>'+project.title+'</h6>'+
          '<small><a href="/project/'+project.id+'" target="_blank">'+
          'Learn more <span class="fui-arrow-right"></span></a></small>';

        marker.title = project.title;

        marker.bindPopup(marker_html);

        if (!is_touch_device()) {
          marker.on('mouseover', function () {
            this.openPopup();
          });
        };

        markers.addLayer(marker);

        markers_arr.push({
          id: project.id,
          marker: marker
        });
      }

      map.addLayer(markers);

      $('.map-loading').fadeOut();
      listMarkers();

      pahali.map.shareable();

      $(window).on('hashchange', function() {
        pahali.map.shareable();
      });

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
      if (bounds.contains(marker.marker.getLatLng())) {
        inBounds.push(marker.marker.options.title);
      }
    });

    $('#marker-no').html(inBounds.length);
  }


});
